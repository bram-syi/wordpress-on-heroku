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
 
require_once 'SignatureUtils.php';
 
class ButtonGenerator {
	const SIGNATURE_KEYNAME = "signature";
        const SIGNATURE_METHOD_KEYNAME = "signatureMethod";
        const SIGNATURE_VERSION_KEYNAME = "signatureVersion";
        const HMAC_SHA1_ALGORITHM = "HmacSHA1";
        const HMAC_SHA256_ALGORITHM = "HmacSHA256";
	const SIGNATURE_VERSION = "2";
        const COBRANDING_STYLE = "logo";
	private static $httpMethod = "POST";
        public static  $SANDBOX_END_POINT = "https://authorize.payments-sandbox.amazon.com/pba/paypipeline";
        public static  $SANDBOX_IMAGE_LOCATION="https://authorize.payments-sandbox.amazon.com/pba/images/SLDonationWithLogo.png";
        public static  $PROD_END_POINT = "https://authorize.payments.amazon.com/pba/paypipeline";
        public static  $PROD_IMAGE_LOCATION="https://authorize.payments.amazon.com/pba/images/SLDonationWithLogo.png";
	
	/**
         * Function creates a Map of key-value pairs for all valid values passed to the function 
         * @param accessKey - Put your Access Key here  
         * @param amount - Enter the amount you want to collect for the item
         * @param description - description - Enter a description of the item
         * @param referenceId - Optionally enter an ID that uniquely identifies this transaction for your records
         * @param abandonUrl - Optionally, enter the URL where senders should be redirected if they cancel their transaction
         * @param returnUrl - Optionally enter the URL where buyers should be redirected after they complete the transaction
         * @param immediateReturn - Optionally, enter "1" if you want to skip the final status page in Amazon Payments
         * @param processImmediate - Optionally, enter "1" if you want to settle the transaction immediately else "0". Default value is "1"
         * @param ipnUrl - Optionally, type the URL of your host page to which Amazon Payments should send the IPN transaction information.
         * @param collectShippingAddress - Optionally, enter "1" if you want Amazon Payments to return the buyer's shipping address as part of the transaction information.
         * @param signatureMethod -Valid values are  HmacSHA256 and HmacSHA1
	 * @param donationType - Optionally, enter the type of donation. Valid values are fixedAmount, minimumAmount and anyAmount. Default value is anyAmount
         * @return - A map of key of key-value pair for all non null parameters
         * @throws Exception
         */

