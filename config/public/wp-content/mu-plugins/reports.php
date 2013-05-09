<?

function sum_donations_by_year($donor, $year = 2011) {

  if($donor->main == 0 || $donor->user_id == 0) return;
  $r = array();
  $r["total$year"] = 0;
  $r["deductible$year"] = 0;
  $r["gifts$year"] = 0;
  $r["errors$year"] = 0;
  
  //this (main) donor
  $nextyear = $year + 1;
  $report = sum_donations("$year-01-01", "$nextyear-01-01", $donor->ID);
  $r["total$year"] += floatval($report['donated']);
  $r["deductible$year"] += floatval($report['deductible']);
  $r["gifts$year"] += intval($report['gifts']);
  $r["errors$year"] += intval($report['errors']);

  $siblings = get_sibling_donors($donor->ID,true);

  if(is_array($siblings))
  foreach($siblings as $sibling) {
    $report = sum_donations("$year-01-01", "$nextyear-01-01", $sibling);
    $r["total$year"] += floatval($report['donated']);
    $r["deductible$year"] += floatval($report['deductible']);
    $r["gifts$year"] += intval($report['gifts']);
    $r["errors$year"] += intval($report['errors']);
  }

  echo "     $year: " . as_money($r["total$year"]) . " total, " 
   . as_money($r['deductible']) . " deductible";

  $gifts = intval($r["gifts$year"]);
  switch ($gifts) {
    case 0: break;
    case 1: echo ", 1 gift"; break;
    default: echo ", $gifts gifts"; break;
  }

  $errs = intval($r["errors$year"]);
  switch ($errs) {
    case 0: break;
    case 1: echo " [[ 1 error ]]"; break;
    default: echo " [[ $errs errors ]]"; break;
  }
  echo "\r\n";
 
  return $r;
}

function sum_donations($date_start,$date_end,$donor_id) {
  global $wpdb;
  global $PAYMENT_METHODS; //now in donation-acct.php
  $report = array();
  $report['gifts'] = 0;
  $report['payments'] = 0.0;
  $report['errors'] = 0;
  $report['donated'] = 0.0;
  $report['deductible'] = 0.0;

//==============================================================================
  $results = get_donor_donations($donor_id,$date_start,$date_end);
//==============================================================================
  foreach ($results as $row) {

    $dgs = get_donation_gifts($row['did']);
    $dg_amount = 0;
    foreach ($dgs as $dg) {
      $dg_amount += $dg['dgamttip'];
      $report['gifts']++;
      $report['donated'] += floatval($dg['dgamt']) + floatval($dg['dgtip']);
    }

//PAYMENT
//****//
    $row['raw_amt'] = get_paid_amt($row['ppid'], $row['praw'], $row['pamt'] + $row['ptip'] - $row['pdis'], $row['pnote']);
    if($row['raw_amt']!=0) { $report['payments']++; $report['deductible'] += floatval($row['raw_amt']); }

//GCUSE
    $gcuses = get_donation_discounts($row['did']);
    $gc_amount = 0;
    foreach ($gcuses as $gcuse) { $gc_amount += $gcuse['tamt']; }
    $diff = -($dg_amount - $row['raw_amt'] + $gc_amount);
    if (abs($diff) >= 0.001) {$report['errors']++;}

  }

//==============================================================================
  $others = get_donor_others($donor_id,$date_start,$date_end);
//==============================================================================
  $xfer_amount = 0;
  foreach ($others as $other) {
    if ($other['ppid'] == NULL)
      $xfer_amount += $other['tamt'];
  }
  if ($xfer_amount != 0) {$report['errors']++;}
  return $report;

}

function td($contents = "", $bg = null, $al = "left", $w = 0, $pr = 10) {
  $td = '<td style="';
  if (!empty($bg))
    $td .= "background:$bg;";
  $td .= "text-align: $al;";
  $td .= "padding-right: {$pr}px;";
  if ($width > 0)
    $td .= "width: {$width}px;";
  $td .= '">';
  $td .= $contents;
  return $td . "</td>";
}

