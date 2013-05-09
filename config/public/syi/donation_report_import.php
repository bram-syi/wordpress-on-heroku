<?php

ini_set('max_execution_time', 600);
include_once('../wp-load.php');
include_once("transaction.php");

print '<pre><table>';

$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
if (FALSE === $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)) {
  throw new Exception("PDO must support ERRMODE_EXCEPTION");
}

if (isset($_GET['reset'])) {
  Txn::resetTests();
  $dbh->query('update donation_report set status = "new"');
}

$status = "1";
if (!array_key_exists('remaining', $_GET) && !array_key_exists('donor_id', $_GET)) {
  switch ($_GET['step']) {
    case 1: $status = "type2 != 'XFER?' AND date < '2011-02-01'"; break;
    case 2: $status = "type2 != 'XFER?' AND date >= '2011-02-01' AND date < '2011-08-01'"; break;
    case 3: $status = "type2 != 'XFER?' AND date >= '2011-08-01' AND date < '2011-10-01'"; break;
    case 4: $status = "type2 != 'XFER?' AND date >= '2011-10-01' AND date < '2011-12-01'"; break;
    case 5: $status = "type2 != 'XFER?' AND date >= '2011-12-01' AND date < '2012-04-01'"; break;
    case 6: $status = "type2 != 'XFER?' AND date >= '2012-04-01' AND date < '2012-06-01'"; break;
    case 7: $status = "type2 != 'XFER?' AND date >= '2012-06-01' AND date < '2012-08-01'"; break;
    case 8: $status = "type2 != 'XFER?' AND date >= '2012-08-01' AND date < '2012-12-01'"; break;
    case 9: $status = "type2 != 'XFER?' AND date >= '2012-12-01'"; break;
    case 10: $status = "type2 = 'XFER?'"; break;

    default:
      print "<p>Due to PHP memory limits, you have to import donors in batches when importing full history.</p>";
      print "<a href=\"?reset&step=1\" target='_new'>step 1</a><br/>";
      for($i = 2; $i <= 7; $i++) {
        print "<a href=\"?step=$i\" target='_new'>step $i</a><br/>";
      }
      print "<p>Or import remaining rows that are still new/unhandled</p>";
      print "<a href=\"?remaining\" target=\"_blank\">remaining</a><br/>";
      exit;
  }
}

$status = "(status = 'new' or status = 'unhandled') AND $status";
if (isset($_REQUEST['donor_id'])) {
  $status .= " AND donor_id=?";
  $sth = $dbh->prepare('update donation_report set `status` = "unhandled" where donor_id = ?');
  $sth->execute(array($_REQUEST['donor_id']));
}

$count = array(
  'handled' => 0,
  'unhandled' => 0,
);

