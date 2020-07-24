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
    public static $echo_fields = array(
        'echo_background' => array(
            'real_name' => 'Arrière-plan',
            'type' => 'image',
            'default' => '',
            'users' => array( 'default_type' ),
            'folder' => 'echo',
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
            'folder' => 'echo',
            'special_params' => array(
                'width' => 1080,
                'height' => 1920
            )
        )
    );
}