<?
/**********************************************************************/
// Author:      Yosia Urip  
// Description: Functions
// Last Edited: August 2010
/**********************************************************************/

include_once(ABSPATH.WPINC.'/class-phpmailer.php');
include_once(ABSPATH.WPINC.'/class-smtp.php');
include_once(ABSPATH.WPINC.'/syi/class.html2text.php');

include_once(ABSPATH.'payments/recurly/library/recurly.php');
include_once(ABSPATH.'dev/class.krumo.php');

include_once('syi-constants.php');

define('GIFTCERT_ITEM_NAME','a SeeYourImpact Impact Card');
define('GIFTCERT_ITEM_DESC','Share the joy of giving with your friends, family, and loved ones!');
define('PAYMENT_BUTTON_TEXT','Proceed to Make Your Payment');
define('GC_APPLY_BUTTON_TEXT','Apply');
define('ENCRYPT_KEY','11181923001823153259324');

$REGIONS = array(
  'africa' => 'africa',
  'americas' => 'latin america',
  'asia' => 'asia',
  'states' => 'united states',
  'india' => 'india'
);
$CAUSES = array(
  'featured' => 'featured solutions',
  'disease' => 'disease',
  'education' => 'education',
  'clean-water' => 'clean water',
  'jobs' => 'job creation',
  'hunger' => 'hunger',
  'disabilities' => 'disability'
);
$FOCUS = array(
  //'orphans' => 'orphan',
  'children' => 'a child',
  //'family' => 'family',
  'girls' => 'a girl or woman',
  'newborns' => 'a mother or newborn'
);

global $GIFTS_V2,$GIFTS_EVENT,$GIFTS_LOC;
$GIFTS_EVENT = intval($_COOKIE["eid"]);
$GIFTS_LOC = $_REQUEST['gpg'];

$payment_vars = array(
'item_number'=>'',
'item_name_1'=>'',
'item_name_2'=>'',
'quantity_2'=>'',
'upload'=>'1',
'hosted_button_id'=>'1420',
'cmd _cart'=>'',
'custom'=>'',
'business'=>'',
'return'=>'',
'cancel_return'=>'',
'notify_url'=>'',
'no_note'=>'0',
'currency_code'=>'USD',
'tax'=>'0',
'lc'=>'US',
'bn'=>'',
'address_override'=>'1',
'cn'=>'',
'on0_1'=>'',
'os0_1'=>'',
'on0_2'=>'',
'os0_2'=>'',
'gift'=>'',
'mg'=>'',
'next'=>'',
'blog_url'=>'',
'sub_site'=>'');

if(!function_exists('do_post_request')){
  function do_post_request($url, $data, $optional_headers = NULL) {  
	$params = array('http' => array('method' => 'GET', 'content' => $data));  
	if ($optional_headers !== null) {$params['http']['header'] = $optional_headers;}  
	$ctx = stream_context_create($params);
	//$fp = fopen($url, 'r', false, $ctx);  
	$fp = fopen($url, 'r', false, $ctx);  
	//if (!$fp) { throw new Exception("Problem with $url, $php_errormsg"); }  
	$response = @stream_get_contents($fp);  
	//if ($response === false) { throw new Exception("Problem reading data from $url, $php_errormsg"); }  
	return $response;  
  }   
}

if(!function_exists('aws_signed_request')){
  function aws_signed_request($host,$params,$public_key,$private_key){
	//Copyright(c) 2009 Ulrich Mierendorff	
	//Parameters:
	//$region - the Amazon(r) region(ca,com,co.uk,de,fr,jp)
	//$params - an array of parameters,eg. array("Operation"=>"ItemLookup",
	//"ItemId"=>"B000X9FLKM","ResponseGroup"=>"Small")
	//$public_key - your "Access Key ID"
	//$private_key - your "Secret Access Key"	
	//some paramters
	$method="GET";
	$params["Service"]="AWSECommerceService";//additional parameters
	$params["Operation"]="CartAdd";
	$params["AWSAccessKeyId"]=$public_key;
	$params["Timestamp"]=gmdate("Y-m-d\TH:i:s.000\Z");//GMT timestamp
	$params["Version"]="2009-03-31";//API version
	ksort($params);//sort the parameters
	$canonicalized_query=array();//create the canonicalized query
	foreach($params as $param=>$value){
	  $param=str_replace("%7E","~",rawurlencode($param));
	  $value=str_replace("%7E","~",rawurlencode($value));
	  $canonicalized_query[]=$param."=".$value;
	}
	$canonicalized_query=implode("&",$canonicalized_query);
	//create the string to sign
	$string_to_sign=$method."\n".$host."\n".$uri."\n".$canonicalized_query;
	//calculate HMAC with SHA256 and base64-encoding
	$signature=base64_encode(hash_hmac("sha256",$string_to_sign,$private_key,True));
	//encode the signature for the request
	$signature=str_replace("%7E","~",rawurlencode($signature));
	$request=$host."?".$canonicalized_query."&Signature=".$signature;// create request		
	$response=file_get_contents($request);//do request
	//debug($request);
	//debug($response,false,'',true,false);

	if($response===false){return false;}
	else{
	  $pxml=@simplexml_load_string($response);//parse XML		
	  if($pxml===false){return false;}else{return $pxml;}
	}
  }
}

//Debug function to debug
if(!function_exists('debug')) {
  function debug($msg,$email=false,$sbj='',$die=false,$pre=true) {
    $phpmailer = new PHPMailer();
    $phpmailer = syi_init_phpmailer($phpmailer);
    if ($pre) { $msg = '<pre>'.print_r($msg,true).'</pre>'; }
    if (empty($email)) {
      echo $msg;
    } else {
      if ($email === true) $email = "payments";
      $email = get_site_option($email . "_email");
      if (empty($email)) $email = get_site_option("payments_email");
      $phpmailer->AddAddress($email);
      $phpmailer->Subject = $sbj.' '.strval(date('Y-m-d H:i:s'));
      if (!is_live_site()) $msg = get_bloginfo('url') . $msg . "\n\n";
      $msg .= "\n\n".stacktrace(NULL, 1);
      $phpmailer->MsgHTML($msg);
      if (!$phpmailer->Send()) {
        error_log($phpMailer->ErrorInfo);
      }
    }
    if ($die) die();
  }
}

if (!function_exists('notifyAdmin')) {
  function notifyAdmin($msg,$sbj='',$email) {
    debug($msg,$email,$sbj,false,false);
  }
}

//Bypass wpdb blog id, get the user ids directly using blog id and role
if(!function_exists('getUsersByRoleByBlogId')){
function getUsersByRoleByBlogId($role,$blogId) {  
  global $wpdb;
  $prefix='wp_'.intval($blogId).'_'; 
  $q='SELECT ID FROM '.$wpdb->users.' INNER JOIN '.$wpdb->usermeta.' '
    . 'ON '.$wpdb->users.'.ID = '.$wpdb->usermeta.'.user_id '
    . 'WHERE '.$wpdb->usermeta.'.meta_key = \''.$prefix.'capabilities\' '
    . 'AND '.$wpdb->usermeta.'.meta_value LIKE \'%"'.$role.'"%\' ';
  $userIDs=$wpdb->get_col($q);  
  return $userIDs;  
}  
}

// http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
function startsWith($haystack,$needle,$case=true) {
    if($case){return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);}
    return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
}

function endsWith($haystack,$needle,$case=true) {
    if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
    return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
}

function plural($count, $word, $words = "") {
  if ($count == 1)
    return "$count $word";
  if (empty($words))
    $words = $word . "s";
  return "$count $words";
}

function as_money($amount,$format='$%.2n') {
  if ($amount < 0)
    return "-" . as_money(-$amount, $format);
  return money_format($format,$amount + 0.0);
}
function from_money($amount) {
  return round(doubleval(str_replace(array("$",","," "),"", $amount)),2);
}

function days_since($d) {
  $diff = time() - $d;
  $days = floor($diff / (60 * 60 * 24));

  return $days;
}

// Before $dy days, don't do the year
function short_date($d, $dy = 150) {
  $days = days_since($d);

  if ($days > $dy)
    return date('M j, Y', $d);

  return date('M j', $d);
}

function eor() {
    $vars = func_get_args();
     while (!empty($vars) && empty($defval))    
         $defval = array_shift($vars);          
     return $defval;
}
//-----------------------------------------------------------------------------------
//UI Form Elements

function build_ddl($name, $id, $vars, $def='', $atts=''){
  $ret = '';
  $ret .= '<select id="'.$id.'" name="'.$name.'" '.$atts.'>';
  $ret .= '<option value="">--</option>';  
  foreach($vars as $k=>$v){
    if(is_object($v))$v = $v->value; 
	$ret .= '<option value="'.$k.'" '
	. ($def == $k?'selected="selected"' : '').'>'.$v.'</option>';
  }
  $ret .= '</select>';    
  return $ret;
}

//Function to build quantity dropdown on confirm page
function build_quantity_ddl($default='', $name='quantity_1'){
  if($default == ''){$default = 1;}
  $ret = '';
  $ret .= '<select id="'.$name.'" name="'.$name.'" '
  . 'onchange="updateTip(true);">';
  for($i = 1; $i <= 20; $i++){
    $ret .= '<option value="'.$i.'" '
	. ($default == $i?'selected="selected"' : '').'>'.$i.'</option>';
  }
  $ret .= '</select>';    
  return $ret;
}

//Function to build tip dropdown on confirm page
function build_tip_ddl($amount,$default='',$name='amount_2'){
  if($default == ''){$default = 0.15 * $amount;}
  $values = array('0'=>'0','5'=>'0.05','10'=>'0.1','15'=>'0.15','20'=>'0.2','25'=>'0.25');
  $ret = '';
  $ret .= '<select id="'.$name.'" name="'.$name.'" ' 
  . 'onchange="updateTip(false);" >';
  foreach($values as $k=>$v){
	$ret .= '<option value="' . number_format($amount * floatval($v),2) . '" '
	. (strval($default) == strval($amount * floatval($v)) ? 'selected="selected"' : '').'>' 
	. as_money($amount * floatval($v)) . ' (' . $k . '%)</option>';    
  }
  $ret .= '</select>';    
  return $ret;    
}

function build_tip_rate_ddl($default=0,$name='tip'){
  $rates = array('0'=>0,'5'=>0.05,'10'=>0.1,
    '15'=>0.15,'20'=>0.2,'25'=>0.25);
  $ret = '';
  $ret .= '<select id="'.$name.'" name="'.$name.'">';
  foreach($rates as $k=>$v){
    //echo $default.' compared with '.$v.' -- '.print_r(doubleval($default) == doubleval($v),true);
    $ret .= '<option '
    .(doubleval($default) == doubleval($v)? 'selected="selected"' : '')
    .' value="'.$v.'">'.$k.'%</option>';
  }
  $ret .= '</select>';
  return $ret;
}

