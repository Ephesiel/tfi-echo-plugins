<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A campain object
 *
 * @since 1.0.0
 */
class Campain {
	public $id;
	public $nice_name;
	public $campain_dir;
	private $templates;
	private $template_settings;

    /**
     * Campain constructor
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $campain_dir    The directory where files of this campain are uploaded.
     */
	public function __construct( $campain_dir ) {
        $this->campain_dir  = $campain_dir;
        $this->id           = pathinfo( $campain_dir, PATHINFO_FILENAME );
        $this->nice_name    = ucfirst( str_replace( '_', ' ', $this->id ) );
	}

	/**
	 * Get_templates.
	 * 
	 * Return all templates already created for this campain
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @return array	All templates for this campain
	 */
	public function get_templates() {
		if ( $this->templates === null ) {
			$files = glob( $this->campain_dir . '/*.json' );
			$this->templates = array();

			foreach ( $files as $file ) {
                $template = new Template( $file );
				$this->templates[$template->id] = $template;
			}
        }

		return $this->templates;
	}

	/**
	 * Get_template.
	 * 
	 * Return the template if it exists, else return false
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @param string $template_id   The wanted template id
     * @return Template             The wanted template
     * @return bool                 If the template doesn't exist
	 */
	public function get_template( $template_id ) {
		foreach ( $this->get_templates() as $existing_template ) {
			if ( $existing_template->id === $template_id ) {
				return $existing_template;
			}
		}

		return false;
	}

    /**
     * Get_default_template.
     * 
     * Return the template by default for the selected campain
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return Template         The default template
     */
    public function get_default_template() {
        $templates = $this->get_templates();
            
        // Create a new template if this user has no template for this campain
        if ( empty( $templates ) ) {
            return $this->new_template();
        }

        // Or choose the first one
        return array_values( $templates )[0];
    }

    /**
     * New_template.
     * 
     * Create a new template for this campain.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return Template The new template
     */
	public function new_template() {
        $template_file;
        $counter = 1;

        do {
            $template_file = $this->campain_dir . '/template_' . $counter . '.json';
            $counter++;
        }
        while ( file_exists( $template_file ) );
        
        require_once ECHO_PATH . 'includes/fields-manager.php';
        $json = fopen( $template_file, 'w' );
        fwrite( $json, json_encode( FieldsManager::get_echo_fields() ) );
        fclose( $json );

        $template = new Template( $template_file );
        $this->templates[$template->id] = $template;

        return $template;
    }
    
    /**
     * Delete_template.
     * 
     * Delete a specific template that the user want to delete
     * /!\ All files will be deleted for ever !!!!
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $template_id   The id of the template to remove
     */
    public function delete_template( $template_id ) {
        if ( array_key_exists( $template_id, $this->get_templates() ) ) {
            tfi_delete_files( $this->templates[$template_id]->template_file );
            unset( $this->templates[$template_id] );
        }
    }
}

/**
 * @since 1.0.0
 */
class Template {
    public $id;
    public $nice_name;
    public $template_file;

    public function __construct( $template_file ) {
        $this->template_file    = $template_file;
        $this->id               = pathinfo( $template_file, PATHINFO_FILENAME );
        $this->nice_name        = ucfirst( str_replace( '_', ' ', $this->id ) );
    }
}