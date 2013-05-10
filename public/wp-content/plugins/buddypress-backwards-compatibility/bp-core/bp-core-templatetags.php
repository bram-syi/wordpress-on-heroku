<?php

if ( !function_exists( 'bp_core_add_nav_item' ) ) {
	function bp_core_add_nav_item( $name, $slug, $css_id = false, $show_for_displayed_user = true ) {
		bp_core_new_nav_item( array( 'name' => $name, 'slug' => $slug, 'item_css_id' => $css_id, 'show_for_displayed_user' => $show_for_displayed_user ) );
	}
}

if ( !function_exists( 'bp_core_add_nav_default' ) ) {
	function bp_core_add_nav_default() {
		return false;
	}
}

if ( !function_exists( 'bp_core_add_subnav_item' ) ) {
	function bp_core_add_subnav_item( $parent_id, $slug, $name, $link, $function, $css_id = false, $user_has_access = true, $admin_only = false ) {
		bp_core_new_subnav_item( array( 'name' => $name, 'slug' => $slug, 'parent_slug' => $parent_id, 'parent_url' => $link, 'item_css_id' => $css_id, 'user_has_access' => $user_has_access, 'site_admin_only' => $admin_only, 'screen_function' => $function ) );
	}
}

if ( !function_exists( 'bp_profile_wire_can_post' ) ) {
	function bp_profile_wire_can_post() {
		global $bp;

		if ( bp_is_my_profile() )
			return true;

		if ( function_exists('friends_install') ) {
			if ( friends_check_friendship( $bp->loggedin_user->id, $bp->displayed_user->id ) )
				return true;
			else
				return false;
		}

		return true;
	}
}

if ( !function_exists( 'bp_is_home' ) && function_exists( 'bp_is_my_profile' ) ) {
	function bp_is_home() { return bp_is_my_profile(); }
}

if ( !function_exists( 'bp_profile_wire_can_post' ) ) {
	function bp_profile_wire_can_post() {
		global $bp;

		if ( bp_is_my_profile() )
			return true;

		if ( function_exists('friends_install') ) {
			if ( friends_check_friendship( $bp->loggedin_user->id, $bp->displayed_user->id ) )
				return true;
			else
				return false;
		}

		return true;
	}
}

if ( !function_exists( 'bp_is_wire_component' ) ) {
	function bp_is_wire_component() {
		global $bp;

		if ( BP_WIRE_SLUG == $bp->current_action || in_array( BP_WIRE_SLUG, (array)$bp->action_variables ) )
			return true;

		return false;
	}
}

if ( !function_exists( 'bp_is_profile_wire' ) ) {
	function bp_is_profile_wire() {
		global $bp;

		if ( BP_XPROFILE_SLUG == $bp->current_component && 'wire' == $bp->current_action )
			return true;

		return false;
	}
}

if ( !function_exists( 'bp_is_group_wire' ) ) {
	function bp_is_group_wire() {
		global $bp;

		if ( BP_GROUPS_SLUG == $bp->current_component && $bp->is_single_item && 'wire' == $bp->current_action )
			return true;

		return false;
	}
}

function bp_home_blog_url() {
	global $bp, $current_blog;

	if ( defined( 'BP_ENABLE_MULTIBLOG' ) ) {
		$blog_id = $current_blog->blog_id;
	} else {
		$blog_id = BP_ROOT_BLOG;
	}

	if ( 'bphome' == get_blog_option( $blog_id, 'template' ) )
		echo $bp->root_domain . '/' . BP_HOME_BLOG_SLUG;
	else
		echo $bp->root_domain;
}

function bp_nav_items() {
	global $bp;
	// This is deprecated, you should put these navigation items in your template header.php for easy editing.
?>
	<li<?php if ( bp_is_page( 'home' ) ) {?> class="selected"<?php } ?>><a href="<?php echo get_option('home') ?>" title="<?php _e( 'Home', 'buddypress' ) ?>"><?php _e( 'Home', 'buddypress' ) ?></a></li>
	<li<?php if ( bp_is_page( BP_HOME_BLOG_SLUG ) ) {?> class="selected"<?php } ?>><a href="<?php echo get_option('home') ?>/<?php echo BP_HOME_BLOG_SLUG ?>" title="<?php _e( 'Blog', 'buddypress' ) ?>"><?php _e( 'Blog', 'buddypress' ) ?></a></li>
	<li<?php if ( bp_is_page( BP_MEMBERS_SLUG ) ) {?> class="selected"<?php } ?>><a href="<?php echo get_option('home') ?>/<?php echo BP_MEMBERS_SLUG ?>" title="<?php _e( 'Members', 'buddypress' ) ?>"><?php _e( 'Members', 'buddypress' ) ?></a></li>

	<?php if ( function_exists( 'groups_install' ) ) { ?>
		<li<?php if ( bp_is_page( $bp->groups->slug ) ) {?> class="selected"<?php } ?>><a href="<?php echo get_option('home') ?>/<?php echo $bp->groups->slug ?>" title="<?php _e( 'Groups', 'buddypress' ) ?>"><?php _e( 'Groups', 'buddypress' ) ?></a></li>
	<?php } ?>

	<?php if ( function_exists( 'bp_blogs_install' ) ) { ?>
		<li<?php if ( bp_is_page( $bp->blogs->slug ) ) {?> class="selected"<?php } ?>><a href="<?php echo get_option('home') ?>/<?php echo $bp->blogs->slug ?>" title="<?php _e( 'Blogs', 'buddypress' ) ?>"><?php _e( 'Blogs', 'buddypress' ) ?></a></li>
	<?php } ?>
<?php
	do_action( 'bp_nav_items' );
}

/* DEPRECATED - use bp_get_loggedin_user_nav() */
function bp_get_nav() { bp_get_loggedin_user_nav(); }

/* DEPRECATED - use bp_get_displayed_user_nav() */
function bp_get_user_nav() { bp_get_displayed_user_nav(); }

/* DEPRECATED - use bp_core_get_user_domain() */
function bp_core_get_userurl( $uid ) { return bp_core_get_user_domain( $uid ); }





?>