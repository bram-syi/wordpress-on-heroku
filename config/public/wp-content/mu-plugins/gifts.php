<?php
/*
Plugin Name: Charity gifts
Description: Defines the gifts structures
*/

global $blog_id;
if ($blog_id > 1) {

  add_action('init','init_gifts');
  function init_gifts() {
    // Create a "gift" type for small widget-like content
    register_post_type( 'gift' , array(
      'labels' => array(
        'name' => _x('Gifts','general name'),
        'singular_name' => _x('Gift','singular name'),
        'add_new' => __('Add New Gift'),
        'add_new_item' => __('Add New Gift'),
        'edit_item' => __('Edit Gift'),
        'new_item' => __('New Gift'),
        'view_item' => __('View Gift'),
        'search_items' => __('Search Gifts'),
        'not_found' => __('No Gifts Found'),
        'not_found_in_trash' => __('No Gifts Found in Trash'),
        'parent_item_colon' => ''
      ),
      'public' => false,
      'publicly_queryable' => true,
      'show_ui' => false,
      'capability_type' => 'page',
      'hierarchical' => false,
      'rewrite' => array('slug'=>'donate'),
      'query_var' => 'gift',
      'menu_position' => 8,
	  'register_meta_box_cb' => 'init_gift_fields',
      'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
    ));
  }
  function update_gift_info($id) {
    global $wpdb;
    global $blog_id;

    $gift_id = $wpdb->get_var($wpdb->prepare(
      "SELECT g.id FROM gift g WHERE g.blog_id=%d AND g.post_id=%d", 
      $blog_id, $id));
    if ($gift_id == 0)
      return;

    $gift = get_post($id);
    $wpdb->query($wpdb->prepare(
      "update gift g set g.excerpt='%s', g.title='%s', g.description='%s', g.image=%s
       where g.id = %d", 
       $gift->post_excerpt, $gift->post_title, $gift->post_content, SITE_URL . "/wp-content/gift-images/Gift_{$gift_id}.jpg",
       $gift_id));

    if (has_post_thumbnail( $gift->ID )) {
      $image = wp_get_attachment_image_src( get_post_thumbnail_id( $gift->ID ),array(320,240)); 
      $uploads = wp_upload_dir();
      $src = str_replace($uploads['baseurl'], $uploads['basedir'], $image[0]);
      $dest = WP_CONTENT_DIR . "/gift-images/";
      $name = "Gift_$gift_id.jpg";
      exec("convert -auto-orient $src {$dest}$name");
    }

  }
  add_action('publish_gift', 'update_gift_info');

  add_action('admin_menu', 'add_gift_page', 100); // so it can replace BuddyPress for charity sites
  function add_gift_page()
  {
    global $blog_id;

    $level = ($blog_id > 1) ? 'author' : 'manage_network';
    add_menu_page('Donations', 'Donations', $level, "donations", 'show_received_gifts', null, 3);

    /* Steve: removed
     $manage_page = add_submenu_page($slug, 'Manage Gifts', 'Manage Gifts', 'editor', "manage-gifts", 'show_manage_gifts_page');

    if ($blog_id > 1) {
      add_submenu_page('edit.php?post_type=gift', 'Available Gifts', 'Available', 'author', __FILE__ . '-active', 'show_active_gifts');
      add_submenu_page('edit.php?post_type=gift', 'Disabled Gifts', 'Archived', 'manage_network', __FILE__ . '-inactive', 'show_inactive_gifts');
    }

    */

    // add_action("admin_print_scripts-$manage_page", 'add_manage_gifts_scripts');
  }

  function donations_widget() {
  ?>
    <?php _e('Select a list of donations to view:'); ?>
    <ul style="margin-left: 30px;">
      <li><b><a href="/wp-admin/admin.php?page=donations&pageName=details">Detailed list of all donations</a></b></li>
      <li><a href="/wp-admin/admin.php?page=donations&pageName=details&feedback=1,3&order=old">Awaiting publication of impact story</a> (<a href="/wp-admin/admin.php?page=donations&pageName=details&feedback=1,3&aggr=yes&order=old">only aggregated</a>)</li>
    </ul>
  <?php
  }

  function add_donation_widget() {
    global $blog_id;

    $level = ($blog_id > 1) ? 2 : 8;
    if (current_user_can('level_$level')) {
      wp_add_dashboard_widget('donations_widget', 'Donations', 'donations_widget');
    }
  }
  add_action('wp_dashboard_setup', 'add_donation_widget' );
}

// The following used to be giftcontrol.php

function check_gift_edit($list) {
  if($_POST['Cancel'] != null) {
    wp_redirect($list->url);  // Discard POST
    return false;
  }

  $id = $_REQUEST['gift_id'];
  if($id != null) {
    $form = new GiftForm($list, $id);
    if($_REQUEST['action'] != null)
      $done = $form->perform($_REQUEST['action']);
    else
      $form->load($id);

    if(!$done) {
      include(WP_CONTENT_DIR . "/plugins/Gift-admin/admin-editgift.php");
      return true;
    }
  }

  return false;
}