//Function to build GC amount dropdown
function build_amount_ddl($default=''){
  if($default == ''){$default = 20;}
  $amts = array(15,20,25,30,40,50,60,80,100);
  $ret = '';
  $ret .= '<select id="amount_1" name="amount_1" '
  . 'onchange="updateTip(true)">';
  foreach($amts as $amt){
    $ret .= '<option value="'.$amt.'" '
	. ($default == $amt?'selected="selected"' : '').'>'.as_money($amt).'</option>';
  }
  $ret .= '</select>';    
  return $ret;
}

global $cc_error_codes;
$cc_error_codes = array(
  '15004' => "Please check your credit card number and CVV.",
  '10535' => "Please check your credit card number and CVV.",
  '10527' => "Please check your credit card number and CVV.",
  '10527' => "Please check your credit card number and CVV.",
  // '15005' => "declined",
  // '15006' => "declined",
  '15007' => "This credit card appears to be expired.",
  '10759' => "Please check your credit card number and CVV.",
  '10762' => "Please check your credit card CVV.",
  'X' => ""
);



function notifyPaymentFailure($array1='',$array2='',$errorType='CC'){
  global $cc_error_codes;
  unset($array1['cc_num']);

  $code = eor($array2['ERRORCODE'], $array2['L_ERRORCODE0']);
  $msg = $cc_error_codes[$code];

  $declinedRefNum = $errorType.'-'.strval(time());
  $declinedLog = '<pre>Submitted: '.print_r($array1,true).'</pre>'
    . '<br><pre>Received: '.print_r($array2,true).'</pre><br>' . $msg;
  notifyAdmin($declinedLog,
    "SYI Payment Error Notification - $declinedRefNum [$code] - ", $errorType);

  if (!empty($msg))
    return $msg;

  return ' Please contact '.
    '<a href="mailto:contact@seeyourimpact.org">'
	. 'contact@seeyourimpact.org</a> for assistance. ' 
	. 'Your reference number is: ' . $declinedRefNum.'.';
}


function get_subsite(){
  $site = explode('//', get_bloginfo('url'));
  $site = explode('.', $site[1]);
  return $site[0];
}

//-----------------------------------------------------------------------------------

function encrypt($input_string, $key=ENCRYPT_KEY){
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $h_key = hash('sha256', $key, TRUE);


  return
  str_replace('+','|',
    base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $h_key,
    $input_string, MCRYPT_MODE_ECB, $iv)));
}

function decrypt($encrypted_input_string, $key=ENCRYPT_KEY){

  $encrypted_input_string = str_replace('|','+',$encrypted_input_string);

  //echo $encrypted_input_string;

//  $encrypted_input_string =
//  substr($encrypted_input_string,1,strlen($encrypted_input_string)-1);


  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $h_key = hash('sha256', $key, TRUE);
  return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $h_key, 
    base64_decode($encrypted_input_string), MCRYPT_MODE_ECB, $iv));
}

//-----------------------------------------------------------------------------------
function fixEncoding($in_str) { 
  $cur_encoding = mb_detect_encoding($in_str) ; 
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8")) 
    return $in_str; 
  else 
    return utf8_encode($in_str); 
}

function xml_entities($content="") {
  $contents = unicode_string_to_array($content);
  $swap = "";
  $iCount = count($contents);
  for ($o=0;$o<$iCount;$o++) {
    $contents[$o] = unicode_entity_replace($contents[$o]);
    $swap .= $contents[$o];
  }
  return mb_convert_encoding($swap,"UTF-8"); //not really necessary, but why not.
}

function unicode_string_to_array( $string ) { //adjwilli
  $strlen = mb_strlen($string);
  if ($strlen == 0)
    return array();

  while ($strlen) {
    $array[] = mb_substr( $string, 0, 1, "UTF-8" );
    $string = mb_substr( $string, 1, $strlen, "UTF-8" );
    $strlen = mb_strlen( $string );
  }
  return $array;
}

function unicode_entity_replace($c) { //m. perez
  $h = ord($c{0});
  if ($h <= 0x7F) {
    return $c;
  } else if ($h < 0xC2) {
    return $c;
  }

  if ($h <= 0xDF) {
    $h = ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
    $h = "&#" . $h . ";";
    return $h;
  } else if ($h <= 0xEF) {
    $h = ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6 | (ord($c{2}) & 0x3F);
    $h = "&#" . $h . ";";
    return $h;
  } else if ($h <= 0xF4) {
    $h = ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12 | (ord($c{2}) & 0x3F) << 6 | (ord($c{3}) & 0x3F);
    $h = "&#" . $h . ";";
    return $h;
  }
}

function array2object($array) {
 
    if (is_array($array)) {
        $obj = new StdClass();
 
        foreach ($array as $key => $val){
            $obj->$key = $val;
        }
    }
    else { $obj = $array; }
 
    return $obj;
}
 
function object2array($object) {
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
    }
    else {
        $array = $object;
    }
    return $array;
}
 

function as_html($s, $newlines = false) {
  $s = xml_entities(fixEncoding(stripslashes($s)));
  if ($newlines)
    $s = nl2br($s);
  return $s;
}

function as_bool(&$o, $def = FALSE) {
  if ($o === TRUE || $o === 1)
    return TRUE;
  if (strcasecmp($o, "true") == 0)
    return TRUE;
  if (strcasecmp($o, "yes") == 0)
    return TRUE;
  if (strcasecmp($o, "false") == 0)
    return FALSE;
  if (strcasecmp($o, "no") == 0)
    return FALSE;
  return $def;
}

function as_array($o) {
  if (empty($o))
    return array();
  if (!is_array($o))
    $o = explode(",", $o);
  return $o;
}

function as_ints($o) {
  if (!is_array($o))
    $o = explode(",", $o);
  return array_filter(array_map('intval', $o));
}

if(!function_exists('mb_ucfirst')) {
  function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
	$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
	$str_end = "";
	if ($lower_str_end) {
  $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
	}
	else {
  $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
	}
	$str = $first_letter . $str_end;
	return $str;
  }
}
 
function as_name($s) {
  return mb_ucfirst(trim($s));
}

function draw_check_option($name, $label, $value = false, $disabled = false) {
 ?><div class="check-option"><input id="<?=$name?>" name="<?=$name?>" type="checkbox" value="1" 
   <? if ($value) echo 'checked="checked" u';?> 
   <? if ($disabled) echo 'disabled="disabled"';?> 
 ><label for="<?=$name?>"><?= htmlspecialchars($label); ?></label></div><?
}

//-----------------------------------------------------------------------------------

function build_charity_options($name,$defaults=NULL,$type="radio",$first_blank=false){
  global $wpdb;
  $results = $wpdb->get_results('SELECT blog_id, domain from wp_blogs', ARRAY_A);
  $return = '';
  
  //if($defaults!=NULL&&$defaults!='')$defaults = explode(',',$defaults);
  if($defaults == NULL || $defaults == ''){
    $defaults = 1;
  }

  if($type == "radio"){  
	foreach($results as $v){
	  $found = false;
	  if($defaults!=NULL&&$defaults!='') 
		$found = $v['blog_id'] == intval($defaults);
	  $return .= '<input type="radio" '
		. (FALSE===$found?'':'checked="checked"')
		. ' name="'.$name.'" value="'.$v['blog_id'].'" /> '.$v['domain'].'<br/>';  
	}
  } else if($type == "dropdown") {
    $return .= '<select name="'.$name.'">';
    if($first_blank){
	  $return .= '<option value="">---</option>';	
	}
	foreach($results as $v){
	  $found = false;
	  if($defaults!=NULL&&$defaults!=''){
	    $found = $v['blog_id'] == intval($defaults);
	  }
	  
	  $return .= '<option value="'.$v['blog_id'].'" '
		. (FALSE===$found?'':'selected="selected"').'>';
	  $return .= $v['domain'];
	  $return .= '</option>';
	}
    $return .= '</select>';
  } else if($type == "checkbox") {
	foreach($results as $v){
	  $found = false;
	  if($defaults!=NULL&&$defaults!='') 
  	    $found = array_search($v['blog_id'],intval($defaults));
  	    $return .= '<input type="checkbox" '
  	    . (FALSE===$found?'':'checked="checked"')
  	    . ' name="'.$name.'[]" value="'.$v['blog_id'].'" /> '.$v['domain'].'<br/>';  
	}	  
  }
  
  return $return;
}

function get_param_value($params_string, $param_key){
  $params = explode(";",$params_string);
  foreach($params as $v){
    if(strpos($v,$param_key.':')===0){
	  return str_replace(array($param_key.':','[',']'),'',$v);
	}
  }
  return NULL;
}

function set_param_value($params_string, $param_key, $param_value){
  $params = explode(";",$params_string); $found = false;

  if(is_array($params)) {
    foreach($params as $k=>$v){
      if(strpos($v,$param_key.':')===0){
	     $params[$k] = $param_key.':'.'['.$param_value.']';
		 $found = true; 
      }
    } 
	if(!$found){
	  array_push($params,$param_key.':'.'['.$param_value.']');
	}
    return implode(";",$params);
  } else {
    return $param_key.':'.'['.$param_value.']';
  }
}

function as_email($e){
  $em = explode('@', $e);
  $addr = $em[0] . "@";
  if (current_user_can('level_10'))
    return $addr . $em[1];
  return $addr . "...";
}

function syi_activate_sitewide($plugin) {
  /* Add the plugin to the list of sitewide active plugins */
  $active_sitewide_plugins = maybe_unserialize( get_site_option( 'active_sitewide_plugins' ) );

  /* Add the activated plugin to the list */
  $active_sitewide_plugins[ $plugin ] = time();

  /* Write the updated option to the DB */
  return update_site_option( 'active_sitewide_plugins', $active_sitewide_plugins );
}

//-----------------------------------------------------------------------------------

function get_sw_mg($cond = "1"){
  global $wpdb;
  $ret = NULL;
  $sql="SELECT code FROM donationAcct "
  . "WHERE donationAcctTypeId = 4 AND balance > 0 "
  . "AND " . $cond . " "
  . "ORDER BY `use` ASC, `priority` ASC "
  . "LIMIT 1";
  
  $ret = $wpdb->get_var($sql);
  
  //echo $sql.'--'.$ret.'++'; 
  return $ret;
}

