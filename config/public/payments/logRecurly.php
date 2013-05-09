<?
/**********************************************************************/
// Author:      Steve
// Description: Acts as a web service to handle request from Recurly
//              when a charge for recurring payment was made.
//              Will redirect to logTransaction with formatted data
// Last Edited: Sept 2010
/**********************************************************************/

include_once('logTransaction.php');
include_once('../wp-includes/user.php');

if(isset($_GET['code'])) {
  $user = get_userdata(intval(get_user_from_monthly_account($_GET['code'])));  	
  if(!empty($user->user_login)) {
    wp_redirect(site_url().'/members/'.$user->user_login.'/profile/payments/');
	exit();
  }
}

function is_mobile_donation($a) {
return false;
  $e = explode('-', $a);
  return count($e) == 5;
}

function recurly_to_donation_account($acct) {
  try {	  
    dr("ACCOUNT: ".print_r($acct,true));
    $code = $acct['account_code'];
    $user_id = get_user_from_monthly_account($code);

    // Did not find the user?
    if (empty($user_id)) {
      $user = get_user_by_email($acct['email']);
      if ($user) {
        $username = $user->user_login;
        $user_id = $user->ID;
      } else {
        dr("CREATING NEW USER");
        list($username, $user_id) = createWpAccount($acct['email'], $acct['first_name'], $acct['last_name']);
      }
      dr("USER #$user_id: $username");
      set_user_monthly_account($user_id, $code);

      // Update recurly if necessary (unfortunately user may later
      // change his username... that's not handled yet)
      if (!is_string($acct['username'])) {
        $acct = RecurlyAccount::getAccount($code);
        $acct->username = $username;
        $acct->update();
      }
    } else {
      dr("USER #$user_id: $username");
    }

    $recurring_tip = get_user_meta($user_id,'recurring_tip',true);
    dr("RECURRING TIP: ".$recurring_tip);
	
    $da = get_donation_acct_by_userid($user_id, ACCT_TYPE_GIVE_ANY, $recurring_tip, 0, 1, TRUE);
    if ($da == null) {
      dr("NO ACCOUNT");
    } else { 
      dr("ACCOUNT #$da->id");
      return $da;
    }
  } catch (Exception $e) {
    dr_end(print_r($e,true)," ERROR"); 
  }
}

function verifyTransaction(){
  global $wpdb;
 
  init_monthly();
  $post_xml = file_get_contents("php://input");
  $notification = new RecurlyPushNotification($post_xml);
  $acct = amstore_xmlobj2array($notification->account);

  dr("NOTIFICATION DUMP:\n".print_r($notification,true));
  $trans = $notification->transaction->reference;
  $recurly_trans_id = $notification->transaction->id;
  $amount = $notification->transaction->amount_in_cents / 100.0;

  switch ($notification->type) {
    case 'successful_payment_notification':
      $da_note = "auto-donation via Recurly #".$trans." ".$recurly_trans_id;
      break;
    case 'successful_refund_notification':
      $amount = -1 * $amount;
      $da_note = "refund via Recurly #".$trans." ".$recurly_trans_id;
      break;
    case 'new_subscription_notification':
    case 'renewed_subscription_notification':
    case 'billing_info_updated_notification':
    case 'canceled_account_notification':
    case 'expired_subscription_notification':
    case 'updated_subscription_notification':
	  exit();
	  break;	
	default:
	  dr_end('UNHANDLED RECURLY REQUEST',' ERROR');
	  exit();
	  break;
    // TODO: what else do we need to handle?
  }

  $da = recurly_to_donation_account($acct);
  $pid = create_donation_payment($da->donorId, $amount, 9/*Recurly*/, $trans);
  $dat_id = insert_donation_acct_trans($da->id, $amount, $pid, $da_note);	  
  dr_end();
  exit();
}

verifyTransaction();

?>
