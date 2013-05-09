<?
/**********************************************************************/
// Author:      Yosia Urip
// Description: 
//
// Last Edited: May 2010
/**********************************************************************/

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

if(isset($_POST)){
  $paymentID = store_cart($_REQUEST);
  echo $paymentID;
}
?>