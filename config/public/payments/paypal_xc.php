<?
/**********************************************************************/
// Author:      Yosia Urip
// Description: PayPal Express Checkout
// Last Edited: July 2010
/**********************************************************************/

//echo '<pre>-'; print_r($_REQUEST);echo '-</pre>';exit();

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

//if(!isset($_POST['gift']) && !isset($_REQUEST['token'])
//){header("Location: ".get_bloginfo('url'));exit();}

//if(!$_SERVER['HTTPS']
//  && (strpos(get_bloginfo('url'),'seeyourimpact.org') !== FALSE)){
  //header("Location: ".$bp->root_domain); exit();
//}

function verifyTransaction(){}

//Get all POST variables

foreach($_POST as $k=>$v){
  if($k == 'amount_1' || $k == 'amount_2'){
    $$k=floatval($v);
  }else if($k == 'quantity_1' || $k == 'quantity_2'){
    $$k=intval($v);
  }else if($k == 'discount_amount_cart'){
    $$k=floatval($v);
  }else if($k == 'code'){
    $$k=stripslashes(str_replace(array("-"," "),"",$v));
  }else{
    $$k=stripslashes($v);
  }
}

include_once($_SERVER['DOCUMENT_ROOT'].'/payments/paypal/phpPayPal.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/payments/logTransaction.php');

$paypal=new phpPayPal();
$paypal->version = "63.0";

