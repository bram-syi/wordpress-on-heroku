<?
/**********************************************************************/
// Author:      Yosia Urip
// Description: PayPal Express Checkout
// Last Edited: Oct 2010
/**********************************************************************/

global $siteinfo;
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/payments/paypal/phpPayPal.php');
//include_once($_SERVER['DOCUMENT_ROOT'].'/payments/logTransaction.php');

$paypal=new phpPayPal();
if (is_cart_test()) { $paypal->switch_payment_mode(false); }

$paypal->version = "63.0";

if(!isset($_REQUEST['token']) || !isset($_REQUEST['PayerID'])){

////////////////////////////////////////////////////////////////////////////////
//NEW CHECKOUT

$cartID = get_cart(); //$_REQUEST['cid']);
$rows = cart_to_array($cartID);
$items = array();
$discounts = array();
$tip = 0;

$paypal->amount_handling=0;
$paypal->amount_shipping=0;
$paypal->amount_tax=0;

foreach ($rows as $row) {
  $qty = absint($row['quantity']);
  if ($qty == 0)
    continue;

  $price = floatval($row['price']);
  switch ($row['giftID']) {
    case CART_BUY_GC:
      $items[] = array('name'=>CART_BUY_GC_DESCRIPTION,
        'desc'=>"", 'quantity'=>$qty, 'amount'=>$price);
      break;
    case CART_USE_GC:
      $discounts[] = array('name'=>CART_USE_GC_DESCRIPTION,
        'desc'=>'#'.$row['ref'], 'quantity'=>1, 'amount'=>$price
        );
      break;
    case CART_TIP:
      $tip += $qty * $price;
      break;
    default:
      $items[] = array('name'=>$row['displayName'],
        'desc'=>"", 'quantity'=>$qty, 'amount'=>$price);
      break;
  }
}

$paypal->amount_total = 0;

// First items, then tip, then discounts
if ($tip > 0) {
  $items[] = array('name'=>CART_TIP_DESCRIPTION,
    'desc'=>"",'quantity'=>1,'amount'=>$tip);
}
foreach ($items as $item) {
  $paypal->addItem($item['name'],$item['desc'],$item['quantity'],0,
    $item['amount']);
  $paypal->amount_total += $item['quantity'] * $item['amount'];
}
foreach ($discounts as $item) {
  $paypal->addItem($item['name'],$item['desc'],$item['quantity'],0,
    $item['amount']);
  $paypal->amount_total += $item['quantity'] * $item['amount'];
}

  $paypal->custom = urlencode($cartID);
  $paypal->return_url = get_thankyou_url($cartID);
    /*'http://'.$_SERVER['SERVER_NAME']
    .'/payments/payPaypal.php?'
    .(strpos(CREDIT_API_URL,'sandbox')===FALSE?'':'sandbox');*/
	$paypal->cancel_url = get_cart_cancel_url($cartID);

  $paypal->landing_page = 'Billing';
  $paypal->solution_type = 'Sole';
  $paypal->user_action = 'commit';
  $paypal->no_shipping = 1;


	//Credit Card Information (required)
	$paypal->credit_card_number = $cc_num;
	$paypal->credit_card_type = $cc_type;
	$paypal->cvv2_code = $cc_cvv;
	$paypal->expire_date = $cc_month . $cc_year;

	//Billing Details (required)
	$paypal->first_name = $first_name;
	$paypal->last_name = $last_name;
	$paypal->address1 = $address1;
	$paypal->address2 = $address2;
	$paypal->city = $city;
	$paypal->state = ($state!=''?$state:$state_other);
	$paypal->postal_code = $zip;
	$paypal->phone_number = $phone;
	$paypal->email = $email;
	$paypal->country_code = $country_code;
	$paypal->return_url = $notify_url;
	$paypal->cancel_url = $cancel_return;

	//validate value on server side
	if(floatval($paypal->amount_total) <= 0){$errorMessage .= 'Payment amount is wrong. ';}
	if($paypal->credit_card_number == ''){$errorMessage .= 'Missing credit card #. ';}
	if($paypal->credit_card_type == ''){$errorMessage .= 'Missing credit card type. ';}
	if($paypal->cvv2_code == 0){$errorMessage .= 'Missing email cvv.';}
	if($paypal->expire_date == ''){$errorMessage .= 'Missing expiration date. ';}
	if($paypal->email == ''){$errorMessage .= 'Missing email address. ';}

	if($errorMessage == ''){
		///echo'<pre>';print_r($paypal);echo'</pre>';exit();
		$paypal->do_direct_payment(); //Perform the payment
		$paypalResponse=$paypal->Response; //Get the response
	}


  //echo '<pre>-';print_r($paypal);echo '-</pre>';exit();
  //echo '<pre>';print_r($paypal->ItemsArray);echo '</pre>';exit();
  //echo $paypal->amount_total;

  if ($paypal->set_express_checkout()) {
    $paypal->set_express_checkout_successful_redirect();
  } else {
    //echo '<pre>-';print_r($paypal->Response);echo '-</pre>';exit();
  }

  exit();

////////////////////////////////////////////////////////////////////////////////
//RETURN FROM PAYPAL

} else if (isset($_REQUEST['token']) && isset($_REQUEST['PayerID'])) {

}

////////////////////////////////////////////////////////////////////////////////

?>
