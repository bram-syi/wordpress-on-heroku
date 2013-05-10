<div class="wrap">
<?
$post = $form->getPostVars();
if ($form->list->status != null){
  print $form->list->status;
}
?>

<h2><? _e($form->title) ?></h2>
<form method="post" action="<?= $list->url; ?>">
<input type='hidden' name='option_page' value='gift' />
<? wp_nonce_field('gift-admin') ?>

<table class="form-table" id="adminGiftEdit">
<tr valign="top">
<th scope="row"><label for="gift_name"><? _e('Name') ?></label></th>
<td><input name="gift_name" type="text" id="gift_name" value="<?= $post['gift_name']; ?>" size="20" /><br />
<? _e('A short description of this gift (ex. a book, a pair of shoe)') ?></td>
</tr>

<tr valign="top">
<th scope="row"><label for="gift_name_plural"><? _e('Plural Name') ?></label></th>
<td><input name="gift_name_plural" type="text" id="gift_name_plural" value="<?= $post['gift_name_plural']; ?>" size="20" /><br />
<? _e('Plural form of the gift name for quantity more than 1 (ex. books, pairs of shoe)') ?></td>
</tr>

<tr valign="top">
<th scope="row"><label for="gift_desc"><? _e('Description') ?></label></th>
<td>
<? if ($post['post_id'] == 0) { ?>
<textarea name="gift_desc" cols="60" rows="10" id="gift_desc" style="width: 98%; font-size: 12px;" class="code"><?= $post['gift_desc']; ?></textarea>
<? } else { ?>
  <input name="gift_desc" type="hidden" value="---" />
  <a target="_new" href="post.php?post=<?= $post['post_id'] ?>&action=edit">Edit gift details</a>
<? } ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="gift_tags"><? _e('Tags') ?></label></th>
<td><input name="gift_tags" type="text" id="gift_tags" value="<?= $post['gift_tags']; ?>" size="80" /><br />
<? _e('Enter the tags in lower case; use comma for multiple entries.') ?></td>
</tr>
<tr valign="top">
<th scope="row"><label for="gift_quantity"><? _e('Quantity') ?></label></th>
<td>
<input name="gift_quantity" type="text" id="gift_quantity" value="<?= $post['gift_quantity']; ?>" size="3" /> <? _e('needed') ?>
</td>
</tr>  
<tr valign="top">
<th scope="row"><label for="gift_cost"><? _e('Cost') ?></label></th>
<td>
<input name="gift_cost" type="text" id="gift_cost" value="<?= $post['gift_cost']; ?>" size="3" /> <? _e('dollars') ?> (current aggregation:
<input name="current_amount" type="text" id="current_amount" value="<?= intval($post['current_amount']); ?>" size="3" /> <? _e('dollars') ?>)
<br/><input name="var_amount" type="checkbox" id="var_amount" value="1" <?= $post['var_amount']=='1'?'checked="checked"':'' ?> /> 
<? _e('Allow donor to specify the amount (must be at least this much)') ?>
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="towards_gift_id"><? _e('Aggregate towards gift') ?></label></th>
<td>
<select name="towards_gift_id">
<option value="0">No Aggregation</option>
<? 
  global $wpdb;
  $gifts_data = $wpdb->get_results( 
  $wpdb->prepare(
    "SELECT id, displayName FROM gift WHERE active = 1 AND blog_id = %d", $form->list->blogId ) );
  
  for ($i=0; $i < count($gifts_data); $i++) { 
	if ($post['towards_gift_id'] == $gifts_data[$i]->id){
	  ?>
	  <option value="<?= $gifts_data[$i]->id ?>" selected><?= $gifts_data[$i]->displayName ?></option>
	  <? 
	  } else {
	  ?>       
	  <option value="<?= $gifts_data[$i]->id ?>"><?= $gifts_data[$i]->displayName ?></option>
	  <? 
	} 
  }
?>
</select>
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="gift_link_text"><? _e('Description link text') ?></label></th>
<td>
<input name="gift_link_text" type="text" id="gift_link_text" size="80" value="<?= $post['gift_link_text']; ?>" size="3" /> 
<br/><? _e('(ex. "see a video about this")') ?>
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="gift_link_href"><? _e('Description link reference') ?></label></th>
<td>
<input name="gift_link_href" type="text" id="gift_link_href" size="80" value="<?= $post['gift_link_href']; ?>" size="3" /> 
<br/><? _e('(http:// link to a post or page on this site)') ?>
</td>
</tr>
<tr valign="top">
  <th scope="row"><label for="campaign"><? _e('Campaign') ?></label></th>
  <td><input name="campaign" type="text" id="campaign" value="<?= $post['campaign']; ?>" size="30" /></td>
</tr>
</table>
<p class="submit">
<input type="hidden" name="gift_id" value="<?= $post['gift_id']; ?>" />
<input type="hidden" name="action" value="save" />
<input type="submit" name="Submit" value="<? _e($form->button) ?>" class="button" />
<input type="submit" name="Cancel" value="<? _e('Cancel') ?>" class="button" />
</p>
</form>
</div>
