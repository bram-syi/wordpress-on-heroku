<?php
/**
 * bp_forums_filter_template_paths()
 *
 * Add fallback for the bp-sn-parent theme template locations used in BuddyPress versions
 * older than 1.2.
 *
 * @package BuddyPress Core
 */
function bp_forums_filter_template_paths() {
	if ( 'bp-sn-parent' != basename( TEMPLATEPATH ) && !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
		return false;

	add_filter( 'bp_forums_template_directory_forums_setup', create_function( '', 'return "directories/forums/index";' ) );
}
add_action( 'init', 'bp_forums_filter_template_paths' );

/* DEPRECATED use bp_has_forum_topics() */
function bp_has_topics( $args = '' ) { return bp_has_forum_topics( $args ); }

/* DEPRECATED use bp_has_forum_topics() */
function bp_topics() { return bp_forum_topics(); }

/* DEPRECATED use bp_the_forum_topic() */
function bp_the_topic() { return bp_the_forum_topic(); }

/* DEPRECATED use bp_has_forum_topic_posts() */
function bp_has_topic_posts() { return bp_has_forum_topic_posts( $args ); }

/* DEPRECATED use bp_forum_topic_posts() */
function bp_topic_posts() { return bp_forum_topic_posts(); }

/* DEPRECATED use bp_the_forum_topic_post() */
function bp_the_topic_post() { return bp_the_forum_topic_post(); }

?>