<?php

/**
 * RecurlyClient provides methods for interacting with the {@link http://support.recurly.com/faqs/api Recurly} API.
 * 
 * @category   Recurly
 * @package    Recurly_Client_PHP
 * @copyright  Copyright (c) 2010 {@link http://recurly.com Recurly, Inc.}
 */
class RecurlyClient
{
    const API_CLIENT_VERSION = '0.1.8';
    const API_URL = 'https://%s.recurly.com';
    const DEFAULT_ENCODING = 'UTF-8';

    const PATH_ACCOUNTS = '/accounts/';
    const PATH_BILLING_INFO = '/billing_info';
    const PATH_ACCOUNT_CHARGES = '/charges';
    const PATH_ACCOUNT_CREDITS = '/credits';
    const PATH_ACCOUNT_INVOICES = '/invoices';
    const PATH_ACCOUNT_SUBSCRIPTION = '/subscription';
    const PATH_TRANSACTIONS = '/transactions';

    const PATH_INVOICES = '/invoices/';
    const PATH_PLANS = '/company/plans/';

	static $class_map = array(
		'account' => 'RecurlyAccount',
		'billing_info' => 'RecurlyBillingInfo',
		'charge' => 'RecurlyAccountCharge',
		'credit' => 'RecurlyAccountCredit',
		'credit_card' => 'RecurlyCreditCard',
		'error' => 'RecurlyError',
		'invoice' => 'RecurlyInvoice',
		'latest_version' => '', // Depreciated -- ignore
		'line_item' => 'RecurlyLineItem',
		'line_items' => 'array',
		'plan' => 'RecurlyPlan',
		'plan_version' => '', // Depreciated -- ignore
		'payment' => 'RecurlyTransaction',
		'payments' => 'array',
		'pending_subscription' => 'RecurlyPendingSubscription',
		'subscription' => 'RecurlySubscription');


    /**
    * Recurly account username
    *
    * @var string
    */
    static $username = '';

    /**
    * Recurly account password
    *
    * @var string 
    */
    static $password = '';

    /**
    * Recurly account subdomain
    *
    * @var string 
    */
    static $subdomain = '';

    /**
    * Set Recurly username and password.
    *
    * @param string $username Recurly username
    * @param string $password Recurly password
    */
    public static function SetAuth($username, $password, $subdomain='app')
    {
        self::$username = $username;
        self::$password = $password;
        self::$subdomain = $subdomain;
    }
	
	
	
	
	
    /**
    * Sends an HTTP request to the Recurly API with Basic authentication.
    *
    * @param string  $uri    Target URI for this request (relative to the API root)
    * @param string  $method Specifies the HTTP method to be used for this request
    * @param mixed   $data   x-www-form-urlencoded data (or array) to be sent in a POST request body
    *
    * @return RecurlyResponse
    * @throws RecurlyException
    */
	public static function __sendRequest($uri, $method = 'GET', $data = '')
	{
        if(function_exists('mb_internal_encoding'))
        {
            mb_internal_encoding(self::DEFAULT_ENCODING);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, sprintf(self::API_URL, self::$subdomain) . $uri);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/xml; charset=utf-8',
            'Accept: application/xml',
            "User-Agent: Recurly PHP Client v" . self::API_CLIENT_VERSION
        )); 

        curl_setopt($ch, CURLOPT_USERPWD, self::$username . ':' . self::$password);

        if('POST' == ($method = strtoupper($method)))
        {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        	else if ('PUT' == $method)
        	{
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        	}
        else if('GET' != $method)
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $result = new StdClass();
        $result->response = curl_exec($ch);
        $result->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result->meta = curl_getinfo($ch);
        
        $curl_error = ($result->code > 0 ? null : curl_error($ch) . ' (' . curl_errno($ch) . ')');

        curl_close($ch);
        
        if ($result->code == 0)
            throw new RecurlyConnectionException('An error occurred while connecting to Recurly: ' . $curl_error);

        return $result;
	}
	
	

	public static function __parse_xml($xml, $node_name, $parse_attributes = false) {
		$dom = @DOMDocument::loadXML($xml);
		if (!$dom) return null;

		$childNodes = $dom->getElementsByTagName($node_name);
		$list = array();
		for ($i=0; $i < $childNodes->length; $i++) {
			$node = $childNodes->item($i)->firstChild;
			$node_class = RecurlyClient::$class_map[$node_name];
			$list[] = RecurlyClient::__parseXmlToObject($node, $node_class, $parse_attributes);
		}
		
		if (count($list) == 0)
			return null;
		else if (count($list) == 1)
			return $list[0];
		else
			return $list;
	}

	protected static function __parseXmlToObject($node, $node_class, $parse_attributes) {
		if ($node_class != null)
		{
		  if ($node_class == 'array')
		    $obj = array();
		  else
		    $obj = new $node_class();
		}
		else
			$obj = new RecurlyObject();
		
		while ($node) {
			if ($node->nodeType == XML_TEXT_NODE) {
				if ($node->wholeText != null) {
					$text = trim($node->wholeText);
					if (strlen($text) > 0)
						$obj->message = $text;
				}
			} else if ($node->nodeType == XML_ELEMENT_NODE) {
				$nodeName = str_replace("-", "_", $node->nodeName);
				
				if (is_array($obj)) {
				  $child_node_class = RecurlyClient::$class_map[$nodeName];
					$obj[] = RecurlyClient::__parseXmlToObject($node->childNodes->item(0), $child_node_class, $parse_attributes);
				  
				  $node = $node->nextSibling;
				  continue;
				}
				
				if (!is_numeric($node->nodeValue) && $tmp = strtotime($node->nodeValue))
					$obj->$nodeName = $tmp;
				else if ($node->nodeValue == "false")
					$obj->$nodeName = false;
				else if ($node->nodeValue == "true")
					$obj->$nodeName = true;
				else
					$obj->$nodeName = $node->nodeValue;

				if ($node->childNodes->length > 1) {
					$child_node_class = RecurlyClient::$class_map[$nodeName];
					
					if ($child_node_class != '') {
					  $obj->$nodeName = RecurlyClient::__parseXmlToObject($node->childNodes->item(0), $child_node_class, $parse_attributes);
				  }
				}

				if ($parse_attributes) {
					foreach ($node->attributes as $attrName => $attrNode) {
						$nodeName = str_replace("-", "_", $attrName);
						$nodeValue = $attrNode->nodeValue;
						if (!is_numeric($nodeValue) && $tmp = strtotime($nodeValue))
							$obj->$nodeName = $tmp;
						else if ($nodeValue == "false")
							$obj->$nodeName = false;
						else if ($nodeValue == "true")
							$obj->$nodeName = true;
						else
							$obj->$nodeName = $nodeValue;
					}
				}
			}
			$node = $node->nextSibling;
		}
		return $obj;
	}
}

// In case node_class is not specified.
class RecurlyObject {}
