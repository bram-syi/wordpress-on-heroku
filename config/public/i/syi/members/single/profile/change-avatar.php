<h2>Change your profile picture</h2>

<?php do_action( 'bp_before_profile_avatar_upload_content' ) ?>
<? global $bp; ?>

<?php if ( !(int)bp_get_option( 'bp-disable-avatar-uploads' ) ) : ?>

	<form action="" method="post" id="avatar-upload-form" enctype="multipart/form-data">

		<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>

			<p><?php _e( 'Click below to select a JPG, GIF or PNG format photo from your computer and then click \'Upload image\' to proceed.', 'buddypress' ) ?></p>

			<p id="avatar-upload">
				<input type="file" name="file" id="file" />
			</p><p id="avatar-upload">
				<input type="submit" name="upload" id="upload" class="button green-button medium-button" value="<?php _e( 'Upload image', 'buddypress' ) ?>" />
				<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
        or <a href="<?= get_member_link(NULL, "profile") ?>" class="button gray-button small-button">cancel</a>
			</p>

			<?php if ( bp_get_user_has_avatar() ) : ?>
                <br><br>
				<p><?php _e( "If you'd like to delete your current profile picture but not upload a new one:", 'buddypress' ) ?></p>
				<p><a class="button green-button medium-button edit" href="<?php bp_avatar_delete_link() ?>" title="<?php _e( 'Delete Picture', 'buddypress' ) ?>"><?php _e( 'Delete my picture', 'buddypress' ) ?></a></p>
                <? 
				global $bp;
                $fb_avatar = get_fb_avatar_url($bp->displayed_user->id);				
				if (!empty($fb_avatar)) {
				?>
                <p style="font-size:12px; width:250px;"><img src="<?=$fb_avatar?>" alt="your facebook profile" class="avatar" width="50" height="50" style="float:left; margin-right:10px;"/> 
                When you have no profile picture, your Facebook profile picture will be used instead. </p>
                <?  
				}
				
				?>
			<?php endif; ?>

			<?php wp_nonce_field( 'bp_avatar_upload' ) ?>

		<?php endif; ?>
		<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>
			<p>Crop the picture as you'd like it to appear, and then click: 
			<input type="submit" class="button green-button medium-button" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php _e( 'Use this picture', 'buddypress' ) ?>" />
      </p>

			<img src="<?php bp_avatar_to_crop() ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Picture to crop', 'buddypress' ) ?>" />

			<input type="hidden" name="image_src" id="image_src" value="<?php echo str_replace("/blogs.dir/1/files","", bp_get_avatar_to_crop_src()) ?>" />
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />

			<?php wp_nonce_field( 'bp_avatar_cropstore' ) ?>

		<?php endif; ?>

	</form>
<?php endif; ?>
<?php do_action( 'bp_after_profile_avatar_upload_content' ) ?> 

<?