function check_gift_details() {
  if(isset($_POST['gift_id'])) {
    $id = $_POST['gift_id'];
  }else{
    $id = $_REQUEST['gift_id'];
  }

  if(isset($_POST['towards_gift_id'])) {
    $towards_gift_id = $_POST['towards_gift_id'];
  }else{
    $towards_gift_id = 0;
  }

  if(isset($_GET['pageName']) && $_GET['pageName'] == 'details') {
    $giftDetails=new GiftDetails( intval($id), intval($towards_gift_id) );
    include(WP_CONTENT_DIR . "/plugins/Gift-admin/admin-gift-details.php");
    return true;
  }
  return false;
}

function check_history_page() {
  if(isset($_GET['donationId']) && (intval($_GET['donationId'])!=0) ) {
    $giftDonation = new GiftDonation(intval($_GET['donationId']));
    include(WP_CONTENT_DIR . "/plugins/Gift-admin/admin-history.php");
    return true;
  }else{
    return false;
  }
}

function show_active_gifts() {
  $list = new GiftList('Available (active) gifts');
  $list->canEdit = true;
  $list->canAdd = true;
  $list->canDelete = true;
  if(!check_gift_edit($list)) {
      $list->getActiveGifts();
      include(WP_CONTENT_DIR . "/plugins/Gift-admin/admin-giftlist.php");
  }
}

function show_inactive_gifts() {
  $list = new GiftList('Archived (inactive) gifts');
  $list->canEdit = true;
  $list->isInactive = true;
  if(!check_gift_edit($list)) {
    $list->getInactiveGifts();
    include(WP_CONTENT_DIR . "/plugins/Gift-admin/admin-giftlist.php");
  }
}

function show_received_gifts() {    
    if(!(check_gift_details() || check_history_page())) {
      ?><h2>Received Donations</h2><?
      donations_widget();
    }
}

class GiftList{
  public $blogId = null;
  public $gifts = array();
  public $status = "";
  public $title = null;
  public function __construct($title = "Gift List")
  {
    global $blog_id;

    $this->blogId = $blog_id;
    $urlEntry = remove_query_arg('action', $_SERVER['REQUEST_URI']);
    $this->url = str_replace( '%7E', '~', remove_query_arg('gift_id',$urlEntry));
    $this->title = $title;
  }

  public function getLinkTo($id) {
    return add_query_arg('gift_id', $id, $this->url);
  }

  public function rs2gift($rs) {

  global $wpdb;
  $total_donated = $wpdb->get_var(
    $wpdb->prepare("SELECT COUNT(*) FROM donationGifts WHERE giftID = %d",
    $rs['id']));

  $gift = array(
      'id' => $rs['id'],
      'txtDispNm' => stripslashes($rs['displayName']),
      'txtPluralNm' => stripslashes($rs['pluralName']),
      'txtDesc' => stripslashes($rs['description']),
      'excerpt' => stripslashes($rs['excerpt']),
      'txtUnitAmt' => sprintf("%d", $rs['unitAmount']),
      'txtGiftQuantity' => sprintf("%d", $rs['unitsWanted']),
      'previos_gift' => stripslashes($rs['previos_gift']),
      'tags' => stripslashes($rs['tags']), 
      'txtGiftReceived' => $total_donated,//stripslashes($rs['unitsDonated']),
      'link_href' => stripslashes($rs['link_href']),
      'link_text' => stripslashes($rs['link_text']),
      'towards_gift_id' => $rs['towards_gift_id'],
      'current_amount' => $rs['current_amount'],
      'var_amount' => $rs['varAmount'],
      'campaign' => $rs['campaign'],
      'post_id' => $rs['post_id']
    );

    return $gift;
  }

  public function gift2rs($gift) {
    $rs = array(
      'id' => $gift['id'],
      'displayName' => $gift['txtDispNm'],
      'pluralName' => $gift['txtPluralNm'],
      'description' => $gift['txtDesc'],
      'excerpt' => $gift['excerpt'],
      'unitAmount' => $gift['txtUnitAmt'],
      'unitsWanted' => $gift['txtGiftQuantity'],
      'previos_gift' => $gift['previos_gift'],
      'tags' => $gift['tags'],
      'link_href' => $gift['link_href'],
      'link_text' => $gift['link_text'],
      'towards_gift_id' => $gift['towards_gift_id'],
      'current_amount' => $gift['current_amount'],
      'varAmount' => $gift['var_amount'],
      'campaign' => $gift['campaign'],
      'post_id' => $gift['post_id']
    );

    return $rs;
  }