function get_mg($params = NULL, $home = false, $bypass = false, $verify = NULL){
  global $wpdb;  
  if(
    (MATCHING_PAYMENT_ENABLED //cant display if not sw enabled
    && (!$home || !MATCHING_PAYMENT_TEST)) //cant display test on home
	|| $bypass //JUST GET IT!!!
    ){  
    
	$ret = NULL; 
	$giftId = NULL;
	$charityId = NULL;
	$unit = NULL;
	//$cond = "0 OR ";   	
	if($params!=NULL){
      //contain all mgs that will be applied on the other side
    
	  //parse the param for gift condition
	  $giftId = get_param_value($params, 'gift');
	  if($giftId != NULL) {
	    $charityId = $wpdb->get_var($wpdb->prepare("SELECT blog_id FROM gift "
		. "WHERE id = %d",$giftId));
	    $unit = $wpdb->get_var($wpdb->prepare("SELECT unitAmount FROM gift "
		. "WHERE id = %d",$giftId));		
      }else{
        $charityId = get_param_value($params, 'charity');
		$unit = get_param_value($params, 'unit');
	  }

	  //add on all conditions and query available mg   
      //if($giftId!=NULL) $cond .= "params LIKE '%gift:[".$giftId."]%' OR ";
	  //if($charityId!=NULL) $cond .= "params LIKE '%charity:[".$charityId."]%' OR ";

      if($charityId!=NULL) $cond .= "blogId = '".$charityId."'";

	  //$cond .= "0"; 
	  $cond = "(".$cond.")";


	  if($giftId!=NULL){ 
		if($unit!=NULL){
		$cond .= 
		" AND ("
		."LOCATE('unit_min:[',params) = 0 OR ("
		."CONVERT(SUBSTR(params, "
		."(LOCATE('unit_min:[',params) + LENGTH('unit_min:[')), "
		."LOCATE(']',params,LOCATE('unit_min:[',params)) - "
		."(LOCATE('unit_min:[',params) + LENGTH('unit_min:[')) "
		."), SIGNED INTEGER)"
		."<=".$unit
		.")"
		.")"
		." AND ("
		."LOCATE('unit_max:[',params) = 0 OR ("
		."CONVERT(SUBSTR(params, "
		."(LOCATE('unit_max:[',params) + LENGTH('unit_max:[')), "
		."LOCATE(']',params,LOCATE('unit_max:[',params)) - "
		."(LOCATE('unit_max:[',params) + LENGTH('unit_max:[')) "
		."), SIGNED INTEGER)"		
		.">=".$unit
		.")"
		.")"
		;
		}
	  }

	  //Have the code already -- just need to veryfy if it exists
	  if($verify!=NULL){$cond .= " AND code='".addslashes($verify)."'";}

      $ret = get_sw_mg($cond);
    } 
 
    //if no condition still not found, get any sw mg
	if($ret == NULL){return get_sw_mg("(blogId = 1 OR blogId = 0)");}
	
	return $ret;
  } else { return NULL; }
}

function show_mg($mg = NULL){
  if($mg == NULL) return NULL;
  return '<div style="font-family:Arial;width:180px;line-height:12px;text-align:center;background:#fff;padding:5px 0;margin:5px auto;">
  <a style="font-size:10px" href="/matching-gift/"><strong style="color:#669900;font-size:11px;">YOUR GIFT WILL BE MATCHED!</strong>
  <br/>(Click for more info)</a></div>';
}

function tip_matched($mgId=0){
  global $wpdb;

  $params = $wpdb->get_var($wpdb->prepare(
    "SELECT params FROM donationAcct WHERE id = %d",$mgId
	));
  if($params!=NULL){
    $tip_matched = get_param_value($params,'match_tip');
    if($tip_matched == "no") 
      return false;
  }
  return true;  
}

//-----------------------------------------------------------------------------------

function store_cart($vars,$local=false){
  global $wpdb;
  if($local){//Use the var names on vars and grab the local value
    $new_vars = array();
    foreach($vars as $k=>$v){
      $new_vars[$k] = $$k;
	}
    $vars = $new_vars;
  }
  //Serialize vars, insert to payment table, and return the ID
  $sql = $wpdb->prepare("INSERT INTO payment "
    ."(dateTime, cart) VALUES(NOW(),'%s') ", serialize($vars));
  $wpdb->query($sql);

  //echo $sql; exit();
  return encrypt($wpdb->insert_id);
}

function validateGiftAmount($giftID,$giftAmount){
  global $wpdb;
  $gift = $wpdb->get_row($wpdb->prepare("SELECT * FROM gift WHERE id = %d"),ARRAY_A);	
  if($gift['varAmount'] && floatval($giftAmount)>=$gift['unitAmount']){
	return true;
  }else if(floatval($giftAmount)==$gift['unitAmount']){
	return true;
  }
  return false;
}

////////////////////////////////////////////////////////////////////////////////

function store_donationContact($donationID,$value,$type){
  global $wpdb;  
  $exists = $wpdb->get_var($wpdb->prepare(
    "SELECT id FROM donationContact WHERE donationID = %d AND type = '%s'",
    $donationID, $type
  )); 
  if($exists != NULL && intval($exists)>0){
  //donationContact of this donation and type exists, just update the value
    $wpdb->query($wpdb->prepare(
      "UPDATE donationContact SET value = %s "
        ."WHERE donationID = %d AND type = '%s' ",
        $value, $donationID, $type
    ));  
  } else {
  //donationContact of this donation and type does not exists, insert new row
    $wpdb->query($wpdb->prepare(
      "INSERT INTO donationContact (donationID,value,type) "
        ."VALUES (%d,'%s','%s')",
        $donationID, $value, $type
    ));
  }
}

function build_dropdown($name,$id,$data,$default=''){
  $ret = '';
  $ret .= '<select name="'.$name.'" id="'.$id.'">';
  foreach($data as $k=>$v){
    $ret .= '<option value="'.$k.'" '
      .($default==$v?'checked="checked"':'').'>'.$v.'</option>';
  }
  $ret .= '</select>';
  return $ret; 
}


function get_post_first_image($post_content) {

  $doc = new DOMDocument();
  $doc->loadHTML($post_content);
  $xml = simplexml_import_dom($doc);
  $images = $xml->xpath('//img');

  if(is_array($images) && count($images)>0 ){
    if($images[0]['src']!='') {
      return $images[0]['src'];
    }
  }

  return null;
}

////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS FOR CAUSE EXPLORER

function get_excerpt($post) {
  if (!empty($post->post_excerpt))
    return $post->post_excerpt;

  $excerpt_length = 50;

  $ex = strip_shortcodes($post->post_content);
  $ex = strip_tags(str_replace(']]>', ']]&gt;', $ex));
  $words = explode(' ', $ex, $excerpt_length + 1);
  if(count($words) > $excerpt_length) {
    array_pop($words);
    array_push($words, '...');
    $ex = implode(' ', $words);
  }
  return trim($ex);
}

function print_posts($limit = 5, $slug = '', $post_type = 'post'){



  if($post_type == 'story'){

    $posts = get_story_list($limit, $slug);
  }else{

    $posts = get_posts(
      $slug!=''?
      array('numberposts'=>$limit, 'post_type'=>'post', 'tag'=>$slug):
      array('numberposts'=>$limit, 'post_type'=>'post')
      );
  }

  if(is_array($posts))
  foreach($posts as $post){
    if(!isset($post->post_excerpt)){
      //Strip ALL images, tags and shortcode, and get an excerpt
      $post_content = preg_replace('/<img[^>]+>/i','',$post->post_content);
      $post_content = preg_replace("/\[caption.*\[\/caption\]/", '', $post_content);
      $post_content = strip_shortcodes(strip_tags($post_content));
      $post_excerpt = getExcerpt($post_content);
    } else {
      $post_excerpt = $post->post_excerpt;
    }

    $post_image = '';
    if(!isset($post->post_image)){

      if($post->post_content != ''){

      //Get the first image
      $doc = new DOMDocument();
      $doc->loadHTML($post->post_content);
      $xml = simplexml_import_dom($doc);
      $images = $xml->xpath('//img');

      if(is_array($images) && count($images)>0 ){
        if($images[0]['src']!='')
        $post_image = '<img src="'.$images[0]['src'].'" class="post-img" '
        .'alt="'.$post->post_title.'" title="'.$post->post_title.'"/>';
      }


      }
    } else {
      if($post->post_image!='')
      $post_image = '<img src="'.$post->post_image.'" class="post-img" '
        .'alt="'.$post->post_title.'" title="'.$post->post_title.'"/>';
    }

?>
    <div class="post-box">
    <div class="post-img-holder">
    <?= $post_image ?>
    </div>

    <div class="post-excerpt">
    <div class="post-title"><?= getExcerpt(strtotitle($post->post_title),0,20) ?></div>
    <?= getExcerpt($post_excerpt,0,75) . ' (<a href="'.$post->guid.'"><u>more...</u></a>)'?>
    </div>

    </div>
<?      
  }
}

function the_optional_title($tag) {
  $s = trim(get_the_title());
  if (empty($s) || substr($s, 0,1)=='(' )
    return;

  echo "<$tag>$s</$tag>";
}

function strtotitle($title) { 
  // Steve: For now, let's let titles remain mostly lowercase
  return stripslashes($title);
}

function fix_name($name) {
  $name = implode(' ', array_map('fix_name_word', explode(' ', $name))); 
  return $name;
}
function fix_name_word($word) {
  $smallwordsarray = array( 'of','a','the','and','an','or','nor','but','is',
    'if','then','else','when', 'at','from','by','on','off','for','in','out',
    'over','to','into','with' ); 

  $word = trim(stripslashes($word)); 
  $lword = strtolower($word);
  if (in_array($lword, $smallwordsarray))
    return $lword;

  if (ctype_upper($word) || ctype_lower($word)) 
    return ucwords($lword);
  
  return $word;
}


function getExcerpt($str, $startPos=0, $maxLength=80) {
	if(strlen($str) > $maxLength) {
		$excerpt   = substr($str, $startPos, $maxLength-3);
		$lastSpace = strrpos($excerpt, ' ');
		$excerpt   = substr($excerpt, 0, $lastSpace);
		//$excerpt  .= '...';
	} else {
		$excerpt = $str;
	}
	
	return $excerpt;
}

function search_gift_by_tag($slug){
  global $wpdb;
  $slug = like_escape($slug);
  $sql =

  ("SELECT DISTINCT id FROM gift WHERE tags LIKE '%".$slug."%'");
  //echo $sql;
  return $wpdb->get_col($sql);
}

function get_story_list($limit = 5, $slug = ''){
  global $wpdb;
  //get list of gifts with the slug
  $gift_ids = array();
  if(strpos(",",$slug)!==FALSE){
    $slugs = explode(",",$slug);
    foreach($slugs as $s){
      $gids = search_gift_by_tag($s);
      if($gids != NULL)
        $gift_ids = array_merge($gift_ids,$gids);
    }
  } else {
    $gift_ids = search_gift_by_tag($slug);
  }


  $gift_ids = implode(",",$gift_ids);
  $sql = $wpdb->prepare(
    "SELECT * FROM donationStory "
    . "WHERE gift_id IN (".$gift_ids.") ORDER BY RAND() LIMIT %d",
    $limit);

  //echo $sql;
  $posts = $wpdb->get_results($sql);

  return $posts;
}

