<?
/**********************************************************************/
// Author:      Yosia Urip
// Description: This page display a form for the user to enter credit 
//              card information, submit the payment through PayPal 
//              Website Payment Pro. It accepts four major credit 
//              card: Visa, MC, Amex, Discover and validates any 
//              missing value and a malformed email address. The 
//              payment is made through DoDirectPayment API call to 
//              PayPal using phpPayPal library (curl, HTTP POST). 
//              The API will response with an ACK value whether it 
//              is successful or not. The page will display an error 
//              message if the payment has been denied, and redirects 
//              to logTransaction.php upon successful acceptance. 
// Last Edited: February, March 2010
/**********************************************************************/

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

if(!isset($_POST['gift'])){header("Location: ".get_bloginfo('url'));exit();}
if(!$_SERVER['HTTPS'] 
  && (strpos(get_bloginfo('url'),'seeyourimpact.org') !== FALSE)){ 
  header("Location: ".$bp->root_domain); exit();
}

function verifyTransaction(){}

include_once($_SERVER['DOCUMENT_ROOT'].'/payments/paypal/phpPayPal.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/payments/logTransaction.php');

//Get all POST variables

foreach($_POST as $k=>$v){ 
  if($k == 'amount_1' || $k == 'amount_2'){
    $$k=floatval($v); 
  }else if($k == 'quantity_1' || $k == 'quantity_2'){
    $$k=intval($v); 
  }else if($k == 'discount_amount_cart'){
    $$k=floatval($v); 
  }else if($k == 'code'){
    $$k=stripslashes(str_replace(array("-"," "),"",$v)); 
  }else{
    $$k=stripslashes($v); 
  }
}

//Store cart to paymentTable, append paymentID to thank-you URL
if(!isset($_POST['direct_payment'])){
  $paymentID = store_cart($_POST);
  $custom = $paymentID;
  
  if(strpos($return,'?cm=')!==FALSE){
	$return = substr($return,0,strpos($return,'?cm=')).'?cm='.urlencode($paymentID);
  }else{
	$return = $return.'?cm='.urlencode($paymentID);
  }
}

//echo '<pre>'; print_r($_POST); echo '</pre>';exit();

$errorMessage='';
$paypal=new phpPayPal();

