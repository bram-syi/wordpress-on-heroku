<style>
#giftdetails tr th{
    text-align:left; 
}
.alternate{
	background-color:#F0F0F0;
}
.disabled:hover{
	color:#999999;
	cursor:not-allowed;
}

.disabled{
	color:#999999;
}
</style>
<script language="JavaScript" type="text/javascript">
function postImpact() 
{
  document.forms['adminGift-Details'].action="/wp-admin/post-new.php";

  var arrayElements= document.getElementsByName('itemID[]');
  for (var i =0; i < arrayElements.length; i++) { 
    if (arrayElements[i].checked) return true;
  }

  alert("Please select one donation to proceed");
  return false;
}

checked=false;
function checkUncheckAll()
{
  checked = !checked;

  var arrayElements= document.getElementsByName('itemID[]');
  for (var i =0; i < arrayElements.length; i++) {
    arrayElements[i].checked = checked;
  }
}

</script>
<div class="wrap">
<?php
if ($giftDetails->giftListObj->status != null)
{
  print $giftDetails->giftListObj->status;
}
?>
<h2><a href="admin.php?page=donations"><?php _e('Received Donations') ?></a><?php _e(' > ' ) ?><?php _e('Search Results' ) ?></h2>
<br/>
<?php
if ($giftDetails->selectAll != "All" && count($giftDetails->filterStatus) > 0) { 
   _e('Showing donations where:');
   echo "<br/>";
   foreach ($giftDetails->filterStatus as $filtered) {
      ?><div style="padding:2px 5px;"><?= $filtered; ?></div><?		
   }
}
?>

