<?php
/*sandbox identity token:
-_9aS9bAS-EDcTVv82MLICy679oVucjoUfelf7JMplMGKXB2OvusPFDqkz8
*/
?>
<script language="JavaScript" type="text/javascript">
function submitGift(button) 
{
  document.getElementById(button.id + '-item').name = "gift";
  button.form.submit();
  return false;
}
</script>
<?php
global $blog_id;
$home_url = get_blogaddress_by_id(1);
$charity_url = get_blogaddress_by_id($blog_id);

$return_url = $home_url . "/" . RETURN_URL;
$cancel_return_url = $home_url . "/" . CANCEL_RETURN_URL;
$notify_url = $home_url . "/" . NOTIFY_URL;

$aggregates = array();
for($i = 0; $i < count($list->gifts); $i++) {
   $gift = $list->gifts[$i];
   
   if ($gift['towards_gift_id'] != 0) {
      $aggregates[] = $gift['towards_gift_id'];
   }
}
$aggregates = array_unique($aggregates);
for($i = 0; $i < count($aggregates); $i++) {
   $towards = $aggregates[(int)$i];
   $gift = $list->findGiftId($towards);
   if ($gift == null)
      continue;
   $gift = $list->gift2html($gift);
      
  $cost = $gift['gift_cost'];
  $progress = $gift['current_amount'];
  if ($progress < 0) $progress = 0;
  if ($progress > $cost) $progress = $cost;
  
?>
  <div class="gift_progress">
    Only <span class="remaining">$<?= $cost - $progress ?></span> more needed for <?= $gift['gift_name'] ?>!
    <div class="progress_outer">
      <div class="progress">
        <div class="progress_inner" style="width:<?= (int)(100 * $progress / $cost) ?>%"></div>
      </div>
    </div>
  </div>
<?php 
  //var_dump($gift);
}
?>
<!--
<form style="clear:both;" id="donations" class="proposals" action="<?=$charity_url?>payments/confirm.php" method="get">
-->
<ul>
<?php

$confirm_url = $charity_url.'payments/confirm.php';
$givebtn_url = $charity_url.'wp-content/themes/seeyourimpact/images/give-bg.gif';
$nonvars = 0;
for($i = 0; $i < count($list->gifts); $i++) {
  $gift = $list->gifts[$i];
  if ((int)$gift['txtGiftQuantity'] <= 0)
    continue;
  $html = $list->gift2html($gift);
?>
  <li>
    <!--<input type="hidden" id="b<?= $html['gift_id'] ?>-item" value="<?= $html['gift_id'] ?>" />onClick="submitGift(this, <?= $html['gift_id'] ?>);"  value="<?= $html['gift_id'] ?>"-->
    <a href="<?=$confirm_url?>?gift=<?= $html['gift_id'] ?>" style="color:#fff;font-size:18px;"><div id="b<?= $html['gift_id'] ?>" class="give-button" style="background:url(<?=$givebtn_url?>);"><div>
      <? 
      if ($html['var_amount'] > 0) {
        echo "NOW";
      } else {
        echo "$".$html['gift_cost'];
	$nonvars++;
      }
      ?>
    </div></div></a>
    <p style="font-size:14px;padding-right:20px;margin-left:115px;">
      <? if ($html['var_amount'] == 0) { ?>
        <a href="<?=$confirm_url?>?gift=<?= $html['gift_id'] ?>"><strong><?= $html['gift_name'] ?></strong></a> - 
      <? } ?>
      <?= $html['gift_desc'] ?>
      <?php if (!empty($html['gift_link_href'])) { ?>
        <a class="gift-description" href="<?= $html['gift_link_href'] ?>"><?= $html['gift_link_text'] ?> <u>click here</u></a>
      <?php } ?>
    </p>
    <br style="clear:both"/>
  </li>
<?php
  } //for
?>
</ul>
<?php if (empty($gifts)) { 
  $current_site = get_current_site();
?>
<div style="margin:15px 10px 0 20px; font-size:10pt;">
  <? if ($nonvars > 0) { ?>Multiple donations? You can specify a quantity after clicking "GIVE"<? } ?>
  <div class="redeem-cert">
  See more about: <a href="http://<?= $current_site->domain ?>/gifts/redeem"><u>redeeming gift certificates</u>, </a>
    <a href="http://<?= $current_site->domain ?>/gifts/matching"><u>corporate matching</u></a>
  </div>
</div>
<?php } else { ?>
 <p>or <a style="margin-top: 10px;" href="<?=$charity_url?>">see other gift options</a></p>
<?php } ?>
<!--</form>-->



