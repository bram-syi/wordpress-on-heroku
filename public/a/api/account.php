<?php

require_once(__DIR__ . '/api.php');
require_once(ABSPATH . '/a/api/donor.php');

class AccountApi extends Api {

  // GET
  //   donor: ID of a specific donor
  //   type: 'gift','pay','allocate' or 'giftcard' (for now)
  //   fr_id: ID of a fundraiser
  //   account: code or ID of an account
  //   partner: domain or ID of a partner
  //   from, to:  date range
  public static function get($req) {
    $record = req($req, array('id:account_id', 'donor', 'type','expired','code','fr_id','campaign','balance'));

    $query = new ApiQuery(
      "da.id as id, da.balance, da.code, da.dateCreated, da.dateUpdated,
      da.owner, da.creator, da.donationAcctTypeId as type, da.note, da.params,
      da.expired, dat.name as type",
      "donationAcct AS da
      LEFT JOIN donationAcctType AS dat ON da.donationAcctTypeId = dat.id");

    $donor = DonorApi::join($query, "da.donorId");
    $fr = FundraiserApi::join($query, "da.event_id");

    if (isset($record->id))
      $query->where("da.id = %d", $record->id);
    if (isset($record->donor))
      $query->where("{$donor}.id = %d", $record->donor);
    if ($record->type)
      $query->where("type = %s", $record->type);
    if (isset($record->balance))
      $query->where_expr("da.balance", $record->balance);
    if (isset($record->expired))
      $query->where("da.expired = %d", $record->expired);
    if (isset($record->code))
      $query->where("da.code = %s", $record->code);
    if (isset($record->fr_id))
      $query->where("{$fr}.id = %d", $record->fr_id);
    if (isset($record->campaign))
      $query->where("{$fr}.theme = %s", $record->campaign);

    $query->require_wheres(); // Expensive query

    $query->orders("(balance=0) ASC, {$donor}_first,{$donor}_last,{$donor}_email, balance");
    return $query->map_results(array(__CLASS__, 'format_row'));
  }

  public static function getColumns($req) {
    return array(
      'group:account' => array(
        'code' => TRUE,
        'id' => 'id'
      ),
      'balance' => 'money',
      'expired' => 'bool',
      'group:donor' => array(
        'first' => TRUE,
        'last' => TRUE,
        'email' => 'email',
        'donor_id' => 'id'
      ),
      'dateCreated' => 'date',
      'dateUpdated' => 'date',
      'group:fundraiser' => array(
        'fr_id' => 'id'
      ),
      'note' => 'text',
      'params' => 'text'
    );
  }

  public static function format_row($row) {
    return $row;
  }

}
register_api(__FILE__, 'AccountApi');
