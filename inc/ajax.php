<?php
class wipAjax{
  static function so_wp_ajax_function(){
    $wip = new WPInteractivePictures();
    $imgID = $wip->wip_get_image($_GET['id']);

    echo wp_get_attachment_image( $imgID, 'full' );
    wp_die();
  }

  static function so_wp_ajax_function_add_point_plan(){
    global $wpdb;

    $table_name = $wpdb->prefix . "wip_points";
    $wpdb->insert(
      $table_name,
      array(
        'coordinatesX' => $_POST['posX'],
        'coordinatesY' => $_POST['posY'],
        'title' => '',
        'description' => '',
        'id_thumbnail' => '',
        'pointerClass' => '',
        'pointerColor' => '',
        'idImage' => $_POST['id']
      ),
      array(
        '%f',
        '%f',
        '%s',
        '%s',
        '%d',
        '%s',
        '%s',
        '%d'
      )
    );
    wp_die();
  }

  static function so_wp_ajax_function_remove_point_plan(){
    global $wpdb;
    $id = $_POST['id'];

    $table_name = $wpdb->prefix . 'wip_points';

    $wpdb->delete( $table_name, array('id' => $id), $where_format = null );
    if( function_exists('icl_unregister_string') ){
      icl_unregister_string('wip', 'title-'.$id);
      icl_unregister_string('wip', 'description-'.$id);
    }

    wp_die();
  }

  static function so_wp_ajax_function_add_form_plan(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'wip_points';
    $sql = 'SHOW TABLE STATUS LIKE "'.$table_name.'"';
    $sqlResults = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_assoc($sqlResults);

    $idCount = $row['Auto_increment']-1;
    if( isset($p) ):
        $title = $p->title;
    else:
        $title = __("Editer les infos du points", "wip");
    endif;

    ob_start();

    require_once('add-form.php');

    $content = ob_get_contents();
    ob_end_clean();

    $result = json_encode(
      array(
        'id' => $idCount,
        'content' => $content
      )
    );

    echo $result;

    wp_die();
  }

  static function so_wp_ajax_function_update_point_plan(){
    global $wpdb;
    $id = $_POST['id'];

    $table_name = $wpdb->prefix . 'wip_points';

    $data = array(
      'coordinatesX' => $_POST['posX'],
      'coordinatesY' => $_POST['posY']
    );

    $wpdb->update( $table_name, $data, array( 'ID' => $id ), $format = null, $where_format = null );
    wp_die();
  }
}