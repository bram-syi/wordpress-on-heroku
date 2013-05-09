<?php $buttons = $this->getConfiguredButtons(); ?>
<div class="premise-thickbox-container">
	<h3 class="media-title"><?php _e('Insert a Button'); ?></h3>
	<p><small><strong><?php _e('Please Note:'); ?></strong> <?php _e('The buttons use styling techniques supported by most modern browsers (like Firefox, Chrome, Safari, and the upcoming version of IE).  Although the buttons will work in older browsers, the styling may not render as detailed as it will in modern browsers.'); ?></small></p>
	
	<form method="post" id="insert-premise-button-form">
	<?php if(empty($buttons)) { ?>
		<p><?php printf(__('You don\'t yet have any buttons configured.  <a target="_blank" href="%s">Configure one now.</a>'), admin_url('admin.php?page=premise-styles#your-buttons')); ?></p>
		<p class="submit">
			<input type="button" class="button button-usage-cancel" value="<?php _e('Close'); ?>" />
		</p>
	<?php } else { ?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="premise-button-text"><?php _e('Button Text'); ?></label></th>
					<td>
						<input type="text" class="text large-text" id="premise-button-text" name="premise-button-text" value="" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="premise-button-url"><?php _e('Button Url'); ?></label></th>
					<td>
						<input type="text" class="text large-text" id="premise-button-url" name="premise-button-url" value="" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="premise-button-text"><?php _e('Button Style'); ?></label></th>
					<td>
						<select name="premise-button-style" id="premise-button-style">
							<?php foreach($buttons as $key => $button) { ?>
							<option value="<?php esc_attr_e($key); ?>"><?php esc_html_e($button['title']); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
					
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" class="button button-primary" id="premise-button-insert" value="<?php _e('Insert'); ?>" />
			<input type="button" class="button button-usage-cancel" value="<?php _e('Close'); ?>" />
		</p>
		
	<?php } ?>
	</form>
</div>