<?php

include_once("payments.php");

ini_set('display_errors',1);
error_reporting(E_ALL | E_STRICT ^ E_NOTICE);

$gift = ($_REQUEST['gift']);
if ($gift <= 0)
  die('need ?gift=##');

global $wpdb;
$price = $wpdb->get_var($wpdb->prepare(
  "SELECT unitAmount FROM gift WHERE ID=%d", $gift));

$tip = ($_REQUEST['tip']);
if (empty($tip)) $tip = 0;

$quantity = ($_REQUEST['quantity']);
if ($quantity < 1) $quantity = 1;

$payer = ($_REQUEST['payer']);
if (empty($payer)) $payer = 'testpayer@seeyourimpact.org';

$order = new Donation($variables);
$order->payment = new stdClass;
$order->payment->status = 'Paid';
$order->payment->gross = $tip;
$order->payment->tipped = 0;
$order->payment->id = $_REQUEST["txn_id"];
$order->payment->method = 'GG';
$order->payment->tip = $tip;
$order->payment->memo = null;

$order->donor = new stdClass;
$order->donor->impersonate=null;
$order->donor->referral=null;
$order->donor->contactme=null;
$order->donor->email = $_REQUEST["email"];
$order->donor->first = eor($_REQUEST['first'], 'Test');
$order->donor->last = eor($_REQUEST['last'], 'Payer');
$order->donor->validated = true;

$order->data = var_export($_REQUEST,true);

// BUILD THE CART
$item1 = new stdClass;
$item1->gift_id = $gift;
$item1->price = $price;
$item1->quantity = $quantity;
$order->payment->gross += $item1->quantity * $item1->price;

$order->cart = new stdClass;
$order->cart->items = array( $item1 );

if ($_REQUEST['email']) {
  $email = $_REQUEST['email'];
  $user_id = $wpdb->get_var($wpdb->prepare('select id from wp_users where user_email = %s', $email));
  $wpdb->insert('cart', array('userID' => $user_id), array('%d'));
  $order->cart->id = $wpdb->insert_id;
}
else {
  $order->cart->id = 11;
}

$gift2 = $_REQUEST['gift2'];
if ($gift2 > 0) {
  $item2 = new stdClass;
  $item2->gift_id = $gift2;
  $item2->price = $wpdb->get_var($wpdb->prepare(
    "SELECT unitAmount FROM gift WHERE ID=%d", $gift2));
  $item2->quantity = 1;
  $order->payment->gross += $item2->quantity * $item2->price;
  $order->payment->tipped += $item2->quantity * $item2->price;
  $order->cart->items[] = $item2;
}

processOrder($order);

?>