  public function gift2html($gift) {
    $html = array(
      'gift_id' => htmlentities($gift['id']),
      'gift_name' => htmlentities($gift['txtDispNm']),
      'gift_name_plural' => htmlentities($gift['txtPluralNm']),
      'gift_desc' => htmlentities($gift['txtDesc']),
      'gift_excerpt' => htmlentities($gift['excerpt']),
      'gift_cost' => htmlentities($gift['txtUnitAmt']),
      'gift_quantity' => htmlentities($gift['txtGiftQuantity']),
      'gift_previous' => htmlentities($gift['previos_gift']),
      'gift_tags' => htmlentities($gift['tags']),
      'gift_received' => htmlentities($gift['txtGiftReceived']),
      'gift_link_text' => htmlentities($gift['link_text']),
      'gift_link_href' => htmlentities($gift['link_href']),
      'towards_gift_id' => htmlentities($gift['towards_gift_id']),
      'current_amount' => htmlentities($gift['current_amount']),
      'var_amount' => intval($gift['var_amount']),
      'campaign' => htmlentities($gift['campaign']),
      'post_id' => htmlentities($gift['post_id']),
    );
    return $html;
  }

  public function post2gift($post) {
    $gift = array(
      'id' => $post['gift_id'],
      'txtDispNm' => trim($post['gift_name']),
      'txtPluralNm' => trim($post['gift_name_plural']),
      'txtDesc' => trim($post['gift_desc']),
      'excerpt' => trim($post['excerpt']),
      'txtUnitAmt' => trim($post['gift_cost']),
      'txtGiftQuantity' => sprintf("%d", $post['gift_quantity']),
      'tags' => trim($post['gift_tags']),
      'previos_gift' => '0',
      'link_text' => $post['gift_link_text'],
      'link_href' => $post['gift_link_href'],
      'towards_gift_id' => $post['towards_gift_id'],
      'current_amount' => $post['current_amount'],
      'var_amount' => intval($post['var_amount']),
      'campaign' => $post['campaign'],
    'post_id' => $post['post_id']
    );
    return $gift;
  }

  public function sql2gifts($sql) {
    $gifts = array();
    $results = mysql_query($sql) or die(mysql_error());
    if($results) {
      if(mysql_num_rows($results) > 0) {
        while ($rs = mysql_fetch_array($results)) {
            $gifts[] = $this->rs2gift($rs);
        }
      }
    }
    return $gifts;
  }
  
  public function findGiftId($id) {
    for ($i = 0; $i < count($this->gifts); $i++) {
      if($this->gifts[$i]['id'] == $id)
        return $this->gifts[$i];
    }
    return null;
  }

  public function getGift($id) {
    $sql = sprintf("SELECT * FROM gift WHERE id='%s' AND blog_id='%s'",
      mysql_real_escape_string($id),
      mysql_real_escape_string($this->blogId));
    $this->gifts = $this->sql2gifts($sql);

    return $this->gifts[0];
  }

  public function getActiveGifts($set = '') {
    global $wpdb;
    $sql = "SELECT * FROM gift WHERE active='1' AND blog_id=%d";
    if(!empty($set))
    $sql = $sql . " AND tags LIKE '%s' OR campaign LIKE '%s'";
    $sql = $wpdb->prepare($sql
      . " ORDER by unitAmount asc", $this->blogId, '%' . $set . '%', '%' . $set . '%');
    $this->gifts = $this->sql2gifts($sql);
    return $this->gifts;
  }

  public function getInactiveGifts() {
    $sql = sprintf("SELECT * FROM gift WHERE active='0' AND blog_id='%s' ORDER by unitAmount asc",
      mysql_real_escape_string($this->blogId));
    $this->gifts = $this->sql2gifts($sql);
    return $this->gifts;
  }

  public function getReceivedGifts($orderValue) {
    if(isset($orderValue) && strlen($orderValue) > 0 ) {
      $sql = sprintf("SELECT * FROM gift "
        . "WHERE id in(select giftID from gift_Donations)AND blog_id='%s' ORDER by %s desc", 
        mysql_real_escape_string($this->blogId), mysql_real_escape_string($orderValue));
      $this->getOrderType($orderValue);
    }
    else
    {
      $sql = sprintf("SELECT * FROM gift "
        . "WHERE id in(select giftID from gift_Donations)AND blog_id='%s' ORDER by id desc",
        mysql_real_escape_string($this->blogId));
    }
    // $sql = sprintf("SELECT * FROM gift WHERE active='1' and unitsDonated > 0 AND blog_id='%s' ORDER by id desc",
    //         mysql_real_escape_string($this->blogId));
    $this->gifts = $this->sql2gifts($sql);
    return $this->gifts;
  }

  public function getOrderType($orderValue) {
   switch ($orderValue) {
     case "id":
         return $this->setStatus("ok", "The gift has been ordered on Gift ID" );
     case "displayName":
         return $this->setStatus("ok", "The gift has been ordered on Gift Name" );
     case "unitAmount":
         return $this->setStatus("ok", "The gift has been ordered on Gift amount" );
     default:
         return;
   }
  }

  function getFirstWord($giftDesc) {
    $descWords = explode(" ",$giftDesc);
    return $descWords[0];
  }

