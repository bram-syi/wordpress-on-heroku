<?
/*
Plugin Name: Site-wide Config Additions
Plugin URI: http://www.seeyourimpact.com/
Version: 1.0
Author: Ishita Chaudhuri, Yosia Urip
Description: Payment control panel for different providers and blogs
Author URI: http://www.seeyourimpact.com/
Instructions: copy into mu-plugins, see line 153 for payment system constants
*/

add_action('admin_menu', 'sw_add_menu', 1); 
define('SW_CMD_BASE',"admin.php?page=".SW_HOME);
define('PAY_MODE_KEY','blog-payment-mode');
define('PAY_PROVIDERS',',creditcard,recurly');

sw_set_payment_variables();

function sw_add_menu(){
  add_menu_page('Site Config', 'Site Config', 'manage_network', SW_HOME, 'sw_page', null, 1);
}

function sw_page(){
  ?><div class="wrap"><?
  switch($_REQUEST['wa']){
    case "set_payment_settings":
      sw_set_provider_details(); break;

    case "show_payment_settings":
      sw_show_provider_details(); break;

    case "set_payment_mode":
      sw_set_payment_mode(); break;

    default: //show payment mode
      sw_show_payment_mode(); break;
  }
  ?></div><?
}

function sw_show_payment_mode(){
?>
  <form action="" method="post">
  <input type="hidden" name="wa" value="set_payment_mode" />
  <h2>Manage Site Wide Settings</h2>
<?
  //Get provider settings from database and display the buttons
  $providers = explode(",",PAY_PROVIDERS);
  foreach($providers as $provider){
    echo sw_show_provider_mode($provider);
  }
?>
  <p class="submit"><input type="submit" name="submit" value="Submit" /></p>
  </form>
<?
}

function sw_show_provider_mode($provider){
  global $wpdb;

  $current_mode = sw_get_payment_mode(true,$provider);
  $down="";$test=""; $live="";
  if($current_mode == "DOWN" || $current_mode == ""){ $down="checked"; }
  else if($current_mode == "TEST"){ $test="checked"; }
  else if($current_mode == "LIVE"){ $live="checked"; }

  $detailsLink = SW_CMD_BASE.
    '&wa=show_payment_settings&amp;provider='.$provider;

return '<h3>'.ucwords($provider==''?'paypal':$provider).'</h3>'
. '<table cellpadding="0" cellspacing="15"  style="background:'
. ($live!=''?'#dfd':($test!=''?'#ffd':'#fdd')).';">
<tr><td><input name="'.$provider.'_mode" type="radio" value="DOWN" '.$down.' /> DOWN </td>
<td><input name="'.$provider.'_mode" type="radio" value="TEST" '.$test.' /> SANDBOX
 <a href="'.$detailsLink.'&amp;type=test'.'">DETAILS</a></td>
<td><input name="'.$provider.'_mode" type="radio" value="LIVE" '.$live.' /> <strong>REAL</strong>
 <a href="'.$detailsLink.'&amp;type=live'.'">DETAILS</a></td></tr></table>';

}

function sw_set_payment_mode(){
  global $wpdb;
  global $user_identity;
  global $user_ID;

  if(isset($_POST) && $_POST['submit'] == 'Submit'){

  //Set provider settings to the database
  $providers = $providers = explode(",",PAY_PROVIDERS);
  $changes = '';
  foreach($providers as $provider){
    $changes .= sw_set_provider_mode($_REQUEST[$provider.'_mode'],$provider,$notes);
  }

  if($changes!=''){
    $notes = $user_identity.'('.$user_ID.'): '. $_REQUEST['notes'];
    sw_log_changes(addslashes($changes),addslashes($notes),'payment mode change');
  }

  }
  //Get the settings
  sw_show_payment_mode();
}

