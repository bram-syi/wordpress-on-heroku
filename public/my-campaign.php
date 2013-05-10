<?
include_once('wp-load.php');
wp_redirect(add_query_arg('theme', $_REQUEST['theme'], "/start"));
