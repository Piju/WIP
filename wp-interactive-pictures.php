<?php
/*
Plugin Name: Wordpress interactive Pictures
Version: 1.0
Plugin URI: http://www.provost-pierrejulien.com/
Description: Wordpress Interactive Pictures vous permet d'intégrer des images annotées avec des épingles contenants des informations (Titre, images, textes).
Author: Provost Pierre-julien
Author URI: http://www.provost-pierrejulien.com/
Text Domain: wip
Domain Path: /languages/
License: GPL v3

Wordpress Interactive Pictures
Copyright (C) 2008-2014, Yoast BV - support@yoast.com

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
* Implémenter fonctionnalité mise à jour
* Sécuriser l'ensemble du code
* Ajouter le champs de sélection de curseur (API Font Awesome ?)
* Réglage bug insertion éditeur texte Ajax
* Création de 2,3 templates par défaut
* Bouton d'ajout dans l'éditeur de texte
**/

if ( ! function_exists( 'add_filter' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

/*if ( ! defined( 'WIP_FILE' ) ) {
    define( 'WIP_FILE', __FILE__ );
}*/

// Load WIP plugin
define('WIP_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_PATH', plugins_url( '/wp-interactives-pictures/'));

require_once( WIP_DIR . '/wp-interactive-pictures-init.php' );