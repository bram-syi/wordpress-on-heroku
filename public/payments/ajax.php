<?
/**********************************************************************/
// Author:      Yosia U
// Description: Ajax Handler for GC
// 
// Last Edited: April 2010
/**********************************************************************/

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

if(isset($_POST)){
  //Retrieve recipient data
  $recipient_first_name = as_name($_REQUEST['recipient_first_name']);
  $recipient_last_name = as_name($_REQUEST['recipient_last_name']);
  $recipient_email = trim($_REQUEST['recipient_email']);  
  $recipient_message = trim($_REQUEST['recipient_message']);

  $sql = $wpdb->prepare("SELECT ID FROM donationGiver WHERE email = '%s'", $recipient_email);
  $id = $wpdb->get_var($sql);

  if($id == NULL) {
	if(empty($recipient_first_name) || empty($recipient_last_name) || empty($recipient_email)) {
	  echo 0; return;
	}

	$wpdb->insert("donationGiver", array(
	  'firstName' => $recipient_first_name, 
	  'lastName' => $recipient_last_name,
	  'email' => $recipient_email,
	  'notes' => $recipient_message));
	echo $wpdb->insert_id;
  } else {
	$sql = $wpdb->prepare("UPDATE donationGiver SET notes = '%s' WHERE ID=%d", $recipient_message, $id);
	$wpdb->query($sql);
	echo $id;
  }  
}
?>
