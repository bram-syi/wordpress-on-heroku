<?php

global $bp;

$donor_name = get_displayname($bp->displayed_user->id, false);

if (bp_current_action() == 'change-avatar' && bp_get_avatar_admin_step() == 'crop-image') {
  ?><div class="avatar" id="avatar-crop-pane"><img src="<? bp_avatar_to_crop() ?>" alt="Preview" id="avatar-crop-preview"></div><?
} else {

  echo make_img(user_image_src($bp->displayed_user->id), 200,200, 'avatar');
  // bp_displayed_user_avatar( 'type=full' );
  
  if (bp_is_my_profile()) {

    if (bp_current_action() != 'change-avatar') {
    ?>
    <div class="avatar-actions">
      <a class="button gray-button small-button" href="<?= get_member_link($bp->displayed_user->id, 'profile/change-avatar') ?>">change picture</a>
    </div>
    <?
    }

  }
}

do_action( 'bp_before_member_header_meta' );

?>
<div id="item-meta">
  <?php if ( function_exists( 'bp_activity_latest_update' ) ) : ?>
    <div id="latest-update">
      <?php bp_activity_latest_update( bp_displayed_user_id() ) ?>
    </div>
  <?php endif; ?>

  <div id="item-buttons">
    <?php if ( function_exists( 'bp_add_friend_button' ) ) : ?>
      <?php bp_add_friend_button() ?>
    <?php endif; ?>

    <?php if ( is_user_logged_in() && !bp_is_my_profile() && function_exists( 'bp_send_public_message_link' ) ) : ?>
      <div class="generic-button" id="post-mention">
        <a href="<?php bp_send_public_message_link() ?>" title="<?php _e( 'Mention this user in a new public message, this will send the user a notification to get their attention.', 'buddypress' ) ?>"><?php _e( 'Mention this User', 'buddypress' ) ?></a>
      </div>
    <?php endif; ?>

    <?php if ( is_user_logged_in() && !bp_is_my_profile() && function_exists( 'bp_send_private_message_link' ) ) : ?>
      <div class="generic-button" id="send-private-message">
        <a href="<?php bp_send_private_message_link() ?>" title="<?php _e( 'Send a private message to this user.', 'buddypress' ) ?>"><?php _e( 'Send Private Message', 'buddypress' ) ?></a>
      </div>
    <?php endif; ?>
  </div><!-- #item-buttons -->

  <?php
   /***
    * If you'd like to show specific profile fields here use:
    * bp_profile_field_data( 'field=About Me' ); -- Pass the name of the field
    */
  ?>

  <?php do_action( 'bp_profile_header_meta' ) ?>
</div><!-- #item-meta -->
<?

do_action( 'bp_after_member_header' );

?>
