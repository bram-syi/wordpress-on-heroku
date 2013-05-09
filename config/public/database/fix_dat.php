<?
require_once( dirname(__FILE__) . '/../wp-load.php' );
global $wpdb,$preview;

ensure_logged_in_admin();

$donorID = trim($_REQUEST['donor']);
$datID = array_unique(as_ints($_REQUEST['dat']));
$amount = trim($_REQUEST['amount']);
$when = trim($_REQUEST['when']);

$provider = trim($_REQUEST['provider']);
$ref = trim($_REQUEST['ref']);

function xif($name, $value, $node = 'input', $type = 'text') {
  ?><<?=$node?> id="<?=$name?>" type="<?=$type?>" name="<?=esc_attr($name)?>" value="<?=esc_attr($value)?>" ><?
}
function hidd($name, $value) {
  xif($name, $value, 'input', 'hidden');
}

if (empty($datID)) { 
  ?>
  <form method="get" action="fix_dat.php">
    <? xif("dat", implode(",", $datID)); ?>
    <input type="submit" value="go" />
  </form>
  <? 
} else if (empty($donorID) || empty($provider) || ($provider != 10 && (empty($ref) || empty($amount)))) {
  ?>
  <style>.yes-xfer { display: none; }</style>
  <form method="post" action="fix_dat.php">
    Adjust donor <? xif("donor", $donorID); ?> 
    <? xif("provider", $provider, "select"); ?>
      <option value="11">Cash/Check</option>
      <option value="9">Recurly</option>
      <option value="10">XFER</option>
    </select>
    on <? xif("when", $when); ?> 
    <span class="no-xfer">
    $<? xif("amount", $amount); ?> 
    REF #<? xif("ref", $ref); ?> 
    </span>
    <span class="yes-xfer">
    DATs:
    <? xif("dat", implode(",", $datID)); ?>
    </span>
    <input type="submit" value="go" />
  </form>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
  <script type="text/javascript">
  $(function() {
    $("#provider").change(function() {
      if ($(this).val() == 10) {
        $(".no-xfer").hide();
        $(".yes-xfer").show();
      } else {
        $(".no-xfer").show();
        $(".yes-xfer").hide();
      }
    });
  });
  </script>
  <? 
} else if ($_POST) {
  $when = strtotime($when);
  if (empty($when))
    $when = time();

  // XFERs net out to zero.
  if ($provider == 10) {
    $ref = "XFER";
    $amount = 0;
  }

  $pid = create_donation_payment($donorID, $amount, $provider, $ref, $when);
  foreach ($datID as $did) {
    $wpdb->update('donationAcctTrans',
      array('paymentID' => $pid),
      array('id' => $did));
  }

  ?><div>Done (Payment=<?=$pid?>)</div><?
} else {
  wp_die('Submit POST please');
}

