<?php

define('XMLRPC_REQUEST', FALSE); // this turns off WP-Minify HTML minifcation
include_once('../wp-load.php');

ensure_logged_in_admin();

$email = $_REQUEST['email'];
if (empty($email))
  die('?email={a valid email address}');

$count = intval($_REQUEST['count']);
if ($count <= 0 || $count > 5000)
  die('?count={number of codes}');

$for = intval($_REQUEST['eid']);
if ($eid < 0)
  die('?eid={fundraiser ID}');

$amount = from_money($_REQUEST['amount']);
if ($amount <= 0)
  die('?amount={initial balance}');

$name = trim($_REQUEST['name']);
if (empty($name))
  die('?name={donor name}');

$names = explode(' ',$name, 2);
if (count($names) < 2)
  die('?name must have a first and last');

$donor_id = insert_donation_giver($email, $names[0], $names[1]);
if (empty($donor_id))
  die("Unable to create donor $email ($first_name $last_name)");
$note = 'Bulk created';

for ($i = 0; $i < $count; $i++ ) {
  $id = insert_donation_acct($donor_id, $amount, ACCT_TYPE_OPEN_CODE,
    0, $donor_id, 0, $note, '', 1, $eid);
}

global $wpdb;
$codes = $wpdb->get_results($wpdb->prepare(
  "SELECT da.* FROM donationAcct da WHERE da.owner=%d
  ORDER BY da.dateCreated DESC LIMIT %d",
  $donor_id, $count));

for ($i = 0; $i < count($codes); $i++) {
  $code = $codes[$i];
  echo "$i,$code->code," . as_money($code->balance) . "<br>";
}
