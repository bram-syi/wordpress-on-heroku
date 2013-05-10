<?
/**********************************************************************/
// Author:      Google, Yosia Urip
// Description: Acts as a web service to handle request from Google 
//              when a payment was made on their end. Will redirect to 
//              logTransaction with formatted data to store it in db.
// Last Edited: February 2010
/**********************************************************************/

header('Content-type: text/xml');
include_once('payments.php');
require_payments('google', 'library/google*.php');

try {

$Gresponse = new GoogleResponse(GOOGLE_MERCHANT_ID,GOOGLE_MERCHANT_KEY);
$Grequest = new GoogleRequest(GOOGLE_MERCHANT_ID,GOOGLE_MERCHANT_KEY,
  GOOGLE_SERVER_MODE,GOOGLE_CURRENCY);
  
//Setup the log file
$Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE,RESPONSE_HANDLER_LOG_FILE,L_ALL);

// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
$xml_response = isset($HTTP_RAW_POST_DATA)
  ? $HTTP_RAW_POST_DATA : file_get_contents("php://input");
if (get_magic_quotes_gpc()) 
  $xml_response = stripslashes($xml_response); 
list($type,$data) = $Gresponse->GetParsedXML($xml_response);
$info = $data[$type];
$Gresponse->SetMerchantAuthentication($merchant_id,$merchant_key);

$fh = fopen("google.log", 'a');
fwrite($fh, "\n=== " . date("m/j/Y h:i:sa") . " ===============================\n");
fwrite($fh, print_r($data, TRUE));
fclose($fh);

//dp(print_r(array($data,$type),true));
$serial_number = $data[$type]['serial-number'];
$cart_id = intval($info['shopping-cart']['merchant-private-data']['cart-id']['VALUE']);

if($cart_id == 0) { 
  $cart_txn = get_cart_txn($serial_number); 
  if(isset($cart_txn->id) && isset($cart_txn->txnData)) {
    $cart_id = $cart_txn->id;
    //retrieving cart transaction

//debug($type." DECODING THIS:\n".print_r($cart_txn->txnData,true),true);
    $stored_data = json_decode($cart_txn->txnData,true);
//debug($type." DECODED THIS:\n".print_r($stored_data,true),true);
    $stored_info = $stored_data['new-order-notification'];

  } else {
    debug("DATA: ".print_r($data,true),true,"GG LOG ERROR - CART TXN NOT FOUND");
  }
}
    
switch ($type){
  case "new-order-notification": 
    //stroring cart transaction
    holdCart($cart_id,$serial_number,$data);
    $Gresponse->SendAck(false, $serial_number);
    dc($cart_id,"ACK GG REQUEST PROCESSING"."\n".print_r(array($data,$type),true));
    die();
  case "charge-amount-notification":
    $Gresponse->SendAck(false, $serial_number);
    dc($cart_id,"ACK GG REQUEST PROCESSING"."\n".print_r(array($data,$type),true));
    processCharge($cart_id, $stored_info,$stored_data);
    die();

  case "authorization-amount-notification":
    $Gresponse->SendAck(false, $serial_number);
    // contains a copy of the order
    dc($cart_id,"ACK GG REQUEST PROCESSING"."\n".print_r(array($data,$type),true));
    // Don't process until charge-amount-notification
    processCharge($cart_id, $info,$data);
    die();

  // We don't yet log order change notifications
  case "order-state-change-notification":
    $Gresponse->SendAck(false, $serial_number);

    $new_financial_state=$data[$type]['new-financial-order-state']['VALUE'];
    $new_fulfillment_order=$data[$type]['new-fulfillment-order-state']['VALUE'];

    //debug($new_financial_state." ".$new_fulfillment_order,true);

    switch($new_financial_state){
      case 'REVIEWING': { break; }
      case 'CHARGEABLE': { break; }
      case 'CHARGING': { break; }
      case 'CHARGED': { 

//        dc($cart_id,"ACK GG REQUEST PROCESSING"."\n".print_r(array($data,$type),true));
//        processCharge($cart_id, $stored_info,$stored_data);
//        die();      
      
      break; }
      case 'PAYMENT_DECLINED': { break; }
      case 'CANCELLED': { break; }
      case 'CANCELLED_BY_GOOGLE': { break; }
      default:break;
    }
    switch($new_fulfillment_order){
      case 'NEW': { break; }
      case 'PROCESSING': { break; }
      case 'DELIVERED': { break; }
      case 'WILL_NOT_DELIVER': { break; }
      default:break;
    }
    dc($cart_id,"UNHANDLED GG REQUEST"."\n".print_r(array($data,$type),true));
    die();

  // Ignore these ones
  case "request-received":
  case "error":
  case "diagnosis":
  case "checkout-redirect":
  case "merchant-calculation-callback":
    dc($cart_id,"UNHANDLED GG REQUEST"."\n".print_r(array($data,$type),true));
    die();

  // Acknowledge these ones
  // case "new-order-notification":
  // case "charge-amount-notification":
  case "chargeback-amount-notification":
  case "refund-amount-notification":
  case "risk-information-notification":
    $Gresponse->SendAck(false, $serial_number);
    dc($cart_id,"ACK GG REQUEST"."\n".print_r(array($data,$type),true));
    die();

  // Disallow everything else
  default:
    $Gresponse->SendBadRequestStatus("Invalid or not supported Message");
    dc($cart_id,"UNHANDLED GG REQUEST"."\n".print_r(array($data,$type),true));
    die();
}

} catch (Exception $e) {
  debug("ERROR: ".print_r($e, true),true,"GG LOG ERROR");
}