// Have to do imports in ascending order so that accounts are created before they are referenced
$old_rows = $dbh->prepare(
  "select * from donation_report
   where $status
   order by date asc,donation_id asc");
$old_rows->execute(array($_REQUEST['donor_id']));

global $update_status;
$update_status = $dbh->prepare('update donation_report set status = ? where id = ?');

// since $old_rows are ordered by date, we'll collect all the rows
// for everything in order, and then assemble them into the new API calls
while ($row = $old_rows->fetch(PDO::FETCH_OBJ)) {

  $d1 = $args ? strtotime($args->date) : 0;
  $d2 = strtotime($row->date);
  $interval = round(abs($d2 - $d1),0);

  // Collect everything with the same donation_id with 10 seconds grace period
  // (some donations / transfers span multiple seconds)
  if ($args && (($args->donation_id != $row->donation_id) || ($interval > 10))) {
    $id = save_donation($args);
    $args = null;
  }

  if (!$args) {
    // we will eventually call one of:
    //    makeDonation($args->donor_id, $arg->opts)
    // or
    //    allocateGifts(...)
    $args = (object)array(
      'donation_id' => $row->donation_id,
      'donor_id' => $row->donor_id,
      'date' => $row->date,
      'opts' => array(
        'date' => $row->date,
        'donor_id' => $row->donor_id,
        'donation_id' => $row->donation_id,
        'payments' => array(),
        'purchases' => array()
      ),
      'gifts' => array(),
      'mgifts' => array(),
      'xfers' => array(),
      'discounts' => array(),
    );

    // this is used to collect multiple gift purchases of the same gift in donation_report into
    // single entities in the new a_* tables
    $args->touched = false;
  }
  try {
  $handled = true;
  $type = $row->type2;
  if (preg_match('/^(GOOGLE|PAYPAL|CC|RECURLY|AMAZON|unknown|CASH\/CHECK)(?: REFUND)?$/', $type, $m)) {
    $type = $m[1];

    $txn_id = preg_replace('/^.*?#/', '', $row->reference);
    $notes = preg_replace('/PMT# \d+/', '', $row->notes);
    $notes = trim($notes);

    if ($type == 'unknown')
      $type = 'PAYPAL';

    $pay = new PayTxn(
      -$row->amount,
      str_replace(' REFUND','',$type),
      $txn_id,
      $notes,
      '' //json_encode($row)
    );
    $pay->copy_id = $row->payment_id;

    // Steve: in 2009, our PAYPAL records didn't count tip properly
    if ($row->type2 == 'PAYPAL' && $row->donation_id > 0 && $row->donation_id < 70) {
      // TODO: check whether we actually got the money or not
    }

    // Steve: this is a bizarre case that happens about 40 times in 2010, where the type
    // seems to be a payment but actually it's an account being created
    // Alex: there are exactly 21 of these in donation_report
    if ($row->card > 0 && $row->amount == 0) {
      $sth = $dbh->prepare('select txnID from payment where id = ?');
      $sth->execute(array($row->payment_id));
      $p = $sth->fetch(PDO::FETCH_OBJ);
      $pay->txn_id = $p->txnID;

      $acct_id = account_from_reference($row->reference);
      if (!$acct_id) {
        Txn::log("DepositTxn: acct_id is going to be null");
      }

      $buy = new DepositTxn(
        $acct_id,
        abs($row->card),
        0, // donation_report has 0 as tip for all 21 rows
        'fund',
        0 // donation_report has 0 in this column for all 21 rows
      );
      $buy->copy_row = $row;
      $args->opts['purchases'][] = $buy;
      $args->touched = true;
    }

    $args->opts['payments'][] = $pay;
    $args->touched = true;
  }
  else if (preg_match('/^BALANCE IN|BALANCE OUT$/', $type)) {
  }
  else if ($type == 'XFER?') {
    $errors_query = $dbh->prepare(
      "select sum(tip) as amount from a_transaction
         where type='error' and donor_id=?");
    $errors_query->execute(array($row->donor_id));
    $error_row = $errors_query->fetch(PDO::FETCH_OBJ);
    $handled = ($error_row != NULL) && ($error_row->amount == $row->keep);
    $row->notes = "XFER? $error_row->amount";
  }
  else if (preg_match('/^XFER|XFER REFUND$/', $type) || $type == '') {
    if (preg_match('/^##(\d+)/', $row->reference, $m)) {
      $hack = array(
        '32302' => 'BXTSEDXPNQ',
        '32321' => 'CNDBWYTUKN',
        '32415' => 'WLAMTX4BFL',
        '32416' => 'MXDWARAEBD',
        '34517' => 'KQ8V3499KV',
      );

      if (!$hack[$m[1]]) {
        throw new Exception("xfer with unexpected string in reference column");
      }
      $row->reference = $hack[$m[1]] . " (hardcoded)";

      if ($row->amount == 0 && $row->total != 0) {
        $row->amount = abs($row->total);
      }
    }

    $args->xfers[] = $row;

    // these rows are handled in handle_xfers, skip other operations in this huge while()
    continue;
  }
  else if (preg_match('/^(FOR DISCOUNT|BUY GC|FOR MATCHING|DEPOSIT|FUND|TO ALLOCATE)$/', $type, $m)) {
    $type = $m[1];

    if (!$row->acct_id) {
      $row->acct_id = account_from_reference($row->reference);
    }
    if (!$row->acct_id) {
      Txn::log("DepositTxn: acct_id is going to be null");
    }
    $buy = new DepositTxn(
      $row->acct_id,
      $row->card,
      $row->keep,
      $type,
      $row->campaign
    );
    $buy->copy_row = $row;
    $args->opts['purchases'][] = $buy;
    $args->touched = true;
  }
  else if (preg_match('/^DISCOUNT$/', $type)) {
    $args->opts['payments'][] = new SpendCardTxn(
      abs($row->amount),
      account_from_reference($row->reference),
      $row->reference,
      $row->notes
    );
    $args->touched = true;
  }
  else if (preg_match('/^SPEND GC|ALLOCATED|MATCH|INTERNAL|FROM FUND$/', $type)) {
    if (preg_match('/^(\S+)/', $row->reference, $m)) {
      $acct_id = $m[1];
    } else {
      throw new Exception('unable to parse acct id from row');
    }

    if ($row->amount > 0) {
      throw new Exception('row expected to have negative amount');
    }

    if (!$acct_id) {
      Txn::log("SpendCardTxn: acct_id is going to be null");
    }

    $pay = new SpendCardTxn(
      -$row->amount + $row->keep,
      $acct_id,
      NULL,
      $row->notes
    );
    $pay->copy_row = $row;
    $args->opts['payments'][] = $pay;
    $args->touched = true;
  }
  else if (preg_match('/^GIFT$/', $type)) {
    if (preg_match('/^GIFT\s*#\s*(\d+)/', $row->notes, $m)) {
      $donation_gift_id = $m[1];
    } else {
      throw new Exception('unabled to parse donation gift id from row');
    }

    global $dbh;
    $stmt = $dbh->prepare('select giftID from donationGifts where ID = ?');
    $stmt->execute(array($donation_gift_id));
    $x = $stmt->fetch(PDO::FETCH_OBJ);
    if ($x != NULL)
      $gift_id = $x->giftID;
    if (!$gift_id && preg_match('/\[(\d+)\]/', $row->reference, $m)) {
      $gift_id = $m[1];
    }
    if (!$gift_id && preg_match('/GIFT\s*#\s*(\d+)/', $row->notes, $m)) {
      $gift_id = $m[1];
    }
    if (!$gift_id) {
      throw new Exception('unable to parse gift id from row');
    }

    $fr_id = $row->campaign;
 
    $key = "$fr_id/$gift_id";
    if (!array_key_exists($key, $args->gifts)) {
      $args->gifts[$key] = array(
        'amount' => 0,
        'tip' => 0,
        'dgs' => array(),
        'price' => 0
      );
    }
    $args->gifts[$key]['amount'] += $row->out;
    $args->gifts[$key]['tip'] += $row->keep;
    $args->gifts[$key]['dgs'][] = $donation_gift_id;
    $args->touched = true;
  }
  else if (preg_match('/^M.GIFT$/', $type)) {
    if (preg_match('/^GIFT\s*#\s*(\d+)/', $row->notes, $m)) {
      $donation_gift_id = $m[1];
    }
    else {
      throw new Exception('unabled to parse donation gift id from row');
    }

    if (preg_match('/\[(\d+)\]/', $row->reference, $m)) {
      $gift_id = $m[1];
    }
    else {
      // some rows are just missing this info, so go look it up in sql
      global $dbh;
      $stmt = $dbh->prepare('select giftID from donationGifts where ID = ?');
      $stmt->execute(array($donation_gift_id));
      $x = $stmt->fetch(PDO::FETCH_OBJ);
      $gift_id = $x->giftID;
      if (!$gift_id) {
        throw new Exception('unable to parse gift id from row');
      }
    }
    $fr_id = $row->campaign;

    $key = "$fr_id/$gift_id";
    if (!array_key_exists($key, $args->mgifts)) {
      $args->mgifts[$key] = array(
        'amount' => 0,
        'tip' => 0,
        'dgs' => array(),
        'price' => 0
      );
    }
    $args->mgifts[$key]['amount'] += $row->out;
    $args->mgifts[$key]['tip'] += $row->keep;
    $args->mgifts[$key]['dgs'][] = $donation_gift_id;
    $args->touched = true;
  }
  else if ($row->date == '2010-01-19 14:13:00' && $row->notes == 'Fixed rob short missing gift') {
    $args->xfers[] = $row;
    continue;
  }
  else if ($row->date == '2010-09-23 15:11:00' && $row->reference == '9QXKXYT9XV (Digvijay Chauhan)') {
    // covers 2 rows
    $args->xfers[] = $row;
    continue;
  }
  else if ($row->date == '2011-06-29 15:49:00' && $row->notes == 'THIS IS A TEST') {
    $args->xfers[] = $row;
    continue;
  }
  else if (preg_match('/^ERROR$/', $type)) {
    // do nothing, we just consider these handled
  }
  else {
    $handled = false;
  }

  } catch (Exception $e) {
    // Any exceptions -- just display and move on
    print "<tr><td colspan='10'>" . $e->getMessage() . "</td></tr>";
    Txn::log("$e\non row: ".json_pretty($row));
    $handled = FALSE;
  }

  print_row_as_html($row, $handled);
}

save_donation($args);





print "handled rows: ".$count['handled']."<br/>UNhandled rows: ".$count['unhandled'];
print "</table></pre>";

function print_row_as_html($row, $handled) {
  global $count;
  global $update_status;

  print "\n".'<tr class="fromfunc" style="background-color: '.($handled ? '#87E6B0' : '#E04848').'">';
  foreach ($row as $k => $v) {
    if (preg_match('/^(donor_email|donor_id|type)$/', $k)) {
      continue;
    }

    print '<td>'.htmlspecialchars($v).'</td>';
  }
  print "</tr>\n";

  $status = $handled ? 'handled' : 'unhandled';
  $count[$status]++;
  $update_status->execute(array($status, $row->id));
}

function save_donation($args) {
  global $dbh;

  if ($args->touched) {
    // compact $args->gifts into the format that Txn expects
    foreach ($args->gifts as $key => $g) {
      list ($g['fr_id'], $g['gift_id']) = explode('/', $key);
      $args->opts['purchases'][] = make_purchase($args, $g, 'ALLOCATED');
    }
    foreach ($args->mgifts as $key => $g) {
      list ($g['fr_id'], $g['gift_id']) = explode('/', $key);
      $args->opts['purchases'][] = make_purchase($args, $g, 'MATCH');
    }
    $id = Txn::makeDonation($args->donor_id, $args->opts);
    print "<tr><td>save $id</td></tr>";
  }

  if ($args->xfers) {
    $known_xfer_failures = array(
      "2010-09-28 14:38:54",
      "2010-10-01 15:50:12",
      "2010-10-15 09:50:17",
      "2010-10-15 09:50:39",
      "2010-12-10 17:14:30",
    );

    if (count($xfers) == 1
      && in_array($xfers[0]->date, $known_xfer_failures)
      && preg_match('/\(hardcoded\)/', $xfers[0]->reference)
      && $xfers[0]->amount == 0) {

      // TODO: what do with these 5 rows?
      // SELECT * FROM donation_report WHERE `date` IN ("2010-09-28 14:38:54",
      //   "2010-10-01 15:50:12","2010-10-15 09:50:17","2010-10-15 09:50:39",
      ///  "2010-12-10 17:14:30");
    }

    try {
      $xfers = array();
      foreach ($args->xfers as $xfer) {
        if (!$xfer->account) {
          $xfer->account = account_from_reference($xfer->reference);
        }
        if (!$xfer->account) {
          Txn::log("DepositTxn: account is going to be null");
        }

        print_row_as_html($xfer, true);
        $deposit = new DepositTxn($xfer->account, $xfer->card);
        $deposit->copy_row = $xfer;
        $xfers[] = $deposit;
      }

      Txn::transfer($xfers);
      print "<tr><td>transfer</td></tr>";
    }
    catch (Exception $e) {
      Txn::log("handle XFERs exception: $e");
      print "<tr style='background: #E04848;'><td>failed transfer: $e</td></tr>";
      print "<tr><td>&nbsp;</td></tr>";
    }
  }

  if (Txn::$isError)
    print "<tr style='background: #E04848;'><td>error</td></tr>";
}

function make_purchase(&$args, $g, $kind = 'ALLOCATED') {
  $x = NULL;
  $match = NULL;

  foreach ($args->opts['payments'] as $i=>$payment) {
    if (!isset($payment->copy_row))
      continue;

    if ($payment->copy_row->type2 == $kind ||
        ($payment->copy_row->type2 == "FROM FUND" && $kind == 'MATCH')) {
      // TODO: assert that the gift fr_id = the payment account's event_id?

      if ($payment->amount <= 0)
        continue;

      // First line that fits?
      if ($match === NULL) {
        // Convert the purchase into an allocation
        if (!$payment->acct) {
          Txn::log('$payment->acct is going to be null');
        }
        $x = new AllocateTxn($payment->acct, $g['amount'], $g['tip'], $g['gift_id'], $g['fr_id']);
        $x->copy_row = $payment->copy_row;
        $x->donationGifts = $g['dgs'];

        $match = $i;
        $balance = $g['amount'] + $g['tip'];
      }

      $payment->amount = round($payment->amount - $balance, 2);
      // Empty payments won't be recorded

      if ($payment->amount >= 0)
        return $x;

      // otherwise, this row is not enough to pay for the whole gift
      // continue on looking for more rows to get the balance from.

      $balance = -($payment->amount);
      $payment->amount = 0;
    }
  }

  if ($balance != 0) {
    // overdrawn
    $args->opts['payments'][$match]->amount = -$balance;
  }

  if ($x === NULL) {
    $x = new BuyTxn($g['amount'], $g['tip'], $g['gift_id'], $g['fr_id']);
    $x->donationGifts = $g['dgs'];
  }

  return $x;
}

// pull an account code out of a string (string is assumed to be the "reference" column
// from the donation report)
function account_from_reference($str) {
  if (preg_match('/^(\w+)/', $str, $m)) {
    return $m[1];
  }
  else {
    throw new Exception("unable to parse 'reference' column: ".$str);
  }
}

// similar to account_from_reference above, but for the "notes" field instead
// NOTE: returns an array, not a string
function account_from_notes($str) {
  if (preg_match('/transfer (to|from) #\s*(\d+)/', $str, $m)) {
    return array(
      'direction' => $m[1],
      'account_id' => $m[2],
    );
  }
  else {
    // this is ok, the "notes" field doesn't always have the "transfer (to|from)" string
    return null;
  }
}

function schema() {
  $dbh->query(<<<SQL
CREATE TABLE `donation_report` (
  `donor_name` varchar(100) NOT NULL default '',
  `donor_email` varchar(100) NOT NULL default '',
  `donor_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `donation_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `total` float NOT NULL,
  `amount` float NOT NULL,
  `out` float NOT NULL,
  `keep` float NOT NULL,
  `card` float NOT NULL,
  `type2` varchar(100) NOT NULL default '',
  `reference` varchar(100) NOT NULL default '',
  `charity` varchar(100) NOT NULL default '',
  `campaign` int(11) NOT NULL,
  `notes` varchar(500) NOT NULL default '',
  `dat_id` int(11) default NULL,
  `acct_id` int(11) default NULL,
  `status` varchar(20) NOT NULL default 'new',
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=344733 DEFAULT CHARSET=latin1;
SQL
  );
}
