<?php
namespace Echo_;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All fields for echo
 *
 * @since 1.0.0
 */
class FieldsManager {
    private static $cache = array();

    public static function get_echo_parent_folder() {
        if ( ! array_key_exists( 'echo_folder', self::$cache ) ) {
            self::$cache['echo_folder'] = array_key_first( self::get_echo_file_folders_option_array() );
        }

        return self::$cache['echo_folder'];
    }

    public static function get_echo_user_types() {
        if ( ! array_key_exists( 'echo_user_types', self::$cache ) ) {
            self::$cache['echo_user_types'] = get_option( 'echo_user_types', array() );
        }

        return self::$cache['echo_user_types'];
    }

    public static function get_echo_file_folders_option_array() {
        if ( ! array_key_exists( 'echo_file_folders_option', self::$cache ) ) {
            self::$cache['echo_file_folders_option'] = array(
                'echo' => array(
                    'display_name' => 'Echo',
                    'parent' => '',
                    'admin_visible' => false
                ),
                'actors' => array(
                    'display_name' => 'actors',
                    'parent' => 'echo',
                    'admin_visible' => false
                ),
                'motionless' => array(
                    'display_name' => 'motionless',
                    'parent' => 'actors',
                    'admin_visible' => false 
                ),
                'movable' => array(
                    'display_name' => 'movable',
                    'parent' => 'actors',
                    'admin_visible' => false 
                ),
                'backgrounds' => array(
                    'display_name' => 'backgrounds',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'buttons' => array(
                    'display_name' => 'buttons',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'screens' => array(
                    'display_name' => 'screens',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'stars' => array(
                    'display_name' => 'stars',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'anims' => array(
                    'display_name' => 'anims',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'bkdos' => array(
                    'display_name' => 'bkdos',
                    'parent' => 'echo',
                    'admin_visible' => false 
                ),
                'numbers' => array(
                    'display_name' => 'numbers',
                    'parent' => 'echo',
                    'admin_visible' => false 
                )
            );
        }

        return self::$cache['echo_file_folders_option'];
    }

    public static function get_echo_fields_option_array() {
        if ( ! array_key_exists( 'echo_fields_option', self::$cache ) ) {
            self::$cache['echo_fields_option'] = array(
                'echo_background' => array(
                    'real_name' => 'Arrière-plan',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_menu-background' => array(
                    'real_name' => 'Menu Background',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_game-logo' => array(
                    'real_name' => 'Logo Personnalisé',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 864,
                        'height' => 0
                    )
                ),
                'echo_loading-screen' => array(
                    'real_name' => 'Ecran de chargement',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_padlock' => array(
                    'real_name' => 'Cadenas',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_sponsor' => array(
                    'real_name' => 'Sponsor',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 650,
                        'height' => 200
                    )
                ),
                'echo_transparent-square' => array(
                    'real_name' => 'Carré transparent',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_select' => array(
                    'real_name' => 'Sélection du skin',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => self::get_echo_parent_folder(),
                        'width' => 716,
                        'height' => 0
                    )
                ),
                'echo_laser' => array(
                    'real_name' => 'Laser',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'actors',
                        'width' => 12,
                        'height' => 120
                    )
                ),
                'echo_joker' => array(
                    'real_name' => 'Item Joker',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'actors',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_bonus' => array(
                    'real_name' => 'Bonus',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'motionless',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_red' => array(
                    'real_name' => 'Bloc d\'obstruction n°1 (rouge)',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'motionless',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_yellow' => array(
                    'real_name' => 'Bloc d\'obstruction n°2 (jaune)',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'motionless',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_green' => array(
                    'real_name' => 'Bloc d\'obstruction n°3 (vert)',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'motionless',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_special' => array(
                    'real_name' => 'Bloc d\'obstruction n°4 (spécial)',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'motionless',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_target' => array(
                    'real_name' => 'Cible',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'motionless',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_wall' => array(
                    'real_name' => 'Mur',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'motionless',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_bomb' => array(
                    'real_name' => 'Item Bombe',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'movable',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_divisor' => array(
                    'real_name' => 'Miroir Diviseur',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'movable',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_standardminus' => array(
                    'real_name' => 'Miroir Antislash',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'movable',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_standardplus' => array(
                    'real_name' => 'Miroir Slash',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'movable',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_teleporter' => array(
                    'real_name' => 'Miroir Téléporteur',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'movable',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_player' => array(
                    'real_name' => 'Joueur (canon)',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'movable',
                        'width' => 200,
                        'height' => 400
                    )
                ),
                'echo_editor-item-background' => array(
                    'real_name' => 'Background Item Editeur',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'backgrounds',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_large-popup-bg' => array(
                    'real_name' => 'Background Large Popup',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'backgrounds',
                        'width' => 864,
                        'height' => 1344
                    )
                ),
                'echo_level-item-background' => array(
                    'real_name' => 'Background Item Level',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'backgrounds',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_little-popup-bg' => array(
                    'real_name' => 'Background Petit Popup',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'backgrounds',
                        'width' => 500,
                        'height' => 384
                    )
                ),
                'echo_back-button' => array(
                    'real_name' => 'Bouton Retour',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-about' => array(
                    'real_name' => 'Bouton Menu À Propos',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-basket' => array(
                    'real_name' => 'Bouton Menu Boutique',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-close' => array(
                    'real_name' => 'Bouton Fermer',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-custom-template' => array(
                    'real_name' => 'Bouton Menu Choisir le skin',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-edit' => array(
                    'real_name' => 'Bouton Menu Editeur de niveau',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-menu-background' => array(
                    'real_name' => 'Fond pour les boutons du menu',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-menu-background-active' => array(
                    'real_name' => 'Fond cliqué des boutons du menu',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-rectangle-background' => array(
                    'real_name' => 'Fond pour les boutons rectangles',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 400,
                        'height' => 200
                    )
                ),
                'echo_button-rectangle-background-active' => array(
                    'real_name' => 'Fond cliqué d\'un bouton rectangle',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 400,
                        'height' => 200
                    )
                ),
                'echo_button-reload' => array(
                    'real_name' => 'Bouton Menu pour recharger les niveaux',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-settings' => array(
                    'real_name' => 'Bouton Menu Paramètres',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-square-background' => array(
                    'real_name' => 'Fond d\'un bouton carré',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-square-background-active' => array(
                    'real_name' => 'Fond cliqué d\'un bouton carré',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_continue-button' => array(
                    'real_name' => 'Bouton Continuer',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_load-button' => array(
                    'real_name' => 'Bouton Charger',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_mirrors-background' => array(
                    'real_name' => 'Fond Miroirs In Game',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_items-background' => array(
                    'real_name' => 'Fond Items In Game',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_next-button' => array(
                    'real_name' => 'Bouton Page Suivante',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_previous-button' => array(
                    'real_name' => 'Bouton Page Précédente',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_quit-button' => array(
                    'real_name' => 'Bouton Quitter sur le menu pause',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_button-quit' => array(
                    'real_name' => 'Bouton Quitter sur l\'écran d\'accueil',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_retry-button' => array(
                    'real_name' => 'Bouton Réessayer sur les écrans in game',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_save-button' => array(
                    'real_name' => 'Bouton Sauvegarder dans le level editor',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_pause' => array(
                    'real_name' => 'Bouton pour mettre le jeu en pause',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'buttons',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_defeat-image' => array(
                    'real_name' => 'Image de défaite',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'screens',
                        'width' => 417,
                        'height' => 0
                    )
                ),
                'echo_defeat-screen' => array(
                    'real_name' => 'Texte de défaite',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'screens',
                        'width' => 667,
                        'height' => 0
                    )
                ),
                'echo_pause-screen' => array(
                    'real_name' => 'Texte de pause',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'screens',
                        'width' => 667,
                        'height' => 0
                    )
                ),
                'echo_victory-screen' => array(
                    'real_name' => 'Texte de victoire',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'screens',
                        'width' => 667,
                        'height' => 0
                    )
                ),
                'echo_active-star' => array(
                    'real_name' => 'Etoile pleine (1)',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'stars',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_inactive-star' => array(
                    'real_name' => 'Etoile vide (1)',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'stars',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_inactive-level-stars' => array(
                    'real_name' => 'Etoiles vides (3) sur la sélection des niveaux',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'stars',
                        'width' => 240,
                        'height' => 80
                    )
                ),
                'echo_one-active-level-stars' => array(
                    'real_name' => 'Etoiles vides (2) et étoile pleine (1) sur la sélection des niveaux',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'stars',
                        'width' => 240,
                        'height' => 80
                    )
                ),
                'echo_two-active-level-stars' => array(
                    'real_name' => 'Etoile vide (1) et étoiles pleines (2) sur la sélection des niveaux',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'stars',
                        'width' => 240,
                        'height' => 80
                    )
                ),
                'echo_three-active-level-stars' => array(
                    'real_name' => 'Etoiles pleines (3) sur la sélection des niveaux',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'stars',
                        'width' => 240,
                        'height' => 80
                    )
                ),
                'echo_click' => array(
                    'real_name' => 'Animation au clic de la souris',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'anims',
                        'width' => 200,
                        'height' => 200
                    )
                ),
                'echo_colordestroy' => array(
                    'real_name' => 'Animation à la destruction d\'un bloc d\'obstruction "couleur" sur la map',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'anims',
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_walldestroy' => array(
                    'real_name' => 'Animation à la destruction d\'un bloc d\'obstruction "mur" sur la map',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'anims',
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_bonusgot' => array(
                    'real_name' => 'Animation à la destruction d\'un bonus sur la map',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'anims',
                        'width' => 1080,
                        'height' => 1920
                    )
                ),
                'echo_jokeradd' => array(
                    'real_name' => 'Animation "+3" sur chaque bloc d\'obstruction "couleur"',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'anims',
                        'width' => 75,
                        'height' => 75
                    )
                ),
                'echo_laserlaunch' => array(
                    'real_name' => 'Animation au lancement du laser par le joueur',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'anims',
                        'width' => 200,
                        'height' => 400
                    )
                ),
                'echo_shop' => array(
                    'real_name' => 'Bouton qui amène vers la boutique (sur le site)',
                    'type' => 'image',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'folder' => 'bkdos',
                        'width' => 716,
                        'height' => 179
                    )
                ),
                'echo_police' => array(
                    'real_name' => 'Police utilisée',
                    'type' => 'text',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_button' => array(
                    'real_name' => 'Couleur de la police sur les boutons',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_editor_select' => array(
                    'real_name' => 'Couleur de la police pour le titre de la sélection des niveaux dans l\'éditeur',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_gamescreen' => array(
                    'real_name' => 'Couleur de la police sur les écrans de victoire / défaite / pause',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_index' => array(
                    'real_name' => 'Couleur de la police sur l\'écran d\'accueil',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_editor_items' => array(
                    'real_name' => 'Couleur de la police sur les items dans l\'éditeur',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_loading_screen' => array(
                    'real_name' => 'Couleur de la police sur l\'écran de chargement',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_login' => array(
                    'real_name' => 'Couleur de la police sur l\'écran de connexion',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_popups' => array(
                    'real_name' => 'Couleur de la police sur les popups',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_color_save' => array(
                    'real_name' => 'Couleur de la police sur la sauvegarde d\'un niveau (il y a un fond blanc)',
                    'type' => 'color',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array()
                ),
                'echo_number' => array(
                    'real_name' => 'Nombre',
                    'type' => 'multiple',
                    'default' => '',
                    'admin_visible' => false,
                    'users' => self::get_echo_user_types(),
                    'special_params' => array(
                        'min_length' => 10,
                        'max_length' => 10,
                        'type' => 'image',
                        'multiple_field_special_params' => array(
                            'folder' => 'numbers',
                            'width' => 50,
                            'height' => 100
                        )
                    )
                )
            );
        }

        return self::$cache['echo_fields_option'];
    }

    public static function get_echo_field_names() {
        return array_keys( self::get_echo_fields_option_array() );
    }

    public static function get_echo_file_folder_names() {
        return array_keys( self::get_echo_file_folders_option_array() );
    }

    public static function get_echo_field_objects() {
        if ( ! isset( self::$cache['echo_field_objects'] ) ) {
            require_once TFI_PATH . 'includes/field.php';

            foreach ( self::get_echo_fields_option_array() as $field_name => $field_datas ) {
                self::$cache['echo_field_objects'][$field_name] = new \TFI\Field( $field_name, $field_datas['real_name'], $field_datas['default'], $field_datas['type'], $field_datas['special_params'] );
            }
        }

        return self::$cache['echo_field_objects'];
    }

    public static function get_echo_default_values() {
        if ( ! isset( self::$cache['echo_default_values'] ) ) {
            $values = array();

            foreach ( self::get_echo_field_objects() as $field ) {
                $values[$field->name] = $field->default_value();
            }

            self::$cache['echo_default_values'] = $values;
        }

        return self::$cache['echo_default_values'];
    }
}