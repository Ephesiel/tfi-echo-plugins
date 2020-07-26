<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage everything about campains and template of a user
 *
 * @since 1.0.0
 */
class CampainsManager {

    /**
     * User.
     * 
     * User which will be used to know campains and templates
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var TFI\User|null	Null if the given user cannot access intranet
     */
	private $user;

    /**
     * Campains.
     * 
	 * All user campains
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var array
     */
    private $campains;

    /**
     * Template_settings.
     * 
	 * An array with a template settings
     * 
     * 'campain'    => The choosen campain
     * 'template'   => The choosen template
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var array
     */
    private $template_settings;

    /**
     * New_template_setting.
     * 
     * This is a boolean which keep in mind if the template setting changed recently
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var bool
     */
    private $new_template_setting;

    /**
     * Campainsmanager constructor
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param TFI\User $user	The user to get campain from.
     */
	public function __construct( $user ) {
        if ( $user->is_ok() ) {
            $this->user = $user;
            $this->new_template_setting = false;

            require_once ECHO_PATH . 'includes/fields-manager.php';

            // Send values in sftp to the echo server
            add_action( 'tfi_user_datas_changed', array( $this, 'on_datas_changed' ), 10, 2 );
            // Change the file folder of each echo file fields to put them into a campain/template folder
            add_filter( 'tfi_field_file_path', array( $this, 'update_echo_data' ), 10, 3 );
        }
    }

    /**
     * On_datas_changed.
     * 
     * This method is called when the tfi_user_dates_changed and will update the template json value
     * 
     * @since 1.0.0
     * @access private
     * 
     * @param \TFI\User $user   Current user (same than $this->user)
     * @param array     $fields Contains all fields which changed
     */
    public function on_datas_changed( $user, $fields ) {
        /**
         * If the update is due to a template settings changement, we don't want to rewrite the template file
         */
        if ( $this->new_template_setting === true ) {
            $this->new_template_setting = false;
            return;
        }

        $campain            = $this->get_template_settings()['campain'];
        $template           = $this->get_template_settings()['template'];
        $echo_fields        = FieldsManager::get_echo_fields_name();
        $values             = $template->get_values();
        $updated_files      = array();
        $non_file_values    = null;
        $non_file_updated   = false;

        foreach ( $fields as $field ) {
            if ( in_array( $field->name, $echo_fields ) ) {
                $value = $field->get_value_for_user( $user, 'upload_path' );
                $values[$field->name] = $value;

                if ( $field->is_multiple_file() ) {
                    $updated_files[$field->name] = $value;
                }
                else {
                    $non_file_updated = true;
                    $non_file_values  = array();
                }
            }
        }

        if ( $non_file_updated ) {
            foreach ( $template->get_values() as $field_name => $value ) {
                if ( ! FieldsManager::get_echo_field_objects()[$field_name]->is_multiple_file() ) {
                    $non_file_values[$field_name] = $value;
                }
            }
        }

        $template->update_values( $values );
        
        require_once ECHO_PATH . 'includes/ftp-manager.php';
        $ftp_manager = new FtpManager;
        $ftp_manager->push_echo_datas( $this->user, $campain, $template, $updated_files, $non_file_values );
    }

    /**
     * Update_echo_data.
     * 
     * This method is connected with the tfi_field_file_path_$fieldname hook and is called for every echo field.
     * It will return the value of the path according to the choosen templates.
     * It will allows to have multiple echo image for the same field !
     * The new path will be user_name/echo/campain/template/...
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string        $value      The path of the folder which will be modify
     * @param \TFI\User     $user       Current user (same than $this->user)
     * @param \TFI\Field    $field      The path of the folder which will be modify
     * @return string                   The new path for the file
     */
    public function update_echo_data( $value, $user, $field ) {
        $parent = $field->get_oldest_parent();
        if ( ! in_array( $parent->name, FieldsManager::get_echo_fields_name() ) || ! FieldsManager::get_echo_field_objects()[$parent->name]->is_multiple_file() ) {
            return $value;
        }

        $settings       = $this->get_template_settings();
        $echo_folder    = tfi_get_user_file_folder_path( $this->user->id, 'echo', false );
        $new_folder     = $echo_folder . '/' . $settings['campain']->id . '/' . $settings['template']->id;
        $new_value;

        if ( is_array( $value ) ) {
            foreach ( $value as $key => $subvalue ) {
                $new_value[$key] = $new_folder . substr( $subvalue, strlen( $echo_folder ) );
            }
        }
        else {
            $new_value = $new_folder . substr( $value, strlen( $echo_folder ) );
        }
        
        return $new_value;
    }

    /**
     * Set_template_settings.
     * 
     * This method will update the database to store which template is the last choosen by the user
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param Campain   $campain    The choosen campain  
     * @param Template  $template   The choosen template
     * 
     * @global wpdb     $wpdb       The database object to update the datas
     */
    public function set_template_settings( $campain, $template ) {
		if ( $this->user === null ) {
			return;
        }

        // No need to update the database if the values are the same
        if ( $this->template_settings !== null ) {
            if ( $this->template_settings['campain']->id === $campain->id && $this->template_settings['template']->id === $template->id ) {
                return;
            }
        }

        global $wpdb;
        $table = $wpdb->prefix . ECHO_TABLE;

        $wpdb->query(
            "INSERT INTO {$table} (user_id, campain_id, template_id)
                VALUES ({$this->user->id}, '{$campain->id}', '{$template->id}')
                ON DUPLICATE KEY UPDATE
                    campain_id = VALUES(campain_id),
                    template_id = VALUES(template_id);"
        );

        $this->template_settings = array(
            'campain' => $campain,
            'template' => $template
        );

        /**
         * We set this value to true because we don't need to reupdate the template on user_datas_changed because we just push template values
         * Note: this is because the hook tfi_user_datas_changed is called by the method User::set_values_for_fields.
         * The on_datas_changed method will reset this value to false when pass
         */
        $this->new_template_setting = true;
        $this->user->set_values_for_fields( $template->get_values() );
    }

