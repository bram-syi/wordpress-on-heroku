<?php 
SaveSettings();

function SaveSettings(){
	if (!isset($_POST['page_options'])){
		return ;
	}
	check_admin_referer('update-options');
	$fields = explode(',', $_POST['page_options']) ;
	$fields = array_intersect(array_values($fields), $_POST ) ;
	foreach ($fields as $setting) {
		$updated = update_option( $setting, $_POST[$setting] );
		/*print_r($setting);
		print_r($_POST[$setting]);*/
		if ($updated === false){
			$_POST['error'] = __('Error: settings not saved.');
			return ;
		}
	}
	$_POST['notice'] = __('Settings Saved.');
	
}

?>


<div class="wrap">
<h2>SYI Webservice</h2>
<?php if(isset($_POST['notice'])){
	echo " <div class=\"updated\"><strong>{$_POST['notice']}</strong></div>";
}
if(isset($_POST['error'])){
	echo " <div class=\"error\"><strong>{$_POST['error']}</strong></div>";
}
?>


<form method="post" action="">
<?php wp_nonce_field('update-options');?>
<table class="form-table">
 <!-- 
<tr valign="top">
<th scope="row"><?php echo _e('Post title')?></th>
<td><input type="text" name="post_title" style="width:50px" value="<?php echo get_option('post_title'); ?>" /></td>
</tr>
 -->
<tr valign="top">
<th scope="row"><?php echo _e('New post title')?></th>
<td>
<input name="new_post_title" style="width:500px" value="<?php echo get_option('new_post_title', '%name% has been impacted.');?>">
</td>
</tr>
<?php 
$path_pieces = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
$webservice_url = WP_PLUGIN_URL .'/'. $path_pieces[count($path_pieces)-1] . '/webservice.php';
?> 
</table>

<p style="color:gray"><?php echo _e('Webservice url')?>: <i><?php echo $webservice_url?></i></p>
<input type="hidden" name="page_options" value="new_post_title" />
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>