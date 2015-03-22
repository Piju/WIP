<?php
  $pointerClass = (isset($p->pointerClass)) ? $p->pointerClass : WIP_DEFAULT_POINTER;
?>
<div class="panel-group" id="accordion-<?php echo $idCount;?>" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading-<?php echo $idCount;?>">
      <div class="row-actions visible">
        <span class="trash">
          <a href="<?php echo admin_url( 'admin.php?page=edit-image&id='.$_GET['id'].'&action=delete&idPoint='.$idCount );?>" class="submitdelete pull-right"><?php _e('Supprimer','wip');?></a>
        </span>
      </div>
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion-<?php echo $idCount;?>" href="#collapse-<?php echo $idCount;?>" aria-expanded="true" aria-controls="collapse-<?php echo $idCount;?>">
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
    <div id="collapse-<?php echo $idCount;?>" class="panel-collapse collapse fade" role="tabpanel" aria-labelledby="heading-<?php echo $idCount;?>" data-id="<?php echo $idCount;?>">
      <div class="panel-body">
        <div id="row-<?php echo $idCount;?>" class="row clearfix" data-id="<?php echo $idCount;?>">
          <div class="col-sm-4">
            <div class="form">
              <label for="title-<?php echo $idCount;?>">
                  <strong>
                    <?php _e('Titre','wip');?>
                  </strong>
                </label>
                <div class="input">
                  <input type="text" name="title-<?php echo $idCount;?>" id="title-<?php echo $idCount;?>" value="<?php echo $p->title;?>" size="40" class="widefat" />
                </div>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="form col-sm-4">
              <label for="thumb-<?php echo $idCount;?>">
                <strong>
                 <?php _e('Visuel attaché','wip');?>
                </strong>
              </label>
              <div class="input">
                <input id="thumb-<?php echo $idCount;?>" class="add_image_button button" type="button" value="<?php _e('Ajouter/Modifier le visuel', 'wip');?>" data-id="<?php echo $idCount;?>" />
                <input type="hidden" value="<?php echo $idCount_thumbnail;?>" id="thumbnail-<?php echo $idCount;?>" name="thumbnail-<?php echo $idCount;?>" data-id="<?php echo $idCount;?>" />
              </div>
              <div class="thumbnail">
                <?php
                  if( $idCount_thumbnail ):
                    echo wp_get_attachment_image( $idCount_thumbnail, 'thumbnail', '', array('class' => 'size-full aligncenter') );
                  endif;
                ?>
              </div>
            </div>
            <div class="form col-sm-4">
              <label for="pointerClass-<?php echo $idCount;?>">
                <strong>
                  <?php _e('Icône du pointeur','wip');?>
                </strong>
              </label>
              <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-default" data-icon="<?php echo $p->pointerClass;?>" data-cols="6" data-rows="5" role="iconpicker" data-icon="fa-wifi" data-iconset="fontawesome"></button>
                </span>
                <input type="text" id="pointerClass-<?php echo $idCount;?>" class="form-control" value="<?php echo $p->pointerClass;?>" name="pointerClass-<?php echo $idCount;?>" />
              </div>
            </div>
            <div class="form col-sm-4">
              <label>
                <strong>
                  <?php _e('Couleur du pointeur','wip');?>
                </strong>
              </label>
              <div class="input">
                <input class="colorpicker" type="text" value="<?php echo $p->pointerColor;?>" data-id="<?php echo $idCount;?>" name="color-<?php echo $idCount;?>" />
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form">
              <label for="description-<?php echo $idCount;?>">
                <strong>
                  <?php _e('Description','wip');?>
                </strong>
              </label>
              <div class="input">
                <textarea name="description-<?php echo $idCount;?>" id="description-<?php echo $idCount;?>" class="widefat"><?php echo stripslashes($p->description);?></textarea>
              </div>
            </div>
          </div>
          <input type="hidden" value="<?php echo $idCount;?>" name="idPoint-<?php echo $idCount;?>" />
        </div>
      </div>
    </div>
  </div>
</div>