function print_charity_list($limit = 5, $slug = ''){
  global $wpdb;
  
  if($slug == ''){
    $charities = list_charities('random', $limit, 
	    array("bi.regions = 'africa'",
	    "bi.causes = 'health'",
	    "bi.causes = 'education'",
	    "bi.causes = 'water'"), false);
  } else {
    $charities = list_charities('random', $limit, 
	    $wpdb->prepare(" (bi.regions = %s OR bi.causes = %s)",$slug,$slug), 
      false, false);     
  }

  $count = 0;
	  
	foreach($charities as $charity){
    if($count==$limit)break;
    $bid = $charity->blog_id;
	  $path = $charity->path;

	  // get blog url depending on vhost or not-vhost installtion
	  if( defined( "VHOST" ) && constant( "VHOST" ) == 'yes' )
	    $domain = $charity->domain;
	  else
	    $domain = get_blog_option( $bid, "siteurl");
	  
    $site = explode('.', $domain); // Gets first word of URL from this

    $name = get_blog_option( $bid, "blogname");
	  $desc = get_blog_option( $bid, "blogdescription");

    if (get_blog_option($bid, 'blog_public') >= 0) {

?>
    <div class="charity-box">
    <div class="post-img-holder">
      <img src="<?= __C('charity-images/charity-' . $site[0] . '.jpg') ?>" class="charity-img" alt="<?= $name ?>" title="<?= $name ?>"/>
    </div>
    <div>
    <div class="charity-name"><?= $name ?></div>
    <?= $desc ?>. (<a href="#"><u>more...</u></a>)</div>
    </div>
<?      
  
      $count++;
    }
  }
}

function build_tag_query($col, $tag) {
  $likes = array();
  if (!is_array($tag))
    $tag = explode(',', $tag);
  foreach ($tag as $t) {
    $t = like_escape(trim($t));
    $likes[] = "$col LIKE '%$t%'";
  }
  return '(' . implode(' OR ', $likes) . ')';
}

function get_archived_posts($blog_id){
  $posts = get_posts("tag=archived");
  $post_ids = array();
  foreach($posts as $post){
    $post_ids[] = $post->ID;
  }
  return implode(",",$post_ids);
}

function get_posts_by_ids($ids, $limit=10) {
  return get_posts("include=".$ids."&numberposts=$limit&orderby=date&exclude=".get_archived_posts($blog_id));
}

function get_posts_by_tag($tag, $limit=10) {
  return get_posts("tag=$tag&numberposts=$limit&orderby=date&exclude=".get_archived_posts($blog_id));
}

function get_posts_by_charity($blog_id, $limit = 10, $order = 'DESC') {
  return get_posts("numberposts=$limit&orderby=date&order=$order&exclude=".get_archived_posts($blog_id));
}

function get_stories_where($where, $limit = 10, $order = 'RAND()', $test_ok = false) {

  $limit = eor($limit, 10);
//
  global $wpdb;
  global $blog_id;
  // TODO: filter TEST charities out - DONE
  $sql = "SELECT ds.*, 'story' as type, ds.post_title as title FROM donationStory ds "
    . " LEFT JOIN gift g ON g.id = ds.gift_id "
    . " LEFT JOIN donationGiver dg ON dg.ID=ds.donor_id "
    . " JOIN wp_blogs b ON ds.blog_id = b.blog_id "
    . " WHERE NOT(ds.post_image = '') "
    . " AND ((b.public = '1' AND b.archived = '0' AND b.mature = '0' "
	  . " AND b.spam = '0' AND b.deleted ='0') "
    . " OR b.blog_id = '".intval($blog_id)."' "
    . " OR ".($test_ok?'1':'0')
    . " ) " //only publicly published blog
    . " AND $where ORDER BY $order LIMIT " . $limit;
  // TODO - use wpdb->prepare.  But it was returning blank string, so I changed it.

  if ($_REQUEST['sql'] == "yes")
    pre_dump($sql);
  $arr = array_map('prepare_story', $wpdb->get_results($sql));
  if (strstr($order, "RAND()") !== FALSE)
    shuffle($arr);

  return $arr;
}

function get_stories_by_tag($tag, $limit = 10, $featured_only = false, $order = "RAND()") {
  $where = build_tag_query("g.tags", $tag,$tag2,$tag3);
  //display featured story only
  if($featured_only) $where .= " AND ds.featured = 1 ";
  return get_stories_where($where, $limit, $order);
}

function get_stories_by_gift($gift_id, $limit = 10) {
  $where = "g.id = " . intval($gift_id);
  //
  return get_stories_where($where, $limit, 'RAND()', true);
}

function prepare_story($story) {
  global $wpdb, $blog_id;

  $wpdb->set_blog_id($story->blog_id);

  $story->ref = "$story->blog_id/$story->post_id";
  $story->post_thumb = get_img_src(get_the_post_thumbnail($story->post_id,'thumbnail'));

  if($story->post_image != ''){
    $img_path = $story->post_image;
    $size = @filesize($img_path);

    //echo 'stored img: '.$img_path . '<br/>'.$size.'<br/>';
    if($size>1000000){
      $story->post_image = get_img_src(get_the_post_thumbnail($story->post_id,'medium'));
      //$size = @filesize($img_path);
      //echo 'wp generated: '.$img_path.'<br/>'.$size.'<br/>';
    } else if ($size==0) {
      //$story->post_image = '';
    }
  }

  $wpdb->set_blog_id($blog_id);
//  WHY?
//  unset($story->post_id);
  return $story;
}

function get_img_src($img_html){
$parser = xml_parser_create();
xml_parse_into_struct($parser, $img_html, $values);
foreach ($values as $key => $val) {
    if ($val['tag'] == 'IMG') {
        $first_src = $val['attributes']['SRC'];
        break;
    }
}
  return $first_src;
}
function get_stories_by_ids($ids, $limit = 10){
  global $wpdb;
  $ids = explode(",",$ids);
  foreach($ids as $k=>$v){
    $ids[$k] = "'".addslashes($v)."'";
  }
  $ids = implode(",",$ids);

  $main_dom = $wpdb->get_var("SELECT domain FROM wp_blogs WHERE blog_id = 1");
  $where =
    "CONCAT(REPLACE(b.domain,CONCAT('.','".$main_dom."'),''),'-',ds.post_id) "
    . "IN (".$ids.")";

  return get_stories_where($where, $limit);
}

function get_gifts_where($where, $args = NULL, $cols = "*") {
  global $wpdb;
  $bid = $GLOBALS['blog_id'];
  // Filter test domains - TODO, do a better job

  $show_private = false;
  $show_all = false;
  if ($args)
    extract($args);
  if ($limit == 0)
    $limit = 1000;

  if (empty($where))
    $where = "(g.id > 0)";

  $where = " ( b.blog_id > 1 "
    . " AND (b.domain NOT LIKE 'syi%' OR b.blog_id = '".intval($bid)."') "
    . " ) AND $where";

  if ($show_private == true || $show_all == true)
    $private = "";
  else
    $private = " AND ((b.public = '1' AND b.archived = '0' AND b.mature = '0' AND b.spam = '0' AND b.deleted ='0') OR b.blog_id = '".intval($bid)."') ";

  if ($show_all == true) 
    $in_stock = "";
  else
    $in_stock = " AND (g.unitsWanted > 0 AND g.active = 1)";

  $sql = "SELECT $cols FROM gift g JOIN wp_blogs b ON b.blog_id = g.blog_id "
    . " WHERE $where $in_stock $private "
    . " ORDER BY varAmount ASC, unitAmount ASC LIMIT " . $limit;

  if ($_GET['sql'] == 'yes')
    pre_dump($sql);

  $gifts = $wpdb->get_results($sql, ARRAY_A);

  return array_map('prepare_gift', $gifts);
}
function get_gift_where($where, $show_private = false) {
  $gifts = get_gifts_where($where, array(
    'limit' => 1,
    'show_all' => $show_private));
  return $gifts[0];
}

function get_gifts_by_tag($tag, $limit = 10, $featured_only = false, $show_private = false) {
  $where = build_tag_query("g.tags", $tag,$tag2,$tag3);
  return get_gifts_where($where, array('limit' => $limit, 'show_private' => $show_private));
}

function get_gifts_by_charity($blog_id, $limit = 10) {
  $where = "g.blog_id = $blog_id";
  return get_gifts_where($where, array('limit' => $limit, 'show_private' => TRUE));
}

function get_gifts_by_event($event_id, $limit = 10) {
  $tags = get_fr_tags($event_id);
  return get_gifts_by_tag($tags, $limit, FALSE, TRUE);
}

function get_gifts_by_ids($ids, $limit = 10){
  $ids = implode(",", as_ints($ids));
  $where = "g.id IN (".$ids.")";
  return get_gifts_where($where, array('limit' => $limit));
}

function tag_like($tag) {
  $tag = like_escape($tag);
  return "g.tags LIKE '%$tag%'";
}

function tag_named($tag) {
  global $REGIONS,$CAUSES,$FOCUS;
  return eor($REGIONS[$tag], $CAUSES[$tag], $FOCUS[$tag]);
}

function list_gifts($args) {
  global $wpdb, $GIFTS_LOC;

  $conds = array();
  $title = array();

  if (intval($args['cols']) > 0)
    unset($args['cols']);
  if ($args)
    extract($args);

  $tags1 = as_array($tags1);
  if (count($tags1) > 0) {
    $conds[] = implode(' OR ',array_map('tag_like', $tags1));
    $title =array_merge($title, array_map('tag_named', $tags1));
  }

  $tags2 = as_array($tags2);
  if (count($tags2) > 0) {
    $conds[] = implode(' OR ',array_map('tag_like', $tags2));
    $title =array_merge($title, array_map('tag_named', $tags2));
  }

  $tags3 = as_array($tags3);
  if (count($tags3) > 0) {
    $conds[] = implode(' OR ',array_map('tag_like', $tags3));
    $title =array_merge($title, array_map('tag_named', $tags3));
  }

  if ($exclude == 'no_browser')
    $conds[] = tag_like('gift-browser');

  foreach (as_array($exclude) as $extag) {
    $conds[] = "NOT(" . tag_like($extag) . ")";
  }

  if (!empty($blog_id))
    $conds[] = $wpdb->prepare("g.blog_id = %d", $blog_id);

/*
  if ($include_small_gifts)
    $min_amt = 0;
  else if ($min_amt == 0)
    $min_amt = 5;
*/

  if (!empty($min_amt))
    $conds[] = $wpdb->prepare("g.unitAmount >= %d ", $min_amt);
  if (!empty($max_amt))
    $conds[] = $wpdb->prepare("g.unitAmount <= %d ", $max_amt);
  if ((count($title) == 0) && (!empty($min_amt) || !empty($max_amt)))
    $title[]= "by price";

  if (count($title) == 0)
    $title[] = "all gifts";

  if (count($conds) > 0)
    $where = "(" . implode(") AND (", $conds) . ")";

  if (empty($cols))
    $cols = "*";

  return array(
    'items' => get_gifts_where($where, $args, $cols),
    'title' => eor($page_title, implode(', ', $title))
  );
}


function prepare_gift($g) {
  // TODO: return a new array so we don't expose fields?
  if($g['unitsWanted']<=0 || $g['active']!=1) $g['hidden'] = true; 
  else $g['hidden'] = false;
  
  unset($g['unitsDonated']);
  unset($g['mature']);
  unset($g['spam']);

  $bid = intval($g['blog_id']);
  $id = $g['id'];
  $g['siteurl'] = get_blog_option($bid,'siteurl');
  if ($g['title'] == null)
    $g['title'] = "Give " . $g['displayName'];
  $g['excerpt'] = stripslashes($g['excerpt']);
  $g['teaser'] = truncate_words($g['excerpt'], 60);
  $g['description'] = stripslashes($g['description']);
  $g['imageUrl'] = $g['image'];

  return $g;
}

