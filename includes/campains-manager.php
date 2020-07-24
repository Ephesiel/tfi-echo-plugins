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
     * Campainsmanager constructor
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param TFI\User $user	The user to get campain from.
     */
	public function __construct( $user ) {
		$this->user = $user->is_ok() ? $user : null;
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

			$folders = glob( $this->user_dir() . '*', GLOB_ONLYDIR );
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

		$campain = $this->get_campain( $campain_id );

		/**
		 * Creation if it doesn't exist
		 */
		if ( $campain === false ) {
			$dir = $this->user_dir() . $campain_id;
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
	 * User_dir.
	 * 
	 * Return the directory of the user inside the echo upload folder.
	 * Campains are stored here.
	 * 
	 * @since 1.0.0
	 * @access private
	 * 
	 * @return string	The path for the current user
	 * @return false	If an error occured
	 */
	private function user_dir() {
		if ( $this->user === null || ! defined( 'ECHO_UPLOAD_FOLDER_DIR' ) ) {
			return false;
		}

		$wp_user = get_user_by( 'id', $this->user->id );

		if ( $wp_user === false ) {
			return false;
		}

		return ECHO_UPLOAD_FOLDER_DIR . $wp_user->user_nicename . '/';
	}
}