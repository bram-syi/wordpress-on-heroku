<?
/*
Plugin Name: Donation Account
Plugin URI: http://seeyourimpact.org/
Description: Donation account capability for user to "store" some money to be given as an Impact Card
Author: Yosia Urip
Version: 1.0

Author URI: http://seeyourimpact.org/
*/

include_once(ABSPATH.'wp-includes/syi/syi-includes.php');
include_once(ABSPATH.'wp-includes/registration.php');

define('DONATION_ACCT_KEY','213FKSEH34012');
define('ACCT_TYPE_INTERNAL', 1);
define('ACCT_TYPE_GENERAL', 2);
define('ACCT_TYPE_GIFT', 3);
define('ACCT_TYPE_MATCHING', 4);
define('ACCT_TYPE_DISCOUNT', 5);
define('ACCT_TYPE_OPEN_CODE', 6);
define('ACCT_TYPE_GIVE_ANY', 7);

$PAYMENT_METHODS = array( 
  'unknown', // 0
  'PAYPAL',  // 1
  'CC', // 2
  'GOOGLE', // 3
  'AMAZON', // 4
  'GC', // 5
  'MATCHING', // 6
  'PAYPAL', // 7
  'SP', // 8
  'RECURLY', // 9
  'XFER', // 10
  'CASH/CHECK' // 11
);

define('DONATION_ACCT_SLUG', 'donation-acct');

add_action('admin_menu', 'add_donation_acct_menu', 101);
function add_donation_acct_menu(){
  global $blog_id;

  if ($blog_id != 1)
    return;

  add_submenu_page('index.php', __('Manage Accounts', 'donation-acct'), 
    __('Manage Accounts', 'donation-acct'), 'manage_network', 
    DONATION_ACCT_SLUG, 'show_donation_acct_page');
}

function get_acct_params($obj) {
  $obj = json_decode($obj);
  if ($obj == NULL)
    $obj = new stdClass;
  return $obj;
}

