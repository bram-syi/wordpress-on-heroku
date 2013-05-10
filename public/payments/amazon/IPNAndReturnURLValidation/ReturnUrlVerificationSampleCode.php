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
  
class ReturnUrlVerificationSampleCode {

	public static function test() {
        $utils = new SignatureUtilsForOutbound();
        
        //Parameters present in return url.
        $params["transactionId"] = "14GPJPNG55RS4ECHZOSNAQA4N8AHOEU9D44";
        $params["transactionDate"] = "1254990077";
        $params["status"] = "PS";
        $params["signatureMethod"] = "RSA-SHA1";
        $params["signatureVersion"] = "2";
        $params["buyerEmail"] = "test-sender@amazon.com";
        $params["recipientEmail"] = "test-recipient@amazon.com";
        $params["operation"] = "pay";
        $params["transactionAmount"] = "USD 1.2";
        $params["referenceId"] = "test-reference123";
        $params["buyerName"] = "test sender";
        $params["recipientName"] = "Test Business";
        $params["paymentMethod"] = "Credit Card";
        $params["paymentReason"] = "Test Widget";
        $params["certificateUrl"] = "https://fps.sandbox.amazonaws.com/certs/090909/PKICert.pem";
        $params["signature"] = "HY14oCfNhuRt+gzWiwsnXT0ocrfIysD01vqMOopU15zUlS5XW3h4zOd98YwxeJuQE9jhIm2O52bu3c1HG5KZZUpsmKt6NKXRvihfCZ2xOpeIXzV0PZBZB0MefU4rtlZ5CsKAlfPm7XYcWq4eOIR/9jkN3wmB+ekZmd1fh1hePQ3kRYvOaQADG6kRmhGc9yCeb7uDzbwnZoHk1bZd4PDJxM+4Sm/Nu2BujhWG36b5KBPZ+cMGlqGRAHX8OPWGX72XjhxYIToP7SVfvqnP4h09PHOiyRqBfVfZoYc5MfupbyCrjrQpssvaqYteZ4sZNxQCyvgS/cnsixTxhGfnlqVpMA=="; 
 
        $urlEndPoint = "http://yourwebsite.com/return.jsp"; //Your return url end point. 
        print "Verifying return url signed using signature v2 ....\n";
        //return url is sent as a http GET request and hence we specify GET as the http method.
        //Signature verification does not require your secret key
        print "Is signature correct: " . $utils->validateRequest($params, $urlEndPoint, "GET") . "\n";
	}
}

ReturnUrlVerificationSampleCode::test(); 
?>
