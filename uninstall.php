<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

// For Single site
if ( !is_multisite() ) {
    global $wpdb;

    $table_image = $wpdb->prefix . "wip_image";
    $table_points = $wpdb->prefix . "wip_points";

    $sql = "DROP TABLE IF EXISTS $table_image, $table_points";
    $wpdb->query($sql);

    delete_option('wip_db_version');
}
// For Multisite
else{
    // For site options in multisite
    delete_site_option( 'wip_db_version' );
}