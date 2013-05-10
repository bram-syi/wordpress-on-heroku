<?php
require_once('../wp-load.php');
require_once('../wp-content/plugins/Gift-admin/giftcontrol.php');
 $list = new GiftList();
 $list->blogId = 12;
 $list->getActiveGifts();
$return_url="http://".$_SERVER['HTTP_HOST']."/".RETURN_URL;
$cancel_return_url="http://".$_SERVER['HTTP_HOST']."/".CANCEL_RETURN_URL;
$notify_url="http://".$_SERVER['HTTP_HOST']."/".NOTIFY_URL;
echo "<strong>";
_e("Current paypal mode is ".sw_get_paypal_mode()." as per setting");
echo "</strong>";
?>

<script language="JavaScript" type="text/javascript">
function checkForm(amount,name,id) 
{
  document.getElementById('item_price_1').value= parseFloat(amount);
  document.getElementById('item_name_1').value= id;
  document.getElementById('item_description_1').value= name;
  return true;
}
</script>

<form action="https://sandbox.google.com/checkout/cws/v2/Donations/760640449735439/checkoutForm" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm">
    <input name="item_name_1" id="item_name_1" type="hidden" value=""/>
    <input name="item_description_1" id="item_description_1" type="hidden" value=""/>
    <input name="item_quantity_1"  type="hidden" value="1"/>
    <input name="merchant-item-id_1" id="merchant-item-id_1" type="hidden" value=""/>
    <input id="item_price_1" name="item_price_1" type="hidden" value=""/>
    <input name="item_currency_1" type="hidden" value="USD"/>
    <input name="item_is_modifiable_1" type="hidden" value="false"/>
    <input name="item_min_price_1" type="hidden" value="0.01"/>
    <input name="item_max_price_1" type="hidden" value="25000.0"/>
    <input name="_charset_" type="hidden" value="utf-8"/>
<strong>  
<?php
if(count($list->gifts) > 0)
_e('Here is the list of Gifts '); 
?>
</strong>
<br/>
<br/>
<?
for($i = 0; $i < count($list->gifts); $i++) {
  $html = $list->gift2html($list->gifts[$i]);
?>    
<input type="image" alt="Donate" onclick="return checkForm('<?= $html['gift_cost']?>','<?= $html['gift_name']?>','<?= $html['gift_id']?>')"  src="https://sandbox.google.com/checkout/buttons/donateNow.gif?merchant_id=760640449735439&amp;w=115&amp;h=50&amp;style=white&amp;variant=text&amp;loc=en_US" />
<div class="gift-details">
<strong><?= $html['gift_name'] ?></strong>
<span> - $<?= $html['gift_cost'] ?></span>
<div class="desc"><?= $html['gift_desc'] ?></div>
</div>
<?php
  } //for
?>
</form>
<br/>
<br/>
<strong>
<?php _e('Buy Now Button') ?>
</strong>

<form action="https://sandbox.google.com/checkout/api/checkout/v2/checkoutForm/Merchant/760640449735439" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm">
    <input name="item_name_1" type="hidden" value=""/>
       <input name="item_description_1" id="item_description_1" type="hidden" value=""/>
    <input name="merchant-item-id_1" id="merchant-item-id_1" type="hidden" value=""/>
    <input name="item_quantity_1" type="hidden" value="1"/>
    <input name="item_price_1" type="hidden" value="3.0"/>
    <input name="item_currency_1" type="hidden" value="USD"/>
    <input name="_charset_" type="hidden" value="utf-8"/>
<?
for($i = 0; $i < count($list->gifts); $i++) {
  $html = $list->gift2html($list->gifts[$i]);
?>      
<input type="image" alt="" onClick="return checkForm('<?= $html['gift_cost']?>','<?= $html['gift_name']?>','<?= $html['gift_id']?>')" src="https://sandbox.google.com/checkout/buttons/donateNow.gif?merchant_id=760640449735439&amp;w=115&amp;h=50&amp;style=white&amp;variant=text&amp;loc=en_US"/>
<div class="gift-details">
<strong><?= $html['gift_name'] ?></strong>
<span> - $<?= $html['gift_cost'] ?></span>
<div class="desc"><?= $html['gift_desc'] ?></div>
</div>
<?php
  } //for
?>
<br/>
<br/>
</form>




