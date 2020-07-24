<?php
/**
 * Plugin Name: TFI Echo
 * Plugin URI: http://www.tempusfugit-thegame.com
 * Description: Create all echo functionnality to be add in the intranet and then send on the server
 * Version: 1.0.0
 * Author: Huftier Benoît
 * Author URI: http://www.tempusfugit-thegame.com
 */

// add_action( 'update_option_tfi_fields' )

define( 'ECHO_PATH', plugin_dir_path( __FILE__ ) );

$upload_dir = wp_upload_dir();

if ( $upload_dir['error'] === false ) {
	define( 'ECHO_UPLOAD_FOLDER_DIR', $upload_dir['basedir'] . '/echo_files/' );
	define( 'ECHO_UPLOAD_FOLDER_URL', $upload_dir['baseurl'] . '/echo_files/' );
}

/**
 * The main file of the echo plugin
 */
require ECHO_PATH . 'includes/plugin.php';
	
/**
 * The installation class to manage activation and deactivation of the plugin
 */
require ECHO_PATH . 'includes/install.php';