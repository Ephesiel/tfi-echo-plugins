<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage connection with the server of echo in sftp
 *
 * @since 1.0.0
 */
class FtpManager {
    private $conn_id;
    private $stfp;

    /**
     * Push_echo_datas.
     * 
     * Push new datas to the echo server.
     * $new_files should only contains files which changed from the last push.
     * $non_file_values in opposite, should be null if nothing change but should contains all echo non file values, because it is stored to a json which is rewrite. 
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param Campain   $campain            The campain choose by the user
     * @param Template  $template           The template choose by the user
     * @param array     $new_files          All new files, keys are echo field name and values are the tfi upload directory path for the file. Default array()
     * @param array     $non_file_values    All non file values, keys are echo field name and values are the values. If null is given, nothing will be push. Default null
     */
    public function push_echo_datas( $campain, $template, $new_files = array(), $non_file_values = null ) {
        $template_folder = $this->get_echo_folder( $campain, $template );

        if ( $this->connect() ) {
            foreach ( $this->get_file_to_upload( $new_files, $campain->owner ) as $file ) {
                $src_url = $file['src_file'];

                // Remove the echo_ in front of the name
                $remote_name    = substr( basename( $src_url ), 5 );
                // Remove the field name to only have the directory
                $remote_dir     = $template_folder . $file['dst_folder'];
                $remote_url     = 'ssh2.sftp://' . intval( $this->sftp ) . '/' . $remote_dir . '/' . $remote_name;
        
                // Create the folder first
                ssh2_sftp_mkdir( $this->sftp, $remote_dir, 0750, true );

                // $remote_url = pathinfo( $src_url, PATHINFO_DIRNAME ) . '/test_' . pathinfo( $src_url, PATHINFO_BASENAME );

                // Copy file content on serveur
                $res_file = fopen( $remote_url, 'w' );
                $src_file = fopen( $src_url, 'r' );
                $written_bytes = stream_copy_to_stream( $src_file, $res_file );
                fclose( $res_file );
                fclose( $src_file );

                if ( $written_bytes === false ) {
                    error_log( 'failed to write bytes from file ' . $src_url . ' to ' . $remote_url );
                }
            }

            if ( is_array( $non_file_values ) ) {
                foreach ( $non_file_values as $field_name => $file_value ) {
                    // Remove the echo_ in front of the name
                    $non_file_values[substr( $field_name, 5 )] = $file_value;
                    unset( $non_file_values[$field_name] );
                }

                $json = fopen( 'ssh2.sftp://' . intval( $this->sftp ) . '/' . $template_folder . '/values.json', 'w' );
                fwrite( $json, json_encode( $non_file_values ) );
                fclose( $json );
            }
        }
        else {
            error_log( 'Impossible to access to the server in ftp' );
        }

        $this->disconnect();
    }

    /**
     * Remove_echo_datas.
     * 
     * Remove data inside the echo server
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param Campain   $campain    The campain to remove
     * @param Template  $template   The template to eventually remove too.
     */
    public function remove_echo_datas( $campain, $template = null ) {
        $template_folder = $this->get_echo_folder( $campain, $template );

        if ( $this->connect() ) {
            $path = 'ssh2.sftp://' . intval( $this->sftp ) . '/' . $template_folder;
            $test = function( $name ) use ( &$test ) {
                if ( is_dir( $name ) ) {
                    foreach ( scandir ( $name ) as $file ) {
                        if ( $file !== '.' && $file != '..' ) {
                            $test( $name . '/' . $file );
                        }
                    }
                    rmdir( $name );
                }
                else if ( is_file( $name ) ) {
                    unlink( $name );
                }
            };
            $test( $path );
        }

        $this->disconnect();
    }

    /**
     * Get_echo_folder.
     * 
     * Return the folder inside the echo server for this campain and template if given.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @param Campain   $campain    The campain for the folder to return
     * @param Template  $template   If given, the folder returns will include the template
     * 
     * @return string   The folder path inside the server
     */
    private function get_echo_folder( $campain, $template = null ) {
        $folder = 'echo/' . get_user_by( 'id', $campain->owner->id )->user_nicename . '-' . $campain->owner->id . '/' . $campain->id;

        if ( $template != null ) {
            $folder .= '/' . $template->id;
        }

        return $folder;
    }

    /**
     * Get_file_to_upload.
     * 
     * This is a method create to factorization.
     * It's purpose is to add all files in an array with 2 entries :
     * - src_file   => The source path where the file is on the server
     * - dst_folder => The folder inside the echo server where the file will be copy
     * 
     * @since 1.0.0
     * @access private
     * 
     * @param array     $files  Keys are echo field names and values are the path of the file
     * @param \TFI\User $user   This user is used to get the folder where the file should be placed in the echo server
     * 
     * @return array            The array with all files src and dest to copy on echo server
     */
    private function get_file_to_upload( $files, $user ) {
        $files_to_upload = array();

        if ( ! empty( $files ) ) {
            require_once TFI_PATH . 'includes/file-manager.php';
            require_once ECHO_PATH . 'includes/fields-manager.php';

            $file_manager = new \TFI\FileManager;

            $register_file_value = function( $file_value, $field ) use ( &$register_file_value, &$files_to_upload, &$file_manager, &$user ) {
                if ( $field->is_multiple() ) {
                    foreach ( $file_value as $sub_value ) {
                        $register_file_value( $sub_value, $field->get_field_for_index( 0 ) );
                    }
                }
                else if ( is_string( $file_value ) ) {
                    $folders = explode( '/', $field->get_folder_path_from_user( $user, false ) );
                    $src = $file_manager->get_file_link( $file_value, false );

                    // We need to remove the 2 first folders. The first is the user name, and the second is the echo folder.
                    if ( count ( $folders ) >= 2 && ! empty( $src ) ) {
                        unset( $folders[0] );
                        unset( $folders[1] );

                        $folder = '/' . implode( '/', $folders );
                        $files_to_upload[] = array(
                            'src_file' => $src,
                            'dst_folder' => $folder
                        );
                    }
                }
            };

            foreach ( $files as $field_name => $file_value ) {
                $register_file_value( $file_value, FieldsManager::get_echo_field_objects()[$field_name] );
            }
        }

        return $files_to_upload;
    }

    /**
     * Connect.
     * 
     * Connect to the echo server in sftp
     * 
     * @since 1.0.0
     * @access private
     * 
     * @return bool     The success of the operation
     */
    private function connect() {
        // Connect to the host in ftp
        $this->conn_id = ssh2_connect( 'vps-a377a376.vps.ovh.net', 2222 );

        if ( ! $this->conn_id ) {
            error_log( 'SSH connection to the server failed' );
            return false;
        }
        
        // Identification with namme and password
        $login_result = ssh2_auth_password( $this->conn_id, 'irje7156', 'Zszu8Uw)YeRf*<;d' );

        // Creation of the SSH2 SFTP resource
        $this->sftp = ssh2_sftp( $this->conn_id );

        return $login_result && $this->sftp;
    }

    /**
     * Disconnect.
     * 
     * Disconnect the sftp connection
     * 
     * @since 1.0.0
     * @access private
     */
    private function disconnect() {
        // Close the connection
        ssh2_exec( $this->conn_id, 'exit' );
        $this->conn_id = null;
        $this->sftp = null;
    }
}