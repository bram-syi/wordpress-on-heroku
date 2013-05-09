<?

global $cartID;

if(isset($_REQUEST['cid']))
  $cartID = decrypt_cart($_REQUEST['cid']);

echo thankyou_widget();

?>