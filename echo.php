<?php
/**
 * Plugin Name: Echo
 * Plugin URI: http://www.tempusfugit-thegame.com
 * Description: Create all echo functionnality to be add in the intranet and then send on the server
 * Version: 1.0.0
 * Author: Huftier Benoît
 * Author URI: http://www.tempusfugit-thegame.com
 */

require_once 'constants.php';

/**
 * The main file of the echo plugin
 */
require ECHO_PATH . 'includes/plugin.php';
	
/**
 * The installation class to manage activation and deactivation of the plugin
 */
require ECHO_PATH . 'includes/install.php';