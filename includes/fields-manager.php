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

    public static function get_echo_parent_folder() {
        if ( ! array_key_exists( 'echo_folder', self::$cache ) ) {
            self::$cache['echo_folder'] = array_key_first( self::get_echo_file_folders_option_array() );
        }

        return self::$cache['echo_folder'];
    }

    public static function get_echo_users() {
        if ( ! array_key_exists( 'echo_users', self::$cache ) ) {
            self::$cache['echo_users'] = get_option( 'echo_users', array() );
        }

        return self::$cache['echo_users'];
    }

    public static function get_echo_file_folders_option_array() {
        if ( ! array_key_exists( 'echo_file_folders_option', self::$cache ) ) {
            self::$cache['echo_file_folders_option'] = array(
                'echo' => array(
                    'display_name' => 'Echo',
                    'parent' => '',
                    'admin_visible' => true
                ),
                'actors' => array(
                    'display_name' => 'actors',
                    'parent' => 'echo',
                    'admin_visible' => false
                ),
                'motionless' => array(
                    'display_name' => 'motionless',
                    'parent' => 'actors',
                    'admin_visible' => false 
                ),
                'movable' => array(
                    'display_name' => 'movable',
                    'parent' => 'actors',
                    'admin_visible' => false 
                ),
                'backgrounds' => array(
                    'display_name' => 'backgrounds',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'buttons' => array(
                    'display_name' => 'buttons',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'screens' => array(
                    'display_name' => 'screens',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'stars' => array(
                    'display_name' => 'stars',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'anims' => array(
                    'display_name' => 'anims',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'bkdos' => array(
                    'display_name' => 'bkdos',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'numbers' => array(
                    'display_name' => 'numbers',
                    'parent' => 'echo',
                    'admin_visible' => false 
                )
            );
        }

        return self::$cache['echo_file_folders_option'];
    }

    public static function get_echo_fields_option_array() {
        if ( ! array_key_exists( 'echo_fields_option', self::$cache ) ) {
            self::$cache['echo_fields_option'] = array(
                'echo_background' => array(
                    'real_name' => __( 'Arrière-plan' ),
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_users(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_menu-background' => array(
                    'real_name' => __( 'Menu Background' ),
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_users(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_bon-kdo' => array(
                    'real_name' => __( 'Bon cadeau' ),
                    'type' => 'multiple',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_users(),
                    'special_params' => array(
                        'min_length' => 5,
                        'max_length' => 80,
                        'type' => 'image',
                        'multiple_field_special_params' => array(
                            'folder' => 'bkdos',
                            'width' => 716,
                            'height' => 0
                        )
                    )
                ),
                'echo_police_color' => array(
                    'real_name' => __( 'Couleur de la police' ),
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_users(),
                    'special_params' => array()
                ),
                'echo_police' => array(
                    'real_name' => __( 'Police utilisée' ),
                    'type' => 'text',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_users(),
                    'special_params' => array()
                ),
                'echo_minimum_police_size' => array(
                    'real_name' => __( 'Taille minimum de la police (en pt)' ),
                    'type' => 'number',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_users(),
                    'special_params' => array(
                        'min' => 5,
                        'max' => 50
                    )
                ),
                'echo_number' => array(
                    'real_name' => __( 'Nombre' ),
                    'type' => 'multiple',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_users(),
                    'special_params' => array(
                        'min_length' => 10,
                        'max_length' => 10,
                        'type' => 'image',
                        'multiple_field_special_params' => array(
                            'folder' => 'numbers',
                            'width' => 100,
                            'height' => 100
                        )
                    )
                )
            );
        }

        return self::$cache['echo_fields_option'];
    }

    public static function get_echo_field_names() {
        return array_keys( self::get_echo_fields_option_array() );
    }

    public static function get_echo_file_folder_names() {
        return array_keys( self::get_echo_file_folders_option_array() );
    }

    public static function get_echo_field_objects() {
        if ( ! isset( self::$cache['echo_field_objects'] ) ) {
            require_once TFI_PATH . 'includes/field.php';

            foreach ( self::get_echo_fields_option_array() as $field_name => $field_datas ) {
                self::$cache['echo_field_objects'][$field_name] = new \TFI\Field( $field_name, $field_datas['real_name'], $field_datas['default'], $field_datas['type'], $field_datas['special_params'] );
            }
        }

        return self::$cache['echo_field_objects'];
    }

    public static function get_echo_default_values() {
        if ( ! isset( self::$cache['echo_default_values'] ) ) {
            $values = array();

            foreach ( self::get_echo_field_objects() as $field ) {
                $values[$field->name] = $field->default_value();
            }

            self::$cache['echo_default_values'] = $values;
        }

        return self::$cache['echo_default_values'];
    }
}