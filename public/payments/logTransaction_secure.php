<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

function verifyTransaction(){
  //Human readable request parameters for logging and mailing.
  $msgstr='';
  foreach($_REQUEST as $k=>$v){
	  $msgstr.= $k." -->".$v."\r\n";
  }
  $adminEmail='admins@seeyourimpact.org';
  // Set the IPN Validation command to paypal
  $queryString= 'cmd=_notify-validate';
  // Constructing the query string from the requeste parameters
  foreach($_POST as $key => $value){
      // If magic quotes is enabled strip slashes
      if(get_magic_quotes_gpc())
     {$value=stripslashes($value);}
      $value=urlencode($value);
      $queryString.= "&$key=$value";
  }

  //$url="http://www.paypal.com/cgi-bin/webscr";
  //For sandbox testing. This should be changed to the above one
  //$url="http://www.sandbox.paypal.com/cgi-bin/webscr";

  //$url=FORM_ACTION;
  //$ssl_url=VERIFY_URL;

  if(isset($_REQUEST['test_ipn']) && $_REQUEST['test_ipn']==1){
    $pp_details = sw_get_provider_details('TEST');
  } else {
    $pp_details = sw_get_provider_details('LIVE');
  }
  $url=$pp_details->form_action;
  $ssl_url=$pp_details->verify_url;

  $url_parsed=parse_url($url);
  // open the connection to paypal using the mode specific ssl url.
  $fp=fsockopen($ssl_url,"443",$err_num,$err_str,60);	
  	
  $error=null;  


  if(!$fp){
	  // could not open the connection.  
	  $error= "fsockopen error no. $errnum: $errstr";

    debug(
    $error.
    '///////////////////////////REQUEST'.'<br/><br/>'.
    print_r($_REQUEST,true).'<br/><br/>'.
    'ssl_url: '.$ssl_url.''
    ,true);
    //@mail($adminEmail, "Error opening socket".date("m-d-Y H:i:s"), "$error\r\n Here are the postback variables:".$msgstr);
	  return;      
  }else{
	  // Post the data back to paypal
    fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
	  fputs($fp, "Host: $url_parsed[host]\r\n"); 
	  fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
	  fputs($fp, "Content-length: ".strlen($queryString)."\r\n"); 
	  fputs($fp, "Connection: close\r\n\r\n"); 
	  fputs($fp, $queryString . "\r\n\r\n"); 

    $postback .= "POST $url_parsed[path] HTTP/1.1\r\n"
    . "Host: $url_parsed[host]\r\n"
    . "Content-type: application/x-www-form-urlencoded\r\n"
    . "Content-length: ".strlen($queryString)."\r\n"
    . "Connection: close\r\n\r\n"
    . $queryString . "\r\n\r\n";
	  // loop through the response from the server and append to variable
	  while(!feof($fp)){ 
   		  $ipn_response .= fgets($fp, 1024); 
     }
     fclose($fp); // close connection  
  }

  debug(
  '///////////////////////////REQUEST'.'<br/><br/>'.
  print_r($_REQUEST,true).'<br/><br/>'.
  '///////////////////////////POSTBACK'.'<br/><br/>'.
  $postback.'<br/>'.
  VERIFY_URL.'<br/><br/>'.
  '///////////////////////////RESPONSE'.'<br/><br/>'.
  $ipn_response,true);

  if (strpos($ipn_response,'VERIFIED') !== false){
	  logTransactions();
	  //@mail($adminEmail, "Successful payment logged - ".date("m-d-Y H:i:s"), "Here are the postback variables: \r\n ".$msgstr);	
  }else{
	  //@mail($adminEmail, "Unsuccessful payment detected".date("m-d-Y H:i:s"), "Response:$ipn_response \r\nHere are the postback variables:\r\n".$msgstr);
  }
}

include_once('logTransaction.php');
verifyTransaction();
?>