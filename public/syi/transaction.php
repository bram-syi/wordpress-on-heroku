<?php

class PayTxn extends Txn {
  public static $table_name = 'a_payment';

  public function __construct($amount, $provider, $txn_id = NULL, $notes = NULL, $raw = NULL) {
    Txn::logThisFunction();

    $this->amount = $amount;
    $this->txn_id = $txn_id;
    $this->notes = $notes;
    $this->raw = $raw;

    if (is_numeric($provider)) {
      $this->provider = $provider;
    }
    else {
      $provider = strtolower($provider);
      if (array_key_exists($provider, self::$providers)) {
        $this->provider = self::$providers[$provider];
      }
      else {
        throw new Exception("invalid provider for PayTxn: $provider");
      }
    }
  }

  public static $providers = array(
    'unknown' => 0,
    'paypal' => 1,
    'cc' => 2,
    'credit' => 2,
    'google' => 3,
    'amazon' => 4,
    'gc' => 5,
    'giftcard' => 5,
    'account' => 5,
    'spend gc' => 5,
    'matching' => 6, // unused?
    // 'paypal' => 7, // ??
    'sp' => 8, // ??
    'recurly' => 9,
    'xfer' => 10,
    'cash' => 11,
    'cash/check' => 11,
    'check' => 11
  );

  public function write($donation_id) {
    Txn::logThisFunction();

    global $wpdb;

    // Map variety of payment provider strings to their corresponding number
    if (is_string($this->provider))
      $this->provider = self::$providers[$this->provider];

    if (empty($this->provider))
      throw new Exception("unknown payment provider");

    // TODO: if payment is via gift card, make sure gift card has available balance

    $overrides = array();

    // Import an old payments row
    if ($this->copy_id > 0) {
      $row = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM payment WHERE id=%d",
        $this->copy_id));
      if ($row != NULL) {
        $overrides['id'] = $this->copy_id;
        $overrides['notes'] = $row->notes;
        $overrides['data'] = $row->data;
        $overrides['raw'] = $row->raw;
      }
    }

    // Record this as a payment (possibly specifying exact ID that should be assigned)
    $r = $wpdb->insert(self::$table_name, self::override(array(
      'donationID' => $donation_id,
      'amount' => $this->amount,
      'provider' => $this->provider,
      'dateTime' => self::$date,
      'txnID' => $this->txn_id,
      'notes' => $this->notes,
      'raw' => $this->raw
    ), $overrides));
    Txn::throw_on_error();

    // Debit the account by the payment amount
    return $this->amount;
  }
}

class BuyTxn extends Txn {
  // during donation_report_import, we also set the donationGifts property:
  // donationGifts => array(
  //   donation_gift_id,
  //   gift_id,
  //   amount,
  //   match
  // )
  public function __construct($amount, $tip=0, $gift_id = 0, $fr_id = NULL, $blog_id = NULL) {
    Txn::logThisFunction();

    $this->amount = $amount;
    $this->tip = $tip;
    $this->gift_id = $gift_id;
    $this->fr_id = $fr_id;
    $this->blog_id = $blog_id;
  }

  public function write($donation_id) {
    self::logThisFunction();

    $is_giveany = ($this->gift_id == 0 || $this->gift_id == CART_GIVE_ANY);
    $type = empty($this->type) ? 'gift' : $this->type;

    if ($is_giveany) {
      if ($this->gift_id == CART_GIVE_ANY)
        $this->gift_id = 0;

      $this->type = 7; // TO-ALLOCATE;
      $this->acct_id = self::_createAccount($this);
      self::adjustAccount($this->acct_id, $this->amount, "Initial balance");

      $txn_id = self::createTransaction($type, 0,$this->tip, $this->gift_id, $this->fr_id, $this->acct_id, $this->amount);
    } else {
      $txn_id = self::createTransaction($type, $this->amount,$this->tip, $this->gift_id, $this->fr_id);
      self::_createGifts($txn_id, $this->donationGifts);
    }

    return $this->amount + $this->tip + $this->card;
  }
}

// A special kind of transaction that merges payment AND purchase
class AllocateTxn extends Txn {
  public function __construct($acct, $amount, $tip=0, $gift_id=0, $fr_id=0) {
    Txn::logThisFunction();

    if ($acct === null) {
      throw new Exception('AllocateTxn: $acct is null');
    }

    $this->acct = $acct;
    $this->amount = $amount;
    $this->tip = $tip;
    $this->gift_id = $gift_id;
    $this->fr_id = $fr_id;
  }

