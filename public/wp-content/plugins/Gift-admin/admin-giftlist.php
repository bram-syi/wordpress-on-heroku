<script language="JavaScript" type="text/javascript">
function chkConfirmDelete(url)
{
  var answer = confirm ("Are you sure you want to delete this Gift?\n"+
      "Deleting this Gift will result in loss of all Gift-related data.\n"+
      "Press OK to proceed to Deletion.")
  if (answer) window.location = url;
}

function getOptionValue(obj)
{
	var index = obj.selectedIndex;
	var objValue = obj.options[index].value;
	document.getElementById('orderBy').value = objValue;
}
</script>

<div class="wrap">
<?
if ($list->status != null)
{
  print $list->status;
}
?>
<h2><? _e($list->title); ?></h2>
<form method="post" action="<?= $list->url; ?>">
<input type='hidden' name='option_page' value='gift' />
<? wp_nonce_field('gift-admin') ?>
<?
if (count($list->gifts) == 0)
{
  print 'Empty list';
}
else for($i = 0; $i < count($list->gifts); $i++) {
	$gift = $list->gifts[$i];
  	$html = $list->gift2html($gift);
  	$link = $list->getLinkTo($gift['id']);
    $avg_tg = get_avg_tgi($gift['id'],true);
	?>
	<div id="gift<?=$i?>" style='margin: 5px 2px; font-size: 11pt;'>
	  <? if($list->canDelete) { ?>
		  <img id="delete<?=$i?>" style="float:right;" src="/wp-content/plugins/Gift-admin/img/delete.jpg" width="16" height="16" border="0" alt="Delete" title="Delete" onClick="chkConfirmDelete('<?= add_query_arg('action', 'delete', $link) ?>');" />
	  <? } ?>
	  <? if($list->isInactive) { ?>
      #<?=$gift['id']?>:
	     <strong><?= $html['gift_name'] ?></strong>
	  <? } else {
      echo "#" . $gift['id'] . ": ";
if ($avg_tg != NULL) {
      ?>
	     <a href="<?= $link ?>"><strong><?=(AVG_NAME_PREFIX.$avg_tg->displayName)?></strong></a>
      <? 
} else {
      ?>
	     <a href="<?= $link ?>"><strong><?= $html['gift_name'] ?>  for $<?= $html['gift_cost'] ?></strong></a>
      <? 	
}
	    } 
	  ?>
<? if ($avg_tg == NULL) { ?>
	  <span>(<? if ((int)$gift['txtGiftReceived'] > 0) echo $gift['txtGiftReceived'] . ' received, ' ?>need <?= $html['gift_quantity'] ?>)</span>
<? } ?>
      <? if ($html['campaign'] != null && $html['campaign'] != "") {?><i style="margin-left: 10px; color:#808080; font-size: 10pt;">campaign: <?= $html['campaign'] ?></i><? } ?>
	  <!--<i style="margin-left: 10px; color:#808080; font-size: 10pt;"><?= $html['gift_tags'] ?></i>-->
	  <div style="margin-left: 5px; margin-top: 2px;"><?= $html['gift_excerpt'] ?>
      <span style="font-size:9pt;">
      <? if ($gift['post_id'] > 0) { ?>
        (<a href="/wp-admin/post.php?post=<?=$gift['post_id']?>&action=edit" target="_edit">edit</a>)
      <? } ?>
      <? if (!empty($html['gift_link_href'])) { ?>
	      more: <a href="<?= $html['gift_link_href'] ?>"><?= $html['gift_link_text'] ?></a>
      <? } ?>
      </span>
    </div>
	</div>
	<?
} //for
?>
<? if ($list->canAdd == true) { ?>
  <p class="submit">
    <input type="hidden" name="gift_id" value="new" />
    <input type="hidden" name="action" value="new" />
    <input type="submit" name="Submit" value="<? _e('Add a Gift') ?>" class="button" />
  </p>
<? }//if ?>
</form>
</div>
