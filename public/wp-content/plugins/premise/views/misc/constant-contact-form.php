<div class="entry-optin-optin align<?php echo $align; ?>">
	<?php if($title) { ?>
	<div class="optin-header"><?php esc_html_e($title); ?></div>
	<?php } ?>
	<div class="optin-box">
		<form class="" method="post" action="<?php esc_attr_e(add_query_arg(array())); ?>#cc<?php echo $ccnumber; ?>" id="cc<?php echo $ccnumber; ?>">

			<ul class="signup-form-messages">
				<?php foreach($messages as $message) { ?>
				<li class="signup-form-message <?php echo $message['type']; ?>"><?php esc_html_e($message['body']); ?></li>
				<?php } ?>
			</ul>


			<ul class="form-container">
				<li>
					<label for="constant-contact-first-name"><?php _e('First Name'); ?> *</label>
					<input type="text" id="constant-contact-first-name" name="constant-contact[first-name]" value="<?php esc_attr_e(stripslashes($_POST['constant-contact']['first-name'])); ?>" />
				</li>
				<li>
					<label for="constant-contact-last-name"><?php _e('Last Name'); ?> *</label>
					<input type="text" id="constant-contact-last-name" name="constant-contact[last-name]" value="<?php esc_attr_e(stripslashes($_POST['constant-contact']['last-name'])); ?>" />
				</li>
				<li>
					<label for="constant-contact-email"><?php _e('Email'); ?> *</label>
					<input type="text" id="constant-contact-email" name="constant-contact[email]" value="<?php esc_attr_e(stripslashes($_POST['constant-contact']['email'])); ?>" />
				</li>
				<li class="premise-form-container-submit">
					<input type="submit" name="constant-contact[signup]" id="constant-contact-signup" value="<?php _e('Signup'); ?>" />
				</li>
			</ul>
			<?php wp_nonce_field('constant-contact-signup-'.$id, 'constant-contact-signup-nonce'); ?>
			<input type="hidden" name="constant-contact[list]" id="constant-contact-list" value="<?php esc_attr_e($id); ?>" />
			<input type="hidden" name="constant-contact[formkey]" id="constant-contact-formkey" value="cc<?php echo $ccnumber; ?>" />
			<input type="hidden" name="constant-contact[currenturl]" id="constant-contact-currenturl" value="<?php esc_attr_e(add_query_arg(array())); ?>" />

		</form>
	</div>
</div>