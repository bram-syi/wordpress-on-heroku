<?
/**********************************************************************/
// Author:      Unknown, Yosia Urip
// Description: This page handles notification from Standard PayPal, 
//              Payment Pro, Google Checkout, and Amazon
//              It parses and inputs the POST data from them into 
//              the donation database, also sends out relevant email 
//              notification to the donors and admins.
// Last Edited: February, March 2010
/**********************************************************************/

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

//If not a secure call,log this one now 
if(!function_exists('verifyTransaction')){
  logTransactions();
}

function logTransactions(){
  //Only PayPal IPN will call this page directly

  if(isset($_REQUEST['payment_status'])){
  logTransactions2($_REQUEST['payment_status'], 
  $_REQUEST['payer_email'], $_REQUEST['custom'], 
  $_REQUEST['mc_gross'], $_REQUEST['memo'], 
  $_REQUEST['first_name'], $_REQUEST['last_name'], 
  $_REQUEST['quantity1'], var_export($_REQUEST,true), 
  '', 'PP', false, $_REQUEST['txn_id']);
  }
}
 
function logTransactions2($payment_status, $payer_email, $custom, 
  $mc_gross, $memo, $first_name, $last_name, $quantity = 1, $variables = '', 
  $returnUrl = '', $paymentMethod = 'PP', $giftCardApplied = false, $txnID = '',
  $donorId = NULL){
  global $wpdb, $emailEngine;

//Note: $quantity only counts donation items, not the tip

//try{

$debug='';
$no_cart = false;

//echo get_bloginfo('url'); exit();

$debug.= '<pre>'."\n";

if(strpos($custom,'||')===FALSE){
  $debug.= "PAYMENT (encrypted) ".$custom."\n";
  $custom = intval(decrypt($custom));
  $debug.= "PAYMENT # ".$custom."\n";
  $passed_custom = $custom;
  $sql = $wpdb->prepare("SELECT cart FROM payment "
    . "WHERE id = %d AND txnID=''",$custom);
  $cart = $wpdb->get_var($sql);
  if($cart != NULL){
    $cart = unserialize($cart);
    $custom = $cart['custom'];
  } else {
    $no_cart = true;
  }
}

//Parse double-pipe separated custom values 
//Fields: tip amount, mail subscribe, gift id, 
//referral, original amount (if discounted by gift card)
//and the provider through which payment is processed 
list($tip, $notifyme, $giftid, 
  $referral, $discount, $code, 
  $mg, $recipientDonorId
  ) = preg_split('/\|\|/',$custom);

$paymentMethodID = intval(array_search($paymentMethod,
  array(0=>'',1=>'PP',2=>'CC',3=>'GG',4=>'AM',5=>'GC',6=>'MG',7=>'XC',8=>'SP')));

$debug.= 'METHOD='.$paymentMethod.'('.$paymentMethodID.')'."\n";
$debug.= 'SITE='.get_bloginfo('url')."\n";
$debug.= 'payment_status='.$payment_status."\n";
$debug.= 'payer_email='.$payer_email."\n";
$debug.= 'passed_custom='.$passed_custom."\n";    
$debug.= 'custom='.$custom."\n";    
$debug.= 'tip='.$tip."\n";
$debug.= 'notifyme='.$notifyme."\n";
$debug.= 'giftid='.$giftid."\n";
$debug.= 'referral='.$referral."\n";
$debug.= 'discount='.$discount."\n";
$debug.= 'code='.$code."\n";
$debug.= 'mg='.$mg."\n";
$debug.= 'recipientDonorId='.$recipientDonorId."\n";
$debug.= 'mc_gross='.$mc_gross."\n";
$debug.= 'memo='.$memo."\n";
$debug.= 'first_name='.$first_name."\n";
$debug.= 'last_name='.$last_name."\n"; 
$debug.= 'quantity='.$quantity."\n";
//$debug.= 'variables='.$variables."\n";
$debug.= '-------------------------------------------------'; 
$debug.= "\n";
$debug.= '</pre>';

if($no_cart){

  //No cart, insert data anyway
  $sql = $wpdb->prepare("INSERT "
    . "INTO payment(raw, amount, tip, discount, provider, dateTime, txnID) "
    . "VALUES('%s','%s','%s','%s',%d, NOW(), '%s')", 
    $variables, $mc_gross, $tip, $discount, $paymentMethodID, $txnID);

  $wpdb->query($sql) or debug($sql . "\n" . mysql_error(), true);
  $paymentId = intval($wpdb->insert_id);

  //Check for txnID duplicate
  $sql = $wpdb->prepare("SELECT txnID FROM payment WHERE txnID = '%s' AND id <> '%d'",
  $txnID, $paymentId);
  $duplicate = $wpdb->get_var($sql) or debug($sql . "\n" . mysql_error(), true);

  if($duplicate != NULL){
    debug($debug."\n FOUND DUPLICATE payment with TXN #".$txnID." #".$paymentId." STOP PROCESSING",true); 
  }

  debug($debug."\n PURCHASE: CART NOT FOUND with TXN #".$txnID." #".$paymentId." STOP PROCESSING",true);  
  exit();
} else {
    //Cart found, updating payment row
  $paymentId = $passed_custom;
  $sql = $wpdb->prepare(
    "UPDATE payment SET raw = '%s', amount = '%s', tip = '%s', discount = '%s', "
    . "provider = %d, dateTime = NOW(), txnID = '%s' "
    . "WHERE id = '%d' ", 
    $variables, $mc_gross, $tip, $discount, $paymentMethodID, $txnID, $paymentId);
  $wpdb->query($sql) or debug($sql . "\n" . mysql_error(), true);
  $debug.= "\n PURCHASE: CART FOUND, UPDATING PAYMENT #".$paymentId;
}


  $quantity = intval('0'.$quantity);
  if($quantity < 1) $quantity=1;
  $tip = floatval('0'.$tip);
    $discount = floatval('0'.$discount);
  if($notifyme!='0') $notifyme=1;
  $net_donation = $mc_gross - $tip + $discount;

  $donation_gift_ids = array();
  $matched_gift_ids = array();
//-----------------------------------------------------------------------------------------
    
  $validated = 0;

  //If user is logged in when making donation
  if(isset($cart['wpid']) && intval($cart['wpid'])>0){
    $donorId = get_donorid_by_userid($cart['wpid']);
  }

  //If this payment is not from GC or CC (GG and PP) it is validated email
  if($paymentMethod != 'GC' && $paymentMethod != 'CC' && $donorId == NULL){
    $donorId = $wpdb->get_var($wpdb->prepare("SELECT ID "
    . "FROM donationGiver WHERE email = '%s' AND validated = 1", $payer_email));
    $validated = 1;
  }

    //If donor does not exist already then add to donor table
  if($donorId == NULL){
    $sql = $wpdb->prepare("INSERT "
      . "INTO donationGiver(email, sendUpdates, firstName, lastName, "
      . "donationOwner, referrer, validated) VALUES ('%s',%d,'%s','%s','1','%s',%d)",
    $payer_email, $notifyme, $first_name, $last_name, $referral, $validated);        
    $wpdb->query($sql) or debug($sql . "\n" . mysql_error(), true);
    $donorId = intval($wpdb->insert_id);

    $debug.= "\n PURCHASE: INSERTING DONOR #".$donorId.' VALIDATED: '.$validated;
  } 

//-----------------------------------------------------------------------------------------
if($giftid != 1) { ////////////////////////////////////////////////////////////////////////
//----------------------------------------------------------------------------------------- 
  //Get gift object
  $gift = $wpdb->get_row(
    $wpdb->prepare("SELECT * FROM gift WHERE id=%d",$giftid));
  $debug.= "\n DONATION PURCHASE: GETTING GIFT INFO #".$giftid;

  //Insert donation raw data
  $sql = $wpdb->prepare("INSERT INTO donation "
    . "(paymentID, donationDate, donorID, donationAmount_Total, notificationsSent, instructions, tip, notifications, test) "
    . "VALUES(%d, NOW(), %d, %d, 0, '%s', '%f', '%d', %d)",
    $paymentId, $donorId,
    $net_donation,
    $memo,
    $tip,
    $notifyMe,
    FALSE
    );

  $wpdb->query($sql) or debug($sql . "\n" . mysql_error(), true); 
  $donationId = intval($wpdb->insert_id);
  $debug.= "\n DONATION PURCHASE: INSERTING DONATION #".$donationId;
  
//----------------------------------------------------------------------------------------- 


  $unit_net_donation = $net_donation / $quantity;
  $unit_tip = $tip / $quantity;   

  //Create donationGifts
  $itemIDs = array();

  for($i=0; $i < $quantity; $i++){    

    $sql = $wpdb->prepare("INSERT INTO donationGifts "
      . "(donationID, giftID, unitsDonated, amount, blog_id, "
      . "towards_gift_id, "
      . "campaign,distributionStatus,fundTransferStatus,tip) "
      . "VALUES (%d,%d,1,%f,%d,%d,%s,1,1,%f) ",
      $donationId,$giftid,$unit_net_donation,$gift->blog_id,
      $gift->towards_gift_id,$gift->campaign,$unit_tip);
    $wpdb->query($sql) or debug($sql . "\n" . mysql_error(), true);
    $itemIDs[] = $wpdb->insert_id;

    //collecting donation gift id
        array_push($donation_gift_ids,intval($wpdb->insert_id));
    $debug.= "\n DONATION PURCHASE: STORING DONATION GIFT: #"
      . $wpdb->insert_id;
    
    //Increment gift units
    $sql = $wpdb->prepare("UPDATE gift " 
      . "SET unitsWanted = unitsWanted-1 " 
      . "WHERE id = %d",$giftid);
    $wpdb->query($sql) or debug($sql . "\n" . mysql_error(), true);

        if($gift->towards_gift_id > 0){
        $debug.= "\n DONATION PURCHASE: PROCESSING AGGREGATE CAMPAIGN ";

      $sql="SELECT * FROM gift "
        . "WHERE id = '" . $gift->towards_gift_id . "'";
      $master_gift = $wpdb->get_row($sql);
      $newcur = $master_gift->current_amount + $unit_net_donation;
      if($newcur >= $master_gift->unitAmount){
        $newcur -= $master_gift->unitAmount;
        $sql = $wpdb->prepare("UPDATE gift "
          . "SET unitsDonated = unitsDonated+1, unitsWanted = unitsWanted-1 "
          . "WHERE id = %d",$gift->towards_gift_id);
        $wpdb->query($sql) or debug($sql . "\n" . mysql_error(), true);
        if($master_gift->campaign!=null) incrementCampaign($master_gift->campaign);
      }
      if($newcur < 0) $newcur = 0;
      $sql = $wpdb->prepare("UPDATE gift "
        . "SET current_amount = %d "
        . "WHERE id = %d",$newcur,$gift->towards_gift_id);      
      $wpdb->query($sql) or debug($sql . "\n" . mysql_error(), true);
    }else if($gift->campaign != null){
      incrementCampaign($gift->campaign);
    }

  }

//-----------------------------------------------------------------------------------------    
    $debug .= "\n DONATION PURCHASE: SENDING NOTIFICATION TO PAYER: ".$payer_email;

    //Send thank you email to donor
    $items = implode(',', $itemIDs);
    $emailEngine->sendMail($payer_email,$gift->blog_id,1,5,"$donationId/$items",0,null,'','',$moreMsg);

    $debug .= "\n DONATION PURCHASE: SENDING NOTIFICATION TO ADMIN ";
	
    //Send donation notification to administrator(s)	
    $admin = get_blog_option($blogid,'admin_email');
    $admins = getUsersByRoleByBlogId('administrator',$gift->blog_id);
    $emailEngine->sendMail($admin,$gift->blog_id,3,7,"$donationId/$items",0,$admins);

//-----------------------------------------------------------------------------------------
    $debug .= "\n DONATION PURCHASE: COMPLETE ";
  
  
  
} else {///////////////////////////////////////////////////////////////////////////////////

//Recurring user account refill
if($recipientDonorId == null && ($paymentMethod=='SP' || $paymentMethod=='RE') && $giftid == 1){
  //Get donor account
  $da = get_donation_acct_by_donorid($donorId);
  //debug('I ('.$donorId.') am here too: '.print_r($da,true),true);
  if($da == null){
    //account not found, create a new one
    create_user_donation_account(0,$donorId);
    $da = get_donation_acct_by_donorid($donorId);
  }

  if($da != null){
    $debug .= "\n RECURRING: DONOR #".$donorId." ACCT #".$da->id
    ." ".($net_donation>0?'REFILLED':'REFUNDED')." USING SPREEDLY";

    insert_donation_acct_trans($da->id, $mc_gross, $paymentId,
      'Automated recurring donation using Spreedly');

    $purchaser = $wpdb->get_row("SELECT CONCAT(firstName,' ',lastName) as name, email "
    . "FROM donationGiver WHERE ID='".$donorId."'");

    $debug .= "\n RECURRING: SENDING NOTIFICATION TO ".$purchaser->email;

    $emailEngine->sendMailSimple($purchaser->name, $purchaser->email,
      'Thank you for your continuous support at SeeYourImpact.org.',
      array(
      '#purchaser_name#' => as_html($purchaser->name),
      '#amount#' => as_money($net_donation)),
      ($net_donation>0?'recurring.html':'recurring_refund.html'));

      $admins = getUsersByRoleByBlogId('administrator',1);
    foreach($admins as $userid){
      $user_object = new WP_User($userid);
      $debug .= "\n RECURRING: SENDING NOTIFICATION TO ".$user_object->user_email;
      $emailEngine->sendMailSimple('Administrator', $user_object->user_email,
      'Donation Account Refilled (Recurring)',
      '<br/>'.as_html($purchaser->name).' ('.$purchaser->email.') added '
      . as_money($net_donation). " to donationAcct #".$da->code." ",
      'syi.html');
    }

  }
//Regular GC purchase
}else{

    $rDonorId = $wpdb->get_var("SELECT ID FROM donationGiver "
    . "WHERE ID = '".$recipientDonorId."'");

    if($rDonorId != NULL && $rDonorId == $recipientDonorId){
    $debug.= "\n GC PURCHASE: RECIPIENT #" . $rDonorId . " FOUND ";
    //Create new donationAccount, type 3 is GC
    //$net_donation here is the full amount of GC
    $donationAcctId = insert_donation_acct($rDonorId, $net_donation, 3, $paymentId, $rDonorId, $donorId);
          $newCode = $wpdb->get_var("SELECT code FROM donationAcct WHERE id='".$donationAcctId."'");    
    $debug.= "\n GC PURCHASE: INSERTING ACCT #".$donationAcctId
      . " $".$net_donation." CODE #".$newCode;
    
    $debug.= "\n GC PURCHASE: SENDING GC NOTIFICATIONS FOR ".$donationAcctId ;

    //Sending email notifications
      $recipient = $wpdb->get_row("SELECT CONCAT(firstName,' ',lastName) as name, email, notes "
      . "FROM donationGiver WHERE ID='".$rDonorId."'");

    $purchaser = $wpdb->get_row("SELECT CONCAT(firstName,' ',lastName) as name, email "
    . "FROM donationGiver WHERE ID='".$donorId."'");

    $debug.= "\n GC PURCHASE: SENDING GC NOTIFICATION TO RECIPIENT: " . $recipient->email
     . ' MESSAGE: '.$recipient->notes;    

    $msg = empty($recipient->notes) ? '' : '<div style="margin: 5px 0 12px 32px;">"<strong>' 
      . nl2br(as_html($recipient->notes)) . '</strong>"</div>';

    $emailEngine->sendMailSimple($recipient->name, $recipient->email, 
      ($recipient->name!=''?$purchaser->name.' sent you a SeeYourImpact gift certificate!':
    'You have received a SeeYourImpact.org gift certificate!'), 
      array('#recipient_name#' => as_html($recipient->name),
      '#recipient_email#' => as_html($recipient->email),
      '#purchaser_name#' => as_html($purchaser->name),
      '#purchaser_email#' => as_html($purchaser->email),
      '#message#' => $msg,
      '#amount#' => as_money($net_donation),'#code#' => as_html($newCode)), 'giftcert.html');

    $debug.= "\n GC PURCHASE: SENDING GC NOTIFICATION TO PURCHASER: ".$purchaser->email;    

    if($notifyme==1){
    $emailEngine->sendMailSimple($purchaser->name, $purchaser->email, 
      'Thank you for sending a SeeYourImpact.org gift certificate!',
      array('#recipient_name#' => as_html($recipient->name),
      '#recipient_email#' => as_html($recipient->email),
      '#purchaser_name#' => as_html($purchaser->name),
      '#purchaser_email#' => as_html($purchaser->email),
      '#message#' => empty($recipient->notes) ? '' :
      'We have sent the certificate code with your message: ' . $msg, 
      '#amount#' => as_money($net_donation), '#code#' => as_html($newCode)), 'giftcert_purchaser.html');
    } else {
      $debug.= "\n GC PURCHASE: NOT SENT - PURCHASER OPTED OUT";
    }
    
      $admins=getUsersByRoleByBlogId('administrator',1);      
      foreach($admins as $userid){
        $user_object=new WP_User($userid);  
        $debug.= "\n GC PURCHASE: SENDING GC NOTIFICATION TO ".$user_object->user_email;
        $emailEngine->sendMailSimple('Administrator', $user_object->user_email, 
        'Gift Certificate Purchased',
      '<br/>'.as_html($purchaser->name).'('.$purchaser->email.') purchased '
      . as_money($net_donation). " gift certificate #$newCode for "
      .  $recipient->name.' ('.as_html($recipient->email) . ')' . $msg,
      'syi.html');
      }
    
  } else {
    $debug.= "\n GC PURCHASE: RECIPIENT #". $rDonorId ." NOT FOUND ";
  }

}//end check if Account Refill / GC

}//////////////////////////////////////////////////////////////////////////////////////////

//If there is a GC and it is not applied yet
if(($code != '0') && ($discount > 0) && !$giftCardApplied){
  //Get the gift ids
  if(count($donation_gift_ids)>1) {
  $donation_gift_ids = implode(', ',$donation_gift_ids);
  } else {
  $donation_gift_ids = $donation_gift_ids[0];
  }

  if(count($matched_gift_ids)>1) {
  $matched_gift_ids = implode(', ',$matched_gift_ids);
  } else {
  $matched_gift_ids = $matched_gift_ids[0];
  }
  
  $daTransNote = 'Donation #'.$donationId
  . ' ('.$donation_gift_ids.') ' 
  . ' (Matched by: '.$matched_gift_ids.') '
  . ' on '.date('Y-m-d H:i:s');
  
  //Deduct from account
  insert_donation_acct_trans(get_acct_id_by_code(addslashes($code)), 
  (-1 * $discount), $donationId, $daTransNote, true);   
  
  $debug.= "\n PURCHASE: APPLYING GC #".$code." DEDUCTING $" . $discount;
} else if($code != '0') {
  $debug.= "\n PURCHASE: GC #".$code." ALREADY APPLIED OR DISCOUNT <= 0 ";  
}

//-----------------------------------------------------------------------------------------
    //SEND ALL THE JUNK!
  $debug.= "\n PURCHASE: DONE, RETURNING TO: " . $returnUrl;  
  debug($debug,true, "Payment #".$paymentId);

  //If there is a return URL, redirect it 
  if($returnUrl!=''){
    if(headers_sent()){
      $parsedReturnUrl = parse_url($returnUrl);
      $qvars = explode("&",$parsedReturnUrl['query']);
      $qvars2 = array();
      foreach($qvars as $k=>$v){
        $var = explode("=",$v);
        $qvars2[$var[0]] = $var[1];
      }
      $cm = $qvars2['cm'];
      $pm = $paymentMethod;

      build_thankyou_page();
    } else {
      header('Location: ' . $returnUrl
       . '&pm=' . $paymentMethod . '&payer_email=' . $payer_email);exit();
    }
  } else { return $donationId; }
//-----------------------------------------------------------------------------------------

//} catch(Exception $e) {
  //debug($debug,true);
  //debug(var_dump($e->getMessage()),true);
//}

}

?>
