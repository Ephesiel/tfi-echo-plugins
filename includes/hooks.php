<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manager of the connection with the tfi option hooks
 *
 * @since 1.0.0
 */
class HooksManager {
    private static $instance;

    public static function instance() {
        if ( self::$instance === null )
            self::$instance = new HooksManager;
        
        return self::$instance;
    }
    
    public function create_hooks() {
        add_filter( 'tfi_file_folders_update', array( $this, 'add_echo_file_folders' ) );
        add_filter( 'tfi_fields_update', array( $this, 'add_echo_fields' ) );
    }

    public function remove_hooks() {
        $value1 = remove_filter( 'tfi_file_folders_update', array( $this, 'add_echo_file_folders' ) );
        $value2 = remove_filter( 'tfi_fields_update', array( $this, 'add_echo_fields' ) );
    }

    public function add_echo_file_folders( $file_folders ) {
        require_once ECHO_PATH . 'includes/fields-manager.php';
        return array_merge( $file_folders, FieldsManager::get_echo_file_folders_option_array() );
    }

    public function add_echo_fields( $fields ) {
        require_once ECHO_PATH . 'includes/fields-manager.php';
        return array_merge( $fields, FieldsManager::get_echo_fields_option_array() );
    }
}