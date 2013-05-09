<?
include_once('../wp-load.php');
include_once('../wp-admin/includes/taxonomy.php');
include_once('../wp-includes/wp-db.php');
include_once('../wp-includes/registration.php');
include_once('../wp-admin/includes/user.php');
include_once('../wp-includes/syi/syi-includes.php');
include_once('../database/db-functions.php');

ensure_logged_in_admin();

function fix_transaction($transID=null) {
  global $wpdb;

//$wpdb->prepare("dat.id=%d",$transID);

  $sql =
  //$wpdb->prepare(
"
SELECT dat.id as id, ROUND(amount,2) as amt, dat.note, dat.dateInserted,
da.id as daid, da.donorId, dat.paymentID,
dd.firstName, dd.lastName
FROM donationAcctTrans dat
LEFT JOIN donationAcct da ON dat.donationAcctID = da.id
LEFT JOIN donationGiver dd ON da.donorId = dd.ID
WHERE
dat.note NOT LIKE '%Initial account balance ' AND
dat.note NOT LIKE '%Donation #%' AND
dat.note NOT LIKE '%payment #%' AND
dat.note NOT LIKE '%auto-donation via Recurly%' AND
dat.note NOT LIKE '%test%' AND
dat.note NOT LIKE '%testing%' AND
dat.note NOT LIKE '%from account%'
AND dat.amount>0

AND dat.dateInserted < '2011-01-01 00:00:00'

ORDER BY dat.dateInserted, dat.note

"
  //)
  ;
/*

*/

  $ts = $wpdb->get_results($sql, ARRAY_A);

  //echo $sql;

  echo '<pre style="font-size:11px;">';
  echo "global \$wpdb;";
  
  foreach($ts as $t) {

    $payment_exists = false;
    //echo'<pre>'.print_r($trans,true).'</pre>';

    if(intval($t['paymentID'])>100 && intval($t['paymentID'])<10000) {
        $payment_exists = true;

      $p = $wpdb->get_row($wpdb->prepare(
          "SELECT * FROM payment p WHERE p.id=%d",$t[paymentID]),ARRAY_A);
      //if($p!=null)
      //if (strtotime($p['dateTime']) == strtotime($t['dateInserted'])) {
      //}
      
    }

//    echo "\n\n".'//'.$t['dateInserted'].' da#'.$t[daid].' t#'.$t[id];
//    echo ' $'.$t[amt];
//    echo ' '.$t[firstName].' '.$t[lastName];
//    if(!$payment_exists) { echo ' _blank_'; }
//    else { echo ' p#'.$t['paymentID'].' '; }

    if(strpos($t[note],'auto-donation via Recurly') !== FALSE) {

      echo ' recurly';

if(!$payment_exists) {

echo "\n".$wpdb->prepare("\$wpdb->query(\"INSERT INTO payment (dateTime,amount,provider,txnID) VALUES (%s,%f,9,%s)\");",$t[dateInserted],$t[amt],'RECURLY-DAT#'.$t[id]);
echo "\n\$id=\$wpdb->insert_id;";
//echo "\n---";
echo "\n".$wpdb->prepare("\$wpdb->query(\$wpdb->prepare(\"INSERT INTO donation (donationDate, donationAmount_Total, donorID, paymentID) VALUES (%s,%f,%d,%%d)\",\$id));",$t[dateInserted],$t[amt],$t[donorId]);
echo "\n".$wpdb->prepare("\$wpdb->query(\$wpdb->prepare(\"UPDATE donationAcctTrans SET paymentID=%%d WHERE id=%d\",\$id));",$t[id]);
echo "\n";

}

//    } else if(strpos($t[note],'Initial account balance') !== FALSE) {
//      echo ' purchase';

    } else if($t[amount] < 0 && strpos($t[note],'payment #') !== FALSE) {
      echo ' use on p#';

    } else if($t[amount] < 0 && strpos($t[note],'Donation #') !== FALSE) {
      echo ' use on d#';
    
    } else {
    
//      echo ' ________ ';

if(!$payment_exists

&& (

$t[firstName].' '.$t[lastName] != 'Benjamin Jenson' &&
$t[firstName].' '.$t[lastName] != 'Yosia Urip' &&
$t[firstName].' '.$t[lastName] != 'Steve Eisner' &&
$t[firstName].' '.$t[lastName] != 'Chuck Johnston'
)


) {

    echo "\n\n".'//'.$t['dateInserted'].' da#'.$t[daid].' t#'.$t[id];
    echo ' $'.$t[amt];
    echo ' '.$t[firstName].' '.$t[lastName];
    echo ' p#'.intval($t['paymentID']).' ';

//    if(!$payment_exists) { echo ' _blank_'; }
//    else { echo ' p#'.$t['paymentID'].' '; }

echo "\n".$wpdb->prepare("\$wpdb->query(\"INSERT INTO payment (dateTime,amount,provider,txnID) VALUES (%s,%f,11,%s)\");",$t[dateInserted],$t[amt],'#'.$t[id]);
echo "\n\$id=\$wpdb->insert_id;";
echo "\n---";
echo "\n".$wpdb->prepare("\$wpdb->query(\$wpdb->prepare(\"INSERT INTO donation (donationDate, donationAmount_Total, donorID, paymentID) VALUES (%s,%f,%d,%%d)\",\$id));",$t[dateInserted],$t[amt],$t[donorId]);
echo "\n".$wpdb->prepare("\$wpdb->query(\$wpdb->prepare(\"UPDATE donationAcctTrans SET paymentID=%%d WHERE id=%d\",\$id));",$t[id]);
echo "\n";

}


    }



//    echo "".str_replace("\r\n"," "," ".$t[note]);
    echo "";
  }
  
  echo '</pre>';
}
//intval($_GET['t'])
//fix_transaction();

?>
