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
  $gc1->details->codes = array('ABCDEF','GHIJK');
  $gc1->price = 50;
  $gc1->itemCount = 2;
  if (is_enabled("gcbuy"))
    $blocks['gcs'][] = $gc1;
  
  $gc2=new stdClass;
  $gc2->recipient->name = 'Another recipient';
  $gc2->recipient->email = 'recipient2@email.com';
  $gc2->details->message = 'Hi check this out!';
  $gc2->details->codes = array('ABCDEF');
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

  $vg3=new stdClass;
  $vg3->price = 200;
  $vg3->blog_id = 1;  

  if (is_enabled("giveany")) {
    $blocks['vargifts'][] = $vg1;
    $blocks['vargifts'][] = $vg2;
    $blocks['vargifts'][] = $vg3;
  }

  $d1=new stdClass;
  $d1->price = -100;
  if (is_enabled("gcuse"))
    $blocks['discounts'][] = $d1;

  $n->build_thankyou_content($blocks);
  echo $n->get_finished_content(1);

} else if (isset($_GET['impactcard'])) {

  $n = new Notification();

  if(!empty($_GET['impactcard'])) {
	  

	$da = get_donation_account(get_acct_id_by_code($_GET['impactcard']));
	$da_params = json_decode($da->params,true);    


	if (!empty($da_params)) {

      list($donor,$d_type) = get_donor_info_by_acct($da->id);
	
	  $code = $_GET['impactcard'];
	  $r_name = trim($da_params['recipient']['first_name']." ".$da_params['recipient']['last_name']);
	  $r_email = $da_params['recipient']['email'];
	  $s_name = trim($donor['firstName']." ".$donor['lastName']); 
	  $s_email = $donor->email;
  
	  $subject = "Purchased: ".$da->balance." (electronic delivery)";
	  if (!empty($r_name)) $body .= "<br><br>for ".$r_name." (".$r_email.")";
	
	  $icn = new Notification();
	  $icn->recipient_name = $r_name;
	  $icn->recipient_email = $r_email;
	
	  if(empty($s_name)) $s_name = 'A donor';

	  $args = array(array($code),$code,array($code),
		"a ".as_money($da->balance)." Impact Card",as_money($da->balance),
		as_html($r_name),as_html($r_name),as_html($r_email),
		$gcMsg, as_html($s_name), as_html($s_email));
//pre_dump($args);	
	} else {
	  die('NO PARAMS');	
	}

//exit();	  
  } else {
	$n->recipient_name = 'Friend';
	$n->recipient_email = 'recipient@email.com';
	$args = array('ABCDEF,ABCDEG','ABCDEF',array('ABCDEF','ABCDEG'),
	  '2 $120 Impact Cards','$120','John Doe','John','john@doe.com',
	  'Hi there, check this out!
	  I think you will like to use it!','Jane Doe','jane@doe.com');
  }

  $n->build_impactcard_content($args);
  echo $n->get_finished_content();



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
