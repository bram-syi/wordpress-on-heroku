<?
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once(ABSPATH.'/wp-includes/syi/syi-includes.php');
include_once(ABSPATH . "/syi/transaction.php");

// Define one or both of these to control which transaction processor
// processOrder will use.
define('PROCESS_ORDER_NEW', TRUE); // TRUE = use transaction.php 
define('PROCESS_ORDER_V1', TRUE); // TRUE = use the older order process code
define('PROCESS_ORDER_TEST', TRUE); // Do we support TEST mode donations?

define('APP_REQUEST', ''); // DISABLE W3TC

function require_payments($dir, $files) {
  chdir("google");
  require_once('library/googleresponse.php');
  require_once('library/googlemerchantcalculations.php');
  require_once('library/googleresult.php');
  require_once('library/googlerequest.php');
}

class Donation {
  public $transaction = array();
  public $donor = array();
  public $cart = array();
  public $discounts = array();
  public $raw;
  public $data;
  public $notes;
  
  public function __construct($r = '') {
    $this->raw = $r;

    // LOG IT AND GET A PAYMENT ID
  }
}

global $payment_debug;
$debug = '';

global $PAYMENT_METHODS;
$PAYMENT_METHODS = array( '', 'PP', 'CC', 'GG', 'AM', 'GC', 'MG', 'XC', 'SP');