  public function write($donation_id) {
    self::logThisFunction();

    // Create a new 'ALLOCATED' row in the table
    $this->acct_id = Txn::getAccountId($this->acct);
    if (empty($this->acct_id))
      $this->acct_id = parent::_createAccount($this);

    // TODO: confirm that this->fr_id = acct->event_id

    $txn_id = self::createTransaction('allocate', $this->amount, $this->tip, $this->gift_id, $this->fr_id, $this->acct_id, -$this->amount-$this->tip);
    $gifts = self::_createGifts($txn_id, $this->donationGifts);

    // Register the payment (deduction from account)
    self::adjustAccount($this->acct_id, -$this->amount-$this->tip, "Allocated gifts " . implode(',', $gifts), TRUE);

    return 0; // This is a net-neutral transaction because it's both payment and purchase
  }
}
 


class SpendCardTxn extends PayTxn {

  public function __construct($amount, $acct, $txn_id = NULL, $notes = NULL) {
    Txn::logThisFunction();

    if ($acct === null) {
      throw new Exception('SpendCardTxn: $acct is null');
    }

    parent::__construct($amount, 5, $txn_id, $notes);
    $this->acct = $acct;
    $this->original_amount = $amount;
  }

  public function write() {
    Txn::logThisFunction();
    if ($this->original_amount != $this->amount) {
      Txn::log("SpendCardTxn::write: ->amount ($this->amount) not equal to ->original_amount ($this->original_amount)");
    }

    $acct_id = Txn::getAccountId($this->acct);
    if (!$acct_id) {
      $acct_id = parent::_createAccount($this);
    }

    // Don't write unnecessary payments
    if ($this->amount == 0)
      return;

    $type = 'withdraw';
    self::createTransaction($type, $this->amount, 0, NULL,NULL, $acct_id, -$this->amount, $this->notes);
    self::adjustAccount($acct_id, -$this->amount, $this->notes, TRUE);

    return $this->amount;
  }
}



// TODO: A lot more fields -- gift cards have recipients, etc.
// see payments/payments.php process_gift_card()
class DepositTxn extends BuyTxn {

  public static $accounts = array(
    'deposit' => 2,
    'fund' => 2,
    'matching' => 4,
    'for matching' => 4,
    'discount' => 5,
    'for discount' => 5,
    'buy gc' => 6,
    'giftcard' => 3,
    'allocate' => 7,
    'to allocate' => 7
  );

  public function __construct($acct, $amount, $tip=0, $type = 'fund', $fr_id = 0, $blog_id = 0) {
    Txn::logThisFunction();

    if ($acct === null) {
      throw new Exception('DepositTxn: $acct is null');
    }

    parent::__construct($amount,$tip,0,$fr_id,$blog_id);
    $this->acct = $acct;

    if (is_numeric($type)) {
      $this->type = $type;
    }
    else {
      $type = strtolower($type);
      if (array_key_exists($type, self::$accounts)) {
        $this->type = self::$accounts[$type];
      }
      else {
        throw new Exception("invalid account type for DepositTxn: $type");
      }
    }
  }

  public function write($donation_id) {
    self::logThisFunction();

    if ($this->type == 7) { // To-allocates are special
      $this->type = "deposit";
      return parent::write($donation_id);
    }

    // Create an account - OR re-use if it already exists
    $acct_id = Txn::getAccountId($this->acct);
    if (empty($acct_id))
      $acct_id = self::_createAccount($this);

    if (empty($acct_id))
      throw new Exception("unknown account");

    // Register the deposit
    $type = $this->amount > 0 ? 'deposit' : 'withdraw';
    self::createTransaction($type, 0,$this->tip, NULL,NULL, $acct_id, $this->amount);
    self::adjustAccount($acct_id, $this->amount, $this->note, FALSE);

    return $this->amount + $this->tip;
  }
}

class ErrorTxn extends Txn {
  public function __construct($errorAmount, $note = NULL) {
    Txn::logThisFunction();
    $this->errorAmount = $errorAmount;
    $this->note = $note;
  }

