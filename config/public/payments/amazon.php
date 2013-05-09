<?
/*********************************************************************************************************/
// Author:      Amazon, Yosia Urip
// Description: Process checkout using Amazon API and wrapper library. The process involve signature
//              creation and automatic data POST back to Amazon
//
// Last Edited: February 2010
/*********************************************************************************************************/

include_once('../wp-load.php');
include_once('../wp-includes/wp-db.php');
include_once('../wp-admin/includes/user.php');
include_once('../wp-includes/syi/syi-includes.php');

include_once('amazon/ButtonGenerationWithSignature/ButtonGenerator.php');

$return_url="http://".$_SERVER['HTTP_HOST']."/".RETURN_URL;
$cancel_return_url="http://".$_SERVER['HTTP_HOST']."/".CANCEL_RETURN_URL;
$notify_url="http://".$_SERVER['HTTP_HOST']."/".NOTIFY_URL;

foreach($_REQUEST as $k=>$v){$$k=stripslashes($v);} //Get all POST variables
if(!isset($quantity_2)&&$item_name_2!=''){$quantity_2=1;}//Set quantity for tips

//get_header();

//
$gift_id =intval($_REQUEST['gift']);
$list=new GiftList();
$gift=$list->getGift($gift_id);
$amount=$gift['txtUnitAmt'];
$desc=$gift['txtDesc'];
$item_name=$gift['txtDispNm'];

$subtotal=$amount*$quantity_1;
$total=$subtotal+$amount_2;

//print_r($_REQUEST);exit();
/*
$signatureMethod='HmacSHA256';
$params=ButtonGenerator::getDonationParams(AMAZON_ACCESS_ID, $amount_1 + $amount_2, $item_name_1.' and '.$item_name_2, $custom, '1', $return, $cancel_return, '1', $notify_return, '0', $signatureMethod, 'fixedAmount');

$serviceEndPoint = parse_url(AMAZON_API_URL);
$signature = SignatureUtils::signParameters($params, AMAZON_ACCESS_KEY, 'POST', $serviceEndPoint['host'], $serviceEndPoint['path'], $signatureMethod);
$params['signature'] = $signature;

$params=array('action'=>AMAZON_API_URL,'immediateReturn'=>'1','collectShippingAddress'=>'0','signature'=>'','isDonationWidget'=>'1','signatureVersion'=>'2','signatureMethod'=>'HmacSHA256','amazonPaymentsAccountId'=>AMAZON_ACCESS_ID,'cobrandingStyle'=>'logo','processImmediate'=>'1','returnUrl'=>$return,'abandonUrl'=>$cancel_return,'ipnUrl'=>$notify_return,'amount'=>$amount_1,'referenceId'=>$custom,'description'=>item_name_1);
*/
//getDonationForm($params,$endPoint,$imageLocation,$donationType)

/*
?>
<div class="top-holder">
	<div class="top-content">
		<div class="page-holder">
            <br/>
            <table style="width:850px" id="form-container">
				<tr class="focus"><td colspan="3">&nbsp;</td></tr>
                <tr class="focus" valign="top">
                    <td rowspan="5" style="width:250px; text-align:center; vertical-align:top;">
                    <?php 
						$site=explode('//', get_bloginfo('url'));
						$site=explode('.', $site[1]);
						echo '<img src="wp-content/charity-images/charity-' . $site[0] . '.jpg" />';
                    ?>
                    </td>
                    <td><strong style="font-size:12pt;">Your gift of <?=$item_name ?></strong><br/><?=$desc ?><br/></td>
                    <td width="130"></td>
                </tr>
                <tr class="focus" valign="top"><td>Quantity: </td><td><?=$quantity_1 ?> x <?='$'.number_format($amount_1, 2) ?></td></tr>
                <tr class="focus" valign="top"><td>Subtotal: </td><td><?='$'.number_format($subtotal,2) ?></td></tr>
                <tr class="focus" valign="top"><td>Contribution to SeeYourImpact.org -- Thank You: </td><td><?='$'.number_format($amount_2,2) ?></td></tr>
                <tr class="focus" valign="top" style="font-size:12pt"><td><strong>Your total donation is: </strong></td><td><strong><?='$'.number_format($total,2)?></strong></td></tr>
				<tr class="focus"><td colspan="3">&nbsp;</td></tr>
                <tr style="background:#fff;"><td colspan="3">&nbsp;</td></tr>
                <tr style="background:#fff;"><td>
                
                </td>
                <td>To proceed with your donation, click the DONATE button</td>
                <td>
        <?
		*/
			ButtonGenerator::GenerateForm(
			AMAZON_ACCESS_ID
			,AMAZON_ACCESS_KEY
			,$total 
			,$item_name_1.' and '.$item_name_2
			,$custom
			,'1'
			,$return
			,$cancel_return
			,'1'
			,$notify_return
			,'0'
			,'HmacSHA256'
			,'fixedAmount'
			,(AMAZON_PAYMENT_TEST?'sandbox':'prod'));
/*		
		?> 
                
                </td></tr>
            </table><br/>
        </div>
    </div>
</div>
</body>
</html>
<? */ ?>