function processOrder($order, $suborder = FALSE) {
  global $wpdb, $PAYMENT_METHODS, $emailEngine;

  $code_check_id = uniqid();
  Txn::log("processOrder: $code_check_id: start");

  $emailEngine = new EmailEngine();
  $userID = 0;

  // for merged thankyou email purpose
  $gcs = array(); // V1
  $discounts = array(); 
  $vargifts = array(); // V1

  // Build the donation
  $d_payments = array(); // NEW
  $d_purchases = array(); // NEW
  $d_frs = array();

try {
  // GET: tip, notifyme, giftID, referral, discount, code, mg, recipientDonorID
  $payment = $order->payment;
  $paymentMethod = $payment->method;
  $paymentMethodID = intval(array_search($payment->method, $PAYMENT_METHODS));

  $payment->amount = $payment->gross - $payment->tip;

  if (PROCESS_ORDER_NEW) {
    try {
      if ($payment->method != 'GC') {
        $p = new PayTxn($payment->amount + $payment->tip, $paymentMethodID, $payment->id, $order->notes, $order->raw);
        Txn::log('new payment: '.json_pretty($p));
        $d_payments[] = $p;
      }
      Txn::log("processOrder: $code_check_id: " . __LINE__);
    }
    catch (Exception $e) {
      dp('PROCESS_ORDER_NEW exception: '.var_export($e, true));
    }
  }
  if (PROCESS_ORDER_V1) {
    // First thing: always log payment info so we don't miss anything
    $sql = $wpdb->prepare("INSERT "
      . "INTO payment(raw,data, amount, tip, discount, provider, dateTime, txnID, notes) "
      . "VALUES('%s','%s', '%s','%s','%s',%d, NOW(), '%s', %s)",
      $order->raw, json_encode($payment), $payment->amount, $payment->tip, 0, $paymentMethodID, $payment->id, $order->notes);
    dp_sql($sql);
    $paymentId = intval($wpdb->insert_id);
  }

  $cart = $order->cart;
  $donor = $order->donor;
  if (is_numeric($donor)) { // Numeric = donor ID
    $donorId = intval($donor);
    $donor = (object)get_donor_by_id($donorId);
    $donor->first = $donor->firstName;
    $donor->last = $donor->lastName;
    if ($order->impersonate !== FALSE)
      $order->impersonate = TRUE;
  } else if ($donor === NULL)
    $donor = $order->payer;

  dp(as_money($payment->amount) . "+" . as_money($payment->tip));
  dp("$payment->method($paymentMethodID)");
  dp("from $donor->first $donor->last ($donor->email)");
  dp($payment->status, "status");
  dp(as_money($payment->gross), "total");
  dp($donor->referral, "refer");
  dp($cart->id, "cart ID");
  dp($payment->id, "transaction ID");
  dp($paymentId, "payment #");
  dp($payment->memo, "memo");
  dp($order->impersonate, "on-behalf");
  dp("------------------------------------");

  // MAKE SURE THERE ARE NO DUPLICATES
  if (PROCESS_ORDER_NEW) {
    try {
      // TODO NEW: ensure no duplicates
      Txn::log("processOrder: $code_check_id: " . __LINE__);
    }
    catch (Exception $e) {
      dp('PROCESS_ORDER_NEW exception: '.var_export($e, true));
    }
  }
  if (PROCESS_ORDER_V1) {
    $duplicate = $wpdb->get_var($sql = $wpdb->prepare(
      "SELECT txnID FROM payment WHERE txnID = '%s' AND id <> %d", $payment->id, $paymentId));

    if ($duplicate != NULL) {
      $wpdb->query($wpdb->prepare(
        "DELETE FROM payment where ID=%d",
        $paymentId));

      dp("FOUND DUPLICATE payment with TXN #$payment->id, DELETED #$paymentId, STOP PROCESSING" ."\n".$sql."\n ORDER DUMP: \n".print_r($order,true));
      if (!$suborder)
        dp_end("", 1, "Duplicate payment #$paymentId");
      return;
    }
  }

  // TODO: check if cart is missing
  if ($cart->id == 0) {
    dp("NO CART ASSOCIATED WITH TXN #$payment->id STOP PROCESSING");
    if (!$suborder)
      dp_end("", 1, "Unhandled payment");
    return;
  } 

  // CHECK CART INTEGRITY
  if (PROCESS_ORDER_NEW) {
    // Nothing
  }
  if (PROCESS_ORDER_V1) {
    // Update payment status
    $sql = $wpdb->prepare(
      "UPDATE cart SET status='paying',paymentID=%d WHERE id=%d", 
      $paymentId, $cart->id);
    dp_sql($sql);
  }

  // In certain conditions we don't want to send a thank you mail
  // confirming the transaction.  Most cases we do...
  Txn::log("order: ".json_pretty($order));
  $on_behalf = ($order->impersonate == TRUE || $order->is_match == TRUE);

  // Update cart status
  if ($on_behalf) {
    $userID = intval($donor->user_id);
    if ($userID > 0) 
      dp("IMPERSONATING USER #$userID");
    $cart->userID = $userID;
  } else {
    // Look for user associated with cart
    $userID = intval(get_cart_user($cart->id));
    dp("CART USER=#$userID");

    if ($userID > 0) 
      $donor->validated = true; // Logged in user always validated
    list($donorId,$userID) = integrate_user_donor($userID, $donor);
  }

  if ($userID != 0) {
    dp("\nUPDATING CART USER TO USER #$userID");
    dp_sql($wpdb->prepare(
      "UPDATE cart SET userID=%d WHERE id=%d", 
      $userID, $cart->id));
  } else {
    dp("\nDONOR #$donor->ID $donor->email " . ($donor->validated?"":"(not validated)"));
  }

  // Match items against the cart (unless we know it's valid)
  if ($cart->validate !== FALSE) {
    $stored_cart = cart_to_array($cart->id, true);
    $cart_valid = validate_paid_cart($order, $stored_cart);
    if (!$cart_valid) {
      // TODO: What happens if cart is not valid - items mismatch?
      debug("CART: " . print_r($order, TRUE) . "\n\nSTORED: ". print_r($stored_cart, TRUE), TRUE, "CART #$cart->id INVALID");
    }
  }

  // TODO: distribute the tip (and bank it)
  if ($payment->amount > 0)
    $tip_pct = round($payment->tip / $payment->amount, 2);
  dp("TIP: $$payment->tip / AMT: $$payment->amount = PCT: $tip_pct");

////////////////////////////////////////////////////////////////////////////////

  $data = get_cart_data($cart->id);
  if ($data->matchme) {
    dp('DONOR REQUESTED MATCHING INFO');
    queue_mail_simple(MAIL_ADMIN_MATCHINFO_PRIORITY,'Administrator',
      get_email_address("outreach"), 'Matching info request',
      "$donor->first $donor->last ($donor->email) requested information about matching donations");

    SyiMailer::send(
      $donor->email,
      'Corporate Matching Info',
      'match_request',
      array(
        'From' => '"SeeYourImpact.org" <contact@seeyourimpact.org>',
        'syi-bcc' => get_email_address('outreach'),
        'X-Tag' => 'email:match_request',
      ),
      array(
        'first_name' => $donor->first,
      )
    );
  }
  if ($data->share_email) {
    dp_sql($wpdb->prepare(
      "UPDATE donationGiver SET share_email=1 WHERE id=%d",
      $donorId));
  }

////////////////////////////////////////////////////////////////////////////////

  // Apply discounts
  if (is_array($order->discounts)) {
    foreach ($order->discounts as $k=>$discount) {

      if (PROCESS_ORDER_NEW) {
        try {
          $acct = Txn::getAccount($discount->ref);
          if ($acct) {
            $d_payments[] = new SpendCardTxn($discount->price, $acct, NULL, $discount->message);
            if ($acct->type == ACCT_TYPE_GIVE_ANY)
              $on_behalf = true;
          } else {
            // TODO: Error case ... once account tables are merged
            // because until then, the discount->ref can only point at one or the other
          }
          Txn::log("processOrder: $code_check_id: " . __LINE__);
        }
        catch (Exception $e) {
          dp('PROCESS_ORDER_NEW exception: '.var_export($e, true));
        }
      }
      if (PROCESS_ORDER_V1) {
        $order->discounts[$k]->acct_trans_id =
          processDiscount($discount, $paymentId, $donorId);

        // Don't send a thank you when allocating on behalf of someone.
        $details = get_acct_details($discount->ref);
        if ($details && $details->type == ACCT_TYPE_GIVE_ANY)
          $on_behalf = true;
      }

      $discount->message = "Gift code $discount->ref applied";
      $discounts[] = $discount;
    }
  }

  // SCAN ITEMS.  Get total quantity

  // Create a new donation
  if (PROCESS_ORDER_NEW) {
    // Don't submit the donation yet -- wait until we've added the purchases
  } 
  if (PROCESS_ORDER_V1) {
    $sql = $wpdb->prepare(
      "INSERT INTO donation (paymentID, donationDate, donorID,"
      ."donationAmount_Total, notificationsSent, instructions,"
      ."tip, notifications, test) "
      . "VALUES(%d, NOW(), %d, %d, 0, '%s', '%f', '%d', %d)",
      $paymentId, $donorId, $payment->amount, $payment->memo,
      $payment->tip, $donor->contactme, FALSE);
    dp_sql($sql);
    $donationId = intval($wpdb->insert_id);

    dp("NEW DONATION #$donationId");
  }

//****************************************************************************//

  dp('PROCESSING FULL AMT AVGS POST PAYMENT');

  // NOTE: In the NEW processing, we don't need to do this.
  if (PROCESS_ORDER_V1) {
    foreach ($cart->items as $k=>$item) { // ITEM LOOP FOR AVG
      $tg = get_avg_tgi($item->gift_id, true);
      if (!empty($tg)) {

        $full_count = floor($item->price / $tg->unitAmount);	
        $left_amt = $item->price - ($tg->unitAmount * $full_count);
        if ($full_count > 0) {
          $i = new stdClass;
          $i->id = 0;
          $i->gift_id = $tg->id;
          $i->price = $tg->unitAmount;
          $i->quantity = $full_count;
          $i->ref = $item->ref;

          $cart_item = $stored_cart[$item->id];

          if (!isset($item->event_id)) // GETTING ITEM EVENT
            $i->event_id = intval($cart_item['event_id']);
          else
            $i->event_id = intval($item->event_id);		

          $i->blog_id = intval($cart_item['item_blog_id']); // GETTING ITEM BLOG
          if ($i->blog_id == 0) $i->blog_id = 1;

          // Re-check matching, because it might have changed since the item was added.
          // Also, never match a match!
          $i->matchingAcct = $order->is_match ? 0 : eor(get_event_match($i->event_id, $i), $i->matchingAcct);

          dp('ADDING NEW CART ITEM FOR FULL AMT AVG (NOT CHANGING CART DB): '."\n".print_r($i,true));  
          $cart->items[] = $i;

          dp('ADJUSTING AVG ITEM PRICE FROM '.as_money($cart->items[$k]->price).' TO '.as_money($left_amt));
          $cart->items[$k]->price = $left_amt;
        }	  
      }
    } // END ITEM LOOP FOR AVG
  }

  Txn::log("cart: ".json_pretty($cart));
////////////////////////////////////////////////////////////////////////////////  
  foreach ($cart->items as $item) { // GIFT ITEM LOOP START
////////////////////////////////////////////////////////////////////////////////

    if ($item->price == 0) {
      dp('IGNORING $0 AMOUNT ITEM: '."\n".print_r($item,true)); 
      continue;
    }
    
    if ($item->gift_id == CART_BUY_GC) { // IF GC BUY 
      process_gift_card($item, $donorId, $paymentId); 

      if (PROCESS_ORDER_NEW) {
        try {
          // Note: for now, we store tip in the account, to be released with each
          // allocation.  After we get rid of the legacy code we could switch this to
          // immediately claim the tip, and allocate gifts without tip.
          $acct = Txn::generate_random_code();
          $d_purchases[] = new DepositTxn($acct, $item->price, 'buy gc', $item->tip, $item->event_id, $donationID);
          Txn::log("processOrder: $code_check_id: " . __LINE__);
        }
        catch (Exception $e) {
          dp('PROCESS_ORDER_NEW exception: '.var_export($e, true));
        }
      }
      if (PROCESS_ORDER_V1) {
        $gcs[] = $item;
      }
      continue;
    } // END IF GC BUY 

    $cart_item = $stored_cart[$item->id];
    //dp("item " . print_r($cart_item, true));

    if (!isset($item->event_id)) // GETTING ITEM EVENT
      $item->event_id = intval($cart_item['event_id']);
    if ($item->event_id > 0)
      $d_frs[$item->event_id] = TRUE;

    $item->blog_id = intval($cart_item['item_blog_id']); // GETTING ITEM BLOG
    if ($item->blog_id == 0) 
      $item->blog_id = 1;

    // Calculate tip on this item
    $item->tip = round($item->price * $tip_pct, 2);

    // Find this gift
    dp("GIFT #$item->gift_id qty$item->quantity");
    $gift = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM gift WHERE id=%d", $item->gift_id));
    if ($gift == NULL) {
      dp("GIFT DOES NOT EXIST"); 
      continue;
    }

    // Is this gift matched?
    if (!isset($item->matchingAcct))
      $item->matchingAcct = $cart_item['matchingAcct'];
    $mg_id = $item->matchingAcct;
    if ($mg_id > 0) {
      dp("THIS GIFT IS MATCHED BY ACCOUNT $mg_id");
    }

    $itemIDs = array();
    $da_code = NULL;

    if (PROCESS_ORDER_V1) {
      for ($i = 0; $i < $item->quantity; $i++) { // START GIFT ITEM QTY LOOP

        $price = $item->price;
        if ($price == -1) $price=$gift->price;

        if ($item->gift_id == CART_GIVE_ANY) { // IF GIVE ANY

          $da_id = insert_donation_acct($donorId, $item->price + $item->tip, ACCT_TYPE_GIVE_ANY, 
            $paymentId, $donorId, $donorId, null, array('tip_rate' => $tip_pct), ($item->blog_id>1?$item->blog_id:1), ($item->blog_id>1?0:$item->event_id));
          dp("PUT $$item->price + $$item->tip IN ACCOUNT #$da_id ".($item->blog_id>0?"blog #$item->blog_id":($item->event_id>0?"event #$item->event_id ":'')));
          
          $vargifts[] = $item; // for merged thankyou email
        } else { // IF NOT GIVE ANY - REGULAR GIFT

          $sql = $wpdb->prepare("INSERT INTO donationGifts 
            (donationID, giftID, unitsDonated, amount, blog_id, 
            towards_gift_id,campaign,distributionStatus,
            fundTransferStatus,tip,event_id,matchingDonationAcctTrans) 
            VALUES (%d,%d,1,%f,%d,%d,%s,1,1,%f,%d,%d) ",
            $donationId, $gift->id, $price,$gift->blog_id,
            $gift->towards_gift_id, $gift->campaign, $item->tip, $item->event_id, 0);
          dp_sql($sql);
          $dg_id = $wpdb->insert_id;
          $itemIDs[] = $dg_id;
          dp("DONATION GIFT #$dg_id");

          if (!is_avg($item->gift_id)) {
            processGift($gift, $price); // PROCESS ACTUAL INVENTORY MGMT
          } else {
            dp(" AVG NOT PROCESSING INVENTORY");			
          }		

        } // END IF GIVE ANY

        if ($mg_id > 0)
          make_matching_donation($mg_id, $item);

      } // END GIFT ITEM QTY LOOP
    }
    if (PROCESS_ORDER_NEW) {
      try {
        // TODO: update $vargifts[] and $itemIDs[] or whatever is needed for later updates
        // TODO process gift (for stock update, etc)
        // TODO process matching gifts

        if ($item->gift_id == CART_GIVE_ANY) {
          $gift = new BuyTxn($item->price * $item->quantity + $item->tip * $item->quantity,0, 0, $item->event_id, $item->blog_id);
          $gift->copy_acct_id = $da_id; // <<- This is from PROCESS_ORDER_V1, to sync accounts
          $d_purchases[] = $gift;
        } else if ($on_behalf) {
          $acct_id = $d_payments[0]->acct->id;
          if (!$acct_id) {
            error_log('unable to determine account id from d_payments: '.json_pretty($d_payments));
            Txn::log('unable to determine account id from d_payments: '.json_pretty($d_payments));
          }
          // still call this even if $acct_id null, for now
          Txn::allocateGifts($acct_id, $item->price * $item->quantity, $item->tip * $item->quantity, $item->gift_id, $donorId);
        } else {
          $gift = new BuyTxn($item->price * $item->quantity, $item->tip * $item->quantity, $item->gift_id, $item->event_id, $item->blog_id);
          $gift->copy_acct_id = $da_id; // <<- This is from PROCESS_ORDER_V1, to sync accounts
          $d_purchases[] = $gift;
        }
        Txn::log("processOrder: $code_check_id: " . __LINE__);
      }
      catch (Exception $e) {
        dp('PROCESS_ORDER_NEW exception: '.var_export($e, true));
      }
    }

    // New order processing doesn't need these as it can all be calculated at SQL query time
    if (PROCESS_ORDER_V1) {
      // Update discount transactions with donation ID and donationGift IDs
      if (is_array($order->discounts) && count($order->discounts) > 0) {
        $itemIDs_str = implode(', ',$itemIDs);
        foreach ($order->discounts as $discount) {

          if (isset($discount->acct_trans_id) && $discount->acct_trans_id!=0) {
            $wpdb->query($wpdb->prepare(
              "UPDATE donationAcctTrans SET note=CONCAT(note,%s) WHERE id=%d",
              " donation #$donationId donationGifts $itemIDs_str",
              $discount->acct_trans_id));
          }
        }
      }
    }


    // TODO NEW: Move these notifications into Transaction?
    // Or better yet, send a periodic digest rather than 1-by-1 mails
    // For now, skip sending mails on < $5 items
    if ($gift->id != CART_GIVE_ANY && $price >= 5) { 
      $admin = get_blog_option($gift->blog_id, 'admin_email');
      $items = implode(',', $itemIDs);

      $admins = getUsersByRoleByBlogId('administrator', $gift->blog_id);
      dp("NOTIFYING ADMINS OF BLOG#$gift->blog_id ABOUT GIFT #$item->gift_id qty$item->quantity \n".
	    print_r($admins,true));
/* TODO: $gift is incorrect, need to fix before we re-enable
      queue_mail(MAIL_ADMIN_GIFTBUY_PRIORITY,$admin,$gift->blog_id,3,7,"$donationId/$items",0,$admins, '','','', true);
*/
    }

////////////////////////////////////////////////////////////////////////////////
  } // END GIFT ITEM LOOP  
////////////////////////////////////////////////////////////////////////////////

//****************************************************************************//

  if (PROCESS_ORDER_NEW) {
    try {
      if (count($d_purchases) > 0) {
        Txn::makeDonation($donorId, array(
          'payments' => $d_payments,
          'purchases' => $d_purchases
        ));
      }
      Txn::log("processOrder: $code_check_id: " . __LINE__);
    }
    catch (Exception $e) {
      dp('PROCESS_ORDER_NEW exception: '.var_export($e, true));
    }
  }

  if (!$on_behalf && count($cart->items)>0) {

    $donor_email = get_user_email($donorId, 'thanks');
    if (empty($donor_email)) {
      dp("NO THANK YOU MAIL SENT");
    } else {
      if (PROCESS_ORDER_NEW) {
        try {
          // TODO NEW: Build the thank you mail from the transactions
          Txn::log("processOrder: $code_check_id: " . __LINE__);
        }
        catch (Exception $e) {
          dp('PROCESS_ORDER_NEW exception: '.var_export($e, true));
        }
      }
      if (PROCESS_ORDER_V1) {
        $n = new Notification($donationId,21);
        $extras = array('gcs'=>$gcs,'discounts'=>$discounts,'vargifts'=>$vargifts);
        dp("EXTRAS: ".print_r($extras,true));
        $n->build_thankyou_content($extras);
      }

      dp("SENDING MERGED THANKYOU EMAIL to $n->recipient_email - $n->recipient_name");  

      // at this point, $n->content is almost done, it stil has $body_tpl_tags that 
      // need to get str_replace()'d

      SyiMailer::send(
        "$n->recipient_name <$n->recipient_email>",
        $n->subject,
        'clean',
        array(
          'From' => 'SeeYourImpact.org <impact@seeyourimpact.org>',
          'syi-bcc' => get_email_address('outreach'),
          'X-Tag' => 'email:donationthankyou',
        ),
        array(
          'content' => $n->replace_body_tpl_tags(),
        )
      );

      if (is_fb_connect_enabled()) { 
        dp("CHECKING FB PUBLISH ON DONATE");
        $fb = new SyiFacebook(get_current_user_id());
        $fbp = $fb->publish_donation($donationId);
        if(get_blog_option(1,'fb_debug')==1 && $fbp>=0)
          debug(print_df(true),true,(var_export($fbp,1)).'FB PUBLISH - THANKS');
      }

    }
  }

  dp("ORDER: " . print_r($order, TRUE));

////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////

  dp('PAYMENT COMPLETED - CART STATUS SET TO PAID');
  if ($cart->id > 0) {
    // Update cart status
    dp_sql($sql = $wpdb->prepare(
      "UPDATE cart SET status='paid' WHERE id=%d", $cart->id));
    dp(get_thankyou_url($cart->id), "THANK YOU URL");

    if ($recycled = recycle_carts($userID, $cart->id)) // intentional assignment
      dp("RECYLCLED SIBLING CARTS OF USER #$userID: $recycled items");
  }

  // UPDATE CAMPAIGN STATS
  foreach ($d_frs as $fr_id => $v) {
    dp("FUNDRAISER STATS: ". update_campaign_stats($fr_id, FALSE, TRUE));
  }

  $al = $on_behalf ? " allocated" : "";
  dp("PURCHASE COMPLETE");
  if (!$suborder)
    dp_end("", 1, "Payment$al #$paymentId");

} catch (Exception $e) {
  dp("EXCEPTION: " . print_r($e, true));
  if (!$suborder)
    dp_end("", 1, "Payment Error");
}

  Txn::log("processOrder: $code_check_id: end");
}

