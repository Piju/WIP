<?php
  if ( current_user_can( 'manage_options' ) ){
?>
  <div class="wrap">
    <h1><?php _e('Ajouter une image interactive','wip');?></h1>
    <form method="post">
      <div id="titlediv">
        <div id="titlewrap">
          <label for="title" id="title-prompt-text"><?php _e('Saisissez le titre de l\'image ici','wip');?></label>
          <input id="title" name="title" value="<?php (isset($_POST['title']))?$_POST['title']:'';?>" />
        </div>
      </div>
      <div id="wp-content-editor-tools">
        <div>
          <label for="upload_image">
            <input id="upload_image_button" class="button" type="button" value="<?php _e('Uploader/modifier le plan', 'wip');?>" />
            <input type="hidden" value="" name="thumbnail" />
          </label>
        </div>
        <div id="map" class="map"></div>
      </div>
      <?php
        // Génère le bouton d'envoi et la vérification de sécurité WP
        submit_button( __('Sauvegarder', 'wip') );
        wp_nonce_field( 'save', 'save' );
      ?>
    </form>
  </div>
<?php
  // Si le test du nonce échoue, la fonction check_admin_referer() renverra un message d'erreur suivi d'un die
  if ( !empty( $_POST ) && check_admin_referer( 'save', 'save' ) ) {

    $wip = new WPInteractivePictures();
    $id = $wip->get_table_status('wip_image');
    
    WPInteractivePictures::wip_add_image($id, $_POST['title'], $_POST['thumbnail']);

    wp_redirect( admin_url( 'admin.php?page=edit-image&id='.$id ) );
    exit;
  }

// Fin de condition sur les droits utilisateurs
}else{
  _e("Vous n'êtes pas autorisé à modifier cette partie du Back-office", "wip");
}