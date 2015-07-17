<?php
class wipAdminScript{
    static function wip_admin_js_init($hook) {

        if ( 'admin_page_edit-image' != $hook && 'wp-interactive-pictures_page_add-image' != $hook && 'toplevel_page_wp-interactive-pictures' != $hook ) {
            return;
        }

        //$GLOBALS['wp_scripts']

        wp_enqueue_script('jquery', array('json2'));
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');

        wp_deregister_script("bootstrap-js");
        wp_enqueue_script( 'bootstrap-js', PLUGIN_PATH . 'js/bootstrap.min.js', array('jquery'), WIP_VERSION, true );
        wp_enqueue_script("bootstrap-js");

        wp_deregister_script("fontawesome-iconset");
        wp_register_script("fontawesome-iconset",PLUGIN_PATH . "js/iconset/iconset-fontawesome-4.2.0.min.js", array('jquery'), false, true );
        wp_enqueue_script("fontawesome-iconset");

        wp_deregister_script("iconpicker");
        wp_register_script("iconpicker",PLUGIN_PATH . "js/bootstrap-iconpicker.min.js", array('jquery', 'fontawesome-iconset'), false, true );
        wp_enqueue_script("iconpicker");
        
        wp_enqueue_script( 'wp-color-picker');

        wp_enqueue_script( 'app', PLUGIN_PATH . 'js/app.js', array('jquery', 'jquery-ui-core', 'colorpicker', 'iconpicker'), WIP_VERSION, true );
        wp_localize_script( 'app', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

        wp_enqueue_media();
    }

    static function wip_admin_css_init($hook) {

        if ( 'admin_page_edit-image' != $hook && 'wp-interactive-pictures_page_add-image' != $hook && 'toplevel_page_wp-interactive-pictures' != $hook ) {
            return;
        }

        wp_enqueue_style( 'bootstrap', PLUGIN_PATH . 'css/bootstrap.min.css', WIP_VERSION, true );
        wp_enqueue_style( 'font-awesome', PLUGIN_PATH . 'icon-fonts/font-awesome-4.2.0/css/font-awesome.min.css"', WIP_VERSION, true );
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'custom', PLUGIN_PATH . 'css/admin.css', WIP_VERSION, true );
    }
}