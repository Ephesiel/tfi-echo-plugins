<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Everything concerning options are here
 * This is equivalent to TFI::OptionsManager but for echo
 *
 * @since 1.0.0
 */
class OptionsManager {

    /**
     * Default_options.
     * 
     * All options and their default values
     * 
     * @since 1.0.0
     * 
     * @static
     * @access private
     * 
     * @var array
     */
    private static $default_options = array(
        'echo_user_types' => array()
    );

    /**
     * Update_options.
     * 
     * Update all options in the database
     * Each option has it's own verification method
     * 
     * @since 1.0.0
     * @access public
     */
    public function update_options() {
        foreach ( self::$default_options as $option_name => $default_value ) {
            $option = get_option( $option_name );

            if ( $option === false && update_option( $option_name, false ) === false ) {
                add_option( $option_name, $default_value );
            }
            else {
                $new_value = $this->verify_option( $option_name, $option );
                if ( $option !== $new_value ) {
                    update_option( $option_name, $new_value );
                }
            }
        }
    }

    /**
     * Delete_options.
     * 
     * Delete every option.
     * This method sould only be used on uninstall
     * 
     * @since 1.0.0
     * @access public
     */
    public function delete_options() {
        foreach ( self::$default_options as $option_name => $display_name ) {
            delete_option( $option_name );
        }
    }

    /**
     * Verify_option.
     * 
     * Launch the verification method of a specific option.
     * Note that each option must have it's own method verification, at least for update verification.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return mixed    the sanitize value of an option.
     * @return null     the option doesn't exist
     */
    public function verify_option( $option_name, $value ) {
        if ( array_key_exists( $option_name, self::$default_options ) ) {
            // Remove the 'echo_'
            $option_name = substr( $option_name, 5 );

            return call_user_func( array( $this, 'verify_' . $option_name ), $value );
        }

        return null;
    }

    /**
     * Verify_user_types.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @param array $user_types     contains all echo user types to verify for the option echo_user_types
     * @return array                $user_types sanitized
     */
    public function verify_user_types( $user_types ) {
        if ( ! is_array( $user_types ) ) {
            return self::$default_options['echo_user_types'];
        }

        $tfi_user_types = tfi_get_option( 'tfi_user_types' );
        $new_user_types = array();

        foreach ( $user_types as $user_type ) {
            if ( in_array( $user_type, $tfi_user_types, true ) ) {
                $new_user_types[] = $user_type;
            }
        }

        return $new_user_types;
    }
}