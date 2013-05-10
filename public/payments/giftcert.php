<?
/**********************************************************************/
// Author:      Yosia Urip
// Description: 
//
// Last Edited: May 2010
/**********************************************************************/

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');
function verifyTransaction(){}
include_once('logTransaction.php');

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

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])) {
  $first_name = trim($first_name);
  $last_name = trim($last_name);
  $email = trim($email);

  if (!empty($first_name) && !empty($last_name) && !empty($email)) {
	//echo '<pre>'; print_r($_REQUEST); echo '</pre>'; exit();    
	$paymentData = 
	array (
	'mc_gross'=>$total, 
	'mc_gross_1'=>floatval($subTotal),
	'mc_gross_2'=>floatval($tipTotal),
	'mc_shipping'=>0, 'mc_handling'=>0,
	'mc_handling1'=>0, 'mc_handling2'=>0,
	'mc_shipping1'=>0, 'mc_shipping2'=>0,
	'mc_fee'=>0,
	'mc_currency'=>'USD',
	'tax'=>0,
	'payer_id'=>'SYI GiftCertificate # '.$code,
	'first_name'=>$first_name, 'last_name'=>$last_name,
	'payer_email'=>$email,
	'payer_status'=>'',
	'protection_eligibility'=>'',
	'business'=>'',
	'receiver_email'=>$email,
	'receiver_id'=>0, 
	'address_name'=>'',
	'address_street'=>'',
	'address_zip'=>'',
	'address_city'=>'',
	'address_state'=>'',
	'address_country'=>'',
	'address_country_code'=>'',
	'address_status'=>'',
	'residence_country'=>'US', 
	'item_number1'=>$item_number, 'item_number2'=>'',
	'item_name1'=>$item_name1, 'item_name2'=>$item_name2,
	'quantity1'=>$quantity_1, 'quantity2'=>$quantity_2,
	'option_name1_1'=>$on0_1, 'option_name1_2'=>$on0_2,
	'option_selection1_1'=>$os0_1, 'option_selection1_2'=>$os0_2,
	'tax1'=>0, 'tax2'=>0,
	'btn_id1'=>0, 'btn_id2'=>0,
	'charset'=>'windows-1252',
	'notify_version'=>'',
	'custom'=>$custom,
	'num_cart_items'=>2,
	'verify_sign'=>'',
	'memo'=>'', 
	'txn_id'=>$txnId,
	'payment_type'=>'GiftCertificate',
	'payment_gross'=>$total, 
	'payment_fee'=>0,
	'payment_date'=>date('Y-m-d H:i:s'),
	'payment_status'=>'Completed',
	'txn_type'=>'',
	'transaction_subject'=>$custom,
	'return'=>$return);

	///echo '<pre>';print_r($paymentData);echo '</pre>';exit();       
	$variables = array();
	foreach($paymentData as $k => $v){
	  array_push($variables,$k.'='.urlencode($v));
	}
	$variables = var_export($variables,true);

	//Calling logTransaction2 
	//total is set to 0 so the internal calculation works correctly
	logTransactions2('COMPLETED', $email, $custom, $total, $memo,
	  $first_name, $last_name, $quantity_1, $variables, $return, 'GC', false, $txnId);
	exit();
	
  } else {

    ///

  }
}

?>