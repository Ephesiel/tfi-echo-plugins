<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
        
        return self::$instance;
    }

    private function addAdminOption() {
        require_once ECHO_PATH . 'includes/admin-panel.php';

        new AdminPanelManager();
    }
	
	private function addHooksManager() {
        require_once ECHO_PATH . 'includes/hooks.php';

        HooksManager::instance()->create_hooks();
	}
	
	private function addSortcodesManager() {
        require_once ECHO_PATH . 'includes/shortcodes.php';

        new ShortcodesManager();
	}

	/**
	 * Plugin constructor.
	 *
	 * Initializing echo plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __construct() {
        if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
            $this->addAdminOption();
			$this->addHooksManager();
        }
        else {
			$this->addSortcodesManager();
		}
	}
}

Plugin::instance();