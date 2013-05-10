<?php

include_once 'wp-load.php';
include_once 'a/api/gdata.php';
include_once 'a/api/gift.php';
include_once 'a/api/account.php';
include_once 'a/api/donor.php';
include_once 'payments/payments.php';

// What page of the workbook?
$PAGE = 4;

// This script can time out.
ini_set('max_execution_time', 5000);

// This will log in and request access to google documents -- redirecting
// through the Google login process
$token = GAuth::login();

// Fake sheet with test data.
$sheet = "https://docs.google.com/spreadsheet/ccc?key=0AuYLZYlqCrncdGhZQTRsajYxaWw5R0pSMjE3M043RkE";

if (is_live_site())
  $sheet = "https://docs.google.com/spreadsheet/ccc?key=0AjNh-faeY3JldG12UExuM1B5MDVFX1Q2SjdyNmxaTmc";

// The real Zaarly sheet:
// $sheet = "https://docs.google.com/a/seeyourimpact.org/spreadsheet/ccc?key=0AjNh-faeY3JldG12UExuM1B5MDVFX1Q2SjdyNmxaTmc";

$gifts = array(
  'Sheree James' => 476,
  'Jane Gershovich' => 476,
  'Autumn Hoverte' => 383,
  'Corey Galusha' => 383,
  'Jigna Patel' => 440,
  'Emily Weber' => 440,
  'Chris Kirchoff' => 297,
  'Adina DeSantis' => 225,
  'Rusty Federman' => 225,
  'Corey St. John' => 180,
  'Robyn Rosenberger' => 225,
  'Tonia Hume' => 664,
  'Coreen Cobley' => 180,
  'Jamie Moskowitz' => 180,
  'Chris Callan' => 180,
  'Anne Boyington' => 440,
  'Lisa Kin' => 440,
  'Tonya Framstad' => 297,
  'Ryan Anderson' => 297,
  'Chloe Fulton' => 383,
  'Laura Taylor' => 383,
  'Alessandro Stortini' => 664,
  'Lewis Zhou' => 664,
  'Geannie Meckler' => 664,
  'Rylie Haack' => 467,
  'Art Stone' => 467,
  'Rita Sunderland' => 467,
  'John Crose' => 467
);

// Load the spreadsheet via private URL
$my_name = GAuth::getName();
$rows = GSpreadsheet::fetchList($sheet, $token, $PAGE);

foreach ($rows as $row) {
  flush();

  $row = array_map('trim', $row);
  if (strcasecmp($row['importstatus'],'done') == 0)
    continue;

  // Prepare for errors
  $errors = array();

  // Which gift?
  $seller = $row['sellername'];
  $gift_id = $gifts[$seller];
  if (empty($gift_id))
    $errors[] = "Unrecognized seller '{$seller}'";
  else {
    $gift = GiftApi::getGiveAny($gift_id);
    if ($gift === NULL)
      $errors[] = "Internal error: GIFT";
  }
  $fr_id = 12297; // ZAARLY fundraiser

  // Buyer info
  if (empty($row['buyerlastname'])) {
    $name = trim($row['buyerfirstname']);
    $names = explode(' ',$name);
    if (count($names) == 2) {
      $row['buyerfirstname'] = $names[0];
      $row['buyerlastname'] = $names[1];
    }
  }
  
  if (empty($row['buyerfirstname']) || empty($row['buyerlastname']) || empty($row['buyeremail']))
    $errors[] = "Please provide complete buyer information.";

  $row['buyerfirstname'] = ucfirst($row['buyerfirstname']);
  $row['buyerlastname'] = ucfirst($row['buyerlastname']);

  $price = round(from_money($row['donation']), 2);
  if ($price <= 0)
    $errors[] = "Please specify the donation amount";

  if (count($errors) == 0) {

    try {

      $acct = AccountApi::getOne(array( 'id' => 50110 )); // zaarly fund
      if ($acct == NULL)
        throw new Exception("Zaarly account is unavailable");

      $image = valueOr($row['buyerphoto'], "http://www.zaarly.com/assets/hammer/session/blank-avatar.jpg");
      $image = str_replace('http://www.gophoto.it/view.php?i=', '', $image);

      $donor = DonorApi::create(array(
        'first' => valueOr($first = $row['buyerfirstname'], "FAIL"),
        'last' => valueOr($last = $row['buyerlastname'], ""),
        'email' => valueOr($email = $row['buyeremail'], "FAIL"),
        'user_image' => $image
      ));
      if ($donor == NULL)
        throw new Exception("Donor account is unavailable");

      // BUILD THE CART
      $item1 = (object)array(
        'gift_id' => $gift->gift_id,
        'price' => $price,
        'quantity' => 1
      );

      $order = build_order(get_donation_account($acct->id), $donor->id, array($item1), 0, $fr_id, FALSE, "Account {$acct->id} made donation on behalf of $first $last <$email>", TRUE);
      $order->impersonate = TRUE; // We do not need e-mails
      processOrder($order);

    } catch (Exception $e) {
      $errors[] = $e->get_message();
    }
  }

  if (count($errors) > 0) {
    $row['importstatus'] = 'Error';
    $row['importmessage'] = implode(' - ', $errors);
  } else {
    $row['importstatus'] = 'Done';
    $row['importmessage'] = "Imported by {$my_name} " . date("M d Y h:i T");
  }

  GSpreadsheet::saveRow($row, $token);
}

flush();
echo "Finished.";

function valueOr($val, $or) {
  if (empty($val) || ($val == 'N/A')) {
    if ($or == "FAIL")
      throw new Exception("Missing donor information");
    return $or;
  }
  return $val;
}






/*  ISSUES



Session timeout:

PHP Fatal error:  Uncaught exception 'Exception' with message 'Your session has expired - please log in again' in /home/digvijay/SeeYourImpact.org/a/api/gdata.php:134
Stack trace:
#0 /home/digvijay/SeeYourImpact.org/import-zaarly.php(62): GData::fetchList('https://docs.go...', Object(stdClass), 5)
#1 {main}
  thrown in /home/digvijay/SeeYourImpact.org/a/api/gdata.php on line 134







If someone edits the spreadsheet while the import is running, it can fail to write back the row:

PHP Fatal error:  Uncaught exception 'Exception' with message 'There was a problem: Mismatch: etags = [&quot;E1NALz0hYCt7ImA_DBEBFw1U&quot;], version = [3e7a5dk2321040]' in /home/digvijay/SeeYourImpact.org/a/api/gdata.php:212
Stack trace:
#0 /home/digvijay/SeeYourImpact.org/import-zaarly.php(151): GData::saveRow(Array, Object(stdClass))
#1 {main}
  thrown in /home/digvijay/SeeYourImpact.org/a/api/gdata.php on line 212






























*/
