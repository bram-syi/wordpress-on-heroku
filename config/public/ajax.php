<?

define('XMLRPC_REQUEST', FALSE); // this turns off WP-Minify HTML minifcation

include_once('wp-load.php');
include_once('wp-includes/wp-db.php');
include_once('wp-admin/includes/user.php');
include_once('wp-includes/syi/syi-includes.php');

global $wpdb;
$error = '';

$field = $_REQUEST['edit_field'];
if (!empty($field)) {
  global $wpdb;
  if (!current_user_can('level_1')) {
    exit();
  }

  switch ($field) {
    case 'gift_notes':
      if (!isset($_POST['notes']))
        $_POST['notes'] = '';
      $key = 'id';
      break;

    case 'donorInfo':
      $key = 'donorID';
      break;
  }

  $upd = $wpdb->update($field, $_POST, array($key => $_POST[$key]));
  if ($upd == 0) {
    $upd = $wpdb->insert($field, $_POST);
  }
  unset($_POST[$key]);

  foreach ($_POST as $k=>$v) {
    echo $v;
    exit();
  }
  exit();
}

if(isset($_REQUEST['list'])){
  $return = '';
  $fields = array("list", "causes", "regions", "min_amt", "max_amt",
    "limit", "page", "blogs", "gifts", "focus", "tags");
  foreach($fields as $v) {
    if(isset($_REQUEST[$v])) {
      $$v = $_REQUEST[$v]; 
    }
  }
  
  echo get_list($list, $causes, $regions, $focus, $tags, $min_amt, $max_amt,
    $limit, $page, $blogs, $gifts);
  exit();                                                            
}

if (isset($_REQUEST['details'])) {
  $g = get_gift_details($_REQUEST['gift']);
  header("Content-Type: application/json");
  header("Cache-Control: max-age=30, must-revalidate");
  $str = json_encode($g);
  echo $str;
  exit();
}

if (isset($_REQUEST['giftinfo'])) {
  $g = get_gift_details($_REQUEST['giftinfo']);

  if ($g.towards_gift_id > 0)
    draw_agg_gift_details($g);
  else
    draw_gift_details($g);
  exit();
}

function get_tag_conditions($tags, $and=true){
  $conds = '';
  if (!empty($tags)){
    if(!is_array($tags)) $tags = explode(",",$tags);
    if($and)
      $conds .= " AND (";
    else
      $conds .= " OR (";

    $count = 0;
    foreach($tags as $tag){
      if($tag != ''){
        if($count>0) $conds .= " OR ";
        $tag = like_escape($tag);
        $conds .= " tags LIKE '%".$tag."%' ";
      }
      $count++;
    }
    $conds .= ") ";
  }
  return $conds;
}

function get_list($list, $causes, $regions, $groups, $tags,
  $min_amt, $max_amt, $limit, $page, $blogs, $gifts, $json=true) {
  global $wpdb;

  switch ($list) {
    case "gifts":
      $return = list_gifts($causes, $regions, $groups,
        $min_amt, $max_amt, $limit, $page);
      break;
    
    case 'featured_gift_sets':
      $sql = "SELECT * FROM featuredGiftSet";
      $return = array(
        'items' => $wpdb->get_results($sql)
      );
      break;

    case 'featured_content_tags':
      $sql = "SELECT * FROM featuredContent WHERE status='published' AND parent > 0";
      $return = array(
        'items' => $wpdb->get_results($sql)
      );
      break;

    case 'stories': 
      //ajax for giving wall
      //response: title, link, image, text, prices                                  
      $sql = $wpdb->prepare(
        "SELECT blog_id FROM wp_blogs WHERE blog_id IN (%s)",
        $blogs);      
      $blog_ids = $wpdb->get_col($sql);       
      
      foreach($blog_ids as $blog_id){
        $post_tbl = 'wp_'.strval($blog_id).'_posts';
        $sql = $wpdb->prepare(
          "SELECT post.post_title, post.post_content, post.guid, "
            . "image.guid"
            . "FROM %s AS post "
            . "LEFT JOIN %s AS image ON "
            . "(image.post_parent = post.ID "
            . "AND image.post_type = 'attachment' "
            . "AND image.post_mime_type "
            . "IN ('image/png','image/jpeg','image/jpg'))"
            . "WHERE post_type IN ('post', 'stories') "
            . "AND post_status = 'publish'",
            $post_tbl, $post_tbl);
      
        $results = $wpdb->get_results($sql);
      }
      break;

    default:
      die();
  }
    
  return $json ? json_encode($return) : $return;
}

////////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST['change_cart'])){//change cart call applies to 1 item only

  $cart_cmd = explode("|",$_REQUEST['change_cart']);

  if(count($cart_cmd)<2) return;
  
  //examples:
  //change_cart=1|88     -- add 1 itm #88 on cart #1
  //change_cart=1|88|0|2 -- add 2 itm #88 on cart #1
  //change_cart=1|88|1   -- update itm #88 qty to 1 on cart #1
  //change_cart=1|88|1|0 -- update itm #88 qty to 0 on cart #1 i.e. remove
  
  $cartID = intval($cart_cmd[0]);
  $giftID = intval($cart_cmd[1]);
  $update = !isset($cart_cmd[2])?0:intval($cart_cmd[2]);
  $quantity = !isset($cart_cmd[3])?1:intval($cart_cmd[3]);
  
  //validate if user is modifying the cart available for him
  $stored_cartID = get_cart();
  if($cartID != $stored_cartID) return false;

  return cart_add($cartID, $giftID, $quantity, $update?true:false);
}

////////////////////////////////////////////////////////////////////////////////

?>
