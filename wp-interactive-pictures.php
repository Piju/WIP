<?php
/*
Plugin Name: Wordpress interactive Pictures
Version: 1.0.1
Plugin URI: http://www.provost-pierrejulien.com/
Description: Wordpress Interactive Pictures allows you to add annoted pictures with pushpins containing information (Title, picture, text, custom cursor).
Author: Provost Pierre-julien
Author URI: http://www.provost-pierrejulien.com/
Text Domain: wip
Domain Path: /languages/
License: GPL v3

Wordpress Interactive Pictures
Copyright (C) 2004-2015, Creative Web

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* TODO:
* Sécuriser l'ensemble du code
* Implémenter fonctionnalité mise à jour
* Choix taille image insérée
* Bouton d'ajout dans l'éditeur de texte
* Moteur de recherche
**/

if ( ! function_exists( 'add_filter' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

// Load WIP plugin
define('WIP_VERSION', '1.0.1');
define('WIP_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_PATH', plugins_url( '/wp-interactive-pictures/'));
define('WIP_DB_VERSION', '1.0');
define('WIP_DEFAULT_POINTER', 'fa fa-map-marker');
define('WIP_DEFAULT_ICONPICKER_POINTER', 'fa-map-marker');

require_once WIP_DIR . '/wp-interactive-pictures-init.php';

register_activation_hook( __FILE__, array('WPInteractivePictures', 'wip_install') );