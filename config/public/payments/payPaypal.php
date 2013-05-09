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
include_once($_SERVER['DOCUMENT_ROOT'].'/payments/payments.php');

$paypal=new phpPayPal();
if (!is_live_payments()) { $paypal->switch_payment_mode(false); }

//echo '<pre>';print_r($paypal);echo'</pre>'; exit();

$paypal->version = "63.0";

if (!isset($_REQUEST['token']) || !isset($_REQUEST['PayerID'])) {

////////////////////////////////////////////////////////////////////////////////
//NEW CHECKOUT

  $cartID = get_cart(false); //$_REQUEST['cid']);
  if ($cartID == NULL) exit();
  $paypal = cart_to_paypal($cartID,$paypal);

  //echo '<pre>-';print_r($paypal);echo '-</pre>';exit();

  if($paypal->set_express_checkout()){
    //echo '<pre>-';print_r($paypal->Response);echo '-</pre>';exit();
    //echo '<pre>-';print_r($paypal);echo '-</pre>';exit();
    dc($cartID,'PP payment redirected to paypal '
      .print_r($paypal->Response,true));
    $paypal->set_express_checkout_successful_redirect();
  } else {
    dc($cartID,'PP payment error getting redirect '
      .print_r($paypal->Response,true));
    //echo '<pre>-';print_r($paypal->Response);echo '-</pre>';exit();
  }

  exit();


} else if (isset($_REQUEST['token']) && isset($_REQUEST['PayerID'])) {

////////////////////////////////////////////////////////////////////////////////
//RETURN FROM PAYPAL

  $paypal->token = $_REQUEST['token'];
  $paypal->payer_id = $_REQUEST['PayerID'];
  //echo '<pre>-';print_r($_SERVER);echo '-</pre>';exit();

  $paypal->get_express_checkout_details();
	$paypalResponse=$paypal->Response; //Get the response

  //echo '<pre>-';print_r($paypal);echo '-</pre>';exit();
  $cartID = $paypal->Response['CUSTOM'];

  if($cartID != NULL){
    // Load the cart
    $cart = new stdClass;
    $cart->id = $cartID;
    get_cart_total($cart);

    $paypal = cart_to_paypal($cartID,$paypal);
    $paypal->do_express_checkout_payment(); //Perform the payment
    dc($cartID,'User returned from paypal with cartID '.$cartID
      .' processed XC payment: '."\n\n".print_r($paypal->Response,true));

    $result = process_paypal_payment($paypal, 'XC', $cart);
    if ($result->status == 'success')
      wp_redirect($result->data); // the thank you URL
    else
      wp_redirect(get_cart_url('cart_restored'));
    exit();
  } else {
    dc($cartID,'User returned from paypal but cartID is missing '
      ."\n\n".print_r($paypal->Response,true));

  }
}

////////////////////////////////////////////////////////////////////////////////
?>