function sw_set_provider_mode($newMode,$provider,$notes){
  global $wpdb;
  if($newMode=='DOWN'){$newMode='';}
  if($provider=='paypal'){$provider='';}
  $change = ''; $sql = '';
  if($newMode=='' || $newMode=='TEST' || $newMode=='LIVE'){
    $curMode=$wpdb->get_var("SELECT current_mode FROM paypal_settings ".
    "WHERE provider='".$provider."'");

    if($curMode!=$newMode){
      $sql="UPDATE paypal_settings SET current_mode='".$newMode."' ".
      "WHERE provider='".$provider."'";
      $wpdb->query($sql);

      $change = "Changed ".($provider==''?'paypal':$provider)." from '".($curMode==''?'DOWN':$curMode)."' ".
      "to '".($newMode==''?'DOWN':$newMode)."'"."\n";
    }
  }

  return $change;
}

function sw_log_changes($changes,$notes,$cat){
  global $wpdb;
  $sql="INSERT INTO sitewideLog (dateTime,changes,notes,category) ".
    "VALUES (NOW(),'".$changes."','".$notes."','".$cat."') ";
  $wpdb->query($sql);
}

function sw_show_provider_details($done=''){
  $provider = $_REQUEST['provider'];
  $type = $_REQUEST['type'];

  $details=sw_get_provider_details($type,$provider,true,$done);
  //echo'<pre>';print_r($_REQUEST);echo'</pre>';
  echo '<h2>'.'Manage '.strtoupper($provider == ''?'Paypal':$provider).
  ' Settings for '.(strtoupper($type)=='TEST'?'SANDBOX':'REAL').' Mode', '</h2>';

?>
  <form action="" method="post" onsubmit="validateSitewideForm(this);">
  <input type="hidden" name="wa" value="set_payment_settings" />
  <input type="hidden" name="type" value="<?= $type?>" />
  <input type="hidden" name="provider" value="<?= $provider?>" />
<table cellpadding="0" cellspacing="5">
<? if($done=="done"){ ?><br/><div id="message" class="updated fade"><p>Options saved successfully.&nbsp;&nbsp;&nbsp;<a href="<?= (SW_CMD_BASE)?>">&laquo; Go Back</a></p></div><? } ?>
<tr valign="top"><th style="text-align:left">Business ID</th>
<td><input name="business_id" type="text" id="business_id" value="<?= $details->business_id?>" size="100" /><br /></td></tr>
<tr valign="top"><th style="text-align:left">Form Action</th>
<td><input name="form_action" type="text" id="form_action" value="<?= $details->form_action?>" size="100" /><br /></td></tr>
<tr valign="top"><th style="text-align:left">Return URL</th>
<td><input name="return_url" type="text" id="return_url" value="<?= $details->return_url?>" size="100" /><br /></td></tr>
<tr valign="top"><th style="text-align:left">Cancel Return URL</th>
<td><input name="cancel_return_url" type="text" id="cancel_return_url" value="<?= $details->cancel_return_url?>" size="100" /><br /></td></tr>
<tr valign="top"><th style="text-align:left">Notify URL</th><td>
<input name="notify_url" type="text" id="notify_url" value="<?= $details->notify_url?>" size="100" /><br /></td></tr>
<tr valign="top"><th style="text-align:left">Verification URL</th>
<td><input name="verify_url" type="text" id="verify_url" value="<?= $details->verify_url?>" size="100" /><br /></td></tr>
<tr valign="top"><th style="text-align:left">BTN Image</th>
<td><input name="btn_image" type="text" id="btn_image" value="<?= $details->btn_image?>" size="100" /><br /></td></tr>
<tr valign="top"><th style="text-align:left">Pixel Image</th>
<td><input name="pixel_image" type="text" id="pixel_image" value="<?= $details->pixel_image?>" size="100" /><br /></td> </tr>

<tr valign="top"><th style="text-align:left">API User</th>
<td><input name="api_user" type="text" id="api_user" value="<?= $details->api_user?>" size="100" /><br /></td> </tr>
<tr valign="top"><th style="text-align:left">API Key</th>
<td><input name="api_key" type="text" id="api_key" value="<?= $details->api_key?>" size="100" /><br /></td> </tr>
<tr valign="top"><th style="text-align:left">API Url</th>
<td><input name="api_url" type="text" id="api_url" value="<?= $details->api_url?>" size="100" /><br /></td> </tr>
<tr valign="top"><th style="text-align:left">API Signature</th>
<td><input name="api_signature" type="text" id="api_signature" value="<?= $details->api_signature?>" size="100" /><br /></td> </tr>
</table>
<p><h4>Please put a detailed note explaining why you make the change:</h4><textarea name="notes" rows="4" cols="90">Just testing</textarea></p>
<p class="submit"><input type="submit" name="submit" value="Submit" />&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" onclick="javascript:window.location='<?= (SW_CMD_BASE) ?>'"/></p>
</form>
<script type="text/javascript">
  //<!--
  function validateSitewideForm(form){
    if(form.notes.value=='') {
      alert('Please insert the notes to explain the change.');
      form.notes.focus();
      return false;
    } else { return true; }
  }
  //-->
</script>
<?
}

