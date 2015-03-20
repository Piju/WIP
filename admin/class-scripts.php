<?php
class wipAdminScript{
    static function wip_admin_js_init($hook) {

        if ( 'admin_page_edit-image' != $hook && 'wp-interactive-pictures_page_add-image' != $hook && 'toplevel_page_wp-interactive-pictures' != $hook ) {
            return;
        }

        wp_deregister_script('jquery');
        wp_register_script('jquery', "http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js");
        wp_enqueue_script('jquery');

        wp_deregister_script("jQueryUI");
        wp_register_script("jQueryUI","http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js");
        wp_enqueue_script("jQueryUI");

        wp_enqueue_script( 'app', PLUGIN_PATH . 'js/app.js', array('jquery', 'jQueryUI', 'postbox', 'wp-color-picker'), false, true );
        wp_localize_script( 'app', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

        wp_enqueue_media();
        wp_enqueue_script("postbox");
    }

    static function wip_admin_css_init($hook) {

        if ( 'admin_page_edit-image' != $hook && 'wp-interactive-pictures_page_add-image' != $hook && 'toplevel_page_wp-interactive-pictures' != $hook ) {
            return;
        }

        wp_enqueue_style( 'custom', PLUGIN_PATH . 'css/admin.css' );
        wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' );
    }
}