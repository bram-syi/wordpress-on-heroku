<?
/*
Plugin Name: Payments
Plugin URI: http://seeyourimpact.org/
Description: Payment administration functionalities, including: manual payment terminal, list of all incoming/outgoing transactions. This does not include the sitewide settings. FOR PAYMENT MODE FOR PROVIDERS AND SUBSITES PLEASE SEE SITE WIDE PLUGIN.
Author: Yosia Urip
Version: 1.0

Author URI: http://seeyourimpact.org/
*/

add_action('admin_menu', 'add_payment_admin_menu');
define('DA_HOME','wpmu-admin.php');
define('DA_CMD_BASE',DA_HOME."?page=payment-admin");

function add_payment_admin_menu(){
  //add_submenu_page(DA_HOME, __('Payments', 'payment-admin'), 
	//__('Payments', 'payment-admin'), 'manage_network', __('Payments', 'payment-admin'), 'show_payment_admin_page');
}

function show_payment_admin_page(){
  global $wpdb;
  $this_url = $_SERVER['REQUEST_URI'];
  $ret = '';
  $ret .= '<script type="text/javascript" src="../wp-includes/js/jquery/jquery.js"></script>';
  $ret .= '<h2>Manual Payment</h2>';  
  $ret .= '<form id="manualDonationForm" action="'.$this_url.'" method="post">';

  //Insert Payment Form
  
  $charity_id = @intval($_REQUEST['charity_id']);  
  $charity = $wpdb->get_row("SELECT * FROM wp_blogs WHERE blog_id = '".$charity_id."'");

  if($charity == NULL) {

    $ret .= '<div>';
    $ret .= '<div id="charity_field"><label class="field_title">Charity: </label>'
	  . build_charity_dropdown($charity).'</div>';  
    $ret .= '<div><p><input type="submit" id="submit" name="submit" value="Submit"/></p></div>';
    $ret .= '</div>';

  } else {
	$gift_id = @intval($_REQUEST['gift_id']);  
	$gift = $wpdb->get_row("SELECT * FROM gift WHERE blog_id = '".$charity_id."' AND id = '".$gift_id."'");
		
    if($gift == NULL) {
  
	  $ret .= '<input type="hidden" name="charity_id" value="'.$charity_id.'">';
	  $ret .= '<div>';
      $ret .= '<div class="field_row"><label class="field_title">Charity: </label>'
	    . $charity->domain.'</div>';
	  $ret .= '<div class="field_row"><label class="field_title">Gift: </label>'
	    . build_gift_dropdown($charity_id).'</div>';
      $ret .= '<div class="field_row"><label class="field_title">Quantity: </label>'
	    . '<input type="text" name="quantity" /></div>';
      $ret .= '<div class="field_row"><label class="field_title">First Name: </label>'
	    . '<input type="text" name="first_name" /></div>';	
      $ret .= '<div class="field_row"><label class="field_title">Last Name: </label>'
	    . '<input type="text" name="last_name" /></div>';	  	
      $ret .= '<div class="field_row"><label class="field_title">Email: </label>'
	    . '<input type="text" name="email" /></div>';	  
      $ret .= '<div class="field_row"><p><input type="submit" id="submit" name="submit" value="Submit"/></p></div>';
      $ret .= '</div>';


    } else {
	
	$quantity = intval($_REQUEST['quantity']);
	
	  $ret .= '<input type="hidden" name="charity_id" value="'.$charity_id.'">';
	  $ret .= '<input type="hidden" name="gift_id" value="'.$gift_id.'">';  
      $ret .= '
<input id="Item" type="hidden" name="item_name_1" value="'.$gift->item_name.'"/>
<input id="Tip" type="hidden" name="item_name_2" value="Contribution to SeeYourImpact.org"/>
<input id="custom" type="hidden" name="custom" value="0||1||'.$gift->id.'||"/>
<input id="Amount" type="hidden" name="amount_1" value="'.$amount.'"/>
<input type="hidden" name="os0_1" value="'.$charity->domain.'"/>
<input type="hidden" name="gift" value="'.$gift->id.'"/>
<input type="hidden" name="blog_url" value="'.$charity->domain.'" />
<input type="hidden" name="manual" value="1" />';
    }
  }
  $ret .= '</form>';    

  //List Payments
  
  echo '<style type="text/css">
  .field_row{padding:10px  0;}
  .field_title{float:left; width:150px;}
  .row_cell{float:left;}
  .row{font-size:11px;clear:both;}
  .odd{background:#ccc;}
  .errorMsg{font-weight:bold; color:#c00;}
  .tbl_header{background: #333; color: #ccc}
  .header_row{font-weight:bold;text-align:left;}
  </style>' . $ret;
}

/*********************************************************************************/

function build_charity_dropdown(){
  global $wpdb;
  $sql = 'SELECT blog_id, domain FROM wp_blogs';
  $rows = $wpdb->get_results($sql);
  //onchange="jQuery(\'#manualDonationForm\').submit();"
  $ret = '<select name="charity_id" >';
  $ret .= '<option>------------</option>';
  foreach($rows as $row){
    $ret .= '<option value="'.$row->blog_id.'">'.$row->domain.'</option>';    
  }
  $ret .= '</select>';
  return $ret;
}

function build_gift_dropdown($charity){
  global $wpdb;
  $sql = "SELECT id, unitAmount, displayName ".
    "FROM gift WHERE blog_id = '".$charity."' ";
  $rows = $wpdb->get_results($sql);
  $ret = '<select name="gift_id" size="100">';
  $ret .= '<option>------------</option>';
  foreach($rows as $row){
    $ret .= '<option value="'.$row->id.'">'.$row->displayName.' '.
	  money_format('$%4.2n',$row->unitAmount).'</option>';      
  }
  $ret .= '</select>';
  return $ret;
}

function insert_payment($data, $id = 0){
  global $wpdb;
  //
  //$isTest = false;
//  $testWords = array('sandbox', 'dev1', 'dev2', 'dev3', 
//    'dev4', 'dev5', 'dev6', 'staging', 'test', 'JohnDo', 'yosia+', 'urip.yosia+');

  if($id == 0){
    //foreach($testWords as $word){
	//  if(strpos($raw, $word)!==FALSE) $isTest = true;
	//}
	$wpdb->insert('payment', array('raw' => $data, 'testData'=>(PAYMENT_TEST_MODE?'1':'0')));
    return $wpdb->insert_id;
  } else {
    if(NULL !== $wpdb->get_var("SELECT id FROM payment WHERE id = '".$id."'")){
      $wpdb->update($table, array('raw' => $data), "WHERE id = '".$id."'");	  
	  return $id;
	} else {
	  return false;
	}
  }
}

	
?>
