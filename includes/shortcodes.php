<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manager of the plugin shortcodes
 *
 * @since 1.0.0
 */
class ShortcodesManager {

    /**
     * User.
     * 
     * Current user
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var User
     */
    private $user;

    /**
     * Campains_manager.
     * 
     * The object which knows everything about campains and templates of the user 
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var CampainsManager
     */
    private $campains_manager;

    /**
     * Choosen_campain.
     * 
     * The campain choose by the user
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var Campain
     */
    private $choosen_campain;

    /**
     * Choosen_template.
     * 
     * The template choose by the user
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var Template
     */
    private $choosen_template;

	/**
	 * ShortcodesManager constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function __construct() {
        add_action( 'init', array( $this, 'shortcodes_init' ) );
        add_action( 'init', array( $this, 'init' ) );
    }

	/**
	 * Shortcodes_init.
     * 
     * Initiation of all shortcodes used by the plugin
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function shortcodes_init() {
        add_shortcode( 'echo_user_form', array( $this, 'display_echo_form' ) );
        add_shortcode( 'echo_template_selection', array( $this, 'display_template_selection' ) );
    }

	/**
	 * User_init.
     * 
     * Initiation of the campain and template for echo
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function init() {
        require_once TFI_PATH . 'includes/user.php';
        require_once ECHO_PATH . 'includes/campains-manager.php';

        $this->user = new \TFI\User( get_current_user_id() );
        $this->campains_manager = new CampainsManager( $this->user );

        if ( ! $this->user->is_ok() ) {
            return;
        }

        // If the user want to delete a campain
        if ( isset( $_GET['echo_campain_to_delete'] ) ) {
            $this->campains_manager->delete_campain( $_GET['echo_campain_to_delete'] );
        }
        
        //If the user choose a specific campain, we select it
        if ( isset( $_GET['echo_choosen_campain'] ) ) {
            $this->choosen_campain = $this->campains_manager->get_campain( $_GET['echo_choosen_campain'] );
        }

        // If the user want to create a new campain, we select it
        if ( isset( $_GET['echo_new_campain'] ) && ! empty( $_GET['echo_new_campain'] ) ) {
            $this->choosen_campain = $this->campains_manager->create_campain( $_GET['echo_new_campain'] );
        }

        // If the campain is null (no value pass in get) or false (the value pass is not valid), get the first campain
        if ( $this->choosen_campain === null || $this->choosen_campain === false ) {
            $campains = $this->campains_manager->get_campains();
            
            // Create a new campain name default if this user has no campain
            if ( empty( $campains ) ) {
                $this->choosen_campain = $this->campains_manager->create_campain( 'default' );
            }
            // Or choose the first one
            else {
                $this->choosen_campain = array_values( $campains )[0];
            }
        }

        // If the user want to delete a template
        if ( isset( $_GET['echo_template_to_delete'] ) ) {
            $this->choosen_campain->delete_template( $_GET['echo_template_to_delete'] );
        }
        
        // If the user choose a specific template, we select it
        if ( isset( $_GET['echo_choosen_template'] ) ) {
            $this->choosen_template = $this->choosen_campain->get_template( $_GET['echo_choosen_template'] );
        }

        // If the user want to create a new template, we select it
        if ( isset( $_GET['echo_new_template'] ) && ! empty( $_GET['echo_new_template'] ) ) {
            $this->choosen_template = $this->choosen_campain->new_template();
        }

        // If the template is null (no value pass in get) or false (the value pass is not valid), get the first template of the campain
        if ( $this->choosen_template === null || $this->choosen_template === false ) {
            $templates = $this->choosen_campain->get_templates();
            
            // Create a new template if this user has no template for this campain
            if ( empty( $templates ) ) {
                $this->choosen_template = $this->choosen_campain->new_template();
            }
            // Or choose the first one
            else {
                $this->choosen_template = array_values( $templates )[0];
            }
        }

        /**
         * 
         */
        require_once ECHO_PATH . 'includes/fields-manager.php';
        foreach ( FieldsManager::get_echo_fields_name() as $field_name ) {
            add_filter( 'tfi_field_file_path_' . $field_name, array( $this, 'update_echo_data' ) );
        }
    }

    /**
     * Display_echo_form.
     * 
     * This method is called when using the [echo_user_form] shortcode
     * It displays the form with all echo's fields and not others
     * 
     * Attributes :
     *      -   preview => bool     => If you want to have a previzualisation of all fields
     * 
     * @since 1.0.0
     * @access public
     */
    public function display_echo_form( $atts = array(), $content = null, $tag = '' ) {
        $atts = array_change_key_case( (array)$atts, CASE_LOWER );

        require_once ECHO_PATH . 'includes/fields-manager.php';
        $fields = implode( ',', Fieldsmanager::get_echo_fields_name() );

        $shortcode = '[tfi_user_form fields="' . $fields . '"';
        if ( isset( $atts['preview'] ) ) {
            $shortcode .= ' preview="' . $atts['preview'] . '"';
        }
        $shortcode .= ']';

        return do_shortcode( $shortcode );
    }

    /**
     * Display_template_selection.
     * 
     * This method is called when using the [echo_template_selection] shortcode
     * It displays a selection for template and campain
     * 
     * @since 1.0.0
     * @access public
     */
    public function display_template_selection( $atts = array(), $content = null, $tag = '' ) {
        if ( ! $this->user->is_ok() ) {
            return;
        }

        $campains = $this->campains_manager->get_campains();
        $templates = $this->choosen_campain->get_templates();

        $o = '<form class="tfi-user-form form-echo" action="' . esc_attr( get_permalink( get_the_ID() ) ) . '" method="GET">';
        $o.=    '<table class="form-table">';
        $o.=        '<tr>';
        $o.=            '<th>';
        $o.=                '<label for="echo-campain-select">';
        $o.=                    esc_html__( 'Echo campain selection' );
        $o.=                '</label>';
        $o.=            '</th>';
        $o.=            '<td>';
        $o.=                '<select onchange="this.form.submit()" id="echo-campain-select" name="echo_choosen_campain">';
        foreach ( $campains as $campain ) {
        $o.=                    '<option value="' . $campain->id . '" ' . ( $campain->id === $this->choosen_campain->id ? ' selected' : '' ) . '>' . $campain->nice_name . '</option>';
        }
        $o.=                '</select>';
        $o.=                '<input onclick="document.getElementById(\'echo-campain-select\').setAttribute(\'name\', \'echo_campain_to_delete\'); this.form.submit()" type="button" class="submit-button" value="' . esc_attr__( 'Delete this campain' ) . '" />';
        $o.=            '</td>';
        $o.=        '</tr>';
        $o.=        '<tr>';
        $o.=            '<th>';
        $o.=                '<label for="echo-new-campain">';
        $o.=                    esc_html__( 'New echo campain' );
        $o.=                '</label>';
        $o.=            '</th>';
        $o.=            '<td>';
        $o.=                '<input type="text" placeholder="' . esc_attr__( 'My new campain' ) . '" id="echo-new-campain" name="echo_new_campain">';
        $o.=                '<input onclick="this.form.submit()" type="button" class="submit-button" value="' . esc_attr__( 'Create and select a new campain' ) . '" />';
        $o.=            '</td>';
        $o.=        '</tr>';
        $o.=        '<tr>';
        $o.=            '<th>';
        $o.=                '<label for="echo-template-select">';
        $o.=                    esc_html__( 'Echo template selection' );
        $o.=                '</label>';
        $o.=            '</th>';
        $o.=            '<td>';
        $o.=                '<select onchange="this.form.submit()" id="echo-template-select" name="echo_choosen_template">';
        foreach ( $templates as $template ) {
        $o.=                    '<option value="' . $template->id . '" ' . ( $template->id === $this->choosen_template->id ? ' selected' : '' ) . '>' . $template->nice_name . '</option>';
        }
        $o.=                '</select>';
        $o.=                '<input id="echo-new-template" type="hidden" name="echo_new_template" value="" />';
        $o.=                '<input onclick="document.getElementById(\'echo-new-template\').setAttribute(\'value\', \'1\'); this.form.submit()" type="button" class="submit-button" value="' . esc_attr__( 'Create and select a new empty template' ) . '" />';
        $o.=                '<input onclick="document.getElementById(\'echo-template-select\').setAttribute(\'name\', \'echo_template_to_delete\'); this.form.submit()" type="button" class="submit-button" value="' . esc_attr__( 'Delete this template' ) . '" />';
        $o.=            '</td>';
        $o.=        '</tr>';
        $o.=    '</table>';
        $o.= '</form>';

        return $o;
    }

    /**
     * Update_echo_data.
     * 
     * This method is connected with the tfi_field_file_path_$fieldname hook and is called for every echo field.
     * It will return the value of the path according to the choosen templates.
     * It will allows to have multiple echo image for the same field !
     * The new path will be user_name/echo/campain/template/...
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $value     The path of the folder which will be modify
     * @return string           The new path for the file
     */
    public function update_echo_data( $value ) {
        $echo_folder    = tfi_get_user_file_folder_path( $this->user->id, 'echo', false );
        $new_folder     = $echo_folder . '/' . $this->choosen_campain->id . '/' . $this->choosen_template->id;
        $new_value      = $new_folder . substr( $value, strlen( $echo_folder ) );
        
        return $new_value;
    }
}