if(isset($_POST['direct_payment'])){//If it is a submitted form

	$paypal->amount_shipping=0;
	$paypal->ip_address = $_SERVER['REMOTE_ADDR']; 

	//Order Totals (amount_total is required)
	$paypal->amount_total = $amount_1 * $quantity_1;

    //tip
	if($amount_2 > 0){
	  $paypal->amount_total += $amount_2;    
	}
		
	//discount
	if($discount_amount_cart > 0){
	  $paypal->amount_total -= $discount_amount_cart;
	}
	
	//Credit Card Information (required)
	$paypal->credit_card_number = $cc_num;
	$paypal->credit_card_type = $cc_type;
	$paypal->cvv2_code = $cc_cvv;
	$paypal->expire_date = $cc_month . $cc_year;

	//Billing Details (required)
	$paypal->first_name = $first_name;
	$paypal->last_name = $last_name;
	$paypal->address1 = $address1;
	$paypal->address2 = $address2;
	$paypal->city = $city;
	$paypal->state = ($state!=''?$state:$state_other);
	$paypal->postal_code = $zip;
	$paypal->phone_number = $phone;
	$paypal->email = $email;	
	$paypal->country_code = $country_code;
	$paypal->return_url = $notify_url;
	$paypal->cancel_url = $cancel_return;
		
	//validate value on server side
	if(floatval($paypal->amount_total) <= 0){$errorMessage .= 'Payment amount is wrong. ';}
	if($paypal->credit_card_number == ''){$errorMessage .= 'Missing credit card #. ';}
	if($paypal->credit_card_type == ''){$errorMessage .= 'Missing credit card type. ';}
	if($paypal->cvv2_code == 0){$errorMessage .= 'Missing email cvv.';}
	if($paypal->expire_date == ''){$errorMessage .= 'Missing expiration date. ';}
	if($paypal->email == ''){$errorMessage .= 'Missing email address. ';}
	
	if($errorMessage == ''){
		///echo'<pre>';print_r($paypal);echo'</pre>';exit();
		$paypal->do_direct_payment(); //Perform the payment		
		$paypalResponse=$paypal->Response; //Get the response
	}	
	
	///echo '<pre>';print_r($paypal->Response);echo '</pre>';exit();			
	
	if($paypalResponse['ACK'] == 'Success'
	  || $paypalResponse['ACK'] == 'SuccessWithWarning'){//If payment successful
		$paypal->transaction_id=$paypalResponse['TRANSACTIONID'];
		
		$paypal->get_transaction_details();	//Grab the transaction details
		if($paypalResponse['ACK'] == 'Success' 
		  || $paypalResponse['ACK'] == 'SuccessWithWarning'){	//If the details acquired

			$paypalResponse=$paypal->Response;
			//Combine all data, make it similar format as regular PayPal payment
			$paymentData = 
			array (
			'mc_gross'=>$paypalResponse['AMT'],
			'protection_eligibility'=>'',
			'address_status'=>'',
			'item_number1'=>'',
			'tax'=>$paypalResponse['TAXAMT'],
			'item_number2'=>'',
			'payer_id'=>$paypalResponse['PAYERID'],
			'address_street'=>$address1.' '.$address2,
			'payment_date'=>$paypalResponse['ORDERTIME'],
			'option_selection1_1'=>$os0_1,
			'payment_status'=>$paypalResponse['PAYMENTSTATUS'],
			'option_selection1_2'=>$os0_2,
			'charset'=>'windows-1252',
			'address_zip'=>$zip,
			'mc_shipping'=>'0.00',//
			'mc_handling'=>'0.00',//
			'first_name'=>$paypalResponse['FIRSTNAME'],
			'mc_fee'=>$paypalResponse['FEEAMT'],
			'address_country_code'=>$paypalResponse['COUNTRYCODE'],
			'address_name'=>'',
			'notify_version'=>$paypalResponse['VERSION'],
			'custom'=>$custom,
			'payer_status'=>$paypalResponse['PAYERSTATUS'],
			'business'=>$business,
			'address_country'=>$paypalResponse['COUNTRYCODE'],
			'num_cart_items'=>2,
			'mc_handling1'=>'0.00',
			'mc_handling2'=>'0.00',
			'address_city'=>$city,
			'verify_sign'=>'',
			'payer_email'=>$paypalResponse['EMAIL'],
			'mc_shipping1'=>'0.00',//
			'mc_shipping2'=>'0.00',//
			'tax1'=>'0.00',//
			'btn_id1'=>'0',//
			'tax2'=>'0.00',//
			'btn_id2'=>'0',//
			'option_name1_1'=>$on0_1,
			'option_name1_2'=>$on0_2,
			'memo'=>'',
			'txn_id'=>$paypalResponse['TRANSACTIONID'],
			'payment_type'=>$paypalResponse['PAYMENTTYPE'],
			'last_name'=>$paypalResponse['LASTNAME'],
			'address_state'=>$state,
			'item_name1'=>$item_name1,
			'receiver_email'=>$paypalResponse['RECEIVEREMAIL'],
			'item_name2'=>$item_name2,
			'payment_fee'=>$paypalResponse['FEEAMT'],
			'quantity1'=>$quantity_1,
			'quantity2'=>$quantity_2,
			'receiver_id'=>$paypalResponse['RECEIVERID'],
			'txn_type'=>$paypalResponse['TRANSACTIONTYPE'],
			'mc_gross_1'=>floatval($amount_1 * $quantity_1),
			'mc_currency'=>$paypalResponse['CURRENCYCODE'],
			'mc_gross_2'=>floatval($amount_2 * $quantity_2),
			'residence_country'=>$paypalResponse['COUNTRYCODE'],
			'transaction_subject'=>$custom,
			'payment_gross'=>$paypalResponse['AMT'],
			'return'=>$return
			);
			$paymentData = array_merge($paymentData,$paypalResponse);
			///echo '<pre>';print_r($paymentData);echo '</pre>';exit();	
			
			$variables=array();
			foreach($paymentData as $k=>$v){array_push($variables,$k.'='.urlencode($v));}
			//$variables=implode('&',$variables);
			
			$variables=var_export($variables,true);
			//echo $variables;exit();
			//header('Location: logTransaction.php?'.$variables);exit();

			logTransactions2($paypalResponse['PAYMENTSTATUS'],
			  $paypalResponse['EMAIL'], $custom, $paypalResponse['AMT'], $memo,
		      $first_name, $last_name, $quantity_1, $variables,$return, 'CC', false, $paypalResponse['TRANSACTIONID']);
			exit();
		}else{
			$errorMessage = 'Payment error. ';
 		    $errorMessage .= notifyPaymentFailure($_POST,$paypalResponse);
		}
	}else{
		if(!isset($paypalResponse['L_SHORTMESSAGE0'])
		  || $paypalResponse['L_SHORTMESSAGE0'] == '') {
		  $errorMessage ='Invalid Payment.';
		} else {
		  $errorMessage = 'Payment error.';
		}
		
		$errorMessage .= notifyPaymentFailure($_POST,$paypalResponse);		
	}

} 

