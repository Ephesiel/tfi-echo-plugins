<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage everything about campains and template of a user
 *
 * @since 1.0.0
 */
class CampainsManager {

    /**
     * User.
     * 
     * User which will be used to know campains and templates
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var TFI\User|null	Null if the given user cannot access intranet
     */
	private $user;

    /**
     * Campains.
     * 
	 * All user campains
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var array
     */
    private $campains;

    /**
     * Template_settings.
     * 
	 * An array with a template settings
     * 
     * 'campain'    => The choosen campain
     * 'template'   => The choosen template
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var array
     */
    private $template_settings;

    /**
     * Campainsmanager constructor
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param TFI\User $user	The user to get campain from.
     */
	public function __construct( $user ) {
        if ( $user->is_ok() ) {
            $this->user = $user;
        }
    }

    /**
     * Set_template_settings.
     * 
     * This method will update the database to store which template is the last updated by the user
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param Campain   $campain    The choosen campain  
     * @param Template  $template   The choosen template
     * 
     * @global wpdb     $wpdb       The database object to update the datas
     */
    public function set_template_settings( $campain, $template ) {
		if ( $this->user === null ) {
			return;
        }

        global $wpdb;

        $values = maybe_serialize(  array(
            'campain_id' => $campain->id,
            'template_id' => $template->id
        ) );

        $wpdb->query( "INSERT INTO " . $wpdb->prefix . ECHO_TABLE . " (user_id, datas) VALUES (" . $this->user->id . ", '" . $values . "') ON DUPLICATE KEY UPDATE datas = VALUES(datas);" );

        $this->template_settings = array(
            'campain' => $campain,
            'template' => $template
        );
    }

    /**
     * Set_default_template_settings.
     * 
     * This is equivalent to the method set_template_settings but with the default settings.
     * 
     * @since 1.0.0
     * @access public
     */
    public function set_default_template_settings() {
        $default_campain = $this->get_default_campain();
        $default_template = $this->get_default_template_for_campain( $default_campain );

        $this->set_template_settings( $default_campain, $default_template );
    }

    /**
     * Get_template_settings.
     * 
     * This method will get the settings for the template choose by the user
     * If no settings are set, it will search in the database
     * If there is nothing in the database, it will return the default values
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return array                The template settings choose by the user
     * 
     * @global wpdb     $wpdb       The database object to select the datas
     */
    public function get_template_settings() {
		if ( $this->user === null ) {
			return array();
        }

        if ( $this->template_settings === null ) {
            global $wpdb;
    
            $result = $wpdb->get_var( "SELECT datas FROM " . $wpdb->prefix . ECHO_TABLE . " WHERE user_id = " . $this->user->id );
            
            // If the result is null, it means that there is no user_id with this id in the database
            if ( $result === null ) {
                $this->set_default_template_settings();
            }
            else {
                $result = maybe_unserialize( $result );
                $campain = $this->get_campain( $result['campain_id'] );

                // The campain and the template can be false if they have been deleted but not replace in the ddb.
                if ( $campain !== false ) {
                    $template = $campain->get_template( $result['template_id'] );
                    if ( $template !== false ) {
                        $this->template_settings = array(
                            'campain' => $campain,
                            'template' => $template
                        );
                    }
                    else {
                        $this->set_default_template_settings();
                    }
                }
                else {
                    $this->set_default_template_settings();
                }
            }
        }

        return $this->template_settings;
    }

    /**
     * Get_default_campain.
     * 
     * Return the campain by default for this user
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return Campain  The default campain
     */
    public function get_default_campain() {
        $campains = $this->get_campains();
        
        // Create a new campain name default if this user has no campain
        if ( empty( $campains ) ) {
            return $this->create_campain( 'default' );
        }

        // Or choose the first one
        return array_values( $campains )[0];
    }

    /**
     * Get_default_template_for_campain.
     * 
     * Return the template by default for the selected campain
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param Campain $campain  The campain to get the default template from
     * @return Template         The default template
     */
    public function get_default_template_for_campain( $campain ) {
        $templates = $campain->get_templates();
            
        // Create a new template if this user has no template for this campain
        if ( empty( $templates ) ) {
            return $campain->new_template();
        }

        // Or choose the first one
        return array_values( $templates )[0];
    }

	/**
	 * Get_campains.
	 * 
	 * Return all campains already create by this user
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @return array	All campains for this user
	 */
	public function get_campains() {
		if ( $this->user === null ) {
			return array();
		}

		if ( $this->campains === null ) {
			require_once ECHO_PATH . 'includes/campain.php';

			$folders = glob( $this->campain_user_dir() . '*', GLOB_ONLYDIR );
			$this->campains = array();

			foreach ( $folders as $folder ) {
				$campain = new Campain( $folder );
				$this->campains[$campain->id] = $campain;
			}
		}

		return $this->campains;
	}

	/**
	 * Get_campain.
	 * 
	 * Return the campain if it exists, else return false
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @param string $campain_id	The wanted campain id
	 * @return Campain				The wanted campain
	 * @return false				If this campain doesn't exist
	 */
	public function get_campain( $campain_id ) {
		if ( $this->user === null ) {
			return false;
		}

		foreach ( $this->get_campains() as $existing_campain ) {
			if ( $existing_campain->id === $campain_id ) {
				return $existing_campain;
			}
		}

		return false;
	}

	/**
	 * Create_campain.
	 * 
	 * Create a campain if the id doesn't exist
	 * Return the existing one if it already exists
	 * 
	 * @since 1.0.0
	 * @access public
	 * 
	 * @param string $campain_id	The wanted campain id
	 * @return Campain				The wanted campain
	 * @return false				If the user cannot create campain
	 */
	public function create_campain( $campain_id ) {
		if ( $this->user === null ) {
			return false;
        }
        
        // Sanitize the name
        $campain_id = preg_replace( '/[^a-z0-9_-]/', '', str_replace( ' ', '_', strtolower( $campain_id ) ) );
		$campain = $this->get_campain( $campain_id );

        // Create the campin if it doesn't exist
		if ( $campain === false && ! empty( $campain_id ) ) {
			$dir = $this->campain_user_dir() . $campain_id;
			wp_mkdir_p( $dir );

			$campain = new Campain( $dir );
			$this->campains[$campain->id] = $campain;
		}
		
		return $campain;
	}

    /**
     * Delete_campain.
     * 
     * Delete a specific campain that the user want to delete
     * /!\ All files will be deleted for ever, including templates for this campain !!!!
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $campain_id The id of the campain to remove
     */
	public function delete_campain( $campain_id ) {
        if ( array_key_exists( $campain_id, $this->get_campains() ) ) {
            tfi_delete_files( $this->campains[$campain_id]->campain_dir );
            unset( $this->campains[$campain_id] );
        }
	}

	/**
	 * Campain_user_dir.
	 * 
	 * Return the directory of the user inside the echo campain folder.
	 * Campains are stored here.
	 * 
	 * @since 1.0.0
	 * @access private
	 * 
	 * @return string	The path for the current user
	 * @return false	If an error occured
	 */
	private function campain_user_dir() {
		if ( $this->user === null || ! defined( 'ECHO_CAMPAIN_FOLDER_DIR' ) ) {
			return false;
		}

		$wp_user = get_user_by( 'id', $this->user->id );

		if ( $wp_user === false ) {
			return false;
		}

		return ECHO_CAMPAIN_FOLDER_DIR . $wp_user->user_nicename . '/';
	}
}