<?
include_once('../wp-load.php');
include_once('../wp-admin/includes/taxonomy.php');
include_once('../wp-includes/wp-db.php');
include_once('../wp-includes/registration.php');
include_once('../wp-admin/includes/user.php');
include_once('../wp-includes/syi/syi-includes.php');
include_once('../database/db-functions.php');

ensure_logged_in_admin();

$donors = $wpdb->get_results("SELECT ID FROM donationGiver ORDER BY lastName, firstName, ID");

////////////////////////////////////////////////////////////////////////////////

global $count, $anomalies;

$count = 0;
$anomalies = 0;
$donor_reports = array();
$error_only = isset($_GET['diff'])?1:0;
$total_only = isset($_GET['total'])?1:0;

?>
<html><head>
<title>Donations Report</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<style type="text/css">
body{ margin:10px; padding:0px; font-family:courier new; width:100%; background:white;}
th,td{ font-size:11px; padding:0 10px;}
</style>
</head><body>
<!--/////////////////////////////////////////////////////////////////////////-->

<form id="report_form" method="GET">
<? if(!$total_only) { ?>
<table>
<tr style="background:black; color: white; ">
<th>donor name</th><th>donor e-mail</th><th>donor#</th><th>type</th>
<th>date</th><th>don#</th><th>pay#</th><th>total</th>
<th>amount</th><th>out</th><th>keep</th><th>card</th>
<th>type</th><th>reference</th><th>charity</th><th>campaign</th>
<th>notes</th></tr>
<? } ?>
<?
  $cutoff = time() + 25;

  $from = $_GET['from'];
  if (empty($from)) $from = '2012-01-01';
  $to = $_GET['to'];
  if (empty($to)) $to = '2014-01-01';

  $donorID = $_GET['donor'];
  if (!empty($donorID)) {
    foreach (as_ints($donorID) as $did) {
      $donor_reports[$did]=sum_donations($from, $to, $did);
      if(!$total_only)
        list_donations($from, $to, $did, $error_only);
    }
    $end = 60000;
  } else {
    $start = intval($_GET['start']);
    $length = intval($_GET['length']);
    if (empty($length)) $length = count($donors) - $start;
    $end = $start + $length;
    for ($i = $start; $i < $end; $i++) {
      $donor_reports[$donors[$i]->ID]=sum_donations($from, $to, $donors[$i]->ID);
      if(!$total_only)
        list_donations($from,$to,$donors[$i]->ID,$error_only);

if (time() > $cutoff) {
?>
  </table>
  <a href="<?= add_query_arg('start', $i) ?>">NEXT PAGE</a>
<? 
  die;
}

    }
  }
?>
<? if(!$total_only) { ?>
</table>
<? } ?>
<br/><br/>
<!--/////////////////////////////////////////////////////////////////////////-->
<?
  if(!$_REQUEST['export'] && is_array($donor_reports)){
?>
<table>
<tr style="background:black; color: white; ">
  <th>donor#</th>
<!--
  <th>name</th><th>email</th>
-->
  <th>gifts</th><th>payments</th><th>errors</th>
  <th>donated</th><th>deductible</th>
</tr>
<?
  ksort($donor_reports);
  foreach ($donor_reports as $k=>$dr) {

    echo '<tr>
    <td style="text-align:left; width:40px; ">'.$k.'</td>
<!--
    <td style="text-align:left; width:300px; ">'.$dr['name'].'</td>
    <td style="text-align:left; width:350px; ">'.$dr['email'].'</td>
-->
    <td style="text-align:left; width:50px; ">'.intval($dr['gifts']).'</td>
    <td style="text-align:left; width:50px; ">'.intval($dr['payments']).'</td>
    <td style="text-align:left; width:50px; ">'.intval($dr['errors']).'</td>
    <td style="text-align:left; width:60px; ">'.as_money($dr['donated']).'</td>
    <td style="text-align:left; width:60px; ">'.as_money($dr['deductible']).'</td>
    </tr>';

  }
?>
</table>
<!--/////////////////////////////////////////////////////////////////////////-->
<!--<div><?= $count ?>, <?= $anomalies ?></div>-->
<?
  }
  if ($end < count($donors)) {
    $url = add_query_arg('start', $i);
    ?><a href="<?=$url?>">next page...</a><?
  }
?>
</form>

