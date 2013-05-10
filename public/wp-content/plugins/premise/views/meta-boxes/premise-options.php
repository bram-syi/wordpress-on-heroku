<?php $meta = $this->getPremiseMeta($post->ID); ?>
<?php wp_nonce_field('save-premise-settings', 'save-premise-settings-nonce'); ?>

<input type="hidden" name="premise[saving]" id="premise-saving" value="1" />
<input type="hidden" name="premise-graphics-url" id="premise-graphics-url" value="<?php esc_attr_e(esc_url(premise_get_media_upload_src('premise-resources-graphics', array('send_to_premise_field_id' => '0')))); ?>" />
<input type="hidden" name="premise-optin-url" id="premise-optin-url" value="<?php esc_attr_e(esc_url(premise_get_media_upload_src('premise-resources-optin', array('send_to_premise_field_id' => '0')))); ?>" />
<input type="hidden" name="premise-buttons-url" id="premise-buttons-url" value="<?php esc_attr_e(esc_url(premise_get_media_upload_src('premise-button-usage', array('send_to_premise_field_id' => '0')))); ?>" />

<span id="premise-landing-page-type-name" style="display:none;"><small> (<?php esc_html_e($this->getLandingPageTypeName($post->ID)); ?>)</small></span>

<div class="premise-option-box">
	<h4><label for="premise-subhead"><?php _e('Subheading'); ?></label></h4>
	<p><?php _e('Provide a great subheading to really pull your readers in.'); ?></p>
	<input tabindex="2" type="text" class="large-text subheading widget-inside" name="premise[subhead]" id="premise-subhead" value="<?php esc_attr_e($meta['subhead']); ?>" />

	<div id="subheadingwrap" class="editwidget">
		<label id="subheading-prompt-text" class="hide-if-no-js" for="premise-subhead" style=""><?php _e('Enter sub-head here'); ?></label>
		
	</div>
</div>

<div class="premise-option-box">
	<h4><?php _e('Landing Page Style'); ?></h4>
	<p><?php _e('You can choose which of the preconfigured styles you wish to use for this landing page.'); ?></p>
	<select name="premise[style]" id="premise-style">
		<?php foreach($this->getDesignSettings() as $key => $style) { ?>
		<option <?php selected($meta['style'], $key); ?> value="<?php esc_attr_e($key); ?>"><?php esc_html_e($style['premise_style_title']); ?></option>
		<?php } ?>
	</select>
</div>

<div class="premise-option-box">
	<h4><?php _e('Header Display'); ?></h4>
	<p><?php _e('You can choose whether or not to show the header (both main and subheadlines) for this landing page.  Remove it by checking the box.'); ?></p>
	<ul>
		<li>
			<label for="premise-header">
				<input <?php checked(1, $meta['header']); ?> type="checkbox" name="premise[header]" id="premise-header" value="1" />
				<?php _e('Remove the main headlines area from this landing page'); ?>
			</label>
		</li>
		<li>
			<label for="premise-header-image-hide">
				<input <?php checked(1, $meta['header-image-hide']); ?> type="checkbox" name="premise[header-image-hide]" id="premise-header-image-hide" value="1" />
				<?php _e('Remove the header image from this landing page'); ?>
			</label>
		</li>
	</ul>

	<div class="premise-dependent-container premise-header-image-hide-dependent-container">
		<h4><label for="premise-header-image"><?php _e('Header Image'); ?></label></h4>
		<p><?php printf(__('Enter a URL that points at the image you wish to use in the header of your landing page.  If you don\'t have an image handy, upload one via the <a class="thickbox" href="%s">WordPress uploader</a>.'), esc_attr(add_query_arg(array('post_id' => 0, 'send_to_premise_field_id'=>'premise-header-image', 'TB_iframe' => 1, 'width' => 640, 'height' => 459), add_query_arg('TB_iframe', null, get_upload_iframe_src('image'))))); ?></p>
		<p><?php printf(__('<strong>Note:</strong> If you leave this field blank but have entered a default image on the <a href="%s" target="_blank">main settings</a> page, that image will be used for this landing page.'), admin_url('admin.php?page=premise-main')); ?></p>
		<input type="text" class="large-text" name="premise[header-image]" id="premise-header-image" value="<?php esc_attr_e($meta['header-image']); ?>" /><br />
	</div>

</div>



<div class="premise-option-box">
	<h4><?php _e('Footer Display'); ?></h4>
	<p><?php _e('You can choose whether or not to show the footer (with text) for this landing page.  The footer is displayed by default, but you can remove it by checking the box.'); ?></p>
	<ul>
		<li>
			<label for="premise-footer">
				<input <?php checked(1, $meta['footer']); ?> type="checkbox" name="premise[footer]" id="premise-footer" value="1" />
				<?php _e('Remove the footer from this landing page'); ?>
			</label>
		</li>
	</ul>

	<div class="premise-dependent-container premise-footer-dependent-container">
		<h4><label for="premise-footer-copy"><?php _e('Footer Copy'); ?></label></h4>
		<p><?php _e('Enter a tagline that will appear in the footer of your landing page.'); ?></p>
		<input type="text" class="large-text" name="premise[footer-copy]" id="premise-footer-copy" value="<?php esc_attr_e($meta['footer-copy']); ?>" />
	</div>

</div>

<div class="premise-option-box">
	<h4><?php _e('Scripts'); ?></h4>
	<p><?php _e('Premise allows you to add content to either the header or footer.  Insert some code into the textareas below to make it appear on this particular landing page only.  These fields are meant for adding JavaScript, tracking codes and CSS, not content.'); ?></p>
	
	<div>
		<h4><label for="premise-header-scripts"><?php _e('Header Scripts'); ?></label></h4>
		<textarea rows="6" class="large-text code" name="premise[header-scripts]" id="premise-header-scripts"><?php esc_html_e($meta['header-scripts']); ?></textarea>
	</div>
	
	<div>
		<h4><label for="premise-footer-scripts"><?php _e('Footer Scripts'); ?></label></h4>
		<textarea rows="6" class="large-text code" name="premise[footer-scripts]" id="premise-footer-scripts"><?php esc_html_e($meta['footer-scripts']); ?></textarea>
	</div>
</div>

