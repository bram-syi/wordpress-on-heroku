<?
/**********************************************************************/
// Author:      Google, Yosia Urip
// Description: Process checkout using Google Checkout API and 
//              wrapper library. The process involve these steps: 
//              library initiate API to send checkout data in an 
//              XML envelope, and then receive the response of URL 
//              redirection to redirect user to the proper cart page 
//              on Google checkout. The user then can proceed with 
//              the checkout process.
//
// Last Edited: February, March 2010
/**********************************************************************/

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

chdir('google');
require_once('library/googlecart.php');
require_once('library/googleitem.php');
require_once('library/googleresponse.php');
require_once('library/googlemerchantcalculations.php');
require_once('library/googleresult.php');
require_once('library/googlerequest.php');

define('GOOGLE_RETURN_INSTRUCTION','Please click here to return to SeeYourImpact<br/>');

$domain = "http://" . $_SERVER['HTTP_HOST']."/";
$cancel_return = $domain . GOOGLE_CANCEL_RETURN_URL;
$notify_url = $domain . GOOGLE_NOTIFY_URL;
//$return_url = $domain . GOOGLE_RETURN_URL;

//Get all POST variables
foreach($_POST as $k=>$v){ 
  if($k=='amount_1' || $k=='amount_2'){
    $$k=floatval($v); 
  }else if($k=='quantity_1' || $k=='quantity_2'){
    $$k=intval($v); 
  }else if($k == 'discount_amount_cart'){
    $$k=floatval($v); 
  }else if($k=='code'){
    $$k=stripslashes(str_replace(array("-"," "),"",$v)); 
  }else{
    $$k=stripslashes($v); 
  }
}

//Store cart to paymentTable, append paymentID to thank-you URL
$paymentID = store_cart($_POST);
$custom = $paymentID;

if(strpos($return,'?cm=')!==FALSE){
  $return = substr($return,0,strpos($return,'?cm=')).'?cm='.urlencode($paymentID);
}else{
  $return = $return.'?cm='.urlencode($paymentID);
}


//GoogleCart instance
$cart=new GoogleCart(GOOGLE_MERCHANT_ID,GOOGLE_MERCHANT_KEY, GOOGLE_SERVER_MODE, GOOGLE_CURRENCY, GOOGLE_API_URL);

$total_count = 0;

if($amount_2 > 0){$quantity_2 = 1;} else {$quantity_2 = 0;}

//Donation
if($quantity_1 * $amount_1 > 0){
  $total_count++;
  $item1=new GoogleItem($item_name_1,"",$quantity_1,$amount_1);
  $item1->SetURLDigitalContent('','', "Thank you for your order!<br /><a href='$return&amp;pm=GG'>" . GOOGLE_RETURN_INSTRUCTION . "</a>");
  $cart->AddItem($item1);
}

//Tip
if($quantity_2 * $amount_2 > 0){
  $total_count++;
  $item2=new GoogleItem($item_name_2,"",$quantity_2,$amount_2);  
  $cart->AddItem($item2);
}

//GC Discount
if(isset($discount_amount_cart)){
  $total_count++;
  $item3=new GoogleItem('SYI Gift Certificate Discount',
    "",1,-1 * $discount_amount_cart);  
  $cart->AddItem($item3);
}

$cart->SetEditCartUrl($cancel_return_url);
//$cart->SetContinueShoppingUrl($return);
$cart->SetRequestBuyerPhone(false);
$cart->SetMerchantPrivateData($custom);

if(isset($_POST['analyticsdata']) && !empty($_POST['analyticsdata'])){
  $cart->SetAnalyticsData($_POST['analyticsdata']);
}

// This will do a server-2-server cart post and send an HTTP 302 redirect status
list($status,$error)=$cart->CheckoutServer2Server();
// if i reach this point, something was wrong
echo "An error had ocurred. Please contact support@seeyourimpact.org for assistance."; exit();

?>