<?php 
if($giftDetails==null){
  _e('Gift details not available');
} else {
?>
<br/>

<form name="adminGift-Details" id="adminGift-Details" method="post" > <!-- id="received-gifts" -->
<div class="tablenav">
<div class="alignleft actions">
<input type="submit" class="button-secondary action"  name="newPost" onclick="return postImpact()" value="Post Impact"/>	
<select id="actionStatus" name="actionStatus"><option value="">Update status...</option>
<? foreach($giftDetails->dbStatusValues as $statusArray) {?>
	<option value="<?= $statusArray[1] ?>|<?= $statusArray[0] ?>"><?= $statusArray[0] ?></option>
<? } ?>	
</select>
<input type="submit" class="button-secondary" name="order" value="Apply"/>
<input type="hidden" value="<?= $giftDetails->id ?>" id="gift_id" name="gift_id" />
</div>
<br class="clear"/>
</div>
<br class="clear"/>
<?php
    if($giftDetails->donatedBy ==null || count($giftDetails->donatedBy)==0)
        _e('Donation details not available for the selected criteria');
else{ 
?>

<table class="widefat">
  <thead>
    <tr>
      <th scope="col" class="check-column"><input type="checkbox" id="selectAll" onclick="checkUncheckAll()" name="selectAll"></th>
      <th scope="col" width="100%">Donation info</th>
      <th scope="col">Date</th>
    </tr>
  </thead>
  <tbody>
<?php
	//Paginate the table
	$size=count($giftDetails->donatedBy);
	$pageSize=50;
	$page=1;
	$lastPage=ceil($size/$pageSize);
	
	if(isset($_REQUEST['paginate']) && intval($_REQUEST['paginate'])!=0)
		$page=intval($_REQUEST['paginate']);
	
	if($page > $lastPage)
		$page = $lastPage;
		
	$alt = true;

	for($index=($page-1)*$pageSize;$index < min(($page*$pageSize),$size);){
		$donation=$giftDetails->donatedBy[$index];
		$did = $donation['donationID'];
?>
      <tr class="<?php if ($alt) echo "alternate"; $alt = !$alt; ?>">
        <td colspan="3" title="<?= $donation['title'] ?>">
        <div style="font-size: 11pt;">
<? if ($did == 0) { ?>
  <span style="color:red;">Deleted donation</span>
<? } else { ?>
          <span style="float:right;"><?= $donation['date']?> (<a href="<?php echo $_SERVER['PHP_SELF'].'?page=donations&donationId='.$donation['donationID'] ?>"><?php _e('history');?></a>)</span>
          #<?=$donation['donationID']?>: <strong>$<?= $donation['amount']?></strong>
          from <?php if($donation['title']!= null && $donation['title']!= 'None') echo $donation['donor']." (note)"; else echo $donation['donor'];
	             if ($donation['tip']!= 0) echo " tip: \$" . $donation['tip'] ;
		     if (!empty($donation['referrer']) && $donation['referrer'] != 'NULL') echo '<font size="-1"> referral: ' . $donation['referrer'] . "</font>";?>
<? } ?>
        </div>
         <?php 
$total = 0; 
	   while ($donation['donationID'] == $did) {
	 ?>
	  <div style="clear:both;">
	  <div style="float:left; padding: 2px 5px 3px 10px;">
	  <input type="checkbox" name="itemID[]" value="<?= $donation['item_id'] ?>" />
	  </div>
        <div style="font-size: 10pt; color: #606060; margin-top: 5px; margin-left: 25px;">
          #<?= $donation['item_id']?>:
	  <b><?= stripslashes($donation['name']) ?></b> - 
	  <?php
	  $stat = array();
    $stat[] = as_money($donation['price']);
$total += $donation['price'];
	  //$stat[] = "payment " . $donation['transfer_status'];
	  //$stat[] = "gift ". $donation['distribution_status'];
    global $blog_id;
 
    if ($blog_id != 1)  {
      if (!empty($donation['story']))
        $stat[] = "story " . $donation['story'];
    } else {
      if(floatval($donation['unit_tip'])>0){
        $stat[] = 'tip: '.as_money(floatval($donation['unit_tip']));
      }
    }
    if(intval($donation['matched'])>0){
	    $stat[] = '<strong>matched</strong>';
	  }
    echo implode(", ", $stat);

    if (($donation['rawDate'] < strtotime("-10 days")) && (strpos($donation['story'], 'published') !== 0)) {
echo ' <span style="color:red;">publish<span>';
}
	  ?>
        </div></div>
	  <?php 
$amount = $donation['amount'];
             $index++;
       if ($index == $size)
         break;
	     $donation=$giftDetails->donatedBy[$index];
	   }
     if ($total < $amount) {
?>
        <div style="font-size: 10pt; color: #606060; margin-top: 5px; margin-left: 25px;">
+ <?= as_money($amount - $total) ?> to other charities
        </div>
<?
     }

 ?>
        </td>
      </tr>
<?php }
?>
</tbody></table><br />
</form>
<?php
$url = $_SERVER['REQUEST_URI'];//'http://deepalaya.dev1.seeyourimpact.com/wp-admin/admin.php?page=donations&pageName=details&money=1&paginate=3';
$parsed=parse_url($url);

$args=explode('&',$parsed['query']);
$query='';
foreach($args as $pair):
	$arg=explode('=',$pair);
	if($arg[0]!='paginate')
		$query.=$arg[0].'='.$arg[1].'&';
endforeach;
$query=$_SERVER['PHP_SELF'].'?'.$query;
?>
<input type="button" <?php if($page==1): ?> class="button-secondary delete disabled" disabled="true" <?php else:?>class="button-secondary delete " <?php endif;?>  onclick="location.href='<?php echo $query.'paginate=1';?>'" value="<< First"></input>
<input type="button" <?php if($page==1): ?> class="button-secondary delete disabled" disabled="true" <?php else:?>class="button-secondary delete" <?php endif;?>  onclick="location.href='<?php echo $query.'paginate='.($page-1);?>'" value="< Previous"></input>
<input type="button" <?php if($page == $lastPage): ?> class="button-secondary delete disabled" disabled="true" <?php else:?>class="button-secondary delete" <?php endif;?>  onclick="location.href='<?php echo $query.'paginate='.($page+1);?>'" value="Next >"></input> 
<input type="button" <?php if($page == $lastPage): ?> class="button-secondary delete disabled" disabled="true" <?php else:?>class="button-secondary delete" <?php endif;?>   onclick="location.href='<?php echo $query.'paginate='.$lastPage;?>'" value="Last >>"></input>
<?php
	}
}
?>
<br/><br/>
</div>
