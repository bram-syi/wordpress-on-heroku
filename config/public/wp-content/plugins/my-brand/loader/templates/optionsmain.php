   <div id="logo" class="krs1" style="background-color:transparent;background-image:url();"><p style="margin-top:15px;">Upload Your Logo Via The 'Upload Image' Button, Copy The File URL, Paste It Into The Text Box Below, Then Click The Button To Save. (330px by 130px MAX)</p>

    <div style="clear:both;padding-top:10px;background-color:transparent;"></div>

  <form method="post" action="options.php" id="krs1-options">
		<?php wp_nonce_field('update-options') ?>             
		<input type="text" name="mybrandimg" value="<?php echo get_option('mybrandimg'); ?>" style="float:left;padding:4px; font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="50" />
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandimg" />
		<div style="clear:both;padding-top:0px;background-color:transparent;"></div>
  <p class="submit" style="margin-top:1px;margin-left:21px;"><input type="submit" name="Submit" style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
	</form></div> 
  <div id="bgclr" class="krs1" style="background-color:transparent;background-image:url();"><p style="margin-top:15px;">Choose Your HEX Color Via The Color Picker, Copy The Code Including The #, Paste It Into The Text Box Below, Then Click The Button To Save. Use <b>transparent</b> for NO color.</p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
  Color Code:  <form method="post" action="options.php" id="krs2-options">
		<?php wp_nonce_field('update-options') ?>		
              <input type="text" name="mybrandbody" value="<?php echo get_option('mybrandbody'); ?>" style="float:left;padding:4px; font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="10" />
  <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandbody" />
		<div style="clear:both;padding-top:0px;background-color:transparent;"></div>
  <p class="submit" style="margin-top:1px;margin-left:21px;"><input type="submit" name="Submit" style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
	</form></div> 
  <div id="bgpic" class="krs1" style="background-color:transparent;background-image:url();"><p style="margin-top:15px;">Upload Your BG Picture Via The 'Upload Image' Button, Copy The File URL, Paste It Into The Text Box Below, Then Click The Button To Save. (Picture is set to 'Repeat')</p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
  <form method="post" action="options.php" id="krs3-options">
		<?php wp_nonce_field('update-options') ?>		
                <input type="text" name="mybrandpic" value="<?php echo get_option('mybrandpic'); ?>" style="float:left;padding:4px; font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="50" />
                <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandpic" />
		<div style="clear:both;padding-top:0px;background-color:transparent;"></div>
  <p class="submit" style="margin-top:1px;margin-left:21px;"><input type="submit" name="Submit" style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
	</form></div> 
  <div id="topclr" class="krs1" style="background-color:transparent;background-image:url();"><p style="margin-top:15px;">Choose Your HEX Color Via The Color Picker, Copy The Code Including The #, Paste It Into The Text Box Below, Then Click The Button To Save. Use <b>transparent</b> for NO color.</p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
  Color Code:    <form method="post" action="options.php" id="krs4-options">
		<?php wp_nonce_field('update-options') ?>		
                <input type="text" name="mybrandbdytp" value="<?php echo get_option('mybrandbdytp'); ?>" style="float:left;padding:4px; font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="10" />
                <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandbdytp" />
		<div style="clear:both;padding-top:0px;background-color:transparent;"></div>
  <p class="submit" style="margin-top:1px;margin-left:21px;"><input type="submit" name="Submit" style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
	</form></div> 
  <div id="lgnpic" class="krs1" style="background-color:transparent;background-image:url();"><p style="margin-top:15px;">Upload Your Login BG Via The 'Upload Image' Button, Copy The File URL, Paste It Into The Text Box Below, Then Click The Button To Save. (400px by 300px MAX)</p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
  <form method="post" action="options.php" id="krs5-options">
		<?php wp_nonce_field('update-options') ?>		
                <input type="text" name="mybrandbaklgin" value="<?php echo get_option('mybrandbaklgin'); ?>" style="float:left;padding:4px; font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="50" />
                <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandbaklgin" />
		<div style="clear:both;padding-top:0px;background-color:transparent;"></div>
  <p class="submit" style="margin-top:1px;margin-left:21px;"><input type="submit" name="Submit" style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
	</form> </div> 
  <div id="lrclr" class="krs1" style="background-color:transparent;background-image:url();"><p style="margin-top:15px;">Choose Your HEX Color Via The Color Picker, Copy The Code Including The #, Paste It Into The Text Box Below, Then Click The Button To Save. Use <b>transparent</b> for NO color.</p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>
  Color Code:   <form method="post" action="options.php" id="krs6-options">
		<?php wp_nonce_field('update-options') ?>		
                <input type="text" name="mybrandloginnav" value="<?php echo get_option('mybrandloginnav'); ?>" style="float:left;padding:4px;font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="10" />
                <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandloginnav" />
		<div style="clear:both;padding-top:0px;background-color:transparent;"></div>
  <p class="submit" style="margin-top:1px;margin-left:21px;"><input type="submit" name="Submit" style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>

	</form>
  </div>
  <div id="tempupload" class="krs1" style="background-color:transparent;background-image:url();">
                <p style="margin-top:15px;">Upload CSS File and Images (optional). Follow The <a href="<?php echo plugins_url('my-brand/loader/templates/guidelines.php?TB_iframe=true'); ?>" id="guide_notes" class="thickbox" title='Template Guidelines' onclick="return false;">Template Guidelines</a> For More Info. If You've Linked Your Images in Some Other Way, Upload CSS File ONLY. Max Two Uploads At A Time.</p><p style="margin-top:15px;"><a href="<?php echo plugins_url('my-brand/loader/templates/uploads/index.php?height=520&width=700&TB_iframe=true'); ?>" id="upload_folder" class="thickbox" title='View and Delete' onclick="return false;" style="color:#1b1b1b;border:1px solid #1b1b1b;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;padding:6px;background:#a6a6a6;" size="10">View and Delete Template Files</a></p>

		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>

  <form action="" method="post" enctype="multipart/form-data" style="height"50px;">
<input type="submit" name="upload" value="Upload" style="margin-left:450px;margin-bottom:-36px; font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;background:#000;color:#f1f1f1;" class="krs"/>
               <input type="file" name="load[]" class="multi" style="margin-left:22px;float:left;padding:4px;font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="40"/>

</form>
  </div>
		<div style="clear:both;padding-top:10px;background-color:transparent;"></div>

      </div>

    </div>