//Test data
if(CREDIT_PAYMENT_TEST){
	$cc_num='4929492378736069';$cc_type='Visa';$cc_month='10';$cc_year='2011';$cc_cvv='808';
	$first_name='John';$last_name='Doe';$address1='12345 Place St';$address2='Apt 205';$city='Seattle';$state='WA';$zip='98115';
}

//Build US states dropdown
$state_options=buildDropDownOptions($paypal->states['US'],$state);

//Build Countries dropdown
$country_options=buildDropDownOptions($paypal->countries,$country_code);

//Build CC types dropdown
$cc_types=array('Visa'=>'Visa','MasterCard'=>'MasterCard','Amex'=>'Amex','Discover'=>'Discover');
$cc_type_options=buildDropDownOptions($cc_types,$cc_type);
$cc_months=array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',
	6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
$cc_month_options=buildDropDownOptions($cc_months,$cc_month);

$cc_years=array();
for($i=0;$i<10;$i++){$cc_years[date('Y',time())+$i]=date('Y',time())+$i;}
$cc_year_options=buildDropDownOptions($cc_years,$cc_year);

$subtotal = $amount_1 * $quantity_1;
$total = $subtotal + $amount_2;
$remaining = $total - $discount_amount_cart;

if($gift == 1){
  $displayName = GIFTCERT_ITEM_NAME;
  $description = GIFTCERT_ITEM_DESC;
} else {
  $displayName = $wpdb->get_var("SELECT displayName FROM gift WHERE id = '".$gift."'");
  $description = $wpdb->get_var("SELECT excerpt FROM gift WHERE id = '".$gift."'");
  if($displayName == NULL){exit();}
}

get_template_part('header', 'ssl');
global $post;

?>
<article class="type-page">
<script type="text/javascript">
//<!--
var goBack=false;

//Change the form ACTION attribute if user want to go back to previous page
function backToConfirm(){
  goBack=true;
  $('#paymentForm').attr({
    action:"/pay/?gift=<?=$gift?>"});
  return true;
}

//Regex validation of email address
function isValidEmailAddress(emailAddress){
  var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  return pattern.test(emailAddress.replace('+',''));
}

//Required field captions
var requiredFieldsTxt = ['first name','last name','credit card number','credit card type','CVV2 code',
'Month on expiration date','Year on expiration date','address line','city','country','zip','email'];
//Required field IDs
var requiredFields = ['first_name','last_name','cc_num','cc_type','cc_cvv',
'cc_month','cc_year','address1','city','country','zip','email'];
	
//Validate form
function validateCreditCardForm(){
  if(goBack){return true;}
  var invalid=false;
  $('#errorMessage').text("");

  $.each(requiredFields,function(index,value){//Walkthrough all fields
	if($('#'+value).val() == ''){//Notify if missing value on required fields
		$('#errorMessage').text($('#errorMessage').text()+"Missing "+requiredFieldsTxt[index]+". ");
		$('#'+value).focus();
		invalid = true;
	}
  });

  if($('#state').val() == '' && $('#state_other').val() == ''){
    $('#errorMessage').text($('#errorMessage').text()+"Missing state/province. ");
	$('#state').focus();
    invalid = true;
  }
  
  if($('#email').val() != $('#email_confirm').val() ){
    $('#errorMessage').text($('#errorMessage').text()+"Email confirmation does not match. ");
	$('#email_confirm').focus();
    invalid = true;
  }
  
  if(!isValidEmailAddress($('#email').val())){//Validate email format
	$('#errorMessage').text($('#errorMessage').text()+"Invalid email format. ");			
	$('#email').focus();
	invalid=true;
  }

  if(!invalid){//If the form valid, hide the form and display the waiting message/image
	$('form').attr({style:"display:none;"});
	$('#waiting').attr({style:"display:block;"});
  }

  return !invalid;
}

