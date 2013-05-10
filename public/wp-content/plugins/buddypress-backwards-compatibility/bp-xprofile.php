<?php

function xprofile_install_wire() {
	if ( function_exists( 'bp_wire_install' ) )
		xprofile_wire_install();
}
add_action( 'xprofile_install', 'xprofile_install_wire' );

function xprofile_setup_globals_wire() {
	global $bp, $wpdb;

	if ( function_exists( 'bp_wire_install' ) )
		$bp->profile->table_name_wire = $wpdb->base_prefix . 'bp_xprofile_wire';
}
add_action( 'xprofile_setup_globals', 'xprofile_setup_globals_wire' );

function xprofile_wire_install() {
	global $bp, $wpdb;

	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

	$sql[] = "CREATE TABLE {$bp->profile->table_name_wire} (
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

	require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );
	dbDelta($sql);
}

/**
 * xprofile_screen_notification_settings()
 *
 * Loads the notification settings for the xprofile component.
 * Settings are hooked into the function: bp_core_screen_notification_settings_content()
 * in bp-core/bp-core-settings.php
 *
 * @package BuddyPress Xprofile
 * @global $current_user WordPress global variable containing current logged in user information
 */
function xprofile_screen_notification_settings() {
	global $current_user; ?>
	<?php if ( function_exists('bp_wire_install') ) { ?>
	<table class="notification-settings" id="profile-notification-settings">
		<tr>
			<th class="icon"></th>
			<th class="title"><?php _e( 'Profile', 'buddypress' ) ?></th>
			<th class="yes"><?php _e( 'Yes', 'buddypress' ) ?></th>
			<th class="no"><?php _e( 'No', 'buddypress' )?></th>
		</tr>

		<tr>
			<td></td>
			<td><?php _e( 'A member posts on your wire', 'buddypress' ) ?></td>
			<td class="yes"><input type="radio" name="notifications[notification_profile_wire_post]" value="yes" <?php if ( !get_usermeta( $current_user->id, 'notification_profile_wire_post' ) || 'yes' == get_usermeta( $current_user->id, 'notification_profile_wire_post' ) ) { ?>checked="checked" <?php } ?>/></td>
			<td class="no"><input type="radio" name="notifications[notification_profile_wire_post]" value="no" <?php if ( 'no' == get_usermeta( $current_user->id, 'notification_profile_wire_post' ) ) { ?>checked="checked" <?php } ?>/></td>
		</tr>

		<?php do_action( 'xprofile_screen_notification_settings' ) ?>
	</table>
	<?php } ?>
<?php
}
add_action( 'bp_notification_settings', 'xprofile_screen_notification_settings', 1 );


/**
 * xprofile_action_new_wire_post()
 *
 * Posts a new wire post to the users profile wire.
 *
 * @package BuddyPress XProfile
 * @global $bp The global BuddyPress settings variable created in bp_core_setup_globals()
 * @uses bp_wire_new_post() Adds a new wire post to a specific wire using the ID of the item passed and the table name.
 * @uses bp_core_add_message() Adds an error/success message to the session to be displayed on the next page load.
 * @uses bp_core_redirect() Safe redirects to a new page using the wp_redirect() function
 */