  public function saveGift(&$gift) {
    global $wpdb;
  global $blog_id;

    if(strlen($gift['txtDispNm']) == 0)
      return $this->setStatus("error", "You must enter a name");
    if(!ctype_digit($gift['txtUnitAmt']))
      return $this->setStatus("error", "You must enter decimal value for Cost");

    $gift['txtUnitAmt'] = sprintf("%d",$gift['txtUnitAmt']);        
    if($gift['txtUnitAmt'] <= 0 )
      return $this->setStatus("error", "You must enter a cost");
    
    if($gift['txtGiftQuantity'] < 0)
      return $this->setStatus("error", "You must enter a positive quantity");

    $gift['current_amount'] = str_replace("$", "", $gift['current_amount']);
    if(strlen($gift['tags']) == 0) {
        //return $this->setStatus("error", "You must enter one or more tags");
        $gift['tags'] = $this->getFirstWord($gift['txtDispNm']);
    }
    $gift['tags'] = strtolower($gift['tags']);

    $rs = $this->gift2rs($gift);
    if(function_exists('createCampaign'))
      createCampaign($rs['campaign']);

  global $wpdb;
 
  $id = intval($gift['id']);
  $post_id = $wpdb->get_var($wpdb->prepare("select post_id from gift where id=%d", $id));

  if ($id > 0) {
      $sql = $wpdb->prepare("UPDATE gift "
        . "SET blog_id=%d, displayName='%s', pluralName='%s', description='%s', "
        . "unitAmount=%f, varAmount=%d, unitsWanted=%d, "
        . "tags='%s', link_href='%s', link_text='%s', "
        . "towards_gift_id=%d, campaign='%s', current_amount=%d, post_id=%d "
        . "WHERE id=%d",
          $this->blogId, $rs['displayName'], $rs['pluralName'], $rs['description'], 
          $rs['unitAmount'], $rs['varAmount'], $rs['unitsWanted'], 
          $rs['tags'], $rs['link_href'], $rs['link_text'], 
          $rs['towards_gift_id'], $rs['campaign'], $rs['current_amount'], 
      $post_id, $id );
      $result = mysql_query($sql) or die(mysql_error() . " in saveGift: " . $sql);
  } else {
      $sql = $wpdb->prepare("INSERT INTO gift"
        . " (blog_id, displayName, pluralName, description, "
        . "unitAmount, varAmount, unitsWanted, "
        . "tags, link_href, link_text, "
        . "towards_gift_id, campaign, current_amount, post_id) "
        . "VALUES(%d, '%s', '%s', '%s', %f, %d, %d, '%s', '%s', '%s', %d, '%s', %d, %d)",
          $this->blogId, $rs['displayName'], $rs['pluralName'], $rs['description'], 
          $rs['unitAmount'], $rs['varAmount'], $rs['unitsWanted'], 
          $rs['tags'], $rs['link_href'], $rs['link_text'], 
          $rs['towards_gift_id'], $rs['campaign'], $rs['current_amount'], $post_id );
      $result = mysql_query($sql) or die(mysql_error() . " in saveGift: " . $sql);
      $gift['id'] = mysql_insert_id();
    $id = $gift['id'];
    }

  if ($post_id == 0) { 
     global $user_id;
     global $blog_id;

     $name = $rs['displayName'];
     $desc = $rs['description'];
     $title = "Give $name";
     $slug = sanitize_title_with_dashes($name);

     $post_id = db_new_page($blog_id, $user_id, $title, "<p>$desc</p>", $slug,0,0,'gift', $desc);
     add_post_meta($post_id, 'gift_id', $id);
     $wpdb->query($wpdb->prepare("update gift set post_id=%d where id=%d", $post_id, $id));
  }
  update_gift_info($post_id);

    return $this->setStatus("ok", "The gift has been added");
  }

  public function removeGift($id) {
    global $wpdb;
    if($id == null || $id == "new")
        return false;
    $result = mysql_query($wpdb->prepare(
      "UPDATE gift SET active='0' WHERE id=%d and blog_id = %d", $id, $this->blogId))
      or die(mysql_error() . " in deleteGift: " . $sql);
    return $this->setStatus("ok", "The gift has been removed");
  }

  public function setStatus($status, $message) {
    if($status == "ok")
    $status = "updated fade";

    $this->status = '<div id="statusMessage" class="' . $status . '"><p><strong>' . htmlentities($message) . '</strong></p></div>';
    return !($status == 'error');
  }

}//class

class GiftForm{
  public $blogId = null;
  public $gifts = array();
  public $status = "";
  public $title = "Edit Gift";
  public $button = "Save Changes";

  public function __construct($list, $id) {
    global $blog_id;
    $this->blogId = $blog_id;

    if(!isset($list)) $list = new GiftList();
    $this->list = $list;
    $this->id = $id;
    if($id == 'new') {
      $this->title = "Add a Gift";
      $this->button = "Save Gift";
    }
  }

  public function load($id) {
    $this->gift = $this->list->getGift($id);
  }

  public function perform($action) {
    switch ($action) {
      case "save":
        $this->gift = $this->list->post2gift($_POST);
        return $this->list->saveGift($this->gift);
      case "delete":
        return $this->list->removeGift($this->id);
      case "new":
      default:
        $this->gift = array('id' => 'new'); //Empty
        return false;
    }
  }