$(function(){
  //Disable ENTER key when on the text input to prevent accidental submission
  $('.input-text').keypress(function(e){
    return e.keyCode != 13;
  });	

  $("#cvv_link").hover(
    function() { $("#cvv_img").show(); },
    function() { $("#cvv_img").hide(); }
  );
});
//-->
</script>

<div id="waiting">
<h3><b>Your payment is being processed.</b></h3> 
You will be redirected in a moment.<br/>
Please do not refresh the page to avoid multiple payments. Thank you!<br/><br/>
<img src="/wp-content/images/ajax-loader.gif" alt="loading" />
</div>

<form method="post" action="?creditcard" id="paymentForm"  onsubmit="return validateCreditCardForm();">
<table id="sum_table">
    <tr>
        <td colspan="2"><b style="font-size:12pt;">
          Your gift of <?= stripslashes($displayName) ?></b>
          <br/><?= stripslashes($description) ?><br/></td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td>Quantity: </td><td style="text-align:right;"><?=$quantity_1 ?> x <?='$'.number_format($amount_1, 2) ?></td></tr>
    <tr><td>Subtotal: </td><td style="text-align:right;"><?='$'.number_format($subtotal,2) ?></td></tr>
    <? if($amount_2 > 0){ ?>
    <tr><td>Contribution to SeeYourImpact.org -- Thank You: </td><td style="text-align:right;"><?='$'.number_format($amount_2,2) ?></td></tr>
    <? } ?>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr style="font-size:12pt"><td><b>Your total <?=($gift==1?'':'donation ')?> is: </b></td><td style="text-align:right;"><b><?='$'.number_format($total,2)?></b></td></tr>
    <? if($discount_amount_cart > 0){ ?>
    <tr style="color:#282;">
    <td><b>Gift certificate applied.</b></td>
    <td style="text-align:right;">
    <b id="discount"><?=money_format('-$%.2n ',$discount_amount_cart);?></b>
    <input type="hidden" name="discount_amount_cart" value="<?= $discount_amount_cart ?>"/>  
    </td></tr>
    <tr class="applied-row">
    <td colspan="2" style="text-align:right;">Payment due:
    <span style="margin-left:40px;font-weight: bold;"><?=money_format('$%.2n ',$remaining);?></span>
    </td>
    </tr>
</table>
<table id="cc_table">
    <tr><td>&nbsp;</td></tr>
    <? } ?>
    <tr>
        <td colspan="2">
        	<div style="display:none;position:absolute;text-align:center;top:350px;padding:20px 40px;background:#fff;border:10px solid #ddd;"  id="cvv_img">
        	<img src="../wp-content/images/credit_card_cvv.gif" alt="CVV"  />
            </div>
            <table cellpadding="0" cellspacing="0" id="cc_form">
                <tr><td colspan="2"><div id="errorMessage" class="error"><?=$errorMessage?>&nbsp;<br/></div></td></tr>
                <tr><td width="200px">*Credit Card Number: </td>
                <td><input class="input-text" value="<?=$cc_num?>" type="text" maxlength="16" size="20" name="cc_num" id="cc_num" /></td></tr>
                <tr><td>*Credit Card Type: </td><td><select name="cc_type" id="cc_type"><?=$cc_type_options?></select></td></tr>
                <tr><td></td><td><img src="/wp-content/images/credit_card_logos.gif" alt="Credit Card Logos"/></td></tr>
                <tr><td>*Expiration Date:</td><td>
                <select name="cc_month" id="cc_month"><?=$cc_month_options?></select>&nbsp;
                <select name="cc_year" id="cc_year"><?=$cc_year_options?></select>
                *<a href="#" id="cvv_link">CVV</a>: <input class="input-text short" value="<?=$cc_cvv?>" type="text" maxlength="4" size="4" name="cc_cvv" id="cc_cvv" /></td></tr>
                <tr><td>*First Name: </td>
                <td><input class="input-text" value="<?=$first_name?>" type="text" maxlength="100" size="30" name="first_name" id="first_name" /></td></tr>
                <tr><td>*Last Name: </td>
                <td><input class="input-text" value="<?=$last_name?>" type="text" maxlength="100" size="30" name="last_name" id="last_name" /></td></tr>
                <tr><td>*Address Line 1: </td>
                <td><input class="input-text" value="<?=$address1?>" type="text" maxlength="100" size="30" name="address1" id="address1" /></td></tr>
                <tr><td> </td>
                <td><input class="input-text" value="<?=$address2?>" type="text" maxlength="100" size="30" name="address2" id="address2" /></td></tr>
                <tr><td>*City: </td>
                <td><input class="input-text" value="<?=$city?>" type="text" maxlength="100" size="30" name="city" id="city" /></td></tr>
                <tr><td>State (US Only): </td>
                <td><select name="state" id="state"><?=$state_options?></select> Other:
                <input class="input-text" type="text" maxlength="100" size="10" name="state_other" id="state_other" value="<?=$state_other?>" />
                </td></tr>
                <tr><td>*Country: </td>
                <td><select name="country_code" id="country"><?=$country_options?></select></td></tr>
                <tr><td>*ZIP Code: </td>
                <td><input class="input-text" value="<?=$zip?>" type="text" maxlength="100" size="30" name="zip" id="zip" /></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>*Email: </td>
                <td><input class="input-text" value="<?=$email?>" type="text" maxlength="100" size="30" name="email" id="email" /></td></tr>
                <tr><td>*Enter Email Again: </td>
                <td><input class="input-text" value="<?=$email?>" type="text" maxlength="100" size="30" name="email_confirm" id="email_confirm" /></td></tr>
                <tr><td>Home Phone: </td>
                <td><input class="input-text" value="<?=$phone?>" type="text" maxlength="100" size="30" name="phone" id="phone" /></td></tr>
                <tr><td colspan="2"><div style="font-style:italic;font-size:8pt;">*Required Field</div></td></tr>
            </table>                
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="submit" onclick="return backToConfirm();" id="back" name="submit" 
          value="Go Back to Previous Screen" style="font: 12pt Arial,Verdana;padding:2px;"/>
        &nbsp;
        <input type="submit" id="submit" name="direct_payment" 
          value="Submit Payment" style="font: bold 12pt Arial,Verdana;padding:2px;"/>
        </td>
    </tr>