function get_stories_by_charity($id, $limit = 10, $order) {
  global $wpdb;

  $where = $wpdb->prepare("ds.blog_id = %d", $id);
  return get_stories_where($where, $limit, $order);
}

function get_stories_by_donor($id, $limit = 10, $order) {
  global $wpdb;

  $where = $wpdb->prepare("ds.donor_id = %d", $id);
  return get_stories_where($where, $limit, $order);
}

define(USER_CUSTOM_STORIES_META_KEY,'user_custom_stories');

function get_stories_by_user($id, $limit = 10, $order) {
  global $wpdb;
  global $blog_id;

  $custom_stories = get_user_meta($id,USER_CUSTOM_STORIES_META_KEY,1);
  $custom_stories = explode(",",$custom_stories);
  $cs = array();
  if (is_array($custom_stories) && !empty($custom_stories)) {
	foreach($custom_stories as $k=>$s) {
	  if(strpos($s,"-")!==FALSE)
	    $cs[]=$wpdb->prepare("%s",trim($s));
	}
    $cs = "'".implode("','",$custom_stories)."'";
  }
  
  $sql = "SELECT DISTINCT ds.* from donationStory ds 
    JOIN donationGifts dg on dg.blog_id=ds.blog_id and dg.story=ds.post_id 
    JOIN donation d on dg.donationID=d.donationID 
    JOIN donationGiver giver on giver.ID=d.donorID 
    JOIN wp_blogs b ON ds.blog_id = b.blog_id 
    WHERE NOT(ds.post_image = '') 
    AND ((b.public = '1' AND b.archived = '0' AND b.mature = '0' AND b.spam = '0' AND b.deleted ='0')
    OR b.blog_id = '".intval($blog_id)."')     
	AND (user_id = ".intval($id)." ".
    (!empty($cs)?" OR CONCAT(b.blog_id,'-',ds.post_id) IN (".$cs.")":'').")	
	ORDER BY $order LIMIT $limit";

  // TODO - use wpdb->prepare.  But it was returning blank string, so I changed it.
  // echo $sql; exit(); //debug($sql,true);
  
  $r = array_map('prepare_story', $wpdb->get_results($sql));
  return $r;
}

function get_stories_by_event($id, $limit = 10, $order) {
  global $wpdb;
  global $blog_id;

  if ($id === NULL)
    return array();

  $sql =
    "SELECT DISTINCT ds.* from donationStory ds 
    JOIN donationGifts dg on dg.blog_id=ds.blog_id and dg.story=ds.post_id 
    JOIN wp_blogs b ON ds.blog_id = b.blog_id 
    WHERE NOT(ds.post_image = '') 
    AND ((b.public = '1' AND b.archived = '0' AND b.mature = '0' 
    AND b.spam = '0' AND b.deleted ='0') 
    OR b.blog_id = '".intval($blog_id)."') ";

  if ($id) {
    $sql .= "AND dg.event_id = $id ";
  }

  $sql .= "ORDER BY $order LIMIT $limit";
  if ($_REQUEST['sql'] == 'yes')
    pre_dump($sql);

  // TODO - use wpdb->prepare.  But it was returning blank string, so I changed it.
  // echo $sql; exit();

  $r = array_map('prepare_story', $wpdb->get_results($sql));
  return $r;
}

function get_charity_gift_thumb ($blog_id, $size) {
  if (empty($blog_gift_samples))
    $gift_id = 0;
  else
    $gift_id = $blog_gift_samples[0]['id'];
  if(!is_array($size)) $size = array(120,90);
  return gift_image_src($gift_id, $size);
}

function get_gift_details($id) {
  global $post, $wpdb;  

  $g = get_gift_where("g.id = $id", array('show_all' => true));
  $post_id = intval($g['post_id']);
  $blog_id = intval($g['blog_id']);
  switch_to_blog($blog_id);
  $post = get_post($post_id);
  setup_postdata($post);
  $g['description'] = xml_entities(apply_filters('the_content', get_the_content()));
  restore_current_blog();

  $g['stories'] = get_stories_by_gift($id, 4);

  foreach($g['stories'] as $k=>$v) {
	$v->post_title = xml_entities($v->post_title);
	$v->post_name = xml_entities($v->post_name);
	$v->post_excerpt = xml_entities($v->post_excerpt);
	$g['stories'][$k] = $v;
  }

  $g['excerpt'] = xml_entities($g['excerpt']);
  $g['teaser'] = xml_entities($g['teaser']);
  
  for ($i = 0; $i < count($g['stories']); $i++) {
    // Encode the URLs
    $g['stories'][$i]->post_image = strip_filename($blog_id, $g['stories'][$i]->post_image);
  }

  if($g['towards_gift_id']>0){
    $master = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT displayName, unitAmount, current_amount FROM gift "
        ."WHERE gift.id = %d ",
        $g['towards_gift_id']
      )
    );
    if($master != NULL){
      $g['master_amount'] = $master->unitAmount;
      $g['master_current'] = $master->current_amount;
      $g['master_name'] = $master->displayName;
      $g['full_count'] = floor($g['master_amount']/$g['unitAmount']);
      $g['left_count'] = floor(($g['master_amount'] - $g['master_current']) / $g['unitAmount']);
      $g['left_amount'] = $g['master_amount'] - $g['master_current'];
      $g['current_percent'] = 100*$g['master_current']/$g['master_amount'];
    } else {
      $g['master_amount'] = 0;
      $g['master_current'] = 0;
      $g['master_name'] = '';
      $g['full_count'] = 0;
      $g['left_count'] = 0;
      $g['left_amount'] = 0;
      $g['current_percent'] = 0;
    }
  }

  if($g['hidden']) $g['title']=''; 

  return $g;
}


function no_redirect($location, $status = NULL) {
  status_header(200);

  echo "REDIRECT TO <a href=\"$location\">$location</a>";
  echo stacktrace(NULL, 3);
  return false;
}

function pre_dump($s, $admin = FALSE) {
  global $no_redirect;
  if ($admin && !current_user_can('level10'))
    return;

  if (function_exists('krumo'))
    krumo($s);
  else 
    echo "<pre style='background:white;color:black;'>" . esc_html(print_r($s, true)) . "</pre>";

  if ($no_redirect)
    return;

  add_filter('wp_redirect', 'no_redirect');
  $no_redirect = TRUE;
}
function cmt_dump($s) {
  echo "<!--\n" . print_r($s, true) . "\n-->";
}
function stack_dump($v) {
  if ($v === NULL)
    return "null";
  if ($v === TRUE)
    return "true";
  if ($v === FALSE)
    return "false";

  if (is_array($v)) {
    $i = 0;
    $s = "[";
    foreach ($v as $k=>$x) {
      $r .= $s;
      if ($k !== $i)
        $r.= esc_html($k) . "=>";
      $r .= stack_dump($x);
      $i = $k+1;
      $s = ", ";
    }
    return "$r]";
  }
  if (is_string($v)) {
    if (strlen($v) > 100)
      $v = esc_html(substr($v, 0, 100)) . "...";
    return '"' . $v . '"';
  }
  if (is_object($v)) {
    return "{object}";
  }
  return esc_html(print_r($v, true));
}
function stacktrace($e = NULL, $pop = 0) {
  if ($e == NULL) {
    $trace = debug_backtrace();
    array_shift($trace);
    for ($i = 0; $i < $pop; $i++) {
      array_shift($trace);
    }

    $result = "";
  } else {
    $trace = $e->getTrace();
    $result = 'Exception: "' . $e->getMessage() . '"';
  }

  foreach ($trace as $stack) {
    $result .= '<br/> at ';
    if($stack['class'] != '') {
      $result .= $stack['class'];
      $result .= '->';
    }
    $result .= $stack['function'];

    $result .= "(";
    $s = "";
    foreach ($stack['args'] as $k=>$v) {
      $result .= $s;
      $result .= stack_dump($v);
      $s = ", ";
    }
    $result .= ")";

    if ($stack['file']) {
      $file = str_replace($_SERVER["DOCUMENT_ROOT"],'', $stack['file']);
      $result .= " - <b>$file</b> line <b>{$stack['line']}</b>";
    }
  }

  return preg_replace('/cc_num=>"(.*?)"/', 'cc_num=>REMOVED', $result);
}

function dump($var, $title='') {
  echo "<!--\r\n $title";
  var_dump($var);
  echo "\r\n-->";
}

function draw_posts($posts) {
  for ($i = 0; $i < count($posts); $i++) {
    $p = $posts[$i];
    $tid = get_post_thumbnail_id($p->ID);
    $src = wp_get_attachment_image_src( $tid, array( 300,300 ), false, '' );
    $p->thumbnail_url = $src[0];
    draw_post($p);
  } 
}

function draw_stories($stories, $large = false) {
  for ($i = 0; $i < count($stories); $i++) {
    $story = $stories[$i];
    draw_story(array(
      'blog_id' => $story->blog_id,
      'id' => $story->post_id,
      'title' => $story->post_title,
      'ref' => $story->ref,
      'url' => $story->guid,
      'large' => $large,
      'excerpt' => $story->post_excerpt,
      'img' => $story->post_image
    ));
  } 
}

function draw_gifts(&$gifts) {
  for ($i = 0; $i < count($gifts); $i++) {
    draw_gift($gifts[$i]);
  }
}

function draw_articles($articles){
  for ($i = 0; $i < count($articles); $i++) {
    $a = $articles[$i];
    $tid = get_post_thumbnail_id($a->ID);
    $src = wp_get_attachment_image_src( $tid, array( 300,300 ), false, '' );
    $a->thumbnail_url = $src[0];
    draw_post($a);
  }
}

function truncate_words($details,$max)
{
  if(strlen($details)>$max)
  {
    $details = substr($details,0,$max);
    $i = strrpos($details," ");
    if ($i > 0) 
      $details = substr($details,0,$i);
    $details = $details . "...";
  }
  return $details;
}

function draw_story($s) {
  $large = ($s['large'] == true);
  $largest = ($s['large'] == 2);

?>
  <a id="story-<?= $s['ref'] ?>" class="story story-link ev divspan<?= ($large || $largest) ? ' large-story' : '' ?><?= $largest ? ' largest-story' : '' ?>" href="<?= $s['url'] ?>" rel="<?= $s['ref'] ?>">
    <span class="box">
      <span class="pic">
        <? draw_thumbnail($s['blog_id'], $s['img'], $largest ? 340 : ($large ? 225 : 150), $largest ? 275 : ($large ? 160 : 100)); ?>
        <span class="zoom"></span>
      </span>
      <span class="title">
        <?= $s['title'] ?>
      </span>
    </span>
    <? do_action('after_draw_story', $s); ?>
  </a>
<?
}

function track_id($id, $loc = NULL) {
  if (!empty($loc))
    $id = "$loc/$id";
  return $id;
}

