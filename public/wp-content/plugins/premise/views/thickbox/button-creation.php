<?php $button = $this->getConfiguredButton($_GET['premise-button-id']); ?>
<div class="premise-thickbox-container">
	<h3 class="media-title"><?php _e('Create a Button'); ?></h3>
	
	<form method="post" id="premise-button-form" action="<?php esc_attr_e(admin_url('admin-ajax.php')); ?>">
		<?php if(!empty($_GET['premise-button-id'])) { ?>
			<input type="hidden" name="premise-button-id" value="<?php esc_attr_e($_GET['premise-button-id']); ?>" />
		<?php } ?>
		
		<div id="premise-button-creation-container">
			<table class="form-table button-creation-form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="button-editing-title"><?php _e('Button Name'); ?></label></th>
						<td>
							<input class="large-text" type="text" name="button-editing[title]" id="button-editing-title" value="<?php esc_attr_e($button['title']); ?>" /><br />
							<?php _e('This name is for identification purposes only.  You will choose the text for the button while configuring it with the landing page editor.'); ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Background'); ?></th>
						<td>
							<ul>
								<li>
									<label class="descriptor" for="button-editing-background-color-1"><?php _e('Start Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[background-color-1]" id="button-editing-background-color-1" value="<?php esc_attr_e($button['background-color-1']); ?>" />
								</li>
								<?php foreach(range(2,4) as $key) { ?>
								<li>
									<label class="descriptor" for="button-editing-background-color-<?php echo $key; ?>"><?php _e('Color Stop'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[background-color-<?php echo $key; ?>]" id="button-editing-background-color-<?php echo $key; ?>" value="<?php esc_attr_e($button['background-color-'.$key]); ?>" />
									<select name="button-editing[background-color-<?php echo $key; ?>-position]" id="button-editing-background-color-<?php echo $key; ?>-position">
										<?php foreach(range(0,100) as $value) { ?>
											<option <?php selected($value, $button['background-color-'.$key.'-position']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>%</option>
										<?php } ?>
									</select>
									<label>
										<input <?php checked('yes', $button['background-color-'.$key.'-enabled']); ?> class="color-stop-enabler" rel="<?php echo $key; ?>" type="checkbox" id="button-editing-background-color-<?php echo $key; ?>-enabled" name="button-editing[background-color-<?php echo $key; ?>-enabled]" value="yes" />
										<?php _e('Enable'); ?>
									</label>
								</li>
								<?php } ?>
								<li>
									<label class="descriptor" for="button-editing-background-color-5"><?php _e('End Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[background-color-5]" id="button-editing-background-color-5" value="<?php esc_attr_e($button['background-color-5']); ?>" />
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Background on Hover'); ?></th>
						<td>
							<ul>
								<li>
									<label class="descriptor" for="button-editing-background-color-hover-1"><?php _e('Start Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[background-color-hover-1]" id="button-editing-background-color-hover-1" value="<?php esc_attr_e($button['background-color-hover-1']); ?>" />
								</li>
								<?php foreach(range(2,4) as $key) { ?>
								<li>
									<label class="descriptor" for="button-editing-background-color-hover-<?php echo $key; ?>"><?php _e('Color Stop'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[background-color-hover-<?php echo $key; ?>]" id="button-editing-background-color-hover-<?php echo $key; ?>" value="<?php esc_attr_e($button['background-color-hover-'.$key]); ?>" />
									<select name="button-editing[background-color-hover-<?php echo $key; ?>-position]" id="button-editing-background-color-hover-<?php echo $key; ?>-position">
										<?php foreach(range(0,100) as $value) { ?>
											<option <?php selected($value, $button['background-color-hover-'.$key.'-position']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>%</option>
										<?php } ?>
									</select>
									<label>
										<input <?php checked('yes', $button['background-color-hover-'.$key.'-enabled']); ?> class="color-stop-enabler-hover color-stop-enabler" rel="hover-<?php echo $key; ?>" type="checkbox" id="button-editing-background-color-hover-<?php echo $key; ?>-enabled" name="button-editing[background-color-hover-<?php echo $key; ?>-enabled]" value="yes" />
										<?php _e('Enable'); ?>
									</label>
								</li>
								<?php } ?>
								<li>
									<label class="descriptor" for="button-editing-background-color-hover-5"><?php _e('End Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[background-color-hover-5]" id="button-editing-background-color-hover-5" value="<?php esc_attr_e($button['background-color-hover-5']); ?>" />
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Border/Padding'); ?></th>
						<td>
							<ul>
								<li>
									<label class="descriptor" for="button-editing-border-color"><?php _e('Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[border-color]" id="button-editing-border-color" value="<?php esc_attr_e($button['border-color']); ?>" />
								</li>
								<li>
									<label class="descriptor" for="button-editing-border-width"><?php _e('Width'); ?></label>
									<select name="button-editing[border-width]" id="button-editing-border-width">
										<?php foreach(range(0,10) as $value) { ?>
										<option <?php selected($value, $button['border-width']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-border-radius"><?php _e('Radius'); ?></label>
									<select name="button-editing[border-radius]" id="button-editing-border-radius">
										<?php foreach(range(0,100) as $value) { ?>
										<option <?php selected($value, $button['border-radius']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-padding-tb"><?php _e('Top + Bottom'); ?></label>
									<select name="button-editing[padding-tb]" id="button-editing-padding-tb">
										<?php foreach(range(0,50) as $value) { ?>
										<option <?php selected($value, $button['padding-tb']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-padding-lr"><?php _e('Left + Right'); ?></label>
									<select name="button-editing[padding-lr]" id="button-editing-padding-lr">
										<?php foreach(range(0,50) as $value) { ?>
										<option <?php selected($value, $button['padding-lr']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Drop Shadow'); ?></th>
						<td>
							<ul>
								<li>
									<label class="descriptor" for="button-editing-drop-shadow-color"><?php _e('Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[drop-shadow-color]" id="button-editing-drop-shadow-color" value="<?php esc_attr_e($button['drop-shadow-color']); ?>" />
								</li>
								<li>
									<label class="descriptor" for="button-editing-drop-shadow-opacity"><?php _e('Opacity'); ?></label>
									<select name="button-editing[drop-shadow-opacity]" id="button-editing-drop-shadow-opacity">
										<?php foreach(range(0,10) as $value) { $value = $value / 10; ?>
										<option <?php selected($value, $button['drop-shadow-opacity']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?></option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-drop-shadow-x"><?php _e('X'); ?></label>
									<select name="button-editing[drop-shadow-x]" id="button-editing-drop-shadow-x">
										<?php foreach(range(-10,10) as $value) { ?>
										<option <?php selected($value, $button['drop-shadow-x']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-drop-shadow-y"><?php _e('Y'); ?></label>
									<select name="button-editing[drop-shadow-y]" id="button-editing-drop-shadow-y">
										<?php foreach(range(-10,10) as $value) { ?>
										<option <?php selected($value, $button['drop-shadow-y']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-drop-shadow-size"><?php _e('Size'); ?></label>
									<select name="button-editing[drop-shadow-size]" id="button-editing-drop-shadow-size">
										<?php foreach(range(0,50) as $value) { ?>
										<option <?php selected($value, $button['drop-shadow-size']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Inner Shadow'); ?></th>
						<td>
							<ul>
								<li>
									<label class="descriptor" for="button-editing-inset-shadow-color"><?php _e('Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[inset-shadow-color]" id="button-editing-inset-shadow-color" value="<?php esc_attr_e($button['inset-shadow-color']); ?>" />
								</li>
								<li>
									<label class="descriptor" for="button-editing-inset-shadow-opacity"><?php _e('Opacity'); ?></label>
									<select name="button-editing[inset-shadow-opacity]" id="button-editing-inset-shadow-opacity">
										<?php foreach(range(0,10) as $value) { $value = $value / 10; ?>
										<option <?php selected($value, $button['inset-shadow-opacity']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?></option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-inset-shadow-x"><?php _e('X'); ?></label>
									<select name="button-editing[inset-shadow-x]" id="button-editing-inset-shadow-x">
										<?php foreach(range(-10,10) as $value) { ?>
										<option <?php selected($value, $button['inset-shadow-x']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-inset-shadow-y"><?php _e('Y'); ?></label>
									<select name="button-editing[inset-shadow-y]" id="button-editing-inset-shadow-y">
										<?php foreach(range(-10,10) as $value) { ?>
										<option <?php selected($value, $button['inset-shadow-y']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-inset-shadow-size"><?php _e('Size'); ?></label>
									<select name="button-editing[inset-shadow-size]" id="button-editing-inset-shadow-size">
										<?php foreach(range(0,50) as $value) { ?>
										<option <?php selected($value, $button['inset-shadow-size']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Text Font'); ?></th>
						<td>
							<ul>
								<li>
									<label class="descriptor" for="button-editing-font-family"><?php _e('Font'); ?></label>
									<select name="button-editing[font-family]" id="button-editing-font-family">
										<?php echo premise_create_options($button['font-family'], 'family'); ?>
									</select>
								</li>
								
								<li>
									<label class="descriptor" for="button-editing-font-color"><?php _e('Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[font-color]" id="button-editing-font-color" value="<?php esc_attr_e($button['font-color']); ?>" />
								</li>
								
								<li>
									<label class="descriptor" for="button-editing-font-size"><?php _e('Size'); ?></label>
									<select name="button-editing[font-size]" id="button-editing-font-size">
										<?php foreach(range(0,50) as $value) { ?>
										<option <?php selected($value, $button['font-size']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Text Shadow 1'); ?></th>
						<td>
							<ul>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-1-color"><?php _e('Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[text-shadow-1-color]" id="button-editing-text-shadow-1-color" value="<?php esc_attr_e($button['text-shadow-1-color']); ?>" />
								</li>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-1-opacity"><?php _e('Opacity'); ?></label>
									<select name="button-editing[text-shadow-1-opacity]" id="button-editing-text-shadow-1-opacity">
										<?php foreach(range(0,10) as $value) { $value = $value / 10; ?>
										<option <?php selected($value, $button['text-shadow-1-opacity']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?></option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-1-x"><?php _e('X'); ?></label>
									<select name="button-editing[text-shadow-1-x]" id="button-editing-text-shadow-1-x">
										<?php foreach(range(-10,10) as $value) { ?>
										<option <?php selected($value, $button['text-shadow-1-x']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-1-y"><?php _e('Y'); ?></label>
									<select name="button-editing[text-shadow-1-y]" id="button-editing-text-shadow-1-y">
										<?php foreach(range(-10,10) as $value) { ?>
										<option <?php selected($value, $button['text-shadow-1-y']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-1-size"><?php _e('Size'); ?></label>
									<select name="button-editing[text-shadow-1-size]" id="button-editing-text-shadow-1-size">
										<?php foreach(range(0,50) as $value) { ?>
										<option <?php selected($value, $button['text-shadow-1-size']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Text Shadow 2'); ?></th>
						<td>
							<ul>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-2-color"><?php _e('Color'); ?></label>
									<input type="text" size="7" class="color-picker" name="button-editing[text-shadow-2-color]" id="button-editing-text-shadow-2-color" value="<?php esc_attr_e($button['text-shadow-2-color']); ?>" />
								</li>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-2-opacity"><?php _e('Opacity'); ?></label>
									<select name="button-editing[text-shadow-2-opacity]" id="button-editing-text-shadow-2-opacity">
										<?php foreach(range(0,10) as $value) { $value = $value / 10; ?>
										<option <?php selected($value, $button['text-shadow-2-opacity']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?></option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-2-x"><?php _e('X'); ?></label>
									<select name="button-editing[text-shadow-2-x]" id="button-editing-text-shadow-2-x">
										<?php foreach(range(-10,10) as $value) { ?>
										<option <?php selected($value, $button['text-shadow-2-x']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-2-y"><?php _e('Y'); ?></label>
									<select name="button-editing[text-shadow-2-y]" id="button-editing-text-shadow-2-y">
										<?php foreach(range(-10,10) as $value) { ?>
										<option <?php selected($value, $button['text-shadow-2-y']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
								<li>
									<label class="descriptor" for="button-editing-text-shadow-2-size"><?php _e('Size'); ?></label>
									<select name="button-editing[text-shadow-2-size]" id="button-editing-text-shadow-2-size">
										<?php foreach(range(0,50) as $value) { ?>
										<option <?php selected($value, $button['text-shadow-2-size']); ?> value="<?php esc_attr_e($value); ?>"><?php esc_html_e($value); ?>px</option>
										<?php } ?>
									</select>
								</li>
							</ul>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	
		<pre class="code" id="button-code" style="display: none;">
			<?php echo $this->getButtonCode($button); ?>
		</pre>
		
		<h3><?php _e('Preview'); ?></h3>
		<div id="button-preview">
			<style type="text/css" id="example-button-style"></style>
			
			<br /><br />
			<a class="css3button" href="#" onclick="return false;"><?php _e('Example'); ?></a>
			<br /><br />
		</div>
		
		<p class="submit">
			<?php wp_nonce_field('premise-save-button', 'premise-save-button-nonce'); ?>
			<input type="hidden" name="action" value="premise_save_button" />
			<input type="submit" class="button button-primary" id="save-button" value="<?php _e('Save'); ?>" />
			<input type="button" class="button button-usage-cancel" value="<?php _e('Cancel'); ?>" />
		</p>
	</form>
	
	<?php $key = 'XXX'; ?>
	<div id="moz-background-color-stop-template" style="display:none;">
		<div class="background-color-<?php echo $key; ?>-enabled-container">,<span id="background-color-<?php echo $key; ?>-moz-flag"><span><span class="c background-color-<?php echo $key; ?>"><?php echo $button['background-color-'.$key] ?></span> <span class="p background-color-<?php echo $key; ?>-position"><?php echo $button['background-color-'.$key.'-position']; ?></span>%</span></div>
	</div>
	<div id="webkit-background-color-stop-template" style="display:none;">
		<div class="background-color-<?php echo $key; ?>-enabled-container">,<span class="background-color-<?php echo $key; ?>-container" id="background-color-<?php echo $key; ?>-webkit-flag"><span>color-stop(<span class="p">0.<span class="background-color-<?php echo $key; ?>-position"><?php echo $button['background-color-'.$key.'-position']; ?></span></span>, <span class="c background-color-<?php echo $key; ?>"><?php echo $button['background-color-'.$key]; ?></span>)</span></span></div>
	</div>
</div>

<script type="text/javascript">
	function premise_button_update_example_css() {
		jQuery('#example-button-style').text(jQuery('#button-code').text());
	}
	
	jQuery(document).ready(function($) {
	    // Add color picker to color input boxes.
	    $('input:text.color-picker').each(function (i) {
	    	var $this = $(this);
	    	var val = $.trim($this.val());
	    	if('' != val) {
	    		$this.css('background-color', val);
	    	}
	    	
	        $(this).after('<div id="picker-' + i + '" style="z-index: 100; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
	        $('#picker-' + i).hide().farbtastic(function(color) { $this.css('background-color', color).val(color); });
	        var picker = $.farbtastic('#picker-'+i);
	        picker.setColor(val);
	    })
	    .focus(function() {
	        $(this).next().show();
	    })
	    .blur(function() {
	        $(this).next().hide();
	        $(this).css('background-color', $(this).val());
	        $.farbtastic('#'+$(this).next().attr('id')).setColor($(this).val());
	    }).keypress(function(event) {
	    	if(event.which == 13) { // They pressed enter
	    		event.preventDefault();
	    		$(this).next().hide();
	    		$(this).css('background-color', $(this).val());
		        $.farbtastic('#'+$(this).next().attr('id')).setColor($(this).val());
	    	}
	    });
	    
	    $('#premise-button-creation-container').find('input[type=text],select').bind('blur', function(event) {
	    	var $this = $(this);
	    	var $buttoncode = $('#button-code');
			var id = $this.attr('id').replace('button-editing-','');
	    	if('' == id) {
	    		id = $this.attr('id').replace('button-editing-','');
	    	}
	    	
	    	var val = $this.val();
	    	
	    	var $element = $('#button-code').find('.'+id);
	    	if($element.is('.rgb')) {
	    		val = hex2rgb(val);
	    	}
	    	
	    	$element.text(val);
	    	premise_button_update_example_css();
	    });
	    
	    $('#premise-button-creation-container').find('input[type=checkbox]').bind('change', function(event) {
	    	var $this = $(this);
	    	var $buttoncode = $('#button-code');
			var id = $this.attr('id').replace('button-editing-','');
	    	if('' == id) {
	    		id = $this.attr('id').replace('button-editing-','');
	    	}
	    	
	    	if($this.is('.color-stop-enabler')) {
	    		if($this.is(':checked')) {
    				
	    			$buttoncode.find('.moz-background-color-stops, .webkit-background-color-stops').empty();
	    			$('.color-stop-enabler:checked').each(function(i) {
		    			var hover = '';
	    				if($(this).is('.color-stop-enabler-hover')) {
	    					hover = '-hover';
	    				}
	    			
	    				var rel = $(this).attr('rel');
	    				var $mozclone = $($('#moz-background-color-stop-template').html().replace(/XXX/g,rel));
	    				var $webkitclone = $($('#webkit-background-color-stop-template').html().replace(/XXX/g,rel));

						$mozclone.find('.background-color-'+rel+'-position').text($('#button-editing-background-color-'+rel+'-position').val());
						$mozclone.find('.background-color-'+rel).text($('#button-editing-background-color-'+rel).val());
						
						$webkitclone.find('.background-color-'+rel+'-position').text($('#button-editing-background-color-'+rel+'-position').val());
						$webkitclone.find('.background-color-'+rel).text($('#button-editing-background-color-'+rel).val());
	    				
	    				
	    				$buttoncode.find('.moz-background-color'+hover+'-stops').append($mozclone);
	    				$buttoncode.find('.webkit-background-color'+hover+'-stops').append($webkitclone);
	    			}); 
	    		} else {
	    			$buttoncode.find('.'+id+'-container').remove();
	    		}
	    		
	    		
	    		premise_button_update_example_css();
	    	}
	    });
	    
	    premise_button_update_example_css();
	    
	    $('#premise-button-form').submit(function() {
	    	var name = $('#button-editing-title').val();
	    	if($.trim(name) == '') {
	    		$('#button-editing-title').val('My Button');
	    	}
	    });
	    $('#premise-button-form').ajaxForm(function() {
			var win = window.dialogArguments || opener || parent || top;
			win.location.reload(true);
	    });
	});
</script>
