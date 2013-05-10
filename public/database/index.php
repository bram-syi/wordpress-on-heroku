<?
require_once('../wp-load.php');
require_once(ABSPATH . 'database/db-functions.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/post.php');

nocache_headers();

if (!is_user_logged_in()) {
  $url = site_url($_SERVER["REQUEST_URI"]);
  wp_redirect( wp_login_url( $url ));
  die();
}

if (!user_can('level10')) {
  wp_redirect("/");
  die();
}