function pay_link($id, $loc = NULL) {
  global $site_url, $GIFTS_EVENT, $event_id, $post;

  $pay_link = get_cart_link() . "?";
  if(strpos($pay_link,'.com')!==FALSE) 
    $pay_link = str_replace('https://','http://',$pay_link);

  if (!empty($_COOKIE['eid']))
    $GIFTS_EVENT = $_COOKIE['eid'];

  if(isset($post) || ($loc=='fb' && intval($GIFTS_EVENT)>0)) {
    if (!empty($GIFTS_EVENT))
      $pay_link .= "eid=$GIFTS_EVENT&";
    else if (!empty($event_id))
      $pay_link .= "eid=$event_id&";
  }

  return $pay_link . "item=" . track_id($id, $loc);
}

function details_link($id, $loc = NULL, $base_url='') {
  global $site_url;

  if ($base_url == '')
    $pay_link = $site_url . "/give/";
  else if (strpos($base_url,'http')!==0)
    $pay_link = $site_url . $base_url;
  else 
    $pay_link = $base_url;

  if ($id <= CART_GIVE_ANY)
    return $pay_link;
	
  return $pay_link . "#gift=" . track_id($id, $loc);
}

function draw_gift(&$g) {
  global $site_url, $GIFTS_V2, $GIFTS_EVENT, $GIFTS_LOC;

  $more_link = details_link($g['id'], $GIFTS_LOC);
  $pay_link = pay_link($g['id'], $GIFTS_LOC);
  $track_id = track_id($g['id'], $GIFTS_LOC);
  $tracked = is_user_logged_in() ? "" : " tracked";

  //echo '<pre>'.print_r($g,true).'</pre>';
  $price = (strpos($g['tags'],'variable')!==false?'other':'$'.$g['unitAmount']);
?>
  <div id="gift-<?=$g['id']?>" class="gift gift-v2 ev">
    <a class="pic more" href="<?= $more_link ?>">
      <?= make_img(gift_image_src($g['id']), array(190,140)) ?>
      <span class="price"><span class="cost"><?= $price ?></span><span class="notyet button green-button small-button">give <span class="cost"><?= $price ?></span></span></span>
      <span class="name"><?= $g['title'] ?></span>
    </a>
  </div>
<?
}

function draw_gift_details1($g) {
?>
  <div class="gift-details based">
    <a id="details-back" class="backlink ev" href="/give/">&laquo; <u>back to gift list</u></a>
    <div class="images">
      <div class="big-pic"><img src="<?= HTTP_IMAGE_HOST ?>image/fetch/w_320,h_240,c_fill,g_faces/<?=$g['image']?>" width="320" height="240"></div>
    </div>
    <div class="info">
<?
  global $site_url, $GIFTS_LOC;
  return pay_link($g['id'], $GIFTS_LOC);
}
function draw_gift_details2($g) {
?>
    </div>
    <div class="details">
      <?= $g['description']; ?>
    </div>
    <div id="stories" class="stories ev">
<? if ($g['stories']) draw_stories($g['stories']); ?>
    </div>
  </div>
<?
}

function draw_gift_details($g) {
  global $GIFTS_EVENT,$GIFTS_LOC,$event_id;

  $pay_link = pay_link($g['id'], $GIFTS_LOC);
  $track_id = track_id($g['id'], $GIFTS_LOC);
  $tracked = is_user_logged_in() ? "" : " tracked";

  draw_gift_details1($g);
?>
  <div class="heading"><?= $g['title'] ?></div>
  <p class="desc"><?= $g['excerpt'] ?></p>
  <div class="price">$<? echo $g['unitAmount']; if ($g['varAmount'] == 1) { echo '+'; } ?></div>
  <div id="pay" class="actions ev"><a href="<?= $pay_link ?>" id="<?= $track_id ?>" class="<? if ($GIFTS_EVENT > 0) echo 'pay-button '; ?> button give-button orange-button big-button ev<?=$tracked?>">DONATE &raquo;</a></div>
<?
  if(current_user_can("level_10")) {
    ?><div><a href="<?=get_cart_link()?>?avg=<?=$g['id']?>|1|<?=(!empty($GIFTS_EVENT)?$GIFTS_EVENT:$event_id)?>" class="editable">any amount</a></div><?	  
  }
?>
<?
  draw_gift_details2($g);

}

function draw_var_gift_details($g) {
  global $GIFTS_EVENT,$GIFTS_LOC,$event_id;

  $pay_link = pay_link($g['id'], $GIFTS_LOC);
  $track_id = track_id($g['id'], $GIFTS_LOC);
  $tracked = is_user_logged_in() ? "" : " tracked";

  draw_gift_details1($g);
?>
  <div class="heading"><?= $g['title'] ?></div>
  <p class="desc"><?= $g['excerpt'] ?></p>
  <form action="<?= get_cart_link() ?>" method="POST" class="var-gift-form">
    <div id="pay" class="price actions ev">Donate $
      <input name="eid" type="hidden" value="<?= $GIFTS_EVENT ?>">
      <input name="item" type="hidden" value="<?= $g['id'] ?>">
      <input name="amount" value="<?= $g['unitAmount'] ?>" size="3" class="varAmount">
      <input type="submit" id="<?=$track_id?>" class="<? if ($GIFTS_EVENT > 0) echo 'pay-button '; ?> button give-any-button give-button orange-button big-button ev<?=$tracked?>" value="give">
    </div>
  </form>
<?
  draw_gift_details2($g);

}

function draw_agg_gift_details($g) {
  global $GIFTS_EVENT,$GIFTS_LOC,$event_id;

  $pay_link = pay_link($g['id'], $GIFTS_LOC);
  $pay_link_full = pay_link($g['id'], $GIFTS_LOC.'f');
  $track_id = track_id($g['id'], $GIFTS_LOC);
  $track_id_full = track_id($g['id'], $GIFTS_LOC.'f');
  $tracked = is_user_logged_in() ? "" : " tracked";

  draw_gift_details1($g);
?>
  <div class="heading"><?= $g['title'] ?></div>
  <p class="desc"><?= $g['excerpt'] ?></p>
  <p>
    <div class="progress-bar">
      <div class="left">$<?=$g['master_current']?> of $<?=$g['master_amount']?> raised</div>
      <div class="right"><b>$<?=$g['left_amount']?></b> needed</div>
      <div class="bar" style="clear:both;">
        <div class="indicator" style="width: <?=$g['current_percent']?>%;"></div>
      </div>
    </div>
  </p>
  <div id="pay" class="ev">
    <a href="<?= $pay_link ?>" id="<?= $track_id ?>" class="<? if ($GIFTS_EVENT > 0) echo 'pay-button '; ?> button medium-button orange-button ev<?=$tracked?>">
    Donate $<?=$g['unitAmount']?> &raquo;</a>
    <? if ($g['left_amount'] != $g['unitAmount']) { ?>
      <span class="or-finish-it">
      or
      <a href="<?= $pay_link_full.',%2B'.$g['left_count'] ?>" id="<?=$track_id_full ?>" class="<? if ($GIFTS_EVENT > 0) echo 'pay-button '; ?> button medium-button orange-button ev<?=$tracked?>">Finish it ($<?=$g['left_amount']?>) &raquo;</a>
      </span>
    <? } ?>
  </div>
<?
  if(current_user_can("level_10")) {
    ?><div><a href="<?=get_cart_link()?>?avg=<?=$g['towards_gift_id']?>|1|<?=(!empty($GIFTS_EVENT)?$GIFTS_EVENT:$event_id)?>" class="editable">any amount</a></div><?	  
  }
?>
<? 
  draw_gift_details2($g); 
}

function draw_gift_offer($g) {
  global $site_url, $GIFTS_LOC;
  $more_link = details_link($g['id'], $GIFTS_LOC);
  $pay_link = pay_link($g['id'], $GIFTS_LOC);
?>

<?
}

function draw_post($p) {
  setup_postdata($p);
?>
  <a class="post-link" href="<?= get_permalink($p->ID); ?>">
    <div class="box">
      <div class="pic">
        <? draw_thumbnail(1, $p->thumbnail_url, 70,80); ?>
        <div class="zoom"></div>
      </div>
      <div class="title">
        <?= get_the_title($p->ID) ?>
      </div>
    </div>
  </a>
<?
}

function draw_widget_title($s, $r = null, $narrow = false)
{
  if (!empty($r))
    echo $r;
  if (!empty($s)) {
    ?><h3 style="padding-left:10px;<? if ($narrow == true) echo 'margin-right: 200px;'; ?>"><?= htmlspecialchars($s) ?></h3> <div class="clearer"></div><?
  }
}

function strip_filename($blog_id, $src) {
  if ($blog_id > 0) {
    $src = preg_replace("/^.*\/files\//", "", $src);
    $src = "/$blog_id/$src";
  }
  return $src;
}

function get_thumbnailed($blog_id, $src, $w = 150, $h = 150, $zc = 1) {
  if ($zc)
    $zc = "&zc=1";
  else
    $zc = "";

  if (empty($src)) 
    return THUMBNAILER_URL . 'default.jpg'; 

  $s = strip_filename($blog_id, $src);
  return SITE_URL . "/thumbs/${w}x${h}${s}";
}

// bypass: 
// fillMode: 0 = don't, 1 = width & height, 2 = width only
// clean: ?
function draw_thumbnail($blog_id, $src, $w = 150, $h = 150, $bypass=false, $title = '', 
  $node='img', $return=false, $fillMode=1, $clean = false, $url_only = false) {

  $w = intval($w);
  $h = intval($h);

  if (!$clean) { 
    if ($bypass) {
      $src = content_version() . $src;
    } else {
      $src = get_thumbnailed($blog_id, $src, $w, $h, $fillMode = 1); 
    }
  }

  if($url_only) return $src;
  
  if (!empty($title))
    $node .= ' title="' . esc_attr($title) . '"';
  
  switch ($fillMode) {
    case 0: $img = "<$node src=\"$src\" />"; break;
    case 1: $img = "<$node src=\"$src\" width=\"$w\" height=\"$h\" />"; break;
    case 2: $img = "<$node src=\"$src\" width=\"$w\" />"; break;
  }

  if (!$return)
    echo $img;

  return $img;
}


function draw_widget($widget_func, $args, $instance = null) {
  if ($instance == null)
    $instance = $args;

  extract($args);
  extract($instance);
  $cols = intval($cols);
  if ($cols == 0) $cols = 2;

  ob_start();
  if (call_user_func($widget_func, $args) == 'cancel' && $skip_empty !== false) { 
    ob_end_clean();
    return;
  }
  $str = ob_get_contents();
  ob_end_clean();

  $banner = ($banner == true || $banner == 'true') ? "banner-widget" : "";
  echo '<div class="widget promo-widget '.$widget_func . ' ' . $widget_class . ' ' . $banner.' widget' . $cols . '"><div class="interior">';
  draw_widget_title($title, $see_all, $show_all);
  echo $str;
  echo '</div></div>';
}

