<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define all constants to be used both in the plugin and in uninstall.php
 */
define( 'ECHO_PATH', plugin_dir_path( __FILE__ ) );
define( 'ECHO_TABLE', 'echo_datas' );

$upload_dir = wp_upload_dir();

if ( $upload_dir['error'] === false ) {
	define( 'ECHO_CAMPAIN_FOLDER_DIR', $upload_dir['basedir'] . '/echo_files/' );
}