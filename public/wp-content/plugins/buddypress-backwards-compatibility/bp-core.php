<?php

/* Define the current version number for checking if DB tables are up to date. */
define( 'BP_CORE_DB_VERSION', '1800' );

/***
 * Define the path and url of the BuddyPress plugins directory.
 * It is important to use plugins_url() core function to obtain
 * the correct scheme used (http or https).
 */
define( 'BPBC_PLUGIN_DIR', WP_PLUGIN_DIR . '/buddypress-backwards-compatibility' );
define( 'BPBC_PLUGIN_URL', plugins_url( $path = '/buddypress-backwards-compatibility' ) );

/***
 * Tell BuddyPress to use the classic template structure, since
 * we can assume that if this plugin is needed, it's because you have
 * a custom 1.1 theme
 */
if ( !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
	define( 'BP_CLASSIC_TEMPLATE_STRUCTURE', true );

/* Define the deprecated slug for the home blog */
if ( !defined( 'BP_HOME_BLOG_SLUG' ) )
	define( 'BP_HOME_BLOG_SLUG', 'blog' );

/* Require included deprecated bp-core files */
require ( BPBC_PLUGIN_DIR . '/bp-core/bp-core-templatetags.php' );
require ( BPBC_PLUGIN_DIR . '/bp-core/bp-core-widgets.php' );

/* Register Backpat themes contained within the bp-theme folder */
if ( function_exists( 'register_theme_directory') )
	register_theme_directory( BPBC_PLUGIN_DIR . '/bp-themes' );

/***
 * "You are about to witness something that you've never witnessed before."
 * ---Oh wait, you probably have...
 */

/**
 * bpbc_core_add_admin_menu()
 *
 * Adds the "Legacy Components" admin submenu item to the "BuddyPress" tab.
 *
 * @package BPBC Core
 * @uses is_site_admin() returns true if the current user is a site admin, false if not
 * @uses add_submenu_page() WP function to add a submenu item
 */
function bpbc_core_add_admin_menu() {
	if ( !is_site_admin() )
		return false;

	add_submenu_page( 'bp-general-settings', __( 'Legacy Components', 'buddypress'), __( 'Legacy Components', 'buddypress' ), 'manage_options', 'bpbc-component-setup', 'bpbc_core_admin_component_setup' );
}
add_action( 'admin_menu', 'bpbc_core_add_admin_menu', 11 );

/**
 * bpbc_core_check_installed()
 *
 * Checks to make sure BPBC is activated. Includes
 *
 * @package BPBC Core
 * @global $current_user WordPress global variable containing current logged in user information
 * @uses is_site_admin() returns true if the current user is a site admin, false if not
 * @uses get_site_option() fetches the value for a meta_key in the wp_sitemeta table
 * @uses bp_core_install() runs the installation of DB tables for the core component
 */
function bpbc_core_check_installed() {
	if ( !is_site_admin() )
		return false;

	require ( BPBC_PLUGIN_DIR . '/bp-core/bp-core-admin.php' );

}
add_action( 'admin_menu', 'bpbc_core_check_installed' );

function bp_has_site_members() {
	return bp_has_members();
}

/* Functions used in members directory loop */
function bp_site_members_pagination_count() {
	bp_members_pagination_count();
}

function bp_site_members_pagination_links() {
	bp_members_pagination_links();
}

function bp_site_members() {
	return bp_members();
}

function bp_the_site_member() {
	return bp_the_member();
}

function bp_the_site_member_hidden_fields() {
	if ( isset( $_REQUEST['s'] ) ) {
		echo '<input type="hidden" id="search_terms" value="' . attribute_escape( $_REQUEST['s'] ) . '" name="search_terms" />';
	}

	if ( isset( $_REQUEST['letter'] ) ) {
		echo '<input type="hidden" id="selected_letter" value="' . attribute_escape( $_REQUEST['letter'] ) . '" name="selected_letter" />';
	}

	if ( isset( $_REQUEST['members_search'] ) ) {
		echo '<input type="hidden" id="search_terms" value="' . attribute_escape( $_REQUEST['members_search'] ) . '" name="search_terms" />';
	}
}

function bp_the_site_member_url() {
	bp_member_link();
}
	function bp_get_the_site_member_url() {
		return bp_get_member_link();
	}

function bp_the_site_member_link() {
	echo bp_get_member_link();
}
	function bp_get_the_site_member_link() {
		return bp_get_member_link();
	}

function bp_the_site_member_name() {
	echo bp_get_the_site_member_name();
}
	function bp_get_the_site_member_name() {
		return bp_get_member_name();
	}

function bp_the_site_member_last_active() {
	echo bp_get_the_site_member_last_active();
}
	function bp_get_the_site_member_last_active() {
		return bp_get_member_last_active();
	}

function bp_the_site_member_user_id() {
	echo bp_get_the_site_member_user_id();
}
	function bp_get_the_site_member_user_id() {
		return bp_get_member_user_id();
	}

function bp_the_site_member_avatar() {
	echo bp_get_the_site_member_avatar();
}
	function bp_get_the_site_member_avatar() {
		return bp_get_member_avatar();
	}

function bp_the_site_member_add_friend_button() {
	bp_add_friend_button();
}
	function bp_get_the_site_member_add_friend_button() {
		return bp_get_add_friend_button();
	}

function bp_the_site_member_total_friend_count() {
	bp_member_total_friend_count();
}
	function bp_get_the_site_member_total_friend_count() {
		return bp_get_member_total_friend_count();
	}

function bp_the_site_member_random_profile_data() {
	bp_member_random_profile_data();
}

/**
 * bp_core_filter_template_paths()
 *
 * Add fallback for the bp-sn-parent theme template locations used in BuddyPress versions
 * older than 1.2.
 *
 * @package BuddyPress Core
 */
function bp_core_filter_template_paths() {
	if ( 'bp-sn-parent' != basename( TEMPLATEPATH ) && !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
		return false;

	add_filter( 'bp_core_template_directory_members', create_function( '', 'return "directories/members/index";' ) );
	add_filter( 'bp_core_template_plugin', create_function( '', 'return "plugin-template";' ) );
}
add_action( 'init', 'bp_core_filter_template_paths' );



?>