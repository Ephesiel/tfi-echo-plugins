<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All fields for echo
 *
 * @since 1.0.0
 */
class FieldsManager {
    private static $cache = array();

    public static function get_echo_folder() {
        if ( ! array_key_exists( 'echo_folder', self::$cache ) ) {
            self::$cache['echo_folder'] = 'echo';
        }

        return self::$cache['echo_folder'];
    }

    public static function get_echo_fields() {
        if ( ! array_key_exists( 'echo_fields', self::$cache ) ) {
            self::$cache['echo_fields'] = array(
                'echo_background' => array(
                    'real_name' => 'Arrière-plan',
                    'type' => 'image',
                    'default' => '',
                    'users' => array( 'default_type' ),
                    'folder' => self::get_echo_folder(),
                    'special_params' => array(
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_menu-background' => array(
                    'real_name' => 'Menu Background',
                    'type' => 'image',
                    'default' => '',
                    'users' => array( 'default_type' ),
                    'folder' => self::get_echo_folder(),
                    'special_params' => array(
                        'width' => 1080,
                        'height' => 1920
                    )
                )
            );
        }

        return self::$cache['echo_fields'];
    }

    public static function get_echo_fields_name() {
        return array_keys( self::get_echo_fields() );
    }

    public static function get_user_datas( $user_id ) {
        if ( ! isset( self::$cache['user_db_data'][$user_id] ) ) {
            global $wpdb;
    
            $result = $wpdb->get_var( "SELECT datas FROM " . $wpdb->prefix . TFI_TABLE . " WHERE user_id = " . $this->id );
            
            // If the result is null, it means that there is no user_id with this id in the database
            if ( $result === null ) {
                self::$cache['user_db_data'][$user_id] = array();
            }
            else {
                self::$cache['user_db_data'][$user_id] = maybe_unserialize( $result );
            }
        }

        return self::$cache['user_db_data'][$user_id];
    } 
}