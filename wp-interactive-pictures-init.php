<?php
require_once WIP_DIR . 'frontend/class-shortcode.php';

/**
* Génère l'appel aux fonctions Ajax du plugin pour ajouter, supprimer et déplacer les points sur la carte
**/
if ( is_admin() ) {
	require_once WIP_DIR . 'inc/ajax.php';
	add_action( 'wp_ajax_addImg', array('wipAjax', 'so_wp_ajax_function') );
	add_action( 'wp_ajax_add_point_plan', array('wipAjax', 'so_wp_ajax_function_add_point_plan') );
	add_action( 'wp_ajax_remove_point_plan', array('wipAjax', 'so_wp_ajax_function_remove_point_plan') );
	add_action( 'wp_ajax_update_point_plan', array('wipAjax', 'so_wp_ajax_function_update_point_plan') );
	add_action( 'wp_ajax_add_form_plan', array('wipAjax', 'so_wp_ajax_function_add_form_plan') );
}

if (!class_exists("WPInteractivePictures")) {
	class WPInteractivePictures{

		public function __construct() {
			//add_action( 'init', array($this, 'app_output_buffer') );
			add_action( 'init', array($this, 'wip_load_text_domain'), 1 );
			add_action( 'admin_menu', array($this, 'wip_add_menu') );
			add_action( 'admin_init', array($this, 'wip_add_scripts') );

			//add_filter('mce_buttons', array($this,'wip_register_buttons') );
			//add_filter( 'mce_external_plugins', array($this, 'wip_register_tinymce_javascript') );
		}

		static function wip_install() {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			add_option( 'wip_db_version', WIP_DB_VERSION );

			$table_image = $wpdb->prefix . "wip_image";
			$table_points = $wpdb->prefix . "wip_points";

			$sql = "CREATE TABLE $table_image (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				title tinytext NOT NULL,
				id_thumbnail int(9),
				PRIMARY KEY  id (id)
			) $charset_collate;";

			$sql .= "CREATE TABLE $table_points (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				coordinatesX float NOT NULL,
				coordinatesY float NOT NULL,
				title tinytext NOT NULL,
				description text NOT NULL,
				pointerClass varchar(50),
				pointerColor varchar(7),
				idImage int(9),
				PRIMARY KEY  id (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		/*public function app_output_buffer() {
			ob_start();
		}*/

		/**
		* Charge les différents fichiers de langue pour la traduction du plugin
		* @see https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
		**/
		public function wip_load_text_domain(){
			load_plugin_textdomain( 'wip', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		* Création du lien dans le menu Wordpress
		* @see https://codex.wordpress.org/Function_Reference/add_options_page
		**/
		public function wip_add_menu(){
			//Affichage Back-office pour ajout/modification des points
			require_once WIP_DIR . 'admin/class-admin.php';

			$wipAdmin = new wipAdmin();

			add_menu_page( 'WP Interactive Pictures', 'WP Interactive Pictures', 'manage_options', 'wp-interactive-pictures', array($wipAdmin, 'theme_list_page'), 'dashicons-location-alt', 23 );
			add_submenu_page( '', 'Editer une image', 'Editer une image', 'manage_options', 'edit-image', array($wipAdmin, 'theme_edit_image_page') );
			$hook = add_submenu_page( 'wp-interactive-pictures', 'Toutes les images', 'Toutes les images', 'manage_options', 'wp-interactive-pictures', array($wipAdmin, 'theme_list_page') );
			add_submenu_page( 'wp-interactive-pictures', 'Ajouter une image', 'Ajouter une image', 'manage_options', 'add-image', array($wipAdmin, 'theme_add_image_page') );

			add_action( "load-$hook", array($wipAdmin, 'wip_list_add_options') );
		}

		/**
		* Charge les différents script JS/CSS nécessaires au bon fonctionnement du plugin
		**/
		public function wip_add_scripts(){
			require_once WIP_DIR . 'admin/class-scripts.php';

			add_action( 'admin_enqueue_scripts', array('wipAdminScript', 'wip_admin_js_init') );
			add_action( 'admin_enqueue_scripts', array('wipAdminScript', 'wip_admin_css_init') );
		}

		/**
		* Sélection et affiche des points sur l'image
		**/
		public function wip_get_image($id){
			global $wpdb;

			if( !$id ) return;

			$table_name = $wpdb->prefix . 'wip_image';
			$row = $wpdb->get_results( 'SELECT * FROM '.$table_name.' WHERE id = '.$id );
			return $row;
			wp_die();
		}

		static function wip_add_image($id, $title, $id_thumbnail){
			global $wpdb;

			if( !$id ) return;

			$table_name = $wpdb->prefix . 'wip_image';

			$wpdb->insert(
				$table_name,
				array(
					'title' => $title,
					'id_thumbnail' => $id_thumbnail
				),
				array(
					'%s',
					'%d'
				)
			) or wp_die(mysql_error());

			if( function_exists('icl_register_string') ){
				icl_register_string('wip', 'image-title-'.$id, stripslashes($title));
			}
		}

		static function wip_update_image($id, $title, $id_thumbnail){
			global $wpdb;

			if( !$id ) return;

			$table_name = $wpdb->prefix . 'wip_image';

			$wpdb->update(
				$table_name,
				array(
					'title' => $title,
					'id_thumbnail' => $id_thumbnail,
				),
				array(
					'id' => $id
				),
				array(
					'%s',
					'%d',
				),
				array(
					'%d'
				)
			);

			if( function_exists('icl_register_string') ){
				icl_register_string('wip', 'image-title-'.$id, stripslashes($title));
			}
		}

		static function delete_image_plan($id){
			global $wpdb;

			if( !$id ) return;

			$table_images = $wpdb->prefix . 'wip_image';
			$table_points = $wpdb->prefix . 'wip_points';

			$wpdb->delete(
				$table_images,
				array(
					'id' => $id
				),
				array(
					'%d'
				)
			);

			$wpdb->delete(
				$table_points,
				array(
					'idImage' => $id
				),
				array(
					'%d'
				)
			);

			if( function_exists('icl_register_string') ){
				icl_unregister_string('wip', 'image-title-'.$id, stripslashes($title));
			}
		}

		/**
		* Sélection et affiche des points sur l'image
		**/
		public function wip_get_points($id){
			global $wpdb;
			if( !$id ) return;
			$table_name = $wpdb->prefix . 'wip_points';
			$table_join = $wpdb->prefix . 'wip_image';
			$row = $wpdb->get_results( '
				SELECT
					'.$table_name.'.id,
					'.$table_name.'.coordinatesX,
					'.$table_name.'.coordinatesY,
					'.$table_name.'.title,
					'.$table_name.'.description,
					'.$table_name.'.id_thumbnail,
					'.$table_name.'.pointerClass,
					'.$table_name.'.pointerColor,
					'.$table_name.'.idImage
				FROM '.$table_name.' 
				INNER JOIN '.$table_join.' 
				ON ('.$table_name.'.idImage = '.$table_join.'.id) 
				WHERE idImage = '.$id);
			return $row;
			wp_die();
		}

		static function wip_add_point_infos($id, $title, $description, $thumbnail, $pointerClass, $pointer, $idImage){
			global $wpdb;

			$table_name = $wpdb->prefix . 'wip_points';

			$wpdb->update(
				$table_name,
				array(
					'title' => $title,
					'description' => $description,
					'id_thumbnail' => $thumbnail,
					'pointerClass' => $pointerClass,
					'pointerColor' => $pointer,
					'idImage' => $idImage,
				),
				array(
					'id' => $id
				),
				array(
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
					'%d',
				),
				array(
					'%d'
				)
			);

			if( function_exists('icl_register_string') ){
				icl_register_string('wip', 'title-'.$id, stripslashes($title));
				icl_register_string('wip', 'description-'.$id, stripslashes($description));
			}
		}

		static function wip_remove_point_plan($id){
			global $wpdb;

			$table_name = $wpdb->prefix . 'wip_points';

			$wpdb->delete( $table_name, array('id' => $id), $where_format = null );
			if( function_exists('icl_unregister_string') ){
				icl_unregister_string('wip', 'title-'.$id);
				icl_unregister_string('wip', 'description-'.$id);
			}

		}

		public function get_table_status($table_name){
			global $wpdb;

			$table_name = $wpdb->prefix.$table_name;

			$sql = 'SHOW TABLE STATUS LIKE "'.$table_name.'"';
			$sqlResults = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_assoc($sqlResults);

			$idCount = $row['Auto_increment'];

			return $idCount;

			wp_die();

		}

		// add new buttons
		public function wip_register_buttons($buttons) {
			array_push($buttons, 'separator', 'myplugin');
			return $buttons;
		}

		// Load the TinyMCE plugin : editor_plugin.js
		public function wip_register_tinymce_javascript($plugin_array) {
			$plugin_array['wip'] = PLUGIN_PATH . 'js/tinymce-plugin.js';
			return $plugin_array;
		}

	}
}

/**
* Instancie la class
**/

if (class_exists("WPInteractivePictures")) {
	$wip = new WPInteractivePictures();
}

add_filter('set-screen-option', 'wip_table_set_option', 10, 3);
function wip_table_set_option($status, $option, $value) {
if ( 'wip_images_per_page' == $option ) return $value;

return $status;
}
?>