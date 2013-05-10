<?php

/* Standalone test 
include_once('../wp-config-standalone.php');
include_once(ABSPATH . 'wp-includes/wp-db.php');
include_once(ABSPATH . 'wp-includes/plugin.php');

global $wpdb;
$wpdb = new wpdb('syidb','nischal1999','impactdb_dev1','mysql.seeyourimpact.com');
*/

include_once("../wp-load.php");
include_once("transaction.php");

ini_set('display_errors',1);
error_reporting(E_ALL | E_STRICT ^ E_NOTICE);

if (isset($_GET['reset']))
  Txn::resetTests();

$donor = 24;


// TEST 1: Buy a gift card

$pay = array();
$pay[] = new PayTxn(20, 'paypal', 'PP001');

$buy = new BuyCardTxn(50);

$donation_id = Txn::makeDonation($donor, array(
  'payments' => $pay,
  'purchases' => $buy
));
$cards = Txn::getCards($donation_id);

Txn::test();

// TEST 1a: Another payment comes in
$pay = array();
$pay[] = new PayTxn(20, 'google', 'GG001');

Txn::updateDonation($donation_id, array(
  'payments' => $pay
));

Txn::test();

// TEST 2: Spend that card plus extra money on some gifts

$pay = array();
$pay[] = new PayTxn(65, 'cc', 'CC001');
$pay[] = new SpendCardTxn(50, $cards[0]);

$buy = array();
$buy[] = new BuyTxn(35,5.25);
$buy[] = new BuyTxn(25,3.75, 100);
$buy[] = new BuyCardTxn(40,6);

$donation_id = Txn::makeDonation($donor, array(
  'payments' => $pay,
  'purchases' => $buy,
));
$cards = Txn::getCards($donation_id);

Txn::test();



// TEST 3: 50 donations of "random" amounts

for ($i = 1; $i <= 50; $i++) {
  $donor = 100 + $i;
  $amt = 20 + ($i * 20) % 135 + $i * .05;
  $tip = round($amt * 0.15,2);
  Txn::makeDonation($donor, array(
    'payments' => new PayTxn($amt + $tip, 'cc', 'CC002-' . $i),
    'purchases' => new BuyTxn($amt,$tip, 100)
  ));
}

Txn::test();




// TEST 4: 50 donations of "random" amounts to a specific fundraiser,
// all done as a give-any

for ($i = 1; $i <= 50; $i++) {
  $donor = 200 + $i;
  $amt = 20 + ($i * 20) % 135 + $i * .05;
  $tip = round($amt * 0.15, 2);
  Txn::makeDonation($donor, array(
    'payments' => new PayTxn($amt + $tip, 'cc', 'CC003-' . $i),
    'purchases' => new BuyTxn($amt,$tip, 0, 10945)
  ));
}

Txn::test();



// TEST 5: Allocate all of the gifts from the previous step

$allocs = Txn::getAllocsForFundraiser(10945);
foreach ($allocs as $alloc) {
  Txn::allocateGifts($alloc->acct_id, $alloc->card, 0, 100, $alloc->donor_id, $alloc->donation_id);
}

Txn::test();



// TEST 6: Allocate the give-any from step 2

$allocs = Txn::getAllocsForFundraiser(0);
foreach ($allocs as $alloc) {
  Txn::allocateGifts($alloc->acct_id, $alloc->card, 0, 100, $alloc->donor_id, $alloc->donation_id);
}

Txn::test();




// TEST 7: Spend the gift card from step 2
$acct = Txn::getAccount($cards[0]);
Txn::makeDonation(26, array(
  'payments' => new SpendCardTxn($acct->balance, $acct->id),
  'purchases' => new BuyTxn($acct->balance - 10,10, 100, 10945)
));

Txn::test();



// Finish testing