if ( !function_exists( 'xprofile_action_new_wire_post' ) ) {
	function xprofile_action_new_wire_post() {
		global $bp;

		if ( $bp->current_component != $bp->wire->slug )
			return false;

		if ( 'post' != $bp->current_action )
			return false;

		/* Check the nonce */
		if ( !check_admin_referer( 'bp_wire_post' ) )
			return false;

		if ( !$wire_post = bp_wire_new_post( $bp->displayed_user->id, $_POST['wire-post-textarea'], $bp->profile->id ) ) {
			bp_core_add_message( __( 'Wire message could not be posted. Please try again.', 'buddypress' ), 'error' );
		} else {
			bp_core_add_message( __( 'Wire message successfully posted.', 'buddypress' ) );

			/* Record the notification for the reciever if it's not on their own wire */
			if ( !bp_is_my_profile() )
				bp_core_add_notification( $bp->loggedin_user->id, $bp->displayed_user->id, $bp->profile->id, 'new_wire_post' );

			/* Record this on the poster's activity screen */
			if ( ( $wire_post->item_id == $bp->loggedin_user->id && $wire_post->user_id == $bp->loggedin_user->id ) || ( $wire_post->item_id == $bp->displayed_user->id && $wire_post->user_id == $bp->displayed_user->id ) ) {
				$from_user_link = bp_core_get_userlink($wire_post->user_id);
				$content = sprintf( __('%s wrote on their own wire', 'buddypress'), $from_user_link ) . ': <span class="time-since">%s</span>';
				$primary_link = bp_core_get_userlink( $wire_post->user_id, false, true );
			} else if ( ( $wire_post->item_id != $bp->loggedin_user->id && $wire_post->user_id == $bp->loggedin_user->id ) || ( $wire_post->item_id != $bp->displayed_user->id && $wire_post->user_id == $bp->displayed_user->id ) ) {
				$from_user_link = bp_core_get_userlink($wire_post->user_id);
				$to_user_link = bp_core_get_userlink( $wire_post->item_id, false, false, true, true );
				$content = sprintf( __('%s wrote on %s wire', 'buddypress'), $from_user_link, $to_user_link ) . ': <span class="time-since">%s</span>';
				$primary_link = bp_core_get_userlink( $wire_post->item_id, false, true );
			}

			$content .= '<blockquote>' . bp_create_excerpt($wire_post->content) . '</blockquote>';

			/* Now write the values */
			xprofile_record_activity( array(
				'user_id' => $bp->loggedin_user->id,
				'content' => apply_filters( 'xprofile_activity_new_wire_post', $content, &$wire_post ),
				'primary_link' => apply_filters( 'xprofile_activity_new_wire_post_primary_link', $primary_link ),
				'component_action' => 'new_wire_post',
				'item_id' => $wire_post->id
			) );

			do_action( 'xprofile_new_wire_post', &$wire_post );
		}

		if ( !strpos( wp_get_referer(), $bp->wire->slug ) ) {
			bp_core_redirect( $bp->displayed_user->domain );
		} else {
			bp_core_redirect( $bp->displayed_user->domain . $bp->wire->slug );
		}
	}
	add_action( 'wp', 'xprofile_action_new_wire_post', 3 );
}

/**
 * xprofile_action_delete_wire_post()
 *
 * Deletes a wire post from the users profile wire.
 *
 * @package BuddyPress XProfile
 * @global $bp The global BuddyPress settings variable created in bp_core_setup_globals()
 * @uses bp_wire_delete_post() Deletes a wire post for a specific wire using the ID of the item passed and the table name.
 * @uses xprofile_delete_activity() Deletes an activity item for the xprofile component and a particular user.
 * @uses bp_core_add_message() Adds an error/success message to the session to be displayed on the next page load.
 * @uses bp_core_redirect() Safe redirects to a new page using the wp_redirect() function
 */
if ( !function_exists( 'xprofile_action_delete_wire_post' ) ) {
	function xprofile_action_delete_wire_post() {
		global $bp;

		if ( $bp->current_component != $bp->wire->slug )
			return false;

		if ( $bp->current_action != 'delete' )
			return false;

		if ( !check_admin_referer( 'bp_wire_delete_link' ) )
			return false;

		$wire_post_id = $bp->action_variables[0];

		if ( bp_wire_delete_post( $wire_post_id, $bp->profile->slug, $bp->profile->table_name_wire ) ) {
			bp_core_add_message( __('Wire message successfully deleted.', 'buddypress') );

			/* Delete the post from activity streams */
			xprofile_delete_activity( array( 'item_id' => $wire_post_id, 'component_action' => 'new_wire_post' ) );

			do_action( 'xprofile_delete_wire_post', $wire_post_id );
		} else {
			bp_core_add_message( __('Wire post could not be deleted, please try again.', 'buddypress'), 'error' );
		}

		if ( !strpos( wp_get_referer(), $bp->wire->slug ) ) {
			bp_core_redirect( $bp->displayed_user->domain );
		} else {
			bp_core_redirect( $bp->displayed_user->domain. $bp->wire->slug );
		}
	}
	add_action( 'wp', 'xprofile_action_delete_wire_post', 3 );
}

