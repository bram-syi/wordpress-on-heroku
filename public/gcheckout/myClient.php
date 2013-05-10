<?php
// Pull in the NuSOAP code
require_once('nusoap.php');
$wsdl="http://".$_SERVER['SERVER_NAME']."/gcheckout/hello.php?wsdl";
// Create the client instance
$ns="http://".$_SERVER['SERVER_NAME'];
if(!isset($client)) $client = new nusoap_client($wsdl,'true');
// Call the SOAP method
$result = $client->call('hello', array('int1'=>'15.00', 'int2'=>'10'));
// Display the result
$err = $client->getError();
    if($err) {
        echo '<p><b>Error: ' . $err . '</b></p>';
    }else
    {
echo "Going to print the result";
echo "<br/>";
print_r($result);
    }
// Display the request and response
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
?>
