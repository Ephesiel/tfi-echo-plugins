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

    public static function get_echo_folder_for_field( $field_name ) {
        if ( ! isset( self::$cache['echo_folder'][$field_name] ) ) {
        }

        return self::$cache['echo_folder'][$field_name];
    }

    public static function get_echo_fields() {
        if ( ! array_key_exists( 'echo_fields', self::$cache ) ) {
            self::$cache['echo_fields'] = array(
                'echo_background' => array(
                    'real_name' => __( 'Arrière-plan' ),
                    'type' => 'image',
                    'default' => '',
                    'users' => array( 'default_type' ),
                    'special_params' => array(
                        'folder' => self::get_echo_folder(),
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_menu-background' => array(
                    'real_name' => __( 'Menu Background' ),
                    'type' => 'image',
                    'default' => '',
                    'users' => array( 'default_type' ),
                    'special_params' => array(
                        'folder' => self::get_echo_folder(),
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_bon-kdo' => array(
                    'real_name' => __( 'Bon cadeau' ),
                    'type' => 'multiple',
                    'default' => '',
                    'users' => array( 'default_type' ),
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
                    'users' => array( 'default_type' ),
                    'special_params' => array()
                ),
                'echo_police' => array(
                    'real_name' => __( 'Police utilisée' ),
                    'type' => 'text',
                    'default' => '',
                    'users' => array( 'default_type' ),
                    'special_params' => array()
                ),
                'echo_minimum_police_size' => array(
                    'real_name' => __( 'Taille minimum de la police (en pt)' ),
                    'type' => 'number',
                    'default' => '',
                    'users' => array( 'default_type' ),
                    'special_params' => array(
                        'min' => 5,
                        'max' => 50
                    )
                ),
                'echo_number' => array(
                    'real_name' => __( 'Nombre' ),
                    'type' => 'multiple',
                    'default' => '',
                    'users' => array( 'default_type' ),
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

        return self::$cache['echo_fields'];
    }

    public static function get_echo_fields_name() {
        return array_keys( self::get_echo_fields() );
    }

    public static function get_echo_field_objects() {
        if ( ! isset( self::$cache['echo_field_objects'] ) ) {
            require_once TFI_PATH . 'includes/field.php';

            foreach ( self::get_echo_fields() as $field_name => $field_datas ) {
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