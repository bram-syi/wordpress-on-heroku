<?php
require_once( dirname(__FILE__) . '/../wp-load.php' );
global $wpdb;

ensure_logged_in_admin();

$d = as_ints($_GET['donation']);
if (count($d) >= 1)
  $cond[] = "donationID in (" . implode(',', $d) . ")";

$g = as_ints($_GET['gift']);
if (count($g) >= 1)
  $cond[] = "ID in (" . implode(',', $g) . ")";

if (count($cond) == 0) {
  echo "Select by ?donation=ID,ID,ID or ?gift=ID,ID,ID";
  exit;
}

$cond = implode(" OR ", $cond);
$sql = "select * from donationGifts where $cond";
d_sql($sql);
foreach ($wpdb->get_results($sql) as $gift)
  undo_gift($gift);

// Undo the transaction
function undo_gift($g) {
  global $wpdb;

  $d = $wpdb->get_row($sql = $wpdb->prepare(
    "select p.provider, dat.* 
     from payment p 
     left join donation d on d.paymentID=p.ID
     left join donationAcctTrans dat on (dat.paymentID=p.ID and dat.amount < 0)
     where d.donationID = %d",
    $g->donationID));
  d_sql($sql);

  if ($d->provider != 5) {
    echo "Gift $g->ID was not a GC allocation; this script can't issue refund payments yet.  <b>DID NOT UNDO</b>.<br>";
    return;
  }
  if ($d->matchingDonationAcctTrans > 0) {
    echo "Gift $g->ID was matched; this script doesn't undo matches yet.  <b>DID NOT UNDO</b>.<br>";
    return;
  }

/*
  pre_dump($d);
  pre_dump($g);
*/

  // Insert a refund transaction
  insert_donation_acct_trans($d->donationAcctId, $g->amount + $g->tip, $d->paymentID, "Refunded gift #$g->ID");

  // Rebalance the donation
  $wpdb->query($wpdb->prepare(
    "update donation set 
      donationAmount_Total = donationAmount_Total - %d,
      tip = tip - %d
     where donationID=%d",
     $g->amount, $g->tip, $g->donationID));

  // Delete the gift
  $wpdb->query($wpdb->prepare(
    "delete from donationGifts where id=%d",
    $g->ID));

  echo "Refunded gift #$g->ID<br>";
}

function d_sql($sql) {
  return;
  echo "<pre>$sql\n</pre>";
}
