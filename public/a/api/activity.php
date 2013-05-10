<?php

require_once(__DIR__.'/api.php');

class ActivityApi extends Api {

  // GET
  //   donor: ID of a specific donor
  //   type: 'gift','pay','allocate' or 'giftcard' (for now)
  //   fr_id: ID of a fundraiser
  //   account: code or ID of an account
  //   partner: domain or ID of a partner
  //   from, to:  date range
  public static function get($req) {
    $req = req($req, array('donor','from','to', 'type','fr_id', 'gift_id:gift', 'account','partner'));

    $query = new ApiQuery(
      "t.*", 
      "((SELECT
          CONCAT('T', CAST(trans.id AS CHAR(10))) as id,
          trans.donor_id as donor_id, 
          donor.firstName as donor_first, donor.lastName as donor_last,
          donor.email as donor_email, 
          /* donorType, */
          donation.donationID as donation_id, 
          donation.amount + donation.tip as total,

          trans.date as date,
          trans.amount+trans.tip+trans.card as amount,
          trans.amount as `out`,
          trans.tip as `keep`,
          trans.card,
          CAST(trans.type AS CHAR(20)) AS type,
          null AS provider,
          acct.code as account_code,
          acct.type AS account_type,
          acct.id as account_id,
          null as txn_id,
          gift.displayName as gift_name,
          gift.id as gift_id,
          c.name as partner_name,
          c.domain as partner_domain,
          c.blog_id as partner_id,
          trans.fr_id as fr_id,
          trans.notes as notes
        FROM a_transaction trans
        left join a_donation donation on donation.donationID = trans.donation_id
        left join donationGiver donor on donor.id = trans.donor_id
        left join a_account acct on trans.acct_id = acct.id
        left join gift gift on gift.id = trans.gift_id
        left join charity c on c.blog_id = gift.blog_id
    ) union (
        SELECT
          CONCAT('P', CAST(p.id AS CHAR(10))) as id,
          donation.donorID as donor_id, 
          donor.firstName as donor_first, donor.lastName as donor_last, 
          donor.email as donor_email, 
          /* donorType, */
          donation.donationID as donation_id,
          donation.amount + donation.tip as total,

          p.dateTime as date,
          -p.amount as amount,
          0 as `out`,
          0 as keep,
          IF(acct.id IS NOT NULL, -p.amount, 0) as card,
          'PAY' as type,
          p.provider AS provider,
          acct.code as account_code,
          acct.type AS account_type,
          acct.id as account_id,
          p.txnID as txn_id,
          null as gift_name,
          null as gift_id,
          null as partner_name,
          null as partner_domain,
          null as partner_id,
          null as fr_id,
          CONCAT('PMT# ', CAST(p.id AS CHAR(10))) as notes
        FROM a_payment p
        left join a_donation donation on donation.donationID = p.donationID
        left join donationGiver donor on donor.id = donation.donorID
        left join a_account acct on p.acct_id = acct.id
    )) as t");

    if (!empty($req->donor))
      $query->where("donor_id = %d", $req->donor);
    if (!empty($req->type))
      $query->where("type = %s", $req->type);
    if (!empty($req->fr_id))
      $query->where_expr("fr_id", $req->fr_id);
    if (!empty($req->gift_id))
      $query->where_expr("gift_id", $req->gift_id);
    if (!empty($req->account))
      $query->where("account_id = %d OR account_code LIKE %s", $req->account, "%$req->account%");
    if (!empty($req->partner))
      $query->where("partner_id = %d OR partner_domain = %s", $req->partner, $req->partner);
    if (!empty($req->from))
      $query->where("date >= %s", $req->from);
    if (!empty($req->to))
      $query->where("date < %s", $req->to);

    $query->order("donor_id ASC");
    $query->order("donation_id ASC");
    $query->order("date ASC");
    $query->order("amount ASC");

    return $query->map_results(array(__CLASS__, 'format_row'));
  }

  public static function getColumns($req) {
    return array(
      'id' => 'id',
      'group:donor' => array(
        'donor_first' => TRUE,
        'donor_last' => TRUE,
        'donor_email' => 'email',
        'donor_id' => 'id'
      ),
      'donation_id' => 'id',
      'total' => 'money',
      'date' => 'date',
      'amount' => 'money',
      'out' => 'money',
      'keep' => 'money',
      'card' => 'money',
      'type' => TRUE,
      // 'provider' => 'int',
      'group:account' => array(
        'account_code' => TRUE,
        // 'account_type' => TRUE,
        'account_id' => 'id'
      ),
      'group:gift' => array(
        'gift_name' => TRUE,
        'gift_id' => 'id'
      ),
      'group:partner' => array(
        'partner_name' => TRUE,
        'partner_domain' => "partner",
        'partner_id' => 'id'
      ),
      'group:fundraiser' => array(
        'fr_id' => 'id'
      ),
      'notes' => 'text'
    );
  }

  public static function format_row($row) {
    static $PAY_OUTS = array( 'unknown fund', 'INTERNAL', 'FROM FUND', 'SPEND GC', 'MATCH', 'DISCOUNT', 'SPEND GC', 'ALLOCATED' );
    static $PAY_INS = array( 'unknown deposit', 'DEPOSIT', 'DEPOSIT', 'BUY GC', 'FOR MATCHING', 'FOR DISCOUNT', 'BUY GC', 'TO ALLOCATE' );
    static $PMS = array(
      'unknown', // 0
      'PAYPAL',  // 1
      'CC', // 2
      'GOOGLE', // 3
      'AMAZON', // 4
      'SPEND GC', // 5
      'MATCHING', // 6
      'PAYPAL', // 7
      'SP', // 8
      'RECURLY', // 9
      'XFER', // 10
      'CASH/CHECK' // 11
    );

    if ($row->type == 'tip') {
      $row->type = 'TIP';
    } else if ($row->account_type > 0) {
      if ($row->amount > 0)
        $row->type = $PAY_INS[$row->account_type];
      else
        $row->type = $PAY_OUTS[$row->account_type];
    } else if ($row->provider > 0) {
      $row->type = $PMS[$row->provider];
    } else if ($row->type == 'allocated') {
      $row->type = 'GIFT';
    } else {
      $row->type = strtoupper($row->type);
    }

    return $row;
  }

}
register_api(__FILE__, 'ActivityApi');
