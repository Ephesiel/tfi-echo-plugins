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
}

add_action( 'tfi_plugins_activate_echo', array( 'Echo_\\InstallManager', 'plugin_activation' ) );
add_action( 'tfi_plugins_deactivate_echo', array( 'Echo_\\InstallManager', 'plugin_deactivation' ) );