<?php 
include_once('../wp-load.php');
include_once('../wp-includes/wp-db.php');
include_once('../syi.php');
?>
<style>
<?php
include_once('../wp-admin/css/colors-fresh.css');
include_once('../wp-admin/css/global.css');
?>
</style>
<div class="wrap">
<?php
	if( empty($_GET['popup']) ){
		echo '<strong>Invalid URl ...</strong>';
	}else{
		switch($_GET['popup']){
			case 'donordetail':
				print_donor();
				break;
			case 'giftdetail':
				print_gift();
				break;
			case 'activitydetail':
				print_history();
				break;
			case 'emaildetail':
				print_email_detail();
				break;				
			default:
				echo '<strong>Invalid URL ...</strong>';
				break;
		}
	}
?>
<br/>
</div>
<?php
	function print_history(){
		if( $activity=get_donationHistoryById($_GET['historyid']) ){?>
			<h2>Activity Details</h2>
			<table border="0" cellspacing="2" cellpadding="5">
				<tr>
					<th align=left>Activity Time</th>
					<td><?php echo date('g:i:s A ',$activity['transactionDate']); ?></td>
				</tr>
				<tr>
					<th align=left>Updated By</th>
					<td><?php 
								if( $user=get_userdata(intval($activity['modifiedBy'])) ):
									echo "$user->display_name( Login: $user->user_login )";
						?>
						<?php
								endif;
						?></td>
				</tr>
			</table>
			<br/>
			&nbsp;&nbsp;<strong><a href="javascript:window.close();">Close</a></strong>
		<?php
		}
	}
	
	function print_gift(){
		
		if( $gift=Syi::get_gift($_GET['giftid']) ){?>
			<h2>Gift Details</h2>
			<table border="0" cellspacing="2" cellpadding="5">
				<tr>
					<th align=left">Name</th>
					<td><?php echo $gift['displayName']; ?></td>
				</tr>
				<tr>
					<th align=left>Description</th>
					<td><?php echo $gift['description']; ?></td>
				</tr>
				<tr>
					<th align=left>Amount</th>
					<td>$<?php echo $gift['unitAmount']; ?></td>
				</tr>
				<tr>
					<th align=left>Tags</th>
					<td><?php echo $gift['tags']; ?></td>
				</tr>
				<tr>
					<th align=left>Wanted</th>
					<td><?php echo $gift['unitsWanted']; ?></td>
				</tr>
				<tr>
					<th align=left>Donated</th>
					<td><?php echo $gift['unitsDonated']; ?></td>
				</tr>
				<tr>
					<th align=left>Active</th>
					<td><?php echo intval($gift['active']) ? 'True' : 'False' ?></td>
				</tr>
			</table>
			<br/>
			&nbsp;&nbsp;<strong><a href="javascript:window.close();">Close</a></strong>
		<?php
		}
	}
	function print_donor(){
		if($donor=Syi::get_donor($_GET['donorid'])){
		?>
			<h2>Donor Details</h2>
			<table border="0" cellspacing="2" cellpadding="5">
				<tr>
					<th align=left>First Name</th>
					<td><?php echo $donor['firstName']; ?></td>
				</tr>
				<tr>
					<th align=left>Last Name</th>
					<td><?php echo $donor['lastName']; ?></td>
				</tr>
				<tr>
					<th align=left>Email ID</th>
					<td><?php echo $donor['email']; ?></td>
				</tr>

			</table>
			<br/>
			&nbsp;&nbsp;<strong><a href="javascript:window.close();">Close</a></strong>
		<?php
		}
	}
	
	function print_email_detail(){
		if($notification=get_notificationById( intval($_GET['notificationId'])) ){
		?>
		<style>
		.mail-header {
		  background: #f0f0f0;
		  border-bottom: 1px solid #c0c0c0;
		}
		.mail-header div { 
		  padding: 5px; 
		  text-align: left;
		}
		.mail-header .attr {
		  float: left;
		  clear: both;
		  width: 65px;
		  font-weight: bold;
		}
		.mail-header .val {
		  margin-left: 75px;
		}
		a.close { 
		  padding: 10px; 
		  display: block;
		  font-weight: bold;
		}
		</style>
		<div class="mail-header">
		  <div class="attr">to:</div>
		  <div class="val"><?= as_email($notification['emailTo']); ?></div>
		  <div class="attr">on:</div>
		  <div class="val"><?= date('D, M n Y - h:i A ',strtotime($notification['sentDate'])); ?></div>
		  <div class="attr">subject:</div>
		  <div class="val"><?= $notification['emailSubject'] ?></div>
		</div>
		<div class="body">
		  <?= xml_entities($notification['emailText']); ?>
		</div>
                <a class="close" href="javascript:window.close();">Close</a>
		<?php
		}
	}

	function get_donationHistoryById($historyId){
		if( $historyId==null )
			return null;
		$queryResult=mysql_query(sprintf("SELECT * FROM donationHistory WHERE donationHistoryID = %d LIMIT 1",intval($historyId) ));
		return ( !mysql_error() && mysql_num_rows($queryResult) > 0 ) ? mysql_fetch_assoc($queryResult) :  null  ;
	}
	
	function get_notificationById($notificationId){
		if( $notificationId==null )
			return null;
		$queryResult=mysql_query(sprintf("SELECT * FROM notificationHistory WHERE notificationID = %d LIMIT 1",intval($notificationId) ));
		return ( !mysql_error() && mysql_num_rows($queryResult) > 0 ) ? mysql_fetch_assoc($queryResult) :  null  ;
	}
	
	function get_notificationHistory($donationId){
		if( $donationId==null )
			return null;
		$queryResult=mysql_query(sprintf("SELECT * FROM notificationHistory WHERE donationID =  %d order by sentDate",intval($donationId) ));
		if(mysql_error())
			return null;
		$notifications=array();
		if(mysql_num_rows($queryResult)>0){
			while($history=mysql_fetch_assoc($queryResult)){
				$notifications[]=$history;
			}
		}
		return $notifications;	
	}
?>
