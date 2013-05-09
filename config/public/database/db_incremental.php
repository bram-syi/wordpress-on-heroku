<?php

// DEPRECATED - AS ALL SITES HAVE RUN THESE
// LEFT AS EXAMPLES FOR HOW TO WRITE INCREMENTALS


/*









require_once( dirname(__FILE__) . '/../wp-load.php' );
require_once('db-functions.php');

// INCREMENTAL: special instructions for donations
// This also shows an example of filling default values when the column is added
if (db_add_column("gift_Donations.instructions", "varchar(255) DEFAULT NULL"))
  $wpdb->query("UPDATE gift_Donations SET instructions='None'");  


// INCREMENTAL: create PayPal table & default submission URLs
if (db_add_table("paypal_settings", array(
  'id' => "tinyint(4) NOT NULL auto_increment /*PRIMARYKEY*/",
  'current_mode' => "enum('LIVE','TEST') NOT NULL",
  'type' => "enum('LIVE','TEST') NOT NULL",
  'business_id' => "varchar(200) NOT NULL",
  'form_action' => "varchar(200) NOT NULL",
  'return_url' => "varchar(200) NOT NULL",
  'cancel_return_url' => "varchar(200) NOT NULL",
  'notify_url' => "varchar(200) NOT NULL",
  'btn_image' => "varchar(200) NOT NULL",
  'pixel_image' => "varchar(200) NOT NULL")))
{
  db_insert_rows("paypal_settings", array(array(
    'id' => 1,
    'current_mode' => "LIVE",
    'type' => "TEST",
    'business_id' => "Partne_1221573342_biz@seeyourimpact.org",
    'form_action' => "https://www.sandbox.paypal.com/cgi-bin/webscr",
    'return_url' => "testpay/returnurl.php",
    'cancel_return_url' => "testpay/cancel.php",
    'notify_url' => "testpay/logTransaction.php",
    'btn_image' => "https://www.sandbox.paypal.com/en_US/i/btn/btn_donate_SM.gif",
    'pixel_image' => "https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif"
  ),array(
    'id' => 2,
    'current_mode' => "LIVE",
    'type' => "LIVE",
    'business_id' => "digvijay@seeyourimpact.org",
    'form_action' => "https://www.paypal.com/cgi-bin/webscr",
    'return_url' => "testpay/returnurl.php",
    'cancel_return_url' => "testpay/cancel.php",
    'notify_url' => "testpay/logTransaction.php",
    'btn_image' => "https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif",
    'pixel_image' => "https://www.paypal.com/en_US/i/scr/pixel.gif"
  )));
}

