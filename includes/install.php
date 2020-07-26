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
}

add_action( 'tfi_plugins_activate_echo', array( 'Echo_\\InstallManager', 'plugin_activation' ) );
add_action( 'tfi_plugins_deactivate_echo', array( 'Echo_\\InstallManager', 'plugin_deactivation' ) );