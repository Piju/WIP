<div class="map">
  <?php
    $uniqid = uniqid();
    echo wp_get_attachment_image( $imgID[0]->id_thumbnail, 'full' );
    $i = 1;
    foreach($points as $k => $p):
      if( !empty($p->pointerClass) ){
        $pointerClass = $p->pointerClass;
      }else{
        $pointerClass = WIP_DEFAULT_POINTER;
      }
  ?>
    <div data-id="<?php echo $p->id;?>" style="position: absolute; top: <?php echo $p->coordinatesY;?>%; left: <?php echo $p->coordinatesX;?>%;">
      <?php if( $p->title || $p->id_thumbnail || $p->description):?>
        <a href="#" data-uk-toggle="{target:'#point-<?php echo $uniqid;?>-<?php echo $i;?>'}">
          <i class="<?php echo $pointerClass;?>"></i>
        </a>
      <?php else:?>
          <i class="<?php echo $pointerClass;?>"></i>
      <?php endif;?>
    </div>
  <?php
    $i++;
    endforeach;
  ?>
</div>
<?php 
  $j = 1;
  foreach($points as $k => $p):
    $icl_t = function_exists('icl_t');
    $image_atts = wp_get_attachment_image_src( $p->id_thumbnail, 'full' );
    $title = $icl_t ? icl_t('wip', 'title-'.$p->id, $p->title) : $p->title;
    $description = $icl_t ? icl_t('wip', 'description-'.$p->id, $p->description) : $p->description;
?>
  <div id="point-<?php echo $uniqid;?>-<?php echo $j;?>" class="point uk-hidden uk-clearfix">
    <h2>
      <?php echo $title;?>
    </h2>
    <?php echo wp_get_attachment_image( $p->id_thumbnail, 'medium', '', array('class' => 'alignright') );?>
    <div class="description">
      <?php echo stripslashes($description);?>
    </div>
    <hr/>
  </div>
<?php
  $j++;
  endforeach;
?>