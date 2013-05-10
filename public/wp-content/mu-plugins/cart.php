<?
/*
Plugin Name: Cart
Plugin URI: http://www.seeyourimpact.com/
Version: 1.0
Author: Yosia Urip
Description: shopping cart settings
Author URI: http://www.seeyourimpact.com/
Instructions:
*/

define('CREDIT_PAYMENT_TEST', FALSE);

include_once(ABSPATH . '/wp-includes/syi/syi-functions.php');

add_action('admin_menu', 'cart_add_menu', SW_HOME_PRIORITY);
add_action('display_share_email_label', 'display_share_email_label');

// Maybe we'll need a class. Let's see
class SYICart
{
  private $row;

  public $ID;
  public $tip = 0;
  public $discount = 0;
  public $discounts = array();
  public $item_rows;

  public function __construct($id) {
    global $wpdb;

    $this->row = $wpdb->get_row($wpdb->prepare( "SELECT * FROM cart WHERE id = %d", $id ));
    $this->item_rows = cart_to_array($id);
  }

  public function __get($name) {
    return $this->row->$name;
  }

  public function getItems() {
    return $this->item_rows;
  }

  public function getPayment() {
    global $wpdb;

    return $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM payment WHERE id=%d", $this->paymentID));
  }
}

define('CART_UPDATE_LABEL', "update changes");
define('CART_CHECKOUT_LABEL', "check out &raquo;");
define('CART_PAYNOW_LABEL', "continue &raquo;");

define('DEFAULT_PASSWORD', 'SeeYourImpact');

define('CART_COOKIE_NAME', 'CART');
define('CART_BUY_GC', 1);
define('CART_BUY_GC_DESCRIPTION', "A SeeYourImpact Impact Card");
define('CART_USE_GC', 2);
define('CART_USE_GC_DESCRIPTION', "Impact Card applied");
define('CART_GIVE_ANY', 50);

define('CART_USE_GC_INVALID_PAYPAL', "Sorry, but we don't recognize that Impact Card code.
  If you're redeeming a PayPal gift certificate, please select PayPal payment and enter your code there.");
define('CART_USE_GC_INVALID', "We're sorry, but we don't recognize that Impact Card code.");
define('CART_USE_GC_EXPIRED', "We're sorry, that Impact Card code has expired.");
define('CART_USE_GC_INSUFFICIENT','Insufficient funds.');

define('CART_BUY_GC_TITLE','Add an Impact Card');
define('CART_BUY_GC_DESC','Share the joy of giving with your friends, family, and loved ones!
Send an Impact Card redeemable for any donations through our charity partners.');
define('CART_BUY_GC_INSTRUCTION','Who would you like to give this gift to?');
define('CART_GC_AMOUNTS','10,15,20,25,30,40,50,60,80,100');

define('CART_TIP', 3);
define('CART_TIP_DESCRIPTION', "Donation to SeeYourImpact - thank you!");
define('CART_TIP_DEFAULT', "15%");
define('CART_TIP_RATE_DEFAULT', 0.15);
define('CART_TIP_RATES','0,5,10,15,20,30,40,50');

define('CART_PLEDGE', 5);

define('CART_CHECKOUT_INSTRUCTION', "How would you like to pay for your donation?");
define('CART_CHECKOUT_UPDATE_NOTE',
""
//"Note: changes you've made is not applied until you click the 'Update Cart' button."
);
define('CART_CHECKOUT_FREE_NOTE', "Your donation is fully paid by the Impact Card.");
define('CART_CHECKOUT_FREE_NOTE2', "<p><b>Please confirm your contact information:</b></p>");

define('CREDITCARD_TITLE','Pay with Credit Card');
define('SIGNIN_TITLE','Sign In to Your Account');

define('CART_EMPTY_MESSAGE','It seems there is no item in your cart.
Please select one of the gift options below or browse our <a href="/give/"><u>give</u></a> page.<br/><br/>
If you recently submitted your payment, refreshed the page, and got to this page, your payment might have already been processed.
Please contact <a href="mailto:contact@seeyourimpact.org"><u>contact@seeyourimpact.org</u></a> if you have more questions.
');

define('AVG_UNIT_AMOUNT',1); // aggregate variable gift unit price
define('AVG_NAME_PREFIX', 'a donation toward '); // avg name prefix before name of actual gift

global $payment_method_titles;
$payment_method_titles = array(
  "GG"=>"Pay with Google",
  "CC"=>"Pay with credit card",
  "PP"=>"Pay with PayPal",
  "GC"=>"Finish"
);

global $payment_method_pages;
$payment_method_pages = array(
  "GG"=>"/payments/payGoogle.php",
  "CC"=>"",
  "PP"=>"/payments/payPaypal.php",
  "GC"=>""
);

////////////////////////////////////////////////////////////////////////////////

function is_live_payments() {
  // Only process real money on the very livest of live sites
  return IS_LIVE_SITE && !IS_STAGING_SITE;
}

function cart_add_menu() {
  add_submenu_page('site-config', 
    __('Cart', 'cart'), 
    __('Cart', 'cart'), 
    'manage_network', 'cart', 'cart_page');
}

function cart_page() {
  global $wpdb;

  ?><div class="wrap"><h2>Cart Settings</h2><?

  //Get request

  if (isset($_POST['cart_debug'])) {
    update_blog_option(1, 'cart_debug', $_POST['cart_debug']==1?1:0);
  }
  if (isset($_POST['cart_signin'])) {
    update_blog_option(1, 'cart_signin', $_POST['cart_signin']==1?1:0);
  }
  if (isset($_POST['saved_cart'])) {
    update_blog_option(1, 'saved_cart', $_POST['saved_cart']==1?1:0);
  }

  $val_debug = get_blog_option(1, 'cart_debug');
  $val_signin = get_blog_option(1, 'cart_signin');
  $saved_cart = get_blog_option(1, 'saved_cart');
  //Display settings
  ?>
  <div>
  <form method="post">
<p>
  Cart debugging is: <br/>
  <input type="radio" name="cart_debug" value="1"
    <?=($val_debug==1?'checked="checked"':'')?> /> ON &nbsp;
  <input type="radio" name="cart_debug" value="0"
    <?=($val_debug==1?'':'checked="checked"')?> /> OFF
</p>
<p>
  Cart signin is: <br/>
  <input type="radio" name="cart_signin" value="1"
    <?=($val_signin==1?'checked="checked"':'')?> /> ON &nbsp;
  <input type="radio" name="cart_signin" value="0"
    <?=($val_signin==1?'':'checked="checked"')?> /> OFF
</p>
<p>
  Saved cart is: <br/>
  <input type="radio" name="saved_cart" value="1"
    <?=($saved_cart==1?'checked="checked"':'')?> /> ON &nbsp;
  <input type="radio" name="saved_cart" value="0"
    <?=($saved_cart==1?'':'checked="checked"')?> /> OFF
</p>
<p>
  <input type="submit" value="Submit" name="submit" />

</p>
</form>
  </div>
  <div>
<form method="post">
  <input type="text" name="cart_id" />
  <input type="submit" name="submit" value="List Cart Activities"/>
  <input type="submit" name="submit" value="Get Last Activities"/>
</form>
  </div>
  <?
  ?><div><?
  if (!empty($_POST['cart_id']) || ($_POST['submit']=='Get Last Activities')) {

    if ($_POST['submit']=='Get Last Activities')
      $cart_id = $wpdb->get_var("SELECT cartID FROM cartDebug WHERE message <> 'Cart created' ORDER BY id DESC LIMIT 1 ");    
    else
      $cart_id = intval($_POST['cart_id']);

    $cartDebug = $wpdb->get_results(
      $wpdb->prepare(
      "SELECT * FROM cartDebug WHERE cartID = %d",$cart_id),ARRAY_A);
    if(is_array($cartDebug)) {
    echo '<br/><br/><table cellpadding="2" cellspacing="0">';
    echo '<tr><td colspan="3">Cart #'.$cart_id.'</td></tr>';
    foreach ($cartDebug as $rec) {
      echo '<tr style="vertical-align:top;">';
      echo '<td style="font-size:9px;width:140px;">'.$rec['recorded'].'</td>';
      echo '<td style="font-size:9px;width:600px;">'.$rec['message'].'</td>';
      echo '<td style="font-size:9px;width:60px;">'
      //.$rec['note']
      .'</td>';
      echo '</tr>';
    }
    echo '</table><br/><br/><div style="font-size:10px;">';
    $cart_items = $wpdb->get_results($wpdb->prepare("SELECT * FROM cartItem WHERE cartID = %d",$cart_id));
    echo '<pre>'.print_r($cart_items,true).'</pre></div>';
    echo '<br/><br/><br/><br/>';
    }
  }
  ?><div><?

  ?></div><?
}



function restore_cart($cartID = 0) {
  global $wpdb;
  global $site_url;
  global $current_user;

  if (is_user_logged_in()) {
    get_currentuserinfo();
    $userID = $current_user->ID;
  } else {
    $userID = 0;
  }

  if ($cartID > 0) {
    $cartStatus = $wpdb->get_var($sql = $wpdb->prepare(
      "SELECT status FROM cart WHERE id=%d AND (userID=0 OR userID=%d)", 
      $cartID, $userID));
    dc($cartID, "$sql = $cartStatus");
  } else {
    $cartStatus = "inactive";
  }

  update_cart_cookie($cartID, $cartStatus);
  return $cartStatus;
}

function set_cart_eid($cartID, $eid) {
  update_cart_data($cartID, 'event_id', $eid);
}
add_action('campaign_add_cart', 'set_cart_eid', 2, 2);

function get_cart($get_new=true) {
  //get cartID from db, if NA, create one
  global $user_ID;
  global $wpdb;
  global $site_url;
  global $current_user;

  $cartID = NULL;
  $userID = NULL;

  if (is_user_logged_in()) {
    get_currentuserinfo();
    $userID = $current_user->ID;
  } else {
    $userID = 0;
  }
  
  if (!empty($_COOKIE[CART_COOKIE_NAME])) {
    //validate session is active and owned by current user
    $cart_value = explode(',', $_COOKIE[CART_COOKIE_NAME]);
    if (isset($cart_value[1])) {
      $cartID = decrypt_cart($cart_value[1]);
      $cart = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM cart
        WHERE id = %d AND type = 'cart' AND status = 'active' 
          AND (userID = 0 OR userID = %d)",
        $cartID, $userID), ARRAY_A);
    }
    
    if ($cart == NULL) {
      $cartID = NULL;
      update_cart_cookie(0);
    } else {
      //cart exists and valid
      $cartID = $cart['id'];
      if ($cart['userID'] == 0 && $userID > 0) {
        //if cart has no owner, assign to user
        $wpdb->query($wpdb->prepare(
          "UPDATE cart SET userID = %d WHERE (userID = 0 OR userID IS NULL) AND id = %d",
          $userID, $cartID));
        cart_update_time($cartID);
      }
      update_cart_cookie($cartID, 'active');
    }
  }

  if ($cartID == NULL && $userID > 0) {
    //if session invalid but logged in, get last stored cart
    $cartID = $wpdb->get_var($wpdb->prepare(
      "SELECT id FROM cart 
      WHERE userID = %d AND type = 'cart' AND status = 'active' 
      ORDER BY id DESC ",
      $userID));
    update_cart_cookie($cartID, 'active');
  }

  if ($cartID == NULL && $get_new) {
    //if user not logged in or no cart available, create a new one
    $cartID = create_cart_for_user($userID, 'cart', 'active');
    update_cart_cookie($cartID, 'active');
  }

  if (empty($cartID)) return NULL;
  cart_update_referrer($cartID);
  
  return $cartID;
}

function update_cart_cookie($cart_id = 0, $status = "active") {
  if ($cart_id > 0 && $status == "active") {
    $cook = count_cart_items($cart_id) . "," . encrypt_cart($cart_id);
    $time = time() + 60*60*24*2;
  } else {
    $cook = "";
    $time = time() - 3600;
  }
  @setcookie(CART_COOKIE_NAME, $cook, $time, '/', IS_LIVE_SITE ? '.seeyourimpact.org' : '.seeyourimpact.com');
}

function create_cart_for_user($userID, $type, $status) {
  global $wpdb;
  $wpdb->insert('cart',
    array(
      'userID' => $userID,
      'type' => $type,
      'status' => $status),
    array('%d', '%s', '%s'));

  $cartID = $wpdb->insert_id;
  if($cartID != NULL)
    dc($cartID,'Cart created');
  return $cartID;
}

function get_cart_link() {
  return get_site_url(1, '/cart/', is_live_site() ? 'https' : 'login');
}

function get_cart_events($cartID = NULL) {
  global $wpdb;
  if ($cartID == NULL) $cartID = get_cart(false);

  return $wpdb->get_results($wpdb->prepare(
    "select * from (
       select event_id,count(*) as num_gifts from cartItem 
       where cartID = %d and event_id > 0
       group by event_id
     ) t
       left join wp_1_posts p on p.ID = t.event_id",
    $cartID) ); 
}