  public function write($donation_id) {
    Txn::logThisFunction();

    global $wpdb;

    if (round($this->errorAmount,2) == 0)
      return; // Nothing to do

    Txn::flagError(); // for debugging import

    $existing = $wpdb->get_results($wpdb->prepare(
      "SELECT * FROM " . self::$table_txn . "
       WHERE type='error' AND donation_id = %d",
       $donation_id));

    if ($existing != null) {
      $txn = $existing[0];
      $this->errorAmount += $txn->tip;

      if ($this->errorAmount == 0) {
        // If the resulting error amount is 0, just delete the error
        // TODO: do we want to leave a trace?
        $r = $wpdb->query($wpdb->prepare(
          "DELETE FROM " . self::$table_txn . " WHERE id = %d",
          $txn->id));
        Txn::throw_on_error();
      } else {
        // Otherwise, update the existing error
        if (!empty($txn->notes))
          $this->note = "$this->note || previously: $txn->notes";

        $r = $wpdb->update(self::$table_txn, array(
          'tip' => $this->errorAmount,
          'notes' => $this->note,
          'date' => self::$date,
        ), array(
          'id' => $txn->id
        ));
        Txn::throw_on_error();
      }
      return;
    }

    // Insert a new error transaction
    self::createTransaction('error', 0,$this->errorAmount, NULL,NULL,NULL,NULL, $this->note);
  }
}



class Txn {
  protected static $table_txn = 'a_transaction';
  protected static $table_account = 'a_account';
  protected static $table_donation = 'a_donation';
  protected static $table_gift = 'a_gift';

  protected static $tip_account = 2;  // SYI Tip Fund account ID

  public static $isError; // debugging flag

  protected static $recorded;
  protected static $donation_id;
  protected static $donor_id;
  protected static $bundle_id;
  protected static $date;

  // Register a user's donation
  //
  // A donation is composed of a few buckets of information:
  //  - Payments: the money that's put into this donation
  //     from either a) outside sources such credit card, etc
  //     or b) internal accounts such as gift cards, etc
  //  - Gifts: money out of the donation that goes to partners
  //     which can either be a directed gift (specifies the
  //     exact gift and amount to be given) or a "give-any"/"to-allocate"
  //     which specifies only the amount
  //  - Gift Cards: money placed into an internal account, to
  //     be redeemed later
  // Any purchase (gifts / gift cards) can also specify that a
  // certain additional amount is intended for SYI as a "tip"
  //
  // parameters:
  //   $donor_id = ID of the donor this donation should be credited to
  //   $opts[] = all additional parameters in an associative array
  //     ['payments' => ..., 'purchases' => ...]
  //
  // Payments & purchass parameters should be built from
  // the appropriate classes elsewhere in this file:
  //   PayTxn
  //   SpendCardTxn
  //   BuyCardTxn
  //   etc.
  // Each parameter can be either 1 object, or an array of objects
  //
  // If the donation payments do not add up to the purchase amount,
  // an ERROR row is automatically added
  //
  public static function makeDonation($donor_id, $opts=array()) {
    self::logThisFunction();

    // make sure this whole thing is transactional and can roll back if there's an error
    $opts['donor_id'] = $donor_id;
    self::_beginTransaction($opts);

    try {
      $paid = 0;
      $amount = 0;
      $tip = 0;
      $diff = 0;

      // Turn each parameter into an array for later foreach
      $payments = self::makeArray($opts['payments']);
      $purchases = self::makeArray($opts['purchases']);

      // Sum up the payments
      foreach ($payments as $p) {
        $paid += $p->amount;
      }

      // Sum up the amounts + tips (to be recorded in the donation totals)
      foreach ($purchases as $p) {
        $amount += $p->amount;
        $tip += $p->tip;
      }

      // Create a donation record
      $donation_id = self::$donation_id = self::_createDonation($donor_id, $amount, $tip);

      // record the payments that were made
      // If no payments were made - there will be an outstanding balance
      // on the donation, but additional payments may come in later.
      // in the meantime, it's okay to not insert any payment rows
      foreach ($payments as $p) {
         $diff += $p->write($donation_id);
      }

      // Record the gifts and giftcards that were purchased
      foreach ($purchases as $p) {
        $diff -= $p->write($donation_id);
      }

      $diff = $diff;
      if ($diff != 0) {
        $error = new ErrorTxn($diff);
        $error->write($donation_id);
      }
    }
    catch (Exception $e) {
      self::log("Exception: " . $e->getMessage() . stacktrace());
      // TODO: roll back, deleting self::$recorded and all side effects
//      print("<tr><td colspan='10'>" . $e->getMessage() . "</td></tr>");
    }
    self::_endTransaction();
    return $donation_id;
  }

