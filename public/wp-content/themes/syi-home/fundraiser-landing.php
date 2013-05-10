<?php
/*
Template Name: Fundraiser Landing Page
*/

global $NO_SIDEBAR, $NO_PADDING, $header_file, $post, $GIFTS_EVENT, $GIFTS_LOC;
$NO_SIDEBAR = true;
$NO_PADDING = true;
$header_file = "landing";

remove_action('syi_pagetop', 'draw_the_crumbs', 0);
add_action( 'wp_head', 'add_landing_styles' );
wp_enqueue_script('twitter', "http://platform.twitter.com/widgets.js");

include_once('page.php');

function add_landing_styles() {
  global $GIFTS_EVENT, $GIFTS_LOC, $post;

  $GIFTS_EVENT = $post->ID; // 6819
  $GIFTS_LOC = 'landing';
  set_event_cookie($GIFTS_EVENT);

?>
<style>
.login-bar { display: none; }
#features ul li {
  font-size:12px;
  list-style: none;
  margin: 25px 0 0;
}

#features ul li strong {
  font-size:14px;
  display: block;
  margin-bottom: 5px;
}

/*
.gift-browser .button {
  background: #c72026;
  border: 1px solid #c72026;
}
*/
.gift-page .page-title {
  font-size: 20pt;
  color: #444;
}
</style>
<?
}
