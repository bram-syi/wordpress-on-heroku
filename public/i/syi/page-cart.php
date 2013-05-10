<?

define('DONOTCACHEPAGE',1); 

include_once(ABSPATH . '/payments/paypal/phpPayPal.php');
include_once(ABSPATH . '/wp-includes/syi/syi-includes.php');
include_once(ABSPATH . '/payments/payments.php');

add_action('draw_cart_header', 'draw_cart_header');
add_action('draw_cart_sidebar', 'draw_cart_sidebar');
add_action('after_form_error', 'cart_error_message');

global $current_user;
global $cartID;
global $wpdb;
global $post;
if(is_user_logged_in()) get_currentuserinfo();

$IS_AJAX = isset($_SERVER['HTTP_AJAX_METHOD']);

////////////////////////////////////////////////////////////////////////////////
//restore cart

if (!empty($_REQUEST['cart'])) {
  $cartID = $_REQUEST['cart'];
  $cartStatus = restore_cart($cartID);

  if ($cartStatus == 'active') {
    //cart restored -- but this is a problem

    dc($cartID, 'User retrieved cart from a CC delay -- potential issue ');
    $errorMsg .= notifyPaymentFailure($_POST, 'Cart #$cartID retrieved after delay ','CC');
    wp_redirect(get_site_url(1, '/cart/?cart_restored', 'login'));
    die();
  } else if($cartStatus == 'paid' || $cartStatus == 'paying') {

    dc($cartID,'User sent to thank you page from cart restore');
    wp_redirect(get_thankyou_url($cartID));
    die;
  }
}

$cart = new stdClass();
$cartID = $cart->id = get_cart();
$cart->donor = get_cart_donor($cart->id);

////////////////////////////////////////////////////////////////////////////////

$change = $_REQUEST['item'];
if ($change == CART_BUY_GC) {
  wp_redirect('/impact-cards/');
  die;
}
if (!empty($change)) {
  $loc = explode('/',$change);
  $change = array_pop($loc);
  $loc = implode('/', $loc);

// TODO: log the location it came from

  $change = explode(',', $change);
  $giftID = array_shift($change);

  if (!empty($giftID)) {
    if (substr($giftID, 0,1) == 'x') {
      $itemID = substr($giftID, 1);
      cart_remove($cart->id, $itemID);

      // If the cart is empty, remove all discounts
      if (count_cart_items($cart->id) == 0)
        cart_remove_discount($cart->id);

    } else if (substr($giftID, 0,1) == 'y') {

      if(is_user_logged_in()) {
        $itemID = substr($giftID, 1);
        $thisCartID = $wpdb->get_var($wpdb->prepare(
          "SELECT cart->id FROM cartItem JOIN cart
          ON cart.id=cartItem.cart->id
          WHERE cart.userID=%d AND cartItem.id=%d",
          $current_user->ID,$itemID));
        cart_remove($thisCartID, $itemID);
      }
    } else if (substr($giftID, 0,1) == 'm') {
      if(is_user_logged_in()) {
        $itemID = substr($giftID, 1);
        $thisCartID = $wpdb->get_var($wpdb->prepare(
          "SELECT cart->id FROM cartItem JOIN cart
          ON cart.id=cartItem.cart->id
          WHERE cart.userID=%d AND cartItem.id=%d",
          $current_user->ID,$itemID));
        if($cart->id!=$thisCartID)
          cart_merge($cart->id, $thisCartID, $itemID);
      }
    } else {
      $qc = (string)array_shift($change);
      if ($qc == NULL)
        $qc = "+1";
      $qc = trim($qc);
      $qty = (string)trim($qc, "+-");
      $abs = strlen($qty) == strlen($qc);
      $qty = intval($qc);
      
      $eid = intval($_REQUEST['eid']);
      if ($eid < 0)
        $eid = 0;
      else if ($eid == 0)
        $eid = absint($_COOKIE['eid']);

      $bid = absint($_REQUEST["blog_id"]);
      if ($bid == 0) $bid = 1;
    
      cart_add($cart->id, $giftID, $qty, $abs, round($_REQUEST['amount']),null,null,null,null, $eid, $bid);

      if ($eid > 1)
        do_action('campaign_add_cart', $cart->id, $eid);
    }

    dc($cart->id,'User added gift #'.$giftID . " q$qty/e$eid");
  } else {
    dc($cart->id,'User failed adding blank gift');
  }

  if ($IS_AJAX) {
    global $siteinfo;

    $cart->items = cart_to_array($cart->id);
    $i = NULL;
    foreach ($cart->items as $item) {
      if ($item['giftID'] == $giftID) {
        $i = $item;
        break;
      }
    }

    ?>
    <form id="checkout" action="<?=get_site_url(1, '/cart/?cit=' . $i['id'], 'login') ?>" method="post">
      <h2>Thanks! This gift has been added to your cart.</h2>
      <input type="hidden" id="cart_count" value="<?= count_cart_items() ?>" />
      <input type="hidden" name="submit" value="yes" />
      <? display_cart_item($i); ?>
      <p class="actions">
        <a class="closebox link" href="#">continue browsing</a> or <a href="<?= get_site_url(1, '/cart/', 'login') ?>" class="button medium-button orange-button">check out now &raquo;</a>
      </p>
    </form>
    <?
    die();
  }
  wp_redirect( remove_query_arg(array('item','amount','eid','avg')) );
  die();
}