<script type="text/javascript">
$(function() {
  $(".fix-dat").click(function() {
    var href = $(this).attr('href');
    var ser = $("form").serialize().replace('%5B%5D','[]');
    $(this).attr('href', href + "&" + ser);
    $("form :checked").attr('checked',false).closest('tr').css('opacity', 0.2);
    return true;
  });
});
</script>
</body>
</html>
<?

function list_donations($date_start,$date_end,$donor_id,$error_only=0,$run_only=0) {
  global $wpdb,$anomalies,$count;
  global $PAYMENT_METHODS; //now in donation-acct.php

  $echo = '';
  $echo_err = '';
  $echo_total = '';

  $ACCOUNT_TYPES_OUT = array( 'unknown fund', 'INTERNAL', 'FROM FUND', 'SPEND GC', 'MATCH', 'DISCOUNT', 'SPEND GC', 'ALLOCATED' );
  $ACCOUNT_TYPES_IN = array( 'unknown deposit', 'DEPOSIT', 'DEPOSIT', 'BUY GC', 'FOR MATCHING', 'FOR DISCOUNT', 'BUY GC', 'TO ALLOCATE' );

  $balances_in = $wpdb->get_results($sql = $wpdb->prepare(
   "SELECT da.code,SUM(dat.amount) AS amount,
     CONCAT(dg.firstName,' ',dg.lastName) as ddname,
      dg.email as ddemail,
      da.event_id as campaign,
      dg.id as ddid
    FROM donationAcctTrans dat
    LEFT JOIN donationAcct da on da.id=dat.donationAcctId
    LEFT JOIN donationGiver dg on dg.id=da.donorId
    WHERE da.donorId=%d
     AND dat.dateInserted < '$date_start 00:00:00'
     AND dat.amount != 0
    GROUP BY dat.donationAcctId", $donor_id), ARRAY_A);

  $balances_out = $wpdb->get_results($sql = $wpdb->prepare(
   "SELECT da.code,da.ID,da.balance AS current,SUM(dat.amount) AS amount,
     CONCAT(dg.firstName,' ',dg.lastName) as ddname,
      dg.email as ddemail,
      da.event_id as campaign,
      dg.id as ddid
    FROM donationAcctTrans dat
    LEFT JOIN donationAcct da on da.id=dat.donationAcctId
    LEFT JOIN donationGiver dg on dg.id=da.donorId
    WHERE da.donorId=%d
     AND dat.dateInserted < '$date_end 00:00:00'
     AND dat.amount != 0
    GROUP BY dat.donationAcctId", $donor_id), ARRAY_A);

  foreach ($balances_in as $balance) {
    $i = 0;
    for ($i = 0; $i < count($balances_out); $i++) {
      $bal2 = $balances_out[$i];
      if ($bal2['code'] == $balance['code'] 
       && $bal2['amount'] == $balance['amount']) {
        $balances_out[$i]['amount'] = 0;
        $balance['amount'] = 0;
      }
    }
    if ($balance['amount'] == 0) continue;

    $bg = '#aaf';

    $echo .= '<tr style="text-align:right; padding:0 20px;">';
    $echo .= td(stripslashes($balance['ddname']));
    $echo .= td($balance['ddemail']);
    $echo .= td('#'.$balance['ddid'], null, 'right');
    $echo .= td();
    $echo .= td();
    $echo .= td();
    $echo .= td();
    $echo .= td();
    $echo .= td("",$bg);
    $echo .= td("",$bg);
    $echo .= td("", $bg);
    $echo .= td(as_money($balance['amount']), $bg, 'right');
    $echo .= td("BALANCE IN", $bg, 'right');
    $echo .= td($balance['code'].' ('.stripslashes($balance['ddname']).')',$bg);
    $echo .= td("",$bg);
    $echo .= td("",$bg);
    $echo .= td("",$bg);
    $echo .= '</tr>';
  }

//==============================================================================
  $results = get_donor_donations($donor_id,$date_start,$date_end);
//==============================================================================

////////////////////////////////////////////////////////////////////////////////
//START DONATIONS LOOP

  foreach ($results as $row) {
    //if ($row['dtest'] == 1) continue;
    $count++;

    $row['raw_amt'] = get_paid_amt($row['ppid'], $row['praw'], $row['pamt'] + $row['ptip'] - $row['pdis'], $row['pnote']);

    $echo2 = '';
    $echo2 .= '<td style="text-align:left; width:300px; overflow:hidden;">'.stripslashes($row['ddname']).'</td>';
    $echo2 .= '<td style="text-align:left; width:350px; overflow:hidden;">'.$row['ddemail'].'</td>';
    $echo2 .= '<td style="text-align:right; width:40px;">#'.$row['ddid'].'</td>';
    $echo2 .= '<td style="text-align:right; width:40px;">'.$row['donorType'].'</td>';
    $echo2 .= '<td style="text-align:right; width:200px;">'.date('m/d/y H:i:s',strtotime($row['ddate'])).'</td>';
    $echo2 .= '<td style="text-align:right; width:40px;">#'.$row['did'].'</td>';
    $echo2 .= '<td style="text-align:right; width:40px;">#'.$row['pid'].'</td>';
    $echo2 .= '<td style="text-align:right; width:60px;">'.as_money(floatval($row['dtot'])).'</td>';


////////////////////////////////////////////////////////////////////////////////

    $dgs = get_donation_gifts($row['did']);
    $dg_amount = 0;

    foreach ($dgs as $dg) {
      if ($dg['dgamt'] == 0) continue;
      $dg_amount += $dg['dgamttip'];
      $echo .= '<tr style=" text-align:right; padding:0 20px;">';
      $echo .= $echo2;
      $echo .= '<td style="text-align:right; width:60px;">'.as_money(floatval($dg['dgamttip'])).'</td>';
      //amt
      $echo .= '<td style="text-align:right; width:60px;">'.as_money(floatval($dg['dgamt'])).'</td>';
      $echo .= '<td style="text-align:right; width:60px;">'.as_money(floatval($dg['dgtip'])).'</td>';
      $echo .= '<td style="text-align:right; width:60px;"></td>';
      //type
      $echo .= '<td style="text-align:right; padding-right:10px;" nowrap="">';
      if ($row['dtest'] == 1) $echo .= ' <b style="color:red;">TEST</b> ';
      $echo .= ($dg['matched'] > 0?'M.':'').'GIFT</td>';
      $echo .= '<td style="text-align:left;" nowrap="">'.stripslashes($dg['g_name']) . ' [' . $dg['gid'] . ']';
      $echo .= '</td>';
      $echo .= '<td style="text-align:left;">';
      $n = explode('.', $dg['blog_name']);
      $echo .=  $n[0];
      $echo .= '</td>';
      $echo .= "<td>";
      if ($dg['campaign'] > 0)
        $echo .= $dg['campaign'];
      $echo .= "</td>";
      //ref
      $echo .= '<td style="text-align:left; width:160px;">';
      $echo .= 'GIFT# '.$dg['dgid'];
      $echo .= '</td>';
      $echo .= '</tr>';

    }

////////////////////////////////////////////////////////////////////////////////
//PAYMENT

    if ($row['ppid'] !== NULL && $row['ppid'] != 5 && !($row['ppid'] == 10 && $row['raw_amt'] == 0)) {
      $bgcolor = 'background: #8f8;';
      $echo .= '<tr style=" text-align:right; padding:0 20px;">';
      $echo .= $echo2;
      // $echo .=  as_money(floatval($row['raw_amt'])<0?floatval($row['raw_amt']):floatval($row['raw_amt'])*-1.00);
      //amt
      $echo .= '<td style="text-align:right; width:70px;'.$bgcolor.'">'.as_money(floatval($row['raw_amt'])*-1.00).'</td>';
      $echo .= '<td style="text-align:right; width:70px;'.$bgcolor.'"></td>';
      $echo .= '<td style="text-align:right; width:70px;'.$bgcolor.'"></td>';
      $echo .= '<td style="text-align:right; width:70px;'.$bgcolor.'"></td>';
      //type
      $echo .= '<td style="text-align:right; width:50px;'.$bgcolor.'">';
      $echo .=  $PAYMENT_METHODS[$row['ppid']].(floatval($row['raw_amt'])<0?' REFUND':'');
      $echo .= '</td>';
      //ref
      $echo .= '<td style="text-align:left; width:160px;'.$bgcolor.'">';
      $echo .=  $row['pnum'] == NULL ? "no ref#" : "#" . $row['pnum'];
      $echo .= '</td>';
      $echo .= '<td style="text-align:left; width:160px;'.$bgcolor.'"></td>';
      $echo .= '<td style="text-align:left; width:160px;'.$bgcolor.'"></td>';
      $echo .= '<td style="text-align:left; width:160px;'.$bgcolor.'">PMT# ' . $row['pid'].'</td>';
      $echo .= '</tr>';

    }

////////////////////////////////////////////////////////////////////////////////
//GCUSE

    $gcuses = get_donation_discounts($row['did']);

    $gc_amount = 0;
    foreach ($gcuses as $gcuse) {
      $total = $gcuse['tamt'];
      $tip = '';
      if ($row['did'] > 8600 && $gcuse['datype'] == 6 && $gcuse['tamt'] > 0) { // BUY_GC
        $tip = ($gcuse['tamt'] / $row['damt']) * $row['ptip'];
        $dg_amount += $tip;
        $total += $tip;
        $tip = as_money($tip);
      }
      if ($gcuse['tamt'] == 0) continue;
      if ($gcuse['tamt'] > 0) $bgcolor = 'background: #ee8;';
      else $bgcolor = 'background: #cc0;';
      $echo .= '<tr style=" text-align:right; padding:0 20px;">';
      $echo .= $echo2;
      //amt
      $echo .= '<td style="text-align:right; width:70px;'.$bgcolor.'">'.as_money(floatval($total)).'</td>';
      $echo .= '<td style="text-align:right; width:70px;'.$bgcolor.'"></td>';
      $echo .= '<td style="text-align:right; width:70px;'.$bgcolor.'">' . $tip . '</td>';
      $echo .= '<td style="text-align:right; width:70px;'.$bgcolor.'">'.as_money(floatval($gcuse['tamt'])).'</td>';
      //type
      $echo .= '<td style="text-align:right; width:50px;'.$bgcolor.'">';
      if ($gcuse['tamt'] > 0) $echo .=  $ACCOUNT_TYPES_IN[$gcuse['datype']];
      else $echo .=  $ACCOUNT_TYPES_OUT[$gcuse['datype']];
      $echo .= '</td>';
      //ref
      $echo .= '<td style="text-align:left; width:160px;'.$bgcolor.'">'.$gcuse['dacode'] . ' (' . $gcuse['donorName'] . ')'.'</td>';
      $echo .= '<td style="text-align:left; width:160px;'.$bgcolor.'"></td>';
      $echo .= '<td style="width:160px;'.$bgcolor.'">';
      if ($gcuse['campaign'] > 0)
        $echo .= $gcuse['campaign'];
      $echo .= '</td>';
      $echo .= '<td style="text-align:left; width:160px;'.$bgcolor.'">ACCT# ' . $gcuse['daid'].' '.stripslashes($gcuse['note']).'</td>';
      $echo .= '</tr>';

      $gc_amount += $gcuse['tamt'];
    }

////////////////////////////////////////////////////////////////////////////////

    $diff = -($dg_amount - $row['raw_amt'] + $gc_amount);
    if(abs($diff) >= 0.001) {
      $anomalies++;
      $bgcolor = 'background:#f60;';
      $err = '<tr style=" text-align:right; padding:0 20px;">';
      $err .= $echo2;
      //amt
      $err .= '<td style="text-align:right; width:70px;'.$bgcolor.'">'.as_money(floatval($diff)).'</td>';
      $err .= '<td style="text-align:right; width:70px;'.$bgcolor.'"></td>';
      $err .= '<td style="text-align:right; width:70px;'.$bgcolor.'">'.as_money(floatval($diff)).'</td>';
      //type
      $err .= '<td style="text-align:right; width:70px;'.$bgcolor.'"></td>';
      $err .= '<td style="text-align:right; width:50px;'.$bgcolor.'">ERROR</td>';
      $err .= '<td style="text-align:right; width:50px;'.$bgcolor.'"></td>';
      $err .= '<td style="text-align:right; width:50px;'.$bgcolor.'"></td>';
      $err .= '<td style="text-align:right; width:50px;'.$bgcolor.'"></td>';
      //ref
      $err .= '</tr>';

      $echo .= $err;
      $echo_err .= $err;

    }
  }

//END DONATIONS LOOP
////////////////////////////////////////////////////////////////////////////////

//==============================================================================
  $others = get_donor_others($donor_id,$date_start,$date_end);
//==============================================================================

  $xfer_amount = 0;

////////////////////////////////////////////////////////////////////////////////
//START OTHERS LOOP

  foreach ($others as $other) {
    $echo .= '<tr style=" text-align:right; padding:0 20px;">';
    $echo .= td(stripslashes($other['ddname']));
    $echo .= td($other['ddemail']);
    $echo .= td('#'.$other['ddid'], null, 'right');
    $echo .= td($other['donorType'], null, 'right');
    $when = strtotime($other['ddate']);
    $echo .= td(date('m/d/y H:i:s',$when), null, 'right');
    $echo .= td();
    if ($other['pid'] == 0) {
      $xfer_amount += $other['tamt'];

      if ($other['tamt'] >= 0) {
        $amt =  $ACCOUNT_TYPES_IN[$other['datype']];
        $bg = '#ee8';
      } else {
        $amt =  $ACCOUNT_TYPES_OUT[$other['datype']];
        $bg = '#cc0';
      }

      $amount = $other['tamt'];
      $dat = $other['datid'];
      $when = date( 'Y-m-d H:i:s', $when );
      $echo .= td($_REQUEST['export'] ? '<span style="color:red;">???</span>' : "<a class='fix-dat' target=\"_new\" href=\"fix_dat.php?dat[]=$dat&donor=$donor_id&amount=$amount&when=$when\">fix</a>");
      $echo .= td();
      $echo .= td(as_money(floatval($other['tamt'])), $bg, 'right');
      $echo .= td("", $bg);
      $echo .= td("", $bg);
      $echo .= td(as_money(floatval($other['tamt'])), $bg, 'right');
      $echo .= td($amt, $bg, 'right');
    } else {
      $amt = $PAYMENT_METHODS[$other['ppid']].(floatval($other['raw_amt'])<0?' REFUND':'');
      $bg = '#ee8';
 
      $echo .= td("#".$other['pid']);
      $echo .= td();
      $echo .= td("", $bg);
      $echo .= td("", $bg);
      $echo .= td("", $bg);
      $echo .= td(as_money(floatval($other['tamt'])), $bg, 'right');
      $echo .= td($amt, $bg, 'right');
    }
    $echo .= td($other['dacode'] . ' (' . $other['ddname'] . ')', $bg);
    $echo .= td("",$bg);
    $echo .= td($other['campaign'] > 0 ? $other['campaign'] : "",$bg);
    $echo .= td((($_REQUEST['export'] || $other['pid'] > 0) ? '' : "<input type='checkbox' name='dat[]' value='" . $other['datid']. "'/>") . " DAT#" . $other['datid'] . " ACCT# " . $other['daid'].(intval($other['pid'])>0?' PMT# '.$other['pid']:'').' '.stripslashes($other['note']), $bg);
    $echo .= '</tr>';
    
  }

//END OTHERS LOOP
////////////////////////////////////////////////////////////////////////////////


  foreach ($balances_out as $balance) {
    if ($balance['amount'] == 0) continue;

    $bg = '#aaf';

    $echo .= '<tr style=" text-align:right; padding:0 20px;">';
    $echo .= td(stripslashes($balance['ddname']));
    $echo .= td($balance['ddemail']);
    $echo .= td('#'.$balance['ddid'], null, 'right');
    $echo .= td();
    $echo .= td();
    $echo .= td();
    $echo .= td();
    $echo .= td();
    $echo .= td("", $bg);
    $echo .= td("", $bg);
    $echo .= td("", $bg);
    $echo .= td(as_money(-$balance['amount']), $bg, 'right');
    $echo .= td("BALANCE OUT", $bg, 'right');
    $echo .= td($balance['code'].' ('.stripslashes($balance['ddname']).')',$bg);
    $echo .= td("",$bg);
    $echo .= td($balance['campaign'] > 0 ? $balance['campaign'] : "",$bg);
    $echo .= td('ACCT# '. $balance['ID'] . ' now ' . as_money($balance['current']),$bg);
    $echo .= '</tr>';
    
  }

  if ($xfer_amount != 0) {
    $bg = '#f60';
    $anomalies++;

    // error correction
    $xfer_amount = -$xfer_amount;

    $err = '<tr style=" text-align:right; padding:0 20px;">';
    $err .= td(stripslashes($other['ddname']));
    $err .= td($other['ddemail']);
    $err .= td('#'.$other['ddid'], null, 'right');
    $err .= td();
    $err .= td();
    $err .= td();
    $err .= td();
    $err .= td();
    $err .= td(as_money(floatval($xfer_amount)), $bg, 'right');
    $err .= td("", $bg);
    $err .= td(as_money(floatval($xfer_amount)), $bg, 'right');
    $err .= td("", $bg);
    $err .= td("XFER?", $bg, 'right');
    $err .= td("",$bg);
    $err .= td("",$bg);
    $err .= td($other['campaign'] > 0 ? $other['campaign'] : "",$bg);
    $err .= td("",$bg);
    $err .= '</tr>';

    $echo .= $err;
    $echo_err .= $err;

  }

  if(!$run_only)
  if($error_only) echo $echo_err;
  else echo $echo;

}

////////////////////////////////////////////////////////////////////////////////

