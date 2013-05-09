<?php

include_once('wp-load.php');

ensure_logged_in_admin();

$theme = eor($_REQUEST['campaign'], 'readathon');
$from = eor($_REQUEST['from'], '2012-06-01');
$interval = $_REQUEST['interval'] == 'hour' ?
  "   DATE_ADD(
          DATE_FORMAT(dat.dateInserted, '%%Y-%%m-%%d %%H:00:00'),
          INTERVAL IF(MINUTE(dat.dateInserted) < 30, 0, 1) HOUR
      )" : "DATE(dat.dateInserted)";

global $wpdb;
$results = $wpdb->get_results($sql = $wpdb->prepare("
  SELECT 
      $interval AS date,
      SUM(IFNULL(dat.amount, 0) * (p1.amount/(p1.amount+p1.tip))) as raised,
      SUM(IFNULL(dat.amount, 0) * (p1.tip/(p1.amount+p1.tip))) as tip
  FROM donationAcct da
  JOIN donationAcctTrans dat ON dat.donationAcctId = da.id AND dat.amount > 0
  LEFT JOIN payment p1 ON p1.id=dat.paymentID
  LEFT JOIN donation d on d.paymentID=p1.ID
  JOIN donationGiver donor ON da.owner = donor.ID
  LEFT JOIN wp_1_posts wp ON wp.ID = da.event_id 
  LEFT JOIN donationAcctTrans dat2 ON dat2.paymentID=p1.id AND dat2.donationAcctId != da.id AND dat2.amount < 0
  LEFT JOIN donationAcct da2 on dat2.donationAcctId = da2.id
  LEFT JOIN donationAcct daMatch on dat2.donationAcctId = daMatch.id AND daMatch.donationAcctTypeId=4
  LEFT JOIN campaigns c on c.post_id=da.event_id
  WHERE IFNULL(d.test,0) = 0
    AND c.theme=%s
    AND d.donationID > 0 AND dat.dateInserted >= %s
    AND NOT (IFNULL(da2.donationAcctTypeId,0) = 7 and da2.event_id = da.event_id) 
    AND da.donationAcctTypeId > 2
  GROUP BY date", $theme, $from));

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
echo json_encode($results);