//Store cart to paymentTable, append paymentID to thank-you URL
if(!isset($_REQUEST['token']) || !isset($_REQUEST['PayerID'])){

  $cart = $_POST;
  if(strpos($return,'?cm=')!==FALSE){
	  $return = substr($return,0,strpos($return,'?cm=')).'?cm=';
  }else{
	  $return = $return.'?cm=';
  }
  $cart['return'] = $return;

  //.urlencode($paymentID)
  $custom = store_cart($cart);

	$paypal->amount_handling=0;
	$paypal->amount_shipping=0;

  $paypal->amount_total = 0;
	$paypal->amount_total += $amount_1 * $quantity_1;
  $paypal->addItem($item_name_1, "", $quantity_1, 0, $amount_1);

  //tip
	if($amount_2 > 0 && $quantity_2 > 0){
    $paypal->addItem($item_name_2, "", $quantity_2, 0, $amount_2);
    $paypal->amount_total += $quantity_2 * $amount_2;
  }
	//discount
	if($discount_amount_cart > 0){
    $paypal->addItem('Gift Certificate', "", 1, 0, -1*$discount_amount_cart);
	  $paypal->amount_total -= $discount_amount_cart;
	}

  $paypal->amount_tax=0;

  $paypal->custom = urlencode($custom);
  $paypal->return_url = 'http://'.$_SERVER['SERVER_NAME']
    .'/payments/paypal_xc.php?'
    .(strpos(CREDIT_API_URL,'sandbox')===FALSE?'':'sandbox');
	$paypal->cancel_url = 'http://'.$_SERVER['SERVER_NAME'];

  $paypal->landing_page = 'Billing';
  $paypal->solution_type = 'Sole';
  $paypal->user_action = 'commit';
  $paypal->no_shipping = 1;

  //echo '<pre>-';print_r($paypal);echo '-</pre>';exit();
  //echo '<pre>';print_r($paypal->ItemsArray);echo '</pre>';exit();

  if($paypal->set_express_checkout()){
    $paypal->set_express_checkout_successful_redirect();
  }

}else if(isset($_REQUEST['token']) && isset($_REQUEST['PayerID'])){

//Return from paypal

  $paypal->token = $_REQUEST['token'];
  $paypal->payer_id = $_REQUEST['PayerID'];

  //echo '<pre>-';print_r($_SERVER);echo '-</pre>';exit();

  $paypal->get_express_checkout_details();
	$paypalResponse=$paypal->Response; //Get the response

  //echo '<pre>-';print_r($paypal);echo '-</pre>';exit();
  
  $custom = decrypt($paypal->Response['CUSTOM']);

  //echo $custom; exit();

  $cart = $wpdb->get_var(
    $wpdb->prepare("SELECT cart FROM payment "
  . "WHERE id = %d AND txnID=''",intval($custom)));

  if($cart != NULL){
    $cart = unserialize($cart);

    //echo '<pre>-';print_r($cart);echo '-</pre>';exit();

    list($tip, $notifyme, $giftid,
    $referral, $discount, $code,
    $mg, $recipientDonorId
    ) = preg_split('/\|\|/',$cart['custom']);


    $subtotal = $cart['amount_1'] * $cart['quantity_1'];
    $total = $subtotal + $cart['amount_2'];
    $total = $total - $discount;

    $paypal->amount_total = $total;
    $paypal->do_express_checkout_payment(); //Perform the payment
	  $paypalResponse=$paypal->Response; //Get the response

    //echo '<pre>-';print_r($paypal->Response);echo '-</pre>';//exit();

///

	if(true || $paypalResponse['ACK'] == 'Success'
	  || $paypalResponse['ACK'] == 'SuccessWithWarning'){//If payment successful

		$paypal->transaction_id=$paypalResponse['TRANSACTIONID'];
		$paypal->get_transaction_details();	//Grab the transaction details

    //echo '<pre>-';print_r($paypal->Response);echo '-</pre>';//exit();


    if($paypalResponse['ACK'] == 'Success'
		  || $paypalResponse['ACK'] == 'SuccessWithWarning'){	//If the details acquired

			$paypalResponse=$paypal->Response;



			//Combine all data, make it similar format as regular PayPal payment
			$paymentData =
			array (
			'mc_gross'=>$paypalResponse['AMT'],
			'protection_eligibility'=>'',
			'address_status'=>'',
			'item_number1'=>'',
			'tax'=>$paypalResponse['TAXAMT'],
			'item_number2'=>'',
			'payer_id'=>$paypalResponse['PAYERID'],
			'address_street'=>$address1.' '.$address2,
			'payment_date'=>$paypalResponse['ORDERTIME'],
			'option_selection1_1'=>$os0_1,
			'payment_status'=>$paypalResponse['PAYMENTSTATUS'],
			'option_selection1_2'=>$os0_2,
			'charset'=>'windows-1252',
			'address_zip'=>$zip,
			'mc_shipping'=>'0.00',//
			'mc_handling'=>'0.00',//
			'first_name'=>$paypalResponse['FIRSTNAME'],
			'mc_fee'=>$paypalResponse['FEEAMT'],
			'address_country_code'=>$paypalResponse['COUNTRYCODE'],
			'address_name'=>'',
			'notify_version'=>$paypalResponse['VERSION'],
			'custom'=>$custom,
			'payer_status'=>$paypalResponse['PAYERSTATUS'],
			'business'=>$business,
			'address_country'=>$paypalResponse['COUNTRYCODE'],
			'num_cart_items'=>2,
			'mc_handling1'=>'0.00',
			'mc_handling2'=>'0.00',
			'address_city'=>$city,
			'verify_sign'=>'',
			'payer_email'=>$paypalResponse['EMAIL'],
			'mc_shipping1'=>'0.00',//
			'mc_shipping2'=>'0.00',//
			'tax1'=>'0.00',//
			'btn_id1'=>'0',//
			'tax2'=>'0.00',//
			'btn_id2'=>'0',//
			'option_name1_1'=>$on0_1,
			'option_name1_2'=>$on0_2,
			'memo'=>'',
			'txn_id'=>$paypalResponse['TRANSACTIONID'],
			'payment_type'=>$paypalResponse['PAYMENTTYPE'],
			'last_name'=>$paypalResponse['LASTNAME'],
			'address_state'=>$state,
			'item_name1'=>$item_name1,
			'receiver_email'=>$paypalResponse['RECEIVEREMAIL'],
			'item_name2'=>$item_name2,
			'payment_fee'=>$paypalResponse['FEEAMT'],
			'quantity1'=>$quantity_1,
			'quantity2'=>$quantity_2,
			'receiver_id'=>$paypalResponse['RECEIVERID'],
			'txn_type'=>$paypalResponse['TRANSACTIONTYPE'],
			'mc_gross_1'=>floatval($amount_1 * $quantity_1),
			'mc_currency'=>$paypalResponse['CURRENCYCODE'],
			'mc_gross_2'=>floatval($amount_2 * $quantity_2),
			'residence_country'=>$paypalResponse['COUNTRYCODE'],
			'transaction_subject'=>$custom,
			'payment_gross'=>$paypalResponse['AMT'],
			'return'=>$return
			);
			$paymentData = array_merge($paymentData,$paypalResponse);
			///echo '<pre>';print_r($paymentData);echo '</pre>';exit();

			$variables=array();
			foreach($paymentData as $k=>$v){array_push($variables,$k.'='.urlencode($v));}
			//$variables=implode('&',$variables);

			$variables=var_export($variables,true);
			//echo $variables;exit();
			//header('Location: logTransaction.php?'.$variables);exit();

      /*
      echo '<pre>---';
      print_r($cart);
      print_r($variables);
      print_r($paypalResponse);

      echo $paypalResponse['PAYMENTSTATUS'].'<br/>';
      echo $paypalResponse['EMAIL'].'<br/>';
      echo $cart['custom'].'<br/>';
      echo $paypalResponse['AMT'].'<br/>';
      echo $paymentData['first_name'].'<br/>';
      echo $paymentData['last_name'].'<br/>';
      echo $cart['quantity_1'].'<br/>';
      echo $variables.'<br/>';
      echo $cart['return'].urlencode($paypalResponse['CUSTOM']).'<br/>';
      echo $paypalResponse['TRANSACTIONID'].'<br/>';

      echo '---</pre>';
      exit();
      */

			logTransactions2($paypalResponse['PAYMENTSTATUS'],
			  $paypalResponse['EMAIL'], $paypalResponse['CUSTOM'], $paypalResponse['AMT'], '',
		      $paymentData['first_name'], $paymentData['last_name'],
          $cart['quantity_1'], $variables,
          $cart['return'].urlencode($paypalResponse['CUSTOM']), 'XC', false,
          $paypalResponse['TRANSACTIONID']);
			exit();
		}else{
			$errorMessage = 'Payment error. ';
 		    $errorMessage .= notifyPaymentFailure($_POST,$paypalResponse);
		}

	}else{
		if(!isset($paypalResponse['L_SHORTMESSAGE0'])
		  || $paypalResponse['L_SHORTMESSAGE0'] == '') {
		  $errorMessage ='Invalid Payment.';
		} else {
		  $errorMessage = 'Payment error.';
		}

		$errorMessage .= notifyPaymentFailure($_POST,$paypalResponse,'XC');
	}
///
  }
}

//echo '<pre>';print_r($paypal->Response);echo '</pre>';exit();
/*

  $subtotal = $amount_1 * $quantity_1;
  $total = $subtotal + $amount_2;
  $remaining = $total - $discount_amount_cart;

  if($gift == 1){
    $displayName = GIFTCERT_ITEM_NAME;
    $description = GIFTCERT_ITEM_DESC;
  } else {
    $displayName = $wpdb->get_var("SELECT displayName FROM gift WHERE id = '".$gift."'");
    $description = $wpdb->get_var("SELECT description FROM gift WHERE id = '".$gift."'");
    if($displayName == NULL){exit();}
  }


	///echo'<pre>';print_r($paypal);echo'</pre>';exit();
*/
////////////////////////////////////////////////////////////////////////////////


?>