if(!empty($_GET['avg'])) { // adding agg var gift thru GET params
  dc($cart->id,'Adding avg item: '.$_GET['avg']);
  cart_add_avg($cart->id, $_GET['avg']); 

  if ($eid > 1)
    do_action('campaign_add_cart', $cart->id, $eid);
}

if ($_POST) {
  // We will refresh the page to remove the post
  $refresh = TRUE;

  // Payment button pressed?
  global $payment_method_titles;
  $pm = eor($_REQUEST['pm'], array_search(eor($_POST['submit'], $_POST['checkout_button']), $payment_method_titles));
}

////////////////////////////////////////////////////////////////////////////////
// POST submits are ANY of:
// 1. update CART ITEMS
// 2. update CONTACT
// 3. submit CC PAYMENT
////////////////////////////////////////////////////////////////////////////////

if (isset($_POST['is_cart'])) {
  // Cart info is included -- update

  // TODO: not sure this path is working
  $remove_items = $_POST['remove'];
  if (is_array($remove_items)) 
    cart_remove($cart->id, $remove_items);

  // Update quantities, etc. for each item
  $cart->items = cart_to_array($cart->id);
  foreach ($cart->items as $k=>$item) {
    $id = $item['id'];
    $gid = $item['giftID'];
    if ($gid == CART_USE_GC || $gid == CART_TIP)
      continue;

    $qty = intval($_POST["quantity_$id"]);
    $amt = from_money($_POST["amount_$id"]);
    $ref = $_POST["ref_$id"];
    $eid = intval($_POST["event_$id"]);
    $bid = intval($_POST["blog_$id"]);
    if ($bid<1) 
      $bid=1;

    if ($amt > 0 || $qty > 0) {
      dc($cart->id,"User updated item#$id gift#$gid qty$qty eid#$eid bid#$bid");
      cart_add($cart->id, $item['giftID'], $qty, true, $amt, !empty($ref) ? $ref : NULL, $id, NULL,NULL, $eid);
    }
  } // end foreach update item      

  $tip_rate = trim($_POST['tip']);
  if ($tip_rate !== '') {
    cart_set_tip($cart->id, $tip_rate);
    $cart->tip_rate = $tip_rate;
  }

  // Apply discount code - may result in a global error being added
  // May result in tip rate changing
  $code = trim($_POST['code']);
  if (!empty($code)) {
    $cart->result = (object)process_cart_discount($cart, $code);
    $refresh = $refresh && ($cart->result->status != 'error');
  }

  update_cart_data_sparse($cart->id, 'matchme', $_REQUEST['match-me'] == 1 ? TRUE : NULL);

  finalize_cart($cart);
}

if (isset($_POST['is_contact'])) {
  $cart->result = (object)process_cart_signin($cart, stripslashes_deep($_REQUEST));
  $refresh = $refresh && ($cart->result->status != 'error');
}

// If cart is impersonated, go back to the first page
if (is_numeric($cart->donor) && isset($_REQUEST['pay'])) {
  global $bp;

  if ($cart->donor != $bp->loggedin_user->id) {
    wp_redirect(remove_query_arg('pay')); die;
  }
}

if (isset($_REQUEST['cart_restored'])) {
  $cart->result = cart_error("We're sorry, an error occurred when we submitted your credit card payment.<br>Your card has not been charged.");
}

