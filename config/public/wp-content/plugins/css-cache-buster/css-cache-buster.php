<?php
/*
Plugin Name: CSS Cache Buster
Plugin URI: http://www.alistercameron.com/2008/09/12/wordpress-plugin-css-cache-buster/
Description: CSS Cache Buster does one very simple but very powerful thing: it ensures that your Wordpress blog delivers the very latest version of your CSS stylesheet to your visitors, no matter how often or how rarely your edit for CSS. You no longer need to worry that you've made a CSS change that others won't see because of caching issues at their end. Technically, this plugin automatically appends a last-modified date/timestamp to the end of your stylesheet url. And since this feature is applied as a filter, you don't have to touch your theme files. It just works. Simple!
Author: Alister Cameron // Blogologist
Version: 1.0
Author URI: http://www.alistercameron.com/
*/

if ( !defined( 'WP_CONTENT_DIR' ) )
define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

function css_cache_buster($info, $show) {
	if ($show == 'stylesheet_url') {
		$pieces = explode("wp-content", $info);
		
		// Is there already a querystring? If so, add to the end of that.
		if (strpos($pieces[1], '?') === false) {
			return $info . "?" . filemtime(WP_CONTENT_DIR . $pieces[1]);
		} else {
			$morsels = explode("?", $pieces[1]);
			return $info . "&" . filemtime(WP_CONTENT_DIR . $morsels[1]);
		}
	}
	else return $info;
}

add_filter('bloginfo_url', 'css_cache_buster', 9999, 2);
?>