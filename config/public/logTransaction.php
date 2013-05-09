<?php
require_once( dirname(__FILE__) . '/wp-load.php' );
//print_r($_REQUEST);
$msgstr="";
foreach($_REQUEST as $k => $v){
$msgstr.= $k." -->".$v."<br>";
}
function logTransactions(){
	if(!($_REQUEST['payment_status']=='Completed'))
		return;
	$result=mysql_query("SELECT * FROM donor where email='".$_REQUEST['payer_email']."'");
	$donor=mysql_fetch_assoc($result);
	$donorId=null;
	if(mysql_num_rows($result)>0){
		$donorId=$donor['ID'];
	}else{
		mysql_query("INSERT INTO donor(email,sendUpdates,firstName,lastName) values('".$_REQUEST['payer_email']."','1','".$_REQUEST['first_name']."','".$_REQUEST['last_name']."')") || die('Error inserting donor details.'.mysql_error());
		$donorId=mysql_insert_id();
	}
	$sql="INSERT INTO gift_Donations (giftID,donorID,statusID,transactionDate,instructions) VALUES('".$_REQUEST['item_number']."','".$donorId."',1,NOW(),'".mysql_real_escape_string($_REQUEST['memo'])."')";		
	$resid=mysql_query($sql) or die(mysql_error());
	
	mysql_query("update gift set unitsDonated=unitsDonated+1 where id='".$_REQUEST['item_number']."'");
	
	$giftResult=mysql_query("select * from gift where id='".$_REQUEST['item_number']."'");
	$gift=mysql_fetch_assoc($giftResult);
	$blogAdmin=mysql_query("select * from wp_registration_log where blog_id='".$gift['blog_id']."'");
	$adminEmailResults=mysql_fetch_assoc($blogAdmin);
	$adminMailSubject="Gift Donated - ".date("m-D-Y H:i:s");
	$adminMailContent=sprintf("Hello Admin,<br/>A gift %s is donated.<br/><br/>Find the details below:<br/><b>Gift ID:</b>%s<br/><b>Donor's Name:</b>%s<br/><b>Donor's Email:</b>%s<br/><b>Transaction Time:</b>%s<br/><br/>",
							$gift['displayName'],$gift['id'],$_REQUEST['first_name'],$_REQUEST['payer_email'],time());
	$adminMailContent.='Regards,<br/>seeyourimpact.org';
	
	@mail($adminEmailResults['email'], $adminMailSubject,$adminMailContent,'Content-Type:text/html');
}
logTransactions();
?>
