<?php

// Temboo data aggregator:
// see library of available sources: https://live.temboo.com/library/

define('TEMBOO_ACCOUNT', 'seeyourimpact');
define('TEMBOO_API_KEY_NAME', 'SeeYourImpact');
define('TEMBOO_API_KEY_VALUE', 'bd5ee5a3-bfb2-49de-9');

require 'temboo-php-sdk/src/temboo.php';

/**
 * A simple Twitter search.
 */
try {
    echo "Searching Twitter...\n";

    // Instantiate a Temboo session with valid API key credentials
    $session = new Temboo_Session(TEMBOO_ACCOUNT, TEMBOO_API_KEY_NAME, TEMBOO_API_KEY_VALUE);

    // Instantiate the choreography using the session object
    $query = new Twitter_Search_Query($session);

    // Get an input object for the choreo
    $inputs = $query->newInputs();

    // Set the inputs
    $inputs->setQuery('Temboo');

    // Execute choreography and get results
    $results = $query->execute($inputs)->getResults();

    // Print the desired result
    echo $results->getResponse();

    echo "\n\nDone.\n";
}
catch(Temboo_Exception_Authentication $e) {
    echo "Temboo authentication exception caught: " . $e->getMessage() . "\n";
    echo "Debug info:\n\n";
    print_r($e->getDetails());
}
catch(Temboo_Exception_Execution $e) {
    echo "Temboo execution exception caught: " . $e->getMessage() . "\n";
    echo "Debug info:\n\n";
    print_r($e->getDetails());
}
catch(Temboo_Exception_Notfound $e) {
    echo "Temboo 'not found' exception caught: " . $e->getMessage() . "\n";
    echo "Debug info:\n\n";
    print_r($e->getDetails());
}
catch(Temboo_Exception $e) {
    echo "Temboo general exception caught: " . $e->getMessage() . "\n";
    echo "Debug info:\n\n";
    print_r($e->getDetails());
}
catch(Exception $e) {
    echo "Something else went wrong! " . $e->getMessage() . "\n\n";
}

?>