  public function getPostVars() {
    return $this->list->gift2html($this->gift);
  }
}//class

class GiftDetails{
  public $id=0;
  public $active=1;
  public $donatedBy;
  public $status = null;
  public $blogId = null;
  public $dbStatusValues = array();
  public $giftListObj;
  public $filterStatus;
  public $towards_gift_id;
  public $selectAll;
  public $donationHistory = array(); 
  public function __construct($id, $towards_gift_id) {
      
  global $blog_id;

  $this->blogId = $blog_id;
  $this->status= array();
  $this->donatedBy=array();
  $this->donationHistory=array();
  $this->giftListObj = new GiftList();
  $this->id=$id;
  $this->towards_gift_id=$towards_gift_id;
  $this->selectAll='';
  $this->populateDbStatusValues();
  $this->showSearchDetailsPage($id);
        
}//constructor
    
  /**
   * Showing the details page based on the input from the search page
   * 
   *giftid is given for future enhancement
   * @param gift $id
   */
  public function showSearchDetailsPage($id)
  {
      $this->filterValues();
      $this->updateDonationStatus();
      $this->populateDonorDetails($this->getDonorDetailsQuery($id));
  }
    
  /**
   * function to show the filter values on the search details page.
   * if the count is 11, means that all the filter values are selected
   * then it should show filtere by all else it will fetch the values from db
   * and showing according to the check box selected from the search page
   *
   */
  
  public function filterValues() {    
    $vals = array_filter(array_map('intval', explode(',', $_GET['money'])));
    if(count($vals) > 0) {
      $sql = sprintf("select transfer_status from money_transfer_status where id in (%s)", implode(',', $vals));
      $result = mysql_query($sql) or die(mysql_error());
      $conds = array();
      while($row = mysql_fetch_array($result)) {
        $conds[] = "<b>" . $row['transfer_status'] . "</b>";
        $count++;
      }
      $this->filterStatus[] = "Payment is " . implode(' or ', $conds) . ".";
    }

    $vals = array_filter(array_map('intval', explode(',', $_GET['distribution'])));
    if(count($vals) > 0) {
      $sql = sprintf("select distribution_status from item_distribution_status where id in (%s)", implode(',', $vals));
      $result = mysql_query($sql) or die(mysql_error());

      $conds = array();
      while($row = mysql_fetch_array($result)) {
        $conds[] = "<b>" . $row['distribution_status'] . "</b>";
        $count++;
      }
      $this->filterStatus[] = "Gift is " . implode(' or ', $conds) . ".";
    }

    $vals = array_filter(array_map('intval', explode(',', $_GET['feedback'])));
    if(count($vals) > 0) {
      $sql = sprintf("select feedback_status from impact_feedback_status where id in (%s)", implode(',', $vals));
      $result = mysql_query($sql) or die(mysql_error());
  
      $conds = array();
      while($row = mysql_fetch_array($result)) {
        $conds[] = "<b>" . $row['feedback_status'] . "</b>";
        $count++;
      }
      $this->filterStatus[] = "Impact story is " . implode(' or ', $conds) . ".";
    }

    if($count == 11) $this->selectAll = "All";
  }
    
  /*********Dymanic query generation to populate the gift details*********/
  /**
   * query generated to popluate the donation details wrt to the donation status from the search page inputs
   *
   * @param  $gift_id
   * @return query to get donor details
   */
  public function getDonorDetailsQuery($gift_id) {
      global $wpdb;
    $hidetest = false;
      $queryArray = array();

      $values = array_filter(array_map('intval', explode(",", $_GET['feedback'])));
      foreach ($values as $val) {
        if($val == 1) {
          $conds[] = "dg.story IS NULL OR (dg.story != 0 AND wp.post_title IS NULL)"; // missing or deleted
      $hidetest = true;
    } else if($val == 3) {
          $conds[] = "wp.post_status != 'publish'";
          $hidetest = true;
        } else if($val == 4) {
          $conds[] = "dg.story = 0 OR wp.post_status = 'publish'";
      $hidetest = true;
    }
    }
      if(count($conds) > 0)
        $queryArray[] = '(' . implode(' OR ', $conds) . ')';
           
      $vals = array_filter( array_map('intval', explode(",", $_GET['money'])));
      if(count($vals) > 0) {
        $vals = implode(',', $vals);
        $queryArray[] = "dg.fundTransferStatus in ($vals)";
      }
   
      $vals = array_filter( array_map('intval', explode(",", $_GET['distribution'])));
      if(count($vals) > 0) {
        $vals = implode(',', $vals);
        $queryArray[] = "dg.distributionStatus in ($vals)";
      }

      switch ($_GET['order']) {
        case 'new': $order = 'donation.donationDate desc'; break;
        case 'old': $order = 'donation.donationDate asc'; break;
        default: $order = 'donation.donationDate desc'; break;
      }

      $conds = array();
      $vals = array_filter( explode(",", $_GET['aggr']));
  foreach ($vals as $val) {
    switch ($val) {
      case 'yes': $conds[] = "gf.towards_gift_id != 0"; break;
      case 'no': $conds[] = "gf.towards_gift_id = 0"; break;
        }
  }
      if(count($conds) > 0)
        $queryArray[] = '(' . implode(' OR ', $conds) . ')';

    if($hidetest)
    $queryArray[] = " donation.test=0 ";

      return $this->select(join(" AND ", $queryArray), $order);
  }