// DID THEY CLICK A PAYMENT BUTTON?
if ($refresh && !empty($pm)) {

  global $payment_method_pages;
  if(!array_key_exists($pm, $payment_method_pages)) {
    dc($cart->id, "Unknown payment method: $pm");
    wp_redirect( remove_query_arg('pm') ); die;
  }

  // No longer require signin
  // wp_redirect( site_url("/signin/?to=cart&pm=$pm") );

  // External payment processors
  $page = $payment_method_pages[$pm];
  if (!empty($page)) {
    dc($cart->id, "Redirecting to payment method page $pm $page");
    wp_redirect( $payment_method_pages[$pm] ); die;
  }

  finalize_cart($cart);

  $cart->result = process_pledges($cart);
  if (is_object($cart->result) && $cart->result->status == "error")
    $refresh = FALSE;
  else if ($pm == 'CC') {
    $cart->result = (object)process_creditcard_payment($cart, stripslashes_deep($_POST));
  } else if ($pm == 'GC') {
    $cart->result = (object)process_free_checkout($cart, stripslashes_deep($_POST));
  } else {
    $cart->result = cart_error('Sorry, we could not complete your payment.');
  }

  $refresh = $refresh && ($cart->result->status != 'error');
}

// UPDATES are finished.
// AJAX METHODS (even GET) MUST HAVE DONE ALL OF THEIR WORK BY NOW
if ($IS_AJAX) {
  if ($cart->result)
    die(json_encode($cart->result));

  // May have requested a particular item to be returned
  $cit = $_REQUEST['cit'];
  if ($cit > 0) {
    $where = array(
      $wpdb->prepare("id=%d", $cit)
    );
    display_cart_item(get_item_row($where, ARRAY_A));
  }
  die();
} else if ($refresh) {

  if ($cart->result)
    switch ($cart->result->status) {
      case 'success':
      case 'OK':
        wp_redirect($cart->result->data); die;
    }

  if ($_POST['checkout_button']) {
    // REDIRECT TO STEP 2
    wp_redirect( get_site_url(1,'/cart/') . '?pay' ); die;
  } 

  wp_redirect( remove_query_arg(array('item','amount','eid','avg')) );
}

////////////////////////////////////////////////////////////////////////////////
// BEGIN CART RENDER
////////////////////////////////////////////////////////////////////////////////

remove_action('syi_pagetop', 'draw_the_crumbs', 0);
add_filter('body_class', 'add_body_classes');

global $NO_SIDEBAR;
$NO_SIDEBAR = TRUE;

global $NO_SHARING;
$NO_SHARING = TRUE;

define('FB_PLACEMENT', 'none');
$is_empty = count_cart_items($cart->id) == 0;
finalize_cart($cart);

$eid = $cart->data->event_id;
if ($eid > 0) {
  // Is the cart empty?
  if( count_cart_items($cart->id) == 0) {
    $url = get_campaign_permalink($cart->data->event_id);
    wp_redirect(add_query_arg("msg","cart_empty", $url));
    die;
  }

  // Load the campaign's custom skin - will this always work?
  include_once('campaign-core.php');
  campaign_init($eid);
  remove_action('get_crumbs', 'member_crumbs');
}

get_header();

if ($is_empty) {
  ?>
  <div id="cart-page" class="standard-page cart-page evs">
    <div class="page-main cart-listing cart-<?=$cart->id?> cart-empty">
      <div id="page-sidebar" class="right page-sidebar">
        <div class="promo-widget">
          <? draw_promo_content("pay-sidebar", NULL, true); ?>
        </div>
      </div>
    <?

    global $GIFTS_LOC;
    $GIFTS_LOC = 'cart-empty';

    draw_cart_error($cart);

    global $post;
    setup_postdata($post);
    ?><section class="entry-content"><div class="topmost"><?
    the_content();
    ?></div></section><?

    gift_browser_widget(array(
      'page_title' => 'Give one of these life-changing gifts',
      'pre_load' => true,
      'shrink' => true,
      'causes' => array('featured'),
      'limit' => 6
    ));

    ?>
    </div>
  </div>
  <?
  
} else {
  ?>
  <form id="cart-page" action="<?= remove_query_arg('item') ?>" method="post" class="standard-form cart-page no-enter evs">
    <div class="page-main cart-listing cart-<?=$cart->id?>">
      <? $total = draw_the_cart(stripslashes_deep($_POST), $cart); ?>
    </div>
  </form>
  <?
}

