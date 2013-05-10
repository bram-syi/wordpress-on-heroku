<?php

require_once(__DIR__.'/api.php');
require_once(__DIR__.'/partner.php');
require_once(__DIR__.'/donor.php');

class DonationApi extends Api {

  // This has been converted to work with NEW format tables
  public static function get($req) {
    $record = req($req, array('campaign','partner:charity','#fr_id:fundraiser','donor','from','to','team','search'));
    $params = req($req, array('#new_tables'));

    $query = new ApiQuery(
      "CONCAT(donation.donationID,'-',IFNULL({$fr}.post_id,'0'),'-',IFNULL({$partner}.blog_id,'0')) as id,

      -- donation info
      donation.donationID,
      trans.date,

      -- Amounts
      SUM(IF(trans.acct_id = 0, trans.amount, 0)) as 'direct',
      SUM(IF(trans.acct_id > 0, trans.card, 0)) as 'allocated'",
      "a_transaction trans
      LEFT JOIN a_donation donation on donation.donationID = trans.donation_id
      LEFT JOIN a_gift g on g.id = trans.gift_id");

    PartnerApi::join($query, "g.blog_id");

    $donor = DonorApi::join($query, "trans.donor_id");
    $query->field("{$donor}.data as donor_data");

    $fr = FundraiserApi::join($query, "trans.fr_id");
    $query->field("{$fr}.theme as campaign");

    if (!$params->new_tables) {
      // OLD model

      $query = new ApiQuery(
        "donation.donationID,
        MAX(IF(payment.provider = 5 AND (account.donationAcctTypeId = 4 OR account.donationAcctTypeId =
  7), datIn.dateInserted, donation.donationDate)) as `date`,

        -- Amounts
        SUM(IF(IFNULL(account.donationAcctTypeId,0) = 7,donationGifts.amount,0)) AS 'allocated',
        SUM(IF(IFNULL(account.donationAcctTypeId,0) != 7,donationGifts.amount,0)) AS 'direct'",

        "donation
        LEFT JOIN donationGifts                 ON donation.donationID = donationGifts.donationID
        LEFT JOIN payment                       ON payment.id = donation.paymentID

        -- for allocated
        LEFT JOIN donationAcctTrans trans       ON trans.paymentID = payment.id
        LEFT JOIN donationAcct account          ON account.id = trans.donationAcctId
        LEFT JOIN donationAcctTrans datIn       ON trans.donationAcctId > 100 AND datIn.donationAcctId=trans.donationAcctId AND datIn.amount > 0
        LEFT JOIN payment payIn                 ON payIn.id = datIn.paymentId AND payIn.provider != 5
        LEFT JOIN donation donIn                ON donIn.paymentId = payIn.id AND donIn.donorID=donation.donorID");

      $donor = DonorApi::join($query, "donation.donorID");

      $partner = PartnerApi::join($query, "donationGifts.blog_id");

      $fr = FundraiserApi::join($query, "IFNULL(donationGifts.event_id, account.event_id)");
      $query->field("{$fr}.theme as campaign");

      $query->field("CONCAT(donation.donationID,'-',IFNULL({$fr}.post_id,'0'),'-',IFNULL({$partner}.blog_id,'0')) as id");
    }

    if ($record->donor > 0)
      $query->where("{$donor}.ID = %d", $record->donor);

    if (!empty($record->partner))
      $query->where("{$partner}.domain = %s", $record->partner);

    if (!empty($record->fr_id))
      $query->where("{$fr}.post_id = %d", $record->fr_id);

    if (!empty($record->from))
      $query->where("IF(payment.provider = 5 AND (account.donationAcctTypeId = 4 OR account.donationAcctTypeId = 7), payIn.dateTime, donation.donationDate) >= %s", $record->from);
    if (!empty($record->to))
      $query->where("IF(payment.provider = 5 AND (account.donationAcctTypeId = 4 OR account.donationAcctTypeId = 7), payIn.dateTime, donation.donationDate) < %s", $record->to);

    if (!empty($record->campaign))
      $query->where("{$fr}.theme = %s", $record->campaign);

    if (!empty($record->team)) {
      // Fetch this team first, it's way easier than trying to build it into the query.
      $t = TeamApi::getOne(array(
        'campaign' => $record->campaign,
        'team' => $record->team
      ));

      $team = TeamApi::join($query, array(
        $t->partner_id,
        $t->campaign_page_id,
        "{$fr}.team"
      ));

      if ($t->independent)
        $query->where("{$team}.id = %d OR {$team}.id IS NULL", $t->team_id);
      else
        $query->where("{$team}.id = %d", $t->team_id);
    }

    if (!empty($record->search)) {
      $like = "%$record->search%";

      // TODO: better search
      $query->where(
        "(({$donor}.firstName like %s) or ({$donor}.lastName like %s) or ({$donor}.email like %s))",
        $like, $like, $like);
    }

    $query->order("date DESC");

    if ($params->new_tables)
      $query->where("trans.type = 'gift'");

    $query->group("donation.donationID, fundraiser_id, partner_id
      HAVING allocated > 0 OR direct > 0");

    $query->require_wheres(); // Expensive query
    $results = $query->map_results(array(__CLASS__, 'format_row'));
    return $results;

/*
      CONCAT(donation.donationID,'-',IFNULL(c.post_id,'0'),'-',IFNULL(ch.blog_id,'0')) as id,
      c.theme as campaign,

      donor.id as donor_id,
      donor.firstName as first,
      donor.lastName as last,
      donor.email,
      donor.address, donor.address2, donor.city, donor.state, donor.zip,

      MAX(donor.share_email) AS 'share_email',
      -- looking first by user_id, and then via donor ID, get the donor type
      -- if the donor type is NULL or 0, then they are normal
      -- otherwise they are special
      ch.name as partner_name, ch.domain as partner_domain, ch.blog_id as partner_id,
      donation.donationID,
      MAX(IF(info1.donorType IS NULL, IFNULL(info2.donorType,0), info1.donorType)) AS 'donor_type',
      MAX(IF(payment.provider = 5 AND (account.donationAcctTypeId = 4 OR account.donationAcctTypeId = 7), payIn.dateTime, donation.donationDate)) as `date`,
      SUM(IF(IFNULL(account.donationAcctTypeId,0) = 7,donationGifts.amount,0)) AS 'allocated',
      SUM(IF(IFNULL(account.donationAcctTypeId,0) != 7,donationGifts.amount,0)) AS 'direct',

      account.event_id AS fundraiser_id, u.display_name as fundraiser_owner,
      c.guid as fundraiser_url, c.team as fundraiser_team

      $fields

    FROM donation
    LEFT JOIN donationGifts                 ON donation.donationID = donationGifts.donationID
    LEFT JOIN charity ch                    ON ch.blog_id = donationGifts.blog_id
    LEFT JOIN payment                       ON payment.id = donation.paymentID
    LEFT JOIN donationAcctTrans trans       ON trans.paymentID = payment.id
    LEFT JOIN donationAcct account          ON account.id = trans.donationAcctId
    LEFT JOIN donationGiver donor           ON donor.id = IF(IFNULL(account.donationAcctTypeId,0) = 7, account.donorID, donation.donorID)
    LEFT JOIN donorInfo info1               ON info1.user_id = donor.user_id
    LEFT JOIN donorInfo info2               ON info2.donorID = donor.id
    LEFT JOIN donationAcctTrans datIn       ON trans.donationAcctId > 100 AND datIn.donationAcctId=trans.donationAcctId AND datIn.amount > 0
    LEFT JOIN payment payIn                 ON payIn.id = datIn.paymentId AND payIn.provider != 5
    LEFT JOIN donation donIn                ON donIn.paymentId = payIn.id AND donIn.donorID=donor.id
    LEFT JOIN campaigns c                   ON c.post_id = IFNULL(donationGifts.event_id, account.event_id)
    LEFT JOIN wp_users u                    ON u.id = c.owner
    $wheres
    GROUP BY account.event_id,email,fundraiser_id,partner_domain
    $orders
    LIMIT 10000";
*/

  }

  public static function getColumns($req) {
    $columns = array(
      'id' => 'id',
      'date' => 'date',
      'group:donor' => $d = array(
        'donor_first' => TRUE,
        'donor_last' => TRUE,
        'donor_email' => 'email'
      ),
      'allocated' => 'money',
      'direct' => 'money',
      'group:partner' => array(
        'partner_name' => TRUE,
        'partner_domain' => TRUE,
        'partner_id' => 'id'
      ),
      'group:fundraiser' => array(
        'fundraiser_name' => TRUE,
        'fundraiser_owner' => TRUE,
        'fundraiser_url' => 'url',
        'fundraiser_team' => TRUE,
        'fundraiser_id' => 'id'
      ),
      'group:address' => array(
        'address' => TRUE,
        'address2' => TRUE,
        'city' => TRUE,
        'state' => TRUE,
        'zip' => TRUE
      )
    );

    if (static::hasPermission("/partners")) {
      $d['donor_id'] = 'id';
      $columns['donationID'] = 'int';
    }

    return $columns;
  }

  public static function format_row($row) {
    if (!static::hasPermission("/partner/$row->partner_domain"))
      return null;

    protect_email($row->email);
    return $row;
  }

}

// Direct request = run the API
register_api(__FILE__, 'DonationApi');
