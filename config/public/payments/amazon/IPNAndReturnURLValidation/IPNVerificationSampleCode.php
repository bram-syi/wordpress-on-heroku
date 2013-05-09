<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     Amazon_FPS
 *  @copyright   Copyright 2008 Amazon Technologies, Inc.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2008-09-17
 */

require_once '.config.inc.php';
require_once 'SignatureUtilsForOutbound.php';
  
class IPNVerificationSampleCode {

	public static function test() {
		
        $utils = new SignatureUtilsForOutbound();
        
        //Parameters present in ipn.
	$params["transactionId"] = "14GPJPNG55RS4ECHZOSNAQA4N8AHOEU9D44"; 
	$params["transactionDate"] = "1254990077"; 
	$params["status"] = "PS"; 
	$params["signatureMethod"] = "RSA-SHA1"; 
	$params["signatureVersion"] = "2"; 
	$params["buyerEmail"] = "test-sender@amazon.com"; 
	$params["recipientEmail"] = "test-recipient@amazon.com"; 
	$params["operation"] = "pay"; 
	$params["transactionAmount"] = "USD 1.200000"; 
	$params["referenceId"] = "test-reference123"; 
	$params["buyerName"] = "test sender"; 
	$params["recipientName"] = "Test Business"; 
	$params["paymentMethod"] = "CC"; 
	$params["paymentReason"] = "Test Widget"; 
	$params["certificateUrl"] = "https://fps.sandbox.amazonaws.com/certs/090909/PKICert.pem"; 
	$params["signature"] ="SVkZgR4WNg9cyoC6e2c215CkQLRUJddWnVZJ+ql1tgNMmOGDSHtLj9qny36YxuHyGXMgBJirr4/IBIC5ohiiIecDuGDfDeZPr4dm3y7xYmQdqw1BBSJ9oEaLHW7BLowNI9jJ+n95/5zOAojEODGzbfy316vleMU8X0oJ8Kcygodyvz31xNCIp41Tl3rEMvIWr4qOsFlT6M6qr4o/B3LH9don3YEuwsXrDPxGDDQkNxv/TVAlwuhNXiLljbxIrvCMgcaPakA9CBSVBkjFwEggFl1XRLvvI9aEtMjKPQt/2Ly84jJat6HKmEmZ1797rAi3NWtTLwRppEnygfmKYfV5Kg==";

        $urlEndPoint = "http://yourwebsite.com/ipn.jsp"; //Your url end point receiving the ipn.
         
        print "Verifying IPN signed using signature v2 ....\n";
        //IPN is sent as a http POST request and hence we specify POST as the http method.
        //Signature verification does not require your secret key
        print "Is signature correct: " . $utils->validateRequest($params, $urlEndPoint, "POST") . "\n";
	}
}

IPNVerificationSampleCode::test(); 
?>