    function select($pred, $order = 'donation.donationDate desc')
    {
      if(!empty($pred))
        $pred = "AND $pred";

      return "SELECT *, dg.id as item_id, wp.id as post_id, 
             dg.tip as unit_tip, dg.matchingDonationAcctTrans as matched 
             FROM donationGifts dg
             LEFT JOIN donation ON donation.donationID = dg.donationID
             LEFT JOIN donationGiver donor ON donor.id = donation.donorID
             LEFT JOIN gift gf ON gf.id = dg.giftID
             LEFT JOIN wp_" . $this->blogId . "_posts wp ON wp.id = dg.story
                       WHERE (dg.blog_id = $this->blogId OR $this->blogId=1) $pred
                       ORDER BY $order LIMIT 300";
       // Steve: ok, I know the WHERE is crazy.
       // We really want to just remove the blog_id condition in case of 1
       // but I don't have time to fix $pred so it works when there is no
       // condition before it.
    }

    /**
     * populate the donor details 
     *
     * @param $sql - query to fetch the donoation details
     * @return giftDetails 
     */
    public function populateDonorDetails($sql) {
        global $wpdb;
        if($_GET['sql'] == "yes")
          echo $sql;
        $result=mysql_query($sql);
        if( !( mysql_num_rows($result) > 0 ) )
          return null;
        while( $row = mysql_fetch_assoc( $result ) ) {

            $story = $row['story'];
            if($story === null)
              $story = "";
            else {
              $status = $row['post_status'];
              if($status == "publish")
                $status = "published";

              if($story == 0)
                $story = "published";
              else if($status == null)
                $story = "";
              else {
                $story_title = stripslashes(htmlentities(trim($row['post_title'])));
                if($story_title == '') $story_title = '(Untitled Story)';
                $story = $status . ': <a href="/publish/?ID=' . $story . '">' . $story_title . '</a> '
                  . ($status=='published'?'('.date('m/d',strtotime($row['post_modified'])).')':'');
              }
            }

            $fbconnect_val = NULL; 

            $tg = get_avg_tgi($row['giftID'], true);
            if ( $tg!=NULL ) {  
              $row['displayName'] = AVG_NAME_PREFIX.$tg->displayName;
            }

            $item = array(
                'name'=>$row['displayName'],
                'donor'=>
                  '<span style="cursor:help;" title="' 
                    . ($row['sendUpdates']==1 ? as_email($row['email']) : '')
                    . ' '
                    . (!empty($fbconnect_val) ? $fbconnect_val : '')
                    . '">'
                  . $row['firstName'] 
				  . (!empty($row['onbehalf'])?'<em style="font-size:0.8em"> on behalf of '.$row['onbehalf'].'</em>':'')
                  .'</span>'
                  ,
                'donationID'=>$row['donationID'],
                'rawDate'=>strtotime($row['donationDate']),
                'date'=>date('jS M Y',strtotime($row['donationDate'])),
                'amount'=>$row['donationAmount_Total'],
                'price'=>$row['amount'],
                'title'=>$row['instructions'],
                'referrer'=>$row['referrer'],
                'story' => $story, 
                'item_id' => $row['item_id'],
                'tip'=>$row['tip'],
                'notifications'=>$row['notifications'],
                'unit_tip'=>$row['unit_tip'],
                'matched'=>$row['matched']
            );
        list($item['transfer_status'], $item['distribution_status']) = $this->populateDonorStatusDetails($row['item_id']);

        $this->donatedBy[] = $item;
        }
    }//populateDonorDetails function
    
    /**
     * populate the donation sattus wrt to the donation ID passed
     *
     * @param $donationID
     * @return status values
     */
    public function populateDonorStatusDetails($itemID)
    {
        $sql = sprintf("select transfer_status,distribution_status from donationGifts item left join money_transfer_status m on m.id =item.fundTransferStatus left join item_distribution_status d on d.id=item.distributionStatus where item.ID = %d",$itemID);

        $result=mysql_query($sql);
        if( ($row=mysql_fetch_assoc($result))!=null ) {
          return array($row['transfer_status'], $row['distribution_status']);
        }

    return array('unknown', 'unknown');
    }//populateDonorStatusDetails  function

    /**
     * populate the status values to display in the drop down box.
     * Adding the type of status before displaying the value
     *
     */
    public function populateDbStatusValues()
    {
        $result = mysql_query("select * from money_transfer_status") or die(mysql_error());
        while($row = mysql_fetch_array($result))
        {
            $this->dbStatusValues[] = array("Payment is ". $row['transfer_status'], "money=" . $row['id']);
        }

        $result = mysql_query("select * from item_distribution_status") or die(mysql_error());
        while($row = mysql_fetch_array($result))
        {
            $this->dbStatusValues[] = array("Gift is ". $row['distribution_status'], "gift=" . $row['id']);
        }
    }
    
