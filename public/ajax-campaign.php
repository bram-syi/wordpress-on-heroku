<?

define('XMLRPC_REQUEST', FALSE); // this turns off WP-Minify HTML minifcation

if (isset($_GET['q'])) { // search

  require_once('a/api/campaign.php');
  require_once('a/api/fundraiser.php');

// Required for bp_core_fetch_avatar and get_charity_thumb used in images.php
  require_once(ABSPATH . '/wp-includes/ms-functions.php');
  require_once(ABSPATH . '/wp-includes/meta.php');
  require_once(ABSPATH . '/wp-includes/shortcodes.php');
  require_once(ABSPATH . '/wp-includes/user.php');
  require_once(ABSPATH . '/wp-includes/class-wp-embed.php');
  require_once(ABSPATH . '/wp-content/plugins/buddypress/bp-core/bp-core-functions.php');
  require_once(ABSPATH . '/wp-content/plugins/buddypress/bp-core/bp-core-classes.php');
  require_once(ABSPATH . '/wp-content/plugins/buddypress/bp-members/bp-members-functions.php');
  require_once(ABSPATH . '/wp-content/plugins/buddypress/bp-core/bp-core-avatars.php');
  require_once(ABSPATH . '/syi/versions.php');
  require_once(ABSPATH . '/wp-content/mu-plugins/charities.php');

  $results = array();
  $orgs = PartnerApi::get(array(
    'search' => $_GET['q'] . '*',
    'view' => 'search',
    'order' => 'score'
  ));
  foreach ($orgs as $org) {
    $results[] = array(
      'id' => $org->id,
      'name' => $org->name,
      'type' => 'Organizations',
      'url' => $org->url,
      'image' => get_charity_thumb($org->id, $org->domain, array(50,50))
    );
  }

  $frs = FundraiserApi::get(array(
    'search' => $_GET['q'],
    'view' => 'search',
    'order' => 'name'
  ));

  if (count($frs) > 0) {
    $results = array_slice($results, 0, 5);

    foreach ($frs as $fr) {
      $results[] = array(
        'id' => $fr->id,
        'name' => $fr->display_name,
        'type' => 'Fundraisers',
        'url' => $fr->url,
        'image' => fundraiser_image_src($fr->id, 50, 50)
      );
    }
  }

  $obj = array(
    'total' => count($results),
    'orgs' => $results
  );
  echo Api::output_json($obj);
  exit();
}




// Old APIs load all of WordPress

include_once('wp-load.php');
include_once('wp-includes/syi/syi-includes.php');
include_once('wp-admin/includes/media.php');
include_once('wp-admin/includes/file.php');

global $current_user;
global $wpdb;
get_currentuserinfo();

nocache_headers();

header('Content-type: text/html; Charset=utf-8');

////////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST['p'])) { //get single post

  $vars = explode("-",$_REQUEST['p']);
  if(!is_array($vars) || count($vars)!=4) die();

  $post = new stdClass();
  $post->blog_id = $vars[1];
  $post->post_id = $vars[2];
  $post->type = $vars[3];

	draw_gallery_item($post, true, isset($_REQUEST['admin']));	  

  exit();
}

/*

////////////////////////////////////////////////////////////////////////////////
// THE REST ARE EDITING FUNCTIONS
////////////////////////////////////////////////////////////////////////////////


$err_msg = '';

if (!isset($_REQUEST['main'])) {

  if (!isset($_REQUEST['id'])) {
	$err_msg = 'Invalid id.';
  } else {
	$post = get_post(intval($_REQUEST['id']));
	if($post == NULL) $err_msg = 'Invalid id.';
  }
	
  if ($current_user->ID != get_post_meta($post->ID,CAMPAIGN_ACTIVE_OWNER,1)
	  && !current_user_can('publish_posts')) {
	$err_msg = 'Invalid user.';
  }
	
} else if (intval($current_user->ID) == 0) {
  $err_msg = 'Invalid user.';
}


if ($err_msg!='') { unset($_FILES); wp_die(__($err_msg)); }

////////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST['d'])) { //delete post
  $vars = explode("-",$_REQUEST['d']);
  if(!is_array($vars) || count($vars)!=4) die();

  $post = new stdClass();
  $post->blog_id = $vars[1];
  $post->post_id = $vars[2];

  switch_to_blog($post->blog_id);  
  //TO DO : add security to ensure user can delete
  wp_delete_post($post->post_id);
  restore_current_blog();
  exit();
}


////////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST['u'])) { //update file info
  $pid = intval($_REQUEST['id']);
  $media = get_media_link($_REQUEST['excerpt']);
  $media_sc = strval($media['sc']);

  wp_update_post(array(
    'post_content'=>$_REQUEST['content'],
    'post_excerpt'=>$media_sc,
    'post_title'=>addslashes($_REQUEST['title']),
    'ID'=>$_REQUEST['mid']
  ));

  exit();    
}

////////////////////////////////////////////////////////////////////////////////
*/

