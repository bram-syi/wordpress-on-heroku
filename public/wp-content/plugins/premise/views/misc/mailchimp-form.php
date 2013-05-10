<div class="entry-optin-optin align<?php echo $align; ?>">
	<?php if($title) { ?>
	<div class="optin-header"><?php esc_html_e($title); ?></div>
	<?php } ?>
	<div class="optin-box">
		<form class="" method="post" action="<?php esc_attr_e(add_query_arg(array())); ?>#mc<?php echo $mcnumber; ?>" id="mc<?php echo $mcnumber; ?>">

			<ul class="signup-form-messages">
				<?php foreach($messages as $message) { ?>
				<li class="signup-form-message <?php echo $message['type']; ?>"><?php esc_html_e($message['body']); ?></li>
				<?php } ?>
			</ul>


			<ul class="form-container">
				<?php foreach($mv as $mvdata) { ?>
				<li>
					<label for="mailchimp-<?php echo $mvdata['tag']; ?>"><?php esc_html_e($mvdata['name']); ?> <?php if($mvdata['req'] == 1) { ?>*<?php } ?></label>
					<input type="text" id="mailchimp-<?php echo $mvdata['tag']; ?>" name="mailchimp[<?php echo $mvdata['tag']; ?>]" value="<?php esc_attr_e(stripslashes($_POST['mailchimp'][$mvdata['tag']])); ?>" />
				</li>
				<?php } ?>
				<li class="premise-form-container-submit">
					<input type="submit" name="mailchimp[signup]" id="mailchimp-signup" value="<?php _e('Signup'); ?>" />
				</li>
			</ul>
			<?php wp_nonce_field('mailchimp-signup-'.$id, 'mailchimp-signup-nonce'); ?>
			<input type="hidden" name="mailchimp[list]" id="mailchimp-list" value="<?php esc_attr_e($id); ?>" />
			<input type="hidden" name="mailchimp[formkey]" id="mailchimp-formkey" value="mc<?php echo $mcnumber; ?>" />
			<input type="hidden" name="mailchimp[currenturl]" id="mailchimp-currenturl" value="<?php esc_attr_e(add_query_arg(array())); ?>" />

		</form>
	</div>
</div>