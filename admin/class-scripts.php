<?php
class wipAdminScript{
    static function wip_admin_js_init($hook) {

        if ( 'admin_page_edit-image' != $hook && 'wp-interactive-pictures_page_add-image' != $hook && 'toplevel_page_wp-interactive-pictures' != $hook ) {
            return;
        }

        wp_deregister_script('jquery');
        wp_register_script('jquery', "//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js");
        wp_enqueue_script('jquery');

        wp_deregister_script("jQueryUI");
        wp_register_script("jQueryUI","//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js");
        wp_enqueue_script("jQueryUI");

        wp_deregister_script("bootstrap-js");
        wp_register_script("bootstrap-js","//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js");
        wp_enqueue_script("bootstrap-js");

        wp_deregister_script("fontawesome-iconset");
        wp_register_script("fontawesome-iconset",PLUGIN_PATH . "js/iconset/iconset-fontawesome-4.2.0.min.js", array('jquery'), false, true );
        wp_enqueue_script("fontawesome-iconset");

        wp_deregister_script("iconpicker");
        wp_register_script("iconpicker",PLUGIN_PATH . "js/bootstrap-iconpicker.min.js", array('jquery'), false, true );
        wp_enqueue_script("iconpicker");

        wp_enqueue_script( 'app', PLUGIN_PATH . 'js/app.js', array('jquery', 'jQueryUI', 'wp-color-picker'), WIP_VERSION, true );
        wp_localize_script( 'app', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

        wp_enqueue_media();
    }

    static function wip_admin_css_init($hook) {

        if ( 'admin_page_edit-image' != $hook && 'wp-interactive-pictures_page_add-image' != $hook && 'toplevel_page_wp-interactive-pictures' != $hook ) {
            return;
        }

        wp_enqueue_style( 'bootstrap', PLUGIN_PATH . 'css/bootstrap.min.css', WIP_VERSION, true );
        wp_enqueue_style( 'font-awesome', PLUGIN_PATH . 'icon-fonts/font-awesome-4.2.0/css/font-awesome.min.css"', WIP_VERSION, true );
        wp_enqueue_style( 'custom', PLUGIN_PATH . 'css/admin.css', WIP_VERSION, true );
    }
}