    /***********Update the donation status table with selected values*******************/
    /**
     * get the Drop down box value and find the master data value. Then update the master data value in the
     * gift donation status table
     *
     */
    public function updateDonationStatus()
    {
      global $wpdb;

      if(count($_POST) == 0)
        return;

      $ids = $_REQUEST['itemID'];
      if(is_array($ids))
        $ids = implode(',', $_REQUEST['itemID']);
      $ids = array_filter( array_map('intval', explode(',', $ids)));
      if(count($ids) == 0) {
        $this->giftListObj->setStatus("error", "Please select at least one checkbox.");
        return;
      }
 
      $action = $_REQUEST['actionStatus'];
      if(empty($action)) {
        $this->giftListObj->setStatus("error", "Please select an action from the dropdown.");
        return;
      }
      list($status, $value, $action) = $this->parseStatusChange($action);

      if($status == null || $value == null)
        return;
  
      $sql = sprintf("update donationGifts set %s = %d where ID in (%s)", $status, $value, implode(',', $ids));
      $result=mysql_query($sql) or die(mysql_error() . " in update Donation Status: " . $sql);
      $this->giftListObj->setStatus("ok", "Donation Status has been updated");
  
      foreach ($ids as $id) {
        $donationId = $wpdb->get_var("select donationId from donationGifts where id=$id");
        $sql = $wpdb->prepare("INSERT INTO donationHistory(donationID,transactionDate,modifiedBy,action) values(%d,NOW(),%d,'%s')", $donationId, get_current_user_id(), "#$id: $action");
        $result=mysql_query($sql) or die(mysql_error() . " in update Donation History: " . $sql);
      }
    }
    
    /**
     * get the donorStatus id based on the status value
     *
     * @param $statusArray
     * @return donationID and the status value
     */
    public function parseStatusChange($status)
    {
       $status = explode('|', $status);
       $vals = explode('=', $status[0]);
       if($vals[0] == 'money')
         $vals[0] = 'fundTransferStatus';
       else if($vals[0] == 'gift')
         $vals[0] = 'distributionStatus';
       else
         return array(null, null);

       $vals[1] = intval($vals[1]);
       $vals[2] = $status[1];
       return $vals;
    }
    
    
}//GiftDetails class

class GiftDonation
{
    public $id=0;
    public $blogId = null;
    public $giftListObj;
    public $donationHistory ; 
    
    public function __construct($id) {
        global $blog_id;

        $this->blogId = $blog_id;
        $this->donatedBy=array();
        $this->donationHistory=array();
        $this->giftListObj = new GiftList();
        $this->id=$id;
        $this->getDonationDetails($this->id);
    }//constructor
    
    /**
     * Fetching the donation details to display in the history page
     *
     * @param unknown_type $donationID
     */
    public function getDonationDetails($donationID)
    {
        global $wpdb;
    
        if(isset($donationID) && intval($donationID) != 0)
        {
          $sqlQuery = $wpdb->prepare("SELECT * FROM gift gf,donation donation,donationGiver donor,donationGifts dg
                              WHERE donation.donationID = %d AND donation.donationID = dg.donationID AND 
                              gf.id = dg.giftID AND gf.blog_id = %d AND donor.id = donation.donorID order by gf.id",
                              intval($donationID),intval($this->blogId));
          $result=mysql_query($sqlQuery) or die('Error fetching donation Details: '.mysql_error());
          while( $row = mysql_fetch_assoc( $result ) )
          { 
              $this->donationHistory[] = array(
                  'name' => $row['displayName'],
                  'donor' => ($row['sendUpdates']==1? $row['firstName'] . " (" . $row['email'] . ")" : $row['firstName']),
                  'donationID' => $row['donationID'],
                  'amount' => $row['amount']
              );
          }
        }
    }
    
    
}//GiftDonation class

////////////////////////////////////////////////////////////////////////////////

function init_gift_fields() {
  global $post, $current_user;  
  get_currentuserinfo();
  add_meta_box ('gift-attributes', 'Gift Attributes', 'draw_gift_fields', 
    'gift', 'normal', 'high');
}

