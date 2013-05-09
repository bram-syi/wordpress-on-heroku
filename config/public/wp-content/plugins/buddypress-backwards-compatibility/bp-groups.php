<?php

/* Require included deprecated bp-groups files */
require ( BPBC_PLUGIN_DIR . '/bp-groups/bp-groups-notifications.php' );
require ( BPBC_PLUGIN_DIR . '/bp-groups/bp-groups-templatetags.php' );

/* Legacy Wire code */
function groups_setup_nav_wire() {
	global $bp, $wpdb;

	$groups_link = $bp->loggedin_user->domain . $bp->groups->slug . '/';

	bp_core_new_subnav_item( array( 'name' => __( 'Create a Group', 'buddypress' ), 'slug' => 'create', 'parent_url' => $groups_link, 'parent_slug' => $bp->groups->slug, 'screen_function' => 'groups_screen_create_group', 'position' => 20, 'user_has_access' => bp_is_home() ) );

	if ( !empty($bp->groups->current_group) && empty( $bp->groups->current_group->enable_wire ) )
		$bp->groups->current_group->enable_wire = $wpdb->get_var( $wpdb->prepare( "SELECT enable_wire FROM {$bp->groups->table_name} WHERE id = %d", $bp->groups->current_group->id ) );

	if ( $bp->groups->current_group->enable_wire && function_exists('bp_wire_install') )
		bp_core_new_subnav_item( array( 'name' => __( 'Wire', 'buddypress' ), 'slug' => BP_WIRE_SLUG, 'parent_url' => bp_get_group_permalink($bp->groups->current_group), 'parent_slug' => $bp->groups->slug, 'screen_function' => 'groups_screen_group_wire', 'position' => 50, 'user_has_access' => $bp->groups->current_group->user_has_access, 'item_css_id' => 'subnav-wire'  ) );
}
add_action( 'groups_setup_nav', 'groups_setup_nav_wire' );

function groups_setup_globals_wire() {
	global $bp, $wpdb;

		$bp->groups->table_name_wire = $wpdb->base_prefix . 'bp_groups_wire';
}
add_action( 'groups_setup_globals', 'groups_setup_globals_wire' );

if ( !function_exists( 'groups_wire_install' ) ) {
	function groups_wire_install() {
		global $wpdb, $bp;

		if ( !empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

		$sql[] = "CREATE TABLE {$bp->groups->table_name_wire} (
				id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				item_id bigint(20) NOT NULL,
				user_id bigint(20) NOT NULL,
				parent_id bigint(20) NOT NULL,
				content longtext NOT NULL,
				date_posted datetime NOT NULL,
				KEY item_id (item_id),
				KEY user_id (user_id),
				KEY parent_id (parent_id)
			   ) {$charset_collate};";

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql);
	}
	add_action( 'groups_install', 'groups_wire_install' );
}

function groups_register_activity_actions_wire() {
	global $bp;

	if ( !function_exists( 'bp_activity_set_action' ) )
		return false;

	bp_activity_set_action( $bp->groups->id, 'new_wire_post', __( 'New group wire post', 'buddypress' ) );
}
add_action( 'groups_register_activity_actions', 'groups_register_activity_actions_wire' );

if ( !function_exists( 'groups_screen_group_wire' ) ) {
	function groups_screen_group_wire() {
		global $bp;

		$wire_action = $bp->action_variables[0];

		if ( $bp->is_single_item ) {
			if ( 'post' == $wire_action && ( is_site_admin() || groups_is_user_member( $bp->loggedin_user->id, $bp->groups->current_group->id ) ) ) {
				/* Check the nonce first. */
				if ( !check_admin_referer( 'bp_wire_post' ) )
					return false;

				if ( !groups_new_wire_post( $bp->groups->current_group->id, $_POST['wire-post-textarea'] ) )
					bp_core_add_message( __('Wire message could not be posted.', 'buddypress'), 'error' );
				else
					bp_core_add_message( __('Wire message successfully posted.', 'buddypress') );

				if ( !strpos( wp_get_referer(), $bp->wire->slug ) )
					bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) );
				else
					bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . $bp->wire->slug );

			} else if ( 'delete' == $wire_action && ( is_site_admin() || groups_is_user_member( $bp->loggedin_user->id, $bp->groups->current_group->id ) ) ) {
				$wire_message_id = $bp->action_variables[1];

				/* Check the nonce first. */
				if ( !check_admin_referer( 'bp_wire_delete_link' ) )
					return false;

				if ( !groups_delete_wire_post( $wire_message_id, $bp->groups->table_name_wire ) )
					bp_core_add_message( __('There was an error deleting the wire message.', 'buddypress'), 'error' );
				else
					bp_core_add_message( __('Wire message successfully deleted.', 'buddypress') );

				if ( !strpos( wp_get_referer(), $bp->wire->slug ) )
					bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) );
				else
					bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . $bp->wire->slug );

			} else if ( ( !$wire_action || 'latest' == $bp->action_variables[1] ) ) {
				bp_core_load_template( apply_filters( 'groups_template_group_wire', 'groups/single/wire' ) );
			} else {
				bp_core_load_template( apply_filters( 'groups_template_group_home', 'groups/single/home' ) );
			}
		}
	}
}