function make_matching_donation($acct_id, $item) {
  if ($acct_id <= 0)
    return;

  dp("BUILDING MATCHING ORDER FOR " . print_r($item,TRUE));

  // TODO: need some setting to parameterize these rules
  $match_amount = $item->price;
  $match_exact_gift = FALSE;
  $match_tip = tip_matched($acct_id) ? $item->tip : 0;

  if ($match_tip > 0)
    dp("MATCHED TIP: $$match_tip");

  if ($item->gift_id == CART_GIVE_ANY || !$match_exact_gift) {

    $account = get_donation_account($acct_id);
    $items = array();
    $items[] = (object)array(
      'price' => $match_amount,
      'quantity' => 1,
      'tip' => $match_tip,
      'gift_id' => CART_GIVE_ANY,
      'displayName' => 'Matching donation'
    );

    $order = build_order($account, NULL, $items, $match_tip, $item->event_id, TRUE, 'Matching donation', FALSE);
    if ($order === NULL)
      dp(" UNABLE TO PROCESS MATCH");
    else {
      dp(" PROCESSING MATCHING DONATION ");
      processOrder($order, TRUE);
    }
  } else {
    // deduct from account
    $datId = insert_donation_acct_trans($mg_id, 
      -($match_amount+$match_tip), $paymentId, 
      "Match donation #$donationId item #$dg_id: " 
        . as_money($unit_net_donation) 
        . ($match_tip == 0) ? "" : ("+" . as_money($match_tip) . " tip"),
      true);
    dp(" DEDUCTED $match_amount+$match_tip FROM MATCHING ACCT $mg_id");

    $sql = $wpdb->prepare("INSERT INTO donationGifts 
      (donationID, giftID, unitsDonated, amount, blog_id, 
      towards_gift_id,campaign,distributionStatus, 
      fundTransferStatus,tip,matchingDonationAcctTrans,event_id) 
      VALUES (%d,%d,1,%f,%d,%d,%s,1,1,%f,%d,%d) ",
      $donationId, $gift->id, $match_amount, $gift->blog_id,
      $gift->towards_gift_id, $gift->campaign, $match_tip, $datId, $item->event_id);
    dp_sql($sql);
    $dg_id = $wpdb->insert_id;
    dp(" MATCHING GIFT: #$dg_id");

    if (!is_avg($item->gift_id)) {          
      processGift($gift, $item->price); // PROCESS MATCH INVENTORY MGMT
    }
  } 
}

