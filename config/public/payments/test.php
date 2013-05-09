<?
//require_once('../wp-load.php');

//require_once('payments.php');

//do_invite_process();
//for($i=4000; $i<=4500; $i++) { echo 'urip.yosia+a'.$i.'@gmail.com<br/>'; }
//pre_dump(get_campaign_donors(5633));
//pre_dump(get_campaign_invitees(5633));
//$txn = get_cart_txn(261699894228492);
//print_r($txn);
//pre_dump(json_decode(($txn->txnData)));
//$txn = get_cart_txn('231214852884156-00010-1');
//pre_dump(json_decode(($txn->txnData)));

//global $blog_id;
//if($blog_id > 1)
//switch_theme('syi','charity');


?>
<?
include_once('../wp-load.php');
include_once('../wp-includes/syi/syi-includes.php');

nocache_headers();

function is_enabled($p) {
  if (!isset($_GET[$p]))
    return FALSE;
  $g = $_GET[$p];
  return (empty($g) || $g == "yes" || $g == "on");
}

if (isset($_GET['thankyou'])) {

  $id = intval($_GET['thankyou']);
  if ($id == 0)
    $id = 3256;

  $n = new Notification($id, 11);

  $blocks = array();

  $gc1=new stdClass;
  $gc1->recipient->name = 'A recipient';
  $gc1->recipient->email = 'recipient@email.com';
  $gc1->details->message = 'Hi this is for you!';
  $gc1->price = 50;
  $gc1->itemCount = 2;
  if (is_enabled("gcbuy"))
    $blocks['gcs'][] = $gc1;
  
  $gc2=new stdClass;
  $gc2->recipient->name = 'Another recipient';
  $gc2->recipient->email = 'recipient2@email.com';
  $gc2->details->message = 'Hi check this out!';
  $gc2->price = 75;
  if (is_enabled("gcbuy"))
    $blocks['gcs'][] = $gc2;
  
  $vg1=new stdClass;
  $vg1->price = 100;
  $vg1->event_id = 5979;  
  $vg1->blog_id = 1;  

  $vg2=new stdClass;
  $vg2->price = 100;
  $vg2->blog_id = 3;  


  if (is_enabled("giveany")) {
    $blocks['vargifts'][] = $vg1;
    $blocks['vargifts'][] = $vg2;
  }

  $d1=new stdClass;
  $d1->price = -100;
  if (is_enabled("gcuse"))
    $blocks['discounts'][] = $d1;

  $n->build_thankyou_content($blocks);
  echo $n->get_finished_content(1);

} else if (isset($_GET['taxinfo'])) {

  $n = new Notification(0,0,51,0);
  $n->tpl_file = 'thankyou_tpl.html';
  //$n->build_taxinfo_content();
  echo $n->get_finished_content();

} else if (isset($_GET['invite'])) {

  if(!empty($_GET['invite'])) $context = $_GET['invite'];

  $n = new Notification(0,0,20,0);
  $n->recipient_name = 'Friend';
  $n->recipient_email = 'recipient@email.com';
  $n->build_invite_content($context);
  echo $n->get_finished_content();

}

?>
