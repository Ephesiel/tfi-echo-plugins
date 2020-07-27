<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manager of the installation, activation/deactivation
 *
 * @since 1.0.0
 */
class InstallManager {

    /**
     * Plugin_activation.
     * 
     * Call every methods needed at activation.
     * 
     * @since 1.3.0
     * @access public
     * @static
     */
    public static function plugin_activation() {
        self::create_table();
        self::add_echo_options();
    }

    /**
     * Plugin_deactivation.
     * 
     * Call every methods needed at deactivation.
     * 
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function plugin_deactivation() {
        self::delete_echo_options();
    }

    /**
     * Create_tables
     * 
     * Create the table required for the plugin.
     * This method can be called multiple times (at activation for example)
     * Because the dbDelta function will check the existence of the table in the database.
     * 
     * @since 1.0.0
     * @access private
     * @static
     * @global wpdb     $wpdb           The database object to add the new table
     */
    private static function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE " . $wpdb->prefix . ECHO_TABLE . " (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL UNIQUE,
            campain_id varchar(255) NOT NULL,
            template_id varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Add_echo_options.
     * 
     * @since 1.0.0
     * @access private
     * @static
     */
    private static function add_echo_options() {
        require_once ECHO_PATH . 'includes/options.php';

        $option_manager = new OptionsManager();
        $option_manager->update_options();

        /**
         * Update option will call hooks creating in the HooksManager
         * Echo's field and folder will be updated
         */
        update_option( 'tfi_file_folders', tfi_get_option( 'tfi_file_folders' ) );
        update_option( 'tfi_fields', tfi_get_option( 'tfi_fields' ) );
    }

    /**
     * Delete_echo_options.
     * 
     * @since 1.0.0
     * @access private
     * @static
     */
    private static function delete_echo_options() {
        require_once ECHO_PATH . 'includes/fields-manager.php';
        require_once ECHO_PATH . 'includes/hooks.php';

        /**
         * This is done because update_option call \TFI\OptionsManager::verify_option_$option_name.
         * And this method is applying the tfi_option_$name_updated which is called by the HooksManager.
         */
        HooksManager::instance()->remove_hooks();

        /**
         * Remove all echo's fields and folders
         */
        $fields = tfi_get_option( 'tfi_fields' );
        $folders = tfi_get_option( 'tfi_file_folders' );

        foreach ( $fields as $field_slug => $values ) {
            if ( in_array( $field_slug, FieldsManager::get_echo_field_names(), true ) ) {
                unset( $fields[$field_slug] );
            }
        }
        foreach ( $folders as $file_folder_slug => $values ) {
            if ( in_array( $file_folder_slug, FieldsManager::get_echo_file_folder_names(), true ) ) {
                unset( $folders[$file_folder_slug] );
            }
        }

        update_option( 'tfi_fields', $fields );
        update_option( 'tfi_file_folders', $folders );
    }
}

add_action( 'tfi_plugins_activate_echo', array( 'Echo_\\InstallManager', 'plugin_activation' ) );
add_action( 'tfi_plugins_deactivate_echo', array( 'Echo_\\InstallManager', 'plugin_deactivation' ) );