function draw_gift_fields() {
  global $wpdb, $blog_id, $post_id;
  global $post_type_template_nonce;
  if(empty($post_type_template_nonce)) {
    echo '<input type="hidden" name="syi_metabox_nonce" value="'.
      wp_create_nonce('syi_metabox').'" />';
    $post_type_template_nonce = 1;
  }

  if($post_id>0) {
    $sql = $wpdb->prepare('SELECT * FROM gift WHERE blog_id=%d AND post_id=%d',$blog_id, $post_id);
    $gift = stripslashes_deep($wpdb->get_row($sql));
    
    if($gift) echo '<p><b>Gift #'.$gift->id.'</b></p>';

  }
//  echo $sql;
//  print_r($gift,true);

  echo draw_post_field ('text','gift_name',$gift->displayName,'Name'); 
  echo draw_post_field ('text','gift_plural',$gift->pluralName,'Plural'); 
  echo draw_post_field ('text','gift_tags',$gift->tags,'Tags'); 
  echo draw_post_field ('text','gift_available',$gift->unitsWanted,'Available'); 
  echo draw_post_field ('text','gift_amount',$gift->unitAmount,'Cost'); 
  echo draw_post_field ('checkbox','gift_variable',$gift->varAmount,'Variable'); 
  echo draw_post_field ('text','gift_tgi',$gift->towards_gift_id,'Aggregate towards'); 
  echo draw_post_field ('text','gift_link_text',$gift->link_text,'Description link text'); 
  echo draw_post_field ('text','gift_link_href',$gift->link_ref,'Description link href'); 
  echo draw_post_field ('text','gift_campaign',$gift->campaign,'Campaign'); 
  echo draw_post_field ('checkbox','gift_active',$gift->active,'Active'); 
}

add_action('save_post', 'save_gift');
add_action('publish_post', 'save_gift');

function save_gift($post_id) {
  global $wpdb;
  global $blog_id;  
  $post = get_post($post_id);

  if ($post->post_type == 'gift') {
    if (wp_verify_nonce($_POST['syi_metabox_nonce'], 'syi_metabox')) {      
      $gift_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM gift WHERE blog_id=%d AND post_id=%d",$blog_id,$post_id));
      if (empty($gift_id)) {
        $sql = $wpdb->prepare("INSERT INTO gift 
        (displayName, pluralName, title, excerpt, 
        description, unitAmount, varAmount, 
        tags, blog_id, post_id, unitsWanted,
        active, link_text, link_href, 
        towards_gift_id, campaign) 
        VALUES (%s, %s, %s, %s, 
        %s, %f, %d, 
        %s, %d, %d, %d,
        %d, %s, %s, 
        %d, %d)",
        $_POST['gift_name'], $_POST['gift_plural'], $_POST['gift_name'], $post->post_excerpt, 
        $post->post_content, $_POST['gift_amount'], $_POST['gift_variable'], 
        $_POST['gift_tags'], $blog_id, $post_id, $_POST['gift_available'],
        $_POST['gift_active'], $_POST['gift_link_text'], $_POST['gift_link_href'], 
        $_POST['gift_tgi'], $_POST['gift_campaign']
        );
      } else {
        $sql = $wpdb->prepare("UPDATE gift SET 
        displayName=%s, pluralName=%s, title=%s, excerpt=%s, 
        description=%s, unitAmount=%f, varAmount=%d, 
        tags=%s, unitsWanted=%d, 
        active=%d, link_text=%s, link_href=%s, 
        towards_gift_id=%d, campaign=%d        
        WHERE blog_id=%d AND post_id=%d",
        $_POST['gift_name'], $_POST['gift_plural'], $post->post_title, $post->post_excerpt,
        $post->post_content, $_POST['gift_amount'], $_POST['gift_variable'], 
        $_POST['gift_tags'], $_POST['gift_available'], 
        $_POST['gift_active'], $_POST['gift_link_text'], $_POST['gift_link_href'], 
        $_POST['gift_tgi'], $_POST['gift_campaign'], 
        $blog_id, $post_id
        );
      }

//debug($sql,true);
      $wpdb->query($sql);
    }
  }
}

add_filter('manage_edit-gift_columns','gift_columns');

function gift_columns($columns) {
  $columns['gift_status']    = 'Status';
  $columns['gift_cost']    = 'Cost';
  $columns['gift_tags']    = 'Tags';
  $columns['gift_tgi']    = 'Agg. To';
  unset($columns['date']);
  return $columns;     
  
}

add_action('manage_posts_custom_column','show_gift_columns');

function show_gift_columns($name) {
  global $post, $blog_id, $wpdb;
  if($post->post_type == 'gift') {
    $sql = $wpdb->prepare('SELECT * FROM gift WHERE blog_id=%d AND post_id=%d',$blog_id, $post->ID);
    $gift = $wpdb->get_row($sql);
    switch ($name) {
      case 'gift_tags':
        echo $gift->tags; 
        break;
      case 'gift_status':
        ?>#<?=$gift->id?>: <?= ($gift->unitsWanted>0) ? "$gift->unitsWanted " : "Un" ?>available (<?=$gift->unitsDonated?> donated)<?
        break;
      case 'gift_cost':
        echo as_money($gift->unitAmount); break;
      case 'gift_tgi':
        if(!empty($gift->towards_gift_id)) {
          $tgi = $wpdb->get_row($sql = $wpdb->prepare('SELECT * FROM gift WHERE id=%d',$gift->towards_gift_id));
          ?>#<?=$tgi->id?>:
          <a href="/wp-admin/post.php?post=<?=$tgi->post_id?>&action=edit">
            <?= xml_entities($tgi->displayName) ?>
          </a><?
        } 
        break;
      case 'date':
        break;
    }
  }
}

