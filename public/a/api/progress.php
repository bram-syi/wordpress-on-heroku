<?php

require_once(__DIR__.'/api.php');

class ProgressApi extends Api {

  public static function get($req) {
    $record = req($req, array('campaign', 'from', 'to', 'interval'));

    if ($record->interval == 'hour')
      $interval = "DATE_ADD(
        DATE_FORMAT(dat.dateInserted, '%%Y-%%m-%%d %%H:00:00'),
        INTERVAL IF(MINUTE(dat.dateInserted) < 30, 0, 1) HOUR)";
    else
      $interval = "DATE(dat.dateInserted)";

    $query = new ApiQuery(
      "$interval AS date,
      SUM(IFNULL(dat.amount, 0) * (p1.amount/(p1.amount+p1.tip))) as raised,
      SUM(IFNULL(dat.amount, 0) * (p1.tip/(p1.amount+p1.tip))) as tip",
      "donationAcct da
      JOIN donationAcctTrans dat ON dat.donationAcctId = da.id AND dat.amount > 0
      LEFT JOIN payment p1 ON p1.id=dat.paymentID
      LEFT JOIN donation d on d.paymentID=p1.ID
      JOIN donationGiver donor ON da.owner = donor.ID
      LEFT JOIN wp_1_posts wp ON wp.ID = da.event_id 
      LEFT JOIN donationAcctTrans dat2 ON dat2.paymentID=p1.id AND dat2.donationAcctId != da.id AND dat2.amount < 0
      LEFT JOIN donationAcct da2 on dat2.donationAcctId = da2.id
      LEFT JOIN donationAcct daMatch on dat2.donationAcctId = daMatch.id AND daMatch.donationAcctTypeId=4
      LEFT JOIN campaigns c on c.post_id=da.event_id");

    if (!empty($record->campaign))
      $query->where("c.theme = %s", $record->campaign);

    if (!empty($record->from))
      $query->where("dat.dateInserted >= %s", $record->from);
    if (!empty($record->to))
      $query->where("dat.dateInserted < %s", $record->to);

    $query->where("IFNULL(d.test,0) = 0");
    $query->where("d.donationID > 0");
    $query->where("NOT (IFNULL(da2.donationAcctTypeId,0) = 7 and da2.event_id = da.event_id)");
    $query->where("da.donationAcctTypeId > 2");

    $query->group("date");
    $query->order("date DESC");

    return $query->get_results();
  }

  public static function getColumns($req) {
    return array(
      'date' => array( 'id' => TRUE, 'type' => 'date' ),
      'raised' => 'money',
      'tip' => 'money'
    );
  }
}

register_api(__FILE__, 'ProgressApi');
