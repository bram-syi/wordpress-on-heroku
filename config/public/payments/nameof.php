<?php

include_once("payments.php");

ini_set('display_errors',1);
error_reporting(E_NOTICE | E_WARNING);

$_REQUEST = stripslashes_deep($_REQUEST);

$acct_id = get_acct_id_by_code_or_id($_REQUEST['account']);
$amount = $_REQUEST['amount'];
$fr = $_REQUEST['fr'];
$tip = ($_REQUEST['tip']);
if (empty($tip)) $tip = 0;
$email = $_REQUEST['email'];
$first = $_REQUEST['first'];
$last = $_REQUEST['last'];

$who = $_REQUEST['who'];
$matches = array();
if (!empty($who)) {
  if (!preg_match('/(\w+)((\s*\w+)+)(?:\:)*\s\$(\d+(?:\.\d*))\s(.*)/', $who, $matches)) {
    echo "?who=first last \$amount email";
    die;
  } else {
    $first = $matches[1];
    $last = trim($matches[2]);
    $amount = $matches[4];
    $email = $matches[5];
  }
}

if (empty($amount) || empty($acct_id) || empty($email)) {
  echo "?amount=###&account=###&email=###&first=###&last=###";
  die;
}
$acct = (object)get_donation_account($acct_id);

// BUILD THE CART
$item1 = new stdClass;
$item1->gift_id = CART_GIVE_ANY;
$item1->giveany = 1;
$item1->price = $amount;
$item1->quantity = 1;

$donorID = get_donor_id($email);
if (empty($donorID)) {
  if (empty($first) || empty($last)) {
    echo "?amount=###&account=###&email=###&first=###&last=###";
    die;
  }
  $donorID = insert_donation_giver($email, $first, $last);
}

$order = build_order($acct, $donorID, array($item1), 0, $fr, FALSE, "Account $acct_id made donation on behalf of $first $last <$email>", TRUE);

$order->impersonate = FALSE; // This is a donation that needs a thank-you
$order->discounts = array();

processOrder($order);

?>
