<div class="map">
  <?php
    echo wp_get_attachment_image( $imgID[0]->id_thumbnail, 'full' );
    $i = 1;
    foreach($points as $k => $p):
      $uniqid = uniqid();
      $icl_t = function_exists('icl_t');
      $image_atts = wp_get_attachment_image_src( $p->id_thumbnail, 'full' );
      $title = $icl_t ? icl_t('wip', 'title-'.$p->id, $p->title) : $p->title;
      $description = $icl_t ? icl_t('wip', 'description-'.$p->id, $p->description) : $p->description;
  ?>
    <div class="marker" data-id="<?php echo $p->id;?>" style="position: absolute; top: <?php echo $p->coordinatesY;?>%; left: <?php echo $p->coordinatesX;?>%;">
      <?php if( $p->title || $p->id_thumbnail || $p->description):?>
        <a href="#point-<?php echo $i;?>">
          <i class="fa fa-map-marker"></i>
        </a>
        <div id="point-<?php echo $i;?>" class="point" style="display: none;">
          <p class="title">
            <?php echo $title;?>
          </p>
          <a href="<?php echo $image_atts[0];?>" title="<?php echo $p->title;?>" class="thumbnail" data-lightbox="group:<?php echo $uniqid;?>">
            <?php echo wp_get_attachment_image( $p->id_thumbnail, 'medium' );?>
          </a>
          <div class="description">
            <?php echo stripslashes($description);?>
          </div>
        </div>
      <?php else:?>
          <i class="fa fa-map-marker"></i>
      <?php endif;?>
    </div>
  <?php
    $i++;
    endforeach;
  ?>
</div>