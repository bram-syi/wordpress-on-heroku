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
// Last Edited: Sep/Oct 2010 v2 for cart checkout
/**********************************************************************/

global $siteinfo, $wpdb;
chdir("google");
require_once('library/googleitem.php');
require_once('library/googlerequest.php');
include_once('library/googlecart.php');
include_once("payments.php");

define('GOOGLE_RETURN_INSTRUCTION','Please click here to return to SeeYourImpact<br/>');

//TODO: Log this into payments table, and update cart payment ID - so we don't lose track of the fact that he intended to pay

$cart = NULL;
finalize_cart($cart);

$referral = (isset($_COOKIE['referral']) ? $_COOKIE['referral'] : '');
$items = array();
$discounts = array();

foreach ($cart->items as $row) {
  $qty = absint($row['quantity']);
  if ($qty <= 0) continue;

  $price = (double)($row['price']);
  $giftID = $row['giftID'];
  switch ($giftID) {
    case CART_BUY_GC:
      $item = new GoogleItem(xml_entities(CART_BUY_GC_DESCRIPTION),"", $qty, $price); 
      $item->SetMerchantItemId($row['id'].'_'.$giftID);
      $item->SetMerchantPrivateItemData(new MerchantPrivateItemData(array(
        'itemID' => $row['id'],
        'giftID' => $giftID
      )));
      $items[] = $item;
      break;
     
    case CART_USE_GC:
      $item = new GoogleItem(xml_entities(CART_USE_GC_DESCRIPTION), '#' . $row['ref'], 1, $price);
      $item->SetMerchantPrivateItemData(new MerchantPrivateItemData(array(
        'itemID' => $row['id'],
        'giftID' => $giftID,
        'code' => $row['ref']
      )));
      $item->SetMerchantItemId($row['id']."_{$giftID}_".$row['ref']);
      $discounts[] = $item;
      break;
   
    case CART_TIP: // No longer used
    case CART_PLEDGE: 
      break; // Not included in checkout

    case CART_GIVE_ANY:
      $event_id = intval($row['event_id']);
      if ($event_id > 0) {
        $item = new GoogleItem("Fundraiser support", "", $qty, $price);
        $item->SetMerchantItemId($row['id']."_{$giftID}_{$event_id}");
        $item->SetMerchantPrivateItemData(new MerchantPrivateItemData(array(
          'itemID' => $row['id'],
          'giftID' => $giftID,
          'eventID' => $row['event_id']
        )));
        $item->SetURLDigitalContent(null, null, 'Thank you for your gift to ' . xml_entities(get_blog_option($row['blog_id'], 'blogname')) . '.<br/><a href="' . get_thankyou_url($cart->id) . '">What happens next?</a>');
        $items[] = $item;
        break;
      }
      // Fall through

    default:
      $avg_tgi = intval(get_avg_tgi($row['giftID']));
      if ($avg_tgi > 0) {
        $tg = $wpdb->get_row($wpdb->prepare(
          "SELECT * FROM gift WHERE id = %d", $avg_tgi),ARRAY_A);
        $item = new GoogleItem(xml_entities(ucfirst(AVG_NAME_PREFIX.$tg['displayName'])), xml_entities($tg['title']), $qty, $price);  
      } else {
        $item = new GoogleItem(xml_entities(ucfirst($row['displayName'])), xml_entities($row['title']), $qty, $price);           
      }

      $item->SetMerchantItemId($row['id'].'_'.$giftID);
      $item->SetMerchantPrivateItemData(new MerchantPrivateItemData(array(
        'itemID' => $row['id'],
        'giftID' => $giftID
      )));
      $item->SetURLDigitalContent(null, null, 'Thank you for your gift to ' . xml_entities(get_blog_option($row['blog_id'], 'blogname')) . '.<br/><a href="' . get_thankyou_url($cart->id) . '">What happens next?</a>');

      $items[] = $item;
      break;
  }

}

//GoogleCart instance
if (is_cart_test($cart->id)) { 
  $gcart = new GoogleCart(GOOGLE_MERCHANT_ID_SB, GOOGLE_MERCHANT_KEY_SB, 
    'sandbox', GOOGLE_CURRENCY, GOOGLE_API_URL_SB);
} else {
  $gcart = new GoogleCart(GOOGLE_MERCHANT_ID, 
    GOOGLE_MERCHANT_KEY, 'live', GOOGLE_CURRENCY, GOOGLE_API_URL);
}


// First items, then tip, then discounts
if ($cart->tip > 0) {
  $item = new GoogleItem(CART_TIP_DESCRIPTION,"",1,$cart->tip);
  $item->SetMerchantItemId("0_" . CART_TIP);
  $item->SetMerchantPrivateItemData(new MerchantPrivateItemData(array(
    'giftID' => CART_TIP
  )));
  $items[] = $item;
}
foreach ($items as $item) {
  $gcart->AddItem($item);
}
foreach ($discounts as $item) {
  $gcart->AddItem($item);
}

$gcart->SetEditCartUrl(get_cart_cancel_url($cart->id));
$gcart->SetContinueShoppingUrl(get_thankyou_url($cart->id));

$gcart->SetRequestBuyerPhone(false);
$gcart->SetMerchantPrivateData(new MerchantPrivateData(array(
  'cart-id' => $cart->id,
  'referral' => $referral
)));

if(isset($_POST['analyticsdata']) && !empty($_POST['analyticsdata'])){
  $gcart->SetAnalyticsData($_POST['analyticsdata']);
}

// This will do a server-2-server cart post and send an HTTP 302 redirect status
list($status,$error) = $gcart->CheckoutServer2Server();

// if we reach this point, something was wrong
debug("$status $error<br/><pre>" . print_r($gcart,true) . "</pre>", true, "Google Checkout failure");
die("We were unable to process your checkout. Please contact support@seeyourimpact.org for assistance.");

?>
