<?php

/* Require included deprecated bp-blogs files */
require ( BPBC_PLUGIN_DIR . '/bp-blogs/bp-blogs-templatetags.php' );


if ( !function_exists( 'bp_blog_title' ) ) {
	function bp_blog_title() {
		bp_blog_name();
	}
}

if ( !function_exists( 'bp_get_blog_title' ) ) {
	function bp_get_blog_title() {
		return bp_get_blog_name();
	}
}

function bp_blogs_add_deprecated_nav() {
	global $bp;

	$blogs_link = $bp->loggedin_user->domain . $bp->blogs->slug . '/';

	bp_core_new_subnav_item( array( 'name' => __( 'My Blogs', 'buddypress' ), 'slug' => 'my-blogs', 'parent_url' => $blogs_link, 'parent_slug' => $bp->blogs->slug, 'screen_function' => 'bp_blogs_screen_my_blogs', 'position' => 10, 'item_css_id' => 'my-blogs-list' ) );
	bp_core_new_subnav_item( array( 'name' => __( 'Recent Posts', 'buddypress' ), 'slug' => 'recent-posts', 'parent_url' => $blogs_link, 'parent_slug' => $bp->blogs->slug, 'screen_function' => 'bp_blogs_screen_recent_posts', 'position' => 20 ) );
	bp_core_new_subnav_item( array( 'name' => __( 'Recent Comments', 'buddypress' ), 'slug' => 'recent-comments', 'parent_url' => $blogs_link, 'parent_slug' => $bp->blogs->slug, 'screen_function' => 'bp_blogs_screen_recent_comments', 'position' => 30 ) );
}
add_action( 'bp_setup_nav', 'bp_blogs_add_deprecated_nav' );

function bp_has_site_blogs() {
	bp_has_blogs();
}

function bp_the_site_blog_hidden_fields() {
	if ( isset( $_REQUEST['s'] ) ) {
		echo '<input type="hidden" id="search_terms" value="' . attribute_escape( $_REQUEST['s'] ). '" name="search_terms" />';
	}

	if ( isset( $_REQUEST['letter'] ) ) {
		echo '<input type="hidden" id="selected_letter" value="' . attribute_escape( $_REQUEST['letter'] ) . '" name="selected_letter" />';
	}

	if ( isset( $_REQUEST['blogs_search'] ) ) {
		echo '<input type="hidden" id="search_terms" value="' . attribute_escape( $_REQUEST['blogs_search'] ) . '" name="search_terms" />';
	}
}

/**
 * bp_blogs_filter_template_paths()
 *
 * Add fallback for the bp-sn-parent theme template locations used in BuddyPress versions
 * older than 1.2.
 *
 * @package BuddyPress Core
 */
function bp_blogs_filter_template_paths() {
	if ( 'bp-sn-parent' != basename( TEMPLATEPATH ) && !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
		return false;

	add_filter( 'bp_blogs_template_directory_blogs_setup', create_function( '', 'return "directories/blogs/index";' ) );
	add_filter( 'bp_blogs_template_my_blogs', create_function( '', 'return "blogs/my-blogs";' ) );
	add_filter( 'bp_blogs_template_recent_posts', create_function( '', 'return "blogs/recent-posts";' ) );
	add_filter( 'bp_blogs_template_recent_comments', create_function( '', 'return "blogs/recent-comments";' ) );
	add_filter( 'bp_blogs_template_create_a_blog', create_function( '', 'return "blogs/create";' ) );
}
add_action( 'init', 'bp_blogs_filter_template_paths' );


?>
