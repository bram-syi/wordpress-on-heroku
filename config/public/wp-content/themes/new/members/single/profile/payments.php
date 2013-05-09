<?
global $bp;
$user = $bp->displayed_user;
$user_id = $user->id;

define('SHOW_CREDIT', false);

$sub = NULL;
$this_page = $_SERVER['SCRIPT_URI'];

$sub = get_monthly_subscriber($user);
if ($sub->cid > 0)
  $old_details = get_blog_details((int)$sub->cid, TRUE);

$for_charity = $_REQUEST['for'];
if ($for_charity > 0) {
  $details = get_blog_details((int)$for_charity, TRUE);
}

if (wp_verify_nonce($_REQUEST['action'], 'modify_amount')) {
  $new_amount = $_REQUEST["monthly_amount"];
  $new_tip = $_REQUEST["tip_amt"];
  if ($new_tip === NULL)
    $new_tip = $sub->recurring_tip;
  else
    $new_tip = doubleval($new_tip);

  if($new_amount != NULL) {
    $new_amount = from_money($new_amount);
    if (!is_numeric($new_amount)) {
      echo 'Please enter a valid donation amount.';
    } else { 
      $sub->cid = $_REQUEST['cid'];
      update_monthly_subscription($sub, $new_amount, $new_tip, $this_page);
      $old_details = get_blog_details((int)$sub->cid, TRUE);
    }
  }
}

if (SPREEDLY_ENABLED) { 
  $suggested_plans = array();
  if ($sub != NULL) {
    foreach(get_monthly_plans() as $k => $plan){
      if (!$plan->enabled)
        continue;

      if($sub->recurring_amount != $plan->price){
        $suggested_plans[] = "<option value='$plan->price'>" .as_money($plan->price) . "</option>";
      }
    }
  }
}

function enter_amount($sub) { 
  $amt = $sub->recurring_amount;
  if ($amt == 0)
    $amt = 10;

  if (SPREEDLY_ENABLED) { 
    ?><select name="monthly_amount" id="monthly_amount" style="font-size: 150%; font-weight:bold;">
      <option value="<?=$amt ?>" selected="selected"><?= as_money($amt) ?></option>
      <?= implode('', $suggested_plans) ?>
    </select> monthly<?
  } else { 
     
    ?><b style="font-size:20px;">$</b><input name="monthly_amount" id="monthly_amount" maxlength="4" size="4" type="text" value="<?= absint($amt) ?>" style="font-size:150%; font-weight: bold; width: 50px;"> monthly<?
  }
}


$da = get_donation_acct_by_userid($user_id, ACCT_TYPE_GIVE_ANY, 
  ($sub->state=='closed'?0.15:$sub->recurring_tip), 0, eor($sub->cid, 1));

if($_REQUEST['dump']=='yes' && current_user_can('level_10'))
  pre_dump($da);

$is_new = $sub->recurring_amount == 0;
$nonce = wp_create_nonce('modify_amount');
?>

