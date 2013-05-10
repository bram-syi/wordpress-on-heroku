<?

include_once('payments.php');

nocache_headers();
ensure_logged_in_admin();


function get_account($accountID) {
  $accountID = get_acct_id_by_code_or_id($accountID);
  return get_donation_account($accountID);
}

function get_item($itemID) {
  global $wpdb;

  $i = (object)$wpdb->get_row($sql = $wpdb->prepare(
  "select g.id as gift_id,g.displayName, g.unitAmount as price, 1 as quantity, '' as ref,g.unitsWanted,g.blog_id
   from gift g
   where g.id=%d
   limit 1", $itemID));

  return $i;
}


$donorID = intval($_REQUEST['donor']);
$items = as_ints($_REQUEST['item']);
$amount = floatval($_REQUEST['amount']);
$tip = floatval($_REQUEST['tip']);
$code = $_REQUEST['code'];
$event = intval($_REQUEST['event']);

$account = get_account($code);
if ($account->donationAcctTypeId == 7 || $account->donationAcctTypeId == 4) { // To allocate
  $params = json_decode($account->params);

  $donorID = $account->donorId;
  $event = eor($account->event_id, $event);
  $tip_rate = $params->tip_rate;
  $tip = intval($tip_rate * 100) . '%';
  $bal = ($account->balance / (1 + $tip_rate));

  $msg = "Account $code: balance " . as_money($account->balance) . " (" . as_money($bal) ;
  if ($tip_rate > 0)
    $msg .= " + " . ($tip_rate * 100) . "% tip";
  if ($account->event_id > 0) {
    $url = get_permalink($account->event_id);
    $msg .= " for <a target='fundraiser' href='$url'>" . str_replace(SITE_URL, '', $url) . "</a>";
    $msg .= " tag [" . get_fr_tags($account->event_id) . "]";
  }
  $msg .= ")<br>";
}

$item_array = array();

if ($_POST) {
  $qty = $_REQUEST['qty']; 
  $gift = $_REQUEST['gift_id'];
  $any = $_REQUEST['any'];

  for ($i = 0; $i < count($qty); $i++) {
    if ($any[$i] > 0) {
      $item = get_item(get_agg_var_gift($gift[$i]));
      if ($item == NULL) {
        echo "<b>Cannot give-any toward gift #" . $gift[$i] . "!</b>";
        continue;
      }
      $item->giveany = TRUE;
      $item->price = $any[$i];
      $item->event = $event;
      $item_array[] = $item;
    }

    if ($qty[$i] > 0) {
      $item = get_item($gift[$i]);
      $item->quantity = $qty[$i];
      $item->event = $event;
      $item_array[] = $item;
    }

  }
}

if ($_REQUEST['go'] != NULL && count($item_array) > 0) {
  ?><div class="output"><?
  global $user_login;
  $order = build_order($account, $donorID, $item_array, $tip, $event, FALSE, "allocated by $user_login", TRUE);

  if ($order != NULL && $_REQUEST['go'] == 'Go') {

/*
pre_dump($order);
die;
*/

    processOrder($order);
    ?><b style="color:green;">Submitted!</b><?
    $submitted = TRUE;
  } else {
    ?><b>Not processed yet - when you're ready, hit 'Go'</b><?
  }
  ?></div><?
}

if (empty($donorID))
  $donorID = '';
if (empty($tip))
  $tip = '';

?>

<form method="POST" action="">
<div class="topbar">
  <?= $msg ?>
  <? if (!$submitted) { ?>
  Account #<input type="text" size="11" name="code" value="<?= esc_attr($code) ?>" /><a target="_new" href="/wp-admin/admin.php?page=donation-acct" class="lookup">?</a>
  spends <span class="spend">$0</span>
  plus tip $<input type="text" size="5" name="tip" value="<?= esc_attr($tip) ?>" />
  for donor #<input type="text" size="5" name="donor" value="<?= esc_attr($donorID) ?>" ><a target="_new" href="/database/fix_donor.php" class="lookup">?</a>
  <input type="submit" name="go" value="Test" />
  <input type="submit" name="go" value="Go" />
  <? } else { ?>
    <a class="restart" href="<?= remove_query_arg('go') ?>">RESTART</a>
  <? } ?>
</div>

<?
if (!$submitted) {
if ($event > 0) {
  $gifts = get_gifts_by_event($event, 999);

  $g = array();
  $tow = array();
  foreach ($gifts as $gift) {
    $id = $gift['id'];
    $g[$id] = $gift;
    $tid = $gift['towards_gift_id'];
    if ($tid <= 0) {
      $tow[$id] = array();
      continue;
    }
    if ($tow[$tid] == null)
      $tow[$tid] = array();
    $tow[$tid][] = $id;
  }

  if (!($account->donationAcctTypeId == 7 && $event > 0)) {
  ?>
    <div class="giftrow">
      <div class="giftdesc">"Give Any Amount"</div>
      <div class="actions">
        <input type="hidden" name="qty[]" value="0">
        <input type="hidden" name="gift_id[]" value="<?= CART_GIVE_ANY ?>">
        $<input class="any" type="text" name="any[]" style="width:70px" value="">
      </div>
      <div style="clear:both;"></div>
    </div>
  <?
  }

  $i = 0;
  foreach ($tow as $id=>$a) {
    if (!isset($g[$id]))
      $g[$id] = get_gift_where("g.id = $id", array('show_all' => true));
    $gift = $g[$id];

    $need = $gift['unitsWanted'];
    $price = $gift['unitAmount'];
    $name = get_blog_domain($gift['blog_id']) . ": " . stripslashes($gift['displayName']) . " ($$price)";
    $needed = $need > 0 ? '' : ' not-needed';
    ?>
    <div class="giftrow newrow <?=$needed?>">
      <div class="giftdesc"><?= xml_entities($name) ?> </div>
      <div class="actions">
        <input type="hidden" name="gift_id[]" value="<?= $id ?>">
        qty: <input class="qty" id="qty_<?=$id?>" type="number" min="0" name="qty[]" style="width:50px" value="<?= intval($qty[$i]) ?>">
        <label class="plus" for="qty_<?=$id?>">+</label>
        any: $<input class="any" id="any_<?=$id?>" type="text" name="any[]" style="width:40px" value="<?=$any[$i]?>"> of $<b class="cost"><?= $gift['unitAmount'] ?></b>
        <? if ($gift['current_amount'] > 0) { ?>
          ($<?= $price - $gift['current_amount'] ?> left)
        <? } ?>
        <? if ($need <= 0) { ?> (<span style="color:red;">OUT</span>) <? } ?>
      </div>
        <? foreach ($a as $id) {
            $gift = $g[$id];
            $need = $gift['unitsWanted'];
            $price = $gift['unitAmount'];
            $name = stripslashes($gift['displayName']) . " ($$price)";
            $i++;
            ?>
              <div style="clear:both;"></div>
            </div>
            <div class="giftrow <?=$needed?>">
              <div class="giftdesc" style="padding-left: 20px;"><?= xml_entities($name) ?> </div>
              <div class="actions">
                <input type="hidden" name="gift_id[]" value="<?= $id ?>">
                qty: <input class="qty" id="qty_<?=$id?>" type="number" min="0" name="qty[]" style="width:50px;" value="<?= intval($qty[$i]) ?>">
                <label class="plus" for="qty_<?=$id?>">+</label>
                ($<span class="cost"><?= $gift['unitAmount'] ?></span>)
                <input type="hidden" name="any[]" value="<?=$any[$i]?>">
              </div>
            <?
          }
        ?>
      <div style="clear:both;"></div>
    </div>
    <?
    $i++;
  }
}
} // !submitted
?>

</form>

<style>
body { margin: 0; padding: 0; padding-top: 80px; }
.topbar { font-size: 15px; position: fixed; top:0; z-index: 1; padding: 10px; height: 50px; background: #f6f6f6; width: 700px; }
form { font: 9pt Arial; }
.error { color: red; font-weight: bold; }
.lookup { display: inline-block; 
  font-size: 10pt;
  padding: 3px;
  background: #ACF;
  color: white;
  font-weight: bold;
  text-decoration: none;
  position: relative;
  left: -1; top: -1;
}
.spend { font-size: 110%; font-weight: bold; }
.output {
  font: 15px Arial,Helvetica;
  padding: 10px;
  background: #eef;
  width: 700px;
}

.newrow {
  border-top: 1px solid #eee;
}
.giftrow {
  padding-top: 5px;
  width: 700px; 
  margin: 10px 20px;
}
.not-needed {
  color: #aaa;
}
.giftdesc {
  float: left;
  width: 300px;
  font-size: 15px;
  padding-top: 6px;
}
.actions {
  width: 350px; 
  float: right;
}
.plus {
  margin: 0 20px 0 5px;
  padding: 0 4px;
  display: inline-block;
  background: #888;
  color: white;
  font-size: 12px;
  cursor: pointer;
}
form input {
  font-size: 16px;
  padding: 1px 0;
}
.restart {
  padding: 3px;
  display: block;
  background: blue;
  color: white;
  width: 200px;
  text-align: center;
  margin-top: 10px;
}
</style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script>
$(function() {
  $(".plus").on('click', function(ev) {
    var id = $(this).attr('for');
    var el = $("#" + id);
    el.val(parseInt(el.val(), 10) + 1);
    recalc();
  });
  function recalc() {
    var total = 0;
    $(".giftrow").each(function(i) {
      var qty = parseInt($(this).find('input.qty').val(), 10) || 0;
      var any = parseFloat($(this).find('input.any').val()) || 0;
      var cost = parseFloat($(this).find('.cost').text()) || 0;
      total += (qty * cost) + any;
    });
    $(".spend").text("$" + total);
  }
  recalc();
  $("form").on('input paste change', 'input', recalc);
});
</script>