function get_donors() {
  global $wpdb;

  $donors_sql = "SELECT
  IF(dd.ID IS NULL,0, dd.ID) as donorID,
  IF(u.ID IS NULL,0,u.ID) as userID
  FROM donationGiver dd
  LEFT OUTER JOIN wp_users u ON (dd.user_id = u.ID)";

  $donors_only = $wpdb->get_results($wpdb->prepare(
  $donors_sql.
  "WHERE dd.user_id=0
  ORDER BY userID ASC, dd.main DESC, dd.ID ASC"));

  $donors = $wpdb->get_results($wpdb->prepare(
  $donors_sql.
  "WHERE dd.user_id>0 GROUP BY dd.user_id
  ORDER BY userID ASC, dd.main DESC, dd.ID ASC"));

  $donors = array_merge($donors_only,$donors);

  return $donors;
}

function get_donation_gifts($donation_id) {
  global $wpdb;
  $syi_blog = isset($_REQUEST['include_syi']) ? "" : " AND dg.blog_id != 19";
  $results = $wpdb->get_results($sql = 
    "SELECT
      dg.amount as dgamt,
      dg.tip as dgtip,
      dg.amount+dg.tip as dgamttip,
      dg.id AS dgid,
      dg.giftID AS gid,
      dg.matchingDonationAcctTrans as matched,
      dg.event_id as campaign,
      b.domain AS blog_name,
      g.displayName AS g_name

      FROM donation d
      RIGHT OUTER JOIN payment p ON d.paymentID = p.id
      LEFT OUTER JOIN donationGifts dg ON dg.donationID = d.donationID
      LEFT OUTER JOIN gift g ON g.id = dg.giftID
      LEFT OUTER JOIN wp_blogs b on b.blog_id = dg.blog_id

      WHERE d.donationID = ".intval($donation_id). $syi_blog, ARRAY_A);
  return $results;
}

function get_donation_discounts($donation_id) {
  global $wpdb;
  $results = $wpdb->get_results(
    "SELECT
    IF(dat3.amount IS NULL,0,dat3.amount) AS tamt,
    dat3.note AS tnote,
    da3.id AS daid,
    da3.code AS dacode,
    da3.donationAcctTypeId as datype,
    da3.event_id as campaign,
    CONCAT(dg.firstName,' ',dg.lastName) as donorName,
    dat3.note as note

    FROM donation d
    JOIN donationAcctTrans dat3 ON dat3.paymentID=d.paymentID
    LEFT OUTER JOIN donationAcct da3 on dat3.donationAcctId=da3.id
    LEFT JOIN donationGiver dg on dg.id=da3.donorId

    WHERE d.donationID = ".intval($donation_id)."
    ",ARRAY_A);
    // AND tamt <> 0
  return $results;
}

function parse_raw_amt($pay_raw, $amt_str, $amt_str2 = null) {
  $amt = 0;
  $amt_pos = strpos($pay_raw,$amt_str);
  if($amt_pos!==FALSE) {
    $amt = substr($pay_raw,$amt_pos+strlen($amt_str));
    if($amt_str2 != null)
      $amt = parse_raw_amt($amt,$amt_str2);
    else
      $amt = substr($amt,0,strpos($amt,"',"));
  }
  return $amt;
}

function get_paid_amt($provider_id,$pay_raw,$pay_amt, $pnotes){
  $raw_amt = 0;
  if ($provider_id != 5) {
    if (strpos($pnotes, 'Failed') === 0)
      return 0;

    if ($provider_id != 9 && $provider_id != 10) {
      $raw_amt = parse_raw_amt($pay_raw," => 'AMT=");
      if(empty($raw_amt)) {$raw_amt = parse_raw_amt($pay_raw,"=> 'payment_gross=");}
      if(empty($raw_amt)) {$raw_amt = parse_raw_amt($pay_raw,"=> 'mc_gross=");}
      if(empty($raw_amt)) {$raw_amt = parse_raw_amt($pay_raw,"'payment_gross' => '");}
      if(empty($raw_amt)) {
        if($provider_id == 1) {$raw_amt = parse_raw_amt($pay_raw,"'mc_gross' => '");
        } else if($provider_id == 7 || $provider_id == 2) {$raw_amt = parse_raw_amt($pay_raw,"'AMT' => '");
        } else if ($provider_id == 3) {$raw_amt = parse_raw_amt($pay_raw,"'order-total' =>","'VALUE' => '");
          //if(empty($raw_amt)) {$raw_amt = parse_raw_amt($pay_raw,"'VALUE' => '");}
        }
      }
    }
    if(intval($raw_amt)==0 && $pay_amt!=0) $raw_amt = floatval($pay_amt);
  }
  return $raw_amt;
}

function get_donor_donations($donor_id,$date_start,$date_end) {
  global $wpdb;
 
  if ($_REQUEST['is_test'])
    $test = "AND (d.test = 1)";
  else
    $test = "AND (d.test <> 1)";

  $results = $wpdb->get_results($sql = $wpdb->prepare(
  "SELECT
    d.donationDate AS ddate,
    d.donationID AS did,
    d.donorID AS ddid,
    CONCAT(dd.firstName, ' ', dd.lastName,' ') AS ddname,
    dd.email AS ddemail,
    info.donorType,
    d.test AS dtest,

    ROUND(d.donationAmount_Total,2) AS damt,
    ROUND(d.tip,2) AS dtip,
    ROUND((d.donationAmount_Total + d.tip),2) as dtot,

    p.id AS pid,
    p.provider AS ppid,
    p.amount AS pamt,
    p.tip AS ptip,
    p.discount AS pdis,
    p.txnID AS pnum,
    p.raw AS praw,
    p.notes AS pnote

    FROM donation d
    RIGHT OUTER JOIN payment p ON d.paymentID = p.id
    LEFT OUTER JOIN donationGiver dd ON d.donorID = dd.ID
    LEFT JOIN donorInfo info ON info.donorID = dd.ID

    WHERE
    d.donationDate >= '$date_start 00:00:00' AND
    d.donationDate < '$date_end 00:00:00' AND
    d.donorID=%d
    $test

    GROUP BY d.donationID
    ORDER BY dd.lastName, dd.firstName, d.donationDate",
    $donor_id),ARRAY_A);
//

  return $results;
}

function get_donor_others($donor_id,$date_start,$date_end) {
  global $wpdb;

  $others = $wpdb->get_results($sql = $wpdb->prepare(
    "SELECT CONCAT(dg.firstName,' ',dg.lastName) AS ddname,
      dg.email AS ddemail,
      info.donorType,
      dg.id AS ddid,
      dat.id AS datid,
      dat.dateInserted AS ddate,
      dat.amount AS tamt,
      dat.note AS note,
      da.id AS daid,
      da.code AS dacode,
      da.event_id AS campaign,
      da.donationAcctTypeId AS datype,
      IF(p.id IS NULL,0,p.id) AS pid,
      p.provider AS ppid
     FROM donationAcctTrans dat
     LEFT JOIN donationAcct da ON da.id=dat.donationAcctId
     LEFT JOIN donationGiver dg ON da.donorID=dg.id
     LEFT OUTER JOIN payment p ON dat.paymentID = p.id
     LEFT OUTER JOIN donation d ON d.paymentID = p.id
     LEFT JOIN donorInfo info ON info.donorID = dg.ID

     WHERE dat.amount != 0
      AND dat.dateInserted >= '$date_start 00:00:00'
      AND dat.dateInserted < '$date_end 00:00:00'
      AND d.donationID IS NULL 
      AND da.donorID=%d
     ORDER BY dat.dateInserted, dat.id",
    $donor_id), ARRAY_A);

  return $others;
}


?>