function draw_home_widget($promo, $widget_func, $args, $instance = null) {
  global $post;

  if ($instance == null)
    $instance = $args;

  extract($args);
  extract($instance);

  $promo = get_posts("post_type=promo&name=$promo");
  $cols = 6;
  if (count($promo) > 0) {
    $post = $promo[0];
    setup_postdata($post);

    ?><div class="widget promo-widget headline-widget widget6 <?=$widget_func?>-headline">
      <h3 <? if ($show_all == true) echo ' style="padding-right: 200px;"'; ?>><? the_title(); ?></h3>
    </div><?

    if ($has_text !== false) {
      $cols = 4;
      ?><div class="widget promo-widget headline-widget widget2"><div class="interior">
        <? the_content(); ?>
      </div></div><?
    }
  }

  $args['cols'] = $cols;
  $args['widget_class'] = 'home-widget';
  unset($args['title']);

  draw_widget($widget_func, $args, $instance);
}

function json_template($template_func, $args) {
  echo '<script type="text/html" id="' . $template_func . '">';
  echo "\r\n";

  $a = array();
  foreach ($args as $k=>$v) {
    $a[$v] = '${' . $v . '}';
  }
  call_user_func($template_func, $a);
  
  echo '</script>';
  echo "\r\n";
}

function get_charities_by_tag($tag, $limit = 4) {
  global $wpdb;
  $where = build_tag_query("g.tags", $tag,$tag2,$tag3);
  $charities = get_charities_where($where, $limit);
  foreach($charities as $k=>$v){
    $charities[$k]['blog_name'] =
      $wpdb->get_var("SELECT option_value FROM wp_".$v['blog_id']."_options "
        ."WHERE option_name = 'blogname'");
    $charities[$k]['blog_desc'] =
      $wpdb->get_var("SELECT option_value FROM wp_".$v['blog_id']."_options "
        ."WHERE option_name = 'blogdescription'");
    $charities[$k]['site_url'] =
      $wpdb->get_var("SELECT option_value FROM wp_".$v['blog_id']."_options "
        ."WHERE option_name = 'siteurl'");
  }
  return $charities;
}

function get_charities_where($where, $limit = 4){
  global $wpdb;
  global $blog_id;
  $main_dom = $wpdb->get_var("SELECT domain FROM wp_blogs WHERE blog_id = 1 ");

  $sql = "SELECT b.blog_id, b.*, "
  . " CONCAT('http://$main_dom/wp-content/charity-images/charity-', "
  . " REPLACE(domain,'.$main_dom',''),'.jpg') as img "
  . " FROM wp_blogs b JOIN gift g "
  . " WHERE g.blog_id = b.blog_id AND ((b.public = '1' "
  . " AND b.archived = '0' AND b.mature = '0' "
	. " AND b.spam = '0' AND b.deleted ='0') OR b.blog_id = '".intval($blog_id)."') "
  . " AND $where GROUP BY b.blog_id ORDER BY RAND() LIMIT $limit ";
  return $wpdb->get_results($sql, ARRAY_A);
}

function draw_charity($c) {
?>
  <a class="charity" href="<?= $c['site_url']; ?>">
    <div class="box">
      <div class="pic">
        <img src="<?= $c['img'] ?>" width="225" height="100" />
        <div class="zoom"></div>
      </div>
      <div class="title">
        <?= $c['blog_name'] ?>
      </div>
    </div>
  </a>
<?
}

if ( false === function_exists('lcfirst') ):
    function lcfirst( $str )
    { return (string)(strtolower(substr($str,0,1)).substr($str,1));}
endif;

function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }
function selfURL(){ if(!isset($_SERVER['REQUEST_URI'])){ $serverrequri = $_SERVER['PHP_SELF']; }else{ $serverrequri = $_SERVER['REQUEST_URI']; } $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); return $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri; }

function get_donor($id){
  global $wpdb;
  $sql = $wpdb->prepare("SELECT * FROM donationGiver WHERE id = %d",$id);
  return $wpdb->get_row($sql);
}

function get_donor_by_uid($user_id){
  global $wpdb;
  $sql = $wpdb->prepare("SELECT * FROM donationGiver WHERE user_id = %d",$user_id);
  //echo $sql;
  return $wpdb->get_row($sql);
}

function get_displayname($user_id, $first_only = true) {
  $u = get_userdata($user_id);

  if ($first_only)
    $name = $u->first_name;
/*
STEVE: In WP3.2+ this can sometimes return the wrong value when called from site
other than #1.  Removing and re-testing the caching
  if (empty($name)) {
    $name = $u->nickname; // same as displayname - but displayname seems overcached? 
  }
*/
  if (empty($name))
    $name = $u->display_name;
  if (!empty($name))
    return fix_name($name);

  return $u->user_nicename;
}

function get_firstname($user_id) {
  return get_displayname($user_id, true);
}

function get_donor_fullname($donor,$default,$first_only=false){
  $id = absint($donor);
  if ($id > 0)
    $donor = get_donor($id);

  if($donor != NULL && $donor->firstName != ''){
    $donor_full_name = $donor->firstName . ($donor->lastName==''||$first_only?'':' ' .$donor->lastName);
  } else {
    if($first_only) {
      $defaults = explode(" ",$default);
      $donor_full_name = $defaults[0];
    } else {
      $donor_full_name = $default;
    }
  }
  return $donor_full_name;
}

function get_donor_fullname_by_uid($user_id,$default='',$first_only=false){
  $donor = get_donor_by_uid($user_id);
  return get_donor_fullname($donor->ID, $default, $first_only);
}

function get_tipnote(){
  global $wpdb;

  if(isset($_REQUEST['gift'])){
    $blog_id = $wpdb->get_var(
      $wpdb->prepare("SELECT blog_id FROM gift WHERE id=%d",$gift));

    $pay_tipnote = $wpdb->get_row(
      $wpdb->prepare(
      "SELECT * FROM wp_%d_posts "
        . "WHERE post_type='promo' AND post_name='pay-tipnote'",$blog_id));

    //if tip note is not defined at charity level, it will use blog 1
    if($pay_tipnote==NULL)
    $pay_tipnote = $wpdb->get_row(
      "SELECT * FROM wp_1_posts "
        . "WHERE post_type='promo' AND post_name='pay-tipnote'");

    return '<strong>'.$pay_tipnote->post_title.'</strong> '
      .$pay_tipnote->post_content;

  } else {

    $pay_tipnote = $wpdb->get_row(
      "SELECT * FROM wp_1_posts "
        . "WHERE post_type='promo' AND post_name='pay-tipnote'");

    return '<strong>'.$pay_tipnote->post_title.'</strong> '
      .$pay_tipnote->post_content;
  }
}

function get_recurring_tipnote(){
  global $wpdb;

  $r_tipnote = $wpdb->get_row(
    "SELECT * FROM wp_1_posts "
      . "WHERE post_type='promo' AND post_name='recurring-tipnote'");

  return '<strong>'.$r_tipnote->post_title.'</strong> '
    .$r_tipnote->post_content;
}

function buildDropDownOptions($arrayList,$default='', $none = TRUE){
  if ($none) {
    $ret='<option value="">--</option>';
  }
	foreach($arrayList as $k=>$v){
		$ret .= '<option value="'.$k.'" '.($default == $k?'selected="selected"':'').'>';
		$ret .= $v;
		$ret .= '</option>';
	}
	return $ret;
}


// ----------- Registration functions

function confirm_new_email($user_id, $new_email) {
  // We trust anyone who's an admin of any kind.
  if (current_user_can("level_2")) {
    approve_new_email($user_id, $new_email);
    return true;
  }

  $hash = md5( $new_email . time() . mt_rand() );
  $new_user_email = array(
      'hash' => $hash,
      'newemail' => $new_email
      );
  update_option( $user_id . '_new_email', $new_user_email );

  $url = add_query_arg('r', "$hash/$user_id", wp_login_url() );
  $user = get_userdata($user_id);

  $content = "$user->user_firstname,<br/>
<br/>
You recently changed the e-mail address of your SeeYourImpact account.<br/>
Please click on the following link to confirm the change:<br/>
<br/>
  $url<br/>
<br/>
(You can safely ignore this e-mail if you did not intend to make this change.)";

  $e = new EmailEngine;
  $e->sendMailSimple("$user->user_firstname $user->user_lastname",
    $new_email,
    "Please confirm your changed e-mail address",
    $content,
    '',
    false,
    false,
    "emailchange"
  );

  return true;
}

function approve_new_email($user_id, $new_email) {
  global $wpdb;

  $user = get_userdata($user_id);
  unset($user->user_pass);
  $user->ID = $user_id;
  $user->user_email = esc_html( trim( $new_email ) );
  wp_update_user( get_object_vars( $user ) );

  debug("Changed to $user->user_email", true, "E-mail changed: $user->user_login");

  $login = $wpdb->get_var( $wpdb->prepare(
    "SELECT user_login FROM {$wpdb->signups} WHERE user_login = %s",
    $user->user_login ));
  if ($login)
    $wpdb->query( $wpdb->prepare(
      "UPDATE {$wpdb->signups} SET user_email = %s WHERE user_login = %s",
      $user->user_email, $user->user_login ) );

  delete_option( $user_id . '_new_email' );
}

function get_reset_password_url($user_id) {
  $hash = md5( $new_password . time() . mt_rand() );
  update_user_meta( $user_id, 'reset_password', $hash);

  $url = add_query_arg('r', "$hash/$user_id", wp_login_url() );
  return $url;
}

function confirm_new_password($user_id, $email, $new_password) {
  $url = get_reset_password_url($user_id);
  $user = get_userdata($user_id);

  $content = "You recently requested a new password for your Impact Page on SeeYourImpact.org<br/>
<b>To choose a new password</b>, please click on the following link:<br/>
<br/>
  $url<br/>
<br/>
(You can safely ignore this e-mail if you did not intend to make this change.)";

  SyiMailer::altsend(array(
    'recipient' => "$user->user_firstname $user->user_lastname <$email>",
    'subject' => "Please confirm your new SeeYourImpact.org password",
    'mail_body' => "clean",
    'variables' => array(
      'content' => $content
    ),
  ));

  debug("Sent to $user->user_email<br>Reset: $url", true, "Password reset request: $user->user_login");
}

function approve_new_password($user_id, $new_password) {
  global $wpdb;

  $user = get_userdata($user_id);
  $user->ID = $user_id;
  $user->user_pass = $new_password;
  wp_update_user( get_object_vars( $user ) );

  delete_user_meta( $user_id, 'reset_password' );
}

function get_donor_info($donorID) {
  global $wpdb;

  $row = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM donorInfo WHERE donorID=%d", $donorID), ARRAY_A);

  if ($row == null)
    $row = array();

  return $row;
}

function update_donor_info($donorID, $data) {
  global $wpdb;

  $data['donorID'] = $donorID;
  $data['lastUpdate'] = current_time('mysql');
  $wpdb->replace('donorInfo', $data);
}

