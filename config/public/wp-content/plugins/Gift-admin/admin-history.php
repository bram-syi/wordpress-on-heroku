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
    if (!window.focus) return true;
    var href;
    if (typeof(mylink) == 'string')
       href=mylink;
    else
       href=mylink.href;
    window.open(href, windowname, 'scrollbars=1,width=600,height=250');
    return false;
    }
    //-->

jQuery(function($) {
  $("a.donor").click(function() {
    popup(this, 'Donor details');
    return false;
  });
});
</SCRIPT>

<div class="wrap">
<h2>
<a href="admin.php?page=donations&pageName=details"><?php _e('Received Donations') ?></a> &gt; <?php _e('Donation History' ) ?>
</h2>

<style>
.date {
  width: 120px;
  float: left;
  clear: both;
  padding: 4px;
  font-weight: bold;
}
.content {
  margin-left: 130px;
  padding: 4px;
}
</style>
<?php
$donationId=$_GET['donationId'];
$donation = Syi::get_donation($donationId);
$prev = '';
if($donation==null){
  ?><span>Invalid Donation</span><?
} else {
    ?><h3>#<?= $donationId ?>: 
    <?
    echo as_money($donation['donationAmount_Total']) . " donation";
    $tip = $donation['tip'];
    if ($tip > 0)
        echo " + " . as_money($tip) . " tip";
    ?> from <?
      $donors = array();
      foreach (get_donorsOfDonation($donationId) as $id) {
        $donor = Syi::get_donor($id);
        $donors[] = $donor['firstName'] . ' ' . $donor['lastName'];
      }
      echo implode(', ', $donors);
    ?></h3>
    
    <? 
    $histories = get_all_donation_history($donationId);
    $o = new stdClass();
    $o->time = $donation['donationDate'];
    $o->type = 'donation';
    $o->modifiedBy = 0;
    $o->id = $donationId;
    $o->action = 'Donation was received';
    $histories[] = $o;
    if ($histories == null || count($histories)==0 ) {
        ?><span>No history</span><?
    } else { 
        foreach ($histories as $history) {
           $id = $history->id;
           ?><div class="date"><? $d = date('D, M j Y', strtotime($history->time)); if ($d != $prev) echo $d; $prev = $d; ?></div><div class="content"><?
	   if ($history->type == 'notification') {
	       $to = as_email($history->emailTo);
	       switch ($history->success) {
	         case 0: $w = "<b>Failed</b> to send"; break;
		 case 1: $w = "Sent"; break;
		 case 2: $w = "Updated (no mail)"; break;
		 default: $w = "???";
	       }
               ?><div><?= $w ?> <a class="subject" target="popup" href='/popups/detailsPopUp.php?popup=emaildetail&notificationId=<?=$id?>'><?= as_html(trim($history->emailSubject)) ?></a> to <?= as_email($to) ?>
	       </div>
	       <? 
	       if (false && ($history->postID > 0)) { 
	          global $wpdb;

		  $blog = 1; // TODO: Disabled until we have a fast function for getting the blog from this history item (because right now it is not easy)
	          $wp = "wp_" . $blog . "_posts";
		  $post = $wpdb->get_results("SELECT ID, post_title FROM $wp WHERE ID=$history->postID");
		  $url = get_blog_permalink($blog, $post->ID);
		  echo "<a style='display:block; margin-left:40px;' href='$url'>" . as_html($post->title) . "</a>";
	       } 
	   } else if ($history->type == 'donation') {
	       echo $history->action;
	   }
	?></div><?
        }
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
    
    function get_all_donation_history($donationId=null) {
        global $wpdb;

        if (empty($donationId))
	    return array();

        $sql = $wpdb->prepare("select dh.transactionDate as time,'donation' as type,dh.donationHistoryID as id,dh.modifiedBy,dh.action from donationHistory dh where dh.donationID = %d order by time desc", $donationId);
        $donations = $wpdb->get_results($sql);

	$sql = $wpdb->prepare("select nh.sentDate as time,'notification' as type,nh.notificationId as id,nh.emailTo,nh.emailSubject,nh.emailText,nh.success,nh.postID from notificationHistory nh where nh.donationID = %d order by time desc", $donationId);
        $notifications = $wpdb->get_results($sql);

        // merge the arrays, preserving sort order
        $histories = array();
	while (count($donations) > 0 || count($notifications) > 0) {
	    if (count($donations) == 0 || $donations[0]->time < $notifications[0]->time)
                $histories[] = array_shift($notifications);
	    else
                $histories[] = array_shift($donations);
	}

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
