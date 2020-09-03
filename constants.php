<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define all constants to be used both in the plugin and in uninstall.php
 */
define( 'ECHO_PATH', plugin_dir_path( __FILE__ ) );
define( 'ECHO_TABLE', 'echo_datas' );

/**
 * Call wp_get_upload_dir instead of wp_upload_dir.
 * It's because we don't want to create the folder, www user doesn't have the permission to do that on the server and it can return an error.
 */
$upload_dir = wp_get_upload_dir();

if ( $upload_dir['error'] === false ) {
	define( 'ECHO_CAMPAIN_FOLDER_DIR', $upload_dir['basedir'] . '/echo_files/' );
}