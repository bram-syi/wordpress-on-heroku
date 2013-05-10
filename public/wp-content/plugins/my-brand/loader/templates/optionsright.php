     <div class="right">
       <div id="colorpicker" style="margin-top:0px;margin-left:-1px;"></div>
  <br />
         <p style="color:#000;font-size:8px;margin-top:-17px;text-align:center;">(copy and paste your #code)</p>

          <form style="margin-left:26px;margin-top:0px;"><input type="text" id="color" name="color" value="#b67e3e" /></form> 
     <form style="margin-top:10px;margin-left:-10px;">       <a href="media-upload.php?type=image&height=820&width=630&TB_iframe=true" id="add_image" class="thickbox" title='Add an Image' onclick="return false;"><img src="<?php echo plugins_url('my-brand/loader/images/upload.png') ?>" alt="" /></a>
      </form>	
     </div>

  <div id="new_stuff">
    <div id="new_block">

     <div class="one" style="width:200px;float:left;border-right:1px solid #bfbfbf;">

  <h4>Position The Login Form</h4>

   <p style="margin-top:-10px;">ex: 20px 20px 20px 20px</p>
  <form method="post" action="options.php" id="krs7-options">
		<?php wp_nonce_field('update-options') ?>		
                <input type="text" name="mybrandformposition" value="<?php echo get_option('mybrandformposition'); ?>" style="float:left;padding:4px;font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="22" />
                <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandformposition" />
		<div style="clear:both;padding-top:0px;"></div>
  <p class="submit" style="margin-top:15px;margin-left:21px;"><input type="submit" name="Submit" style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;"></div>
	</form>
   </div>

  <div class="two" style="width:300px;float:left;margin-left:2px;padding-left:18px;">
   <h4>Admin Logo - Top Left Corner (look up)</h4>
    <p style="margin-top:-10px;">max size: 40px X 40px. Paste URL Below.</p>
     <form method="post" action="options.php" id="krs8-options">
		<?php wp_nonce_field('update-options') ?>		
                <input type="text" name="mybrandadminlogo" value="<?php echo get_option('mybrandadminlogo'); ?>" style="float:left;padding:4px; font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="22" />
                <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandadminlogo" />
		<div style="clear:both;padding-top:0px;"></div>
  <p class="submit" style="margin-top:15px;margin-left:21px;"><input type="submit" name="Submit" style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;"></div>
	</form>
   </div>
  <div class="three" style="width:255px;float:left;margin-left:2px;border-left:1px solid #bfbfbf;padding-left:22px;">
   <h4>Background To The Administration</h4>
    <p style="margin-top:-10px;">Image Repeats. Paste URL Below.</p>
  <form method="post" action="options.php" id="krs9-options">
		<?php wp_nonce_field('update-options') ?>		
                <input type="text" name="mybrandadminbg" value="<?php echo get_option('mybrandadminbg'); ?>" style="float:left;padding:4px; font-size:1em;border:1px solid #a6a6a6;-moz-border-radius:6px;-khtml-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;" size="22" />
                <input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="mybrandadminbg" />
		<div style="clear:both;padding-top:0px;"></div>
<p class="submit" style="margin-top:15px;margin-left:21px;"><input type="submit" name="Submit"  style="background-image:url(<?php echo plugins_url('my-brand/loader/images/but2back.png);background-color:#000;'); ?>" class="krs" value="<?php _e('Save Changes') ?>" /></p>
		<div style="clear:both;padding-top:10px;"></div>
	</form>
     </div>
  </div> 