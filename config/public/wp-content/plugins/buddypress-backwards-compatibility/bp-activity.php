<?php
/* Require included deprecated bp-core files */
require ( BPBC_PLUGIN_DIR . '/bp-activity/bp-activity-widgets.php' );

/**
 * bp_activity_filter_template_paths()
 *
 * Add fallback for the bp-sn-parent theme template locations used in BuddyPress versions
 * older than 1.2.
 *
 * @package BuddyPress Core
 */
function bp_activity_filter_template_paths() {
	if ( 'bp-sn-parent' != basename( TEMPLATEPATH ) && !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
		return false;

	add_filter( 'bp_activity_template_my_activity', create_function( '', 'return "activity/just-me";' ) );
	add_filter( 'bp_activity_template_friends_activity', create_function( '', 'return "activity/my-friends";' ) );
	add_filter( 'bp_activity_template_profile_activity_permalink', create_function( '', 'return "activity/single";' ) );
}
add_action( 'init', 'bp_activity_filter_template_paths' );

?>