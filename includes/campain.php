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
			$folders = glob( $this->campain_dir . '/*', GLOB_ONLYDIR );
			$this->templates = array();

			foreach ( $folders as $folder ) {
                $template = new Template( $folder );
				$this->templates[$template->id] = $template;
			}
        }

		return $this->templates;
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
        $template_dir;
        $counter = 1;

        do {
            $template_dir = $this->campain_dir . '/template_' . $counter . '/';
            $counter++;
        }
        while ( file_exists( $template_dir ) );
        
        wp_mkdir_p( $template_dir );

        $template = new Template( $template_dir );
        $this->templates[$template->id] = $template;

        return $template;
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
            tfi_delete_files( $this->templates[$template_id]->template_dir );
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
    public $template_dir;

    public function __construct( $template_dir ) {
        $this->template_dir = $template_dir;
        $this->id           = pathinfo( $template_dir, PATHINFO_FILENAME );
        $this->nice_name    = ucfirst( str_replace( '_', ' ', $this->id ) );
    }
}