  // Update an existing donation
  //
  // Like makeDonation, but specifies an incremental set of payments and
  // purchases to add.  We use this to do allocations, issue refunds, etc.
  public static function updateDonation($donation_id, $opts) {
    self::logThisFunction();

    // make sure this whole thing is transactional and can roll back if there's an error
    $opts['donor_id'] = self::getDonorFromDonation($donation_id);
    self::_beginTransaction($opts);
    try {
      $amount = 0;
      $tip = 0;
      $diff = 0;

      // Turn each parameter into an array for later foreach
      $payments = self::makeArray($opts['payments']);
      $purchases = self::makeArray($opts['purchases']);

      // record all the updates
      foreach ($payments as $p) {
        $diff += $p->write($donation_id);
      }
      foreach ($purchases as $p) {
        $diff -= $p->write($donation_id);
      }

      $diff = $diff;
      if ($diff != 0) {
        $error = new ErrorTxn($diff);
        $error->write($donation_id);
      }
    }
    catch (Exception $e) {
      self::log("Exception: " . $e->getMessage() . stacktrace());
      // TODO: roll back, deleting self::$recorded and all side effects
    }
    self::_endTransaction();
  }

  // Pass in an array of DepositTxn objects (or subclasses)
  public static function transfer($xfers) {
    self::logThisFunction();
    global $wpdb;

    if (count($xfers) == 0)
      return; // Nothing to do.

    // make sure this whole thing is transactional and can roll back if there's an error
    self::_beginTransaction(array(
      // TODO: should we allow donor or donation ID?
      // technically this should not have a donation_id as it's not a donation
    ));
    try {
      $diff = 0;

      foreach ($xfers as $xfer) {
        if (isset($xfer->copy_row)) {
          self::$date = $xfer->copy_row->date;
        }
        $diff += $xfer->write(0);
      }

      if ($diff != 0) {
        $error = new ErrorTxn($diff);
        $error->write(0, self::_now());
      }

      $bundle_id = self::$bundle_id;
    }
    catch (Exception $e) {
      self::log("Exception: " . $e->getMessage() . stacktrace());
      // TODO: roll back, deleting self::$recorded and all side effects
    }

    self::_endTransaction();
    return $bundle_id;
  }

  // Given any argument, turn it into an array usable in foreach()
  protected static function makeArray($a) {
    // If the argument's already an array, return that.
    if (is_array($a))
      return $a;

    // Empty arguments: empty array
    if ($a === NULL)
      return array();

    // Everything else: return it inside an array
    return array($a);
  }

  // Allocate the money from an internal account on behalf of a user.
  // 
  // When donations produce a "to allocate" account, that money is stored
  // temporarily in an account that can only be spent on behalf of the original
  // donor.  This function can be called later to withdraw & spend that saved money.
  //
  // Any transactions recorded by this function are recognizable as having no "donor intent",
  // ie. not a separate action taken by the donor, and therefore not affecting stats.
  //
  // Parameters:
  //  - acct = <ID/object> for account to allocate
  //  - amount = $ amount to spend
  //  - tip = $ amount to tip
  //  - gift_id = ID of gift to purchase
  //  - donor_id = ID of donor to allocate as (can be left blank, will use
  //      the donor that owns the account)
  //  - donation_id = ID of donation to combine into (can be left blank, will
  //      try to find the donation that created the account and join that)
  //
  public static function allocateGifts($acct, $amount, $tip, $gift_id, $donor_id = 0, $donation_id = 0) {
    self::logThisFunction();

    $acct = self::getAccount($acct);
    if ($acct == NULL)
      throw new Exception("Unknown allocation account");

    if ($gift_id <= 50) // CART_GIVE_ANY
      throw new Exception("Invalid gift ID");

    if ($amount <= 0)
      return; // Nothing to do

    if (empty($donation_id)) {
      // we want allocations to join the donation that
      // created the to-allocate account.  We can reverse that out by looking at
      // the transaction(s) that created the account.  But if there's more than one,
      // we can't assume and must make a new donation rather than possibly joining
      // the wrong account.
      $donation_id = Txn::_findDonationForAccount($acct->id);

      // Can't determine a single donation_id to join?  Then this gets its own.
      if (empty($donation_id)) {
        $g = new BuyTxn($amount,$tip, $gift_id);
        $g->type = 'allocate';

        self::makeDonation($donor_id, array(
          'payments' => new SpendCardTxn($amount + $tip, $acct->id),
          'purchases' => $g
        ));
        return;
      }
    }

    // TODO: Convert everything past this point into a call to ::updateDonation

    if (empty($donor_id)) {
      $donor_id = self::getDonorFromDonation($donation_id);
    }

    // make sure this whole thing is transactional and can roll back if there's an error
    self::_beginTransaction(array(
      'donor_id' => $donor_id,
      'donation_id' => $donation_id
    ));
    try {
      // Create a new 'ALLOCATED' row in the table
      $txn_id = self::createTransaction('allocate', $amount, $tip, $gift_id, $acct->event_id, $acct->id, -$amount-$tip);
      $gifts = self::_createGifts($txn_id);

      // Register the payment (deduction from account)
      self::adjustAccount($acct->id, -$amount-$tip, "Allocated gifts " . implode(',', $gifts), TRUE);

    }
    catch (Exception $e) {
      self::log("Exception: " . $e->getMessage() . stacktrace());
      // TODO: roll back, deleting self::$recorded and all side effects
    }

    self::_endTransaction();
  }

