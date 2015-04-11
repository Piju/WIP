<?php
class wipShortcode{
  /**
   * Génère le shortcode pour l'insertion du plan dans les pages du site
   * @see http://codex.wordpress.org/Shortcode_API
   */

  public function init(){
    add_shortcode( 'wip', array($this, 'wip_shortcode') );
  }

  public function wip_shortcode($atts){
    ob_start();
      extract(shortcode_atts(array(
        'id'      => 1,
        'theme'   => 'modal',
        'align'   => 'center',
        'uniqid'  => uniqid()
      ), $atts));

      require_once(WIP_DIR . 'wp-interactive-pictures-init.php');

      $wip      = new WPInteractivePictures();
      $imgID    = $wip->wip_get_image($id);
      $points   = $wip->wip_get_points($id);

      if( $imgID ):
        require(WIP_DIR . 'styles/'.$theme.'/theme.php');

        $js_array = glob(WIP_DIR . 'styles/'.$theme.'/js/*.js');
        $css_array = glob(WIP_DIR . 'styles/'.$theme.'/css/*.css');

        foreach($js_array as $js){
          $filename = pathinfo($js, PATHINFO_FILENAME);

          wp_enqueue_script( $filename, PLUGIN_PATH . 'styles/'.$theme.'/js/'.$filename.'.js', array(), false, true );
        }

        foreach($css_array as $css){
          $filename = pathinfo($css, PATHINFO_FILENAME);
          wp_enqueue_style( $filename, PLUGIN_PATH . 'styles/'.$theme.'/css/'.$filename.'.css' );
        }
        wp_enqueue_style( 'font-awesome', PLUGIN_PATH . 'css/font-awesome.min.css', array(), '4.2.0' );

      else:
        _e("Aucune image n'a été trouvé.", 'wip');
      endif;

      $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }
} //Fin Class

$wip_shortcode = new wipShortcode();
$wip_shortcode->init();