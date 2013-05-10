<?php
/*
Plugin Name: BuddyPress Backwards Compatibilty
Plugin URI: http://buddypress.org/download/
Description: Code that is needed to maintain backwards compatibility with previous versions of BuddyPress. Contains code for wire component, status updates, and functions that have been renamed or replaced.
Author: The BuddyPress Community
Version: 0.6
Author URI: http://buddypress.org/developers/
Site Wide Only: true
*/

/* Check BuddyPress versions for backpat compat */
if ( defined( 'BP_VERSION' ) ) {
	switch ( substr( BP_VERSION, 0, 3 ) ) {
		case '1.2' :
			bpbc_wrapper();
			break;
		case '1.1' :
		default :
			return false;
	}
} else {
	add_action( 'bp_init', 'bpbc_wrapper');
	return false;
}

/*
 * Everything is loaded from within this function. It's done
 * like this to prevent issues with plugin load order as it's possible
 * to load this plugin without BuddyPress installed and active.
 */
function bpbc_wrapper() {

	/* Only load components if we're not ignoring deprecated components */
	define( 'BPBC_VERSION', '0.5.3' );

	/***
	 * This file will load in each BPBC component based on which
	 * of the components have been activated on the "BuddyPress" admin menu.
	 */
	require_once( WP_PLUGIN_DIR . '/buddypress-backwards-compatibility/bp-core.php' );
	$bpbc_deactivated = apply_filters( 'bpbc_deactivated_components', get_site_option( 'bpbc-deactivated-components' ) );

	/* Activity Streams */
	if ( !isset( $bpbc_deactivated['bp-activity.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-activity.php') )
		include( BPBC_PLUGIN_DIR . '/bp-activity.php' );

	/* Blog Tracking */
	if ( !isset( $bpbc_deactivated['bp-blogs.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-blogs.php') )
		include( BPBC_PLUGIN_DIR . '/bp-blogs.php' );

	/* bbPress Forum Integration */
	if ( !isset( $bp_deactivated['bp-forums.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-forums.php') )
		include( BPBC_PLUGIN_DIR . '/bp-forums.php' );

	/* Friend Connections */
	if ( !isset( $bpbc_deactivated['bp-friends.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-friends.php') )
		include( BPBC_PLUGIN_DIR . '/bp-friends.php' );

	/* Groups Support */
	if ( !isset( $bpbc_deactivated['bp-groups.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-groups.php') )
		include( BPBC_PLUGIN_DIR . '/bp-groups.php' );

	/* Private Messaging */
	if ( !isset( $bp_deactivated['bp-messages.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-messages.php') )
		include( BPBC_PLUGIN_DIR . '/bp-messages.php' );

	/* Extended Profiles */
	if ( !isset( $bp_deactivated['bp-xprofile.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-xprofile.php') )
		include( BPBC_PLUGIN_DIR . '/bp-xprofile.php' );

	/* Wire Support */
	if ( !isset( $bpbc_deactivated['bp-wire.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-wire.php') )
		include( BPBC_PLUGIN_DIR . '/bp-wire.php' );

	/* Status Updates */
	if ( !isset( $bpbc_deactivated['bp-status.php'] ) && file_exists( BPBC_PLUGIN_DIR . '/bp-status.php') )
		include( BPBC_PLUGIN_DIR . '/bp-status.php' );
}

/* Deactivation Function */
function bpbc_loader_deactivate() {
	if ( !function_exists( 'delete_site_option') )
		return false;

	//delete_site_option( 'bp-core-db-version' );
	//delete_site_option( 'bp-activity-db-version' );
	//delete_site_option( 'bp-blogs-db-version' );
	//delete_site_option( 'bp-friends-db-version' );
	//delete_site_option( 'bp-groups-db-version' );
	//delete_site_option( 'bp-messages-db-version' );
	//delete_site_option( 'bp-xprofile-db-version' );
	delete_site_option( 'bp-wire-db-version' );
	//delete_site_option( 'bp-deactivated-components' );

	do_action( 'bpbc_loader_deactivate' );
}
register_deactivation_hook( __FILE__, 'bpbc_loader_deactivate' );

?>