if ( !function_exists( 'groups_new_wire_post' ) ) {
	function groups_new_wire_post( $group_id, $content ) {
		global $bp;

		if ( !function_exists( 'bp_wire_new_post' ) )
			return false;

		if ( $wire_post = bp_wire_new_post( $group_id, $content, 'groups' ) ) {

			/* Post an email notification if settings allow */
			require_once ( BPBC_PLUGIN_DIR . '/bp-groups/bp-groups-notifications.php' );
			groups_notification_new_wire_post( $group_id, $wire_post->id );

			/* Record this in activity streams */
			$activity_content = sprintf( __( '%s wrote on the wire of the group %s:', 'buddypress'), bp_core_get_userlink( $bp->loggedin_user->id ), '<a href="' . bp_get_group_permalink( $bp->groups->current_group ) . '">' . attribute_escape( $bp->groups->current_group->name ) . '</a>' );
			$activity_content .= '<blockquote>' . bp_create_excerpt( $content ) . '</blockquote>';

			groups_record_activity( array(
				'content' => apply_filters( 'groups_activity_new_wire_post', $activity_content ),
				'primary_link' => apply_filters( 'groups_activity_new_wire_post_primary_link', bp_get_group_permalink( $bp->groups->current_group ) ),
				'type' => 'new_wire_post',
				'component' => $bp->groups->id,
				'item_id' => $bp->groups->current_group->id,
				'secondary_item_id' => $wire_post->item_id
			) );

			do_action( 'groups_new_wire_post', $group_id, $wire_post->id );

			return true;
		}

		return false;
	}
}

if ( !function_exists( 'groups_delete_wire_post' ) ) {
	function groups_delete_wire_post( $wire_post_id, $table_name ) {
		if ( bp_wire_delete_post( $wire_post_id, 'groups', $table_name ) ) {
			/* Delete the activity stream item */
			if ( function_exists( 'bp_activity_delete_by_item_id' ) )
				bp_activity_delete_by_item_id( array( 'item_id' => $wire_post_id, 'component_name' => 'groups', 'component_action' => 'new_wire_post' ) );

			do_action( 'groups_deleted_wire_post', $wire_post_id );
			return true;
		}

		return false;
	}
}
add_action( 'groups_new_wire_post', 'bp_core_clear_cache' );
add_action( 'groups_deleted_wire_post', 'bp_core_clear_cache' );

function groups_screen_notification_settings_wire() {
	global $current_user;
	if ( function_exists('bp_wire_install') ) { ?>
	<tr>
		<td></td>
		<td><?php _e( 'A member posts on the wire of a group you belong to', 'buddypress' ) ?></td>
		<td class="yes"><input type="radio" name="notifications[notification_groups_wire_post]" value="yes" <?php if ( !get_usermeta( $current_user->id, 'notification_groups_wire_post') || 'yes' == get_usermeta( $current_user->id, 'notification_groups_wire_post') ) { ?>checked="checked" <?php } ?>/></td>
		<td class="no"><input type="radio" name="notifications[notification_groups_wire_post]" value="no" <?php if ( 'no' == get_usermeta( $current_user->id, 'notification_groups_wire_post') ) { ?>checked="checked" <?php } ?>/></td>
	</tr>
<?php }
}
add_action( 'groups_screen_notification_settings', 'groups_screen_notification_settings_wire' );

