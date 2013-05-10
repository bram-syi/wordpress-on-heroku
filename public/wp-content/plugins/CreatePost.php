<?php
/*
Plugin Name: CreatePost
Plugin URI: http://www.seeyourimpact.org
Description: Creates a new POST.
Version: 0.1
Author: Nischal Pathania
Author URI: http://www.seeyourimpact.org
*/

include_once(ABSPATH.'/wp-config.php');

function create_New_Post($title, $content) {

// create post object
class wm_mypost {
var $post_title;
var $post_content;
var $post_status;
var $post_author; /* author user id (optional) */
var $post_name; /* slug (optional) */
var $post_type; /* 'page' or 'post' (optional, defaults to 'post') */
var $comment_status; /* open or closed for commenting (optional) */
}

// initialize post object
$wm_mypost = new wm_mypost();

// fill object
$wm_mypost->post_title = $title;
$wm_mypost->post_content = $content;
$wm_mypost->post_status = 'pending';
$wm_mypost->post_author = 1;
$wp_rewrite->feeds = 'no';

// Optional; uncomment as needed
// $wm_mypost->post_type = 'page';
// $wm_mypost->comment_status = 'closed';

// Set blog ID to post under

// feed object to wp_insert_post
$postID = wp_insert_post($wm_mypost);
$description = 'pic description';

// Attach image
/**
* $attachment = array(
*      'post_title' => $title . time(),
*      'post_content' => '',
*      'post_type' => 'attachment',
*      'post_parent' => $postID,
*      'post_status' => 'published',
*      'post_mime_type' => 'image/jpeg'
*    );
*   
*   $file = dirname(__FILE__)."/rt.jpg";
*   
*   // Save the data
*   $aid = wp_insert_attachment($attachment, $file, $postid );
*   wp_update_attachment_metadata( $aid, wp_generate_attachment_metadata( $aid, $file ) );
*/
  
  return $postID;
  }

?>