//Main function to display response/process request
function show_donation_acct_page(){
  $id = intval($_GET['accountID']);
  
?>
  <script type="text/javascript" src="<?=get_site_url(1,'/wp-content/themes/syi/jquery.tablesorter.min.js') ?>"></script>
  <style type="text/css">
  .field_title{float:left; width:150px;}
  .row_cell{padding: 1px 5px;}
  .row_cell .debug { color: #aaa; }
  #allocate-form .acct-type { display: none; }
  .subtab table th { cursor: pointer; }
  .row{font-size:11px; border-top:1px solid #888;}
  .odd{background:#eee;}
  .errorMsg{font-weight:bold; color:#c00; padding: 20px; font-size: 15px;}
  .tbl_header{background: #333; color: #ccc}
  .header_row{font-weight:bold;text-align:left;}
  .subtabs { border-bottom: 1px solid black; margin-top: 20px; }
  a.subtab { background: #f0f0f0; display: block; width: 120px; float:left; padding: 3px; border: 1px solid white; text-align: center; margin: 2px 5px; word-break: none; white-space: nowrap; }
  form.subtab { clear: both; display: none; padding: 15px; }
  form.selectedTab { display: block; }
  a.selectedTab { font: bold 12pt Arial; border-bottom: 0px none; text-decoration: none; color:black; margin-bottom: -2px; padding-bottom: 6px;  background-color: white; border:1px solid black; border-bottom: 1px solid white; width: 160px;}
  .clear { clear: both; }
  .loader { padding: 20px 0 20px 35px; font-size: 20px; color: #888; background: url(/wp-content/themes/syi/images/loading.gif) no-repeat 0 10px; }
</style><?

  if ($id > 0) 
    $selected_tab = show_donation_acct_item($id);
  else
    $selected_tab = show_donation_acct_list();

?><script type="text/javascript">
jQuery(function($) {
  function select(tab) {
    $(".subtab").removeClass("selectedTab");
    id = $(tab).addClass("selectedTab").attr('id');
    var form = $("#" + id + "-form");
    if (form.length > 0)
      form.addClass('selectedTab');
    else {
      var e = $('<div class="loader selectedTab">Loading...</div>').appendTo('#account-tabs');
      $.post(ajaxurl, { action: 'show_accounts', tab: id }, function(response) {
        $(response).replaceAll(e).addClass('selectedTab').find('table').tablesorter();
      });
    }
  }

  $("a.subtab").click(function() {
    select(this);
    return false;
  });

  $("select.payment_type").live('change', function() {
    var pa = jQuery(this).siblings('.payment_account');
    if (jQuery(this).val() == 0)
      pa.show();
    else
      pa.hide();
  });

  select("<?= $selected_tab ?>");

  if ($.fn.tablesorter)
    $("form.subtab table").tablesorter();
});
</script><?
}

//Delete test accounts
function delete_donation_acct_test(){
  wp_die('NOT ALLOWED. SORRY, ASK STEVE');  // Steve: whoa, this function had a big bug. disabling.
}

//Delete particular donationAcct
function delete_donation_acct($accountID){
  global $wpdb;
  $wpdb->query("DELETE FROM donationAcct WHERE id='" . $accountID . "'"); 
  delete_donation_acct_trans($accountID, true);
  // delete_donation_promo($accountID, true);
}

function donation_admin_show_accounts() {
  $id = $_REQUEST['tab'];

  show_accounts_tab($id);
}
add_action('wp_ajax_show_accounts', 'donation_admin_show_accounts');

function show_accounts_tab($tabid) {
  global $wpdb;
  
  switch ($tabid) {
    case 'allocate':
      $query = 
        "SELECT da.*, dat.name as type,
          dg.ID as donorID, dg.email, CONCAT(dg.firstName,' ',dg.lastName) AS name
         FROM donationAcct AS da
         LEFT JOIN donationGiver AS dg ON dg.id = da.donorId
         LEFT JOIN donationGiver AS dg2 ON dg2.id = da.owner
         LEFT JOIN donationAcctType AS dat ON da.donationAcctTypeId = dat.id
         WHERE da.donationAcctTypeId = 7 AND (da.expired != 1) AND (balance != 0)
         ORDER BY name, email, balance";
      break;
    case 'allocate-empty':
      $query = 
        "SELECT da.*, dat.name as type,
          dg.ID as donorID, dg.email, CONCAT(dg.firstName,' ',dg.lastName) AS name
         FROM donationAcct AS da
         LEFT JOIN donationGiver AS dg ON dg.id = da.donorId
         LEFT JOIN donationGiver AS dg2 ON dg2.id = da.owner
         LEFT JOIN donationAcctType AS dat ON da.donationAcctTypeId = dat.id
         WHERE da.donationAcctTypeId = 7 AND (da.expired != 1) AND (balance = 0)
         ORDER BY name, email, balance
         LIMIT 5000";
      break;
    case 'accounts':
      $query = 
        "SELECT da.*, dat.name as type, 
          dg.ID as donorID, dg.email, CONCAT(dg.firstName,' ',dg.lastName) AS name
         FROM donationAcct AS da 
         LEFT JOIN donationGiver AS dg ON dg.id = da.donorId
         LEFT JOIN donationGiver AS dg2 ON dg2.id = da.owner
         LEFT JOIN donationAcctType AS dat ON da.donationAcctTypeId = dat.id
         WHERE balance >= 0.01 AND da.donationAcctTypeId != 7 AND da.expired != 1
         ORDER BY donationAcctTypeId, name, email, balance";
      break;
    case 'empties-past':
      $query = 
        "SELECT da.*, dat.name as type,
          dg.email, CONCAT(dg.firstName,' ',dg.lastName) AS name
         FROM donationAcct AS da 
         LEFT JOIN donationGiver AS dg ON dg.id = da.donorId
         LEFT JOIN donationAcctType AS dat ON da.donationAcctTypeId = dat.id
         WHERE da.expired != 1 AND
           (balance <= 0.01  AND da.donationAcctTypeId != 7)
           AND da.dateUpdated < '2012-01-01'
         ORDER BY donationAcctTypeId, name, email, balance";
      break;
    case 'empties':
      $query = 
        "SELECT da.*, dat.name as type,
          dg.email, CONCAT(dg.firstName,' ',dg.lastName) AS name
         FROM donationAcct AS da 
         LEFT JOIN donationGiver AS dg ON dg.id = da.donorId
         LEFT JOIN donationAcctType AS dat ON da.donationAcctTypeId = dat.id
         WHERE da.expired != 1 AND
           (balance <= 0.01  AND da.donationAcctTypeId != 7)
           AND da.dateUpdated >= '2012-01-01'
         ORDER BY donationAcctTypeId, name, email, balance";
      break;
    case 'expired':
      $query = 
        "SELECT da.*, dat.name as type,
          dg.email, CONCAT(dg.firstName,' ',dg.lastName) AS name
         FROM donationAcct AS da
         LEFT JOIN donationGiver AS dg ON dg.id = da.donorId
         LEFT JOIN donationAcctType AS dat ON da.donationAcctTypeId = dat.id
         WHERE da.expired = 1
         ORDER BY donationAcctTypeId, name, email, balance";
      break;

  }

  ?>
    <form id="<?=$tabid?>-form" class="subtab" action="" method="post">
    <? 
      if (!empty($query))
        show_accounts($query);
      else
        echo 'No accounts';
    ?>
    </form>
  <?
}

function show_accounts($query) {
  global $wpdb;

  $this_url = "/wp-admin/admin.php?page=donation-acct";
  $rows = $wpdb->get_results($query,ARRAY_A); 
  //For each db row, create a table row, with a link to individual item  
  $i = 0;
  if (!is_array($rows) || count($rows) == 0) {
    echo "No accounts.";
  } else {
?>
    <table>
      <thead>
        <tr class="row header_row">
          <th class="row_cell">#</td>
          <th class="row_cell">Balance</td>
          <th class="row_cell">Name</td>
          
          <th class="row_cell">Code</td>
          <th class="row_cell">Created</td>
          <th class="row_cell">Used</td>
          <th class="row_cell">Settings</td>
        </tr>
      </thead>
<?

    $total = 0;
    foreach($rows as $row) { 

?>
      <tr class="row<? if ($i % 2 == 1) echo ' odd' ?>">
        <td class="row_cell" nowrap=""><a href="<?= add_query_arg('accountID', $row['id'], $this_url) ?>" target="_new"><?= $row['id'] ?></a> <span class="acct-type debug"><?= $row['type'] ?></span></td>
        <td class="row_cell" style="text-align:right;"><?= as_money($row['balance']) ?></td>
          <td class="row_cell">
<?
  $type = intval($row['donationAcctTypeId']);
  if ($type == 3) // gift cert
    echo 'to ';
  else if ($type == 6) // open code
    echo 'from ';

  $dc = explode(' ',$row['dateCreated']);
  $du = explode(' ',$row['dateUpdated']);

  echo "<b>{$row['name']}</b> ({$row['email']}) <span class=\"debug\">#{$row['donorID']}</span>";
?>
        </td>
          <td class="row_cell" nowrap=""><?= $row['code'] ?></td>
          <td class="row_cell" nowrap=""><?= $dc[0] ?></td>
          <td class="row_cell" nowrap=""><?= $du[0] != $dc[0] ? $du[0] : "" ?></td>
          <td class="row_cell" nowrap=""><?= htmlspecialchars(show_acct_params($row['params'])) ?></td>
      </tr>
<?
      $i++;
      $total += $row['balance'];
    }

?></table><?
  }
  ?><div style="margin-top:40px; clear:both;">Total: <b><?= as_money($total) ?></b> in <?= count($rows) ?> accounts</div><?
}


function show_acct_params($p) {
  $o = json_decode($p, true);
  if ($o == NULL)
    return $p;

  $a = array();
  foreach ($o as $k=>$v) {
    switch ($k) {
      case 'tip_rate':
        if (empty($v))
          $a[] = "no tip";
        else
          $a[] = ($v * 100) . "% tip";
        break;
      case 'monthly':
        $v = as_money($v);
        $a[] = "$v/mo";
        break;
      case 'yearly':
        $v = as_money($v);
        $a[] = "$v/yr";
        break;
      case 'recipient':
        $a[] = "$k: " . $v['first_name'] . ' ' . $v['last_name'];
        break;
      default:
        $a[] = "$k: $v";
        break;
    }
  }

  return implode(', ', $a);
}


//Show donationAcct items
function show_donation_acct_list(){
  global $wpdb;

  $this_url = $_SERVER['REQUEST_URI'];
  $selected_tab = "#allocate";

  if ($_POST['submit']=='Create Account') {

////////////////////////////////////////////////////////////////////////////////
    //print_r($_REQUEST); exit();
    $selected_tab = "#create";

    foreach($_REQUEST as $k=>$v) { 
      if($k == 'balance'){
        $$k = doubleval($v);
      } else if($k == 'maximum') {
        $$k = doubleval($v);
      } else if($k == 'count') {
        $$k = intval($v);
      } else if($k == 'charity') {
        $$k = addslashes($v);
      } else if($k == 'unit_min') {
        $$k = intval($v);
      } else if($k == 'unit_max') {
        $$k = intval($v);
      } else {
        $$k = addslashes($v);
      } 
    } 
    $balance = from_money($balance);

    $params = get_acct_params(null);
    $params->unit_min = $unit_min;
    $params->unit_max = $unit_max;

    $provider_from = intval($_POST['provider_from']);
    $acct_from = get_acct_id_by_code_or_id($_POST['acct_from']);

    if ($balance < 0 && $provider_from != 10) {
      $errorMsg = "Please specify the balance of the new account.";

    } else if ($provider_from == 0 && $acct_from == 0) {
      $errorMsg = 'Please specify valid account code as source of funds.';
    } else {

      $donationGiver = insert_donation_giver($email, $first_name, $last_name);
      if (empty($donationGiver)) {
        $errorMsg = "Please provide valid details for the donor.";
      } else {
        $accountID = insert_donation_acct($donationGiver, 0, $type, 0, 
          $donationGiver, 0, $note, $params, $charity);
        $create_success = 'OK! Created account <a target="_new" href="'
          . $this_url . '&amp;accountID=' . $accountID . '">' 
          . $accountID . '</a>';
      }

      if($errorMsg == '') {
        if ($provider_from == 0) { //ACCT
          transfer_money($acct_from, $accountID, $balance, $note);

        } else if ($provider_from == 11) { //CASH/CHK
          do_offline_payment($accountID, $balance, $note);

        } else {
          insert_donation_acct_trans($accountID, $balance, 0, addslashes($note));

        }

        $type='';
        $provider_from='';
        $balance=0;
        $acct_from='';
        $first_name='';
        $last_name='';
        $email='';
        $note='';
      }
    }


////////////////////////////////////////////////////////////////////////////////

  } else if ($_POST['submit'] == 'Delete Accounts') {
      $deletedIds = as_ints($_POST['delete']);
    foreach($deletedIds as $deletedId) {
        delete_donation_acct($deletedId);
    }
  } else if($_POST['submit'] == 'Delete Test Data') {
      delete_donation_acct_test();
  }
?>
  <div id="account-tabs" style="width:1160px;">
  <h2>Donation Accounts</h2>

  <div class="subtabs">
  <a class="subtab" id="allocate" href="#">To Allocate</a>
  <a class="subtab" id="allocate-empty" href="#">To Allocate (empty)</a>
  <a class="subtab" id="accounts" href="#">Gift Codes</a>
  <a class="subtab" id="empties" href="#">$0 Accounts (2012)</a>
  <a class="subtab" id="empties-past" href="#">$0 Accounts (past)</a>
  <a class="subtab" id="expired" href="#">Expired</a>
  <a class="subtab" id="create" href="#">Create Account</a>
  <div class="clear"></div>
  </div>

  <div class="errorMsg"><?= $errorMsg ?></div>

  <form id="create-form" class="subtab" action="<?= $this_url ?>" method="post">
<? if ($create_success) { ?>
  <div style="color:#0a0; padding: 5px; font-size: 10pt;"><?= $create_success ?></div>
<? } ?>

    <h3>Details</h3>
    <div><label class="field_title" for="type">Account type: </label>
      <select name="type">
        <option value="7" <? selected($type, 7); ?>>to be donated on behalf of user</option>
        <option value="3" <? selected($type, 3); ?>>Impact Card (specified recipient)</option>
        <option value="6" <? selected($type, 6); ?>>Impact Card (unknown recipient)</option>
        <option value="5" <? selected($type, 5); ?>>discount (single-use, any recipient)</option>
        <option value="4" <? selected($type, 4); ?>>matching account</option>
  <!--  <option value="1" <? selected($type, 1); ?>>internal account</option>-->
        <option value="2" <? selected($type, 2); ?>>general account</option>
      </select>
    </div>  
    <div><label class="field_title" for="balance">Starting balance: </label>
      <input type="text" name="balance" maxsize="20" size="20" id="balance" value="<?=esc_attr($balance)?>"/></div>

    <div><label class="field_title" for="provider_from">Source of funds: </label>
      <select name="provider_from" class="payment_type">
        <option <? selected($provider_from, 0); ?> value="0">from account</option>
        <option <? selected($provider_from, 11); ?> value="11">cash/check</option>
        <option <? selected($provider_from, 10); ?> value="10">correction</option>
      </select>
      <span class="payment_account"> account: <input type="text" name="acct_from" id="acct_from" value="<?=esc_attr($acct_from)?>" /></span>
    </div>

    <h3>For Donor</h3>
    <div><label class="field_title" for="email">E-mail address: </label>
      <input type="text" name="email" maxsize="50" size="48" id="email" value="<?=$email?>"/></div>
    <div><label class="field_title" for="first_name">First, last name: </label>
      <input type="text" name="first_name" maxsize="50" size="20" id="first_name" value="<?=$first_name?>"/>
      <input type="text" name="last_name" maxsize="50" size="20" id="last_name" value="<?=$last_name?>"/> (if new)</div>
    <div>
      <br/>What will this account be used for? (required)<br/>
      <textarea name="note" id="note" rows="4" cols="50"><?=$note?></textarea>
    </div>
    <div><input type="submit" name="submit" value="Create Account" /></div>
    <h4>Advanced options below</h4>
    <div><br/>Limit the account for use only on:<br/>
      <?=build_charity_options('charity');?>
    </div>
    <br/>
    <div><label class="field_title" for="unit_min">Unit Min: </label>
      <input type="text" name="unit_min" maxsize="5" size="5" id="amount" value="0"/></div> 
    <div><label class="field_title" for="unit_max">Unit Max: </label>
      <input type="text" name="unit_max" maxsize="5" size="5" id="amount" value="99999"/></div> 
    <br/>
  </form>
  </div>
<?
  
  //MG
  //Apply to which blog?
  //Apply to which gift?
  //Apply to time start, time ends
  //Apply to min, max amount

  return $selected_tab;
}

//Get donor id by email
function get_donor_id($email){
  global $wpdb;  
  return $wpdb->get_var("SELECT ID FROM donationGiver WHERE email='" . $email . "'");  
}

function get_acct_code_by_donor($donor_id, $type){
  global $wpdb;  

  return $wpdb->get_var($wpdb->prepare(
    "SELECT code FROM donationAcct 
     WHERE donorId=%d AND donationAcctTypeId=%d
     ORDER BY id ASC LIMIT 1",
    $donor_id, $type));
}

//Get donation account id by donor id
function get_donor_info_by_acct($accountID){
  global $wpdb;  

  $res = $wpdb->get_row($sql = $wpdb->prepare(
    "SELECT da.donationAcctTypeId AS acctType, dg.*
     FROM donationAcct da
     LEFT JOIN donationGiver dg ON dg.id=da.donorId
     WHERE da.id=%d",
    $accountID), ARRAY_A);

  return array(
    $res,
    $res['acctType']
  );
}

//Get donation account id by acct code
function get_acct_id_by_code($code, $include_expired = TRUE){
  global $wpdb;  

  $code = trim($code);

  $include_expired = $include_expired ? "" : " AND NOT(expired=1)";
  $sql = "SELECT id FROM donationAcct WHERE code = '%s' ";
  return $wpdb->get_var($wpdb->prepare($sql, $code));
}
function get_acct_id_by_code_or_id($code, $include_expired = TRUE) {
  global $wpdb;

  $i = intval($code);
  if (strlen($code) < 7 && $i > 0) {
    $include_expired = $include_expired ? "" : " AND NOT(expired=1)";
    $i = intval($wpdb->get_var($wpdb->prepare("SELECT id FROM donationAcct WHERE id=%d $include_expired",$i)));
    return $i;
  }
  return get_acct_id_by_code($code, $include_expired);
}

function get_donation_account($id) {
  global $wpdb;  

  return $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM donationAcct WHERE id=%d", $id));
}

//Get donation account code by acct id
function get_acct_code_by_id($id){
  global $wpdb;  

  return $wpdb->get_var($wpdb->prepare(
    "SELECT code FROM donationAcct WHERE id=%d", $id));
}

//Get actual donation account balance by calculating the total transaction history
function get_donation_acct_balance($accountID, $static=false){
  global $wpdb;  
  
  $sql = "SELECT balance FROM donationAcct WHERE id = '%s'";
  $balance = $wpdb->get_var($wpdb->prepare($sql, $accountID));
  if($balance===NULL){return $balance;}
  if($static){return $balance;}
    
  $sql = "SELECT SUM(amount) FROM donationAcctTrans WHERE donationAcctId = %d GROUP BY donationAcctId";
  $balance2 = $wpdb->get_var($wpdb->prepare($sql, $accountID));  

  if ($balance != $balance2) {
    $balance = $balance2;
    $sql = $wpdb->prepare("UPDATE donationAcct SET balance = %f WHERE id = %d", $balance, $accountID);
    $wpdb->query($sql);
  }
  
  return $balance;
}

//Get donation account type
function get_donation_acct_type($accountID){
  global $wpdb;  
  
  $sql = 'SELECT donationAcctTypeId FROM donationAcct WHERE id = "%s"';
  return $wpdb->get_var($wpdb->prepare($sql, $accountID));
}

function expire_donation_acct($id, $expired = TRUE, $into = NULL, $neg_ok = FALSE) {
  global $wpdb;
 
  if (!empty($into)) {
    $balance = get_donation_acct_balance($id);
    if ($balance < 0 && !$neg_ok)
      return form_error("Balance is negative -- please reconcile before expiring");

    // Transfer entire balance into account
    $to = get_acct_id_by_code_or_id($into);
    if (empty($to))
      return form_error("Can't find account '$into'");

    $acct = get_donation_account($to);
    if ($acct->expired)
      return form_error("Account $acct->code (#$acct->id) is expired!");

    if ($balance < 0)
      transfer_money($acct->id, $id, -$balance, "Account expiration");
    else
      transfer_money($id, $acct->id, $balance, "Account expiration");
  }

  $wpdb->update('donationAcct',
    array('expired' => $expired),
    array('id' => $id));
 }

function insert_donation_giver($email, $first_name='', $last_name='', $referrer='', $user_id=0){
  global $wpdb;    

  $email = sanitize_email($email);
  if (!is_email($email)) 
    return NULL;

  //See if there is a user with the email already
  $donorId = get_donor_id($email);
  if (!empty($donorId)) 
    return $donorId;

  $first_name = trim($first_name);
  $last_name = trim($last_name);
  if (empty($first_name) || empty($last_name)) 
    return NULL;

  //donor email not found, create a new one    
  $wpdb->insert('donationGiver', array(
    'email' => $email,
    'firstName' => $first_name,
    'lastName' => $last_name,
    'donationOwner' => 1,
    'referrer' => $referrer,
    'main' => 1,
    'user_id' => $user_id));
  
  return $wpdb->insert_id;
}

//Add new donation account
function insert_donation_acct($donorId, $balance = 0, $type = ACCT_TYPE_GIFT, $paymentID = 0, 
  $owner = 0, $creator = 0, $note = '', $params = '', $charity = 1, $event_id = 0){
  global $wpdb, $emailEngine;

  if ($params == NULL)
    $params = new stdClass;

  if (empty($params->unit_min))
    unset($params->unit_min);
  if (empty($params->unit_max) || $params->unit_max > 10000)
    unset($params->unit_max);
  if (empty($params->match_tip))
    unset($params->match_tip);

    if ($owner == 0) { $owner = $donorId; }
  $randomCode = generate_random_code();

  $p = json_encode($params);
  if ($p == '{}')
    $p = '';

  // Disabling it for now
  //$event_id = NULL;

  //Insert new row to donationAcct table
  $sql = $wpdb->prepare(
    "INSERT INTO donationAcct (donorId, balance, dateCreated, dateUpdated, code,
     testData, donationAcctTypeId, owner, creator, note, params, blogId, event_id)
     VALUES(%d, 0, NOW(), NOW(), %s, %d, %d, %d, %d, %s, %s, %d, %d)",
    $donorId,$randomCode,(PAYMENT_TEST_MODE?1:0),$type,$owner,$creator,
    $note,$p,$charity,$event_id);


  $wpdb->query($sql);
  $accountID = $wpdb->insert_id;
    
  //Insert initial donationAcctTrans 
  $payment = empty($payment) ? "" : "(Payment #$payment)";
  if (!empty($note))
    $note = " - $note";
  insert_donation_acct_trans($accountID, $balance, $paymentID, 
    "Initial account balance $payment$note");

  return $accountID;
}

function transfer_money($src, $dst, $amt, $notes, $type_id=10) {
  global $wpdb;
  
  $amt = abs(floatval($amt));

  $src_tid = insert_donation_acct_trans($src, -1*$amt, 0, addslashes($notes).' - transfer to #'.$dst);
  $dst_tid = insert_donation_acct_trans($dst, $amt, 0, addslashes($notes).' - transfer from #'.$src);

  $wpdb->query($wpdb->prepare("INSERT INTO payment (dateTime,amount,provider,txnID) VALUES (NOW(),%f,%d,CONCAT('#',%d))", -1*$amt, $type_id, $src_tid));
  $src_pid = $wpdb->insert_id;

 $wpdb->query($wpdb->prepare("INSERT INTO payment (dateTime,amount,provider,txnID) VALUES (NOW(),%f,%d,CONCAT('#',%d))", $amt, $type_id, $dst_tid));
  $dst_pid = $wpdb->insert_id;

  $wpdb->query($wpdb->prepare("UPDATE donationAcctTrans SET paymentID=%d WHERE id=%d",$src_pid,$src_tid));
  $wpdb->query($wpdb->prepare("UPDATE donationAcctTrans SET paymentID=%d WHERE id=%d",$dst_pid,$dst_tid));
}

function do_offline_payment($acct_id, $amt, $notes, $type_id=11) {
  global $wpdb;

  list($donor, $type) = get_donor_info_by_acct($acct_id);
  $did = $donor['ID'];

  $dat_id = insert_donation_acct_trans($acct_id, $amt, 0, addslashes($notes));

  $wpdb->query($wpdb->prepare("INSERT INTO payment (dateTime,amount,provider,txnID) VALUES (NOW(),%f, %d, CONCAT('CSH/CHK #',%d))", $amt, $type_id, $dat_id));
  $pid = $wpdb->insert_id;
  $wpdb->query($wpdb->prepare("INSERT INTO donation (donationDate, donationAmount_Total, donorID, paymentID) VALUES (NOW(),%f,%d,%d)", $amt, $did, $pid));
  $wpdb->query($wpdb->prepare("UPDATE donationAcctTrans SET paymentID=%d WHERE id=%d", $pid, $dat_id));
}

function generate_random_code(){
  global $wpdb;

  $chars = "ABCDEFHKLMNPQRSTUVWXY3489"; 
  srand(doubleval(microtime()*1000000)); 

  $x = 0;

  do {
    if ($x > 0) {
      debug("DUPLICATE CODE CREATED: ".$pass,true);    
    }

    $pass = '' ; 
    for($i = 0; $i < 10; $i++) { 
      $num = rand(0,24); 
      $tmp = substr($chars, $num, 1); 
      $pass .= $tmp; 
    } 

    // if($x == 0) { $pass = 'APVKBDBP8S';    }

    $exists = $wpdb->get_var($wpdb->prepare(
    "SELECT id FROM donationAcct WHERE code=%s", $pass));
    
    $x++;
  } while(intval($exists)>0);
  
  return $pass; 
}

//Show donationAcct 
function show_donation_acct_item($accountID){
  global $wpdb;

  list($donor, $type) = get_donor_info_by_acct($accountID);
  $name = $donor['firstName'] . ' ' . $donor['lastName'];
  $donorId = $donor['ID'];

  $this_url = $_SERVER['REQUEST_URI'];
  $selected_tab = "#transactions";

  $balance = $wpdb->get_var($wpdb->prepare("SELECT balance FROM donationAcct WHERE id = %d",$accountID));
  
  if ($_POST['submit'] == 'Add Money') {
    $selected_tab = "#add";

//    $amount = from_money($_POST['amount']);
//    $provider = intval($_POST['provider']);
//    $reference = trim($_POST['reference']);
    $amount_in = abs(doubleval(from_money($_POST['amount_in'])));
    $amount_out = abs(doubleval(from_money($_POST['amount_out'])));
    $provider_from = intval($_POST['provider_from']);
    $provider_to = intval($_POST['provider_to']);
    $notes = trim($_POST['notes']);

    //Inserting new transaction

    if ($amount_in == 0 && $amount_out == 0) {
      $errorMsg = 'No amount specified.'; 
    } else if ($notes == '') { 
      $errorMsg = 'Please provide an explanation for this change.'; 
    } else {

      if($amount_in > 0) { // there is money coming in
        $acct_from = get_acct_id_by_code_or_id($_POST['acct_from']);

        if ($provider_from == 0 && $acct_from == $accountID) {
          $errorMsg = 'Please specify a valid account to transfer from.';
        } else if($provider_from == 0 && $acct_from == 0) {
          $errorMsg = 'Please specify a valid account code to transfer from.';
        } else if ($provider_from == 0) { //ACCT
          transfer_money($acct_from, $accountID, $amount_in, $notes);
        } else if ($provider_from == 11) { //CASH/CHK
          do_offline_payment($accountID, $amount_in, $notes);
        } else {
          insert_donation_acct_trans($accountID, $amount_in, 0, addslashes($notes));
        }
      }
      
      if($amount_out > 0 && $errorMsg == '') { // there is money going out
        $acct_to = get_acct_id_by_code_or_id($_POST['acct_to']);

        if ($provider_to == 0 && $acct_to == $accountID) {
          $errorMsg = 'Please specify a valid account to transfer to.';
        } else if($provider_to == 0 && $acct_to == 0) {
          $errorMsg = 'Please specify valid account ID to distribute the money to.';
        } else if ($provider_to == 0) { //ACCT
          transfer_money($accountID, $acct_to, $amount_out, $notes);
        } else if ($provider_to == 11) { //CASH/CHK
          do_offline_payment($accountID, -1*$amount_out, $notes);
        } else {
          insert_donation_acct_trans($accountID, -1*$amount_out, 0, addslashes($notes));
        }
      } 
    }

    if($errorMsg == '') {
      unset($amount_in);
      unset($amount_out);
      unset($provider_from);
      unset($provider_to);
      unset($notes);
      $selected_tab = "#transactions";
    }

  } else if ($_POST['submit'] == 'Delete Transactions') {
    $deletedIds = as_ints($_POST['delete']);

    foreach ($deletedIds as $deletedId) {
      delete_donation_acct_trans($deletedId);
    }
  } else if ($_POST['submit'] == 'Reactivate') {
    $result = expire_donation_acct($accountID, FALSE);
    $selected_tab = '#transactions';
  } else if ($_POST['submit'] == 'Expire') {
    $result = expire_donation_acct($accountID, TRUE);
    $selected_tab = '#transactions';
  } else if ($_POST['submit'] == 'Expire into SYI') {
    $result = expire_donation_acct($accountID, TRUE, 2);
    if (is_error_result($result)) {
      $errorMsg = $result->data; 
    }
    $selected_tab = '#transactions';
    
  } else if ($_POST['submit'] == 'Expire from Account' || $_POST['submit'] == 'Expire to Account') {
    $result = expire_donation_acct($accountID, TRUE, $_POST['expire_into'], $_POST['submit'] == 'Expire from Account');
    if (is_error_result($result)) {
      $errorMsg = $result->data; 
    }
    $selected_tab = '#transactions';
  } else if ($_POST['submit'] == 'Create GCs') {
    $selected_tab = "#distribute";

    $count = intval($_POST['count']);
    if ($count <= 0)
      $count = 1;
   
    $amount = from_money($_POST['amount']);

    if ($amount == 0) { $errorMsg = 'No amount specified.';  }
    if (($count * $amount) > $balance) { $errorMsg = 'There\'s not enough balance to distribute that amount.'; }
    else {
        $charity = $wpdb->get_var("SELECT blogId FROM donationAcct WHERE id = $accountID");

    $type = ACCT_TYPE_OPEN_CODE;

        //Create new GCs
        for($i=1;$i<=$count;$i++){
        $newDaId = insert_donation_acct($donorId, $amount, $type, 0, $donorId, 0,
          "$i of $count from account $accountID ($name)", '',$charity);
    }
             
    //Reduce the parent account
    insert_donation_acct_trans($accountID, -$count * $amount, '', 
       "Debited to create $count new " . as_money($amount) . " Impact Cards");
        $selected_tab = "#transactions";
    }
    
  } else if ($_POST['submit'] == 'Save Settings') {

    $params = json_decode($donationAcct['params'],true);
    $params = change_params($params, $_POST);

    $wpdb->update("donationAcct",
      array('params' => json_encode($params), 'blogId' => $charity),
      array('id' => $accountID));
  }

  $balance = $wpdb->get_var($wpdb->prepare("SELECT balance FROM donationAcct WHERE id = %d",$accountID));
  $donationAcct = $wpdb->get_row("SELECT * FROM donationAcct WHERE id = '" . $accountID . "'",ARRAY_A);

  $charity = $donationAcct['blogId'];
  $params = json_decode($donationAcct['params']);
  $unit_min = $params->unit_min;
  $unit_max = $params->unit_max;
  $match_tip = $params->match_tip;

?>
  <form action="<?=$this_url?>" method="post" style="float: right; padding: 10px 20px;">
    <? $balance = round($donationAcct['balance'], 2); ?>
    <? if ($donationAcct['expired']) { ?>
      <input type="submit" name="submit" value="Reactivate" class="button white-button medium-button" style="margin-right: 30px;">
    <? } else if ($balance == 0) { ?>
      <input type="submit" name="submit" value="Expire" class="button white-button medium-button">
    <? } else { ?>
      <input type="submit" name="submit" value="Expire into SYI" class="button white-button medium-button" style="margin-right: 30px;">
    <? } ?>

    <? if ($balance > 0) { ?>
      <label for="expire_into">account: </label>
      <input type="text" id="expire_into" name="expire_into" size="15">
      <input type="submit" name="submit" value="Expire to Account" class="button white-button medium-button">
    <? } else if ($balance < 0) { ?>
      <label for="expire_into">account: </label>
      <input type="text" id="expire_into" name="expire_into" size="15">
      <input type="submit" name="submit" value="Expire from Account" class="button white-button medium-button">
    <? } ?>
  </form>

  <div> 
  <h2>Account #<?= $donationAcct['id'] ?> <?= $donationAcct['code'] ?> 
<? 
switch ($donationAcct['donationAcctTypeId']) {
  case ACCT_TYPE_INTERNAL: echo "[Internal]"; break;
  case ACCT_TYPE_GENERAL: echo "[General Fund]"; break;
  case ACCT_TYPE_GIFT: echo "[Gift Code]"; break;
  case ACCT_TYPE_MATCHING: echo "[Matching]"; break;
  case ACCT_TYPE_DISCOUNT: echo "[Discount]"; break;
  case ACCT_TYPE_OPEN_CODE: echo "[Open Code]"; break;
  case ACCT_TYPE_GIVE_ANY: echo "[To Allocate]"; break;
}
if ($donationAcct['expired'])
  echo ' <span style="color:#800;">EXPIRED</span>';

if ($donationAcct['event_id'] > 0) {
  echo " [for fundraiser #" . $donationAcct['event_id'] . "]";
}
?>
   - balance
    <span <?= $balance>=0 ? '' : 'style="color:#900"' ?>><?= as_money($balance) ?></span>
  </h2>
  Donor: <?= $name ?> (<?= as_email($donor['email']) ?>) #<?= $donor['ID'] ?><br>
  </div> 

  <div class="subtabs">
  <a class="subtab selectedTab" id="transactions" href="#">Transactions</a>
  <a class="subtab" id="add" href="#">Add Money</a>
  <a class="subtab" id="distribute" href="#">Create GCs</a>
  <div class="clear"></div>
  </div>

  <div class="errorMsg"><?= $errorMsg ?></div>

  <form class="subtab" id="transactions-form" action="<?= $this_url ?>" method="post">
<?
    //Get donation account transaction rows from donationAcctTrans table
    $rows = $wpdb->get_results($wpdb->prepare(
      "SELECT dat.id as id, 
        DATE_FORMAT(dat.dateInserted,'%%m/%%d/%%y \n %%H:%%i') as date,
        dat.amount, dat.kind, dat.note, p.id as paymentID, p.donation as donationID,
        GROUP_CONCAT(CONCAT(dg.id,': $',dg.amount,'+$',dg.tip,' ',g.displayName)) as matched
       FROM donationAcctTrans dat
       LEFT JOIN payment p on dat.paymentID=p.id
       LEFT JOIN donationGifts dg on dg.matchingDonationAcctTrans=dat.id
       LEFT JOIN gift g on dg.giftID=g.id
       WHERE donationAcctId = %d
       GROUP BY dat.id
       ORDER BY dateInserted", 
       $accountID), ARRAY_A);

  $i = 0;
  if(is_array($rows)) {
?><table><tr><?
    $tbl = "";
    foreach($rows as $row) {
?>
      <tr class="row<? if ($i % 2 == 1) echo ' odd' ?>">
        <td class="row_cell"><?= $row['id'] ?></td>
        <td class="row_cell"><?= $row['date'] ?></td>
        <td class="row_cell"><?= as_money($row['amount']) ?></td>
        <td class="row_cell" style="width:600px;word-wrap:break-word">
          <? if (!empty($row['kind'])) { ?>
            <?= $row['kind'] ?>:
          <? } ?>

          <?= nl2br(stripslashes($row['note'])) ?>
          <? if ($row['matched'] > 0) { ?>
            [match <?= $row['matched'] ?>]
          <? } ?>
          <? if ($row['paymentID'] > 0) { ?>
            <span class="debug">payment <?= $row['paymentID'] ?></span>
          <? } ?>
          <? if ($row['donationID'] > 0) { ?>
            <span class="debug">donation <?= $row['donationID'] ?></span>
          <? } ?>
          <? if ($row['donationID'] == 0 && $row['paymentID']==0 && $row['amount'] != 0) { ?>
            <span class="debug" style="color:red;">no info</span>
          <? } ?>
    
        </td>
      </tr>
<?
      $i++;
    }
?></tr></table><?
  } else {
    echo "No transactions.";
  }
?>
  <h3 style="margin-top:40px;">Settings</h3>
    <div>
<? $da_type = $donationAcct['donationAcctTypeId'];
   $p = json_decode($donationAcct['params'], true);
   if ($da_type == 7) { ?>
    <? param_field_for('monthly', $p) ?>
    <? param_field_for('tip_rate', $p) ?>
    <? param_field_for('tags', $p) ?>
<? } else { 
  if (is_object($params->recipient)) {
  ?>
    <div>Recipient: <?= $params->recipient->first_name ?>
      <?= $params->recipient->last_name ?> (<?= eor($params->recipient->email, "no email") ?>)<br>
      <?= $params->recipient->address ?> <?= $params->recipient->address2 ?><br>
      <?= $params->recipient->city ?> <?= $params->recipient->state ?> <?= $params->recipient->zipcode ?><br>
      <br>
    </div>
  <?
  }
/*
    Limit the account for use only on:<br/><br/>
    <?=build_charity_options('charity',$charity);?>  
    <br/>
*/
?>
    <div><label class="field_title" for="unit_min">Unit Min: </label>
    <input type="text" name="unit_min" maxsize="5" size="5" id="amount" value="<?=$unit_min?>"/></div> 
    <div><label class="field_title" for="unit_max">Unit Max: </label>
    <input type="text" name="unit_max" maxsize="5" size="5" id="amount" value="<?=$unit_max?>"/></div> 
    <div><input type="checkbox" name="match_tip" value="1" <? checked($match_tip); ?> /> Match Tip</div>
<? } ?>
    </div>

    <input type="submit" name="submit" value="Save Settings" style="margin-top:20px;"/>

  </form>
    
  <form class="subtab" id="add-form" action="<?= $this_url ?>" method="post">
    <p>Fill the form below to add or transfer money:</p>

    <p>
    ADD: $<input type="text" name="amount_in" maxsize="20" size="10" id="amount_in" value="<?=$amount_in?>"/>
    <select name="provider_from" class="payment_type">
      <option <? selected($provider_from, 0); ?> value="0">from account</option>
      <option <? selected($provider_from, 11); ?> value="11">cash/check</option>
      <option <? selected($provider_from, 10); ?> value="10">correction</option>
    </select>
    <span class="payment_account"> account: <input type="text" name="acct_from" id="acct_from" value="<?=esc_attr($acct_from)?>" />
    </p>

    <p>
    XFER: $<input type="text" name="amount_out" maxsize="20" size="10" id="amount_out" value="<?=$amount_out?>" />
    <select name="provider_to" class="payment_type">
      <option <? selected($provider_from, 0); ?> value="0">to account</option>
      <option <? selected($provider_from, 10); ?> value="10">correction</option>
      <option <? selected($provider_from, 11); ?> value="11">withdrawal / payment</option>
    </select>
    <span class="payment_account"> account: <input type="text" name="acct_to" id="acct_to" value="<?=esc_attr($acct_to)?>" />
    </p>

    <p style="margin-top:10px;">
    Please explain this transaction: <br/><textarea name="notes" id="notes" cols="30" rows="3" style="width:500px; height:75px;"><?=$notes?></textarea>
    </p>

    <div><br/><input type="submit" name="submit" value="Add Money" /><input type="reset" name="reset" value="Reset" /></div><br/>
  </form>

  <form class="subtab" id="distribute-form" action="<?= $this_url ?>" method="post">
    <div><label class="field_title" for="count"># of GCs: </label>
    <input type="text" name="count" maxsize="5" size="5" id="count" value=""/></div>
    <div><label class="field_title" for="maximum">Amount per GC: </label>
    <input type="text" name="amount" maxsize="5" size="5" id="amount" value=""/></div> 
    <br/>
    <div><input type="submit" name="submit" value="Create GCs" /></div>  
  </form>

  <form class="subtab" id="expire-form" action="<?= $this_url ?>" method="post">
  </form>
<?
  return $selected_tab;
}

function param_field_for($name, $params, $div = true) {
  if ($params == NULL)
    $params = array();

  if ($div) { ?><div><? }
  switch ($name) {
    case 'tags':
      echo htmlspecialchars($name) . ": ";
      ?><input type="text" name="<?= esc_attr($name) ?>" size="50" value="<?= esc_attr($params[$name]) ?>" /><?
      break;
    default:
      echo htmlspecialchars($name) . ": ";
      ?><input type="text" name="<?= esc_attr($name) ?>" value="<?= esc_attr($params[$name]) ?>" /><?
      break;
  }
  if ($div) { ?></div><? }
}

function change_params($params, $vals) {
  if ($params == NULL)
    $params = array();
  $keys = array('charity', 'unit_min', 'unit_max', 
    'match_tip', 'monthly', 'yearly', 'tip_rate', 'tags');

  foreach ($keys as $key) {
    if(isset($vals[$key])) {
	  $val = $vals[$key];
	  switch ($key) { 
		case 'tip_rate':
		  $val = floatval($val);
		  if ($val >= 1)
			$val = $val / 100;
		  break;
		case 'monthly':
		case 'yearly':
		  $val = from_money($val);
		  break;
	  }
  
	  if (empty($val))
		unset($params[$key]);
	  else {
		$params[$key] = $val;
	  }
	} 
  }

  return $params;
}

function create_donation_payment($donorID, $amount, $providerID, $txn_id, 
  $when = NULL, $raw = NULL, $insert_donation = true) {
  global $wpdb;

  if (empty($when))
    $when = time();
  $when = date( 'Y-m-d H:i:s', $when );

  if (empty($raw)) {
    $user = wp_get_current_user();
    $now = date( 'Y-m-d H:i:s', time() );
    $raw = "inserted by {$user->user_login} on {$now}";
  }

  $wpdb->insert('payment', array(
    'dateTime' => $when,
    'amount' => $amount,
    'provider' => $providerID,
    'raw' => $raw,
    'txnID' => $txn_id),
    array('%s','%f','%d','%s','%s'));
  $pid = $wpdb->insert_id;

  if($insert_donation)
	$wpdb->insert('donation', array(
	  'donationDate' => $when,
	  'donationAmount_Total' => $amount,
	  'donorID' => $donorID,
	  'paymentID' => $pid),
	  array('%s','%f','%d','%s'));

  return $pid;
}

//Insert donationAcctTrans
function insert_donation_acct_trans($accountID, $amount=0, $paymentID='', $note='', $use=false){
  global $wpdb;  
  $ret = '';

  //Insert new donationAcctTrans  
  if($amount != 0 || $note !=''){//You need to have a point in this transaction
    $wpdb->query("INSERT INTO donationAcctTrans "
      . "(donationAcctId, amount, paymentID, note, dateInserted) "
      . " VALUES('" . $accountID . "', '" . $amount . "', '"
      . $paymentID . "', '".$note."', NOW()) ");
      $ret = $wpdb->insert_id;
  }
  
  //Sum the new balance from transactions
  $balance = get_donation_acct_balance($accountID);
  
  //Update the balance on donationAcct
  $x="";
  if ($use) $x = "dateUpdated=NOW(),";
  $wpdb->query(
    "UPDATE donationAcct SET $x "
    . "balance = " . $balance . " "
    . ($use?",`use` = `use` + 1":"") . " "
    . "WHERE id = '" . $accountID . "'");  
  
  return $ret;
}

//Delete donationAcctTrans
function delete_donation_acct_trans($id, $deleteAcct = false){
  global $wpdb;

  if($deleteAcct) {
    $wpdb->query("DELETE FROM donationAcctTrans WHERE donationAcctId = '" . $id . "'"); 
    get_donation_acct_balance($id);
  } else {
    $wpdb->query("DELETE FROM donationAcctTrans WHERE id = '" . $id . "'"); 
  }
}

function create_user_donation_account($userID, $donorID=0, $da_type=ACCT_TYPE_GENERAL){
  global $wpdb;

  if($donorID == 0) {
    $donorID = $wpdb->get_var($wpdb->prepare(
      "SELECT id FROM donationGiver WHERE user_id=%d AND donationAcctTypeId=%d 
       ORDER BY id LIMIT 1", $userID, $da_type));
  }

  if ($donorID == 0) {
    $user = get_userdata($userID);

    if(!empty($user->first_name)) {
      $first_name = $user->first_name;
    } else {
      $display_name = explode(" ",$user->display_name);
      if(!empty($display_name[0])) {
        $first_name = $display_name[0];
      } else {
        $first_name = $user->user_login;
      }
      if (!empty($display_name[1])) $last_name = $display_name[1];
    }
    
    if (!empty($user->last_name)) $last_name = $user->last_name;
    
    $donorID = insert_donation_giver($user->user_email, $first_name, $last_name, '', $userID);
  }

  $da_id = insert_donation_acct($donorID, 0, $da_type, 0, $donorID, 0, "Created donor $donorID", '', 1);
  return $da_id;
}

////  ////

function get_user_donation_accts($user_id, $type_id=ACCT_TYPE_GENERAL,
  $event_id=NULL, $blog_id=NULL) {
  global $wpdb;
  $sql = $wpdb->prepare("SELECT da.* FROM donationAcct da
	LEFT JOIN donationGiver dg ON da.donorID = dg.ID 
	WHERE donationAcctTypeId = %d AND dg.user_id = %d ".
	($event_id!==NULL?" AND event_id=".intval($event_id):"").
	($blog_id!==NULL?" AND blogId=".intval($blog_id):"").
	" ORDER BY da.id ASC",$type_id,$user_id);
  dr("GET USER DAS: ".$sql);
  return $wpdb->get_results($sql);	
}

function get_donation_acct_by_userid($user_id, $type_id=ACCT_TYPE_GENERAL, 
  $tip_rate=NULL,$event_id=NULL, $blog_id=NULL, $create_new=FALSE){
  global $wpdb;
  
  $current_recurring = get_user_meta($user_id,'current_recurring',true);
  $daccts = get_user_donation_accts($user_id,$type_id,$event_id,$blog_id);

  if (is_array($daccts) && !empty($daccts)) {
    $select = 0;
	foreach($daccts as $k=>$dacct) {
	  $params = json_decode($dacct->params);
	  if($tip_rate!=NULL && $params->tip_rate==$tip_rate) {
        if($select==0) $select = $k;
		if($current_recurring == $dacct->id) {
		  dr("GET CURRENT DA W SAME TIPRATE \n".print_r($dacct,true));
		  return $dacct;
		}
	  } else if ($tip_rate==NULL && $current_recurring == $dacct->id) {
        dr("GET CURRENT DA REGARDLESS TIP \n".print_r($dacct,true));
		return $dacct;
	  }
	}
    if($select>0) { //return da with same tiprate and set current
      $dacct = $daccts[$select];
      update_user_meta($user_id,'current_recurring',$dacct->id);
	  dr("SET DA W SAME TIPRATE ".$tip_rate." AS CURRENT \n".print_r($dacct,true));
	  return $dacct;
	} 
	if($tip_rate==NULL) { //return first available and set as current
      $dacct = $daccts[0];
      update_user_meta($user_id,'current_recurring',$dacct->id);
	  dr("SET 1ST AVAILABLE DA AS CURRENT \n".print_r($dacct,true));
	  return $dacct;  
	}
  }
  
  if($create_new) { //there is no da with same tip rate, or there is no da at all
    $dacct_id = create_user_donation_account($user_id, 0, $type_id);
    update_user_meta($user_id,'current_recurring',$dacct_id);
    if($tip_rate!=NULL) 
      $wpdb->update("donationAcct", 
        array('params'=>json_encode(array('tip_rate'=>$tip_rate))), array('id'=>$dacct_id));
    $dacct = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM donationAcct WHERE id=%d",$dacct_id));
      dr("GET NEW DA W TIPRATE".$tip_rate." \n".print_r($dacct,true));
    return $dacct;
  }

  dr("ERROR");
  return NULL;
}

function get_donation_acct_by_donorid($donorID, $da_type=ACCT_TYPE_GENERAL){
  global $wpdb;

  return $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM donationAcct da 
     WHERE da.donorId = %d AND da.donationAcctTypeId = %d ",
    $donorID, $da_type));
}

function get_donorid_by_userid($userID){
  global $wpdb;
  
  return $wpdb->get_var($wpdb->prepare(
    "SELECT ID FROM donationGiver dg 
     WHERE dg.user_id = %d
     ORDER BY main,ID LIMIT 1",$userID));
}

global $spreedly_url;

function init_monthly() {
  global $monthly_api;
  if (!empty($monthly_api))
    return;

  if (SPREEDLY_ENABLED) {

    $monthly_api = 'https://spreedly.com/'.SPREEDLY_NAME;
    Spreedly::configure(SPREEDLY_NAME, SPREEDLY_TOKEN);

  } else if (RECURLY_ENABLED) {

    $monthly_api = 'https://' . RECURLY_SITE . '.recurly.com';
    RecurlyClient::SetAuth(RECURLY_USERNAME, RECURLY_PASSWORD, RECURLY_SITE);

  }
}

function get_monthly_plans() {
  global $subscription_plans;
  if ($subscription_plans != null)
    return $subscription_plans;

  $subscription_plans = array();
  if (SPREEDLY_ENABLED) {
    foreach (SpreedlySubscriptionPlan::get_all() as $plan) {
      $subscription_plans[] = $plan;
    }
  } else if (RECURLY_ENABLED) {
    try {
      foreach (RecurlyPlan::getPlans() as $plan) {
        $p = new stdClass();
        $p->name = $plan->plan_code;
        $p->enabled = true;
        $p->title = $plan->name;
        $p->price = $plan->unit_amount_in_cents / 100;
        $subscription_plans[] = $p;
      }
    } catch (RecurlyException $e) {
    }
  }
  return $subscription_plans;
}

//NOTE:
//recurring_account on 3 functions below is NOT referencing to donation accounts
//it is merely a unique identifier based on WP user id, to be recorded at recurly

function get_user_monthly_account($user_id) {
  $acct_id = get_user_meta($user_id, 'recurring_account', true);
  if (empty($acct_id)) { 
    set_user_monthly_account($user_id, md5($user_id));
    $acct_id = get_user_meta($user_id, 'recurring_account', true);
  }
  return $acct_id;
}

function set_user_monthly_account($user_id, $acct_id) {
  update_user_meta($user_id, 'recurring_account', $acct_id);
}

function get_user_from_monthly_account($acct_id) {
  //$acct_id is NOT donation account code

  global $wpdb;
  return $wpdb->get_var($wpdb->prepare(
    "SELECT user_id FROM wp_usermeta 
     WHERE meta_key = 'recurring_account' AND meta_value=%s 
	 ORDER BY umeta_id ASC LIMIT 1", $acct_id));
}

function get_monthly_subscriber($user) {
  init_monthly();
  $recurring_tip = get_user_meta($user->id,'recurring_tip',true);
  if (SPREEDLY_ENABLED) {
    $sub = SpreedlySubscriber::find($user->id);
    if($sub == NULL){
      // user not found on spreedly - creating one
      $sub = SpreedlySubscriber::create($user->id, $user->email, $user->user_login);
    }

    foreach(get_monthly_plans() as $k => $plan){
      if($sub->subscription_plan_name == $plan->name){
        $sub->recurring_plan = $plan;
        $sub->recurring_amount = $plan->price; 
        break;
      } 
    }
  } else if (RECURLY_ENABLED) {

    $acct_id = get_user_monthly_account($user->id);
    try {
      $sub = RecurlyAccount::getAccount($acct_id);
    } catch (RecurlyException $e) {
      $sub = NULL;
    }
    if ($sub == NULL) {
      $ud = get_userdata($user->id);
      $sub = new RecurlyAccount($acct_id, 
        $ud->user_login, $ud->user_email,
        $ud->first_name, $ud->last_name, '');
      $sub->recurring_amount = 0;
      $sub->is_new = true;
    }

    $sub->recurring_plan = NULL;
    $pending = NULL;
    if (!$sub->is_new) {
      try {
        $sub->recurring_plan = RecurlySubscription::getSubscription($sub->account_code);
        $pending = $sub->recurring_plan->pending_subscription;
      } catch (RecurlyException $e) { }
    }

    if ($pending != NULL) {
      $sub->recurring_amount = round(($pending->quantity / (1 + $recurring_tip)) / 20);
      $sub->next_payment = $pending->activates_at;
    } else if ($sub->recurring_plan->state == "active") {
      $sub->recurring_amount = round(($sub->recurring_plan->total_amount_in_cents / (1 + $recurring_tip)) / 100);
      $sub->next_payment = $sub->recurring_plan->current_period_ends_at;
    } else {
      $sub->recurring_amount = 0;
    }
  }

  $sub->recurring_tip = $recurring_tip;
  $sub->user = $user;
  $sub->cid = get_user_meta($user->id, 'recurring_cid', TRUE);

  if($_REQUEST['dump']=='yes' && current_user_can('level_10'))
    pre_dump($sub);

  return $sub;
}

function get_monthly_billing_url($sub) {
  global $monthly_api;

  if (SPREEDLY_ENABLED) {
    return "";
  } else if (RECURLY_ENABLED) {
    return "$monthly_api/account/$sub->hosted_login_token";
  }

  return null;
}

function update_monthly_subscription($sub, $amount, $tip, $backto) {
  global $monthly_api;
  init_monthly();
  
  if ($sub == null)
    return;

  $current_amount = $sub->recurring_amount * (1.0 + $sub->recurring_tip);
  $amount = absint($amount);
  $new_amount = $amount * (1.0 + $tip);

  update_user_meta($sub->user->id, 'recurring_cid', $sub->cid);

  if ($current_amount == $new_amount)
    return;

  update_user_meta($sub->user->id, 'recurring_tip', $tip);

  if (SPREEDLY_ENABLED) {
    if ($amount == 0) {
      // Cancel the monthly plan.
      $payment_url = "$monthly_api/subscriber_accounts/$sub->token/stop_auto_renew_confirm";
      header("Location: $payment_url?return_url=".urlencode($backto)); exit();
    } else {
      foreach(get_monthly_plans() as $k => $plan){
        if(intval($plan->price) == intval($new_amount)){
          $payment_url = "$monthly_api/subscribers/$sub->customer_id/$sub->token/subscribe/$plan->id/$sub->screen_name";
          header("Location: $payment_url?return_url=".urlencode($backto)); exit();
        }
      }
    }
  } else if (RECURLY_ENABLED) {

    $nickles = round($amount * 20 * (1.0 + $tip));

    if ($amount == 0) {

      RecurlySubscription::cancelSubscription($sub->account_code);
      RecurlyAccount::closeAccount($sub->account_code);

    } else if ($sub->recurring_plan != null) {
      if ($sub->recurring_plan->state != 'canceled') {
        $when = 'renewal'; // always at month periods.
        //$when = ($new_amount > $current_amount) ? 'now' : 'renewal';
      } else {
        $when = 'now'; // old plan canceled, new plan starts now
      }
      RecurlySubscription::changeSubscription($sub->account_code, $when, null, $nickles, 0.05); 

    } else {
      if ($sub->is_new == true) {
        $id = $sub->user->id;
        $sub = $sub->create();
        set_user_monthly_account($id, $sub->account_code);
      }

      $payment_url = "$monthly_api/subscribe/monthly/$sub->account_code/$sub->username?quantity=$nickles";
      header("Location: $payment_url&return_url=".urlencode($backto)); exit();
    }
  }

  header("Location: $backto"); exit();
}

////////////////////////////////////////////////////////////////////////////////

function amstore_xmlobj2array($obj, $level=0) {
    $items = array();
    if(!is_object($obj)) return $items;
    $child = (array)$obj;
    if(sizeof($child)>1) {
        foreach($child as $aa=>$bb) {
            if(is_array($bb)) {
                foreach($bb as $ee=>$ff) {
                    if(!is_object($ff)) {
                        $items[$aa][$ee] = $ff;
                    } else
                    if(get_class($ff)=='SimpleXMLElement') {
                        $items[$aa][$ee] = amstore_xmlobj2array($ff,$level+1);
                    }
                }
            } else
            if(!is_object($bb)) {
                $items[$aa] = $bb;
            } else
            if(get_class($bb)=='SimpleXMLElement') {
                $items[$aa] = amstore_xmlobj2array($bb,$level+1);
            }
        }
    } else
    if(sizeof($child)>0) {
        foreach($child as $aa=>$bb) {
            if(!is_array($bb)&&!is_object($bb)) {
                $items[$aa] = $bb;
            } else
            if(is_object($bb)) {
                $items[$aa] = amstore_xmlobj2array($bb,$level+1);
            } else {
                foreach($bb as $cc=>$dd) {
                    if(!is_object($dd)) {
                        $items[$obj->getName()][$cc] = $dd;
                    } else
                    if(get_class($dd)=='SimpleXMLElement') {
                        $items[$obj->getName()][$cc] = amstore_xmlobj2array($dd,$level+1);
                    }
                }
            }
        }
    }
    return $items;
}

function get_refcode_from_donor($donor_id, $force_new = false) {
  $type = ACCT_TYPE_DISCOUNT; //referrer code 

  if (empty($donor_id))
    return NULL;

  $code = "";
  if (!$force_new)
    $code = get_acct_code_by_donor($donor_id, $type);

  if (empty($code)) {
    $acct_id = insert_donation_acct($donor_id,0,$type);
    $code = get_acct_code_by_id($acct_id);
  }

  return $code;
}

function get_refcode_from_user($user_id) {
  global $wpdb;

  $donor_id = get_donorid_by_userid($user_id);

  return get_refcode_from_donor($donor_id);
}

function get_acct_details($code) {
  global $wpdb;

  return $wpdb->get_row($wpdb->prepare(
    "SELECT dg.firstName,dg.lastName,da.params,da.donationAcctTypeId as type
     FROM donationGiver dg
     JOIN donationAcct da ON da.donorId=dg.id
     WHERE da.code=%s", $code), OBJECT);
}

function get_refcode_details($code) {
  global $wpdb;

  $details = $wpdb->get_row($wpdb->prepare(
    "SELECT dg.firstName,da.params 
      FROM donationGiver dg
      JOIN donationAcct da ON da.donorId=dg.id
      WHERE da.code=%s", $code), OBJECT);

  $params = json_decode($details->params);
  unset($details->params);
  if ($params != NULL) {
    $details->amount = $params->discount;
    $details->message = $params->message;
  } else {
    // Assume it's a referral code
    $details->amount = 5;
    $details->message = "Welcome to SeeYourImpact.org!"; // (referral from $details->firstName)";
  }

  return $details;
}

function get_user_email($donorID, $mailType='') {
  global $wpdb;
  $sql = $wpdb->prepare("SELECT
      dg1.email as donor_email,
      wu.ID as user_id, 
      wu.user_email as user_email,
      dg2.email as main_email
      FROM donationGiver dg1
      LEFT OUTER JOIN wp_users wu ON (dg1.user_id=wu.ID)
      LEFT OUTER JOIN donationGiver dg2 ON (dg2.user_id=wu.ID AND dg2.main=1)
      WHERE dg1.ID=%d",$donorID);

  $u = $wpdb->get_row($sql,ARRAY_A);
  $user_id = intval($u['user_id']);
  if (!empty($mailType) && get_user_meta($user_id, "no_{$mailType}_email", true) == true) {
    return null; // Don't send the mail
  }

  if(!empty($u['user_email']) && is_email($u['user_email'])) {
    return $u['user_email'];
  } else if(!empty($u['main_email']) && is_email($u['main_email'])) {
    return $u['main_email'];
  } else if(!empty($u['donor_email']) && is_email($u['donor_email'])) {
    return $u['donor_email'];
  } else {
    //debug('SQL TO USER: '.$sql."\nUSER: ".print_r($u,true),true,"ERROR GETTING USER EMAIL");
    return null;
  }
}

function get_user_emails($user_id, $is_donor=false) {
  global $wpdb;

  $emails = array();
  $donor_id = 0;

  if($is_donor) {
    $donor_id = $user_id;
    $user_id = $wpdb->get_var($wpdb->prepare("SELECT wu.ID
      FROM donationGivers dg JOIN wp_users wu ON dg.user_id=wu.ID
      WHERE dg.ID=%d",$donor_id));
  }

  $user_email = $wpdb->get_var($wpdb->prepare("SELECT wu.user_email
  FROM wp_users WHERE wu.ID=%d",$user_id));

  if(is_email($user_email)) {$emails[] = $user_email;}

  $dgs = $wpdb->get_results($wpdb->prepare("SELECT dg.ID, dg.email,
    FROM wp_users wu, JOIN donationGivers dg ON dg.user_id=wu.ID
    WHERE wu.ID=%d",$user_id),ARRAY_A);

  if (!empty($dgs)) {
    foreach ($dgs as $dg) {
      if (in_array($dg['email'],$emails)===false && is_email($dg['email'])) {
        $emails[] = $dg['email'];
      }
    }
    $emails = get_dc_emails($emails,$dg['ID']);
  } else if($is_donor) {
    $emails = get_dc_emails($emails,$donor_id);
  }

  return $emails;
}

function get_dc_emails($emails,$donor_id) {
  global $wpdb;

  $dcs = $wpdb->get_rows($wpdb->prepare("SELECT dc.value,
    FROM donation d, donationContact dc
    WHERE  d.donorID=%d AND dc.donationID=d.donationID AND dc.type='email'",
    $donor_id),ARRAY_A);

  if (!empty($dcs)) foreach ($dcs as $dc) {
    if (in_array($dc['value'],$emails)===false && is_email($dc['value'])) {
      $emails[] = $dc['value'];
    }
  }

  return $emails;
}

////////////////////////////////////////////////////////////////////////////////

function integrate_user_donor($userID, $donorinfo) {
  global $payment_debug;
  global $wpdb;

  if (empty($donorinfo)) {
    if ($userID==0) { 
      debug("DONOR CREATION ERROR",true,"DONOR CREATION ERROR"); 
      return NULL;
    }
    if (isset($payment_debug)) {
      return NULL; //this is a duplicate cron job on payment
    }	  
  }

  if ($userID==0) { //user not logged in//
    dp("\nUSER IS NOT LOGGED IN");

    if($donorinfo->validated) { 
      //not logged in but verified email, may merge
      dp("BILLING EMAIL IS VERIFIED: $donorinfo->email");
      $donor = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM donationGiver
         WHERE email=%s AND (verified=1 OR validated=1)",
        $donorinfo->email));
    } 

    if ($donor == NULL) {
      dp("BILLING EMAIL UNVERIFIED ");
      $wpdb->query($wpdb->prepare(
        "INSERT INTO donationGiver
         (email, firstName, lastName, verified, validated, main, sendUpdates)
        VALUES (%s,%s,%s,%d,%d,0,1)",
        $donorinfo->email,$donorinfo->first,$donorinfo->last,
        $donorinfo->validated, $donorinfo->validated));
      $donorID = $wpdb->insert_id;
      dp("INSERTED NEW DONOR #$donorID");
    } else {
      $donorID = $donor->ID;
      $userID = $donor->user_id;
      dp("BILLING EMAIL FOUND ON DONOR #$donorID USER #$userID");
    }

    if ($userID == 0) {
      
      $existing_user_same_email = email_exists($donorinfo->email);
      if ($existing_user_same_email >0) {
        $existing_user_same_email = get_userdata($existing_user_same_email);
        $username = $existing_user_same_email->user_login;
        $userID = $existing_user_same_email->ID;        

        dp("UNVERIFIED EMAIL FOUND ON USER #$userID ($username) - MERGING");  

      } else {      
        list($username, $userID) = createWpAccount($donorinfo->email, $donorinfo->first, $donorinfo->last);
        dp("CREATED USER #$userID ($username)");  
      }
      $wpdb->update("donationGiver", array("user_id" => $userID), array("ID" => $donorID));
    }

  } else { //user logged in/////////////////////////////////////////////////////

    $user=get_userdata($userID);
    if($user!=NULL) { //user exists

      if (empty($donorinfo)) {
        $donorinfo = new stdClass;  
        $user_n = explode(" ",$user->display_name);		
        $donorinfo->first = $user_n[0];
        $donorinfo->last = (empty($user_n[1])?'':$user_n[1]);
        $donorinfo->email = $user->user_email;
      }

      $new_user = array('ID'=>$userID);
      if (empty($user->user_firstname)) {dp("UPDATE USER FIRST NAME"); $new_user['user_firstname'] = $donorinfo->first; }
      if (empty($user->user_lastname)) {dp("UPDATE USER LAST NAME"); $new_user['user_lastname'] = $donorinfo->last; }
      if (empty($user->user_email)) {dp("UPDATE USER EMAIL"); $new_user['user_email'] = $donorinfo->email; }
      if (count($new_user) > 1) {dp("SAVE USER CHANGES"); $x = wp_update_user($new_user);
        if (is_wp_error($x)) {dp("- ".explode('\n- ',$x->get_error_messages()));}
        $user=get_userdata($userID);
      }
    } else {
      return NULL;
    }

    dp("\nUSER IS LOGGED IN #".$userID);

    //look up a donor for this user with the same email
    $donor_x = $wpdb->get_row($wpdb->prepare("SELECT * FROM donationGiver
      WHERE user_id=%d AND email=%s",$userID,$donorinfo->email));

    if ($donor_x==NULL) { //no donor of this user with the email

      //Look up a donor for other user with the same email
      $donor_y = $wpdb->get_row($wpdb->prepare("SELECT * FROM donationGiver
        WHERE user_id<>%d AND email=%s",$userID,$donorinfo->email));

      if($donor_y==NULL) { //no donor of other user with the email
      
        $wpdb->query($wpdb->prepare("INSERT INTO donationGiver
        (user_id,email,firstName,lastName,verified,validated,main,sendUpdates)
        VALUES (%d,%s,%s,%s,1,1,0,1)",
        $userID,$donorinfo->email,(empty($user->user_firstname)?$donorinfo->first:$user->user_firstname),
          (empty($user->user_lastname)?$donorinfo->last:$user->user_lastname)));
        $donorID_x = $wpdb->insert_id;
        dp("INSERTING DONOR #".$donorID_x." FOR USER #".$userID." W EMAIL: ".$donorinfo->email);

      } else {
        $donorID_y = $donor_y->ID;
        dp("DONOR #".$donorID_y." W PAY EMAIL: ".$donorinfo->email." EXISTS ON OTHER USER#".$donor_y->user_id);
      }
      
    } else {
      $donorID_x = $donor_x->ID;
      dp("DONOR #".$donorID_x." W PAY EMAIL: ".$donorinfo->email." IS ALREADY ON USER");
    }

    //look for main donor of the user
    $donor = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM donationGiver
      WHERE user_id=%d AND main=1",$userID));

    if($donor!=NULL) { //if main donor found
      $donorID = $donor->ID;
      dp("USER MAIN DONOR FOUND #".$donorID);
    } else { //if main donor not found
      dp("USER MAIN DONOR NOT FOUND");
      $donor = $wpdb->get_row($wpdb->prepare("SELECT * FROM donationGiver
        WHERE user_id=%d AND email=%s ",$userID,$user->user_email));
      if($donor!=NULL) {
        $donorID = $donor->ID;
        dp("USER DONOR WITH SAME EMAIL FOUND #".$donorID);
        $wpdb->query($wpdb->prepare("UPDATE donationGiver set main=1
          WHERE user_id=%d AND ID=%d",$userID,$donorID));
      } else {
        $donorID = user_main_donor_sync($userID,0);
        dp("NO DONOR HAS ".$user->user_email.", INSERT NEW #".$donorID);

        $donor = $wpdb->get_row($wpdb->prepare(
          "SELECT * FROM donationGiver WHERE ID=%d",$donorID));

      }
    }

    ////////

    if(empty($donor->lastName) && !empty($donorinfo)) { // donor has no last name
      $wpdb->query($wpdb->prepare("UPDATE donationGiver SET firstName=%s, lastName=%s 
        WHERE ID=%d", $donorinfo->first, $donorinfo->last,$donor->ID));
      dp("UPDATING DONOR NAME FROM '".$donor->firstName."' TO '".
        $donorinfo->first." ".$donorinfo->last."'");
    } 

  } //end if user logged in/////////////////////////////////////////////////////

  $address = paypal_to_address($donorinfo);
  if (isset($address['address'])) {
    dp("UPDATING DONOR #$donorID " . json_encode(array_filter($address)));
    if (!$wpdb->update("donationGiver", $address, array('ID' => $donorID))) 
      dp("UPDATING DONOR FAILED");
  } else {
    dp("NOT UPDATING DONOR #$donorID " . json_encode(array_filter($address)));
  }

  return array($donorID, $userID);
}
function paypal_to_address($p) {
  $p = (object)$p;
  return array(
    'address' => $p->address1,
    'address2' => $p->address2,
    'city' => $p->city,
    'state' => $p->state,
    'zip' => $p->postal_code,
    'phone' => $p->phone_number,
  );
}

function user_main_donor_sync($userID,$donorID=0,$keep_donor=false) {
  global $wpdb;

  //sync with user means that the donor is main

  if (!$keep_donor) { //push from user to donor update
    $user = get_userdata($userID);
    if(empty($user->ID)) return 0;

    if($donorID==0) { //if donorID not specified, assume update current main

      $donorID = intval($wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM donationGiver WHERE main=1 AND user_id=%d",$userID)));

      if($donorID==0) { //if there is no current main, look for secondary with same email
        $donorID = intval($wpdb->get_var($wpdb->prepare(
          "SELECT ID FROM donationGiver WHERE email=%s AND user_id=%d",
          $user->user_email,$userID)));
      }
      
      if($donorID==0) {//if there is still no donor found, insert a new one
        $wpdb->query($wpdb->prepare("INSERT INTO donationGiver
          (email,firstName,lastName,user_id,sendUpdates,main,verified,validated)
          VALUES (%s,%s,%s,%d,1,1,1,1)",
          $user->user_email,$user->first_name,$user->last_name,$userID));
        $donorID = $wpdb->insert_id;

      } else {
        
        $wpdb->query($wpdb->prepare("UPDATE donationGiver
          SET email=%s, firstName=%s, lastName=%s, user_id=%d, sendUpdates=1,
          main=1, verified=1, validated=1 WHERE ID=%d",
          $user->user_email,$user->first_name,$user->last_name,$userID,$donorID));

      }


    } else { //if donorID specified, force it to be main, and de-main others

      $wpdb->query($wpdb->prepare("UPDATE donationGiver
        SET email=%s, firstName=%s, lastName=%s, user_id=%d, sendUpdates=1,
        main=1, verified=1, validated=1 WHERE ID=%d",
        $user->user_email,$user->first_name,$user->last_name,$userID,$donorID));

      $wpdb->query($wpdb->prepare("UPDATE donationGiver SET main=0
        WHERE user_id=%d AND ID<>%d",$userID,$donorID)); //de-main others
    }
  } else { //push from donor to user update

    if($donorID==0) return 0;

    $donor = $wpdb->get_row($wpdb->prepare("SELECT * FROM donationGiver
      WHERE ID=%d",$donorID));

    if($donor==NULL) return 0;

    $new_user = array('ID'=>$userID);
    $new_user['user_firstname'] = $donor->firstName;
    $new_user['user_lastname'] = $donor->lastName;
    $new_user['user_email'] = $donor->email;
    wp_update_user($new_user); //update user information

    //reset main flag
    $wpdb->query($wpdb->prepare("UPDATE donationGiver SET main=1
      WHERE ID=%d",$donorID));
    $wpdb->query($wpdb->prepare("UPDATE donationGiver SET main=0
      WHERE user_id=%d AND ID<>%d",$userID,$donorID));
  }

  return $donorID;
}

function get_sibling_donors($donor_id,$use_email=false) {
  global $wpdb;
  return $wpdb->get_col($wpdb->prepare(
    "SELECT DISTINCT dg.ID FROM donationGivers dg
    JOIN donationGivers dgm ON (dgm.user_id=dg.user_id".
    ($use_email?" OR (dgm.email=dg.email AND dg.user_id=0)":"").")
    WHERE dg.ID<>%d AND dgm.ID=%d ",$donor_id,$donor_id));
}

function get_donor_by_id($id) {
  global $wpdb;
  return $wpdb->get_row($wpdb->prepare("SELECT * FROM donationGiver WHERE id=%d", $id));    
}

function get_donor_by_user_id($user_id) {
  global $wpdb;
  return $wpdb->get_row($wpdb->prepare("SELECT * FROM donationGiver WHERE user_id=%d ORDER by main,ID", $id));    
}

function get_acct_user_by_trans($trans_id) {
  global $wpdb;

  return $wpdb->get_row($wpdb->prepare("SELECT u.* FROM donationAcctTrans dat 
JOIN donationAcct da ON (dat.donationAcctId = da.id) JOIN donationGiver dd ON (da.donorId = dd.ID)
JOIN wp_users u ON (dd.user_id = u.ID) WHERE dat.id = %d",$trans_id));
}

function dr($str) {
  global $dr;
  $dr .= "\n".$str;
}

function dr_end($str='',$sbj='') {
  global $dr;
  dr($str);
  debug($dr,true,"DEBUG RECURLY".$sbj);
}

add_action('user_donor_register','create_user_donor_on_register');

function create_user_donor_on_register($user_id) {
  if($user_id>0) {  
	integrate_user_donor($user_id, NULL);  
  }
}

?>
