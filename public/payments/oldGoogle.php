<?
/**********************************************************************/
// Author:      Google, Yosia Urip
// Description: Acts as a web service to handle request from Google 
//              when a payment was made on their end. Will redirect to 
//              logTransaction with formatted data to store it in db.
// Last Edited: February 2010
/**********************************************************************/

include_once('logTransaction.php');
include_once('../wp-includes/syi/syi-includes.php');

chdir('google');
require_once('library/googlecart.php');
require_once('library/googleitem.php');
require_once('library/googleresponse.php');
require_once('library/googlemerchantcalculations.php');
require_once('library/googleresult.php');
require_once('library/googlerequest.php');

function verifyTransaction(){
	$Gresponse = new GoogleResponse(GOOGLE_MERCHANT_ID,GOOGLE_MERCHANT_KEY);
	$Grequest = new GoogleRequest(GOOGLE_MERCHANT_ID,GOOGLE_MERCHANT_KEY,
		GOOGLE_SERVER_MODE,GOOGLE_CURRENCY);
		
	//Setup the log file
	$Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE,RESPONSE_HANDLER_LOG_FILE,L_ALL);
	
	// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
	$xml_response=isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:file_get_contents("php://input");
	if (get_magic_quotes_gpc()){$xml_response=stripslashes($xml_response);}
	list($root,$data)=$Gresponse->GetParsedXML($xml_response);
	$Gresponse->SetMerchantAuthentication($merchant_id,$merchant_key);
	
	switch ($root){
		case "request-received":{break;}
		case "error":{break;}
		case "diagnosis":{break;}
		case "checkout-redirect":{break;}
		case "merchant-calculation-callback":{break;}
		case "new-order-notification":{

		$googleResponse=$data["new-order-notification"];

		$paymentData = 
		array(
		'mc_gross'=>$googleResponse['order-total']['VALUE'],
		'protection_eligibility'=>'',
		'address_status'=>'',
		'item_number1'=>'',
		'tax'=>$googleResponse['order-adjustment']['total-tax']['VALUE'],
		'item_number2'=>'',
		'payer_id'=>$googleResponse['buyer-id']['VALUE'],
		'address_street'=>$googleResponse['buyer-billing-address']['address1']['VALUE'].
			' '.$googleResponse['buyer-billing-address']['address2']['VALUE'],
		'payment_date'=>$googleResponse['timestamp']['VALUE'],
		'option_selection1_1'=>'',
		'option_selection1_2'=>'',
		'payment_status'=>'CHARGED',
		'charset'=>'windows-1252',
		'address_zip'=>$googleResponse['buyer-billing-address']['postal-code']['VALUE'],
		'mc_shipping'=>'0.00',//
		'mc_handling'=>'0.00',//
		'first_name'=>$googleResponse['buyer-billing-address']['structured-name']['first-name']['VALUE'],
		'mc_fee'=>'',
		'address_country_code'=>$googleResponse['buyer-billing-address']['country-code']['VALUE'],
		'address_name'=>'',
		'notify_version'=>'',
		'custom'=>$googleResponse['shopping-cart']['merchant-private-data']['VALUE'],
		'payer_status'=>'',
		'business'=>GOOGLE_MERCHANT_ID,
		'address_country'=>$googleResponse['buyer-billing-address']['country-code']['VALUE'],
		'num_cart_items'=>2,
		'mc_handling1'=>'0.00',
		'mc_handling2'=>'0.00',
		'address_city'=>$googleResponse['buyer-billing-address']['city']['VALUE'],
		'verify_sign'=>'',
		'payer_email'=>$googleResponse['buyer-shipping-address']['email']['VALUE'],
		'mc_shipping1'=>'0.00',//
		'mc_shipping2'=>'0.00',//
		'tax1'=>'0.00',//
		'btn_id1'=>'0',//
		'tax2'=>'0.00',//
		'btn_id2'=>'0',//
		'option_name1_1'=>'',
		'option_name1_2'=>'',
		'memo'=>'',
		'txn_id'=>$googleResponse['google-order-number']['VALUE'],
		'payment_type'=>'Google Checkout',
		'last_name'=>$googleResponse['buyer-billing-address']['structured-name']['last-name']['VALUE'],
		'address_state'=>$googleResponse['buyer-shipping-address']['region']['VALUE'],
		'item_name1'=>
		///
		(isset($googleResponse['shopping-cart']['items']['item'][0])?
		$googleResponse['shopping-cart']['items']['item'][0]['item-name']['VALUE']:
		$googleResponse['shopping-cart']['items']['item']['item-name']['VALUE'])
		,
		
		'receiver_email'=>$googleResponse['buyer-shipping-address']['email']['VALUE'],
		'item_name2'=>
		///
		(isset($googleResponse['shopping-cart']['items']['item'][0])?
		$googleResponse['shopping-cart']['items']['item'][1]['item-name']['VALUE']:
		NULL),
		
		'payment_fee'=>$googleResponse['FEEAMT'],
		'quantity1'=>
		///
		(isset($googleResponse['shopping-cart']['items']['item'][0])?
		$googleResponse['shopping-cart']['items']['item'][0]['quantity']['VALUE']:
		$googleResponse['shopping-cart']['items']['item']['quantity']['VALUE']),
		
		'quantity2'=>
		///
		(isset($googleResponse['shopping-cart']['items']['item'][0])?
		$googleResponse['shopping-cart']['items']['item'][1]['quantity']['VALUE']:
		NULL),
		
		'receiver_id'=>$googleResponse['buyer-id']['VALUE'],
		'txn_type'=>'Google Checkout',
		'mc_gross_1'=>
		///
		(isset($googleResponse['shopping-cart']['items']['item'][0])?
		floatval($googleResponse['shopping-cart']['items']['item'][0]['unit-price']['VALUE'] 
		* $googleResponse['shopping-cart']['items']['item'][0]['quantity']['VALUE']):
		floatval($googleResponse['shopping-cart']['items']['item']['unit-price']['VALUE'] 
		* $googleResponse['shopping-cart']['items']['item']['quantity']['VALUE']))
		,
		
		
		'mc_currency'=>$googleResponse['order-total']['currency'],
		'mc_gross_2'=>
		///
		(isset($googleResponse['shopping-cart']['items']['item'][0])?
		floatval($googleResponse['shopping-cart']['items']['item'][1]['unit-price']['VALUE'] 
		* $googleResponse['shopping-cart']['items']['item'][1]['quantity']['VALUE']):
		0),
		
		
		'residence_country'=>$googleResponse['buyer-billing-address']['country-code']['VALUE'],
		'transaction_subject'=>$googleResponse['shopping-cart']['merchant-private-data']['VALUE'],
		'payment_gross'=>$googleResponse['order-total']['VALUE'],
		'return'=>''
		);

		$variables=array();
		foreach($paymentData as $k=>$v){array_push($variables,$k.'='.urlencode($v));}
		$variables=var_export($paymentData,true);
		$variables.=var_export($data,true);

		logTransactions2('Completed',
		  $googleResponse['buyer-shipping-address']['email']['VALUE'],
		  $googleResponse['shopping-cart']['merchant-private-data']['VALUE'],
		  $googleResponse['order-total']['VALUE'],'',
		  $googleResponse['buyer-billing-address']['structured-name']['first-name']['VALUE'],
		  $googleResponse['buyer-billing-address']['structured-name']['last-name']['VALUE'],
		  (isset($googleResponse['shopping-cart']['items']['item'][0])?
		  $googleResponse['shopping-cart']['items']['item'][0]['quantity']['VALUE']:
		  $googleResponse['shopping-cart']['items']['item']['quantity']['VALUE'])
		  ,
		  $variables,'','GG', false, $googleResponse['google-order-number']['VALUE']);
				
		$Gresponse->SendAck();break;}
		case "order-state-change-notification":{
		$Gresponse->SendAck(false);
		$new_financial_state=$data[$root]['new-financial-order-state']['VALUE'];
		$new_fulfillment_order=$data[$root]['new-fulfillment-order-state']['VALUE'];

		switch($new_financial_state){
			case 'REVIEWING':{break;}
			case 'CHARGEABLE':{break;}
			case 'CHARGING':{break;}
			case 'CHARGED':{break;}
			case 'PAYMENT_DECLINED':{break;}
			case 'CANCELLED':{break;}
			case 'CANCELLED_BY_GOOGLE':{break;}
			default:break;
		}
		switch($new_fulfillment_order){
		case 'NEW':{break;}
		case 'PROCESSING':{break;}
		case 'DELIVERED':{break;}
		case 'WILL_NOT_DELIVER':{break;}
		default:break;
	}
	break;}
	case "charge-amount-notification":{$Gresponse->SendAck();break;}
	case "chargeback-amount-notification":{$Gresponse->SendAck();break;}
	case "refund-amount-notification":{$Gresponse->SendAck();break;}
	case "risk-information-notification":{$Gresponse->SendAck();break;}
	default:$Gresponse->SendBadRequestStatus("Invalid or not supported Message");break;
	}
}

//In case the XML API contains multiple open tags with the same value,then invoke this function and
//perform a foreach on the resultant array. This takes care of cases when there is only one unique tag
//or multiple tags. Examples of this are "anonymous-address","merchant-code-string" from the merchant-calculations-callback API

function get_arr_result($child_node){
	$result=array();
	if(isset($child_node)){
		if(is_associative_array($child_node)){$result[]=$child_node;}
		else{foreach($child_node as $curr_node){$result[]=$curr_node;}}
	}
	return $result;
}

//Returns true if a given variable represents an associative array
function is_associative_array($var){return is_array($var) && !is_numeric(implode('',array_keys($var)));}

verifyTransaction();

?>
