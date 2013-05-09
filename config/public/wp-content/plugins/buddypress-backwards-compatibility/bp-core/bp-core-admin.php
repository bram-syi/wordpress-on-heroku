<?php

function bpbc_core_admin_component_setup() {
	global $wpdb, $bp;
?>

	<?php
	if ( isset( $_POST['bpbc-admin-component-submit'] ) && isset( $_POST['bpbc_components'] ) ) {
		if ( !check_admin_referer('bpbc-admin-component-setup') )
			return false;

		// Settings form submitted, now save the settings.
		foreach ( $_POST['bpbc_components'] as $key => $value ) {
			if ( !(int) $value )
				$disabled[$key] = 1;
		}
		update_site_option( 'bpbc-deactivated-components', $disabled );
	}
	?>

	<div class="wrap">

		<h2><?php _e( 'BuddyPress Legacy Component Setup', 'buddypress' ) ?></h2>

		<?php if ( isset( $_POST['bpbc-admin-component-submit'] ) ) : ?>
			<div id="message" class="updated fade">
				<p><?php _e( 'Settings Saved', 'buddypress' ) ?></p>
			</div>
		<?php endif; ?>

		<form action="" method="post" id="bp-admin-component-form">

			<p>
			<?php _e(
				'By default, all BuddyPress Legacy components are enabled. You can selectively disable legacy support of each
				component by using the form below. Your BuddyPress installation may not function without these active,
				particularly if your theme still supports the Wire or Status update components.
				', 'buddypress' )?>
			</p>

			<?php $disabled_components = get_site_option( 'bpbc-deactivated-components' ); ?>

			<table class="form-table" style="width: 80%">
			<tbody>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-activity.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'Activity Streams', 'buddypress' ) ?></h3><p><?php _e( 'Allow users to post activity updates and track all activity across the entire site.', 'buddypress' ) ?></p></td>
					<td>
						<input type="radio" name="bpbc_components[bp-activity.php]" value="1"<?php if ( !isset( $disabled_components['bp-activity.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?> &nbsp;
						<input type="radio" name="bpbc_components[bp-activity.php]" value="0"<?php if ( isset( $disabled_components['bp-activity.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-blogs.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'Blog Tracking', 'buddypress' ) ?></h3><p><?php _e( 'Tracks blogs, blog posts and blogs comments for a user across a WPMU installation.', 'buddypress' ) ?></p></td>
					<td>
						<input type="radio" name="bpbc_components[bp-blogs.php]" value="1"<?php if ( !isset( $disabled_components['bp-blogs.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?>  &nbsp;
						<input type="radio" name="bpbc_components[bp-blogs.php]" value="0"<?php if ( isset( $disabled_components['bp-blogs.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-forums.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'bbPress Forums', 'buddypress' ) ?></h3><p><?php _e( 'Activates bbPress forum support within BuddyPress groups or any other custom component.', 'buddypress' ) ?></p></td>
					<td>
						<input type="radio" name="bpbc_components[bp-forums.php]" value="1"<?php if ( !isset( $disabled_components['bp-forums.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?>  &nbsp;
						<input type="radio" name="bpbc_components[bp-forums.php]" value="0"<?php if ( isset( $disabled_components['bp-forums.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-friends.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'Friends', 'buddypress' ) ?></h3><p><?php _e( 'Allows the creation of friend connections between users.', 'buddypress' ) ?></p></td>
					<td>
						<input type="radio" name="bpbc_components[bp-friends.php]" value="1"<?php if ( !isset( $disabled_components['bp-friends.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?>  &nbsp;
						<input type="radio" name="bpbc_components[bp-friends.php]" value="0"<?php if ( isset( $disabled_components['bp-friends.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-groups.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'Groups', 'buddypress' ) ?></h3><p><?php _e( 'Let users create, join and participate in groups.', 'buddypress' ) ?></p></td>
					<td>
						<input type="radio" name="bpbc_components[bp-groups.php]" value="1"<?php if ( !isset( $disabled_components['bp-groups.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?>  &nbsp;
						<input type="radio" name="bpbc_components[bp-groups.php]" value="0"<?php if ( isset( $disabled_components['bp-groups.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-messages.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'Private Messaging', 'buddypress' ) ?></h3><p><?php _e( 'Let users send private messages to one another. Site admins can also send site-wide notices.', 'buddypress' ) ?></p></td>
					<td>
						<input type="radio" name="bpbc_components[bp-messages.php]" value="1"<?php if ( !isset( $disabled_components['bp-messages.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?>  &nbsp;
						<input type="radio" name="bpbc_components[bp-messages.php]" value="0"<?php if ( isset( $disabled_components['bp-messages.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-xprofile.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'Extended Profiles', 'buddypress' ) ?></h3><p><?php _e( 'Activates customizable profiles and avatars for site users.', 'buddypress' ) ?></p></td>
					<td width="45%">
						<input type="radio" name="bpbc_components[bp-xprofile.php]" value="1"<?php if ( !isset( $disabled_components['bp-xprofile.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?>  &nbsp;
						<input type="radio" name="bpbc_components[bp-xprofile.php]" value="0"<?php if ( isset( $disabled_components['bp-xprofile.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-wire.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'Comment Wire', 'buddypress' ) ?></h3><p><?php _e( 'Let users leave a comment on groups, profiles and custom components.', 'buddypress' ) ?></p></td>
					<td>
						<input type="radio" name="bpbc_components[bp-wire.php]" value="1"<?php if ( !isset( $disabled_components['bp-wire.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?>  &nbsp;
						<input type="radio" name="bpbc_components[bp-wire.php]" value="0"<?php if ( isset( $disabled_components['bp-wire.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ( file_exists( BPBC_PLUGIN_DIR . '/bp-status.php') ) : ?>
				<tr>
					<td><h3><?php _e( 'Status Updates', 'buddypress' ) ?></h3><p><?php _e( 'Allow users to post status updates.', 'buddypress' ) ?></p></td>
					<td width="45%">
						<input type="radio" name="bpbc_components[bp-status.php]" value="1"<?php if ( !isset( $disabled_components['bp-status.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Enabled', 'buddypress' ) ?>  &nbsp;
						<input type="radio" name="bpbc_components[bp-status.php]" value="0"<?php if ( isset( $disabled_components['bp-status.php'] ) ) : ?> checked="checked" <?php endif; ?>/> <?php _e( 'Disabled', 'buddypress' ) ?>
					</td>
				</tr>
				<?php endif; ?>
			</tbody>
			</table>

			<p class="submit">
				<input class="button-primary" type="submit" name="bpbc-admin-component-submit" id="bpbc-admin-component-submit" value="<?php _e( 'Save Settings', 'buddypress' ) ?>"/>
			</p>

			<?php wp_nonce_field( 'bpbc-admin-component-setup' ) ?>
		</form>
	</div>
<?php
}

?>