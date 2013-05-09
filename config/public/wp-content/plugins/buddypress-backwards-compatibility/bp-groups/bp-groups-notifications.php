<?php

if ( !function_exists( 'groups_notification_new_wire_post' ) ) {
	function groups_notification_new_wire_post( $group_id, $wire_post_id ) {
		global $bp;

		if ( !isset( $_POST['wire-post-email-notify'] ) )
			return false;

		$wire_post = new BP_Wire_Post( $bp->groups->table_name_wire, $wire_post_id );
		$group = new BP_Groups_Group( $group_id, false, true );

		$poster_name = bp_core_get_user_displayname( $wire_post->user_id );
		$poster_profile_link = bp_core_get_user_domain( $wire_post->user_id );

		$subject = '[' . get_blog_option( BP_ROOT_BLOG, 'blogname' ) . '] ' . sprintf( __( 'New wire post on group: %s', 'buddypress' ), stripslashes( attribute_escape( $group->name ) ) );

		foreach ( $group->user_dataset as $user ) {
			if ( 'no' == get_usermeta( $user->user_id, 'notification_groups_wire_post' ) ) continue;

			$ud = get_userdata( $user->user_id );

			// Set up and send the message
			$to = $ud->user_email;

			$wire_link = site_url( $bp->groups->slug . '/' . $group->slug . '/wire/' );
			$group_link = site_url( $bp->groups->slug . '/' . $group->slug . '/' );
			$settings_link = bp_core_get_user_domain( $user->user_id ) . 'settings/notifications/';

			$message = sprintf( __(
	'%s posted on the wire of the group "%s":

	"%s"

	To view the group wire: %s

	To view the group home: %s

	To view %s\'s profile page: %s

	---------------------
	', 'buddypress' ), $poster_name, stripslashes( attribute_escape( $group->name ) ), stripslashes($wire_post->content), $wire_link, $group_link, $poster_name, $poster_profile_link );

			$message .= sprintf( __( 'To disable these notifications please log in and go to: %s', 'buddypress' ), $settings_link );

			// Send it
			wp_mail( $to, $subject, $message );

			unset( $message, $to );
		}
	}
}

?>