function get_cart_event_ids($cart_id) {
  global $wpdb;

  return $wpdb->get_var($wpdb->prepare(
    "SELECT GROUP_CONCAT(DISTINCT event_id ORDER BY event_id SEPARATOR ',') 
    FROM cartItem WHERE cartID = %d AND event_id IS NOT NULL AND event_id > 0 
    GROUP BY cartID",$cart_id));
}

function get_event_match($event_id, $item) {
  // $item may be an array or an object depending on call context...
  // remember to normalize
  $acct_id = get_post_meta($event_id, 'syi_matching_account', true);
  if ($acct_id > 0)
    return intval($acct_id);
  return NULL;
}

function get_cart_data($cart_id, $field = NULL) {
  global $wpdb;

  $data = $wpdb->get_var($wpdb->prepare(
    "SELECT data FROM cart WHERE id=%d", $cart_id));
  $d = json_decode($data);
  if ($d === NULL)
    $d = new stdClass;
  if ($field !== NULL)
    return $d->$field;
  return $d;
}

function update_cart_data($cart_id, $key, $value = NULL) {
  global $wpdb;

  $data = get_cart_data($cart_id);

  if (is_array($key)) {
    $data = (object)array_merge((array)$data, (array)$key);
  } else if ($data->$key === $value)
    return;
  else if ($value === NULL)
    unset($data->$key);
  else
    $data->$key = $value;

  $wpdb->update('cart',
    array('data' => json_encode($data)),
    array('id' => $cart_id),
    array('%s'), array('%d'));
}




function update_cart_data_sparse($cart_id, $key, $value) {
  if (empty($value))
    $value = NULL;
  update_cart_data($cart_id, $key, $value);
}



function count_cart_items($cartID = NULL) {
  global $wpdb;
  if ($cartID == NULL) 
    $cartID = get_cart(false);
  return intval($wpdb->get_var($wpdb->prepare("
    SELECT SUM(quantity) 
    FROM cartItem 
    WHERE giftID!=2 AND giftID!=3 AND cartID = %d", 
    $cartID)));
}

function item_where($cartID, $giftID = 0) {
  global $wpdb;

  $where[] = $wpdb->prepare("cartID=%d", $cartID);
  if ($giftID > 0)
    $where[] = $wpdb->prepare("giftID=%d", $giftID);
  return $where;
}

function get_item_row($where, $as = OBJECT) {
  global $wpdb;
  return $wpdb->get_row("SELECT * FROM cartItem WHERE " . implode(' AND ', $where), $as);
}

function cart_add_discount($cartID, $discount, $ref) {
  global $wpdb;

  dc($cartID, "add discount: $discount / $ref");

  if ($discount > 0)
    $discount = -$discount;

  $giftID = CART_USE_GC;
  $where = item_where($cartID, $giftID);
  $where[] = $wpdb->prepare("ref=%s", $ref);
  $item = get_item_row($where);

  if ($item == NULL) {
    $wpdb->query($wpdb->prepare(
      "INSERT INTO cartItem (cartID, giftID, price, quantity, ref) "
      ."VALUES (%d, %d, %f, 1, %s) ",
      $cartID, $giftID, $discount, $ref ));
  } else {
    $wpdb->query($wpdb->prepare(
      "UPDATE cartItem SET price=%f, quantity=1 WHERE id=%d",
      $discount, $item->id));
  }
  cart_update_time($cartID);
}

// Tip rate is a string, like "10%".
// In the future we will also support fixed amounts (a number, not a percent)
function cart_set_tip($cartID, $tip_rate) {
  global $wpdb;

  // BACK COMPAT
  if ($cartID < 32000) {
    $wpdb->query($wpdb->prepare(
      "delete cartItem where giftID=3 and cartID=%d",
      $cartID));
  }

  $wpdb->query($wpdb->prepare(
    "update cart set tip=%s where id=%d",
    $tip_rate, $cartID));
  cart_update_time($cartID);
}

function cart_clear($cartID) {
  global $wpdb;
  $wpdb->query($wpdb->prepare(
    "UPDATE cartItem SET quantity=0, price=0, ref=NULL, event_id=NULL WHERE cartID=%d", $cartID));

  // When the cart is emptied, also remove all applied coupons and donor info
  cart_remove_discount($cartID);
  update_cart_data_sparse($cartID, 'donor', NULL);
  update_cart_data_sparse($cartID, 'event_id', NULL);
  cart_set_tip($cartID, CART_TIP_DEFAULT);
  cart_update_time($cartID);
}

// Return a numeric tip amount (to cents) from a tip rate ("10%") and amount
function calculate_tip($tip, $amount) {
  if ($tip == "0" || $tip === 0)
    return 0;

  // match a percentage
  $matches = array();
  if (preg_match("/^(\d{0,3})(\.\d{1,2})?%$/", $tip, $matches)) { 
    $p = $matches[1];
    if (isset($matches[2]))
      $p .= "." . $matches[2];
    $pct = floatval($p);
    $tip = ($pct * $amount) / 100.0;
  } else {
    $tip = from_money($tip);
  }
  return round($tip, 2);
}

// Returns a TIP RATE not a tip amount - in other words, a string like "10%"
function get_tip($cartID) {
  global $wpdb;

  $tip = $wpdb->get_var($wpdb->prepare(
    "select tip from cart where id=%d",
    $cartID));
  if ($tip === "0")
    return 0;
  if (empty($tip))
    $tip = CART_TIP_DEFAULT;
  return $tip;
}

// $cartID = 0 (NEW) or an ID
// $cards = an object with parameters
function cart_add_gift_certificates($cartID, $cards) {
  global $wpdb;

  $cards = (object)$cards;

  // Use the current cart ID?
  if (empty($cartID))
    $cartID = get_cart(TRUE);

  // Can't buy less than 1
  if ($cards->quantity <= 0)
    $cards->quantity = 1;

  dc($cartID, "Add GC: $cards->itemID / $cards->quantity x $$cards->price / $cards->message");

  // insert_donation_giver($cards->email, $cards->first_name, $cards->last_name);
  $details = array();
  if (!empty($cards->message))
    $details['message'] = $cards->message;
  if ($cards->recipient !== NULL)
    $details['emailTo'] = json_encode($cards->recipient);
  if ($cards->mailTo !== NULL)
    $details['mailTo'] = json_encode($cards->mailTo);

  // Update an existing item OR find one that can be re-used
  $item_id = $cards->itemID;
  if ($item_id == 0) {
    $item_id = $wpdb->get_var($wpdb->prepare(
      "SELECT id FROM cartItem 
       WHERE cartID=%d AND giftID=%d AND quantity=0",
       $cartID, CART_BUY_GC));

    // Clear old recipients
    if ($item_id > 0) {
      dc($cartID,"re-using cart item $item_id");
      $wpdb->query($wpdb->prepare(
        "DELETE from cartItemDetails WHERE cartItemID=%d",
        $item_id));
    }
  }

  if ($item_id != NULL) {
    // Update an old, unused gift card
    $wpdb->update('cartItem', array(
      'price' => $cards->price,
      'quantity' => $cards->quantity),
      array('id' => $item_id));
  } else {
    // Insert a new gift card
    $wpdb->insert('cartItem', array(
      'cartID' => $cartID,
      'giftID' => CART_BUY_GC,
      'price' => $cards->price,
      'quantity' => $cards->quantity));

    $item_id = $wpdb->insert_id;
  }

  // Update messaging if necessary
  if (count($details) > 0) {
    $details['cartItemID'] = $item_id;
    $c = $wpdb->update('cartItemDetails', 
      $details,
      array('cartItemID' => $item_id));
    if ($c == 0) {
      $wpdb->insert('cartItemDetails', $details);
    }
  }

  dc($cartID, "added GC as cart item $item_id");
  cart_update_time($cartID);
}

function cards_purchased_shortcode() {
  global $wpdb;

  $id = get_cart(FALSE);
  $cartID = decrypt_cart($_REQUEST['cid']);
  if (empty($cartID))
    return "";

  $gcs = $wpdb->get_results($wpdb->prepare(
    "select da.* from donationAcct da
      join donationAcctTrans dat on dat.donationAcctId=da.id and dat.paymentID != 0 AND dat.amount > 0
      join cart cart on cart.id=%d and IFNULL(cart.paymentID,0)=dat.paymentID
      where da.donationAcctTypeId=6",
    $cartID));

  if (count($gcs) == 0)
    return "";

  if (count($gcs) > 1)
    $s .= "We're sure your Impact Cards will be a hit.  If you'd like, you can click here to print and deliver them yourself:<br>";
  else
    $s .= "We're sure your Impact Card will be a hit.  If you'd like, you can click here to print and deliver it yourself:<br>";

  foreach ($gcs as $gc) {
    $p = json_decode($gc->params);
    
    $s .= '<a style="margin-left: 30px;" class="link" target="_new" href="' . SITE_URL . '/card/' . $gc->code . '">';
    $s .= "view or print #{$gc->code} (\$$gc->balance)";
    $s .=  '</a>';
    if ($p->recipient->first_name != NULL) {
      $s .= " for {$p->recipient->first_name} {$p->recipient->last_name}";
    }
    $s .= '<br>';
  }

  return "$s<br>";
}
add_shortcode('cards_purchased', 'cards_purchased_shortcode');

function get_gift_by_id ($gid=0,$wheres='') {
  global $wpdb;
  $sql = $wpdb->prepare(
    "SELECT * FROM gift WHERE id=%d ".($wheres==''?'':'AND '.$wheres),$gid);
  //pre_dump($sql);
  return $wpdb->get_row($sql);
}

function get_agg_var_gift ($tgi) {
  global $wpdb;

  if ($tgi == CART_GIVE_ANY)
    return CART_GIVE_ANY;

  // validate if parent gift not agg/var gift
  $tg = get_gift_by_id($tgi,'towards_gift_id=0');
  if (empty($tg)) return false;  

  if ($tg->varAmount)
    return $tgi;

  // look for existing agg var gift
  $gid = $wpdb->get_var($wpdb->prepare("SELECT id FROM gift 
    WHERE unitAmount=".AVG_UNIT_AMOUNT." AND varAmount=1 AND towards_gift_id=%d", $tg->id));    

  if (empty($gid)) { //insert new one  if none exists
    $sql = $wpdb->prepare(
      "INSERT INTO gift (unitAmount, varAmount, towards_gift_id, blog_id, displayName) 
        VALUES(".AVG_UNIT_AMOUNT.", 1, %d, %d, %s) ", $tg->id, $tg->blog_id, AVG_NAME_PREFIX.$tg->displayName); 
    $wpdb->query($sql);     
     $gid = $wpdb->get_var($wpdb->prepare("SELECT id FROM gift 
      WHERE unitAmount=".AVG_UNIT_AMOUNT." AND varAmount=1 AND towards_gift_id=%d", $tg->id));    
  }
  return $gid;
}

function get_avg_tgi($gid, $item=false, $arr=false) {
  global $wpdb;
  if ($item) {
    $sql = $wpdb->prepare(
      "SELECT g.* FROM gift g JOIN gift g2
      WHERE g2.id=%d AND g2.unitAmount=".AVG_UNIT_AMOUNT." 
      AND g2.varAmount=1 AND g.id = g2.towards_gift_id",$gid);
    $ret = $wpdb->get_row($sql,($arr?ARRAY_A:OBJECT));  
  } else {
    $sql = $wpdb->prepare(
      "SELECT towards_gift_id FROM gift 
      WHERE id=%d AND unitAmount=".AVG_UNIT_AMOUNT." 
      AND varAmount=1 AND towards_gift_id>0",$gid);
    $ret = $wpdb->get_var($sql);
  }
  return $ret;
}

function is_avg($gid) {
  return intval(get_avg_tgi($gid)) > 0;
}

function cart_add_avg($cart_id, $params) { // pre payment cart add
  $avg_arr = explode("|",$params);
  $avg = new stdClass;
  $avg->towards_gift_id = intval($avg_arr[0]); 
  $avg->amount = intval($avg_arr[1]); 
  $avg->event_id = intval($avg_arr[2]); 

  $tg = get_gift_by_id($avg->towards_gift_id);
  if (empty($tg)) return;

  $avg->id = get_agg_var_gift($avg->towards_gift_id); 
  if (empty($avg->id)) return;

  //

  cart_add($cart_id, $avg->id, 1, false, $avg->amt, NULL, NULL, 0, NULL, $avg->event_id);
}

function div_full_avg ($cart_id, $avg_amt, $tg_amt, $tg_id, $event_id) {
  $full_count = floor($avg_amt / $tg_amt);    
  $left_amt = $avg_amt - ($tg_amt * $full_count);
  if ($full_count > 0) {
    cart_add($cart_id, $tg_id, $full_count, false, NULL, NULL, NULL, 0, NULL, $event_id);    
  }
  return $left_amt;  
}

////



function cart_add($cartID, $giftID, $quantity = 1, $abs = false, $price = NULL, $ref = NULL, 
  $cartItemID = NULL, $recipientID = 0, $message = NULL, $event_id = NULL, $blog_id = 1) {
  global $wpdb;

  if ($cartID === NULL)
    $cartID = get_cart();
  if ($event_id < 0)
    $event_id = 0;

  // Handle special-case items in different routines
  switch ($giftID) {
    case CART_BUY_GC:
      // gc update quantity only -- insert is handled elsewhere
      return cart_add_gift_certificates($cartID, array(
        'price' => $price,
        'quantity' => $quantity,
        'itemID' => $cartItemID
      ));
    case CART_USE_GC:
      return cart_add_discount($cartID, $price, $ref);
    case CART_TIP: return; // No longer used

    case CART_PLEDGE:
      // TODO: Can this fundraiser actually accept pledges?

      // Replace other pledges in cart.
      $wpdb->query($wpdb->prepare(
        "update cartItem set quantity=0 where cartID=%d and giftID=%d and event_id=%d",
        $cartID, CART_PLEDGE, $event_id));

      $gift = $wpdb->get_row($wpdb->prepare( 
        "SELECT * from gift WHERE id=%d", $giftID ), ARRAY_A);
      $gift = array(
        'varAmount' => 1,
        'unitAmount' => $price
      );
      break;

    case CART_GIVE_ANY:
    default:
      $gift = $wpdb->get_row($wpdb->prepare( 
        "SELECT * from gift WHERE id=%d", $giftID ), ARRAY_A);

      if(intval($event_id) > 0) {
        switch_to_blog(1);  
        $event_tags = get_fr_tags($event_id);
        $event_restricted = trim($event_tags) != '';

        restore_current_blog();
        dc($cartID, "event $event_id restricted? $event_restricted");
        if ($event_restricted && $giftID <> CART_GIVE_ANY) { // if event restricted to tag gifts
          $gift_tags = array_map('trim', explode(",",$gift['tags']));
          $event_tags = array_map('trim', explode(",",$event_tags));
          $shared_tags = array_intersect($gift_tags,$event_tags); 
          dc($cartID, "gift_tags: " . print_r($gift_tags,TRUE) . " / event_tags: " . print_r($event_tags, TRUE));

          // if gift not in event tags, remove event association
          if (!is_array($shared_tags) || count($shared_tags) == 0) {
            dc($cartID, "removing event $event_id");
            $event_id = 0;
          }      
        }
      }
      break;
  }

  if ($gift == NULL || count($gift) == 0) return false;
  if (empty($price) && $gift['varAmount'] == 1) {
    $amt = sanitize_text_field($_REQUEST['amount']);
    $price = from_money($amt);
  }
  if (empty($price)) 
    $price = $gift['unitAmount'];
  if (empty($price)) return NULL;
  if ($gift['varAmount'] == 1) {
    if ($price <= 0)
      $price = from_money($gift['unitAmount']);
    else if ($price > 50000)
      $price = 50000;
  }

  $item = array('gift_id' => $giftID, 'price' => $price);
  $mg_id = get_event_match($event_id, $item);
  $avg_tgi = get_avg_tgi($giftID); // if agg var gift

  // pre_dump("$price $event_id $giftID $avg_tgi");
  if ($cartItemID != NULL) { // id is known, just updating
    if($quantity ==0 || $price == 0){
      cart_remove($cartID, $cartItemID);
    } else {

      if ($avg_tgi > 0) { // if agg var gift

      }

      $s = array($wpdb->prepare("quantity=%d", $quantity));
      if ($price != NULL)
        $s[] = $wpdb->prepare("price=%f", $price);
      
      if ($blog_id > 1) {
        $s[] = $wpdb->prepare("blog_id=%d", $blog_id);
        $s[] = $wpdb->prepare("event_id=0");
        $event_id = 0;
      } else {
        $s[] = "blog_id=1"; //$wpdb->prepare("blog_id=1");
        if ($event_id !== NULL && $event_id > 0) 
          $s[] = $wpdb->prepare("event_id=%d", $event_id);
        else
          $s[] = "event_id=0"; //$wpdb->prepare("event_id=0");
        $blog_id = 1;
      }
      
      $sql = $wpdb->prepare(
        "UPDATE cartItem SET " . implode(',', $s) . " WHERE id=%d", $cartItemID);
      $wpdb->query($sql);
      cart_update_time($cartID);
      return true;
    }
  }

  // Generate an INSERT
  $sql = $wpdb->prepare(
    "INSERT INTO cartItem (cartID, giftID, price, quantity, matchingAcct, ref, event_id, blog_id) "
      ."VALUES (%d, %d, %f, %d, %d, %s, %d, %d)",
    $cartID, $giftID, $price, ($avg_tgi > 0 ? 1 : $quantity), $mg_id, $ref, $event_id, $blog_id);

  // Some gifts must be kept as separate rows
  if ($avg_tgi > 0 || $gift['varAmount'] != 1) {
    if ($quantity < 0) $q = "AND quantity > 0";

    $item = $wpdb->get_row($wpdb->prepare(
      "SELECT id,quantity,price,event_id FROM cartItem WHERE cartID=%d AND giftID=%d $q", $cartID, $giftID));

    if ($item != NULL) {
      // Generate an UPDATE instead of the INSERT
      if (!$abs) $quantity += $item->quantity;

      if ($avg_tgi > 0) { // if agg var gift
        $price += $item->price;
      }

      if ($quantity < 0) $quantity = 0;
      if (empty($event_id)) $event_id = $item->event_id;

      $sql = $wpdb->prepare("UPDATE cartItem SET price=%f, quantity=%d, event_id=%d, blog_id=%d, matchingAcct=%d WHERE id=%d",
        $price, ($avg_tgi > 0 ? 1 : $quantity), $event_id, $blog_id, $mg_id, $item->id);

    }
  } else {
    //reuse if possible
    $item_id = $wpdb->get_var($wpdb->prepare(
      "SELECT id FROM cartItem WHERE cartID=%d AND giftID=%d AND quantity=0 ", $cartID, $giftID));

    if($item_id != NULL)
      $sql = $wpdb->prepare("UPDATE cartItem SET 
        price=%f, quantity=1, event_id=%d, blog_id=%d, matchingAcct=%d WHERE id=%d",
        $price, $event_id, $blog_id, $mg_id, $item_id);
  }

  $wpdb->query($sql);
  cart_update_referrer($cartID);
  cart_update_time($cartID);
}

function cart_update_referrer($cartID) {
  global $wpdb;
  //pre_dump($_COOKIE);

  $referrer = 0;
  if (!empty($_COOKIE['referrer'])) $referrer = absint($_COOKIE['referrer']);

  if ($referrer>0) {
     $sql = $wpdb->prepare("UPDATE cart SET referrer=%d WHERE id=%d",$referrer,$cartID);
    $wpdb->query($sql);  
  }    
}

function cart_remove($cartID, $cartItemID) {
  global $wpdb;

  $sql = $wpdb->prepare(
    "UPDATE cartItem SET quantity=0, price=0, ref=NULL, event_id=NULL
    WHERE cartID=%d ", $cartID
  );

  if (is_array($cartItemID)) {
    $sql .= "AND id IN (" . implode(',', array_map('intval', cartItemID)) . ")";
  } else {
    $sql .= $wpdb->prepare('AND id = %d', $cartItemID);
  }

  dc($cartID, "Removed: " . print_r($cartItemID, true));
  $wpdb->query($sql);
  cart_update_time($cartID);
  return true;
}

function cart_remove_discount($cartID) {
  global $wpdb;    

  $sql = $wpdb->prepare(
    "UPDATE cartItem SET quantity=0, price=0, ref=NULL, event_id=NULL
    WHERE cartID=%d AND price<0 AND ref IS NOT NULL", $cartID, $cartItemID);
  
  $wpdb->query($sql);
  cart_update_time($cartID);
  return true;    
}

function cart_merge($dstID, $srcID, $itemID) {
  global $wpdb;
  
  $item = $wpdb->get_row($wpdb->prepare(
    "SELECT giftID,quantity FROM cartItem
    WHERE cartID=%d AND id=%d",$srcID,$itemID),ARRAY_A);
  $giftID = $item['giftID'];
  $quantity = $item['quantity'];
  
  switch ($giftID) {
    case CART_TIP: break; //not allowed
    case CART_USE_GC: break; //not allowed
    case CART_BUY_GC:
      $wpdb->query($wpdb->prepare(
        "UPDATE cartItem SET cartID=%d WHERE cartID=%d AND id=%d",
        $dstID,$srcID,$itemID));
      cart_update_time($dstID);
      cart_update_time($srcID);
      break;
    default:
      $cartItemID = gift_in_cart($dstID,$giftID);
      if ($cartItemID!=null) { //already in cart
        cart_add($dstID,$giftID,$quantity);
        cart_remove($srcID, $itemID);
      } else { //not in cart
        $wpdb->query($wpdb->prepare(
          "UPDATE cartItem SET cartID=%d WHERE cartID=%d AND id=%d",
          $dstID,$srcID,$itemID));
        cart_update_time($srcID);
        cart_update_time($dstID);
      }
      break;
  }
  
  return true;
}

function gift_in_cart($cartID, $giftID) {
  global $wpdb;
  return $wpdb->get_var($wpdb->prepare(
    "SELECT id FROM cartItem WHERE cartID=%d AND giftID=%d",
    $cartID, $giftID));
}

function cart_to_array($cartID, $assoc=false) {
  global $wpdb;
  $cart = $wpdb->get_results($wpdb->prepare(
    "SELECT i.*,g.title,g.displayName,g.blog_id,det.message,i.blog_id as item_blog_id FROM cartItem i 
     LEFT JOIN gift g ON g.ID=i.giftID 
     LEFT JOIN cartItemDetails det ON det.cartItemID=i.id
     WHERE i.cartID = %d AND quantity > 0",
     $cartID), ARRAY_A);

  if (!$assoc) {
    return $cart;
  } else {
    $cart_assoc = array();
    foreach ($cart as $item) {
      $cart_assoc[$item['id']] = $item;
    }
    return $cart_assoc;
  }
}

function array_to_cart($items) {
  global $wpdb;
  foreach ($items as $item) {
    cart_add($item['cartID'], $item['giftID'], $item['quantity'], true);
  }
}

function form_result($status = "success", $data = NULL, $fields = NULL) {
  $result = new stdClass;
  $result->status = eor($status, "success");
  $result->data = $data;
  if ($fields !== NULL) {
    if (is_array($fields))
      $result->missing = $fields;
    else
      $result->missing[] = $fields;
  }
  return $result;
}
function form_error($message, $fields = NULL) {
  return form_result("error", $message, $fields);
}
function form_success($message = "") {
  return form_result("success", $message);
}
function cart_error($message, $fields = NULL) {
  return form_result("error", $message, $fields);
}
function cart_success($message) {
  return form_result("success", $message);
}

function is_error_result($result) {
  return is_object($result) && $result->status == 'error';
}


function process_cart_discount(&$cart, $code) {
  $ret = cart_apply_discount($cart->id, $code);
  if (is_numeric($ret))
    return;
  return cart_error($ret);
}

function cart_apply_discount($cartID, $code, $item_id = NULL) {
  dc($cartID, "apply discount $code");
  
  //validate the code
  if(strlen($code) != 10){
    if (strlen($code) > 10)
      return CART_USE_GC_INVALID_PAYPAL;
    return CART_USE_GC_INVALID;
  }

  global $wpdb, $current_user;
  get_currentuserinfo();
  
  $daID = get_acct_id_by_code($code);
  if (empty($daID)) {
    return CART_USE_GC_INVALID;
  }

  $applied = 0;
  $acct = get_donation_account($daID);
  if ($acct->expired) {
    return CART_USE_GC_EXPIRED;
  }

  $daType = $acct->donationAcctTypeId;
  switch ($daType) {
    case ACCT_TYPE_DISCOUNT:
      $disc = get_refcode_details($code);
      if ($disc == NULL)
        return CART_USE_GC_INVALID;
      $applied = -$disc->amount;
      $message = $disc->message;
      break;

    case ACCT_TYPE_GIVE_ANY:
    case ACCT_TYPE_MATCHING:
      if (!current_user_can('level_2'))
        return CART_USE_GC_INVALID;

      $params = get_acct_params($acct->params);
      if (isset($params->tip_rate)) {
        $tip_rate = round($params->tip_rate * 100) . "%";
        cart_set_tip($cartID, $tip_rate);
      }

      list($owner, $type) = get_donor_info_by_acct($daID);
      update_cart_data($cartID, 'donor', $owner['ID']);

      $message = "Pre-paid donation";
      // fall through
    case ACCT_TYPE_GENERAL:
    case ACCT_TYPE_GIFT:
    case ACCT_TYPE_OPEN_CODE:
      $balance = $acct->balance;
      if ($balance === NULL)
        return CART_USE_GC_INVALID;

      if ($balance <= 0) {
        if ($daType == ACCT_TYPE_GIVE_ANY) {
          $applied = -10000;
        } else {
          return CART_USE_GC_INSUFFICIENT;
        }
      } else {
        $applied = -floatval($balance);
      }
      break;

    default:
      return CART_USE_GC_INVALID;
  }
  //--up to this point gc is good to go--

  if ($item_id === NULL) {
    $item_id = 0;
    $cart = cart_to_array($cartID);
    foreach($cart as $item) {
      if($item['ref'] == $code) { //gc is applied
        $item_id = $item['id'];
        break;
      }
    }
  }


  if ($item_id == 0) { //if not added
    //reuse if possible
    $item_id = $wpdb->get_var($wpdb->prepare(
      "SELECT id FROM cartItem WHERE cartID=%d AND giftID=%d AND quantity<2",
      $cartID, CART_USE_GC));
  }

  if (empty($item_id)) {
    $wpdb->query($wpdb->prepare(
      "INSERT INTO cartItem (cartID, giftID, price, quantity, ref) 
       VALUES (%d, %d, %f, 1, %s)", 
      $cartID, CART_USE_GC, $applied, $code));
    $item_id = $wpdb->insert_id;
  } else {
    $wpdb->query($sql = $wpdb->prepare(
      "UPDATE cartItem SET price=%f, quantity=1, ref=%s, cartID=%d WHERE id=%d",
      $applied, $code, $cartID, $item_id));
  }

  $wpdb->query($wpdb->prepare(
    "DELETE FROM cartItemDetails WHERE cartItemID=%d",
    $item_id));
  cart_update_time($cartID);
  if (!empty($message)) {
    $wpdb->query($wpdb->prepare(
      "INSERT INTO cartItemDetails (cartItemID,message)
       VALUES (%d,%s)",
      $item_id, $message));
  }

  return $applied;
}

function finalize_cart(&$cart, $id = NULL) {
  if ($id == NULL)
    $id = get_cart();
  if ($cart == NULL) {
    $cart = new stdClass;
    $cart->id = $id;
  }

  $cart->data = get_cart_data($cart->id);
  $cart->items = cart_to_array($cart->id);
  $cart->is_test = FALSE;
  $cart->user_id = get_cart_user($cart->id);
  get_cart_total($cart);
}

function get_cart_total(&$cart, $donation_only=false) {
  global $wpdb, $gc_user;

  if ($cart->donor === NULL)
    $cart->donor = get_cart_donor($cart->id);
  if ($cart->items === NULL)
    $cart->items = cart_to_array($cart->id);
  // Add on the tip
  if ($cart->tip_rate === NULL)
    $cart->tip_rate = get_tip($cart->id);

  // Nothing left? clear it out
  if (count_cart_items($cart->id) == 0) {
    cart_clear($cart->id);
    return 0;
  }

  $total = 0.0;
  $discount_items = array();

  foreach ($cart->items as $item){
    switch ($item['giftID']) {
      case CART_USE_GC:
      $discount_items[] = $item; //store active applied gc
      break;

      case CART_TIP:
      case CART_PLEDGE:
        break;

      default:
        if ($item['quantity'] > 0)
          $items++;
        $total += $item['quantity'] * $item['price'];
    }
  }
  $cart->tip = calculate_tip($cart->tip_rate, $total);

  if ($donation_only) return $total;

  if (!is_object($cart->donor)) {
/*
    global $bp;
    $uid = $bp->loggedin_user->id;
    update_cart_data_sparse($cart->id, 'donor', $uid);
*/
  }

  foreach ($discount_items as $discount_item) {
    if ($items == 0) {
      cart_remove($cart->id, $discount_item['id']);
      continue;
    }

    $id = $discount_item['id'];

/* Steve: disabled because a) we don't support multiple discounts yet
   and b) not sure that removing the last one is the right behavior if we did
    if ($discounts >= $total + $tip) {
      //enough discount already, remove remaining discounts
      cart_remove($cart->id, $id);
      continue;
    }
*/

    //reapply full balance
    $discount = cart_apply_discount($cart->id, $discount_item['ref'], $id);
    if (!is_numeric($discount)) {
      // discount caused a problem - remove it
      cart_remove($cart->id, $id);
      continue;
    }
    $cart->tip_rate = get_tip($cart->id);
    $cart->tip = calculate_tip($cart->tip_rate, $total);

    if (-$discount > $total + $cart->tip) {
      $discount = -($total + $cart->tip); //reduce discount to remainder total

      //update the discount to match total
      $wpdb->query($sql = $wpdb->prepare(
        "UPDATE cartItem SET price=%f WHERE id=%d ",
        $discount, $id));
      cart_update_time($cart->id);
    }

    $discounts += $discount;
  }

  // Re-load the cart items  TODO: do this by modifying cart->items instead
  $cart->items = cart_to_array($cart->id);
  $cart->donor = get_cart_donor($cart->id);

  // Discounts may affect cart tip
  $cart->total = $total + $cart->tip + $discounts;
  return $total;
}

function validate_paid_cart($order, $stored_cart) {
  $cart = $order->cart;
  $discounts = $order->discounts;
  dp("VALIDATING CART #$cart->id");
  $valid = true;

  if ($stored_cart == NULL || count_cart_items($cart->id) == 0) {
    $valid = false;
    dp("STORED CART NOT FOUND OR EMPTY");
  }
  if (!is_array($cart->items) || count($cart->items) == 0) {
    $valid = false;
    dp("PASSED CART IS EMPTY");
  }
  if ($valid) {
    foreach ($cart->items as $item) {
      if(!empty($stored_cart[$item->id])){
        $stored_cart[$item->id]['found'] = true;
        $stored_item = $stored_cart[$item->id];
        dp("ITEM #$item->id ACCOUNTED");
        if($stored_item['giftID'] == $item->gift_id){
          dp(" gift OK #$item->gift_id");
        } else {
          $valid = false;
          dp(" gift WRONG #$item->gift_id stored:".$stored_item['giftID']);
        }
        if($stored_item['price'] == $item->price){
          dp(" amt OK $$item->price");
        } else {
          $valid = false;
          dp(" amt WRONG $$item->price stored $".$stored_item['price']);
        }
        if($stored_item['quantity'] == $item->quantity){
          dp(" qty OK $item->quantity");
        } else {
          $valid = false;
          dp(" qty WRONG $item->quantity stored:".$stored_item['quantity']);
        }
      } else {
        dp("PASSED ITEM #$item->id UNACCOUNTED");
        $valid = false;
      }
    }
    if(is_array($discounts))
      foreach ($discounts as $disc) {
        if(!empty($stored_cart[$disc->id])
          && empty($stored_cart[$disc->id]['found'])){
          $stored_cart[$disc->id]['found'] = true;
          $stored_item = $stored_cart[$disc->id];
          dp("DISCOUNT #$disc->id ACCOUNTED");
          if($stored_item['price'] == $disc->price){
            dp(" amt OK $$disc->price");
          } else {
            $valid = false;
            dp(" amt WRONG $".floatval($disc->price)." stored $"
            .floatval($stored_item['price']));
          }
        } else {
          dp("PASSED DISCOUNT #$disc->id UNACCOUNTED");
          $valid = false;
        }
      }

  }

  dp("------------------------------------");
  return $valid;
}

function get_free_checkout_donor($cartID) {
  global $wpdb;

    //getting GC USE item on the cart
    $gcItemID = $wpdb->get_var($wpdb->prepare("SELECT id
      FROM cartItem WHERE cartID=%d AND giftID=2 ORDER BY id",$cartID));

    if($gcItemID==null) return null;

    $gcuItemDetails = $wpdb->get_row($wpdb->prepare("SELECT *
        FROM cartItemDetails WHERE cartItemID=%d",$gcItemID));

    return $gcuItemDetails->recipientID;
}

function save_free_checkout_donor($cartID) {
  global $wpdb;
  if(!empty($_POST['donor_email']) && !empty($_POST['donor_first_name'])
    && !empty($_POST['donor_last_name'])) {

    //getting GC USE item on the cart
    $gcItemID = $wpdb->get_var($wpdb->prepare("SELECT id
      FROM cartItem WHERE cartID=%d AND giftID=2 ORDER BY id",$cartID));
    dc('found gc use item#'.$gcItemID);

    //there is no GC being used here so return, its pointless
    if($gcItemID==null) return null;

    //insert a new donor using data from free checkout form
    $newDonorID = insert_donation_giver(
      $_POST['donor_email'],
      $_POST['donor_first_name'],
      $_POST['donor_last_name']);

    if($newDonorID==NULL) return null;
    dc('free checkout donor#'.$newDonorID.' : '.$_POST['donor_email']);

    //is there a cart item details for the GC USE
    $gcuItemDetails = $wpdb->get_row($wpdb->prepare("SELECT *
        FROM cartItemDetails WHERE cartItemID=%d",$gcItemID));

    //store the donor ID in the GC USE details
    if($gcuItemDetails==NULL) {
      $wpdb->query($wpdb->prepare("INSERT INTO cartItemDetails
        (cartItemID,recipientID) VALUES (%d,%d)",$gcItemID,$newDonorID));
      dc('storing donor to new gc use details #'.$wpdb->insert_id);
    } else {
      dc('storing donor to gc use details #'.$gcuItemDetails->id);
      $wpdb->query($wpdb->prepare("UPDATE cartItemDetails
        SET recipientID=%d WHERE cartItemID=%d ",$newDonorID,$gcItemID));
    }
    return $newDonorID;
  }
}

function process_free_checkout(&$cart, $args) {
  if ($cart == NULL || count($cart->items) == 0)
    return cart_error('Sorry, we could not complete your payment.');

  // Better error message for case where cart is now not empty?
  if ($cart->total > 0)
    return cart_error('Sorry, we could not complete your payment.');

  $order = new Donation(print_r($cart->items, TRUE));
  $order->payment = new stdClass;
  $order->payment->status = 'Paid';
  $order->payment->gross = $cart->total;
  $order->payment->id = 'SYI-FREE-'.date('Y-m-d H:i:s');
  $order->payment->method = 'GC';
  $order->payment->memo = null;

  $order->donor = get_cart_donor_info($cart->id);
  if ($order->donor === NULL)
    return cart_error('Please provide your contact information.');

  $order->data = $cart->id;

  // BUILD THE CART
  $order->cart = new stdClass;
  $order->cart->id = $cart->id;
  $order->cart->items = array();
  $order->discounts = array();

  $total = 0;

  foreach ($cart->items as $item) {
    if ($item['quantity'] <= 0)
      continue;

    $item_id = $item['id'];
    $gift_id = $item['giftID'];
    $ref = $item['ref'];

    $i = new stdClass;
    $i->id = $item_id;
    $i->gift_id = $gift_id;
    $i->price = $item['price'];
    $i->quantity = $item['quantity'];
    $i->ref = $ref;

    switch ($gift_id) {
      case CART_TIP: // No longer used
      case CART_PLEDGE:
        break;

      case CART_USE_GC:
        $order->discounts[] = $i;
        break;

      default:
        $total += $i->price * $i->quantity;
        $order->cart->items[] = $i;
        break;
    }
  }

  $order->payment->tip = $cart->tip;
  $order->payment->tipped = $total;
  $order->payment->gross = $total + $order->payment->tip;

  /*
  // Make sure the cart is owned by the donor, even if we're just impersonating
  if (is_numeric($order->donor)) {
    $donor = get_donor_by_id($order->donor);

    global $wpdb;
    $wpdb->update('cart', 
      array( 'userID' => $order->donor->user_id ),
      array( 'id' => $cart->id ));
  }
*/

  // Pledges are handled before this, so it's possible to get here with
  // no items to pay for.  In that case, just skip the order processing
  if (count($order->cart->items) > 0)
    processOrder($order);

  return cart_success(get_thankyou_url($cart->id));
}

function try_login($args, &$result = NULL) {
  global $error_signin;

  if (is_user_logged_in())
    return;

  $cartID = get_cart();
  $error_signin = '';
  $email = trim($args['account']);

  if ($args['register'] == 0) {
    if (validate_email($email)) {
      $user = get_user_by_email($email);
      if ($user) 
        $username = $user->user_login;
      else 
        $username = '_invalid_';
    } else {
      $username = trim(sanitize_user($email));
      if (!validate_username($username)) {
        $error_signin = 'Please enter a valid e-mail address.';
        $result = cart_error($error_signin, "email");
        return false;
      }
    }

    if (trim($username) == '') {
        $error_signin = 'Please sign in with your e-mail address.';
        $result = cart_error($error_signin, "email");
        return false;
    }

    $user = wp_signon(array(
      'user_login'=>$username,
      'user_password'=>$args['password'],
      'remember'=>true), false);
    if (is_wp_error($user)) {
      if (isset($user->errors['incorrect_password'])) {
        $error_signin = "Sorry, the username/e-mail and password you provided don't match!";
        $result = cart_error($error_signin);
      } else if (isset($user->errors['invalid_username'])) {
        $error_signin = "Sorry, I can't find anyone by that username/e-mail address.";
        $result = cart_error($error_signin);
      } else {
        $error_signin = $user->get_error_message();
        $result = cart_error($error_signin);
        $result->wp_error = $user;
      }
      return false;
    } 

    auto_wp_login($user->ID);
    return $user->ID;
  }

  // Registering a new user

  if (!validate_email($email)) {
    $error_signin = 'Please enter a valid e-mail address.';
    $result = cart_error($error_signin, "email");
    return false;
  }
  $firstname = trim(sanitize_text_field($args['first_name']));
  $lastname = trim(sanitize_text_field($args['last_name']));
  if (empty($firstname) || empty($lastname)) {
    $error_signin = "Please enter your first and last name (we won't display your full name on the site, unless you choose to)";
    $result = cart_error($error_signin);
    return false;
  }
  $password = trim(sanitize_text_field($args['password']));
  // Let people sign in with a default password
  if ($result !== NULL)
    $password = eor($password, DEFAULT_PASSWORD);

  if ( empty($password) || false !== strpos( stripslashes($password), "\\" ) ) {
    $error_signin = "Please choose another password - that one has some invalid symbols";
    $result = cart_error($error_signin, "password");
    return false;
  }

  list($user, $user_id) = createWpAccount($email, $firstname, $lastname, '', $password, true);

  if ($user === false || $user_id == 0)  {
    global $error_wp_signin;
    if (empty($error_signin))
      $error_signin = $error_wp_signin;
    $result = cart_error($error_signin, "password");
    return false;
  }

  // SEND NEW USER MAIL
/*
  global $emailEngine;
      $args['username'] = $user[0];
      $user_id = $user[1];
      $emailEngine->sendMailSimple($args['username'],
        $args['email'], 'Welcome to SeeYourImpact!',
        array(
        '#user_nicename#' => as_html($args['username']),
        '#user_username#' => as_html($args['username']),
        '#user_password#' => as_html($args['password'])),
        'welcome.html');
      dc($cartID,'User successfully registered, welcome email sent: '
        .$args['email']." username:".$args['username']);
    }
*/

  auto_wp_login($user_id);
  return $user_id;
}

function get_cart_user($cartID,$object=false) {
  global $wpdb;
  $userID = $wpdb->get_var($sql = $wpdb->prepare(
    "SELECT userID FROM cart WHERE id = %d", 
    $cartID));
  if($object) 
    return get_userdata($userID);
  return $userID;
}

function get_user_donor($userID, $email='') {
  //look for user donor with matching email, if no match look for donor w/o user
  //but with email match and assign the donor to passed user

  global $wpdb;
 
  if (empty($userID))
    return NULL; 
  
  if (empty($email)) {
    //Look for donor already associated with the user
    $donorID = $wpdb->get_var($wpdb->prepare(
      "SELECT ID FROM donationGiver WHERE user_id=%d order by main desc,id asc",
      $userID));
    return $donorID;
  }

  //Look for donor already associated with the user and email
  $donorID = $wpdb->get_var($wpdb->prepare(
    "SELECT ID FROM donationGiver WHERE user_id=%d AND email=%s order by main desc,id asc",
    $userID,$email));

  if ($donorID == NULL) {
    //Look for user-less validated donor with the same email
    $donorID = $wpdb->get_var($wpdb->prepare(
      "SELECT ID FROM donationGiver "
      ."WHERE validated=1 AND email=%s AND user_id=0 order by main desc,id asc",$email));

    if ($donorID != NULL) {
      //Attach the userless donor to the user
      $wpdb->query($wpdb->prepare(
        "UPDATE donationGiver SET user_id=%d WHERE ID=%d",$userID,$donorID));
    }
  }
  
  return $donorID;
}

function count_saved_items($userID=0) {
  global $cartID;
  global $wpdb;
  global $current_user;
  if(get_blog_option(1, 'saved_cart')!=1){return 0;}
  if($userID==0) {
    if(!is_user_logged_in()){return 0;}
    get_currentuserinfo();
    $userID = $current_user->ID;
  }
  $cart_ids = $wpdb->get_col($wpdb->prepare(
      "SELECT id FROM cart WHERE userID=%d and status='active' AND id<>%d",
      $userID,$cartID));
  $total = 0;
  foreach($cart_ids as $cart_id) { $total += count_cart_items($cart_id); }
  return $total;
}


function get_donation_from_cart($cartID) {
  global $wpdb;
 
  $id =  $wpdb->get_var($sql = $wpdb->prepare(
    "SELECT d.donationID
     FROM cart c JOIN donation d ON (d.paymentID=c.paymentID AND c.paymentID>0)
     WHERE c.id=%d",$cartID));
  return intval($id);
}

function is_gc_in_cart($cartID) {
  global $wpdb;

  $exists = $wpdb->get_var($wpdb->prepare(
    "SELECT id FROM cartItem WHERE giftID=%d AND cartID=%d
    AND price>0 AND quantity>0",CART_BUY_GC,$cartID));
    
  if($exists!=NULL) return true;
  return false;

}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//OUTPUT FUNCTIONS

function cart_update_time($cartID=null, $mine = TRUE) {
  global $wpdb;
  if (!empty($cartID)) {
    $wpdb->query($wpdb->prepare(
      "UPDATE cart SET lastUpdated=NOW() WHERE id=%d",$cartID));
  }

  if ($mine)
    restore_cart($cartID);
}

function display_saved_cart() {
  global $cartID;
  global $wpdb;
  global $current_user;
  
  if(!is_user_logged_in()){return;}
  
  get_currentuserinfo();

  $cart_ids = $wpdb->get_col(
    $wpdb->prepare(
      "SELECT id FROM cart WHERE userID=%d AND status='active' AND id<>%d ",
      $current_user->ID,$cartID));
  
  foreach($cart_ids as $cart_id) {
    $cart = cart_to_array($cart_id);
    foreach($cart as $item) {

      $giftID = $item['giftID'];

      switch ($giftID) {
        case CART_TIP: break; //not allowed
        case CART_USE_GC: break; //not allowed
        default:
          display_cart_item($item, true, true);
          break;
      }
    }
  }
}

function display_free_checkout_form() {
  global $errorMsg;
  global $current_user;
  global $gc_user;
  global $payment_method_titles;

  if ($gc_user != NULL) {
    $donor_first_name = stripslashes($gc_user['firstName']);
    $donor_last_name = stripslashes($gc_user['lastName']);
    $donor_email = stripslashes($gc_user['email']);
    $validated = $gc_user['validated'];
  } else {
    get_currentuserinfo();
    $donor_first_name = $current_user->first_name;
    $donor_last_name = $current_user->last_name;
    $donor_email = $current_user->user_email;
    $validated = is_user_logged_in();
  }

?><div class="payment-line">
<input type="submit" onclick="return validateFreeInput();" 
  name="submit" value="<?=$payment_method_titles['GC']?>" class="payment-button big-button button orange-button" style="width: 200px;"/>
<p id="free-error" class="error"><?=$errorMsg;?></p></div>
<? if (!$validated) {
  echo CART_CHECKOUT_FREE_NOTE2; ?>
  <p><input class="name-field first-name" type="text" placeholder="First name"
    name="donor_first_name" id="donor_first_name" maxlength="50" value="<?=$donor_first_name?>"/>
  <input class="name-field last-name" type="text" placeholder="Last name"
    name="donor_last_name" id="donor_last_name" maxlength="50" value="<?=$donor_last_name?>"/>
  <p></p>e-mail:<input class="email-field" type="text" placeholder="E-mail address"
    name="donor_email" id="donor_email" maxlength="50" value="<?=$donor_email?>"/></p>
<? } 

}

function display_form_error($result) {
  if ($result == null || $result->status != 'error')
    return FALSE;

  $result->data = str_replace('<strong>ERROR</strong>: ','', $result->data);

  ?><p class="error"><?= $result->data ?>
  <? do_action('after_form_error', $cart); ?>
  </p><?

  if (count($result->missing) > 0) {
    ?><script>$(function(){<?
    foreach ($result->missing as $m) {
      ?> $('#<?=$m?>, *[name="<?=$m?>"]').addClass('error-field'); <?
    }
    ?>});</script><?
  }

  return TRUE;
}

function display_checkout_buttons($hide = "") {
  global $payment_method_titles;

  $hide = explode(",",$hide);

/*
  if (GOOGLE_PAYMENT_ENABLED && array_search('GG',$hide) === FALSE) {
    ?><input id="pay-GG" type="submit" class="payment-button medium-button orange-button button conv" 
      name="submit" value="<?=$payment_method_titles['GG']?>" /><?
  }
*/

  if (PAYPAL_PAYMENT_ENABLED && array_search('PP',$hide) === FALSE) {
    ?><input id="pay-PP" type="submit" class="payment-button medium-button orange-button button conv" 
      name="submit" value="<?=$payment_method_titles['PP']?>" /><?
  }
}

function show_cart_link() {
  global $site_url;

  $cartID = get_cart(false);
  $count = count_cart_items();
  ?>
    <a class="cart-link" href="<?= SITE_URL ?>/cart/"><b>your cart</b> <span class="cart-count hidden"></span></a>
    <script type="text/javascript">
      $(function() {
        var c = intval($.cookie('CART'));
        if (c == 0)
          $(".cart-link").addClass('hidden');
        else {
          $(".cart-link").addClass('cart-full');
          $(".cart-count").removeClass('hidden');
          $(".cart-count").html('(' + c + ' gift' + (c != 1 ? 's)' : ')'));
        }
      });
    </script>
  <?

  return $count > 0;
}

function display_quantity_ddl($default='', $name='') {
  if ($default == '') {$default = 1;}
  $ret = '';
  $ret .= '<select id="'.$name.'" name="'.$name.'" >';
  for ($i = 1; $i <= 50; $i++) {
    $ret .= '<option value="'.$i.'" ' . selected($default, $i, false) 
        .'>'.$i.'</option>';
  }
  $ret .= '</select>';
  return $ret;
}

function display_tip_ddl($cartID, $amount, $default='', $name='') {
  $found = false;

  ?><select id="<?=$name?>" class="ev tip-selector" name="<?=$name?>"><?
  foreach (explode(",", CART_TIP_RATES) as $v) {
    $selected = ($default == "$v%");
    if ($selected) $found = true;

    $tip = calculate_tip("$v%", $amount);
    $label = as_money($tip) . " ($v%)";
    $value = ($name == 'tip' ? "$v%" : $tip);
    if ($value == "0%") {
      $value = "0";
      $label = "$0.00";
    }

    ?><option value="<?=$value?>" <? selected($selected) ?>><?= $label ?></option><?
  }
  ?></select><?

  if (!$found) 
    dc($cartID, 'Set tip rate not found in ddl: '.$default);

  return calculate_tip($default, $amount);
}

function display_gc_amount_ddl($name='gc_amount', $default=25, $attr=''){
  $amts = explode(",",CART_GC_AMOUNTS);
  ?><select id="<?=$name?>" name="<?=$name?>" <?=$attr?>><?
  foreach($amts as $amt){
    ?><option value="<?= $amt ?>"
    <? selected($amt, $default) ?>
    ><?=($amt == 0 ? 'Select Amount' : as_money($amt))?></option><?
  }
  ?></select><?
}

function display_cart_item($item, $readonly=false, $saved=false) {
  global $wpdb; //pre_dump($item);
  $event_id = absint($item['event_id']);
  $blog_id = absint($item['item_blog_id']); 
  $tg = get_avg_tgi($item['giftID'],true,true);

  if($event_id) {
    $campaign = get_post($event_id);
    $campaign_url = get_campaign_permalink($event_id); 
  } else {
    $campaign_url = '';  
  }

  if ($tg != null) { //agg var gift 

    $gift = $tg; //get gift data
    $gift_url = details_link($gift['id'],'',$campaign_url);
    $gift_tag = 'span';
    $gift['displayName'] = AVG_NAME_PREFIX.$gift['displayName'];
    $gift['varAmount'] = 1;
    $row_price = $item['price'] * $item['quantity'];

  } else { //not agg var gift

    //get gift data
    $gift = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM gift WHERE id = %d", $item['giftID']),ARRAY_A);
    $gift_url = details_link($gift['id'],'',$campaign_url);
    $gift_tag = ($gift['id'] == CART_GIVE_ANY && $event_id == 0) ? 'span' : 'a';
    if ($gift['varAmount'] == 1) {
      $row_price = $item['price'] * $item['quantity'];
    } else {
      $row_price = $gift['unitAmount'] * $item['quantity'];
    }

  } // endif agg var gift

  $show_gift_to = TRUE;

  if ($gift['id'] == CART_GIVE_ANY && $event_id > 0) {
    $gift_img = make_img(fundraiser_image_src($event_id), 60, 60);
    $name = get_campaign_owner_name($event_id);
    $gift_name = apply_filters('get_fundraiser_donation_item', $name);
    $gift['excerpt'] = 'Thank you for your contribution!';
    $show_gift_to = FALSE;

    // STEVE: first instance of a specific gift message for an event that's not a match
    if ($event_id == 8621) {
      $gift_msg = "We'll follow up with ticketing information!";
    }
  } else if ($item['giftID'] == CART_PLEDGE) {
    unset($item['matchingAcct']);
    $gift_img = make_img(fundraiser_image_src($event_id), 60, 60);
    $name = get_campaign_owner_name($event_id);
    if (!empty($name))
      $gift_name = "Pledge to $name's fundraiser"; 
    else 
      $gift_name = "Fundraiser support";
    $gift['excerpt'] = "You'll complete this donation when the fundraiser is over";
    $gift['varAmount'] = 1;
    $show_gift_to = FALSE;
    $gift_url = get_campaign_permalink($event_id);
    $unit = "per book";
    $txt = "A <b>pledge</b> is a promise to pay this amount when $name's fundraiser is over.  <i>No payment is due now.</i> ";
  } else {
    $gift_img = make_img(gift_image_src($gift), array(60,60));
    $gift_name = ucfirst($gift['displayName']);
    if ($gift['id'] == CART_GIVE_ANY)
      $txt = $gift['excerpt'];
  }

?>
    <div class="cart-item" id="item-<?=$item['id']?>">
      <? if (!$readonly) { ?>
        <<?=$gift_tag?> href="<?= $gift_url ?>" class="back-to-item item-img">
          <?= $gift_img ?>
        </<?=$gift_tag?>>
      <? } ?>
      <div class="txt">
          <? if ($gift['varAmount'] == 0) { ?><div class="right quantity">qty:
          <?= $readonly ? $item['quantity'] : display_quantity_ddl($item['quantity'],'quantity_'.$item['id'])?></div>
          <? } ?>
        <<?=$gift_tag?> href="<?= $gift_url ?>" class="title block">
          <strong><?= xml_entities($gift_name) ?></strong>
          <? if ($gift['varAmount'] == 0) { ?>($<?= $item['price'] ?>)<? } ?>
        </<?=$gift_tag?>>
        <?= $txt ?>
        <? if ($readonly) { }
           else if ($blog_id > 1) {
             $blog_details = get_blog_details($blog_id,1);
             ?><p class="cart-gift">&nbsp; &nbsp;
                  <input id="blog-field" class="ev" type="hidden" name="blog_<?= $item['id'] ?>" value="<?= $blog_id ?>">
               a gift to: <a href="<?= $blog_details->siteurl ?>"><?= xml_entities($blog_details->blogname) ?></a></p>
                <?

           } else if ($event_id > 0 && $show_gift_to) {
             $event = get_post($event_id);
             ?>
             <p class="cart-gift">
               <input id="event-checkbox" class="ev" type="checkbox" checked="" name="event_<?= $item['id'] ?>" value="<?= $event_id ?>">
               a gift to: <a href="<?= get_post_permalink($event_id) ?>"><?= xml_entities($event->post_title) ?></a>
             </p>
             <?
           } else if ($event_id > 0) {
             ?><input id="event-checkbox" type="hidden" name="event_<?= $item['id'] ?>" value="<?= $event_id ?>"><?
           }

           if (!empty($gift_msg)) {
             ?><p class="match-gift"><?= xml_entities($gift_msg) ?></p><?
           }

           $mg_id = get_event_match($event_id, $item);
           if ($mg_id > 0) {
             $acct = get_donation_account($mg_id);
             $params = get_acct_params($acct->params); 
             $msg = $params->message;
             if (empty($msg))
               $msg = "your gift will be matched!";
             ?>
             <p class="match-gift"><?= xml_entities($msg) ?></p>
             <?
           }

           if ($gift['id'] == 451 || $gift['towards_gift_id'] == 451) {
             ?>
             <p class="match-gift">SeeYourImpact normally provides personal impact stories within 2 weeks.  Because some NOLS courses take place in coming months, impact stories from your gift may not be available until that time.</p>
             <?
           }
        ?>
      </div>
      <div class="calc">

        <? if ($avg_tgi > 0 || $gift['varAmount'] == 1) { ?>
          <div class="item-total">

          <? if ($readonly) { ?>
            <?= as_money($item['price']) ?>
          <? } else { // not readonly
            $pf = floatval($item['price']);
            $pi = intval($pf);
            if ($pf != $pi)
              $pi = number_format($pf,2);
            $aid = "amount_" . $item['id'];
           ?>
              <label class="dollar" for="<?=$aid?>">$</label>
              <input type="text" size="3" class="var-amount" id="<?=$aid?>" name="<?=$aid?>" maxlength="5" value="<?= $pi ?>" />
              <div class="unit"><?= $unit ?></div>
          <? } // endif readonly ?>

          <? if (!$readonly) { ?><a class="remove-checkbox" href="/cart/?item=x<?=$item['id']?>"><img src="<?= _C('/wp-content/images/remove.gif') ?>" title="remove"></a><? } ?>
          </div><input type="hidden" name="<?='quantity_'.$item['id']?>" value="1" />

        <? } else { // not varAmount ?>

          <div class="item-total">
          <?=as_money($row_price)?><br/>
          <? if (!$readonly) { ?><a class="remove-checkbox" href="/cart/?item=x<?=$item['id']?>"><img src="<?= _C('/wp-content/images/remove.gif') ?>" title="remove"></a><? } ?>
          <? if ($saved) { ?>
          <a class="remove-checkbox" href="/cart/?item=m<?=$item['id']?>"><u>move to cart</u></a>
          <a class="remove-checkbox" href="/cart/?item=y<?=$item['id']?>"><u>remove</u></a><br/>
          <? } ?>
          </div> 

        <? } // endif varAmount ?>

      </div>
    </div>
<?
  return $row_price;

}

function get_fundraiser_donation_item($name) {
  if (!empty($name))
    $gift_name = "Donation - $name's fundraiser";
  else
    $gift_name = 'Fundraiser support';
  return $gift_name;
}
add_filter('get_fundraiser_donation_item', 'get_fundraiser_donation_item');

//get recipient/message/address details
function get_gc_details($id) {
  global $wpdb;

  return $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM cartItemDetails WHERE cartItemID = %d",
    $id));
}
function get_gc_recipient($details) {
  global $wpdb;

  if (is_numeric($details))
    $details = get_gc_details($details);

  if ($details == NULL)
    return NULL;

  if (!empty($details->recipientID)) {
    $recipient = $wpdb->get_row($wpdb->prepare(
      "SELECT firstName as first_name, lastName as last_name, email
       FROM donationGiver WHERE id = %d",
      $details->recipientID));
  } else if (!empty($details->mailTo)) {
    $recipient = json_decode($details->mailTo);
  } else if (!empty($details->emailTo)) {
    $recipient = json_decode($details->emailTo);
  }

  return $recipient;
}

function display_gcs($gcs, $readonly = FALSE) {
  if (!is_array($gcs))
    return 0;

  global $wpdb;
  $subtotal = 0;

  foreach ($gcs as $gc) { //display gc (purchase) line item
    $row_price = $gc['price'] * $gc['quantity'];
    $details = get_gc_details($gc['id']);
    $recipient = get_gc_recipient($details);

    ?>
      <div class="cart-item cart-giftcard cart-discount" id="item-<?=$gc['id']?>">
        <? if (!$readonly) { ?>
          <span class="item-img"><img src="<?= __C('images/impact-card.png') ?>" alt="" height="45" width="60" style="border: 0px none;" /></span>
        <? } ?>
        <div class="txt">
          <div class="right quantity">
            <? if ($readonly) { ?>
              qty: <?= $gc['quantity'] ?> x <?= as_money($gc['price']) ?>
            <? } else { ?>
              qty: <?= display_quantity_ddl($gc['quantity'],'quantity_'.$gc['id']) ?><br/>
              <?= display_gc_amount_ddl('amount_'.$gc['id'], $gc['price']) ?>
            <? } ?>
          </div>

          <div class="title"><strong><?= ucfirst(CART_BUY_GC_DESCRIPTION) ?></strong>
          <? if ($readonly) { ?>
              <? if ($recipient != NULL) { ?>
                for <?= xml_entities($recipient->first_name) ?>
                <?= xml_entities($recipient->last_name) ?>
              <? } ?>
            </div>
          <? } else { ?>
            </div>
            <? if ($recipient != NULL) { ?>
              <? if (!empty($recipient->first_name)) {?>
                for: <?= xml_entities($recipient->first_name) ?>
                <?= xml_entities($recipient->last_name) ?>
                <br>
              <? } ?>
              <? if (!empty($recipient->email)) { ?>
                sent to:
                <?= xml_entities($recipient->email) ?>
                <br/>
              <? } ?>
              <? if (!empty($recipient->address)) { ?>
                sent to:
                <?= xml_entities($recipient->address) ?>
                <?= xml_entities($recipient->address2) ?>
                <?= xml_entities($recipient->city) ?>,
                <?= xml_entities($recipient->state) ?>
                <?= xml_entities($recipient->zipcode) ?>
                <br/>
              <? } ?>
            <? } ?>
            <? if (!empty($details->message)) { ?>
              <p>"<?= as_html($details->message, true) ?>"</p>
            <? } ?>
          <? } ?>
        </div>
        <div class="calc">
          <div class="item-total gc-amount">
            <?= as_money($row_price) ?><br/>
          <? if (!$readonly) { ?><a class="remove-checkbox" href="/cart/?item=x<?=$gc['id']?>"><img src="<?= _C('/wp-content/images/remove.gif') ?>" title="remove"></a><? } ?>
          </div>
        </div>
      </div>
    <?
    $subtotal += $row_price;
  }

  return $subtotal;
}

function draw_payment_method_link($type, $color='green') {
  global $payment_method_titles;
  $label = $payment_method_titles[$type];
  $logo = array(
    'PP' => 'paypal_tiny.png',
    'GG' => 'google_tiny.jpg'
  );
  if ($logo[$type] !== NULL)
    $label = 'Pay with <img src="/wp-content/images/' . $logo[$type] . '" style="vertical-align: middle;">';

  ?><button id="pay-<?=$type?>" type="submit" name="submit" style="padding: 5px; display:block; margin: 50px auto -25px;" class="payment-button medium-button <?=$color?>-button button conv" value="<?= $payment_method_titles[$type] ?>"><?= $label ?></button><?
}

function get_cart_donor($cartID) {
  $donor = get_cart_data($cartID, 'donor');
  if ($donor === NULL)
    return NULL;
  if (is_numeric($donor))
    return $donor;
  return (object)$donor;
}
function get_cart_donor_info($cartID) {
  $donor = get_cart_donor($cartID);

  if ($donor !== NULL) // Numeric or object
    return $donor;

  $user_id = get_cart_user($cartID);
  if ($user_id > 0) {
    $donorID = get_user_donor($user_id);
    $donor = (object)get_donor_by_id($donorID);
    $d = array(
      'first' => $donor->firstName,
      'last' => $donor->lastName,
      'email' => $donor->email,
      'user_id' => $donor->user_id
    );
    return (object)$d;
  }

  return NULL;
}

function display_cart_signin_fields($args, $cart = NULL, $signup = FALSE) {
  ?>

  <? if (can_fb_connect()) { ?>
    <div class="right" style="width: 110px; margin: -14px -20px 0 0; position: relative;">
      <div style="position: absolute; left: -50px; top: 19px;">- or -</div>
      <div style="font-size:14px; margin-bottom: 5px; color:#048; font-weight: bold;">Sign in with</div>
      <div><? display_fb_login("connect"); ?></div>
      <div style="color:#666;font-size:9pt;margin-top:5px;">(we won't post anything without your permission!)</div>
    </div>
  <? } ?>

  <div class="left" style="width: 400px; margin-left: 20px;">
    <div class="fields if-expanded">
      <div class="labeled" style="width:158px;">
        <label for="first">First name</label>
        <input class="focused" value="<?=esc_attr($args['first'])?>" type="text" maxlength="100" size="16" name="first" id="first" autocomplete="off" />
      </div>
      <div class="labeled" style="width:158px;">
        <label for="last">Last name</label>
        <input class="focused" value="<?=esc_attr($args['last'])?>" type="text" maxlength="100" size="16" name="last" id="last" autocomplete="off" />
      </div>
    </div>

    <div class="fields">
      <div class="labeled" style="width:330px;">
        <? if ($signup) { ?>
        <label for="email">E-mail address<span class="if-collapsed"> or username</span></label>
        <? } ?>
        <input class="focused" value="<?=esc_attr($args['email'])?>" type="text" maxlength="100" size="40" name="email" id="email" autocomplete="off" />
      </div>
    </div>

    <? if ($signup) { ?>
      <div class="fields if-collapsed">
        <div class="labeled" style="width: 158px;">
          <label for="password">Password</label>
          <input type="password" class="focused" value="<?=esc_attr($args['password'])?>" type="text" maxlength="100" size="15" name="password" id="password" autocomplete="off" />
        </div>
        <div class="left" style="padding:6px;"><a href="<?= get_site_url(1, '/signin/?action=resetpass') ?>" class="link" style="color: #666; font-size: 90%;">forgotten password</a></div>
      </div>
      <div class="fields if-expanded">
        <div class="labeled" style="width: 158px;">
          <label for="new-password">Choose a password</label>
          <input type="password" class="focused" value="<?=esc_attr($args['new-password'])?>" type="text" maxlength="100" size="11" name="new-password" id="new-password" autocomplete="off" />
        </div>
        <div class="left" style="margin: 2px -110px 0 0; font-size: 9pt; width: 260px; color: #444;">
           <?= apply_filters('sign_in_password', "<b>Optional</b>, but you'll need one to review<br>or change your donation later.") ?>
        </div>
      </div>
    <? } ?>
  </div>
<?
}

function cart_radio($name, $value, $current = NULL, $label = NULL, $attrs = NULL) {
  if (!empty($label)) {
    ?><label for="<?=$value?>" <?=$attrs?>><?
  }

  ?><input type="radio" id="<?=$value?>" name="<?=$name?>" value="<?=$value?>"
    <? if ($value == $current) echo 'checked="checked"'; ?>><?

  if (!empty($label)) {
    ?></label><?
  }
}

function display_cart_signin_page($args, $cart = NULL) {
  ?>
  <div class="signin-field full-wide">
  <div class="signin-form">
  <?
  display_cart_signin_form($args, $cart);

  // OPT OUT checkbox
  if (!isset($args['is_contact']))
    $cart->data->share_email = 0;

  if (!is_numeric($cart->donor)) { // Can't opt in for someone   
    global $wpdb;
    global $bp;

    $is_sharing = $wpdb->get_var($wpdb->prepare(
      "SELECT share_email FROM donationGiver WHERE user_id=%d ORDER BY main DESC, share_email DESC",
      $bp->loggedin_user->id));
    if (empty($bp->loggedin_user->id) || !$is_sharing) {
    ?>
      <div style="font-size: 10pt; margin: 2px -20px 0 20px; line-height: 20px;">
        <input type="checkbox" class="left" name="share_email" id="share_email" value="1" style="margin-left: -20px;" <?= checked($cart->data->share_email) ?> />
        <label for="share_email"><? do_action('display_share_email_label', $cart); ?></label>
      </div>
    <?
    }
  }

  ?></div></div><?
}

function display_share_email_label($cart) {
  // Extracted from a bunch of custom themes - not sure if we really need to IF/THEN this
  if (isset($cart->data) && $cart->data->event_id > 0) {
    ?><b>Keep in touch!</b> We'll send you email updates with the latest news and stories.<?
  } else { 
    ?><b>Stay in touch</b> - get news and updates from the charities you support!<?
  }
}


function display_cart_signin_form($args, $cart = NULL) {
  global $bp;

  global $current_user;
  get_currentuserinfo();

  if ($args) { 
  } else if ($cart->donor !== NULL && is_object($cart->donor)) {
    $args = array(
      'first' => $cart->donor->first,
      'last' => $cart->donor->last,
      'email' => $cart->donor->email
    );
  } 

  if (!isset($args['acct']) && isset($_COOKIE['wp-settings-1']))
    $args['acct'] = 'sign-in';

  $new_donor = $cart->donor !== NULL;

  ?>
  <input type="hidden" name="is_contact" value="yes" />
<? /*
  <div id="page-sidebar" class="right page-sidebar">
    <div class="promo-widget" style="margin-top: -30px;">
      <div><b>Why do we need your contact info?</b></div>
      <? draw_promo_content("pay-sidebar", NULL, true); ?>
    </div>
  </div>
*/ ?>

  <? if (is_user_logged_in()) { $acct = $new_donor ? 'use-new' : 'user'; ?>
    <div class="fast collapser">
    <label for="user" style="display:block; font-size:11pt;">
      <? /* cart_radio('acct', 'user', $acct); */ ?> 
      <input type="hidden" name="acct" value="user" />
      We'll follow up via e-mail at
      <b><?= htmlspecialchars($current_user->user_firstname) ?>
      <?=htmlspecialchars($current_user->user_lastname) ?>
      (<?=htmlspecialchars($current_user->user_email) ?>)</b>
    </label>
<? /* TODO
    <div style="margin-bottom:10px;" class="collapser expanded <? if (!$new_donor) echo 'js-hide'; ?>">
      <label for="use-new" class="expander expand" style="font-size:11pt;">
        <? cart_radio('acct', 'use-new', $acct); ?>
        Use a different address<span class="if-expanded">:</span>
      </label>
      <div class="if-expanded" style="padding: 10px 0 0 20px;">
        <? display_cart_signin_fields($args, $cart); ?>
      </div>
    </div>
*/ ?>
    </div>
  <? } else { $acct = eor($args['acct'], 'sign-up'); ?>
    <div class="collapser fast <? if ($acct == 'sign-up') echo 'expanded'; ?>">
      <div class="signin-choices">
        <h3 class="htop left block" style="padding-right: 30px; margin-bottom: 1em;">
          <?= apply_filters('sign_in_intro', 'Please sign in:') ?>
        </h3>
        <label for="sign-in" class="left block expander collapse" style="padding-right: 30px;">
          <? cart_radio('acct', 'sign-in', $acct); ?>
          I've been here before
        </label>
        <label for="sign-up" class="left block expander expand" >
          <? cart_radio('acct', 'sign-up', $acct); ?>
          I'm new
        </label>
      </div>
      <div style="padding: 10px 0 -10px 20px;">
        <? display_cart_signin_fields($args, $cart, TRUE); ?> 
      </div>
    </div>
  <? }
}

function display_cart_payment_page($args, $cart = NULL, $focused = FALSE) {
  global $current_user;
  global $site_url;
  global $error_cc;
  global $cartID;
  global $payment_method_titles;

  if ($cart->total <= 0) {
    ?>
    <div style="margin-top: 15px;">
      <input type="submit" class="payment-button big-button orange-button button conv" name="checkout_button" value="<?= $payment_method_titles['GC'] ?>"> 
      <span style="color:#444;">Complete your donation (no payment is due.)</span>
    </div>
    <?
    return;
  }


  global $payment_method_titles;

  $focused = $focused ? " focused" : "";

  //Test data
  if(CREDIT_PAYMENT_TEST){
    $cc_num = '4929492378736069';$cc_type = 'Visa';$cc_month = '10';
    $cc_year = '2011';$cc_cvv = '808';
    $first_name = 'John';$last_name = 'Doe';
    $address1 = '12345 Place St';$address2 = 'Apt 205';
    $city = 'Seattle';$state = 'WA';$zip = '98115';
  } else if ($args != NULL) {
    $cc_num = $args['cc_num'];
    $cc_type = $args['cc_type'];
    $cc_month = $args['cc_month'];
    $cc_year = $args['cc_year'];
    $cc_cvv = $args['cc_cvv'];
    $first_name = $args['first_name'];
    $last_name = $args['last_name'];
    $address1 = $args['address1'];
    $address2 = $args['address2'];
    $city = $args['city'];
    $state = $args['state'];
    $zip = $args['zip'];
    $email = $args['email'];
  } else if ($cart->donor !== NULL) {
    if (is_numeric($cart->donor)) {
      // Could load the user? but this is just to fill in a field
    } else {
      $first_name = $cart->donor->first;
      $last_name = $cart->donor->last;
    }
  } else {
    global $current_user;
    $first_name = $current_user->user_firstname;
    $last_name = $current_user->user_lastname;
  }

  $paypal=new phpPayPal(); //need paypal object for the dropdown values

  //Build US states dropdown
  $state_options=buildDropDownOptions($paypal->states['US'],$state);

  //Build Countries dropdown
  if (empty($country_code))
    $country_code = "US";
  $country_options=buildDropDownOptions($paypal->countries,$country_code);

  //Build CC types dropdown
  $cc_types=array('Visa'=>'Visa','MasterCard'=>'MasterCard','Amex'=>'Amex','Discover'=>'Discover');
  $cc_type_options=buildDropDownOptions($cc_types,$cc_type);
  $cc_months=array(1=>'01 - January',2=>'02 - February',3=>'03 - March',4=>'04 - April',5=>'05 - May',
      6=>'06 - June',7=>'07 - July',8=>'08 - August',9=>'09 - September',10=>'10 - October',11=>'11 - November',12=>'12 - December');
  $cc_month_options=buildDropDownOptions($cc_months,$cc_month, FALSE);

  $cc_years=array();
  for($i=0;$i<10;$i++){$cc_years[date('Y',time())+$i]=date('Y',time())+$i;}
  $nyr = date('Y',time())+1;
  $cc_year_options=buildDropDownOptions($cc_years,eor($cc_year, $nyr), FALSE);

  ?>

  <input type="hidden" name="is_cc" value="yes" />

  <div class="right" style="width: 180px; margin-top: 30px; text-align: center;">
    <h2>Your total: <?= as_money($cart->total) ?></h2>
    <br><br><br><div style="margin-bottom:-25px;">Are you a PayPal member?</div>
    <?
    if (PAYPAL_PAYMENT_ENABLED !== FALSE)
      draw_payment_method_link('PP');
    ?>
  </div>

  <? if (CREDIT_PAYMENT_ENABLED == FALSE) return; ?>

  <div class="cc_form left" style="background: #eee; margin: 30px 0 0 -10px; padding: 20px 10px 20px 20px; border: 1px solid #aaa; border-radius: 4px; position: relative;">
    <img class="right" style="margin: -6px 10px 0 0;" src="/wp-content/images/credit_card_logos.gif" alt="">
    <h3 style="margin: 0 0 30px 0;">Pay with credit card</h3>

  <div class="fields">
    <div class="labeled" style="width:175px;">
      <label for="first_name">first name</label>
      <input class="<?=$focused?>" value="<?=$first_name?>" type="text" maxlength="100" size="15" name="first_name" id="first_name" />
    </div>
    <div class="labeled" style="width:175px;">
      <label for="last_name">last name</label>
      <input class="<?=$focused?>" value="<?=$last_name?>" type="text" maxlength="100" size="15" name="last_name" id="last_name" />
    </div>
  </div>
  <div class="fields">
    <div class="labeled"  style="width:220px;">
      <label for="cc_num">card number</label>
      <input class="<?=$focused?>" value="<?=$cc_num?>" type="text" maxlength="16" size="20" name="cc_num" id="cc_num" />
    </div>
    <div class="labeled" style="width:130px;">
      <label for="cc_type">card type</label>
      <select name="cc_type" id="cc_type"><?=$cc_type_options?></select>
    </div>
  </div>
  <div class="fields">
    <div class="labeled" style="width: 167px;">
      <label for="cc_month">expiration</label>
      <select name="cc_month" id="cc_month"><?=$cc_month_options?></select>
    </div>
    <div class="labeled" style="width: 100px;">
      <label for="cc_year">year</label>
      <select name="cc_year" id="cc_year"><?=$cc_year_options?></select>
    </div>
    <div class="labeled" style="width: 70px;">
      <img src="../wp-content/images/credit_card_cvv.gif" alt="CVV" style="display: none; position: absolute; background: white; paddding: 20px 40px; bottom: 40px; right: -20px; border: 10px solid #ddd; width: 400px; height: 150px; z-index: 100; box-shadow: 0 0 10px #888; " id="cvv_img" />
      <label for="cc_cvv">CVV</label>
      <input class="<?=$focused?> short" value="<?=$cc_cvv?>" type="text" maxlength="4" size="4" name="cc_cvv" id="cc_cvv" />
      <div id="cvv_link" style="position: absolute; font-size: 11px; bottom: -15px; cursor: pointer;">(<u>what's this?</u>)</div>
    </div>
  </div>
  <br>
  <div class="fields">
    <div class="labeled" style="width:367px;">
      <label for="address1">billing address</label>
      <input class="<?=$focused?> full" value="<?=$address1?>" type="text" maxlength="100" size="50" name="address1" id="address1" />
    </div>
  </div>
  <div class="fields">
    <div class="labeled" style="width:130px;">
      <label for="city">city</label>
      <input class="<?=$focused?>" value="<?=$city?>" type="text" maxlength="100" size="20" name="city" id="city" />
    </div>
    <div class="labeled" style="width: 70px;">
      <label for="state">state</label>
      <select name="state" id="state"><?=$state_options?></select>
    </div>
    <div class="labeled" style="width: 40px; margin-left: -10px; margin-right: -10px;">
      <div style="color:#666; padding: 7px; height: 32px; font-size: 16px; ">or</div>
    </div>
    <div class="labeled" style="width: 120px;">
      <label for="state_other">other</label>
      <input value="<?=$state_other?>" type="text" maxlength="100" size="6" name="state_other" id="state_other" />
    </div>
  </div>
  <div class="fields">
    <div class="labeled" style="width:130px;">
      <label for="zip">zip/post</label>
      <input class="<?=$focused?>" value="<?=$zip?>" type="text" maxlength="100" size="10" name="zip" id="zip" />
    </div>
    <div class="labeled" style="width:225px;">
      <label for="country">country</label>
      <select name="country_code" id="country"><?=$country_options?></select>
    </div>
  </div>

  <div id="page-sidebar" class="right page-sidebar">
    <div class="promo-widget">
<div style="margin: -25px 0 0 15px; font-size:90%;">
<img src="<?=__C('images/lock.png')?>">
We promise to protect your privacy! All transactions are secure. For more information, please read our <a class="link" href="/about/privacy">privacy policy</a>.
</div>
    </div>
  </div>

  <div style="margin-top: 20px; margin-right: 10px; text-align:center;">
      <input id="pay-CC" type="submit" style="width:200px;" class="payment-button medium-button green-button button conv"
          name="submit" value="<?=$payment_method_titles['CC']?>" />
    <input type="hidden" id="ajax" name="ajax" value="0" />
  </div>

<div style="text-align:center; font-size: 80%; margin-top: 20px;color: #666;">This charge will appear on your statement as<br>"SEEYOURIMPA" or "SEEYOURIMPACT.ORG"</div>

  </div>

</div>
<script type="text/javascript">
//Validate form
function validateCreditCardForm(mask){
  console.log(mask);
  $('.cc_form').block({
    message: mask ? '<h3><strong>Your payment is being processed.</strong></h3>You will be redirected in a moment.<br/>Please do not refresh the page to avoid multiple payments. Thank you!' : null,
    overlayCSS: { opacity: mask ? 0.1 : 0},
    css: { padding: 20, width: 300}
  });

  /*
  $('#ajax').val("1");
  $.ajax({
    type: 'POST',
    url: this.closest('form').attr('action'),
    data: form.serialize(),
    dataType: 'html',
    cache: false,
    timeout: 20000,
    error: function(request,status,err) {
      window.location.replace('<?= get_site_url(1, '/cart/?cart='.$cartID, 'login') ?>');
    },
    success: function(d) {
      try {
        var res = $.parseJSON(d);
        if (res.status == "success") {
          window.location.replace(res.data);
          return;
        } 
      } catch (ex) { }
      window.location.replace('<?= get_site_url(1, '/cart/?cart='.$cartID, 'login') ?>');
    }
  });
  */
  return true;
}

$(function(){
  var b;
  $('#cart-page input').live('click', function() {
    b = $(this).attr('id');
  });
  $('#cart-page').submit(function(ev) {
    return validateCreditCardForm(b == 'pay-CC');
  });

  // Autoselect credit card type
  $("#cc_num").live('input paste change', function(ev) {
    var v = $(this).val();

    if (v.match(/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/))
      $("#cc_type").val('Visa');     
    else if (v.match(/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/))
      $("#cc_type").val('MasterCard');     
    else if (v.match(/^3[4,7]\d{13}$/))
      $("#cc_type").val('Amex');     
    else if (v.match(/^6011-?\d{4}-?\d{4}-?\d{4}$/))
      $("#cc_type").val('Discover');     
  });
});
//-->
</script>
<?
}

////////////////////////////////////////////////////////////////////////////////

function cc_field(&$args, $field, &$result, $required = FALSE) {
  $val = trim($args[$field]);
  if ($required && empty($val)) {
    $result->status = 'error';
    $result->missing[] = $field;
    $result->data = (count($result->missing) > 1) ? "Oops, looks like you missed some required fields!" : "Oops, looks like you missed a required field!";
  }
  return $args[$field];
}

function process_cart_signin(&$cart, $args) {
  $result = new stdClass;
  global $error_signin;

  if ($cart != NULL)
    update_cart_data_sparse($cart->id, 'share_email', $args['share_email'] == 1 ? TRUE : NULL);

  if ($args['acct'] == 'sign-up') {
    $data = array(
      "account" => cc_field($args, 'email', $result, TRUE),
      "first_name" => cc_field($args, 'first', $result, TRUE),
      "last_name" => cc_field($args, 'last', $result, TRUE),
      "password" => cc_field($args, 'new-password', $result, FALSE),
      "register" => true
    );

    if ($result->status != 'error') {
      // Try to sign up
      $user_id = try_login($data, $result);
      if (!empty($user_id)) {
        bp_setup_globals();
      }

    } 

  } else if ($args['acct'] == 'sign-in') {
    $data = array(
      "account" => cc_field($args, 'email', $result, TRUE),
      "password" => cc_field($args, 'password', $result, TRUE),
      "register" => false
    );

    if ($result->status != 'error') {
      // Try to log in
      $user_id = try_login($data, $result);
      if (!empty($user_id)) {
        bp_setup_globals();
      }
    }

  } else if ($args['acct'] == "user") {
    if ($cart != NULL) {
      $cart->donor = NULL;
      update_cart_data_sparse($cart->id, 'donor', $cart->donor);
    }
  } else {
    $email = cc_field($args, 'email', $result, TRUE);
    if ($cart != NULL) {
      $cart->donor->first = cc_field($args, 'first', $result, TRUE);
      $cart->donor->last = cc_field($args, 'last', $result, TRUE);
      $cart->donor->email = $email;
    }

    if ($result->status != 'error') {
       if (!validate_email($email))
         $result = cart_error("Please enter a valid e-mail address.", "email");
       else if ($cart != NULL)
         update_cart_data_sparse($cart->id, 'donor', $cart->donor);
    }

  }

  $just_update = ($args['update_button'] == CART_UPDATE_LABEL);
  if ($just_update && $result->status == 'error' && count($result->missing) > 0) 
    return; // Not really an error.

  return $result;
}

function process_pledges(&$cart) {
  finalize_cart($cart);

  foreach ($cart->items as $item) {
    if ($item['giftID'] == CART_PLEDGE)
      $pledges[] = $item;
  }

  if (count($pledges) == 0)
    return;
  
  if (is_numeric($cart->donor))
    return cart_error("You can't make a pledge for another user.");

  if ($cart->donor !== NULL || !is_user_logged_in())
    return cart_error("Please provide your contact information.", "email");

  global $bp;
  $result = NULL;
  foreach ($pledges as $pledge) {
    if (!insert_pledge($pledge['event_id'], $pledge['price'], $bp->loggedin_user->id, $result)) {
      return $result;
    }
    cart_remove($cart->id, $pledge['id']);
  }
}

function process_creditcard_payment(&$cart, $args) {
  $arr = $args;
  unset($arr['cc_num']);
  dc($cart->id, 'Processing CC submission: ' . print_r($arr, true));

  $paypal = new phpPayPal();
  $paypal->version = "63.0";

  $result = new stdClass;

  //Billing Details (required)
  $paypal->first_name = cc_field($args, 'first_name', $result, TRUE);
  $paypal->last_name = cc_field($args, 'last_name', $result, TRUE);
  $paypal->address1 = cc_field($args, 'address1', $result, TRUE);
  $paypal->address2 = cc_field($args, 'address2', $result);
  $paypal->city = cc_field($args, 'city', $result, TRUE);
  $paypal->state = eor(cc_field($args, 'state', $result), trim($args['state_other']));
  if (empty($paypal->state))
    $result->missing[] = 'state';
  $paypal->postal_code = cc_field($args, 'zip', $result, TRUE);
  $paypal->phone_number = cc_field($args, 'phone', $result); // (We don't collect this right now)
  $paypal->country_code = cc_field($args, 'country_code', $result, TRUE);
  $paypal->credit_card_number = cc_field($args, 'cc_num', $result, TRUE);
  $paypal->credit_card_type = cc_field($args, 'cc_type', $result, TRUE);
  $paypal->cvv2_code = cc_field($args, 'cc_cvv', $result, TRUE);
  $paypal->expire_date = cc_field($args, 'cc_month', $result, TRUE) . cc_field($args, 'cc_year', $result, TRUE);

  if (count($result->missing) > 0) {
    $result->status = 'error';
    $result->data = (count($result->missing) > 1) ? "Oops, looks like you missed some required fields!" : "Oops, looks like you missed a required field!";
    return $result;
  }

  // Fill in the items
  cart_to_paypal($cart->id, $paypal);
  if ($cart->is_test)
    $paypal->switch_payment_mode(false);

  //validate value on server side
  if (floatval($paypal->amount_total) <= 0) {
    dc($cart->id, "Paypal total was $paypal->amount_total");
    return cart_error("There was a problem processing your cart.  Please try again, thanks!");
  }

  // ==============
  // SUBMIT PAYMENT
  $paypal->do_direct_payment(); //do creditcard payment
  return process_paypal_payment($paypal, 'CC', $cart);
}

function process_paypal_payment($paypal, $method = 'XC', $cart = NULL) {
  dc($cart->id, "Received $method response: " . print_r($paypal->Response, true));

  if ($paypal->Error !== NULL) {
    $arr = $_REQUEST;
    unset($arr['cc_num']);

    dc($cart->id, "User got error in $method payment: " . print_r($paypal->Error, true));
    return cart_error('Sorry, there was a problem processing your payment. ' . notifyPaymentFailure($arr, $paypal->Response, $method));
  }

  $paypalResponse = $paypal->Response;

/*
  This code can be used to confirm the transaction is authentic & successful.

  $paypal->transaction_id = $paypalResponse['TRANSACTIONID'];
  $paypal->get_transaction_details();    //grab details
  $paypalResponse = $paypal->Response;

  dc($cart->id,"User $method payment successful, asking for details");
  $paypalResponse = $paypal->Response;
  if($paypalResponse['ACK'] != 'Success' && $paypalResponse['ACK'] != 'SuccessWithWarning') {    //details received
    dc($cart->id,'User got error in retrieving details in '.$method);
        $errorMsg = 'Payment error: ';
         $errorMsg .= notifyPaymentFailure($arr,$paypalResponse,$method);
    return array(
      'status' => 'error',
      'data' => 'Sorry, there was a problem processing your payment. ' . notifyPaymentFailure($arr,$paypal->Response,$method)
    );
  } 
  dc($cart->id,"User $method payment details retrieved:\n\n".print_r($paypal->Response,true));
*/

  try {
  // TODO: This really should be done by the order process itself
  // 1. Lock the order (prevents double submission)
  // 2. Process order, which marks the cart as paid
  global $wpdb;
  $sql = $wpdb->prepare("UPDATE cart SET status='paid' WHERE id=%d",$cart->id);
  $wpdb->query($sql);
  $wpdb->flush();
  dc($cart->id, "Set to paid. $sql");

  $variables = var_export($paypalResponse, true);

  $order = new Donation($variables);
  $order->payment = new stdClass;
  $order->payment->status = 'Paid';
  $order->payment->id = $paypalResponse['TRANSACTIONID'];
  $order->payment->method = $method;
  $order->payment->memo = null;
  
  $order->donor = get_cart_donor_info($cart->id);
  copy_address($paypal, $order->donor); // Donor's assumed info
  copy_address($paypal, $order->payment); // CC billing address
  
  $order->data = urlencode($cart->id);

  // BUILD THE CART
  $order->cart = new stdClass;
  $order->cart->id = $cart->id;
  $order->cart->items = array();
  $order->discounts = array();

  //to dis-count the discount
  $total_positive = 0;
  $total_tipped = 0;
  foreach ($paypal->ItemsArray as $item) {
    $numbers = explode('_', $item['number']);
    $item_id = $numbers[0];
    $gift_id = $numbers[1];
    if(!empty($numbers[2])) $ref = $numbers[2];
    else $ref = '';

    if (empty($item_id))
      continue;

    $i = new stdClass;
    $i->id = $item_id;
    $i->gift_id = $gift_id;
    $i->price = $item['amount'];
    $i->quantity = $item['quantity'];
    $i->ref = $ref;
    
    switch ($gift_id) {
      case CART_TIP:
      case CART_PLEDGE:
        break; // Ignore these
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

  $order->payment->tip = $cart->tip;
  $order->payment->gross = $total_positive + $cart->tip;
  $order->payment->tipped = $total_tipped;

  processOrder($order);

  return cart_success(get_thankyou_url($order->cart->id));

  } catch (Exception $e) {
    dc($cart->id, "User got error in $method payment: " . print_r($e, true));
    return cart_error('Sorry, there was a problem processing your payment. ' . notifyPaymentFailure($arr, $e, $method));
  }
}

function cart_to_paypal($cartID, &$paypal){
  global $wpdb;

  $items = array();
  $discounts = array();

  $rows = cart_to_array($cartID);

  foreach ($rows as $row) {
    $qty = absint($row['quantity']);
    if ($qty == 0)
      continue;

    $price = floatval($row['price']);
    switch ($row['giftID']) {
      case CART_BUY_GC:
        $items[] = array('name'=>CART_BUY_GC_DESCRIPTION,
          'number'=>$row['id'].'_'.CART_BUY_GC, 'quantity'=>$qty,
          'amount'=>$price);
        break;

      case CART_USE_GC:
        $discounts[] = array('name'=>CART_USE_GC_DESCRIPTION,
          'number'=>$row['id'].'_'.CART_USE_GC.'_'.$row['ref'],
          'quantity'=>1, 'amount'=>$price);
        break;

      case CART_TIP: // No longer used 
      case CART_PLEDGE:
        break;

      case CART_GIVE_ANY:
        $event_id = intval($row['event_id']);
        if ($event_id > 0) {
          $items[] = array('name'=>"Fundraiser support",
            'number'=>$row['id'].'_'.CART_GIVE_ANY.'_'.$row['event_id'], 'quantity'=>$qty,
            'amount'=>$price);
          $items[] = $item;
          break;
        }
        // Fall through

      default:
        $avg_tgi = intval(get_avg_tgi($row['giftID']));
        if ($avg_tgi > 0) {
          $tg = $wpdb->get_row($wpdb->prepare("SELECT * FROM gift WHERE id = %d", $avg_tgi),ARRAY_A);
          $items[] = array('name'=>AVG_NAME_PREFIX.$tg['displayName'],
            'number'=>$row['id'].'_'.$row['giftID'], 'quantity'=>$qty, 'amount'=>$price);
        } else {
          $items[] = array('name'=>$row['displayName'],
            'number'=>$row['id'].'_'.$row['giftID'], 'quantity'=>$qty, 'amount'=>$price);
        }
      break;
    }
  }

  $paypal->amount_total = 0;

  // First items, then tip, then discounts
  foreach ($items as $item) {
    if ($item['amount'] <= 0) continue;
    $paypal->addItem($item['name'], $item['number'], $item['quantity'], 0, $item['amount']);
    $paypal->amount_total += $item['quantity'] * $item['amount'];
  }

  $tip_rate = get_tip($cartID);
  $tip = calculate_tip($tip_rate, $paypal->amount_total);
  if ($tip > 0) {
    $paypal->addItem(CART_TIP_DESCRIPTION, '0_' . CART_TIP, 1, 0, $tip);
    $paypal->amount_total += $tip;
  }

  foreach ($discounts as $item) {
    $paypal->addItem($item['name'], $item['number'], $item['quantity'], 0, $item['amount']);
    $paypal->amount_total += $item['quantity'] * $item['amount'];
  }

  $paypal->custom = urlencode($cartID);
  $paypal->return_url = SITE_URL . '/payments/payPaypal.php' . (strpos(CREDIT_API_URL,'sandbox')===FALSE?'':'?sandbox');
  $paypal->cancel_url = get_cart_url('pay');

  $paypal->landing_page = 'Billing';
  $paypal->solution_type = 'Sole';
  $paypal->user_action = 'commit';
  $paypal->no_shipping = 1;
  $paypal->amount_handling = 0;
  $paypal->amount_shipping = 0;
  $paypal->amount_tax = 0;
  
  return $paypal;
}

function copy_address($from, &$to) {
  $to->address1 = $from->address1;
  $to->address2 = $from->address2;
  $to->city = $from->city;
  $to->state = $from->state;
  $to->postal_code = $from->postal_code;
  $to->phone_number = $from->phone_number;
  $to->country_code = $from->country_code;
}

function get_cart_url($mode = NULL) {
  $url = get_site_url(1, '/cart/', is_live_site() ? 'https' : 'login');
  if (!empty($mode))
    $url .= "?$mode";
  return $url;
}

function encrypt_cart($cart_id) { return encrypt($cart_id); }
function decrypt_cart($str) { return intval(decrypt($str)); }

function recycle_carts($user_id, $exclude_id) {
  global $wpdb;
  if (intval($user_id) > 0) {
	$user_carts = $wpdb->get_results(
	  $sql = $wpdb->prepare("SELECT * FROM cart 
	    WHERE userID=%d AND id<>%d AND status='active'",
      $user_id, $exclude_id));
    dp($sql);
	$count = 0;
	if (!empty($user_carts) && is_array($user_carts))
	  foreach ($user_carts as $cart) $count += recycle_cart($cart);
    if ($count>0) return $count;
  }
  return false;
}

function recycle_cart($cart) {
  global $wpdb;  	
  if(empty($cart->id)) return 0;
  $count = 0;

  $item_ids = $wpdb->get_col(
    $wpdb->prepare("SELECT id FROM cartItem WHERE cartID=%d",$cart->id));
  $affected = $wpdb->query($sql = $wpdb->prepare("UPDATE cart 
    SET userID=0,paymentID=0,referrer=0,test=0,data='' WHERE id=%d",$cart->id));
  if ($affected !== FALSE) $count+=$affected;  
  foreach($item_ids as $item_id) {
	$affected = $wpdb->query($sql = $wpdb->prepare("UPDATE cartItem 
	  SET giftID=0, price=0, quantity=0, matchingAcct=0, ref='', event_id=0, blog_id=0 
      WHERE id=%d",$item_id));
	if ($affected !== FALSE) $count+=$affected;	
	$affected = $wpdb->query($sql = $wpdb->prepare("UPDATE cartItemDetails 
	  SET recipientID=0, emailTo='', mailTo='', message='' WHERE cartItemID=%d",$item_id));
	if ($affected !== FALSE) $count+=$affected;  
  }
  return $count;
}

function get_thankyou_url($cart_id) {
  $cid = urlencode(encrypt_cart($cart_id));

  $event_id = get_cart_data($cart_id, 'event_id');
  if ($event_id > 0) {
    return add_query_arg(array(
      'msg' => 'thankyou',
      'cid' => $cid
    ), get_campaign_permalink($event_id));
  }

  global $siteinfo;
  $userID = get_cart_user($cart_id);

  if ($userID != NULL) 
    return add_query_arg('cid', $cid, get_member_link($userID));

  // only case for this will be unloggedin, unprocessed thank you page, e.g.: Google return url
  return add_query_arg('cid', $cid, SITE_URL . "/pay/thank-you/");
}
function get_cart_cancel_url($cart_id) {
  return get_cart_url('pay');
}

////////////////////////////////////////////////////////////////////////////////

global $CART_DEBUG;
$CART_DEBUG = get_blog_option(1, 'cart_debug');
function dc($cartID,$msg) {
  global $CART_DEBUG;
  if (!$CART_DEBUG)
    return;

  //debug cart
  global $wpdb;
  $wpdb->query(
    $wpdb->prepare("INSERT INTO cartDebug (cartID, recorded, message, note) "
    ." VALUES (%d, NOW(), %s, %s)", $cartID, $msg, getClientInfo()));
}

function getClientInfo(){
  $ret = '';
  $ret .= print_r($_SERVER,true)."\n\n";
  $ret .= print_r(@get_browser(null,true),true);
  return $ret;
}

function donate_shortcode($args) {
  global $GIFTS_EVENT;
  ob_start();

  $w1 = "Give";
  $w2 = "Donate";
  $eid = $GIFTS_EVENT;
  if (is_array($args))
    extract($args);
  if (empty($color))
    $color = "orange";
  if (empty($method))
    $method = "default";

  switch($method) {
    case 'google':
?>
<script type="text/javascript"> 
function validateAmount(amount){
  if(amount.value.match( /^[0-9]+(\.([0-9]+))?$/)){
    return true;
  }else{
    alert('You must enter a valid donation.');
    amount.focus();
    return false;
  }
}
</script>
<form action="https://checkout.google.com/cws/v2/Donations/306596820749762/checkoutForm" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm" onSubmit="return validateAmount(this.item_price_1)" target="_new">
    <input name="item_name_1" type="hidden" value="Donation to SeeYourImpact.org"/>
    <input name="item_description_1" type="hidden" value="Thank you!"/>
    <input name="item_quantity_1" type="hidden" value="1"/>
    <input name="item_currency_1" type="hidden" value="USD"/>
    <input name="item_is_modifiable_1" type="hidden" value="true"/>
    <input name="item_min_price_1" type="hidden" value="0.01"/>
    <input name="item_max_price_1" type="hidden" value="25000.0"/>
    <input name="_charset_" type="hidden" value="utf-8"/>
   
    <div>
       <div style="float:left;">&#x24; <input id="item_price_1" name="item_price_1" onfocus="this.style.color='black'; this.value='';" size="11" style="color:grey; padding:2px; margin: 2px;" type="text" value="Enter Amount"/>
       </div>
       <div style="float: left;">
          <input alt="Donate" src="https://checkout.google.com/buttons/donateNow.gif?merchant_id=306596820749762&amp;w=115&amp;h=50&amp;style=white&amp;variant=text&amp;loc=en_US" type="image"/>
       </div>
    </div>
</form>
<?
    break;

    case 'paypal':
?>
<script type="text/javascript"> 
function validateAmount2(amount){
  if(amount.value.match( /^[0-9]+(\.([0-9]+))?$/)){
    return true;
  }else{
    alert('You must enter a valid donation.');
    amount.focus();
    return false;
  }
}
</script>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" onSubmit="return validateAmount(this.amount)" target="_new">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="digvijay@seeyourimpact.org">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Donation to SeeYourImpact.org">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="amount" value="75.00">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
    <div>
       <div style="float:left;">&#x24; <input id="amount" name="amount" onfocus="this.style.color='black'; this.value='';" size="11" style="color:grey; padding:2px; margin: 2px;" type="text" value="Enter Amount"/>
       </div>
       <div style="float: left;">
          <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
          <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
       </div>
    </div>
</form>
<?
    break;

    default:
//get_site_url(1, "/cart/", 'login_post')
?>
<form id="give-any-amount" action="<?= get_cart_url() ?>" class="donate-any" method="post" style="font-size:14pt;">
  <input type="hidden" name="item" value="<?= CART_GIVE_ANY ?>">
  <input type="hidden" name="eid" value="<?= $eid ?>">
  <label for="give-any"><b><?=$w1?> $ </b></label><input type="text" name="amount" size="5" maxlength="5" style="width:60px;padding:1px; font-size:12pt; font-weight:bold;" value="" id="damt">
  <input id="give-any" type="submit" class="button medium-button <?=$color?>-button" name="submit" value="<?=$w2?>">
</form>
<?
    break;
  }

  $str = ob_get_contents();
  ob_end_clean();
  return $str;
}
add_shortcode('donate', 'donate_shortcode');

?>