</table>
<input type="hidden" name="item_name_1" id="item_name_1" value="<?=$item_name_1?>"/>
<input type="hidden" name="item_name_2" id="item_name_2" value="<?=$item_name_2?>"/>
<input type="hidden" name="upload" value="<?=$upload?>"/>
<input type="hidden" name="hosted_button_id" value="<?=$hosted_button_id?>"/>
<input type="hidden" name="cmd" value="<?=$cmd?>"/>
<input type="hidden" id ="custom" name="custom" value="<?=$custom?>"/>
<input type="hidden" id ="amount_1" name="amount_1" value="<?=$amount_1?>"/>
<input type="hidden" id ="amount_2" name="amount_2" value="<?=$amount_2?>"/>
<input type="hidden" id ="quantity_1" name="quantity_1" value="<?=$quantity_1?>"/>
<input type="hidden" id ="quantity_2" name="quantity_2" value="<?=$quantity_2?>"/>
<input type="hidden" name="business" value="<?=BUSINESS_ID?>"/>
<input type="hidden" name="return" value="<?=$return?>"/>
<input type="hidden" name="cancel_return" value="<?=$cancel_return_url?>"/>
<input type="hidden" name="notify_url" value="<?=$notify_url?>"/>
<input type="hidden" name="no_note" value="<?=$no_note?>"/>
<input type="hidden" name="currency_code" value="<?=$currency_code?>"/>
<input type="hidden" name="tax" value="<?=$tax?>"/>
<input type="hidden" name="lc" value="<?=$lc?>"/>
<input type="hidden" name="bn" value="<?=$bn?>"/>
<input type="hidden" name="address_override" value="<?=$address_override?>"/>
<input type="hidden" name="cn" value="<?=$cn?>"/>
<input type="hidden" name="on0_1" value="<?=$on0_1?>"/>
<input type="hidden" name="os0_1" value="<?=$os0_1?>"/>
<input type="hidden" name="on0_2" value="<?=$on0_2?>"/>
<input type="hidden" name="os0_2" value="<?=$os0_2?>"/>
<input type="hidden" name="gift" value="<?=$gift?>"/>
<input type="hidden" name="next" value="<?=FORM_ACTION?>"/>
<input type="hidden" name="blog_url" value="<?=$blog_url?>" />
</form>
</article>
<?
get_template_part('footer', 'ssl');