function build_order_error($e, $debug) {
  if ($debug)
    echo '<div class="error">' . esc_html($e) . '</div>';
  return NULL;
}

function build_order($account, $donorID, $items, $tip, $event = 0, $match = FALSE, $notes = '', $debug = FALSE) {
  global $wpdb;

  if (empty($account))
    return build_order_error('Please specify a payment account.', $debug);

  if ($account->donationAcctTypeId == ACCT_TYPE_MATCHING)
    $match = TRUE;

  if (empty($donorID)) {
    $donorID = $account->donorId;
  }

  $order = new Donation(print_r($_REQUEST, true));

  $order->donor = $donorID;
  $donor = get_donor_by_id($donorID);
  if ($donor == NULL)
    return build_order_error('Please specify a valid donor.', $debug);
  $order->impersonate = TRUE;
  $order->notes = $notes;
  if ($match)
    $order->is_match = TRUE;

  if ($debug) 
    echo "<div>Donor #{$donor->ID}: {$donor->firstName} {$donor->lastName}</div>";

  $order->payment = new stdClass;
  $order->payment->status = 'Paid';
  $auto = $match ? 'MATCH' : 'AUTO';
  $order->payment->id = "SYI-$auto-".date('Y-m-d H:i:s');
  $order->payment->method = 'GC';

  $order->cart = new stdClass;
  $wpdb->insert('cart', array(
    'userID' => $donor->user_id,
    'status' => 'temporary',
    'type' => 'cart'));
  $order->cart->id = $wpdb->insert_id;
  $order->cart->validate = FALSE;

  $order->cart->items = array();
  foreach ($items as $i) {
    $msg = "";
    $wanted = intval($i->unitsWanted);
    if ($i->giveany)
      $wanted = "";
    else if ($wanted <= 0)
      $wanted = ', <b style="color:red;">' . $wanted . '</b> left';
    else 
      $wanted = ", $wanted left";
    $cha = get_blog_domain($i->blog_id);
    if ($i->quantity > 1) {
      $qty = "$i->quantity x ";
    } else
      $qty = "";

    if ($i->gift_id == CART_GIVE_ANY)
      $msg .= "\$$i->price <b>$i->displayName</b>";
    else
      $msg .= "$cha: $qty\$$i->price for <b>{$i->displayName}</b> [#$i->gift_id$wanted]";
    if ($match) {
      $i->match = TRUE;
      $msg .= " (match)";
    }
    if (!empty($event)) {
      $i->event_id = $event;
      $msg .= " (fundraiser $event)";
    }

    if ($debug)
      echo "<div>$msg</div>";

    $order->payment->gross += $i->price * $i->quantity;
    $order->cart->items[] = $i;
  }

  $order->payment->tipped = $order->payment->gross;
  if (endswith($tip, '%')) {
    $tip = (floatval($tip) * $order->payment->gross) / 100.0;
  }
  $order->payment->tip = $tip;
  $order->payment->gross += $tip;

  if (count($order->cart->items) == 0)
    return build_order_error('Please specify one or more items.', $debug);

  if ($debug)
    echo "<div>Total: " . as_money($order->payment->gross) . " (including " . as_money($order->payment->tip) . " tip)</div>";

  // Only check balances when allocating, not matching
  if (!$match && (floatval($account->balance) < $order->payment->gross))
    return build_order_error("Not enough money in account $account->code", $debug);

  $discount = new stdClass;
  $discount->gift_id = CART_USE_GC;
  $discount->price = -$order->payment->gross;
  $discount->quantity = 1;
  $discount->ref = $account->code;
  $order->discounts = array( $discount );

  return $order;
}