function groups_delete_group_wire_posts( $group ) {
	global $bp;

	// Delete the wire posts for this group if the wire is installed
	if ( function_exists('bp_wire_install') )
		BP_Wire_Post::delete_all_for_item( $group->id, $bp->groups->table_name_wire );

}
add_action( 'bp_groups_delete_group', 'groups_delete_group_wire_posts' );

function groups_admin_enable_wire( $group_id ) {
	global $bp, $wpdb;

	if ( isset( $_POST['group-show-wire'] ) ) {
		$wpdb->query( $wpdb->prepare( "UPDATE {$bp->groups->table_name} SET enable_wire = 1 WHERE id = %d", $group_id ) );
	} else {
		$wpdb->query( $wpdb->prepare( "UPDATE {$bp->groups->table_name} SET enable_wire = 0 WHERE id = %d", $group_id ) );
	}
}
add_action( 'groups_settings_updated', 'groups_admin_enable_wire' );

function groups_get_user_is_admin_of( $user_id = false, $pag_num = false, $pag_page = false, $filter = false ) {
	global $bp;

	if ( !$user_id )
		$user_id = $bp->displayed_user->id;

	return BP_Groups_Member::get_is_admin_of( $user_id, $pag_num, $pag_page, $filter );
}

function groups_get_user_is_mod_of( $user_id = false, $pag_num = false, $pag_page = false, $filter = false ) {
	global $bp;

	if ( !$user_id )
		$user_id = $bp->displayed_user->id;

	return BP_Groups_Member::get_is_mod_of( $user_id, $pag_num, $pag_page, $filter );
}

function groups_search_groups( $search_terms, $pag_num_per_page = 5, $pag_page = 1, $sort_by = false, $order = false ) {
	return BP_Groups_Group::search_groups( $search_terms, $pag_num_per_page, $pag_page, $sort_by, $order );
}

function groups_filter_user_groups( $filter, $user_id = false, $order = false, $pag_num_per_page = 5, $pag_page = 1 ) {
	return BP_Groups_Group::filter_user_groups( $filter, $user_id, $order, $pag_num_per_page, $pag_page );
}

/**
 * groups_filter_template_paths()
 *
 * Add fallback for the bp-sn-parent theme template locations used in BuddyPress versions
 * older than 1.2.
 *
 * @package BuddyPress Core
 */
function groups_filter_template_paths() {
	if ( 'bp-sn-parent' != basename( TEMPLATEPATH ) && !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
		return false;

	add_filter( 'groups_template_directory_groups', create_function( '', 'return "directories/groups/index";' ) );
	add_filter( 'groups_template_my_groups', create_function( '', 'return "groups/index";' ) );
	add_filter( 'groups_template_group_invites', create_function( '', 'return "groups/invites";' ) );
	add_filter( 'groups_template_group_admin', create_function( '', 'return "groups/single/admin";' ) );
	add_filter( 'groups_template_group_forum_topic_edit', create_function( '', 'return "groups/single/forum/edit";' ) );
	add_filter( 'groups_template_group_forum_topic', create_function( '', 'return "groups/single/forum/topic";' ) );
	add_filter( 'groups_template_group_forum', create_function( '', 'return "groups/single/forum/index";' ) );
	add_filter( 'groups_template_group_leave', create_function( '', 'return "groups/single/leave-confirm";' ) );
	add_filter( 'groups_template_group_request_membership', create_function( '', 'return "groups/single/request-membership";' ) );
	add_filter( 'groups_template_group_invite', create_function( '', 'return "groups/single/send-invite";' ) );
	add_filter( 'groups_template_group_members', create_function( '', 'return "groups/single/members";' ) );
	add_filter( 'groups_template_group_admin_settings', create_function( '', 'return "groups/single/admin";' ) );
	add_filter( 'groups_template_group_admin_avatar', create_function( '', 'return "groups/single/admin";' ) );
	add_filter( 'groups_template_group_admin_manage_members', create_function( '', 'return "groups/single/admin";' ) );
	add_filter( 'groups_template_group_admin_requests', create_function( '', 'return "groups/single/admin";' ) );
	add_filter( 'groups_template_group_admin_delete_group', create_function( '', 'return "groups/single/admin";' ) );
}
add_action( 'init', 'groups_filter_template_paths' );

?>