<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/database/db-functions.php');

function notification_has_been_sent($mailType, $donationId, $postId, $blogId) {
  global $wpdb;

  switch ($mailType) {
    case 2: case 6: // Both types of thank you are equivalent
    $mm = "(mailType=2 OR mailType=6)"; break;
    default:
    $mm = "mailType=$mailType"; break;
  }
  $sql = $wpdb->prepare(
    "SELECT COUNT(1) FROM notificationHistory
    WHERE donationID=%d AND postID=%d AND blogID=%d AND success=1 AND $mm",
    $donationId,$postId, $blogId);
  $upd = intval($wpdb->get_var($sql));

  SyiLog::log('info', "notification_has_been_sent($mailType, $donationId, $postId, $blogId): $upd");
  return $upd > 0;
}

// Sends email to the donors,when admin select donations from the
// gift details page and clicks on the post update button

function notify_donationUpdate($postID) {
  global $blog_id;
  global $emailEngine;
  global $wpdb;

  $p = get_post($postID); if ($p->post_status != 'publish') return;
  
  $items = as_ints(get_post_meta($postID, 'donation_items', true));
  if (count($items) == 0)
    return;

  $sql = sprintf(
    "SELECT dd.email, dg.donationID, group_concat(dg.ID) as items, dd.ID, dg.giftID, dd.user_id as 'user_id'
      FROM donationGifts dg JOIN donation d ON d.donationId=dg.donationId
      JOIN donationGiver dd ON dd.ID = d.donorID
      WHERE dg.ID in (%s) GROUP BY dd.ID", implode(",", $items));

  $result = $wpdb->get_results($sql);
  foreach ($result as $row) {
    if (!notification_has_been_sent(2, $row->donationID, $postID, $blog_id)) {
      $link = get_permalink($postID);
      SyiLog::log('info', "post_notification: $row->user_id, $link");
      $facebook = new SyiFacebook($row->user_id);
      $facebook->publish_story($blog_id, $postID);
    }

    $resend = false;
    if(current_user_can('publish_posts') || current_user_can('publish_post')) {
      if(isset($_REQUEST['resend']) && $_REQUEST['resend']=="yes"){
        $resend = true;
      }

      $resend_subject = "";
      if(!empty($_REQUEST['resend_subject'])) $resend_subject = $_REQUEST['resend_subject'];

      //bypassing old donation to prevent duplicate notifications
      //  (donations before 377 didn't track notification history)
      if($row->donationID>377){

        // Get the donor's email.  May be NULL if they have specified
        // that they don't want a story mail, but we still pass it
        // in. sendMail takes care of not mailing anything to a
        // blank address - and must be called to keep certain tables
        // updated.
        $donor_email = get_user_email($row->ID, 'story');

        $emailEngine->sendMail($donor_email, $blog_id, 2,6,
          "$row->donationID/$row->items", $postID, false,
          $resend?$resend_subject:'', '', '', $resend, 'storyready');
      }
    }
  }

  $result = $wpdb->get_results($wpdb->prepare(
    "SELECT ID, donationID, giftID, towards_gift_id FROM donationGifts 
     WHERE ID IN (%s) GROUP BY donationID", implode(",", $items)),ARRAY_A);
    
  foreach($result as $k=>$v){
    if(intval($v['towards_gift_id'])>0) {
      $typeID = 14;
    } else {
      $typeID = 12;      
    }
    $donationID = intval($v['donationID']);
    $giftID = intval($v['giftID']);
  }    

  if(isset($_REQUEST['resend']) && $_REQUEST['resend']=="yes"){  
    //wp_redirect('post.php?action=edit&post='.$postID);
  }
}

/**
 * Save the donor Ids as post metadata
 * this will only save if the post is not having meta data
 *
 * @param unknown_type $postID
 */

function save_donorDetails($post_id) {
  global $wpdb, $id, $blog_id;

  $post = get_post($post_id);
  if ($post->post_parent > 0) { //ensure not revision
    $post_id = $post->post_parent;
    $post = get_post($post_id);
  }

  if ($post->post_type!='post') return; //ensure this is actually a post

  $id = $post_id;
  wp_get_shortlink();
  wp_set_post_tags($post_id, 'story', true); //set story tag

  $saved_item_ids = get_post_meta($post_id, "donation_items", 1); //get current dgs
  $item_ids = '';

  //validating donation gifts added

  $item_ids_arr = explode(",",$saved_item_ids);
  $item_ids_arr_new = array();

  if(is_array($item_ids_arr) && !empty($item_ids_arr)) {
    foreach($item_ids_arr as $item_id) {
      $dg = $wpdb->get_row($wpdb->prepare("SELECT story, blog_id FROM donationGifts WHERE ID=%d",$item_id));
      if(!empty($dg))
	      // exclude donation gifts with existing story
      if(intval($dg->story) == 0 || ($dg->story == $post_id && $dg->blog_id == $blog_id))
        $item_ids_arr_new[] = $item_id;
    }
    $item_ids = implode(",",$item_ids_arr_new);
  }

  if($item_ids != $old_item_ids) {  //update post meta after validating
    update_post_meta($post_id,'donation_items',$item_ids);
  }

  $sql1 = $wpdb->prepare("UPDATE donationGifts SET story=NULL WHERE story=%d AND blog_id=%d", $post_id, $blog_id);
  $wpdb->query($sql1); // remove post id from previous dgs

  if (!empty($item_ids)) {
    $sql2 = sprintf("UPDATE donationGifts SET story=%d WHERE ID in (%s)", $post_id, $item_ids);
    $wpdb->query($sql2); // add post id to new dgs
  }
}

function remove_donorDetails($postID) {
  global $wpdb, $blog_id;

  $status = get_post_status($postID);
  if ($status != 'publish') {
    delete_post_meta($postID, 'donation_items');
    $item_ids = as_ints(get_post_meta($postID, 'donation_items', true));
    if (count($item_ids) > 0) {
      $sql = sprintf("UPDATE donationGifts SET story=null 
      WHERE ID in (".implode(',', $item_ids).") AND story=%d", $postID);
      $wpdb->query($sql);
    } else {
      SyiLog::log('info', "no item IDs found from donation_items where \$postID = $postID");
    }
  }  
}


function add_donorinfo_box() {
  add_meta_box(
    'donorDetails', // id of the <div> we'll add
    'Donation Details', //title
    'show_donation_details', // callback function that will echo the box content
    'post', // where to add the box: on "post", "page", or "link" page
    'side'
  );
}

/**
 * this will return the donation id based on the user flow
 *
 * @return unknown
 */

function getDonationIDs(){
  $ids = as_ints($_REQUEST['itemID']);
  if (count($ids) > 0){
    $ids = implode(',', $ids);
    echo '<input type="hidden" name="_donation_items" value="'.$ids.'"/>';
    return $ids;
  } else {
    if (!isset($_REQUEST['post'])){
      global $post;
      return get_post_meta($post->ID, 'donation_items', true);
    }else{
      return get_post_meta($_REQUEST['post'], 'donation_items', true);
    }
  }
}


/**
 * show the donor details on the page
 *
 * @return unknown
 */

function show_donation_details(){
  global $wpdb;

  $item_ids = as_ints(getDonationIDs());
  $sql = sprintf("SELECT d.donationID, donor.firstName, donor.lastName, 
    sum(dg.unitsDonated) AS quantity, g.displayName, sum(dg.amount) AS total, g.ID AS giftID
	FROM donationGifts dg 
	LEFT JOIN donation d ON d.donationId=dg.donationId 
	LEFT JOIN donationGiver donor ON donor.ID = d.donorID 
	LEFT JOIN gift g ON dg.giftID = g.ID 
	WHERE dg.ID in (".implode(',', $item_ids).") GROUP BY donor.ID,g.id");
  $result = $wpdb->get_results( $sql );

  // Display the donors
  foreach ($result as $row) {
    $donor = $row->firstName . " " . $row->lastName;

	$tg = get_avg_tgi($row->giftID, true);
	if ( $tg!=NULL ) {  
	  $row->displayName = AVG_NAME_PREFIX.$tg->displayName;
	}

	$gift = $row->displayName;
	$quantity = intval($row->quantity);
	$id = intval($row->donationID);
	if ($quantity > 1)
	  $gift = $gift . " (x$quantity)";
	$url = admin_url("admin.php?page=Gift-admin/admin-gift.php&donationId=$id");
	echo "<a style='text-indent:-1em; text-decoration: none; padding: 2px; "
	."display:block; padding-left: 1em;' href='$url'>$donor: $gift</a>";       
  }


  if(isset($_REQUEST['post'])){
    $postID = intval($_REQUEST['post']);
    $sql = $wpdb->prepare("SELECT notificationID FROM notificationHistory 
	  WHERE donationID IN (".implode(',',$item_ids).") AND postID=%d AND success=1 ", $postID);

    $notifications = $wpdb->get_col($sql);    
    $notifications = array_map('link_notification', $notifications);
    
    if(current_user_can('publish_post')) {
      if($notifications != NULL && count($notifications)>0){
        echo '<br/>Notifications Sent: '.implode(', ',$notifications);
        echo '<br/><br/>'
          .'<input type="checkbox" name="resend" value="yes" '
          .'onclick="return confirm'
          .'(\'Resend Notification? This will resend to ALL donors.\')" />'
          .' Resend | Subject: <input type="text" name="resend_subject" value=""/>';
      }
    }
  }      
}

function link_notification($id) {
  return "<a target=\"_new\" href=\"/popups/detailsPopUp.php?popup=emaildetail&notificationId=$id\">$id</a>";
}

function save_donationStory($postID){
  global $blog_id;
  global $wpdb;
  $p = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM wp_%d_posts WHERE ID = %d", $blog_id, $postID),ARRAY_A);

  if($p['post_type']=='post')
    insert_donation_story($blog_id, $p);
  else
    delete_donationStory($postID);
}

function delete_donationStory($postID){
  global $blog_id;
  global $wpdb;
  $wpdb->query($wpdb->prepare("DELETE FROM donationStory WHERE blog_id=%d AND post_id=%d",$blog_id, $postID));		
}

// hook in the donor info box to appear on post page
add_action('admin_menu', 'add_donorinfo_box');

//process donation story cache
add_action('publish_post', 'save_donationStory', 100);
add_action('save_post', 'save_donationStory', 100);
add_action('trash_post', 'delete_donationStory', 100);
add_action('delete_post', 'delete_donationStory', 100);

// create hook when new post is published
add_action('publish_post', 'notify_donationUpdate', 102);
add_action('edit_post', 'notify_donationUpdate', 102);

// create hook when the post is saved
add_action('publish_post', 'save_donorDetails',9999);
add_action('save_post', 'save_donorDetails',9999);
add_action('edit_post', 'save_donorDetails',9999);
add_action('pending_post', 'save_donorDetails',9999);
add_action('trash_post', 'remove_donorDetails');
add_action('delete_post', 'remove_donorDetails');
