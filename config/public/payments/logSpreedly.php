<?
/**********************************************************************/
// Author:      Yosia Urip
// Description: Acts as a web service to handle request from Spreedly
//              when a charge for recurring payment was made.
//              Will redirect to logTransaction with formatted data
// Last Edited: Sept 2010
/**********************************************************************/

include_once('logTransaction.php');
include_once('../wp-includes/user.php');
function verifyTransaction(){
  global $wpdb;
  if(isset($_REQUEST['subscriber_ids'])){
    $subscriber_ids = explode(",",$_REQUEST['subscriber_ids']);

    foreach($subscriber_ids as $subscriber_id){
      Spreedly::configure(SPREEDLY_NAME, SPREEDLY_TOKEN);
      $sub = SpreedlySubscriber::find($subscriber_id);
      if($sub!=NULL){
        //echo '<pre>';
        //print_r($sub);
        //echo '</pre>';
        //debug($sub,true);

        $wp_user = new WP_User($subscriber_id);

        //$sub_no_invoice = $sub;
        //$sub_no_invoice->invoices = null;
        //print_r($wp_user);
        foreach($sub->invoices as $invoice){

          $exists = $wpdb->query(
            $wpdb->prepare(
            "SELECT id FROM payment WHERE txnID = %s",$invoice->token));

          //debug($invoice,true);

          if($exists == NULL){

          $donorID = get_donorid_by_userid($subscriber_id);

          //debug('USER #'.$subscriber_id." DONOR #".$donorID,true);
          //TO DO: calculate tip

          //
          $custom = '0||1||1||0||0||0||||';
          $full_name = explode(" ",$wp_user->display_name);

          $cart = array();
          $cart['custom'] = $custom;
          $encrypted_cart_id = store_cart($cart);

          //debug(print_r($invoice,true)."\n".'---'.$encrypted_cart_id.'---');
          logTransactions2(
          $invoice->closed?'Completed':'Pending',
          $wp_user->user_email,
          $encrypted_cart_id,
          $invoice->amount,
          '',
          $full_name[0],
          (count($full_name)>1?$full_name[1]:''),
          1,
          print_r($sub,true),
          '',
          'SP',
          false,
          $invoice->token,
          $donorID
          );
          }
        }
      }
    }
  }
}

verifyTransaction();

?>