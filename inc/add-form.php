<div id="postbox-container-<?php echo $idCount;?>" class="postbox-container postbox col-sm-12" data-id="<?php echo $idCount;?>">
  <div class="handlediv" title="Cliquer pour inverser.">
    <br>
  </div>
  <h3 class="hndle">
    <span><?php echo $title;?></span>
  </h3>
  <div class="inside">
    <div id="row-<?php echo $idCount;?>" class="row clearfix" data-id="<?php echo $idCount;?>">
      <div class="col-sm-6">
        <div class="form">
          <p>
              <strong>
                  <label for="title-<?php echo $idCount;?>"><?php _e('Titre','wip');?></label>
              </strong>
              <br />
              <input type="text" name="title-<?php echo $idCount;?>" id="title-<?php echo $idCount;?>" value="" size="40" class="widefat" />
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
            </div>
            <input class="add_image_button button" type="button" value="<?php _e('Ajouter/Modifier le visuel', 'wip');?>" data-id="<?php echo $idCount;?>" />
            <input type="hidden" value="" id="thumbnail-<?php echo $idCount;?>" name="thumbnail-<?php echo $idCount;?>" data-id="<?php echo $idCount;?>" />
          </p>
        </div>
        <div class="form col-sm-6">
          <p>
            <strong>
             <label><?php _e('Couleur du pointeur','wip');?></label>
            </strong>
            <br />
            <input class="colorpicker" type="text" value="" data-id="<?php echo $idCount;?>" name="color-<?php echo $idCount;?>" />
          </p>
        </div>
      </div>
      <div class="col-sm-12">
        <div class="form">
          <p>
            <strong>
                <label for="description-<?php echo $idCount;?>"><?php _e('Description','wip');?></label>
            </strong>
            <br />
            <textarea name="description-<?php echo $p->id;?>" id="description-<?php echo $p->id;?>" class="widefat"><?php echo stripslashes($p->description);?></textarea>
            <?php //wp_editor( stripslashes($p->description), 'description-<?php echo $idCount', $settings = array() ); ?>
          </p>
        </div>
      </div>
      <input type="hidden" value="<?php echo $idCount;?>" name="idPoint-<?php echo $idCount;?>" />
    </div>
  </div>
</div>