//creationg of table to store the donation status and tracking them 
// INCREMENTAL: impact_feedback_status table
if (db_add_table("impact_feedback_status", array(
  'id' => " int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'feedback_status' => "varchar(255) NOT NULL")))
{
  db_insert_rows("impact_feedback_status", array(array(
    'id' => 1,
    'feedback_status' => "Pending handover"
    ),array(
    'id' => 2,
    'feedback_status' => "Pending digitization"
	),array(
    'id' => 3,
    'feedback_status' => "Pending publishing"
	),array(
    'id' => 4,
    'feedback_status' => "Published"
	)));
}

// need a way to do conditional schema migrations...
{
  $wpdb->query("UPDATE impact_feedback_status SET id=1 WHERE id=2");
  db_update_rows("impact_feedback_status", array(array(
    'id' => 1,
    'feedback_status' => "not recorded"
  ),array(
    'id' => 3,
    'feedback_status' => "recorded"
  ),array(
    'id' => 4,
    'feedback_status' => "published"
  )));
  db_delete_row("impact_feedback_status", array(
    'id' => 2
  ));
}

// INCREMENTAL: money_transfer_status table
if (db_add_table("money_transfer_status", array(
  'id' => " int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'transfer_status' => "varchar(255) NOT NULL")))
{
  db_insert_rows("money_transfer_status", array(array(
    'id' => 1,
    'transfer_status' => "Pending transfer"
    ),array(
    'id' => 2,
    'transfer_status' => "Transferred to Champion Partner"
	),array(
    'id' => 3,
    'transfer_status' => "Transferred to charity")
	));
}
// need a way to do conditional schema migrations...
{
  db_update_rows("money_transfer_status", array(array(
    'id' => 1,
    'transfer_status' => "received by SYI"
  ),array(
    'id' => 2,
    'transfer_status' => "transferred to certifying org"
  ),array(
    'id' => 3,
    'transfer_status' => "transferred to field partner"
  )));
}


// INCREMENTAL: impact_distribution_status table
if (db_add_table("item_distribution_status", array(
  'id' => " int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'distribution_status' => "varchar(255) NOT NULL")))
{
  db_insert_rows("item_distribution_status", array(array(
    'id' => 1,
    'distribution_status' => "Pending order"
    ),array(
    'id' => 2,
    'distribution_status' => "Ordered"
	),array(
    'id' => 3,
    'distribution_status' => "In Stock"
	),array(
    'id' => 4,
    'distribution_status' => "With Recipient")
	));
}
// need a way to do conditional schema migrations...
{
  $wpdb->query("UPDATE item_distribution_status SET id=1 WHERE id=2");
  db_update_rows("item_distribution_status", array(array(
    'id' => 1,
    'distribution_status' => "not allocated"
  ),array(
    'id' => 3,
    'distribution_status' => "on order"
  ),array(
    'id' => 4,
    'distribution_status' => "delivered"
  )));
  db_delete_row("item_distribution_status", array(
    'id' => 2
  ));
}

// INCREMENTAL: gift_donation_status table
db_add_table("gift_donation_status", array(
  'id' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'donationID' => "int(10) unsigned NOT NULL default '0'",
  'transfer_statusID' => "int(10) unsigned NOT NULL default '0'",
  'impact_statusID' => "int(10) unsigned NOT NULL default '0'",
  'distribution_statusID' => "int(10) unsigned NOT NULL default '0'"
   ));


// INCREMENTAL: add new schema for donation history & notifications

db_add_table("donation", array(
  'donationID' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'donationDate' => "datetime NOT NULL default '0000-00-00 00:00:00'",
  'donationAmount_Total' => "double NOT NULL default '0'",
  'impactStatus' => "int(10) unsigned NOT NULL default '0'",
  'distributionStatus' => "int(10) unsigned NOT NULL default '0'",
  'fundTransferStatus' => "int(10) unsigned NOT NULL default '0'",
  'notificationsSent' => "int(10) unsigned NOT NULL default '0'",
  'instructions' => "varchar(255) default NULL",
  'updateDate' => "datetime NOT NULL default '0000-00-00 00:00:00'"
  ), "ROW_FORMAT=FIXED");

db_add_table("donationGifts", array(
  'donationID' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'giftID' => "int(10) unsigned NOT NULL default '0' /*PRIMARYKEY*/",
  'unitsDonated' => "int(10) unsigned NOT NULL default '0'",
  'amount' => "int(10) unsigned NOT NULL default '0'"
  ));

db_add_table("donationGiver", array(
  'ID' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'email' => "varchar(255) NOT NULL default ''",
  'sendUpdates' => "tinyint(1) NOT NULL default '0'",
  'firstName' => "varchar(255) NOT NULL default ''",
  'lastName' => "varchar(255) NOT NULL default ''",
  'donationOwner' => "tinyint(1) NOT NULL default '0'",
  ), "AUTO_INCREMENT=7");

db_add_table("donationDonor", array(
  'donationID' => "int(10) unsigned NOT NULL default '0'",
  'donationGiverID' => "int(10) unsigned NOT NULL default '0'"
  ));

db_add_table("donationHistory", array(
  'donationHistoryID' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'donationID' => "int(10) unsigned NOT NULL",
  'transactionDate' => "datetime NOT NULL default '0000-00-00 00:00:00'",
  'modifiedBy' => "int(10) unsigned NOT NULL default '0'",
  'action' => "varchar(255) default NULL"
  ));  //V2 - removed some columns and added 'action'

// Update old charities to new status
db_add_column("donationHistory.action", "varchar(255) default NULL"); 
db_remove_column("donationHistory.impactStatus");
db_remove_column("donationHistory.distributionStatus");
db_remove_column("donationHistory.fundTransferStatus");

db_add_table("notificationGroup", array(
  'groupID' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'groupName' => "varchar(255) NOT NULL default ''",
  'groupTags' => "varchar(255) NOT NULL default ''"
  ));

db_add_table("notificationGroupMember", array(
  'groupMemberID' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'groupID' => "int(10) unsigned NOT NULL default '0'",
  'memberEmail' => "varchar(255) NOT NULL default ''",
  'sendUpdates' => "tinyint(1) NOT NULL default '0'"
  ));

db_add_table("notificationHistory", array(
  'notificationID' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'donorID' => "int(10) unsigned NOT NULL default '0'",
  'donationID' => "int(10) unsigned NOT NULL default '0'",
  'sentDate' => "datetime NOT NULL default '0000-00-00 00:00:00'",
  'success' => "tinyint(1) NOT NULL default '0'",
  'emailTo' => "varchar(255) NOT NULL default ''",
  'emailSubject' => "varchar(1000) NOT NULL default ''",
  'emailText' => "varchar(2000) NOT NULL default ''"
  ));

// Tables for Email Engine plugin

if(db_add_table("EE_EMAIL_TEMPLATE", array(
'ID' => "BIGINT(20) unsigned NOT NULL AUTO_INCREMENT",
'MAIL_SUBJECT' => "VARCHAR(1024) DEFAULT ''",
'MAIL_CONTENT' => "VARCHAR(2048) DEFAULT ''",            
'MAIL_TYPE_ID' => "INT(10) DEFAULT NULL",
'BLOG_ID' => "BIGINT(20) DEFAULT NULL"
))){
	$thank_mail_subject='Thank You donor ...';
	$thank_mail_content='Dear $DONOR_NAME, <br/>Thank you for donating $GIFT_NAME, to our charity foundation $CHARITY_NAME - $CHARITY_DESCRIPTION.';
	$thank_mail_content.='<br/><br/>Regards,<br/>$CHARITY_NAME';
	
	$update_mail_subject='You have an update mail ..';
	$update_mail_content='Dear donor,<br/>You have an update waiting for you at $CHARITY_NAME.<br/>';
	$update_mail_content.='Please click on the link below to open the update page.<br/>$POST_LINK';
	$update_mail_content.='<br/><br/>Regards,<br/>$CHARITY_NAME';
	
	db_insert_rows("EE_EMAIL_TEMPLATE", 
							array(
									array(
									    'ID' => 1,
									    'MAIL_SUBJECT' => $thank_mail_subject,
									    'MAIL_CONTENT' => $thank_mail_content,
									    'MAIL_TYPE_ID' => "1",
									    'BLOG_ID' => "1"
									  ),
									 array(
									    'ID' => 2,
									    'MAIL_SUBJECT' => $update_mail_subject,
									    'MAIL_CONTENT' => $update_mail_content,
									    'MAIL_TYPE_ID' => "2",
									    'BLOG_ID' => "1"
  								)
  							)
  						);
}

$successfullDonationSubject='A gift has been donated...';
$successfullDonationContent='Dear Admin,<br/>'.
							'A gift was successfully donated, with the following details:<br/>'.
							'Donor Name:$DONOR_NAME<br/>' .
							'Donor Email:$DONOR_EMAIL<br/>'.
							'Transaction date:$TRANSACTION_DATE<br/>'.
							'Regards,<br/>'.
							'$CHARITY_NAME';

if(db_count('EE_EMAIL_TEMPLATE where BLOG_ID=1 AND MAIL_TYPE_ID=3')==0){
	db_insert_rows("EE_EMAIL_TEMPLATE", 
							array(
								 array(
								    'MAIL_SUBJECT' => $successfullDonationSubject,
								    'MAIL_CONTENT' => $successfullDonationContent,
								    'MAIL_TYPE_ID' => "3",
								    'BLOG_ID' => "1"
  							)
  						)
  					);
}

if(db_count('EE_EMAIL_TEMPLATE where BLOG_ID=1 AND MAIL_TYPE_ID=4')==0){
	db_insert_rows("EE_EMAIL_TEMPLATE", 
		array(
			 array(
			    'MAIL_SUBJECT' => 'An impact story has been saved',
			    'MAIL_CONTENT' => 'An impact story has been saved:<br/>$POST_LINK<br/>'.
	                'Donor Name:$DONOR_NAME<br/>' .
	                'Donor Email:$DONOR_EMAIL<br/>'.
	                'Transaction date:$TRANSACTION_DATE<br/>'.
	                'Regards,<br/>$CHARITY_NAME',
			    'MAIL_TYPE_ID' => "4",
			    'BLOG_ID' => "1"
			)
		)
	);
}

  //For those database, which already has an EE_EMAIL_TEMPLATE table but does not have an auto_increment attribute on ID column
  $wpdb->query("ALTER TABLE EE_EMAIL_TEMPLATE MODIFY COLUMN ID bigint(20) NOT NULL auto_increment;");  
   
  //Change the EE_EMAIL_TABLE columns subject and content lengths..
  $wpdb->query("ALTER TABLE EE_EMAIL_TEMPLATE MODIFY COLUMN MAIL_SUBJECT varchar(1024) default '';");
  $wpdb->query("ALTER TABLE EE_EMAIL_TEMPLATE MODIFY COLUMN MAIL_CONTENT varchar(2048) default '';");
  
 
  //New set of tempaltes

  //Delete the default templates of mail type 1 and 2
  $deleteQuery="DELETE FROM EE_EMAIL_TEMPLATE where blog_id=1 AND mail_type_id = 1;";
  $wpdb->query($deleteQuery);
  
  $deleteQuery="DELETE FROM EE_EMAIL_TEMPLATE where blog_id=1 AND mail_type_id = 2;"; 
  $wpdb->query($deleteQuery);
  
  //Thank you mail to donor
  $subject='Thank you from SeeYourImpact.org and $CHARITY_NAME';
  $content='Dear $DONOR_NAME,' .
  			'<br/>&nbsp;&nbsp;&nbsp;&nbsp;Thank you for your generous donation of a $GIFT_NAME to '.
  			'$CHARITY_NAME via SeeYourImpact.org'.
			'<br/><br/>Sincerely,'.
			'<br/>$CHARITY_NAME';
  
  db_insert_rows("EE_EMAIL_TEMPLATE", 
							array(
								 array(
								    'MAIL_SUBJECT' => $subject,
								    'MAIL_CONTENT' => $content,
								    'MAIL_TYPE_ID' => "1",
								    'BLOG_ID' => "1"
  							)
  						)
  					);
  
  //Thank you mail to donor
  $subject='Update regarding your donation to $CHARITY_NAME at SeeYourImpact.org';
  $content='Dear $DONOR_NAME,' .
  			'<br/>&nbsp;&nbsp;&nbsp;&nbsp;An update related to your gift of a $GIFT_NAME to $CHARITY_NAME is posted at SeeYourImpact.org.' .
  			'<br/>Click on the link below to see the latest update:' .
  			'<br/>$POST_LINK'.
  			
			'<br/><br/>Sincerely,'.
			'<br/>$CHARITY_NAME';
  
  db_insert_rows("EE_EMAIL_TEMPLATE", 
							array(
								 array(
								    'MAIL_SUBJECT' => $subject,
								    'MAIL_CONTENT' => $content,
								    'MAIL_TYPE_ID' => "2",
								    'BLOG_ID' => "1"
  							)
  						)
  					);
  					
  //Adding the verification url column to the  paypal_settings table
  
  $wpdb->query("ALTER TABLE paypal_settings ADD COLUMN verify_url VARCHAR(255) DEFAULT '';");
  
  $wpdb->query("UPDATE paypal_settings SET verify_url='ssl://www.sandbox.paypal.com' WHERE type='TEST';");
  $wpdb->query("UPDATE paypal_settings SET verify_url='ssl://www.paypal.com' WHERE type='LIVE'");

//creation of table to store the member badges
// INCREMENTAL: bp_member_profile_badges table
if (db_add_table("bp_member_profile_badges", array(
  'id' => " int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'badge_key' => "varchar(255) NOT NULL")))
{
  db_insert_rows("bp_member_profile_badges", array(array(
    'id' => 1,
    'badge_key' => "Staff "
    ),array(
    'id' => 2,
    'badge_key' => "Volunteer "
    ),array(
    'id' => 3,
    'badge_key' => "Donor "
    ),array(
    'id' => 4,
    'badge_key' => "Community Member"
    )));
}



db_add_table("wp_invitations", array(
  'id' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'user_id' => "int(10) unsigned NOT NULL default '0' /*KEY*/",
  'invited_email' => "varchar(255) NOT NULL default '' /*KEY*/",
  'datestamp' => "datetime NOT NULL default '0000-00-00 00:00:00' /*KEY*/"
  ));
  
// table to maintain list of authkeys for charities
db_add_table("charityAuthKeys", array(
  'charityID' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'authKey' => "int(10) unsigned NOT NULL default '0' /*KEY*/",
  'description' => "varchar(255) NOT NULL default '' /*KEY*/"
  ));

// table to track current version of database.
db_add_table("version", array(
  'id' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'version' => "int(10) unsigned NOT NULL",
  'datestamp' => "datetime NOT NULL default '0000-00-00 00:00:00'"
  ));

// table to track version history -- the incrementals applied to the DB.
db_add_table("version_log", array(
  'id' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
  'version' => "int(10) unsigned NOT NULL",
  'datestamp' => "datetime NOT NULL default '0000-00-00 00:00:00'"
  ));


// ===========================================  
// INSERT NEW INCREMENTALS RIGHT ABOVE THIS
// ===========================================

*/