function xprofile_register_activity_actions_wire() {
	global $bp;

	if ( !function_exists( 'bp_activity_set_action' ) )
		return false;

	/* Register the activity stream actions for this component */
	bp_activity_set_action( $bp->profile->id, 'new_wire_post', __( 'New profile wire post', 'buddypress' ) );
}
add_action( 'xprofile_register_activity_actions', 'xprofile_register_activity_actions_wire' );

/**
 * xprofile_format_notifications()
 *
 * Format notifications into something that can be read and displayed
 *
 * @package BuddyPress Xprofile
 * @param $item_id The ID of the specific item for which the activity is recorded (could be a wire post id, user id etc)
 * @param $action The component action name e.g. 'new_wire_post' or 'updated_profile'
 * @param $total_items The total number of identical notification items (used for grouping)
 * @global $bp The global BuddyPress settings variable created in bp_core_setup_globals()
 * @uses bp_core_global_user_fullname() Returns the display name for the user
 * @return The readable notification item
 */
function xprofile_format_notifications( $action, $item_id, $secondary_item_id, $total_items ) {
	global $bp;

	if ( 'new_wire_post' == $action ) {
		if ( (int)$total_items > 1 ) {
			return apply_filters( 'bp_xprofile_multiple_new_wire_post_notification', '<a href="' . $bp->loggedin_user->domain . $bp->wire->slug . '" title="' . __( 'Wire', 'buddypress' ) . '">' . sprintf( __( 'You have %d new posts on your wire', 'buddypress' ), (int)$total_items ) . '</a>', $total_items );
		} else {
			$user_fullname = bp_core_get_user_displayname( $item_id );
			return apply_filters( 'bp_xprofile_single_new_wire_post_notification', '<a href="' . $bp->loggedin_user->domain . $bp->wire->slug . '" title="' . __( 'Wire', 'buddypress' ) . '">' . sprintf( __( '%s posted on your wire', 'buddypress' ), $user_fullname ) . '</a>', $user_fullname );
		}
	}

	do_action( 'xprofile_format_notifications', $action, $item_id, $secondary_item_id, $total_items );

	return false;
}

/**
 * xprofile_remove_screen_notifications_wire()
 *
 * Removes notifications from the notification menu when a user clicks on them and
 * is taken to a specific screen.
 *
 * @package BuddyPress Core
 */
function xprofile_remove_screen_notifications_wire() {
	global $bp;

	bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->profile->id, 'new_wire_post' );
}
add_action( 'bp_wire_screen_latest', 'xprofile_remove_screen_notifications' );

/**
 * xprofile_filter_template_paths()
 *
 * Add fallback for the bp-sn-parent theme template locations used in BuddyPress versions
 * older than 1.2.
 *
 * @package BuddyPress Core
 */
function xprofile_filter_template_paths() {
	if ( 'bp-sn-parent' != basename( TEMPLATEPATH ) && !defined( 'BP_CLASSIC_TEMPLATE_STRUCTURE' ) )
		return false;

	add_filter( 'xprofile_template_display_profile', create_function( '', 'return "profile/index";' ) );
	add_filter( 'xprofile_template_edit_profile', create_function( '', 'return "profile/edit";' ) );
	add_filter( 'xprofile_template_change_avatar', create_function( '', 'return "profile/change-avatar";' ) );
}
add_action( 'init', 'xprofile_filter_template_paths' );

/* Deprecated: Don't use this as it it too easily confused with site groups */
function bp_group_has_fields() {
	return bp_profile_group_has_fields();
}

?>