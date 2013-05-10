<?php

/* Require included deprecated bp-core files */
require ( BPBC_PLUGIN_DIR . '/bp-friends/bp-friends-templatetags.php' );

/* Deprecated friend loop functions
 * Most of these are wrappers for new functions
 */
if ( !function_exists( 'bp_has_friendships' ) ) {
	function bp_has_friendships( $args = '' ) {
		global $bp;

		$defaults = array(
			'type' => 'active',
			'user_id' => bp_displayed_user_id(),
			'per_page' => 10,
			'max' => false,
			'filter' => false
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		/* The following code will auto set parameters based on the page being viewed.
		 * for example on example.com/members/andy/friends/my-friends/newest/
		 * $type = 'newest'
		 */
		if ( 'my-friends' == $bp->current_action ) {
			$order = $bp->action_variables[0];
			if ( 'newest' == $order )
				$type = 'newest';
			else if ( 'alphabetically' == $order )
				$type = 'alphabetical';
		} else if ( 'requests' == $bp->current_action ) {
			$type = 'requests';
		}

		if ( isset( $_REQUEST['friend-search-box'] ) )
			$filter = $_REQUEST['friend-search-box'];

		return bp_has_members( array( 'type' => $type, 'user_id' => $user_id, 'per_page' => $per_page, 'max' => $max, 'search_terms' => $filter ) );
	}
}

if ( !function_exists( 'bp_user_friendships' ) ) {
	function bp_user_friendships() {
		return bp_members();
	}
}

if ( !function_exists( 'bp_the_friendship' ) ) {
	function bp_the_friendship() {
		return bp_the_member();
	}
}

if ( !function_exists( 'bp_friend_id' ) ) {
	function bp_friend_id() {
		bp_member_user_id();
	}
}

if ( !function_exists( 'bp_get_friend_id' ) ) {
	function bp_get_friend_id() {
		return bp_get_member_user_id();
	}
}

if ( !function_exists( 'bp_friend_url' ) ) {
	function bp_friend_url() {
		bp_member_link();
	}
}
if ( !function_exists( 'bp_get_friend_url' ) ) {
	function bp_get_friend_url() {
		return bp_get_member_link();
	}
}

if ( !function_exists( 'bp_friend_link' ) ) {
	function bp_friend_link() {
		echo bp_get_friend_link();
	}
}

if ( !function_exists( 'bp_get_friend_link' ) ) {
	function bp_get_friend_link() {
		return '<a href="' . bp_get_member_link() . '">' . bp_get_member_name() . '</a>';
	}
}

if ( !function_exists( 'bp_friend_name' ) ) {
	function bp_friend_name() {
		echo bp_get_member_name();
	}
}
if ( !function_exists( 'bp_get_friend_name' ) ) {
	function bp_get_friend_name() {
		return bp_get_member_name();
	}
}

if ( !function_exists( 'bp_friend_pagination_count' ) ) {
	function bp_friend_pagination_count() {
		bp_members_pagination_count();
	}
}
if ( !function_exists( 'bp_friend_pagination' ) ) {
	function bp_friend_pagination() {
		bp_members_pagination_links();
	}
}
if ( !function_exists( 'bp_friend_avatar_thumb' ) ) {
	function bp_friend_avatar_thumb( $args = '' ) {
		bp_member_avatar( $args );
	}
}
if ( !function_exists( 'bp_friend_time_since_requested' ) ) {
	function bp_friend_time_since_requested() {
		bp_member_last_active();
	}
}

/**
 * friends_filter_template_paths()
 *
 * Add fallback for the bp-sn-parent theme template locations used in BuddyPress versions
 * older than 1.2.
 *
 * @package BuddyPress Core
 */
function friends_filter_template_paths() {
	if ( 'bp-sn-parent' != basename( TEMPLATEPATH ) && !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
		return false;

	add_filter( 'friends_template_my_friends', create_function( '', 'return "friends/index";' ) );
	add_filter( 'friends_template_requests', create_function( '', 'return "friends/requests";' ) );
}
add_action( 'init', 'friends_filter_template_paths' );

?>