<?php
if( ! class_exists( 'WP_List_Table' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WIP_List_Table extends WP_List_Table {

  function __construct(){
    global $status, $page, $wpdb;

    parent::__construct( array(
      'singular'  => __( 'image', 'mylisttable' ),     //singular name of the listed records
      'plural'    => __( 'images', 'mylisttable' ),   //plural name of the listed records
      'ajax'      => false        //does this table support ajax?
    ) );

  }

  function no_items() {
    _e( 'Il n\'y a aucune image pour le moment.' );
  }

  function column_default( $item, $column_name ) {
    $actions = $this->get_bulk_actions($item->id);

    switch( $column_name ) {
      case 'id':
      case 'title':
        return '<a href="'.admin_url().'admin.php?page=edit-image&id='.(int)$item->id.'" title="Edit">'.stripslashes($item->$column_name).'</a></strong>'.$this->row_actions( $actions );
      case 'id_thumbnail':
        return wp_get_attachment_image( $item->$column_name, 'thumbnail', '', array('class' => 'size-full aligncenter') );
      case 'shortcode':
        return '<code>[wip id="'.$item->id.'"]</code>';
        return $item->$column_name;
      default:
        return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }

  function get_sortable_columns() {
    $sortable_columns = array(
      'title'  => array('title',false)
    );
    return $sortable_columns;
  }

  function get_columns(){
    $columns = array(
      'cb'          => '<input type="checkbox" />',
      'title'       => __('Titre'),
      'id_thumbnail'=> __('Image'),
      'shortcode'   => __('Shortcode')
    );
     return $columns;
  }

  function usort_reorder( $a, $b ) {
    // If no sort, default to title
    $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'title';
    // If no order, default to asc
    $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
    // Determine sort order
    $result = strcmp( $a[$orderby], $b[$orderby] );
    // Send final sort direction to usort
    return ( $order === 'asc' ) ? $result : -$result;
  }

  function get_bulk_actions($id) {
    $actions = array(
      'edit'      => sprintf('<a href="?page=%s&id=%s">'.__('Editer').'</a>','edit-image',$id),
      'delete'    => sprintf('<a href="?page=%s&action=%s&image=%s">'.__('Supprimer').'</a>',$_REQUEST['page'],'delete',$id)
    );
    return $actions;
  }

  /** ************************************************************************
   * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
   * For this example package, we will handle it in the class to keep things
   * clean and organized.
   * 
   * @see $this->prepare_items()
   **************************************************************************/
  function process_bulk_action() {
    if( 'delete'===$this->current_action() ) {
      foreach($_GET['image'] as $image) {
        WPInteractivePictures::delete_image_plan($image);
      }

      wp_redirect( admin_url( 'admin.php?page=wp-interactive-pictures&save=1' ) );
      exit;

      //wp_die('Items deleted (or they would be if we had items to delete)!');
    /*}elseif( 'edit'===$this->current_action() ) {
        wp_die('Items edited (or they would be if we had items to edit)!');*/
    }
  }

  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="image[]" value="%s" />', $item->id
    );
  }

  function prepare_items() {
    global $wpdb;

    $per_page = $this->get_items_per_page('wip_images_per_page', 5);
    $current_page = $this->get_pagenum();

    /* Register the Columns */
    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array($columns, $hidden, $sortable);
    //$this->_column_headers = $this->get_column_info();

    $this->process_bulk_action();

    $query = "SELECT * FROM ".$wpdb->prefix."wip_image";

    /* -- Ordering parameters -- */
    //Parameters that are going to be used to order the result
    /* -- Pagination parameters -- */
    //Number of elements in your table?
    //How many to display per page?
    //Which page is this?
    //Page Number
    //How many pages do we have in total?
    //adjust the query to take pagination into account

    $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
    $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
    if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

    $totalitems = $wpdb->query($query);
    $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
    if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
    $totalpages = ceil($totalitems/$per_page);
    if(!empty($paged) && !empty($per_page)){
      $offset=($paged-1)*$per_page;
        $query.=' LIMIT '.(int)$offset.','.(int)$per_page;
    }

    /* -- Register the pagination -- */
    $this->set_pagination_args( array(
      "total_items" => $totalitems,
      "total_pages" => $totalpages,
      "per_page"    => $per_page,
    ) );
    //The pagination links are automatically built according to those parameters

    /* -- Fetch the items -- */
    $this->items = $wpdb->get_results($query);
  }
} //class

function wip_list_page(){
  global $wipListImage;
  $wipListImage = new WIP_List_Table();
  $wipListImage->prepare_items();
?>
  <div class="wrap">
    <h2>
      <?php _e('Images interactives', 'wip');?><a href="admin.php?page=add-image" class="add-new-h2"><?php _e('Ajouter','wip');?></a>
    </h2>
    <form method="get">
      <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
      <?php
        $wipListImage->search_box( __('Rechercher'), 'search_id' );
        $wipListImage->display();
      ?>
    </form>
  </div>
<?php
  }

  wip_list_page();