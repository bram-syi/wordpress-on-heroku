<?php
/*
 * Created on Nov 6, 2008
 *
 * This page displays the Donation History and the Notification history details.
 */
 
include_once(ABSPATH.'/syi.php');
?>
<SCRIPT TYPE="text/javascript">
	<!--
	function popup(mylink, windowname)
	{
	if (! window.focus)return true;
	var href;
	if (typeof(mylink) == 'string')
	   href=mylink;
	else
	   href=mylink.href;
	window.open(href, windowname, 'scrollbars=1,width=600,height=250');
	return false;
	}
	//-->
</SCRIPT>

<div class="wrap">
<h2>
<a href="admin.php?page=donations"><?php _e('Received Donations') ?></a>
<?php _e(' > ' ) ?><?php _e('Donation History' ) ?>
</h2>
<br/>
<?php
$donationId=$_GET['donationId'];
$donation=Syi::get_donation($donationId);
if($donation==null){?>
		<span>Invalid Donation</span>
<?php }else{
?>
<font size="3"><strong>History for:</strong></font>
<table cellspacing="5" cellpadding="2" style="margin-left:20px;">
	<tr>
		<th align="left" >Donation Id</th>
		<td><?php echo $donationId; ?></td>
	</tr>
	<tr>
		<th align="left" >Donor Details</th>
		<td>
		<?php 
			$donors=get_donorsOfDonation($donationId);
			if($donors!=null):
				foreach($donors as $donorID):
					$donor=Syi::get_donor($donorID);
					echo $donor['firstName'].' '.$donor['lastName'];?>(<?php echo $donor['email']; ?>)
					<a href="/popups/detailsPopUp.php?popup=donordetail&donorid=<?php echo $donor['ID']; ?>" onClick="return popup(this, 'Donor Details')">more...</a>
				<?endforeach; 
			endif;
		?>
		</td>
	</tr>
	<tr>
		<th align="left">Gift Name</th>
		<td>
		<?php
		$gifts=get_giftsOfDonation($donationId); 
			if($gifts!=null):
				foreach($gifts as $giftID):
					$gift=get_gift($giftID);
					echo $gift['displayName'];?>&nbsp;
					<a href="/popups/detailsPopUp.php?popup=giftdetail&giftid=<?php echo $gift['id']; ?>" onClick="return popup(this, 'Gift Details')">more...</a>
		<?php 	
				endforeach; 
			endif;
		?>
		</td>
	</tr>
	<tr>
		<th align="left">Amount</th>
		<td>$<?php echo $gift['unitAmount'];?></td>
	</tr>
</table>
<hr/>
<br/>

<?php 
$histories=get_all_donation_history($donationId);
if( $histories == null || count($histories)==0 ){ ?>
	<span>Empty List</span>
<?php
}else{ ?>

<table border="0" width="100%" cellspacing="2" cellpadding="4">
<?php
$flag=true;
$firstNotification=true;
foreach($histories  as $history):
?>
	<tr <?php if($flag): ?>class="alternate" <?php endif; ?> 
		<?php if($history['type'] == 'notification'):
			$title=get_email_detail($history['id']);
			echo "title='$title'";
		endif;?> 
		<td width="15%">
			<strong>
				<?php if($history['type'] == 'donation'):?>
				<a href="/popups/detailsPopUp.php?popup=activitydetail&historyid=<?php echo $history['id'];?>" onClick="return popup(this, 'Activity Details')">
					<?php echo date('dS F ',strtotime($history['time'])); ?>
				</a>
				<?php else: ?>
				<a href="/popups/detailsPopUp.php?popup=emaildetail&notificationId=<?php echo $history['id'];?>" onClick="return popup(this, 'Activity Details')">
					<?php  echo date('dS F ',strtotime($history['time'])); ?>
				</a>
				 <? endif; ?>
			</strong>			
		</td>
		<td width="85%">
		<?php 
			if($history['type'] == 'donation'): 
				echo $history['action'];
			else:
				if($firstNotification):
					echo 'Donation made on website';
					$firstNotification=false;
				else:
					echo 'Email Notification sent';?>
					<a href="/popups/detailsPopUp.php?popup=emaildetail&notificationId=<?php echo $history['id']; ?>" onClick="return popup(this, 'Email Notifications')">
						detail...
					<a>
		<?php	endif;
			endif;	
		?>
		</td>
	</tr>
<?php
if($flag)
	$flag=false;
else
	$flag=true;
endforeach;

?>
</table>

<?php
	}
}
	function getDonationHistory($donationId){
		$resultSet=mysql_query("select * from donationHistory where donationId = ".intval($donationId));
		if(mysql_num_rows($resultSet)>0){
			$records=array();
			while($record=mysql_fetch_assoc($resultSet))
				$records[]=$record;
			return $records;
		}
		return null;
	}

	function get_gift( $giftId=null ){
		if( $giftId==null )
			return null;
		$queryResult=mysql_query(sprintf("SELECT * FROM gift WHERE id = %d LIMIT 1",intval($giftId) ));
		return ( !mysql_error() && mysql_num_rows($queryResult) > 0 ) ? mysql_fetch_assoc($queryResult) :  null  ;
	}
	
	function get_donorsOfDonation($donationID){
		if( $donationID==null )
			return null;
		$queryResult=mysql_query(sprintf("SELECT donorID FROM donation WHERE donationID =  %d",intval($donationID) ));
		if(mysql_error())
			return null;
		$donorsID=array();
		if(mysql_num_rows($queryResult)>0){
			while($donorID=mysql_fetch_assoc($queryResult)){
				$donorsID[]=$donorID['donorID'];
			}
		}
		return $donorsID;	
	}
	
	function get_giftsOfDonation($donationID){
		if( $donationID==null )
			return null;
		$queryResult=mysql_query(sprintf("SELECT giftID FROM donationGifts WHERE donationID =  %d",intval($donationID) ));
		if(mysql_error())
			return null;
		$giftsID=array();
		if(mysql_num_rows($queryResult)>0){
			while($giftID=mysql_fetch_assoc($queryResult)){
				$giftsID[]=$giftID['giftID'];
			}
		}
		return $giftsID;	
	}
	
	function get_all_donation_history($donationId=null){
		if($donationId==null)
			return null;
		$donationHistories=mysql_query("select donationHistoryId,transactionDate,modifiedBy,action from donationHistory where donationId = ".intval($donationId));
		$histories=array();
		
		if(mysql_num_rows($donationHistories)>0){
			while($history=mysql_fetch_assoc($donationHistories)){
				$histories[]=array(
								'type'=>'donation',
								'id'=>$history['donationHistoryId'],
								'time'=>$history['transactionDate'],
								'modifiedBy'=>$history['modifiedBy'],
								'action'=>$history['action'],
								);
			}
		}
		
		$notificationHistories=mysql_query("select notificationId,donorID,sentDate,emailTo,emailSubject,emailText from notificationHistory where donationID = ".intval($donationId));
		if( mysql_num_rows($notificationHistories)>0 ){
			while($history=mysql_fetch_assoc($notificationHistories)){
				$histories[]=array(
								'type'=>'notification',
								'id'=>$history['notificationId'],
								'time'=>$history['sentDate'],
								'emailTo'=>$history['emailTo'],
								'emailSubject'=>$history['emailSubject'],
								'emailText'=>$history['emailText']
								);
							
			}
		}
			//Sort the histories on the time column
		$time=array();
		for($i=0;$i<count($histories);$i++){
			$history=$histories[$i];
			$time[$i]=$history['time'];
		}
		array_multisort($time,SORT_STRING,$histories);
		return $histories;
	}
	
	function get_email_detail($notificationId){
		if($notification=get_notificationById( intval($notificationId) ) ){
			ob_start();?>
			Time:<?php echo strip_tags(date('dS F y , h:i A ',strtotime($notification['sentDate']))); ?>
			Email Subject:<?php echo strip_tags($notification['emailSubject']);?>
			Email Text:<?php echo strip_tags($notification['emailText'])?>
		<?php
			$output=ob_get_contents();
			ob_end_clean();
			return $output;
		}else{
			return "";
		}
	}
	function get_notificationById($notificationId){
		if( $notificationId==null )
			return null;
		$queryResult=mysql_query(sprintf("SELECT * FROM notificationHistory WHERE notificationID = %d LIMIT 1",intval($notificationId) ));
		return ( !mysql_error() && mysql_num_rows($queryResult) > 0 ) ? mysql_fetch_assoc($queryResult) :  null  ;
	}
?>
</div>
