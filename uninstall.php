<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manager of the uninstallation
 * This file is automatically include when the plugin is uninstall
 *
 * @since 1.0.0
 */
class UninstallManager {

    /**
     * UninstallManager constructor.
     * 
     * It calls every methods needed on uninstallation.
     * 
     * /!\ On unistallation, every datas stored and used by the plugin will be DELETED  /!\
     * /!\ Be carefull before unistalling                                               /!\
     * 
     * @since 1.0.0
     * @access public 
     */
    public function __construct() {
        $this->drop_table();
        $this->delete_upload_dir();
    }
    
    /**
     * Drop_table.
     * 
     * Drop the table with all echo user datas.
     * 
     * @since 1.0.0
     * @access private
     * @global wpdb     $wpdb           The database object to drop the table
     */
    private function drop_table() {
        global $wpdb;

        $wpdb->query(
            "DROP TABLE IF EXISTS " . $wpdb->prefix . ECHO_TABLE
        );
    }
    
    /**
     * Delete_upload_dir.
     * 
     * Delete the upload folder and all files inside this one.
     * 
     * @since 1.0.0
     * @access private
     */
    private function delete_upload_dir() {
        if ( defined( 'ECHO_CAMPAIN_FOLDER_DIR' ) ) {
            tfi_delete_files( ECHO_CAMPAIN_FOLDER_DIR );
        }
    }
}

require_once 'constants.php';

new UninstallManager();