function process_gift_card(&$item, $donorId, $paymentId) {
  global $wpdb;

  $details = get_gc_details($item->id);
  $recipient = get_gc_recipient($details);
  $recip_name = trim("$recipient->first_name $recipient->last_name");
  $price = $item->price;

  $sender = $wpdb->get_row($wpdb->prepare(
    "SELECT CONCAT(firstName,' ',lastName) as name, email 
    FROM donationGiver WHERE ID=%d",$donorId));
  $sender->name = trim($sender->name);

  //Create new donationAccounts
  $params = array('recipient' => $recipient);
  if (!empty($details->message))
    $params['message'] = $details->message;


  if (PROCESS_ORDER_V1) {
    $codes = array();
    for ($i = 0; $i < $item->quantity; $i++) {
      $acct_id = insert_donation_acct($donorId, $price, 
        ACCT_TYPE_OPEN_CODE, $paymentId, $donorId, $donorId,
        "Purchased for $recip_name $recipient->email",
        $params);
      $code = get_acct_code_by_id($acct_id);
      dp("GC BUY: CREATED $$price #$code #$acct_id");
      $codes[] = $code;
    }
    $details->codes = $codes;
  }

  $code_first = $codes[0];
  $code_array = $codes;

  $price = as_money($price);
  
  if ($item->quantity > 1) {
     $amount = count($codes) . " $price Impact Cards";
     $codes = "Redemption Codes: " . implode(", ", $codes) . " ($price each)";
  } else {
     $amount = "a $price Impact Card";
     $codes = "Redemption Code: " . implode(", ", $codes);
  }
  $body = "$sender->name ($sender->email) purchased $amount.<br>$codes";

  $gcMsg = empty($details->message)?'':
    '<div style="margin: 5px 0 12px 32px;">"<strong>'
    . nl2br(as_html($details->message)).'</strong>"</div>';

  if (!empty($recipient->address)) {
    // HANDLE POSTAL DELIVERY

    $subject = "Delivery request: $amount";
    $body .= "<br><br>$recip_name<br>$recipient->address<br>$recipient->address2<br>$recipient->city, $recipient->state $recipient->zipcode";

    dp("GC BUY: POSTAL DELIVERY, MSG: $details->message");
  } else {
    // HANDLE EMAIL DELIVERY
    $subject = "Purchased: $amount (electronic delivery)";
    if (!empty($recip_name)) 
      $body .= "<br><br>for $recip_name ($recipient->email)";

    dp("GC BUY: EMAIL DELIVERY TO RECIPIENT $recipient->email MSG: $details->message");

    $icn = new Notification();
    $icn->recipient_name = $recip_name;
    $icn->recipient_email = $recipient->email;

    if(empty($sender->name))
      $sender->name = 'A donor';

    $icn_args = array($codes,$code_first,$code_array,$amount,$price,as_html($recip_name),as_html($recip_name),as_html($recipient->email),
      $gcMsg, as_html($sender->name), as_html($sender->email));

    $icn->build_impactcard_content($icn_args); 
    $gc_content = $icn->get_finished_content();
    $icn->send(null,false,true,$gc_content);
  }

  // Notify administrator
  queue_mail_simple(MAIL_ADMIN_GCBUY_PRIORITY,
    'Administrator', get_email_address("outreach"),
    $subject,
    xml_entities($body . $gcMsg),
    'syi.html');

  $item->details = $details;
  $item->sender = $sender;
  $item->recipient = $recipient;
  $item->itemCount = $item->quantity;
}

function processDiscount($discount, $paymentId, $donorId) {
  if ($discount->ref!='' && $discount->price!=0 && $discount->quantity!=0) {
    $daId = get_acct_id_by_code($discount->ref);
    if ($daId != NULL) { //account exists
      $balance = get_donation_acct_balance($daId);
      $datNote = date('Y-m-d H:i:s').' payment #'.$paymentId.' donor#'.$donorId;
      //Deduct from account
      $datId = insert_donation_acct_trans($daId, $discount->price, $paymentId,
        $datNote, true);
      dp("GC #".$discount->ref." APPLIED DEDUCTING: $".abs($discount->price));
      return $datId;
    } else {
      dp("GC #".$discount->ref." NOT FOUND, AMT PASSED: $".abs($discount->price));
    }
  }
  return 0;
}

function processGift($gift, $price) {
  global $wpdb, $emailEngine;
  if ($gift->id == CART_GIVE_ANY) return;

//
  dp(" GIFT $gift->id +" . as_money($price));
  $gift->current_amount += $price;
  
  if ($gift->current_amount < $gift->unitAmount) {
    $sql = $wpdb->prepare(
      "UPDATE gift SET current_amount=%d WHERE id = %d", 
	  $gift->current_amount, $gift->id);
    dp_sql($sql);
    dp(" #$gift->id NOW AT " . as_money($gift->current_amount) . " OF " . 
	  as_money($gift->unitAmount));
    return;
  }

  $gift->current_amount -= $gift->unitAmount;
  if ($gift->current_amount < 0) $gift->current_amount = 0;
  if ($gift->unitsWanted < 1) $gift->unitsWanted = 1; 
  $gift->unitsWanted -= 1;
  // TODO: ENFORCE INVENTORY CONTROL

  $sql = $wpdb->prepare(
    "UPDATE gift SET current_amount=%d, "
    ."unitsWanted=%d,unitsDonated=unitsDonated+1 "
    ."WHERE id = %d", $gift->current_amount, $gift->unitsWanted, $gift->id);
  dp_sql($sql);
  dp(" #$gift->id NOW " . ($gift->current_amount > 0 ? "AT " . 
    as_money($gift->current_amount) . ", " : "") . "NEEDS qty$gift->unitsWanted");

  if (intval($gift->unitsWanted) == 0) { // Only send 1 mail
    dp("BLOG#$gift->blog_id GIFT #$gift->id IS OUT OF STOCK - SENDING MAIL");
    $stock_email = get_email_address("updates");
	
    queue_mail_simple(MAIL_ADMIN_STOCK_PRIORITY,'Updates',$stock_email,
      "Gift out of stock: $gift->displayName at " . 
      get_blog_option($gift->blog_id, 'blogname'),
      get_blog_option(1, 'siteurl') . 
	  "/admin/gift/{$gift->id}", '', false);
  }

  // Update campaign counters
  if ($gift->campaign != null) incrementCampaign($gift->campaign);
  if ($gift->towards_gift_id == 0) return;

  $price = $gift->unitAmount;
  $gift = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM gift WHERE id=%d", $gift->towards_gift_id));
  dp(" AGGREGATES TOWARD GIFT $gift->id");

  processGift($gift, $price);
}

function holdCart($cart_id, $txn_id='', $txn_data='') {
  global $wpdb;
  $txn_id_arr = explode("-",$txn_id);
  if (is_array($txn_id_arr) && !empty($txn_id_arr)) $txn_id = $txn_id_arr[0];
  $sql = $wpdb->prepare(
    "UPDATE cart SET status='paying', txnID=%s, txnData=%s WHERE id=%d", 
    $txn_id, json_encode($txn_data), $cart_id);
  $wpdb->query($sql);
}


function get_cart_txn($txn_id) {
  global $wpdb;
  if (strpos($txn_id,"-")!==FALSE) {
    $txn_id_arr = explode("-",$txn_id);
    if (is_array($txn_id_arr) && !empty($txn_id_arr)) $txn_id = $txn_id_arr[0];
  }
  if (empty($txn_id)) return NULL;
  $sql = $wpdb->prepare("SELECT id, txnData FROM cart WHERE txnID=%s",$txn_id);
  return $wpdb->get_row($sql);
}

?>