get_footer();
die;

function draw_cart_error($cart) {
  if (display_form_error($cart->result)) {
    dc($cart->id, "Showed error: {$cart->result->data}");
  }
}

function cart_error_message() {
?>
  <span style="display:block; font-size:80%; margin-top:10px; color: #A44;">
  Having trouble with your order?  Please e-mail <a href="mailto:contact@seeyourimpact.org">contact@seeyourimpact.org</a> for assistance.
  </span>
<?
}

function draw_cart_header($cart) {
  global $context;

  if ($context != NULL && is_showing($context->campaign_page, 'header'))
    draw_gallery_part($context->gallery['campaign_header'], 'campaign_header');
}

function draw_cart_sidebar($cart) {
  global $context;

  if ($context != NULL && is_showing($context->campaign_page, 'note')) {
    draw_gallery_part($context->gallery['campaign_note'], 'campaign_note');
  } else {
    draw_promo_content("pay-sidebar", NULL, true); 
  }
}

function draw_the_cart($args, $cart) {
  global $error_gc_use, $error_process_cc;

  do_action('draw_cart_header', $cart);
  ?>
  <div id="page-sidebar" class="right page-sidebar">
    <div class="promo-widget">
      <? do_action('draw_cart_sidebar', $cart); ?>
    </div>
  </div>
  <?

  if (isset($_GET['pay'])) {
    if ($cart->total > 0) {
      ?><a class="right white-button button small-button" href="<?= get_cart_url() ?>" style="margin-top: 15px;">&laquo; back to your donation</a><?
      ?><h1 class="entry-title">Check out</h1><?
    } else {
      ?><h1 class="entry-title">Complete donation</h1><?
    }

    draw_cart_error($cart);

    display_cart_signin_page($args, $cart);
    // $focused = $cart->result->status != "error";
    display_cart_payment_page($args, $cart, $focused);
    return;
  }

  do_action('draw_cart_top', $cart);
  ?>
  <h1 class="entry-title">Your donation</h1>
  <input type="hidden" name="is_cart" value="yes">
  <?
  draw_cart_error($cart);

  $subtotal = 0;
  $discounts = array();
  $gcs = array();

  $only_pledges = TRUE;

  foreach ($cart->items as $item) { //walk through cart items
    if ($item['quantity'] <= 0)
      continue;

    $row_price = 0;
    switch ($item['giftID']) {
      case CART_BUY_GC:
        $gcs[] = $item;
        $only_pledges = FALSE;
        break;
   
      case CART_USE_GC:
        $discounts[] = $item;
        break;

      case CART_TIP:
        // Nothing to do
        break;

      case CART_PLEDGE:
        display_cart_item($item, $readonly);
        // No payment due now
        break;

      default:
        $subtotal += display_cart_item($item, $readonly);
        $only_pledges = FALSE;
        break;
    }
  }

  ////////////////////////////////////////////////////////////////////////////////

  $subtotal_gc = display_gcs($gcs);
  ?>
    <input type="hidden" name="subtotal" value="<?=$subtotal?>"/>
    <input type="hidden" name="subtotal_gc" value="<?=$subtotal_gc?>"/>
  <?

/*
?>

<div class="collapser" style="margin-top: 20px; position:relative;">
  <div style="position: absolute; z-index:100;">
    <input id="add-gift-card" type="submit" name="submit" value="<?=CART_BUY_GC_TITLE?>" class="button medium-button green-button ev">
    <a id="add-another" href="<?=$add_gift_url?>" class="button medium-button green-button ev">Continue shopping</a>
  </div>
  <div class="cart-item" style="position:relative; left:0;">
    <div class="calc-total">
<!--
      <div class="right item-total"><?= as_money($subtotal) ?></div>
      Subtotal:<br style="clear:right;"/>
-->
    </div>
  </div>
</div>

<?
*/

  if($subtotal + $subtotal_gc > 0) {
  ?>
    <div class="cart-item tip-item" id="tip-item">
      <span class="item-img"><img src="<?= _C('/wp-content/images/syi-tip-image.png')?>" width="60" height="45"></span>
      <div class="txt"><?=draw_promo_content('tip-info-promo');?></div>
      <div class="calc">
        <? $subtotal += display_tip_ddl($cart->id, $subtotal + $subtotal_gc, $cart->tip_rate, 'tip'); ?>
      </div>
    </div>
  <? 
  }

  //display discounts line item
  foreach ($discounts as $discount) {
    $spend = get_acct_details($discount['ref']);
    $message = $discount['message'];
    if (empty($message))
      $message = CART_USE_GC_DESCRIPTION;

    ?>
    <div class="cart-item cart-discount" id="item-<?=$discount['id']?>">
      <span class="item-img"><img src="<?= __C('images/impact-card.png') ?>" alt="" height="45" width="60" style="border: 0px none;" /></span>
      <div class="txt txt">
        <div class="title"><?= as_html($message, true); ?></div>
        #<?=$discount['ref']?>
        <?
        if ($spend->type == ACCT_TYPE_GIVE_ANY || $spend->type == ACCT_TYPE_MATCHING) {
          $params = show_acct_params($spend->params);
          if (!empty($params))
            $params = " (<b>" . htmlspecialchars($params) . "</b>)";
          echo ": $spend->firstName $spend->lastName $params";
        }
        ?>
      </div>
      <div class="calc">
        <div class="item-total">
          -<?=as_money(abs($discount['price']))?><br/>
          <? if (!$readonly) { ?><a class="remove-checkbox" href="/cart/?item=x<?=$discount['id']?>"><img src="<?= _C('/wp-content/images/remove.gif') ?>" title="remove"></a><? } ?>
        </div>
      </div>
    </div>
    <?

    $subtotal_discount += abs($discount['price']);
  }

  $total = $subtotal + $subtotal_gc - $subtotal_discount;

  ////////////////////////////////////////////////////////////////////////////////
  //display total

  if ($total != 0 || !$only_pledges) {
  ?>
    <input type="hidden" name="total" value="<?=$total?>"/>
    <div class="cart-item total-item">
      <? if ($total > 0 && GIFTCERT_PAYMENT_ENABLED && count($discounts) == 0) { ?>
      <div class="left collapser expanded <? if (!is_super_admin()) echo 'js-hide' ?>" id="code-entry">
        <label for="code" class="if-expanded">
          Impact card or promo: 
          <input style="padding: 2px" type="text" name="code" placeholder="enter code here" id="code" size="12" maxlength="15" />
          <input id="apply-gift-code" type="submit" name="submit" value="Apply" class="button small-button white-button ev" style="padding:3px 10px;" />
        </label>
        <label for="code" class="expander if-collapsed" id="code-click">Impact card or promotional code? <u class="link">click here</u></label>
      </div>
      <? } ?>
      <div class="calc item-total"><?= as_money($total) ?></div>
      <div class="right" style="font-weight: bold; text-align:right; margin: 5px 20px 0 0;"><?= $total > 0 ? 'Your total' : 'Total due now' ?>:</div>
    </div>
  <?
  }

  ?><div><?

////////////////////////////////////////////////////////////////////////////////

  ?></div><?

  ?>
  <div class="right">
    <? if ($cart->total > 0) { ?>
      <div style="font-size: 10pt; margin: 0 20px 20px; line-height: 20px;">
        <input type="checkbox" class="left" id="match-me" name="match-me" value="1" style="margin-left: -20px;" <? if ($cart->data->matchme) echo ' checked'; ?> />
        <label for="match-me"><b>My company matches donations.</b><br>
        Please send more info about submitting matches.</label>
      </div>
    <? } ?>
  </div>
  <?

  if ($only_pledges) {
    ?><div style="clear:both; margin: 30px -10px; padding: 20px; border-top: 1px solid #eee; background: #EAF3FF; border-radius: 10px; padding-right: 40px;"><?
    display_cart_signin_form($args, $cart);
    ?></div><?
  }

  global $payment_method_titles;
  ?>
  <div class="right" style="clear:both; width: 450px; text-align:right;">
   <input type="submit" class="medium-button white-button button conv" name="update_button" value="<?= CART_UPDATE_LABEL ?>">
    <?  if (($cart->total == 0) && ($cart->donor > 0 || $only_pledges)) { ?>
      <input type="submit" class="payment-button big-button orange-button button conv" name="submit" value="<?= $payment_method_titles['GC'] ?>">
    <? } else { ?>
      <input type="submit" class="payment-button big-button orange-button button conv" name="checkout_button" value="<?= CART_CHECKOUT_LABEL ?>">
    <? } ?>
  </div>
  <?

  return $total;

} // end draw_the_cart