    /**
     * Get_template_settings.
     * 
     * This method will get the settings for the template choose by the user
     * If no settings are set, it will search in the database
     * If there is nothing in the database, it will return the default values
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return array                The template settings choose by the user
     * 
     * @global wpdb     $wpdb       The database object to select the datas
     */
    public function get_template_settings() {
		if ( $this->user === null ) {
			return array();
        }

        if ( $this->template_settings === null ) {
            global $wpdb;
            $table = $wpdb->prefix . ECHO_TABLE;
    
            $result = $wpdb->get_row( "SELECT campain_id, template_id FROM {$table} WHERE user_id = {$this->user->id}" );

            $campain = false;
            $template = false;

            // We try to get the campain
            if ( $result !== null ) {
                $campain = $this->get_campain( $result->campain_id );
            }

            // If the campain doesn't exist, get the default one
            if ( $campain === false ) {
                $campain = $this->get_default_campain();
            }
            // Only get the template if this is the not the default campain
            else if ( $result !== null ) {
                $template = $campain->get_template( $result->template_id );
            }

            // If the template is still false, we get the default one
            if ( $template === false ) {
                $template = $campain->get_default_template();
            }

            $this->set_template_settings( $campain, $template );
        }

        return $this->template_settings;
    }

	/**
	 * Get_campains.
	 * 
	 * Return all campains already create by this user
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @return array	All campains for this user
	 */
	public function get_campains() {
		if ( $this->user === null ) {
			return array();
		}

		if ( $this->campains === null ) {
			require_once ECHO_PATH . 'includes/campain.php';

			$folders = glob( $this->campain_user_dir() . '*', GLOB_ONLYDIR );
			$this->campains = array();

			foreach ( $folders as $folder ) {
				$campain = new Campain( $folder );
				$this->campains[$campain->id] = $campain;
			}
		}

		return $this->campains;
	}

	/**
	 * Get_campain.
	 * 
	 * Return the campain if it exists, else return false
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @param string $campain_id	The wanted campain id
	 * @return Campain				The wanted campain
	 * @return false				If this campain doesn't exist
	 */
	public function get_campain( $campain_id ) {
		if ( $this->user === null ) {
			return false;
		}

		foreach ( $this->get_campains() as $existing_campain ) {
			if ( $existing_campain->id === $campain_id ) {
				return $existing_campain;
			}
		}

		return false;
	}

    /**
     * Get_default_campain.
     * 
     * Return the campain by default for this user
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return Campain  The default campain
     */
    public function get_default_campain() {
        $campains = $this->get_campains();
        
        // Create a new campain name default if this user has no campain
        if ( empty( $campains ) ) {
            return $this->create_campain( 'default' );
        }

        // Or choose the first one
        return array_values( $campains )[0];
    }

	/**
	 * Create_campain.
	 * 
	 * Create a campain if the id doesn't exist
	 * Return the existing one if it already exists
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @param string $campain_id	The wanted campain id
	 * @return Campain				The wanted campain
	 * @return false				If the user cannot create campain
	 */
	public function create_campain( $campain_id ) {
		if ( $this->user === null ) {
			return false;
        }
        
        // Sanitize the name
        $campain_id = preg_replace( '/[^a-z0-9_-]/', '', str_replace( ' ', '_', strtolower( $campain_id ) ) );
		$campain = $this->get_campain( $campain_id );

        // Create the campin if it doesn't exist
		if ( $campain === false && ! empty( $campain_id ) ) {
			$dir = $this->campain_user_dir() . $campain_id;
			wp_mkdir_p( $dir );

			$campain = new Campain( $dir );
			$this->campains[$campain->id] = $campain;
		}
		
		return $campain;
	}

    /**
     * Delete_campain.
     * 
     * Delete a specific campain that the user want to delete
     * /!\ All files will be deleted for ever, including templates for this campain !!!!
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $campain_id The id of the campain to remove
     */
	public function delete_campain( $campain_id ) {
        if ( array_key_exists( $campain_id, $this->get_campains() ) ) {
            tfi_delete_files( $this->campains[$campain_id]->campain_dir );
            unset( $this->campains[$campain_id] );
        }
	}

	/**
	 * Campain_user_dir.
	 * 
	 * Return the directory of the user inside the echo campain folder.
	 * Campains are stored here.
	 * 
	 * @since 1.0.0
	 * @access private
	 * 
	 * @return string	The path for the current user
	 * @return false	If an error occured
	 */
	private function campain_user_dir() {
		if ( $this->user === null || ! defined( 'ECHO_CAMPAIN_FOLDER_DIR' ) ) {
			return false;
		}

		$wp_user = get_user_by( 'id', $this->user->id );

		if ( $wp_user === false ) {
			return false;
		}

		return ECHO_CAMPAIN_FOLDER_DIR . $wp_user->user_nicename . '/';
    }
}