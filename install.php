<?php
class wipInstall{

  public function init(){
    register_activation_hook( __FILE__, 'wip_install' );
  }

  static function wip_install() {

    global $wpdb;

    $table_image = $wpdb->prefix . "wip_image";
    $table_points = $wpdb->prefix . "wip_points";

    $sql = "CREATE TABLE $table_image (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      title tinytext NOT NULL,
      id_thumbnail int(9),
      PRIMARY KEY  id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    wp_die();

    $sql2 = "CREATE TABLE $table_points (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      coordinatesX float NOT NULL,
      coordinatesY float NOT NULL,
      title tinytext NOT NULL,
      description text NOT NULL,
      pointerClass mediumint(9),
      pointerColor varchar(7),
      idImage int(9),
      PRIMARY KEY  id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql2 );

    wp_die();
  }
}

$wip_install = new wipInstall();
$wip_install->init();