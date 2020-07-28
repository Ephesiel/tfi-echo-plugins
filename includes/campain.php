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
	public $owner;
	private $templates;
	private $template_settings;

    /**
     * Campain constructor
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string    $campain_dir    The directory where files of this campain are uploaded.
     * @param \TFI\User $owner          The owner of this campain
     */
	public function __construct( $campain_dir, $owner ) {
        $this->campain_dir  = $campain_dir;
        $this->id           = pathinfo( $campain_dir, PATHINFO_FILENAME );
        $this->nice_name    = ucfirst( str_replace( '_', ' ', $this->id ) );
        $this->owner        = $owner;
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
                $template = new Template( $file, $this );
                // Reupdate the json to be sure that all echo's field are inside
                $template->update_values( array_merge( FieldsManager::get_echo_default_values(), $template->get_values() ) );
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

        $template = new Template( $template_file, $this );
        $template->update_values( FieldsManager::get_echo_default_values() );
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
            // Destroy campain files on echo server
            require_once ECHO_PATH . 'includes/ftp-manager.php';
            $ftp_manager = new FtpManager;
            $ftp_manager->remove_echo_datas( $this, $this->templates[$template_id] );

            // Delete echo file (the template json)
            tfi_delete_files( $this->templates[$template_id]->template_file );
            // Delete template folder inside the tfi upload dir
            tfi_delete_files( CampainsManager::tfi_upload_dir_for_campain( $this, $this->templates[$template_id] ) );
            // Remove the template from this campain template
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
    private $values;
    private $json;

    public function __construct( $template_file ) {
        $this->template_file    = $template_file;
        $this->id               = pathinfo( $template_file, PATHINFO_FILENAME );
        $this->nice_name        = ucfirst( str_replace( '_', ' ', $this->id ) );
    }

    public function get_values() {
        if ( ! file_exists( $this->template_file ) ) {
            return array();
        }

        if ( $this->values === null ) {
            $json_file      = fopen( $this->template_file, 'r' );
            $this->json     = fread( $json_file, filesize( $this->template_file ) );
            $this->values   = json_decode( $this->json , true );
            fclose( $json_file );
        }

        return $this->values;
    }

    public function update_values( $new_values ) {
        if ( $new_values !== $this->get_values() ) {
            $json_file      = fopen( $this->template_file, 'w' );
            $this->values   = $new_values;
            $this->json     = json_encode( $new_values );
            fwrite( $json_file, $this->json );
            fclose( $json_file );

        }
    }

    public function get_json() {
        if ( $this->json === null ) {
            $this->get_values();
        }

        return $this->json;
    }
}