function sw_get_provider_details($type,$provider='',$bypass=false,$done=''){
  global $wpdb, $sw_details;
  
  if($sw_details == null) sw_get_all_provider_details();

  //detect if this is a dev machine/not if it is then always default to test
  if(FALSE===strpos(get_bloginfo('url'),'seeyourimpact.org') && !$bypass){
    $type = 'TEST';
  }

  if(is_array($sw_details) && !empty($sw_details) && $done=='')
    foreach ($sw_details as $swd) {
      if ($swd->type == strtoupper($type) && $swd->provider == $provider) {      
        return $swd;    
      } 
    }

  return $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM paypal_settings WHERE type=%s AND provider=%s",
    strtoupper($type), $provider));
}

function sw_get_all_provider_details() {
  global $wpdb, $sw_details;
  $sw_details = $wpdb->get_results("SELECT * FROM paypal_settings");    
}

function sw_set_provider_details(){
  global $wpdb;
  global $user_identity;
  global $user_ID;

  $provider = $_REQUEST['provider'];

  $sql = "UPDATE paypal_settings SET ";
  $sql.= "business_id = '".$_REQUEST['business_id']."', ";
  $sql.= "form_action = '".$_REQUEST['form_action']."', ";
  $sql.= "return_url = '".$_REQUEST['return_url']."', ";
  $sql.= "cancel_return_url = '".$_REQUEST['cancel_return_url']."', ";
  $sql.= "notify_url = '".$_REQUEST['notify_url']."', ";
  $sql.= "btn_image = '".$_REQUEST['btn_image']."', ";
  $sql.= "pixel_image = '".$_REQUEST['pixel_image']."', ";
  $sql.= "verify_url = '".$_REQUEST['verify_url']."', ";

  $sql.= "api_key = '".$_REQUEST['api_key']."', ";
  $sql.= "api_url = '".$_REQUEST['api_url']."', ";
  $sql.= "api_user = '".$_REQUEST['api_user']."', ";
  $sql.= "api_signature = '".$_REQUEST['api_signature']."' ";

  $sql.= "WHERE type='".strtoupper($_REQUEST['type'])."' ".
    "AND provider='".$provider."'";

  $wpdb->query($sql);

  $notes = $user_identity.'('.$user_ID.'): '. $_REQUEST['notes'];
  $changes = "Changed ".($provider==''?'paypal':$provider)." details: ".$sql."\n";
  sw_log_changes(addslashes($changes),addslashes($notes),'payment setting change');

  sw_show_provider_details("done");
}


////////////////////////////////////////////////////////////////////////////////

function sw_get_payment_mode($for_admin_panel = false, $provider = '', $bid = 0){
  global $wpdb;

  $global = $wpdb->get_var(
    "SELECT current_mode FROM paypal_settings WHERE provider='".$provider."' ");

  if($for_admin_panel) { //This is to display actual on admin
    return $global;
  }

  //Global = DOWN, payment is disabled
  if (empty($global) || $global == 'DOWN')
    return 'DOWN';
  return is_live_payments() ? "LIVE" : "TEST";
}