	public static function getDonationParams($accessKey,$amount, $description, $referenceId, $immediateReturn,
			$returnUrl, $abandonUrl, $processImmediate, $ipnUrl, $collectShippingAddress,  
			$signatureMethod,$donationType) {
		$cobrandingStyle= self::COBRANDING_STYLE;
		
		$formHiddenInputs = array();
		if($accessKey!=null) $formHiddenInputs["accessKey"] = $accessKey;
		else throw new Exception("Accesskey is Required");
		if($description!=null) $formHiddenInputs["description"] = $description;
		else throw new Exception("Description is required");
   	        if($signatureMethod!=null) $formHiddenInputs[self::SIGNATURE_METHOD_KEYNAME] = $signatureMethod;
                else throw new Exception("Signature Method is required");
	
		if ($donationType == null) $donationType="anyAmount"; 
		if ($donationType == "minimumAmount"){
			$formHiddenInputs["minimumDonationAmount"]= $amount;
		}else if ($donationType == "fixedAmount"){
			$formHiddenInputs["amount"]= $amount;
		}
		$formHiddenInputs["donationType"]=$donationType;
		$formHiddenInputs["isdonationWidget"]="1";

		if ($referenceId != null) $formHiddenInputs["referenceId"] = $referenceId;
		if ($immediateReturn != null) $formHiddenInputs["immediateReturn"] = $immediateReturn;
		if ($returnUrl != null) $formHiddenInputs["returnUrl"] = $returnUrl;
		if ($abandonUrl != null) $formHiddenInputs["abandonUrl"] = $abandonUrl;
		if ($processImmediate != null) $formHiddenInputs["processImmediate"] = $processImmediate;
		if ($ipnUrl != null) $formHiddenInputs["ipnUrl"] = $ipnUrl;
		if ($cobrandingStyle != null) $formHiddenInputs["cobrandingStyle"] = $cobrandingStyle;
		if ($collectShippingAddress != null) $formHiddenInputs["collectShippingAddress"] = $collectShippingAddress;
	
		$formHiddenInputs[self::SIGNATURE_VERSION_KEYNAME] = self::SIGNATURE_VERSION;
		return $formHiddenInputs;
	}
	 /**
         * Creates a form from the provided key-value pairs 
         * @param formHiddenInputs - A map of key of key-value pair for all non null parameters
         * @param serviceEndPoint - The Endpoint to be used based on environment selected
         * @param imageLocation - The imagelocation based on environment
	 * @param donationType - Optionally, enter the type of donation. Valid values are fixedAmount, minimumAmount and anyAmount. Default value is anyAmount
         * @return - An html form created using the key-value pairs
         */
	public static function getDonationForm(array $formHiddenInputs,$endPoint,$imageLocation,$donationType) {

		$form = "";
		if($donationType!="fixedAmount"){

			$form.="<div style=\"width:20em;padding-left:10px;padding-top:10px;padding-right:10px;padding-bottom:10px;\">";
			$form.="<table class=\"table\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"> <tr>";
		}
		$form .=  "<form action=\""; 
		$form .= $endPoint;
		$form .= "\" method=\"";
		$form .= self::$httpMethod . "\">\n";
		
		foreach ($formHiddenInputs  as $name => $value) {
			$form .= "<input type=\"hidden\" name=\"$name";  
			$form .= "\" value=\"$value";
			$form .= "\" >\n";
		}
		if($donationType != "fixedAmount"){
			$form .= "<td>$&nbsp;<input type=\"text\" name=\"amount\" size=\"8\" value=\"" ;
			if($donationType == "minimumAmount")
				$form .= str_replace('USD ','',$formHiddenInputs["minimumDonationAmount"]);
			else 
				$form.="";	
			$form .=  "\"</td><td>&nbsp;</td><td><input type=\"image\" src=\"";
			$form .= $imageLocation;
			$form .= "\" border=\"0\"></td></form></tr></table></div>";
			}
			else{
			$form .= "<input type=\"image\" src=\"".$imageLocation."\" border=\"0\">\n";
			$form .= "</form>\n";
		}

		
		return $form;
	}
	 /**
         * Function Generates the html form 
         * @param accessKey - Put your Access Key here  
         * @param secretKey - Put your secret Key here
         * @param amount - Enter the amount you want to collect for the ite
         * @param description - description - Enter a description of the item
         * @param referenceId - Optionally enter an ID that uniquely identifies this transaction for your records
         * @param abandonUrl - Optionally, enter the URL where senders should be redirected if they cancel their transaction
         * @param returnUrl - Optionally enter the URL where buyers should be redirected after they complete the transaction
         * @param immediateReturn - Optionally, enter "1" if you want to skip the final status page in Amazon Payments
         * @param processImmediate - Optionally, enter "1" if you want to settle the transaction immediately else "0". Default value is "1"
         * @param ipnUrl - Optionally, type the URL of your host page to which Amazon Payments should send the IPN transaction information.
         * @param collectShippingAddress - Optionally, enter "1" if you want Amazon Payments to return the buyer's shipping address as part of the transaction information
         * @param signatureMethod - Valid values are  HmacSHA256 and HmacSHA1
	 * @param donationType - Optionally, enter the type of donation. Valid values are fixedAmount, minimumAmount and anyAmount. Default value is anyAmount
         * @param environment - Sets the environment where your form will point to can be "sandbox" or "prod" 
         * @return - A map of key of key-value pair for all non null parameters
         * @throws Exception
         */

	 public static function GenerateForm($accessKey,$secretKey,$amount, $description, $referenceId, $immediateReturn,
                        $returnUrl, $abandonUrl, $processImmediate, $ipnUrl,$collectShippingAddress,
                        $signatureMethod,$donationType,$environment) {
			 			if($environment=="prod"){
                                $endPoint = self::$PROD_END_POINT;
                                $imageLocation = self::$PROD_IMAGE_LOCATION;
                        } else {
                                $endPoint= self::$SANDBOX_END_POINT;
                                $imageLocation = self::$SANDBOX_IMAGE_LOCATION;
                        }


                $params = self::getDonationParams($accessKey,$amount, $description, $referenceId, $immediateReturn,
                        $returnUrl, $abandonUrl, $processImmediate, $ipnUrl,$collectShippingAddress, $signatureMethod,$donationType);

                $serviceEndPoint = parse_url($endPoint);
                $signature = SignatureUtils::signParameters($params, $secretKey,
                                self::$httpMethod, $serviceEndPoint['host'], $serviceEndPoint['path'],$signatureMethod);
                
				$params[self::SIGNATURE_KEYNAME] = $signature;
				$simplePayForm = self::getDonationForm($params,$endPoint,$imageLocation,$donationType);
                print $simplePayForm . "\n";
				
				//return $signature;
        }

	
}


?>
