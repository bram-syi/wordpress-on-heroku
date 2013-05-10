<?php

/**
 * messages_filter_template_paths()
 *
 * Add fallback for the bp-sn-parent theme template locations used in BuddyPress versions
 * older than 1.2.
 *
 * @package BuddyPress Core
 */
function messages_filter_template_paths() {
	if ( 'bp-sn-parent' != basename( TEMPLATEPATH ) && !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
		return false;

	add_filter( 'messages_template_compose', create_function( '', 'return "messages/compose";' ) );
	add_filter( 'messages_template_sentbox', create_function( '', 'return "messages/sentbox";' ) );
	add_filter( 'messages_template_inbox', create_function( '', 'return "messages/index";' ) );
	add_filter( 'messages_template_notices', create_function( '', 'return "messages/notices";' ) );
	add_filter( 'messages_template_view_message', create_function( '', 'return "messages/view";' ) );
}
add_action( 'init', 'messages_filter_template_paths' );


?>