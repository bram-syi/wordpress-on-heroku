<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

if (!is_user_logged_in()) {
  $url = site_url($_SERVER["REQUEST_URI"]);
  wp_redirect( wp_login_url( $url ));
  die();
}

nocache_headers();

if ( !current_user_can('level_1') )
  wp_die('No access');

print "<pre>";

$x = 200;
$y = get_fundraiser_image_url(10243,$x,$x);
print "$y<br/><img src=\"$y\" width=\"$x\" height=\"$x\"/>";
