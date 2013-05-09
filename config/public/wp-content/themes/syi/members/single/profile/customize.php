<?php
global $bp;
global $wpdb;
global $errors;

require_once( ABSPATH . 'wp-admin/includes/misc.php' );
require_once( ABSPATH . 'wp-admin/includes/template.php' );
require_once( ABSPATH . 'wp-admin/includes/user.php' );

wp_reset_vars(array('action', 'redirect', 'profile', 'user_id', 'wp_http_referer'));

$user_id = $bp->displayed_user->id;
$user = get_userdata( $user_id );
unset($user->user_pass);
if (!$user)
	die( __('Invalid user ID.') );
$errors = new WP_Error();

// Only allow super admins
if (!current_user_can( 'manage_network_users' ))
	die( __( 'You do not have permission to edit this user.' ) );

$user_id = $bp->displayed_user->id;



if(isset($_REQUEST[USER_CUSTOM_STORIES_META_KEY])) {
  update_user_meta($user_id,USER_CUSTOM_STORIES_META_KEY,$_REQUEST[USER_CUSTOM_STORIES_META_KEY]);	
}

$custom_stories = get_user_meta($user_id,USER_CUSTOM_STORIES_META_KEY,1);

?>
<form action="" method="post">
<div class="editfield custom_stories field_custom_stories">
  <label class="field-label" for="<?=USER_CUSTOM_STORIES_META_KEY?>"><strong>Custom impact stories:</strong><br/>
  <span style="font-size:0.9em"><em>format: blogID-storyID, example: 30-333, separate stories with comma</em></span><br/>
  </label><br/>
  <textarea name="<?=USER_CUSTOM_STORIES_META_KEY?>" cols="48" rows="5"><?=$custom_stories?></textarea>
</div>
<br/>
<div class="submit-row">
  <input class="button green-button medium-button" type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<? _e( 'Save Changes', 'buddypress' ) ?> " />
</div>


<input type="hidden" name="action" value="update" />
<input type="hidden" name="user_id" id="user_id" value="<?= esc_attr($user_id); ?>" />


</form><?

?>