function sw_set_payment_variables(){

////////////////////////////////////////////////////////////////////////////////

  //PAYPAL -- OBSOLETE PayPal Standard Payment is no longer used
  $mode = sw_get_payment_mode(false);
  $details = sw_get_provider_details($mode);

  define('PAYMENT_TEST_MODE',($mode!='DOWN'&&$mode!=''));
  define("FORM_ACTION", $details->form_action);
  define("BUSINESS_ID", $details->business_id);
  define("RETURN_URL", $details->return_url);
  define("CANCEL_RETURN_URL", $details->cancel_return_url);
  define("NOTIFY_URL", $details->notify_url);
  define("BTN_IMAGE", $details->btn_image);
  define("PIXEL_IMAGE", $details->pixel_image);
  define("VERIFY_URL", $details->verify_url);

  //Backward compatibility
  define('PAYPAL_PAYMENT_ENABLED',PAYMENT_TEST_MODE);
  define('PAYPAL_BUSINESS_ID',BUSINESS_ID);
  define('PAYPAL_FORM_ACTION',FORM_ACTION);
  define('PAYPAL_API_KEY',FORM_ACTION);

////////////////////////////////////////////////////////////////////////////////
  
  //CREDITCARD and EXPRESS CHECKOUT
  $mode = sw_get_payment_mode(false,'creditcard');
  
  //RECURLY CC REPLACEMENT
  $mode_rcc = sw_get_payment_mode(false,'recurlycc');
  if($mode_rcc!='DOWN'&&$mode_rcc!='') {
    $mode = $mode_rcc;
    define('CREDIT_RECURLY_MODE',1);
    $cc_provider = 'recurlycc';
  } else {
    define('CREDIT_RECURLY_MODE',0);      
    $cc_provider = 'creditcard';
  }

  define('CREDIT_PAYMENT_TEST',$mode=='TEST');
  define('CREDIT_PAYMENT_ENABLED',($mode!='DOWN'&&$mode!=''));

  //Get inherited DETAILS
  $details = sw_get_provider_details($mode,$cc_provider);

  define('CREDIT_API_BUSINESS_ID',$details->business_id);    
  define('CREDIT_API_USER',$details->api_user);
  define('CREDIT_API_KEY',$details->api_key);
  define('CREDIT_API_SIGNATURE',$details->api_signature);
  define('CREDIT_API_URL',$details->api_url);
  define('CREDIT_VERIFY_URL',
    (strpos(CREDIT_API_URL,'sandbox')!==FALSE?
    'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token='
    :'https://www.paypal.com/webscr&cmd=_express-checkout&token='));

  $details = sw_get_provider_details('TEST',$cc_provider);
  define('CREDIT_API_BUSINESS_ID_SB',$details->business_id);    
  define('CREDIT_API_USER_SB',$details->api_user);
  define('CREDIT_API_KEY_SB',$details->api_key);
  define('CREDIT_API_SIGNATURE_SB',$details->api_signature);
  define('CREDIT_API_URL_SB',$details->api_url);
  define('CREDIT_VERIFY_URL_SB',
    'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');


////////////////////////////////////////////////////////////////////////////////
  //GOOGLE
  $mode = sw_get_payment_mode(false,'google');
  define('GOOGLE_PAYMENT_TEST',$mode=='TEST');
  define('GOOGLE_PAYMENT_ENABLED',($mode!='DOWN'&&$mode!=''));
  define('GOOGLE_SERVER_MODE',(GOOGLE_PAYMENT_TEST?'sandbox':'production'));
  define('GOOGLE_CURRENCY','USD');

  $details = sw_get_provider_details($mode,'google');
  define('GOOGLE_MERCHANT_ID',$details->api_user);
  define('GOOGLE_MERCHANT_KEY',$details->api_key);
  define('GOOGLE_API_URL',$details->api_url);
  define("GOOGLE_RETURN_URL", $details->return_url);
  define("GOOGLE_CANCEL_RETURN_URL", $details->cancel_return_url);
  define("GOOGLE_NOTIFY_URL", $details->notify_url);

  $details = sw_get_provider_details('TEST','google');
  define('GOOGLE_MERCHANT_ID_SB',$details->api_user);
  define('GOOGLE_MERCHANT_KEY_SB',$details->api_key);
  define('GOOGLE_API_URL_SB',$details->api_url);
  define("GOOGLE_RETURN_URL_SB", $details->return_url);
  define("GOOGLE_CANCEL_RETURN_URL_SB", $details->cancel_return_url);
  define("GOOGLE_NOTIFY_URL_SB", $details->notify_url);

////////////////////////////////////////////////////////////////////////////////
/*
  //AMAZON
  $mode = sw_get_payment_mode(false,'amazon');
  $details = sw_get_provider_details($mode,'amazon');
  define('AMAZON_PAYMENT_TEST',$mode=='TEST');
  define('AMAZON_PAYMENT_ENABLED',($mode!='DOWN'&&$mode!=''));
  define('AMAZON_ACCESS_ID',$details->api_user);
  define('AMAZON_ACCESS_KEY',$details->api_key);
  define('AMAZON_URL',$details->api_url);
  define("AMAZON_RETURN_URL", $details->return_url);
  define("AMAZON_CANCEL_RETURN_URL", $details->cancel_return_url);
  define("AMAZON_NOTIFY_URL", $details->notify_url);
*/
////////////////////////////////////////////////////////////////////////////////

  //GIFTCERT
  $mode = sw_get_payment_mode(false,'giftcert');
  $details = sw_get_provider_details($mode,'giftcert');
  define('GIFTCERT_PAYMENT_TEST',$mode=='TEST');
  define('GIFTCERT_PAYMENT_ENABLED',($mode!='DOWN'&&$mode!=''));

  //MATCHING
  $mode = sw_get_payment_mode(false,'matching');
  $details = sw_get_provider_details($mode,'matching');
  define('MATCHING_PAYMENT_TEST',$mode=='TEST');
  define('MATCHING_PAYMENT_ENABLED',($mode!='DOWN'&&$mode!=''));

  //FB CONNECT
  $mode = sw_get_payment_mode(false,'fbconnect');
  $details = sw_get_provider_details($mode,'fbconnect');
  define('FBCONNECT_TEST',$mode=='TEST');
  define('FBCONNECT_ENABLED',($mode!='DOWN'&&$mode!=''));
  define('FACEBOOK_APP_ID',$details->api_user);
  define('FACEBOOK_SECRET',$details->api_signature);

  //SPREEDLY
  $mode = sw_get_payment_mode(true,'spreedly');
  $details = sw_get_provider_details($mode,'spreedly');
  define('SPREEDLY_TEST',$mode=='TEST');
  define('SPREEDLY_ENABLED',($mode!='DOWN'&&$mode!=''));
  define('SPREEDLY_NAME',$details->api_user);
  define('SPREEDLY_TOKEN',$details->api_key);

  //RECURLY
  $mode = sw_get_payment_mode(true,'recurly');
  $details = sw_get_provider_details($mode,'recurly');
  define('RECURLY_TEST',$mode=='TEST');
  define('RECURLY_ENABLED',($mode!='DOWN'&&$mode!=''));
  define('RECURLY_USERNAME',$details->api_user);
  define('RECURLY_PASSWORD',$details->api_key);
  define('RECURLY_SITE',$details->business_id);

}
////////////////////////////////////////////////////////////////////////////////


if (function_exists('bp_core_add_admin_bar_css')) {
  remove_action( 'template_redirect', 'bp_core_add_admin_bar_css' );
  add_action( 'wp', 'bp_core_add_admin_bar_css' );
}

// Add to the custom profile fields
function cp_text_fields()
{
  global $no_link_fields;
  $no_link_fields[] = 'About Me';
}
add_action('wp', 'cp_text_fields');

function remove_dashboard_widgets() {
  global $wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