function get_post_for_email($id, &$url, &$title, &$content, &$image) {
  $post = get_post($id);

  $url = get_permalink($id);
  $title = stripslashes($post->post_title);
  $content = stripslashes($post->post_content);
  $content = str_replace(array("\xc2", "[youtube]", "[/youtube]"), " ", $content);

  preg_match_all('/<img[^>]+>/i', $content, $images);
  if (is_array($images))
    $image = str_replace('<img ', '<img style="border: 5px solid #ddd;" ', $images[0][0]);

  $content = strip_tags(nl2br($content), '<p><strong><em><ul><ol><li><a><br>');
  $content = str_replace("][/", "] [/", $content); // Fix issue where shortcodes are empty and don't get stripped
  $content = strip_shortcodes($content);
}

function _get_avatar( $id ) {
$avatar_file = bb_get_usermeta( get_post_author_id(), 'bp_core_avatar_v1' );
$url = BB_PATH . $avatar_file;

if ( strlen( $avatar_file ) ) {
return '<img src="' . attribute_escape( $url ) . '" alt="" class="avatar photo" width="50" height="50" />';
} else {
$default_grav = bb_get_active_theme_uri() . 'images/mystery-man.gif';
$user_email = bb_get_user_email( $id );
$gravatar = 'http://www.gravatar.com/avatar/' . md5( $user_email ) . '?d=' . $default_grav . '&s=50';
return '<img src="' . attribute_escape( $gravatar ) . '" alt="" class="avatar photo" width="50" height="50" />';
}
return;
}

function ensure_logged_in() {
  if (!is_user_logged_in()) {
    $url = site_url($_SERVER["REQUEST_URI"]);
    wp_redirect( wp_login_url( $url ));
    die();
  }
}
function ensure_logged_in_admin($subsite = FALSE) {
  ensure_logged_in();

  global $blog_id;
  if ($blog_id == 1)
    $subsite = FALSE;

  if ( !current_user_can($subsite ? 'level_2' : 'level_10') ) wp_die('No access');
}


////////////////////////////////////////////////////////////////////////////////


function get_media_meta($media, $key) {
  if (!array($media) || count($media)==0) return null;
  $meta_urls = array(
    'youtube'=>'http://youtube.com/get_video_info?video_id=[id]', 
    'vimeo'=>'http://vimeo.com/api/v2/video/[id].php');

  if($media['site']=='youtube') {
    if ($key=='thumbnail')	  
      return 'http://img.youtube.com/vi/'.$media['id'].'/0.jpg';

	$content = (file_get_contents(str_replace('[id]', $media['id'], $meta_urls[$media['site']])));  
	parse_str($content, $meta);

//pre_dump($meta);

    return $meta[$key];
  
  } else if($media['site']=='vimeo') {
    if ($key=='thumbnail')	  	  
      $key = 'thumbnail_small';

    $content = unserialize(file_get_contents(str_replace('[id]', $media['id'], $meta_urls[$media['site']])));  

//pre_dump($content[0]);

	return $content[0][$key];

  }
}

function get_media_link($str, $type='array', $from_wp=false) {
  $sites = array();
  $sites[] = array('youtube','youtube.com','http://youtube.com/?v=[id]',
    array('.*youtube\.com.*\?.*v\=([A-Za-z0-9\-_]{11})',
	  '.*youtube\.com\/embed\/([A-Za-z0-9\-_]{11})'));
  $sites[] = array('vimeo','vimeo.com','http://vimeo.com/[id]', 
    array('.*vimeo\.com\/([0-9]{7,8})'));
    
  foreach ($sites as $site) {    
	foreach ($site[3] as $pattern) {
      $matches = array();
	  preg_match('/'.($from_wp?'\['.$site[0].'\]':'').$pattern.'/', $str, $matches);
      if (!empty($matches[1])) {
		return array(
		  'site'=>$site[0], 
		  'id'=>$matches[1], 
		  'url'=>str_replace('[id]',$matches[1],$site[2]),
		  'sc'=>'['.$site[0].']'.str_replace('[id]',$matches[1],$site[2]).'[/'.$site[0].']'
		);	  
	  }
	}
  }  
  return null;
}

function br2nl($text) { return preg_replace('#<br\s*?/?>#i', "\n", $text); }
function strip_non_alphanumunderscore ($str) { return preg_replace("/[^a-zA-Z0-9_\s]/","",$str); }
function limit_br ($str,$max=2) { return preg_replace("/<br\\s*?\/??>(<br\\s*?\/??>)+/i",str_repeat("<br />",$max),$str); }

function clean_text($t) {
  // First, replace UTF-8 smart characters
  $t = str_replace(
   array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
   array("'", "'", '"', '"', '-', '--', '...'), $t);

  // Next, replace their Windows-1252 equivalents.
  $t = str_replace(
   array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
   array("'", "'", '"', '"', '-', '--', '...'), $t);

  $t = utf8_encode(xml_entities($t));
  return trim($t);
}

function to_store ($str,$ok_tags='') { return (str_replace(array("\t","\0","\x0B","\r","\n"),"",limit_br(nl2br(strip_tags(clean_text($str),$ok_tags))))); }
function to_print ($str) { return str_replace("<br />","\r",stripslashes($str)); } 

function to_store_html($html, $strip = TRUE) {
  $HTML = "<b><strong><strike><i><em><p><br>";
  if($strip && current_user_can("level_2"))
    $HTML .= "<a><h3><h2><h1><iframe><table><tr><td><div><span>";

  return to_store($html, $HTML);
}
function to_store_post_content($html, $strip = TRUE) {
  return str_replace("<br />","\n", to_store_html($html, $strip));
}


function force_login() {
  if (!is_user_logged_in()) {
    $url = site_url($_SERVER["REQUEST_URI"]);
    wp_redirect( wp_login_url( $url ));
    die();
  }
}

function comma_list($list, $conj = 'and', $comma = ', ') {
  $c = count($list) - 1;
  $sep = " ";

  for ($i = $c; $i >= 0; $i--) {
    $l .= trim($list[$c - $i]);
    switch ($i) {
      case 0: break;
      case 1: $l .= "$sep$conj "; break;
      default: $l .= $comma; $sep = $comma; break;
    }
  }

  return $l;
}

function get_member_link($id = 0, $page = NULL, $action = NULL) {
  global $bp;

  if ($id == 0)
    $id = $bp->displayed_user->id;
  $prof = bp_core_get_user_domain($id);
  if (!empty($action))
    $page = "$page/$action";
  else if ($id == $bp->displayed_user->id && $page == $bp->default_component)
    $page = NULL;
  return $prof . $page;
}

function do_nothing() {}

function resize_img($src, $w=320, $h=240, $dst='') {
  if ( empty($dst) ) $dst = $src;
  $cmd = "convert -resize {$w}x{$h} -auto-orient -unsharp 0x.5 $src $dst";
  exec($cmd);
}

if (!function_exists('get_post_by_slug')) :
function get_post_by_slug($slug, $pages = false)
{
    if (empty($slug))
        return false;

    if (is_array($pages) && !empty($pages))
    {
        foreach ($pages as $page)
            if ($page->post_name == $slug)
                return $page;
    }

    global $wpdb;
    return $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_name = '{$wpdb->escape($slug)}'");
}
endif;


function get_post_by_name($name,$blog_id=1)  {
  global $wpdb;
  $blog_id = intval($blog_id);
  if($blog_id>0) {
	$sql = $wpdb->prepare("SELECT * FROM wp_".$blog_id."_posts WHERE post_name = %s",$name);
    return $wpdb->get_row($sql);	
    
  }
}

function get_post_metas($keys, $post_id, $blog_id=1) {
  global $wpdb;
  $blog_id = intval($blog_id);
  $post_id = intval($post_id);
  if($post_id>0 && $blog_id>0 && is_array($keys) && count($keys)>0) {
	$fields = array();
	$tables = array();
	$wheres = array();
    foreach($keys as $key) {
      $fields[] = $key.'_tbl.meta_value AS '.$key;
	  $tables[]	= 'wp_'.$blog_id.'_postmeta AS '.$key.'_tbl';
	  $wheres[] = $key.'_tbl.post_id='.$post_id.' AND '.$key.'_tbl.meta_key='."'".$key."'";
	}
    $fields = implode(", ",$fields);
    $tables = implode(", ",$tables);
    $wheres = implode(" AND ",$wheres);	
	    
    $sql = "SELECT ".$fields." FROM ".$tables." WHERE ".$wheres;
    //pre_dump($sql);
	//debug($sql,true);	
	return $wpdb->get_row($sql);  
  } 
}

function dp($val, $var = '') {
  global $payment_debug;
  global $payment_start;
  if($payment_debug == null) {
    $payment_start = time();
  }

  $msg = '';
  if (!empty($var))
    $msg .= "$var=";
  $msg .= "$val\n";

  $payment_debug .= $msg;
  SyiLog::log('dp', rtrim($msg));
}

function dp_sql($sql) {
  global $wpdb;

  $wpdb->query($sql);
  $error = mysql_errno();
  if ($error > 0) {
    dp_end("SQL ERROR: $sql\n".mysql_errno() . ": " . mysql_error(), true, "PAYMENT ERROR");
    die();
  }
}

function dp_end($msg='', $email=true, $subject='Debug') {
  global $payment_debug;
  global $payment_start;

  if (!empty($msg))
    dp($msg);

  $payment_stop = time();
  dp("PAYMENT START AT: ".$payment_start);
  dp("PAYMENT STOP AT: ".$payment_stop);
  dp("PAYMENT TOTAL TIME: ".($payment_stop-$payment_start));

  dp('PROCESSING MAIL QUEUE LAST');

  debug($payment_debug, $email, "$subject");

  process_mail_queue();
}

function replace_action($tag, $func, $priority = NULL, $args = NULL) {
  if (has_action($tag, $tag))
    remove_action($tag, $tag);
  add_action($tag, $func, 
    $priority === NULL ? 10 : $priority, 
    $args === NULL ? 1 : $args);
}
// http://recursive-design.com/blog/2008/03/11/format-json-with-php/
/**
 * Indents a flat JSON string to make it more human-readable.
 *
 * @param string $json The original JSON string to process.
 *
 * @return string Indented version of the original JSON string.
 */
function json_pretty($json) {

    if (!is_string($json)) {
      $json = json_encode($json);
    }

    // because PHP's json_encode is STUPID
    $json = str_replace('\\/', '/', $json);

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

        // If this character is the end of an element,
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}

function main_site_request($page) {
  global $blog_id;

  $server = parse_url(SITE_URL);
  if ($_SERVER['SERVER_NAME'] == $server['host'])
    return $page; // No need to do a cross-site request

  return "/cors/" . SITE_URL . $page;
}

function custom_error_handler($eno, $estr, $efile, $eline) {
  if (isset($_REQUEST['strict']) && $_REQUEST['strict'] == 'yes')
    return FALSE;

  if (startswith($estr, "Undefined variable:"))
    return TRUE;
  if (startswith($estr, "Undefined property:"))
    return TRUE;
  if (startswith($estr, "Undefined index:"))
    return TRUE;

  return FALSE;
}
set_error_handler('custom_error_handler', E_NOTICE);




