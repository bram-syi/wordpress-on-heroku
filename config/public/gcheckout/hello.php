<?php
// Pull in the NuSOAP code
require_once('nusoap.php');
// Create the server instance
$ns="http://".$_SERVER['SERVER_NAME'];

$server = new soap_server;
// Register the method to expose
$server->configureWSDL('hello',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

// register a web service method
$server->register('hello',
	array('int1' => 'xsd:integer','int2' => 'xsd:integer'), 	// input parameters
	array('total' => 'xsd:integer'), 							// output parameter
	$ns, 														// namespace
    "$ns#hello",		                						// soapaction
    'rpc',                              						// style
    'encoded',                          						// use
    'return the sum of two values'           	// documentation
	);

/*$server->register('hello');*/
// Define the method as a PHP function
function hello($int1, $int2){
    return new soapval('return','xsd:integer',add($int1, $int2));
}
// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);


// implementation of add function
function add($int1, $int2) {
	return $int1 + $int2;
}
?>