<form action="<?= $this_page ?>" method="post" class="standard-form">
<input type="hidden" name="action" value="<?= $nonce ?>">
<? if ($is_new) {
  if ($sub->recurring_plan != NULL || $da->balance > 0) {
    ?><div class="focus">
    <? if ($sub->recurring_plan != NULL) { ?>
      <p>Your monthly contribution has ended.</p>
    <? }
    if ($da->balance > 0) { ?>
      <p class="account-balance">
        You have a <b><?= as_money($da->balance) ?> credit</b> remaining!
        You can apply it toward your next donation by using the code "<?= $da->code ?>" at checkout.
      </p>
    <? } ?>
    </div><?
  }
}
$is_new = $is_new || $for_charity > 0;
if ($is_new) {
  ?><p><?
  draw_promo_content("monthly-giving", 'h1', true);
  ?></p><?
} else { ?>
  <h1>You're a monthly donor - thanks!</h1>
<? } ?>

  <div class="fields" style="margin: 10px; border: 1px solid #ddd;">
    <? if (!$is_new) { ?>
      <div style="font-size: 90%; background: #ddd; padding: 2px; text-align: center; margin-bottom: 5px;">
      Your next payment: <?= date("M jS, Y", $sub->next_payment) ?>
      </div>
    <? } ?>
    <div style="padding: 10px;">
    <? enter_amount($sub); ?> 
    <? if ($is_new && $details != NULL) { ?>
      donation <select name="cid" style="font-size:1em;">
        <option selected="" value="<?=$for_charity?>">to <?= xml_entities($details->blogname) ?></option>
        <? if ($old_details != NULL && $sub->cid != $for_charity) { ?>
        <option value="<?=$sub->cid?>">to <?= xml_entities($old_details->blogname) ?></option>
        <? } ?>
        <option value="">wherever it's needed most</option>
      </select>
    <? } else if ($old_details != NULL) { ?>
      donation to <b><?= xml_entities($old_details->blogname) ?></b>
      <input type="hidden" name="cid" value="<?= $sub->cid ?>">
    <? } else { ?>
      <input type="hidden" name="cid" value="">
    <? } ?>
    <? if (!$is_new) { ?>
      <span style="float: right; font-size:80%; margin:15px 10px; display: block;">(<a class="confirm-ok link" alt="Press OK to end your recurring monthly billing, or Cancel to remain a monthly donor." style="color: #800;" href="<?=$this_page?>?action=<?=$nonce?>&monthly_amount=0">cancel</a>)</span>
    <? } ?>
    
    <div style="font-size: 90%; margin-top: 10px;">
      plus a <?= build_tip_rate_ddl($sub->recurring_tip,'tip_amt'); ?>
      contribution to SeeYourImpact.org
      <? if ($is_new || $sub->recurring_tip == 0) { ?>
        (please consider helping us cover our costs!)
      <? } ?>
    </div>
    <p class="submit" style="margin: 10px 0 0 0;">
    <? $label = $is_new ? "Sign me up!" : "Update"; ?>
    <input class="button orange-button medium-button" type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<?= $label ?>" />
    </p>
    </div>
  </div>

<?
  if (!$is_new) {
    if(SPREEDLY_ENABLED) {
      $provider_name = 'Spreedly, ';
    } else if (RECURLY_ENABLED) {
      $provider_name = 'Recurly, ';    
    }
?>  
  <p style="margin-top: 30px;"><em style="font-size:12px;">Need to <a class="link" target="_new" href="<?= get_monthly_billing_url($sub) ?>">change cards or billing address</a>? (You will be redirected to <strong><?=$provider_name?></strong> our 3rd-party billing partner)</em>
  </p>
<? } ?>

</form>
 
<? 

if (current_user_can('level_10')) { 
  if(SPREEDLY_ENABLED) {
    if(SPREEDLY_TEST) echo 'test';
  } else if (RECURLY_ENABLED) {
    if(RECURLY_TEST) echo 'test';
  }
  $daccts = get_user_donation_accts($user_id,ACCT_TYPE_GIVE_ANY);
  $current_recurring = get_user_meta($user_id,'current_recurring',true);
?><section class="entry-utility">
  Admin: allocation accounts for user #<?=$user_id?>  <strong>(refresh to update)</strong><br/><?
  $total = 0;
  foreach($daccts as $dacct) {
    if($dacct->balance==0) continue;
      $total += $dacct->balance;
    $params = json_decode($dacct->params);
    ?><div><a style="float:left;" href="/wp-admin/admin.php?page=donation-acct&accountID=<?=$dacct->id?>"><?= $dacct->code ?></a>
      <div style="padding-left: 120px;"><?= as_money($dacct->balance) ?> <?
    echo ' <span style="font-size:80%">(' . htmlspecialchars(show_acct_params($dacct->params)) . ')</span>';
    if ($current_recurring == $dacct->id) { 
      echo ' <strong>current</strong>';
    }
    ?></div><?
  }
  echo "Total: " . as_money($total).'<br/>';
?></section><?
} 
?>
