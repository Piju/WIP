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
          <input id="upload_image_button" class="button" type="button" value="<?php _e('Uploader/modifier l\'image', 'wip');?>" />
          <input type="hidden" value="<?php echo $imgID[0]->id_thumbnail;?>" name="thumbnail" />
        </label>
        <?php if( $imgID ):?>
          <p class="lead"><?php _e('Glissez/Déposez le marqueur pour ajouter un point sur votre image interactive','wip');?></p>
          <div class="plan" style="position:relative;">
            <span class="marker original">
              <i class="<?php echo WIP_DEFAULT_POINTER;?>"></i>
            </span>
            <hr />
            <div id="map" class="map drop" data-id="<?php echo $_GET['id'];?>">
              <?php
                echo wp_get_attachment_image( $imgID[0]->id_thumbnail, 'full' );
                foreach($points as $k => $p):
                  $pointerClass = (isset($p->pointerClass)) ? $p->pointerClass : WIP_DEFAULT_POINTER;
              ?>
                <span id="marker-<?php echo $p->id;?>" class="marker drag" data-id="<?php echo $p->id;?>" style="position: absolute; top: <?php echo $p->coordinatesY;?>%; left: <?php echo $p->coordinatesX;?>%;">
                  <i class="<?php echo $pointerClass;?>" style="color:<?php echo $p->pointerColor;?>;"></i>
                </span>
              <?php
                endforeach;
              ?>
            </div>
          </div>
          <hr/>
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
                    if( !empty($p->pointerClass) ){
                      $pointerClass = explode(' ', trim($p->pointerClass));
                      $dataIcon     = $pointerClass[1];
                      $inputIcon    = $pointerClass[0].' '.$pointerClass[1];
                    }else{
                      $dataIcon     = WIP_DEFAULT_ICONPICKER_POINTER;
                      $inputIcon    = WIP_DEFAULT_POINTER;
                    }
                    $count = count($points);
              ?>
              <div class="panel-group" id="accordion-<?php echo $p->id;?>" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="heading-<?php echo $p->id;?>">
                    <div class="row-actions visible">
                      <span class="trash">
                        <a href="<?php echo admin_url( 'admin.php?page=edit-image&id='.$_GET['id'].'&action=delete&idPoint='.$p->id );?>" class="submitdelete pull-right"><?php _e('Supprimer','wip');?></a>
                      </span>
                    </div>
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion-<?php echo $p->id;?>" href="#collapse-<?php echo $p->id;?>" aria-expanded="true" aria-controls="collapse-<?php echo $p->id;?>">
                        <?php
                          if( $p->title ):
                            $title = __('Editer les infos du points', 'wip').' : '.$p->title;
                          else:
                            $title = __('Editer les infos du points', 'wip');
                          endif;
                        ?>
                        <span><?php echo $title;?></span>
                      </a>
                    </h4>
                  </div>
                  <div id="collapse-<?php echo $p->id;?>" class="panel-collapse collapse fade" role="tabpanel" aria-labelledby="heading-<?php echo $p->id;?>" data-id="<?php echo $p->id;?>">
                    <div class="panel-body">
                      <div id="row-<?php echo $p->id;?>" class="row clearfix" data-id="<?php echo $p->id;?>">
                        <div class="col-sm-4">
                          <div class="form">
                            <label for="title-<?php echo $p->id;?>">
                                <strong>
                                  <?php _e('Titre','wip');?>
                                </strong>
                              </label>
                              <div class="input">
                                <input type="text" name="title-<?php echo $p->id;?>" id="title-<?php echo $p->id;?>" value="<?php echo $p->title;?>" size="40" class="widefat" />
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-8">
                          <div class="form col-sm-4">
                            <label for="thumb-<?php echo $p->id;?>">
                              <strong>
                               <?php _e('Visuel attaché','wip');?>
                              </strong>
                            </label>
                            <div class="input">
                              <input id="thumb-<?php echo $p->id;?>" class="add_image_button button" type="button" value="<?php _e('Ajouter/Modifier le visuel', 'wip');?>" data-id="<?php echo $p->id;?>" />
                              <input type="hidden" value="<?php echo $p->id_thumbnail;?>" id="thumbnail-<?php echo $p->id;?>" name="thumbnail-<?php echo $p->id;?>" data-id="<?php echo $p->id;?>" />
                            </div>
                            <div class="thumbnail">
                              <?php
                                if( $p->id_thumbnail ):
                                  echo wp_get_attachment_image( $p->id_thumbnail, 'thumbnail', '', array('class' => 'size-full aligncenter') );
                                endif;
                              ?>
                            </div>
                          </div>
                          <div class="form col-sm-4">
                            <label for="pointerClass-<?php echo $p->id;?>">
                              <strong>
                                <?php _e('Icône du pointeur','wip');?>
                              </strong>
                            </label>
                            <div class="input-group">
                              <span class="input-group-btn">
                                  <button class="btn btn-default" data-cols="6" data-rows="5" role="iconpicker" data-prefix="fa" data-iconset="fontawesome" data-icon="<?php echo $dataIcon;?>"></button>
                              </span>
                              <input type="text" id="pointerClass-<?php echo $p->id;?>" class="form-control" value="<?php echo $inputIcon;?>" name="pointerClass-<?php echo $p->id;?>" />
                            </div>
                          </div>
                          <div class="form col-sm-4">
                            <label>
                              <strong>
                                <?php _e('Couleur du pointeur','wip');?>
                              </strong>
                            </label>
                            <div class="input">
                              <input class="colorpicker" type="text" value="<?php echo $p->pointerColor;?>" data-id="<?php echo $p->id;?>" name="color-<?php echo $p->id;?>" />
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form">
                            <label for="description-<?php echo $p->id;?>">
                              <strong>
                                <?php _e('Description','wip');?>
                              </strong>
                            </label>
                            <div class="input">
                              <?php wp_editor( stripslashes($p->description), 'description-'.$p->id, $settings = array() ); ?>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" value="<?php echo $p->id;?>" name="idPoint-<?php echo $p->id;?>" />
                      </div>
                    </div>
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
    WPInteractivePictures::wip_update_image($_GET['id'], $_POST['title'], $_POST['thumbnail']);

    foreach($points as $k => $p){
      $id = $p->id;
      WPInteractivePictures::wip_add_point_infos($_POST['idPoint-'.$id], $_POST['title-'.$id], $_POST['description-'.$id], $_POST['thumbnail-'.$id], $_POST['pointerClass-'.$id], $_POST['color-'.$id], $_GET['id']);
    }
    wp_redirect( admin_url( 'admin.php?page=edit-image&id='.$_GET['id'].'&save=1' ) );
    exit;
  }

  if( isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['idPoint']) ){
    WPInteractivePictures::wip_remove_point_plan($_GET['idPoint']);
    wp_redirect( admin_url( 'admin.php?page=edit-image&id='.$_GET['id'].'&save=1' ) );
    exit;
  }

// Fin de condition sur les droits utilisateurs
}else{
  _e("Vous n'êtes pas autorisé à modifier cette partie du Back-office");
}