if (!empty($_FILES)) { // file upload
  $data = json_decode(decrypt($_REQUEST['key']));
  if ($data == NULL)
    die("nope");

  $pid = $data->eid;
  if (empty($pid))
    die("nope");

  $mid = media_handle_upload('file',0);
  unset($_FILES);

  $mid_obj = get_post($mid);
  resize_img(str_replace($site_url,ABSPATH.'wp-content/blogs.dir/1',$mid_obj->guid),
    CGW_IMG_FULL_W,CGW_IMG_FULL_H);

  wp_update_post(array('ID'=>$mid,'post_parent'=>$pid));
  $old_mid = intval(get_post_meta($pid,'_thumbnail_id',true));

  wp_delete_post($old_mid);

  update_post_meta($pid,'_thumbnail_id',$mid);
  $img = get_post(get_post_thumbnail_id($pid));
  if ($img == null)
    die("failed");

  $photo = make_img(fundraiser_image_src($pid), 250,250);
  if(isset($_REQUEST['html4'])) {
    echo htmlentities($photo);
    exit();
  }

  echo $photo;
  exit();
/*
  } else {
	  if (count_cgw_posts($pid) >= CGW_MAX_MEDIA) {
	    echo 'Error: Please delete an existing picture/video to add a new one.';
	  } else {
	  
  	  $mid = media_handle_upload('file',0);
      $mid_obj = get_post($mid);

  	  resize_img(str_replace($site_url,ABSPATH.'wp-content/blogs.dir/1',$mid_obj->guid),
  	  CGW_IMG_FULL_W,CGW_IMG_FULL_H);

  	  wp_update_post(array('ID'=>$mid,'post_parent'=>$pid,
        'post_name'=>$pid.'_syicg_'.$mid_obj->post_name));
  	  $post = get_media_post($pid, $mid);
      if(isset($_REQUEST['html4'])) {
	      echo htmlentities(draw_gallery_thumb($post, -1, 0, 1, true));
	    } else {
	      echo draw_gallery_thumb($post, -1, 0, 1, true);
	    }
	  }
  }
*/
  exit();
}

////////////////////////////////////////////////////////////////////////////////

if (!empty($_REQUEST['links'])) { //link upload
  
  $pid = intval($_REQUEST['id']);
  $urls = explode("\n",$_REQUEST['links']);


  if (count_cgw_posts($pid) >= CGW_MAX_MEDIA) {
	echo 'Error: Please delete an existing picture/video to add a new one.';
  } else if (is_array($urls)) { 
    foreach ($urls as $k=>$url) {
      $media = get_media_link($url);
	  if (empty($media)) { echo 'Error: Sorry, but your link is not working. Please provide a valid YouTube or Vimeo link only.'; exit(); }
      $mid = wp_insert_post(array(
        'post_author'=>$current_user->ID,
        'post_content'=>'', 
        'post_excerpt'=>$media['sc'],
        'post_parent'=>$pid,
        'post_status'=>'inherit',
        'post_title'=>get_media_meta($media, 'title'),
        'post_type'=>'attachment'
      ));

      if (!empty($mid)) {
        $mid_obj = get_post($mid);
		wp_update_post(array('ID'=>$mid,'post_name'=>$pid.'_syicg_'.$mid_obj->post_name));
        $post = get_media_post($pid, $mid);  
        draw_gallery_thumb($post, -1, 0, 1);
      }
    }
  }
  exit();
}


?>