  protected static function _now() {
    if (empty(self::$date))
      self::$date = current_time('mysql', 1);
    return self::$date;
  }

  protected static function _beginTransaction($opts) {
    self::logThisFunction();

    if (self::$recorded !== NULL)
      throw new Exception("can't nest transactions");

    self::$recorded = array();
    self::$bundle_id = NULL;
    self::$isError = FALSE;

    if (isset($opts['donor_id']))
      self::$donor_id = $opts['donor_id'];
    else
      self::$donor_id = 0;

    if (isset($opts['donation_id']))
      self::$donation_id = $opts['donation_id'];
    else
      self::$donation_id = 0;

    if (isset($opts['date']))
      self::$date = $opts['date'];
    else
      self::$date = current_time('mysql', 1);
  }

  protected static function _endTransaction() {
    self::logThisFunction();
    self::$recorded = NULL;
    self::$donor_id = NULL;
    self::$donation_id = NULL;
    self::$bundle_id = NULL;
    self::$date = NULL;
  }

  protected static function _createGifts($txn_id, $donationGifts = NULL) {
    self::logThisFunction();

    global $wpdb;

    $txn = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM " . self::$table_txn . " WHERE id=%d",
      $txn_id));

    self::throw_on_error();

    $amount = $txn->amount;

    // Fetch info about this gift from the DB
    $gift = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM gift WHERE id=%d",
      $txn->gift_id));

    self::throw_on_error();

    // Don't allocate incorrect gifts
    if ($gift->unitAmount <= 0)
      throw new Exception("invalid gift amount for $txn_id:$txn->gift_id");

    // Template for the gift rows that are about to be inserted
    $g = array(
      'donationID' => $txn->donation_id,
      'trans_id' => $txn_id,
      'giftID' => $txn->gift_id,
      'amount' => $gift->unitAmount,
      'blog_id' => $gift->blog_id,
      'towards_gift_id' => $gift->towards_gift_id,
      'event_id' => $txn->fr_id
    );

    $ids = array();

    // Handle an import case - specific donationGift IDs
    // Each member of array should be (id,gift_id,amount,match)
    if ($donationGifts !== NULL) {
      foreach ($donationGifts as $dg_id) {
        $dg = $wpdb->get_row($wpdb->prepare(
          "SELECT * FROM donationGifts WHERE ID=%d",
          $dg_id));
        if ($dg == NULL) {
          Txn::log('Could not find donationGift to import');
          continue;
        }

        $r = $wpdb->insert(self::$table_gift, self::override($g, array(
          'id' => $dg->ID,
          'amount' => $dg->amount,
          'giftID' => $dg->giftID,
          'tip' => $dg->tip,
          'story' => $dg->story,
          'matchingDonationAcctTrans' => $dg->matchingDonationAcctTrans
        )));
        self::throw_on_error();
        $amount -= $dg->amount;
        $ids[] = $wpdb->insert_id;
      }

      // TODO NEW:  Handle error case where amount < 0 (definite error)
      // or amount > 0 (less troublesome but still probably an error)
    }

    // If the gift isn't a variably-priced gift...
    if (!$gift->varAmount) {
      // Allocate full gifts
      while ($amount >= $gift->unitAmount) {
        $r = $wpdb->insert(self::$table_gift, $g);
        self::throw_on_error();
        $amount -= $g['amount'];
        $ids[] = $wpdb->insert_id;
      }
    }

    // Allocate a final partial gift
    if ($amount > 0) {
      $g['amount'] = $amount;
      $r = $wpdb->insert(self::$table_gift, $g);
      self::throw_on_error();
      $ids[] = $wpdb->insert_id;
    }

    return $ids;
  }

  public static function getAccount($code_or_id, $include_expired = TRUE) {
    self::logThisFunction();
    global $wpdb;

    if (empty($code_or_id))
      return NULL;

    if (is_object($code_or_id))
      return $code_or_id;

    if (empty($code_or_id))
      throw new Exception("Invalid account code/id: it is empty");

    $code_or_id = trim($code_or_id);

    $i = intval($code_or_id);
    if (strlen($code_or_id) < 7 && $i > 0) {
      // We could just return i, but this tests that the account
      // actually exists.
      $where = $wpdb->prepare(" WHERE id=%d", $i);
    } else {
      $where = $wpdb->prepare(" WHERE code=%s", $code_or_id);
    }

    if (!$include_expired)
      $where = "$where AND NOT(expired=1)";

    $account = $wpdb->get_row($sql = "SELECT * FROM " . self::$table_account . $where);
    self::throw_on_error();
    return $account;
  }

  public static function getAccountId($code_or_id, $include_expired = TRUE) {
    self::logThisFunction();
    $acct = self::getAccount($code_or_id, $include_expired);
    if ($acct == NULL)
      return NULL;
    return $acct->id;
  }

  // Helper function to generate a random account code.  Adapted from donation-acct.php
  public static $code_chars = "ABCDEFHKLMNPQRSTUVWXY3489";
  public static function generate_random_code(){
    srand(doubleval(microtime()*1000000));

    do {
      $pass = '' ;
      for($i = 0; $i < 10; $i++) {
        $num = rand(0,24);
        $tmp = substr(self::$code_chars, $num, 1);
        $pass .= $tmp;
      }

      $acct = self::getAccount($pass);
    } while($acct != NULL);

    return $pass;
  }

  // Helper function to get all to-allocate accounts for a particular fundraiser
  // (returns the to-allocate amount, NOT the current balance)
  public static function getAllocsForFundraiser($fr_id) {
    self::logThisFunction();
    global $wpdb;

    $res = $wpdb->get_results($sql = $wpdb->prepare(
      "SELECT donation_id,donor_id,card,acct_id FROM " . self::$table_txn . " t
       WHERE t.acct_id > 0 AND t.type='gift' AND t.fr_id=%d", 
      $fr_id));
    self::throw_on_error();
    return $res;
  }

  // Helper function to get all giftcard IDs that were created by a specific donation
  public static function getCards($donation_id) {
    self::logThisFunction();
    global $wpdb;
    $col = $wpdb->get_col($sql = $wpdb->prepare(
      "SELECT DISTINCT t.acct_id FROM " . self::$table_txn . " t
      WHERE t.donation_id = %d and t.type='deposit' ",
      $donation_id));
    if (count($col) == 0) throw new Exception('$wpdb returned empty array');
    return $col;
  }

  // Helper function to find the donation that created a certain account
  public static function _findDonationForAccount($acct_id) {
    self::logThisFunction();

    global $wpdb;

    $results = $wpdb->get_col($sql = $wpdb->prepare(
      "SELECT donation_id FROM " . self::$table_txn . " WHERE acct_id=%d AND type='gift'",
      $acct_id));
    if (count($results) == 1)
      return $results[0];
    return NULL;
  }

  // Helper function to get the donor for a specific donation
  public static function getDonorFromDonation($donation_id) {
    self::logThisFunction();

    global $wpdb;
    return $wpdb->get_var($sql = $wpdb->prepare(
      "SELECT donorID FROM " . self::$table_donation . " WHERE donationID=%d",
      $donation_id));
  }

  // used in self::record() to stringify debug_backtrace frames
  protected static function debugFunctionName($f) {
    return $f['function'] . '(' . implode(',', array_values($f['args'])) . ')';
  }
  protected static function debugStacktrace($frames = NULL) {
    $slice = 3;

    if ($frames == NULL) {
      $frames = debug_backtrace();
      $slice++;
    }

    return implode(',', array_map(array(self, 'debugFunctionName'), array_slice($frames, 1, $slice)));
  }

  protected static function _createDonation($donor_id, $amount, $tip) {
    self::logThisFunction();

    global $wpdb;

    // Insert it into our transaction table
    $r = $wpdb->insert(self::$table_donation, self::override(array(
      'donorID' => $donor_id,
      'donationDate' => self::$date,
      'donationAmount_Total' => $amount + $tip,
      'amount' => $amount,
      'tip' => $tip,
      'test' => FALSE
    ), array(
      'donationID' => self::$donation_id
    )));

    self::throw_on_error();
    return $wpdb->insert_id;
  }

  // Create a new account with zero balance
  protected static function _createAccount($a) {
    self::logThisFunction();

    global $wpdb;

    $owner = $a->owner;
    if (empty($owner))
      $owner = self::$donor_id;

    // This is kind of ugly, but it's temporary while running both tablesets
    // copy_acct_id can be a code or an ID.  If it's a code, there's a chance it's a code that
    // doesn't exist, in which case we can at least use the code when creating the new account
    $code = '';
    if (isset($a->copy_row)) {
      $row = $a->copy_row;

      if (preg_match('/^(\S+)/', $row->reference, $m)) {
        $code = $m[1];
      }
    }
    else if (isset($a->copy_acct_id)) {
      $code = $a->copy_acct_id;
    }

    if ($code) {
      // Does it already exist?
      $existing = Txn::getAccount($code);
      if ($existing != NULL) {
        return $existing->id;
      }

      $copy = $wpdb->get_row($wpdb->prepare(
        "SELECT id, code, event_id, owner, note, donationAcctTypeId, donationAcctTypeId as type, dateCreated, params FROM donationAcct WHERE code=%s", $code));
      if (!$copy)
        throw new Exception("Copy_row code '$code' is missing");
      $code = $copy->code;
    } else {
      $copy = NULL;
      $code = self::generate_random_code(); // from donation-acct.php
    }

    // TODO: cover all of the fields in $a
    $r = $wpdb->insert(self::$table_account, self::override(array(
      'balance' => 0, // Always creates with zero balance
      'code' => $code,
      'donationAcctTypeId' => $a->type,
      'type' => $a->type,
      'dateCreated' => self::$date,
      'event_id' => $a->fr_id,
      'owner' => $owner,
      'note' => $a->note
    ), $copy));
    self::throw_on_error();
    $acct_id = $wpdb->insert_id;
    self::_recordAcctTrans($acct_id, $a->amount, "Initial account balance");

    return $acct_id;
  }

  public static function override($a1, $a2) {
    if ($a2 == NULL)
      return $a1;
    foreach ($a2 as $k=>$v) {
      if ($v !== NULL)
        $a1[$k] = $v;
    }
    return $a1;
  }

  public static function adjustAccount($acct_id, $amount, $note, $is_use = FALSE) {
    self::logThisFunction();

    global $wpdb;

    if (empty($acct_id))
      throw new Exception("invalid adjustment account: acct_id is empty");

    if ($amount == 0)
      return; // Nothing to do

    if ($is_use)
      $use_fields = ",`use` = `use` + 1, dateUpdated = '".self::$date."'";
    else
      $use_fields = "";

    $r = $wpdb->query($wpdb->prepare("
      UPDATE " . self::$table_account . "
      SET balance = ROUND(balance + %f, 2)
      $use_fields
      WHERE id = %d",
      $amount, $acct_id));
    self::throw_on_error();
    self::_recordAcctTrans($acct_id, $amount, $note);
  }

  protected static function _recordAcctTrans($acct_id, $amount, $note) {
    /* TODO: backwards compat once we merge back into the real tables
    insert_donation_acct_trans($acct_id, $amount, NULL, $note);
    */
    self::logThisFunction();
  }


  // record a transaction between 2 accounts
  public static function createTransaction($type, $amount, $tip_amount, $gift_id = NULL, $fr_id = NULL, $acct_id = NULL, $acct_amount = NULL, $notes = NULL) {
    self::logThisFunction();

    global $wpdb;

    // Must specify at least one destination for the transaction
    if ($amount > 0 && $gift_id == NULL && $fr_id == NULL && ($type != 'withdraw' && $type != 'allocate'))
      throw new Exception('no transaction destination specified');

    if ($acct_id == self::$tip_account) {
      $tip_amount = $acct_amount;
      $acct_amount = 0;
    } else if ($acct_id > 0 && empty($acct_amount))
      throw new Exception('no account amount specified');

    if (empty($amount) && empty($tip_amount) && empty($acct_amount))
      throw new Exception('no transaction amounts specified');

    // Insert it into our transaction table
    $r = $wpdb->insert(self::$table_txn, array(
      'bundle_id' => self::$bundle_id,
      'donation_id' => self::$donation_id,
      'donor_id' => self::$donor_id,
      'type' => $type,
      'amount' => $amount,
      'card' => $acct_amount,
      'tip' => $tip_amount,
      'date' => self::$date,
      'gift_id' => $gift_id,
      'fr_id' => $fr_id,
      'acct_id' => $acct_id,
      'notes' => $notes
    ));
    self::throw_on_error();
    $txn_id = $wpdb->insert_id;

    if (self::$bundle_id === NULL) {
      // This is the first transaction, so use its ID as the bundle ID for it and
      // all subsequent transactions in the same bundle.
      self::$bundle_id = $txn_id;
      $wpdb->update(self::$table_txn,
        array( 'bundle_id' => $txn_id ), 
        array( 'id' => $txn_id )
      );
    }

    // Bank the tip
    if ($tip_amount > 0)
      self::adjustAccount(self::$tip_account, $tip_amount, "Tip for transaction $txn_id", FALSE);

    self::$recorded[] = $txn_id;
    return $txn_id;
  }

  // TESTS
  public static function resetTests() {
    self::logThisFunction();

    global $wpdb;

    $wpdb->query("delete from " . self::$table_txn);
    $wpdb->query("delete from " . self::$table_donation);
    $wpdb->query("delete from " . self::$table_gift);
    $wpdb->query("delete from " . PayTxn::$table_name);

    // burn two IDs so we never create account 2
    // this won't be necessary once the testing class becomes real
    $wpdb->insert(self::$table_account, array('code' => 'X'));
    $wpdb->insert(self::$table_account, array('code' => 'Y'));
    $wpdb->query("delete from " . self::$table_account);
    $wpdb->insert(self::$table_account, array(
      'id' => self::$tip_account,
      'type' => 1 /* general */
    ));
  }

  public static function test() {
    self::logThisFunction();

    global $wpdb;

    $total_payments = $wpdb->get_var("SELECT SUM(amount) FROM " . PayTxn::$table_name );
    $total_accounts = $wpdb->get_var("SELECT SUM(balance) FROM " . self::$table_account );
    $total_used = $wpdb->get_var("SELECT SUM(amount+tip+card) FROM " . self::$table_txn );
    $total_into_gift = $wpdb->get_var("SELECT SUM(amount) FROM " . self::$table_txn );
    $total_into_fr = $wpdb->get_var("SELECT SUM(amount+card) FROM " . self::$table_txn . " WHERE fr_id > 0");
    $total_into_acct = $wpdb->get_var("SELECT SUM(card) FROM " . self::$table_txn );
    $total_from_acct = $wpdb->get_var("SELECT SUM(amount) FROM " . PayTxn::$table_name . " WHERE acct_id > 0" );
    $total_gifts = $wpdb->get_var("SELECT SUM(amount) FROM " . self::$table_gift );
    $total_errors = $wpdb->get_var("SELECT SUM(tip) FROM " . self::$table_txn . " WHERE type='error' ");

    $total_cash_in = $wpdb->get_var("SELECT SUM(amount) FROM " . PayTxn::$table_name . " WHERE provider != 5");

    $expected_tip = $wpdb->get_var("SELECT SUM(tip) FROM " . self::$table_donation );
    $expected_tip2 = $wpdb->get_var("SELECT SUM(tip) FROM " . self::$table_txn ) - $total_errors;
    $actual_tip = $wpdb->get_var("SELECT balance FROM " . self::$table_account . " WHERE id=" . self::$tip_account);

    ?>
    <PRE>
    <?= $total_payments + 0 ?> paid = <?= $total_used + 0 ?> used?
    <?= $total_accounts - $expected_tip2 ?> in non-tip accts = <?= $total_into_acct - $total_from_acct ?> used for accts?
    <?= $total_gifts + 0 ?> in gifts = <?= $total_into_gift + 0 ?> used for gifts?
    <?= $actual_tip + 0 ?> in tip account = <?= $expected_tip + 0 ?>,<?= $expected_tip2 + 0 ?> expected tip?
    <?= $total_accounts + $total_gifts + $total_errors ?> accounted = <?= $total_cash_in + 0 ?> cash in? (<?= -$total_errors ?> in errors)
    <?= $total_accounts - $actual_tip + 0 ?> total liabilities
    </PRE>
    <?

    sleep(1);
  }

  public static function log($str) {
    SyiLog::log('txn', $str);
  }

  public static function logThisFunction() {
    $frames = debug_backtrace();
    $frame = (object)$frames[1];
    $file = str_replace(ABSPATH, '', $frame->file);
    $args = json_encode($frame->args);

    SyiLog::log('txn', "{$frame->class}{$frame->type}{$frame->function} $args ($file $frame->line)");
  }

  protected static $wpdb_exceptions = false;
  public static function throw_on_error() {
    if (!self::$wpdb_exceptions) return;
    global $wpdb;
    if ($wpdb->last_error !== '') throw new Exception('$wpdb->last_error: '.$wpdb->last_error);
  }

  // Debugging function used to identify transactions that had an error
  public static function flagError() {
    self::$isError = TRUE;
  }
}
