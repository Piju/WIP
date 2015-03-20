<?php
class wipUninstall{

    public function init(){
        register_uninstall_hook( __FILE__, 'wip_uninstall' );
    }

    static function wip_uninstall() {
        global $wpdb;

        //if uninstall not called from WordPress exit
        if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
            exit();

        // For Single site
        if ( !is_multisite() ) {
            global $wpdb;

            $table_image = $wpdb->prefix . "wip_image";
            $table_points = $wpdb->prefix . "wip_points";

            $sql = "DROP TABLE ".$table_name.",".$table_points;
            $wpdb->query($sql);
        }
        // For Multisite
        else{
        }
    }
}

$wip_uninstall = new wipUninstall();
$wip_uninstall->init();