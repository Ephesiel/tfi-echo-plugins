<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manager of the plugin admin panel
 * Look like TFI::AdminPanelManager
 * 
 * @since 1.0.0
 * @since 1.3.0		Option sanitation method updated
 */
class AdminPanelManager {

	/**
	 * AdminPanelManager constructor.
	 *
	 * Initializing all actions to do for the admin panel
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'load_menu' ) );
        add_action( 'admin_init', array( $this, 'register_options' ) );
    }

	/**
	 * Load_menu.
	 *
	 * Add the option page in the admin panel
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function load_menu() {
        add_options_page( __( 'Echo Options' ), __( 'Echo' ), 'manage_options', 'echo-options', array( $this, 'display_options' ) );
    }

	/**
	 * Display_options.
	 *
	 * Html content to display in the option panel
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function display_options() {
        if ( ! current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
        <div class="wrap">
			<h1><?php esc_html_e( 'Echo options page' ); ?></h1>

			<form method="post" action="options.php">
				<?php settings_fields( 'echo_options' ); ?>
				<?php do_settings_sections( 'echo-users' ); ?>
				<?php submit_button(); ?>
			</form>
        </div>
		<?php
	}

	/**
	 * Register_options.
	 *
	 * Function calls in admin_init hook to register options and create settings form
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function register_options() {
        register_setting(
			'echo_options',
			'echo_user_types',
			array( $this, 'sanitize_user_types' )
		);

		add_settings_section(
			'echo_users_id',
			__( 'Echo users' ),
			array( $this, 'display_users_section' ),
			'echo-users'
		);
		
		add_action( 'update_option_echo_user_types', array( $this, 'reupdate_tfi_fields' ), 10, 0 );
	}

	/**
	 * Certify_option.
	 * 
	 * This method should be call before each option inputs.
	 * It will add a specific key of this option.
	 * When the data will be send, if the key exists, the sanitize method will be sanitize output like expected on the form.
	 * If the key do not exists, it means that the value isn't passed by this form and we don't need to sanitize it.
	 * 
	 * @since 1.0.0
	 * @access private
	 * 
	 * @param string $option_name	The option name to put an certified input
	 * 
	 * @see is_value_certified
	 * @see verify_option
	 */
	private function certify_option( $option_name ) {
		?>
		<input type="hidden" name="<?php echo esc_attr( $option_name ); ?>[certification_option_key]" value="true" />
		<?php
	}

	/**
	 * Is_value_certified.
	 * 
	 * This method should be call before each sanitation method.
	 * Return if the given value as a certfied key given by the form.
	 * If yes, delete it because it is useless and sanitize as if the value was sent by the form.
	 * If no, just sanitize the option normally.
	 * 
	 * @since 1.0.0
	 * @access private
	 * 
	 * @param array	$value	The given value, it should be an array. This is a reference to be able to delete the certification key.
	 * 
	 * @see certify_option
	 * @see verify_option
	 */
	private function is_value_certified( &$value ) {
		if ( isset( $value['certification_option_key'] ) ) {
			unset( $value['certification_option_key'] );
			return true;
		}

		return false;
	}

	/**
	 * Verify_option.
	 * 
	 * This method verify an option by given a name and a value.
	 * Don't forget to check is_value_certified to be sure if you need a specific sanitation before calling OptionsManager.
	 * 
	 * @since 1.0.0		Refactorization
	 * @access private
	 * 
	 * @see certify_option
	 * @see is_value_certified
	 */
	private function verify_option( $option_name, $option_value ) {
		require_once ECHO_PATH . 'includes/options.php';

		$options_manager = new OptionsManager;
		return $options_manager->verify_option( $option_name, $option_value );
	}
	
	/**
	 * Sanitize_user_types.
	 * 
	 * Sanitize echo users. Those users will be able to use echo fields
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
     * @param array     $input Contains an array of user type set by the user
	 * @return          $input sanitized
	 */
	public function sanitize_user_types( $input ) {
		if ( ! $this->is_value_certified( $input ) ) {
			return $this->verify_option( 'echo_user_types', $input );
		}

		$user_types = array();

		// The unchecked checkbox won't be send in post datas (and so on $input array), so just take all user types send
		foreach ( $input as $id => $value ) {
			$user_types[] = $id;
        }
        
		return $this->verify_option( 'echo_user_types', $user_types );
	}

	public function display_users_section() {
		$tfi_user_types = tfi_get_option( 'tfi_user_types' );
		$echo_user_types = get_option( 'echo_user_types', array() );

		$this->certify_option( 'echo_user_types' );
		?>
		<table id="echo-users-table" class="tfi-options-table">
			<thead>
				<tr>
				<?php foreach ( $tfi_user_types as $type_id => $name ): ?>
					<th><?php esc_html_e( $name ); ?></th>
				<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<tr>
				<?php foreach ( $tfi_user_types as $type_id => $name ): ?>
					<td style="text-align: center;"><input type="checkbox" name="echo_user_types[<?php echo esc_attr( $type_id ); ?>]" <?php echo in_array( $type_id, $echo_user_types, true ) ? 'checked ' : ''; ?>/></td>
				<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
		<?php
	}
		
	/**
	 * Reupdate_tfi_fields.
	 * 
	 * When the echo_user_types option is changed, it means that new users can have access to echo fields (or cannot access anymore).
	 * When the tfi_fields option is updated, the tfi_fields_update hook is called, which will return echo fields (see HooksManager::add_echo_fields).
	 * 
	 * Those fields, contains in key 'users' all users in the fresh updated option echo_user_types (see FieldsManager::get_echo_fields_option_array).
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function reupdate_tfi_fields() {
		update_option( 'tfi_fields', tfi_get_option( 'tfi_fields' ) );
	}
}