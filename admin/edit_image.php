<?php
  if ( current_user_can( 'manage_options' ) && isset( $_GET['id'] ) ){
?>
  <div class="wrap">
    <h2><?php _e('Editer une image interactive','wip');?></h2>
    <form method="post">
      <div id="titlediv">
        <div id="titlewrap">
          <label for="title" class="screen-reader-text" id="title-prompt-text"><?php _e('Saisissez le titre de l\'image ici','wip');?></label>
          <input id="title" name="title" value="<?php echo $imgID[0]->title;?>" />
        </div>
      </div>
      <div id="wp-content-editor-tools">
        <label for="upload_image">
          <input id="upload_image_button" class="button" type="button" value="<?php _e('Uploader/modifier le plan', 'wip');?>" />
          <input type="hidden" value="" name="thumbnail" />
        </label>
        <?php if( $imgID ):?>
          <p class="lead"><?php _e('Glissez/Déposez le marqueur pour ajouter un point sur votre plan de site','wip');?></p>
          <div class="plan" style="position:relative;">
            <span class="marker original">
              <i class="fa fa-map-marker"></i>
              <a href="#" class="destroy"><i class="glyphicon glyphicon-remove"></i></a>
            </span>
            <hr />
            <div id="map" class="map drop" data-id="<?php echo $_GET['id'];?>">
              <?php
                echo wp_get_attachment_image( $imgID[0]->id_thumbnail, 'full' );
                foreach($points as $k => $p):
              ?>
                <span id="marker-<?php echo $p->id;?>" class="marker drag" data-id="<?php echo $p->id;?>" style="position: absolute; top: <?php echo $p->coordinatesY;?>%; left: <?php echo $p->coordinatesX;?>%;">
                  <i class="fa fa-map-marker"></i>
                  <a href="#" class="destroy" style="display:inline-block;" data-id="<?php echo $p->id;?>"><i class="glyphicon glyphicon-remove"></i></a>
                </span>
              <?php
                endforeach;
              ?>
            </div>
          </div>
          <div class="edit">
            <fieldset>
              <legend>
                <h3>
                  <?php _e('Editer le contenu des points', 'wip');?>
                </h3>
              </legend>
            </fieldset>
              <?php
                $count = count($points);
                if( $count >= 1 ):
                  foreach($points as $k => $p):
                    $count = count($points);
              ?>
              <div id="postbox-container-<?php echo $p->id;?>" class="postbox postbox-container col-sm-12 closed" data-id="<?php echo $p->id;?>">
                <div class="handlediv" title="Cliquer pour inverser.">
                  <br>
                </div>
                <h3 class="hndle">
                  <?php
                    if( $p->title ):
                      $title = __('Editer les infos du points', 'wip').' : '.$p->title;
                    else:
                      $title = __('Editer les infos du points', 'wip');
                    endif;
                  ?>
                  <span><?php echo $title;?></span>
                </h3>
                <div class="inside">
                  <div id="row-<?php echo $p->id;?>" class="row clearfix" data-id="<?php echo $p->id;?>">
                    <div class="col-sm-6">
                      <div class="form">
                        <p>
                            <strong>
                              <label for="title-<?php echo $p->id;?>"><?php _e('Titre','wip');?></label>
                            </strong>
                            <br />
                            <input type="text" name="title-<?php echo $p->id;?>" id="title-<?php echo $p->id;?>" value="<?php echo $p->title;?>" size="40" class="widefat" />
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form col-sm-6">
                        <p>
                          <strong>
                           <label><?php _e('Visuel attaché','wip');?></label>
                          </strong>
                          <br />
                          <div class="thumbnail">
                            <?php
                              if( $p->id_thumbnail ):
                                echo wp_get_attachment_image( $p->id_thumbnail, 'thumbnail', '', array('class' => 'size-full aligncenter') );
                              endif;
                            ?>
                          </div>
                          <input class="add_image_button button" type="button" value="<?php _e('Ajouter/Modifier le visuel', 'wip');?>" data-id="<?php echo $p->id;?>" />
                          <input type="hidden" value="<?php echo $p->id_thumbnail;?>" id="thumbnail-<?php echo $p->id;?>" name="thumbnail-<?php echo $p->id;?>" data-id="<?php echo $p->id;?>" />
                        </p>
                      </div>
                      <div class="form col-sm-6">
                        <p>
                          <strong>
                           <label><?php _e('Couleur du pointeur','wip');?></label>
                          </strong>
                          <br />
                          <input class="colorpicker" type="text" value="<?php echo $p->pointerColor;?>" data-id="<?php echo $p->id;?>" name="color-<?php echo $p->id;?>" />
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form">
                        <p>
                          <strong>
                            <label for="description-<?php echo $p->id;?>"><?php _e('Description','wip');?></label>
                          </strong>
                          <br />
                          <?php wp_editor( stripslashes($p->description), 'description-'.$p->id, $settings = array() ); ?>
                        </p>
                      </div>
                    </div>
                    <input type="hidden" value="<?php echo $p->id;?>" name="idPoint-<?php echo $p->id;?>" />
                  </div>
                </div>
              </div>
            <?php
              endforeach;
              else:
            ?>
              <p class="info"><?php _e("Il n'y a aucun contenu à modifier pour le moment","wip");?></p>
            <?php
              endif;
              // Génère le bouton d'envoi et la vérification de sécurité WP
              submit_button( 'Mettre à jour' );
              wp_nonce_field( 'update', 'update' );
            ?>
          </div>
        </div>
      </form>
    <?php else:?>
      <div class="map drop"></div>
    <?php endif; //Fin de condition sur l'ID image plan?>
  </div>
<?php
  // Si le test du nonce échoue, la fonction check_admin_referer() renverra un message d'erreur suivi d'un die
  if ( !empty( $_POST ) && check_admin_referer( 'update', 'update' ) ) {
    foreach($points as $k => $p){
      $id = $p->id;
      WPInteractivePictures::add_infos_plan($_POST['idPoint-'.$id], $_POST['title-'.$id], $_POST['description-'.$id], $_POST['thumbnail-'.$id], $_POST['color-'.$id], $_GET['id']);
    }
    wp_redirect( admin_url( 'admin.php?page=edit-image&id='.$_GET['id'].'&save=1' ) );
    exit;
  }

// Fin de condition sur les droits utilisateurs
}else{
  _e("Vous n'êtes pas autorisé à modifier cette partie du Back-office");
}