function processCharge($cart_id, $info,$data) {

//debug($data,true);

  if(isset($info['order-summary'])) $info = $info['order-summary'];

  $items = $info['shopping-cart']['items'];
  $variables = var_export($data,true);
  $order = new Donation($variables);
  $order->payment = new stdClass;
  $order->payment->status = 'Paid';
  $order->payment->gross = $info['order-total']['VALUE']; 
  $order->payment->tipped = 0;
  // TODO: make sure the currency is USD!
  $order->payment->id = $info['google-order-number']['VALUE'];
  $order->payment->method = 'GG';
  
  $order->data = $info['shopping-cart']['merchant-private-data']['VALUE'];
  $order->donor = get_cart_donor_info($cart_id);

  $order->payer = new stdClass;
  $order->payer->email = $info['buyer-shipping-address']['email']['VALUE'];
  $order->payer->first = $info['buyer-shipping-address']['structured-name']['first-name']['VALUE'];
  $order->payer->last = $info['buyer-shipping-address']['structured-name']['last-name']['VALUE'];
  $order->payer->contactme = $info['buyer-marketing-preferences']['email-allowed']['VALUE'];
  $order->payer->referral = $info['shopping-cart']['merchant-private-data']['referral']['VALUE'];

  // BUILD THE CART - we use the reported Google data rather than trusting 
  // our "cart" table, because it might have been modified in transit
  $order->cart = new stdClass;
  $order->cart->id = $info['shopping-cart']['merchant-private-data']['cart-id']['VALUE'];
  $order->cart->items = array();
  $order->discounts = array();

  $items = $info['shopping-cart']['items'];
  if ($items['item'][0])
    $items = $items['item'];

  //to dis-count the discount
  $total_positive = 0;
  $total_tipped = 0;

  foreach ($items as $item) {
    $data = $item['merchant-private-item-data'];
    $item_id = $data['itemID']['VALUE'];
    $gift_id = $data['giftID']['VALUE'];
    $ref = $data['ref']['VALUE'];

    $i = new stdClass;
    $i->id = $item_id;
    $i->gift_id = $gift_id;
    $i->price = $item['unit-price']['VALUE'];
    $i->quantity = $item['quantity']['VALUE'];
    $i->ref = $ref;

    switch ($gift_id) {
      case CART_TIP: 
        $total_positive += $i->price;
        $order->payment->tip += $i->price;
        break;
      case CART_USE_GC:
        $order->discounts[] = $i;
        break;
      case CART_BUY_GC:
        $total_positive += $i->price * $i->quantity;
        $order->cart->items[] = $i;
        break;
      default:
        $total_positive += $i->price * $i->quantity;
		$total_tipped += $i->price * $i->quantity;
        $order->cart->items[] = $i;
        break;
    }
  }

  $order->payment->gross = $total_positive;
  $order->payment->tipped = $total_tipped;
//debug(print_r($order, true),true);
  dc($order->cart->id,"PROCESSING ORDER \n".print_r($order,true));
  processOrder($order);    
}

?>
