<?php
class wipAdmin{
  public $id = NULL;

  public function __construct(){
    if( isset($_GET['id']) ){
      $this->id = $_GET['id'];
    }
  }

  public function theme_list_page() {
    // Teste si l'utilisateur a les droits nécessaires pour accéder à la gestion du plan
    if ( current_user_can( 'manage_options' ) && !isset( $this->id ) ){
      $wip      = new WPInteractivePictures();
      $imgID    = $wip->wip_get_image($this->id);
      $points   = $wip->wip_get_points($this->id);
      require_once('get_images.php');
    }else{
      _e("Vous n'êtes pas autorisé à modifier cette partie du Back-office");
    }
  }

  public function wip_list_add_options() {
    $option = 'per_page';
    $args = array(
      'label' => 'Images',
      'default' => 10,
      'option' => 'wip_images_per_page'
    );
    add_screen_option( $option, $args );
  }

  public function theme_edit_image_page(){
    $wip      = new WPInteractivePictures();
    $imgID    = $wip->wip_get_image($this->id);
    $points   = $wip->wip_get_points($this->id);

    // Affiche le message de succès en cas de modification des informations
    if ( isset( $_GET['save'] ) && $_GET['save'] == '1' ){
      require_once('success-message.php');
    }
    require_once('edit_image.php');
  }

  public function theme_add_image_page(){
    $wip      = new WPInteractivePictures();
    $imgID    = $wip->wip_get_image($this->id);
    $points   = $wip->wip_get_points($this->id);
    require_once('add_image.php');
  }
}
?>