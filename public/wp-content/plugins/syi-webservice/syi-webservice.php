<?php
/*
Plugin Name: SYI Webservice
Description: Provides webservice actions for the mobile application. 
Version: 0.1b
Author: Aditi
Author URI: http://www.aditi.com

Webservice url: http://yourdomain.com/wp-content/plugins/wp-syi-webservice/webservice.php
*/
add_action('admin_menu', 'syi_webservice_menu');
register_activation_hook(__FILE__, 'init_syi');


function init_syi(){
	update_option( 'thumbnail_size_mobile', '100' );
	update_option( 'thumbnail_size_posts', 'Medium' );
}

function syi_webservice_menu() {
  //add_options_page('My Plugin Options', 'SYI Webservice', 8, 'syi-webservice-options', 'my_plugin_options');
  add_options_page('My Plugin Options', 'SYI Webservice', 8, 'syi-options', 'my_plugin_options');
}

function my_plugin_options() {
  	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'syi-options.php');
}
return;

?>