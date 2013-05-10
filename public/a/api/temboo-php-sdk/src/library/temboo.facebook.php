<?php

/**
 * Temboo PHP SDK Facebook classes
 *
 * Execute Choreographies from the Temboo Facebook bundle.
 *
 * PHP version 5
 *
 * LICENSE: Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   Services
 * @package    Temboo
 * @subpackage Facebook
 * @author     Temboo, Inc.
 * @copyright  2012 Temboo, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @version    1.7
 * @link       http://live.temboo.com/sdk/php
 */


/**
 * Allows you to use a SQL-style syntax to query data in the Graph API.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_FQL extends Temboo_Choreography
{
    /**
     * Allows you to use a SQL-style syntax to query data in the Graph API.
     *
     * @param Temboo_Session $session The session that owns this FQL choreography.
     * @return Facebook_Searching_FQL New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Searching/FQL/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this FQL choreography.
     *
     * @param Facebook_Searching_FQL_Inputs|array $inputs (optional) Inputs as Facebook_Searching_FQL_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Searching_FQL_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Searching_FQL_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this FQL choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Searching_FQL_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Searching_FQL_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the FQL choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_FQL_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the FQL choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Searching_FQL_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this FQL input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Searching_FQL_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Searching_FQL_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this FQL choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Searching_FQL_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Conditions input for this FQL choreography.
     *
     * @param string $value (required, string) The conditions to use in the WHERE clause of the FQL statement.
     * @return Facebook_Searching_FQL_Inputs For method chaining.
     */
    public function setConditions($value)
    {
        return $this->set('Conditions', $value);
    }

    /**
     * Set the value for the Fields input for this FQL choreography.
     *
     * @param string $value (required, string) The fields to return in the response.
     * @return Facebook_Searching_FQL_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this FQL choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Searching_FQL_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Table input for this FQL choreography.
     *
     * @param string $value (required, string) The table to select records from.
     * @return Facebook_Searching_FQL_Inputs For method chaining.
     */
    public function setTable($value)
    {
        return $this->set('Table', $value);
    }
}


/**
 * Execution object for the FQL choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_FQL_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the FQL choreography.
     *
     * @param Temboo_Session $session The session that owns this FQL execution.
     * @param Facebook_Searching_FQL $choreo The choregraphy object for this execution.
     * @param Facebook_Searching_FQL_Inputs|array $inputs (optional) Inputs as Facebook_Searching_FQL_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Searching_FQL_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Searching_FQL $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this FQL execution.
     *
     * @return Facebook_Searching_FQL_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this FQL execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Searching_FQL_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Searching_FQL_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the FQL choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_FQL_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the FQL choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Searching_FQL_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this FQL execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Completes the OAuth process by retrieving a Facebook access token for a user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_OAuth_FinalizeOAuth extends Temboo_Choreography
{
    /**
     * Completes the OAuth process by retrieving a Facebook access token for a user.
     *
     * @param Temboo_Session $session The session that owns this FinalizeOAuth choreography.
     * @return Facebook_OAuth_FinalizeOAuth New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/OAuth/FinalizeOAuth/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this FinalizeOAuth choreography.
     *
     * @param Facebook_OAuth_FinalizeOAuth_Inputs|array $inputs (optional) Inputs as Facebook_OAuth_FinalizeOAuth_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_OAuth_FinalizeOAuth_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_OAuth_FinalizeOAuth_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this FinalizeOAuth choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_OAuth_FinalizeOAuth_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the FinalizeOAuth choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_OAuth_FinalizeOAuth_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the FinalizeOAuth choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this FinalizeOAuth input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccountName input for this FinalizeOAuth choreography.
     *
     * @param string $value (required, string) Your Temboo account name.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setAccountName($value)
    {
        return $this->set('AccountName', $value);
    }

    /**
     * Set the value for the AppID input for this FinalizeOAuth choreography.
     *
     * @param string $value (required, string) The App ID provided by Facebook (AKA the Client ID).
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setAppID($value)
    {
        return $this->set('AppID', $value);
    }

    /**
     * Set the value for the AppKeyName input for this FinalizeOAuth choreography.
     *
     * @param string $value (required, string) The name of your Temboo application key.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setAppKeyName($value)
    {
        return $this->set('AppKeyName', $value);
    }

    /**
     * Set the value for the AppKeyValue input for this FinalizeOAuth choreography.
     *
     * @param string $value (required, string) Your Temboo application key.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setAppKeyValue($value)
    {
        return $this->set('AppKeyValue', $value);
    }

    /**
     * Set the value for the AppSecret input for this FinalizeOAuth choreography.
     *
     * @param string $value (required, string) The App Secret provided by Facebook (AKA the Client Secret).
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setAppSecret($value)
    {
        return $this->set('AppSecret', $value);
    }

    /**
     * Set the value for the CallbackID input for this FinalizeOAuth choreography.
     *
     * @param string $value (required, string) The callback token returned by the InitializeOAuth Choreo. Used to retrieve the authorization code after the user authorizes.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setCallbackID($value)
    {
        return $this->set('CallbackID', $value);
    }

    /**
     * Set the value for the LongLivedToken input for this FinalizeOAuth choreography.
     *
     * @param bool $value (optional, boolean) Set to 1 to automatically exchange the short-lived access token for a long-lived access token. Defaults to 0 (false).
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setLongLivedToken($value)
    {
        return $this->set('LongLivedToken', $value);
    }

    /**
     * Set the value for the Timeout input for this FinalizeOAuth choreography.
     *
     * @param int $value (optional, integer) The amount of time (in seconds) to poll your Temboo callback URL to see if your app's user has allowed or denied the request for access. Defaults to 20. Max is 60.
     * @return Facebook_OAuth_FinalizeOAuth_Inputs For method chaining.
     */
    public function setTimeout($value)
    {
        return $this->set('Timeout', $value);
    }
}


/**
 * Execution object for the FinalizeOAuth choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_OAuth_FinalizeOAuth_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the FinalizeOAuth choreography.
     *
     * @param Temboo_Session $session The session that owns this FinalizeOAuth execution.
     * @param Facebook_OAuth_FinalizeOAuth $choreo The choregraphy object for this execution.
     * @param Facebook_OAuth_FinalizeOAuth_Inputs|array $inputs (optional) Inputs as Facebook_OAuth_FinalizeOAuth_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_OAuth_FinalizeOAuth_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_OAuth_FinalizeOAuth $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this FinalizeOAuth execution.
     *
     * @return Facebook_OAuth_FinalizeOAuth_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this FinalizeOAuth execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_OAuth_FinalizeOAuth_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_OAuth_FinalizeOAuth_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the FinalizeOAuth choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_OAuth_FinalizeOAuth_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the FinalizeOAuth choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_OAuth_FinalizeOAuth_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "AccessToken" output from this FinalizeOAuth execution.
     *
     * @return string (string) The access token for the user that has granted access to your application.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getAccessToken()
    {
        return $this->get('AccessToken');
    }

    /**
     * Retrieve the value for the "ErrorMessage" output from this FinalizeOAuth execution.
     *
     * @return string (string) If an error occurs during the redirect process, this output variable will contain the error message generated by Foursquare.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getErrorMessage()
    {
        return $this->get('ErrorMessage');
    }

    /**
     * Retrieve the value for the "Expires" output from this FinalizeOAuth execution.
     *
     * @return int (integer) The expiration time of the access_token retrieved.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getExpires()
    {
        return $this->get('Expires');
    }
}

/**
 * Retrieves the feed from a specified user's Wall.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_ProfileFeed extends Temboo_Choreography
{
    /**
     * Retrieves the feed from a specified user's Wall.
     *
     * @param Temboo_Session $session The session that owns this ProfileFeed choreography.
     * @return Facebook_Reading_ProfileFeed New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/ProfileFeed/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ProfileFeed choreography.
     *
     * @param Facebook_Reading_ProfileFeed_Inputs|array $inputs (optional) Inputs as Facebook_Reading_ProfileFeed_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_ProfileFeed_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_ProfileFeed_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ProfileFeed choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_ProfileFeed_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_ProfileFeed_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ProfileFeed choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_ProfileFeed_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ProfileFeed choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_ProfileFeed_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ProfileFeed input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this ProfileFeed choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this ProfileFeed choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this ProfileFeed choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this ProfileFeed choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this ProfileFeed choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve a feed for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ProfileFeed choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this ProfileFeed choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this ProfileFeed choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_ProfileFeed_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the ProfileFeed choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_ProfileFeed_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ProfileFeed choreography.
     *
     * @param Temboo_Session $session The session that owns this ProfileFeed execution.
     * @param Facebook_Reading_ProfileFeed $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_ProfileFeed_Inputs|array $inputs (optional) Inputs as Facebook_Reading_ProfileFeed_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_ProfileFeed_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_ProfileFeed $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ProfileFeed execution.
     *
     * @return Facebook_Reading_ProfileFeed_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this ProfileFeed execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_ProfileFeed_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_ProfileFeed_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ProfileFeed choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_ProfileFeed_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ProfileFeed choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_ProfileFeed_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this ProfileFeed execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this ProfileFeed execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this ProfileFeed execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of photos associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_PhotoTags extends Temboo_Choreography
{
    /**
     * Retrieves a list of photos associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this PhotoTags choreography.
     * @return Facebook_Reading_PhotoTags New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/PhotoTags/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this PhotoTags choreography.
     *
     * @param Facebook_Reading_PhotoTags_Inputs|array $inputs (optional) Inputs as Facebook_Reading_PhotoTags_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_PhotoTags_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_PhotoTags_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this PhotoTags choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_PhotoTags_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_PhotoTags_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the PhotoTags choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_PhotoTags_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the PhotoTags choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_PhotoTags_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this PhotoTags input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this PhotoTags choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this PhotoTags choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this PhotoTags choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this PhotoTags choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this PhotoTags choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve photo tags for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this PhotoTags choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this PhotoTags choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this PhotoTags choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_PhotoTags_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the PhotoTags choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_PhotoTags_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the PhotoTags choreography.
     *
     * @param Temboo_Session $session The session that owns this PhotoTags execution.
     * @param Facebook_Reading_PhotoTags $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_PhotoTags_Inputs|array $inputs (optional) Inputs as Facebook_Reading_PhotoTags_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_PhotoTags_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_PhotoTags $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this PhotoTags execution.
     *
     * @return Facebook_Reading_PhotoTags_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this PhotoTags execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_PhotoTags_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_PhotoTags_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the PhotoTags choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_PhotoTags_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the PhotoTags choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_PhotoTags_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this PhotoTags execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this PhotoTags execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this PhotoTags execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of events associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Events extends Temboo_Choreography
{
    /**
     * Retrieves a list of events associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this Events choreography.
     * @return Facebook_Reading_Events New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Events/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Events choreography.
     *
     * @param Facebook_Reading_Events_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Events_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Events_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Events_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Events choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Events_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Events_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Events choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Events_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Events choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Events_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Events input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Events choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Events choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Events choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Events choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Events choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve events for. Defaults to "me" indicating authenticated user.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Events choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Events choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Events choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Events_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Events choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Events_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Events choreography.
     *
     * @param Temboo_Session $session The session that owns this Events execution.
     * @param Facebook_Reading_Events $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Events_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Events_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Events_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Events $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Events execution.
     *
     * @return Facebook_Reading_Events_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Events execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Events_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Events_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Events choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Events_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Events choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Events_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Events execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Events execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Events execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Allows a user to "like" a Graph API object.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_Like extends Temboo_Choreography
{
    /**
     * Allows a user to "like" a Graph API object.
     *
     * @param Temboo_Session $session The session that owns this Like choreography.
     * @return Facebook_Publishing_Like New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/Like/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Like choreography.
     *
     * @param Facebook_Publishing_Like_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_Like_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_Like_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_Like_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Like choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_Like_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_Like_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Like choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_Like_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Like choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_Like_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Like input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_Like_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_Like_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Like choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_Like_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the ObjectID input for this Like choreography.
     *
     * @param string $value (required, string) The id of a graph api object to like.
     * @return Facebook_Publishing_Like_Inputs For method chaining.
     */
    public function setObjectID($value)
    {
        return $this->set('ObjectID', $value);
    }
}


/**
 * Execution object for the Like choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_Like_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Like choreography.
     *
     * @param Temboo_Session $session The session that owns this Like execution.
     * @param Facebook_Publishing_Like $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_Like_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_Like_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_Like_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_Like $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Like execution.
     *
     * @return Facebook_Publishing_Like_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Like execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_Like_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_Like_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Like choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_Like_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Like choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_Like_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Like execution.
     *
     * @return bool (boolean) The response from Facebook. Returns "true" on success.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of names and profile IDs for Facebook friends associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Friends extends Temboo_Choreography
{
    /**
     * Retrieves a list of names and profile IDs for Facebook friends associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this Friends choreography.
     * @return Facebook_Reading_Friends New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Friends/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Friends choreography.
     *
     * @param Facebook_Reading_Friends_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Friends_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Friends_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Friends_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Friends choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Friends_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Friends_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Friends choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Friends_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Friends choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Friends_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Friends input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Friends choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final OAuth step.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Friends choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Friends choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Friends choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Friends choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve a friends list for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Friends choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Friends choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Friends choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Friends_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Friends choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Friends_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Friends choreography.
     *
     * @param Temboo_Session $session The session that owns this Friends execution.
     * @param Facebook_Reading_Friends $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Friends_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Friends_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Friends_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Friends $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Friends execution.
     *
     * @return Facebook_Reading_Friends_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Friends execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Friends_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Friends_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Friends choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Friends_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Friends choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Friends_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Friends execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Friends execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Friends execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Deletes a specified status message from the authenticated user's feed.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteStatus extends Temboo_Choreography
{
    /**
     * Deletes a specified status message from the authenticated user's feed.
     *
     * @param Temboo_Session $session The session that owns this DeleteStatus choreography.
     * @return Facebook_Deleting_DeleteStatus New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Deleting/DeleteStatus/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DeleteStatus choreography.
     *
     * @param Facebook_Deleting_DeleteStatus_Inputs|array $inputs (optional) Inputs as Facebook_Deleting_DeleteStatus_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Deleting_DeleteStatus_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Deleting_DeleteStatus_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DeleteStatus choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Deleting_DeleteStatus_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Deleting_DeleteStatus_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DeleteStatus choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteStatus_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DeleteStatus choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Deleting_DeleteStatus_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DeleteStatus input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Deleting_DeleteStatus_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Deleting_DeleteStatus_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this DeleteStatus choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Deleting_DeleteStatus_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the StatusID input for this DeleteStatus choreography.
     *
     * @param string $value (required, string) The ID for the status message you want to delete.
     * @return Facebook_Deleting_DeleteStatus_Inputs For method chaining.
     */
    public function setStatusID($value)
    {
        return $this->set('StatusID', $value);
    }
}


/**
 * Execution object for the DeleteStatus choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteStatus_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DeleteStatus choreography.
     *
     * @param Temboo_Session $session The session that owns this DeleteStatus execution.
     * @param Facebook_Deleting_DeleteStatus $choreo The choregraphy object for this execution.
     * @param Facebook_Deleting_DeleteStatus_Inputs|array $inputs (optional) Inputs as Facebook_Deleting_DeleteStatus_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Deleting_DeleteStatus_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Deleting_DeleteStatus $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DeleteStatus execution.
     *
     * @return Facebook_Deleting_DeleteStatus_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this DeleteStatus execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Deleting_DeleteStatus_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Deleting_DeleteStatus_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DeleteStatus choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteStatus_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DeleteStatus choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Deleting_DeleteStatus_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this DeleteStatus execution.
     *
     * @return bool (boolean) The response from Facebook. Returns "true" on success.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Allows a user to leave a comment on a specified Graph API object.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_LeaveComment extends Temboo_Choreography
{
    /**
     * Allows a user to leave a comment on a specified Graph API object.
     *
     * @param Temboo_Session $session The session that owns this LeaveComment choreography.
     * @return Facebook_Publishing_LeaveComment New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/LeaveComment/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this LeaveComment choreography.
     *
     * @param Facebook_Publishing_LeaveComment_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_LeaveComment_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_LeaveComment_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_LeaveComment_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this LeaveComment choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_LeaveComment_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_LeaveComment_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the LeaveComment choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_LeaveComment_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the LeaveComment choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_LeaveComment_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this LeaveComment input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_LeaveComment_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_LeaveComment_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this LeaveComment choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_LeaveComment_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Message input for this LeaveComment choreography.
     *
     * @param string $value (required, string) The comment text.
     * @return Facebook_Publishing_LeaveComment_Inputs For method chaining.
     */
    public function setMessage($value)
    {
        return $this->set('Message', $value);
    }

    /**
     * Set the value for the ObjectID input for this LeaveComment choreography.
     *
     * @param string $value (required, string) The id of a graph api object to comment on.
     * @return Facebook_Publishing_LeaveComment_Inputs For method chaining.
     */
    public function setObjectID($value)
    {
        return $this->set('ObjectID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this LeaveComment choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Publishing_LeaveComment_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the LeaveComment choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_LeaveComment_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the LeaveComment choreography.
     *
     * @param Temboo_Session $session The session that owns this LeaveComment execution.
     * @param Facebook_Publishing_LeaveComment $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_LeaveComment_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_LeaveComment_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_LeaveComment_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_LeaveComment $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this LeaveComment execution.
     *
     * @return Facebook_Publishing_LeaveComment_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this LeaveComment execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_LeaveComment_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_LeaveComment_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the LeaveComment choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_LeaveComment_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the LeaveComment choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_LeaveComment_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this LeaveComment execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Generates an authorization URL that an application can use to complete the first step in the OAuth2 process.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_OAuth_InitializeOAuth extends Temboo_Choreography
{
    /**
     * Generates an authorization URL that an application can use to complete the first step in the OAuth2 process.
     *
     * @param Temboo_Session $session The session that owns this InitializeOAuth choreography.
     * @return Facebook_OAuth_InitializeOAuth New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/OAuth/InitializeOAuth/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this InitializeOAuth choreography.
     *
     * @param Facebook_OAuth_InitializeOAuth_Inputs|array $inputs (optional) Inputs as Facebook_OAuth_InitializeOAuth_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_OAuth_InitializeOAuth_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_OAuth_InitializeOAuth_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this InitializeOAuth choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_OAuth_InitializeOAuth_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_OAuth_InitializeOAuth_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the InitializeOAuth choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_OAuth_InitializeOAuth_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the InitializeOAuth choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_OAuth_InitializeOAuth_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this InitializeOAuth input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_OAuth_InitializeOAuth_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_OAuth_InitializeOAuth_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccountName input for this InitializeOAuth choreography.
     *
     * @param string $value (required, string) Your Temboo account name.
     * @return Facebook_OAuth_InitializeOAuth_Inputs For method chaining.
     */
    public function setAccountName($value)
    {
        return $this->set('AccountName', $value);
    }

    /**
     * Set the value for the AppID input for this InitializeOAuth choreography.
     *
     * @param string $value (required, string) The App ID provided by Facebook (AKA the Client ID).
     * @return Facebook_OAuth_InitializeOAuth_Inputs For method chaining.
     */
    public function setAppID($value)
    {
        return $this->set('AppID', $value);
    }

    /**
     * Set the value for the AppKeyName input for this InitializeOAuth choreography.
     *
     * @param string $value (required, string) The name of your Temboo application key.
     * @return Facebook_OAuth_InitializeOAuth_Inputs For method chaining.
     */
    public function setAppKeyName($value)
    {
        return $this->set('AppKeyName', $value);
    }

    /**
     * Set the value for the AppKeyValue input for this InitializeOAuth choreography.
     *
     * @param string $value (required, string) Your Temboo application key.
     * @return Facebook_OAuth_InitializeOAuth_Inputs For method chaining.
     */
    public function setAppKeyValue($value)
    {
        return $this->set('AppKeyValue', $value);
    }

    /**
     * Set the value for the ForwardingURL input for this InitializeOAuth choreography.
     *
     * @param string $value (optional, string) The URL that Temboo will redirect your users to after they grant your application access.
     * @return Facebook_OAuth_InitializeOAuth_Inputs For method chaining.
     */
    public function setForwardingURL($value)
    {
        return $this->set('ForwardingURL', $value);
    }

    /**
     * Set the value for the Scope input for this InitializeOAuth choreography.
     *
     * @param string $value (optional, string) A comma-separated list of permissions to request access for (i.e. user_birthday,read_stream).
     * @return Facebook_OAuth_InitializeOAuth_Inputs For method chaining.
     */
    public function setScope($value)
    {
        return $this->set('Scope', $value);
    }
}


/**
 * Execution object for the InitializeOAuth choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_OAuth_InitializeOAuth_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the InitializeOAuth choreography.
     *
     * @param Temboo_Session $session The session that owns this InitializeOAuth execution.
     * @param Facebook_OAuth_InitializeOAuth $choreo The choregraphy object for this execution.
     * @param Facebook_OAuth_InitializeOAuth_Inputs|array $inputs (optional) Inputs as Facebook_OAuth_InitializeOAuth_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_OAuth_InitializeOAuth_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_OAuth_InitializeOAuth $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this InitializeOAuth execution.
     *
     * @return Facebook_OAuth_InitializeOAuth_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this InitializeOAuth execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_OAuth_InitializeOAuth_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_OAuth_InitializeOAuth_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the InitializeOAuth choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_OAuth_InitializeOAuth_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the InitializeOAuth choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_OAuth_InitializeOAuth_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "AuthorizationURL" output from this InitializeOAuth execution.
     *
     * @return string (string) The authorization URL to send your user to in order for them to grant access to your application.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getAuthorizationURL()
    {
        return $this->get('AuthorizationURL');
    }

    /**
     * Retrieve the value for the "CallbackID" output from this InitializeOAuth execution.
     *
     * @return string (string) An ID used to retrieve the callback data that Temboo stores once your application's user authorizes.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getCallbackID()
    {
        return $this->get('CallbackID');
    }
}

/**
 * Retrieves movies associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Movies extends Temboo_Choreography
{
    /**
     * Retrieves movies associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this Movies choreography.
     * @return Facebook_Reading_Movies New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Movies/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Movies choreography.
     *
     * @param Facebook_Reading_Movies_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Movies_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Movies_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Movies_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Movies choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Movies_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Movies_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Movies choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Movies_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Movies choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Movies_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Movies input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Movies choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Movies choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Movies choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Movies choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Movies choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve movies for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Movies choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Movies choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Movies choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Movies_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Movies choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Movies_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Movies choreography.
     *
     * @param Temboo_Session $session The session that owns this Movies execution.
     * @param Facebook_Reading_Movies $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Movies_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Movies_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Movies_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Movies $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Movies execution.
     *
     * @return Facebook_Reading_Movies_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Movies execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Movies_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Movies_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Movies choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Movies_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Movies choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Movies_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Movies execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Movies execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Movies execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Publishes a note on a given profile.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_PublishNote extends Temboo_Choreography
{
    /**
     * Publishes a note on a given profile.
     *
     * @param Temboo_Session $session The session that owns this PublishNote choreography.
     * @return Facebook_Publishing_PublishNote New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/PublishNote/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this PublishNote choreography.
     *
     * @param Facebook_Publishing_PublishNote_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_PublishNote_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_PublishNote_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_PublishNote_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this PublishNote choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_PublishNote_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_PublishNote_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the PublishNote choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_PublishNote_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the PublishNote choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_PublishNote_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this PublishNote input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_PublishNote_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_PublishNote_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this PublishNote choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_PublishNote_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Message input for this PublishNote choreography.
     *
     * @param string $value (required, string) The contents of the note.
     * @return Facebook_Publishing_PublishNote_Inputs For method chaining.
     */
    public function setMessage($value)
    {
        return $this->set('Message', $value);
    }

    /**
     * Set the value for the ProfileID input for this PublishNote choreography.
     *
     * @param string $value (optional, string) The id of the profile that the note will be published to. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Publishing_PublishNote_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this PublishNote choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Publishing_PublishNote_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Subject input for this PublishNote choreography.
     *
     * @param string $value (required, string) A subject line for the note being created.
     * @return Facebook_Publishing_PublishNote_Inputs For method chaining.
     */
    public function setSubject($value)
    {
        return $this->set('Subject', $value);
    }
}


/**
 * Execution object for the PublishNote choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_PublishNote_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the PublishNote choreography.
     *
     * @param Temboo_Session $session The session that owns this PublishNote execution.
     * @param Facebook_Publishing_PublishNote $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_PublishNote_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_PublishNote_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_PublishNote_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_PublishNote $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this PublishNote execution.
     *
     * @return Facebook_Publishing_PublishNote_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this PublishNote execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_PublishNote_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_PublishNote_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the PublishNote choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_PublishNote_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the PublishNote choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_PublishNote_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this PublishNote execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Allows a user to "unlike" a Graph API object.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_Unlike extends Temboo_Choreography
{
    /**
     * Allows a user to "unlike" a Graph API object.
     *
     * @param Temboo_Session $session The session that owns this Unlike choreography.
     * @return Facebook_Deleting_Unlike New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Deleting/Unlike/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Unlike choreography.
     *
     * @param Facebook_Deleting_Unlike_Inputs|array $inputs (optional) Inputs as Facebook_Deleting_Unlike_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Deleting_Unlike_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Deleting_Unlike_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Unlike choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Deleting_Unlike_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Deleting_Unlike_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Unlike choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_Unlike_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Unlike choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Deleting_Unlike_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Unlike input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Deleting_Unlike_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Deleting_Unlike_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Unlike choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Deleting_Unlike_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the ObjectID input for this Unlike choreography.
     *
     * @param string $value (required, string) The id of a graph api object to unlike.
     * @return Facebook_Deleting_Unlike_Inputs For method chaining.
     */
    public function setObjectID($value)
    {
        return $this->set('ObjectID', $value);
    }
}


/**
 * Execution object for the Unlike choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_Unlike_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Unlike choreography.
     *
     * @param Temboo_Session $session The session that owns this Unlike execution.
     * @param Facebook_Deleting_Unlike $choreo The choregraphy object for this execution.
     * @param Facebook_Deleting_Unlike_Inputs|array $inputs (optional) Inputs as Facebook_Deleting_Unlike_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Deleting_Unlike_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Deleting_Unlike $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Unlike execution.
     *
     * @return Facebook_Deleting_Unlike_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Unlike execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Deleting_Unlike_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Deleting_Unlike_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Unlike choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_Unlike_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Unlike choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Deleting_Unlike_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Unlike execution.
     *
     * @return bool (boolean) The response from Facebook. Returns "true" on success.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of objects that have a location associated with them.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_ObjectsWithLocation extends Temboo_Choreography
{
    /**
     * Retrieves a list of objects that have a location associated with them.
     *
     * @param Temboo_Session $session The session that owns this ObjectsWithLocation choreography.
     * @return Facebook_Reading_ObjectsWithLocation New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/ObjectsWithLocation/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ObjectsWithLocation choreography.
     *
     * @param Facebook_Reading_ObjectsWithLocation_Inputs|array $inputs (optional) Inputs as Facebook_Reading_ObjectsWithLocation_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_ObjectsWithLocation_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_ObjectsWithLocation_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ObjectsWithLocation choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_ObjectsWithLocation_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ObjectsWithLocation choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_ObjectsWithLocation_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ObjectsWithLocation choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ObjectsWithLocation input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this ObjectsWithLocation choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this ObjectsWithLocation choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this ObjectsWithLocation choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this ObjectsWithLocation choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this ObjectsWithLocation choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve results for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ObjectsWithLocation choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this ObjectsWithLocation choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this ObjectsWithLocation choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_ObjectsWithLocation_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the ObjectsWithLocation choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_ObjectsWithLocation_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ObjectsWithLocation choreography.
     *
     * @param Temboo_Session $session The session that owns this ObjectsWithLocation execution.
     * @param Facebook_Reading_ObjectsWithLocation $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_ObjectsWithLocation_Inputs|array $inputs (optional) Inputs as Facebook_Reading_ObjectsWithLocation_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_ObjectsWithLocation_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_ObjectsWithLocation $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ObjectsWithLocation execution.
     *
     * @return Facebook_Reading_ObjectsWithLocation_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this ObjectsWithLocation execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_ObjectsWithLocation_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_ObjectsWithLocation_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ObjectsWithLocation choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_ObjectsWithLocation_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ObjectsWithLocation choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_ObjectsWithLocation_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this ObjectsWithLocation execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this ObjectsWithLocation execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this ObjectsWithLocation execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Creates an event.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateEvent extends Temboo_Choreography
{
    /**
     * Creates an event.
     *
     * @param Temboo_Session $session The session that owns this CreateEvent choreography.
     * @return Facebook_Publishing_CreateEvent New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/CreateEvent/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateEvent choreography.
     *
     * @param Facebook_Publishing_CreateEvent_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_CreateEvent_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_CreateEvent_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_CreateEvent_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateEvent choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_CreateEvent_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_CreateEvent_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateEvent choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateEvent_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateEvent choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_CreateEvent_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateEvent input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_CreateEvent_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_CreateEvent_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this CreateEvent choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_CreateEvent_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the EndTime input for this CreateEvent choreography.
     *
     * @param string $value (optional, date) The end time of the event formatted as a ISO-8601 string (i.e 2012-07-04 or 2012-07-04T19:00:00-0700).
     * @return Facebook_Publishing_CreateEvent_Inputs For method chaining.
     */
    public function setEndTime($value)
    {
        return $this->set('EndTime', $value);
    }

    /**
     * Set the value for the Name input for this CreateEvent choreography.
     *
     * @param string $value (required, string) The name of the event.
     * @return Facebook_Publishing_CreateEvent_Inputs For method chaining.
     */
    public function setName($value)
    {
        return $this->set('Name', $value);
    }

    /**
     * Set the value for the ProfileID input for this CreateEvent choreography.
     *
     * @param string $value (optional, string) The id of the profile that the event will be created for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Publishing_CreateEvent_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this CreateEvent choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Publishing_CreateEvent_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the StartTime input for this CreateEvent choreography.
     *
     * @param string $value (required, date) The start time of the event formatted as a ISO-8601 string (i.e 2012-07-04 or 2012-07-04T19:00:00-0700).
     * @return Facebook_Publishing_CreateEvent_Inputs For method chaining.
     */
    public function setStartTime($value)
    {
        return $this->set('StartTime', $value);
    }
}


/**
 * Execution object for the CreateEvent choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateEvent_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateEvent choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateEvent execution.
     * @param Facebook_Publishing_CreateEvent $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_CreateEvent_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_CreateEvent_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_CreateEvent_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_CreateEvent $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateEvent execution.
     *
     * @return Facebook_Publishing_CreateEvent_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this CreateEvent execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_CreateEvent_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_CreateEvent_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateEvent choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateEvent_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateEvent choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_CreateEvent_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateEvent execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves retrieves the details for a Graph API object that you specify.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_GetObject extends Temboo_Choreography
{
    /**
     * Retrieves retrieves the details for a Graph API object that you specify.
     *
     * @param Temboo_Session $session The session that owns this GetObject choreography.
     * @return Facebook_Reading_GetObject New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/GetObject/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this GetObject choreography.
     *
     * @param Facebook_Reading_GetObject_Inputs|array $inputs (optional) Inputs as Facebook_Reading_GetObject_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_GetObject_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_GetObject_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this GetObject choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_GetObject_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_GetObject_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the GetObject choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_GetObject_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the GetObject choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_GetObject_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this GetObject input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_GetObject_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_GetObject_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this GetObject choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_GetObject_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this GetObject choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_GetObject_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the ObjectID input for this GetObject choreography.
     *
     * @param string $value (required, string) The id of a graph api object to retrieve.
     * @return Facebook_Reading_GetObject_Inputs For method chaining.
     */
    public function setObjectID($value)
    {
        return $this->set('ObjectID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this GetObject choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_GetObject_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the GetObject choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_GetObject_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the GetObject choreography.
     *
     * @param Temboo_Session $session The session that owns this GetObject execution.
     * @param Facebook_Reading_GetObject $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_GetObject_Inputs|array $inputs (optional) Inputs as Facebook_Reading_GetObject_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_GetObject_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_GetObject $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this GetObject execution.
     *
     * @return Facebook_Reading_GetObject_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this GetObject execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_GetObject_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_GetObject_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the GetObject choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_GetObject_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the GetObject choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_GetObject_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this GetObject execution.
     *
     * @return bool (boolean) The response from Facebook. Returns "true" on success.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves comments for a specified Graph API object.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Comments extends Temboo_Choreography
{
    /**
     * Retrieves comments for a specified Graph API object.
     *
     * @param Temboo_Session $session The session that owns this Comments choreography.
     * @return Facebook_Reading_Comments New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Comments/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Comments choreography.
     *
     * @param Facebook_Reading_Comments_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Comments_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Comments_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Comments_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Comments choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Comments_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Comments_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Comments choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Comments_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Comments choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Comments_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Comments input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Comments choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Comments choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limt input for this Comments choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setLimt($value)
    {
        return $this->set('Limt', $value);
    }

    /**
     * Set the value for the ObjectID input for this Comments choreography.
     *
     * @param string $value (required, string) The id of a graph api object to get comments for.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setObjectID($value)
    {
        return $this->set('ObjectID', $value);
    }

    /**
     * Set the value for the Offset input for this Comments choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Comments choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Comments choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Comments choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Comments_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Comments choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Comments_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Comments choreography.
     *
     * @param Temboo_Session $session The session that owns this Comments execution.
     * @param Facebook_Reading_Comments $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Comments_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Comments_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Comments_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Comments $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Comments execution.
     *
     * @return Facebook_Reading_Comments_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Comments execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Comments_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Comments_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Comments choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Comments_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Comments choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Comments_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Comments execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Comments execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Comments execution.
     *
     * @return bool (boolean) The response from Facebook. Returns "true" on success.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of groups associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Groups extends Temboo_Choreography
{
    /**
     * Retrieves a list of groups associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this Groups choreography.
     * @return Facebook_Reading_Groups New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Groups/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Groups choreography.
     *
     * @param Facebook_Reading_Groups_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Groups_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Groups_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Groups_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Groups choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Groups_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Groups_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Groups choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Groups_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Groups choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Groups_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Groups input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Groups choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Groups choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Groups choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Groups choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Groups choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve groups for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Groups choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Groups choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Groups choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Groups_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Groups choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Groups_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Groups choreography.
     *
     * @param Temboo_Session $session The session that owns this Groups execution.
     * @param Facebook_Reading_Groups $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Groups_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Groups_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Groups_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Groups $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Groups execution.
     *
     * @return Facebook_Reading_Groups_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Groups execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Groups_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Groups_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Groups choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Groups_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Groups choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Groups_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Groups execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Groups execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Groups execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Creates a checkin at a location represented by a Page.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateCheckin extends Temboo_Choreography
{
    /**
     * Creates a checkin at a location represented by a Page.
     *
     * @param Temboo_Session $session The session that owns this CreateCheckin choreography.
     * @return Facebook_Publishing_CreateCheckin New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/CreateCheckin/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateCheckin choreography.
     *
     * @param Facebook_Publishing_CreateCheckin_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_CreateCheckin_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_CreateCheckin_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_CreateCheckin_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateCheckin choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_CreateCheckin_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_CreateCheckin_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateCheckin choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateCheckin_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateCheckin choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_CreateCheckin_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateCheckin input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this CreateCheckin choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Latitude input for this CreateCheckin choreography.
     *
     * @param float $value (conditional, decimal) The latitude coordinate of the Checkin location.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function setLatitude($value)
    {
        return $this->set('Latitude', $value);
    }

    /**
     * Set the value for the Longitude input for this CreateCheckin choreography.
     *
     * @param float $value (conditional, decimal) The longitude coordinate of the Checkin location.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function setLongitude($value)
    {
        return $this->set('Longitude', $value);
    }

    /**
     * Set the value for the Message input for this CreateCheckin choreography.
     *
     * @param string $value (optional, string) A message to include with the Checkin.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function setMessage($value)
    {
        return $this->set('Message', $value);
    }

    /**
     * Set the value for the PlaceID input for this CreateCheckin choreography.
     *
     * @param string $value (conditional, string) The ID of the place associated with your Checkin.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function setPlaceID($value)
    {
        return $this->set('PlaceID', $value);
    }

    /**
     * Set the value for the ProfileID input for this CreateCheckin choreography.
     *
     * @param string $value (optional, string) The id of the profile to create a checkin for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this CreateCheckin choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Publishing_CreateCheckin_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the CreateCheckin choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateCheckin_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateCheckin choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateCheckin execution.
     * @param Facebook_Publishing_CreateCheckin $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_CreateCheckin_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_CreateCheckin_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_CreateCheckin_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_CreateCheckin $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateCheckin execution.
     *
     * @return Facebook_Publishing_CreateCheckin_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this CreateCheckin execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_CreateCheckin_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_CreateCheckin_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateCheckin choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateCheckin_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateCheckin choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_CreateCheckin_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateCheckin execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of uploaded videos associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_VideoUploads extends Temboo_Choreography
{
    /**
     * Retrieves a list of uploaded videos associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this VideoUploads choreography.
     * @return Facebook_Reading_VideoUploads New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/VideoUploads/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this VideoUploads choreography.
     *
     * @param Facebook_Reading_VideoUploads_Inputs|array $inputs (optional) Inputs as Facebook_Reading_VideoUploads_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_VideoUploads_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_VideoUploads_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this VideoUploads choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_VideoUploads_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_VideoUploads_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the VideoUploads choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_VideoUploads_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the VideoUploads choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_VideoUploads_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this VideoUploads input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this VideoUploads choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this VideoUploads choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this VideoUploads choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this VideoUploads choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this VideoUploads choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve a list of video uploads for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this VideoUploads choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this VideoUploads choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this VideoUploads choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_VideoUploads_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the VideoUploads choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_VideoUploads_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the VideoUploads choreography.
     *
     * @param Temboo_Session $session The session that owns this VideoUploads execution.
     * @param Facebook_Reading_VideoUploads $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_VideoUploads_Inputs|array $inputs (optional) Inputs as Facebook_Reading_VideoUploads_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_VideoUploads_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_VideoUploads $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this VideoUploads execution.
     *
     * @return Facebook_Reading_VideoUploads_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this VideoUploads execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_VideoUploads_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_VideoUploads_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the VideoUploads choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_VideoUploads_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the VideoUploads choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_VideoUploads_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this VideoUploads execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this VideoUploads execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this VideoUploads execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of video tags associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_VideoTags extends Temboo_Choreography
{
    /**
     * Retrieves a list of video tags associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this VideoTags choreography.
     * @return Facebook_Reading_VideoTags New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/VideoTags/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this VideoTags choreography.
     *
     * @param Facebook_Reading_VideoTags_Inputs|array $inputs (optional) Inputs as Facebook_Reading_VideoTags_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_VideoTags_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_VideoTags_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this VideoTags choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_VideoTags_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_VideoTags_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the VideoTags choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_VideoTags_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the VideoTags choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_VideoTags_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this VideoTags input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this VideoTags choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this VideoTags choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this VideoTags choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this VideoTags choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this VideoTags choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve video tags for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this VideoTags choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this VideoTags choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this VideoTags choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_VideoTags_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the VideoTags choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_VideoTags_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the VideoTags choreography.
     *
     * @param Temboo_Session $session The session that owns this VideoTags execution.
     * @param Facebook_Reading_VideoTags $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_VideoTags_Inputs|array $inputs (optional) Inputs as Facebook_Reading_VideoTags_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_VideoTags_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_VideoTags $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this VideoTags execution.
     *
     * @return Facebook_Reading_VideoTags_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this VideoTags execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_VideoTags_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_VideoTags_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the VideoTags choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_VideoTags_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the VideoTags choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_VideoTags_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this VideoTags execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this VideoTags execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this VideoTags execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of checkins associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Checkins extends Temboo_Choreography
{
    /**
     * Retrieves a list of checkins associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this Checkins choreography.
     * @return Facebook_Reading_Checkins New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Checkins/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Checkins choreography.
     *
     * @param Facebook_Reading_Checkins_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Checkins_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Checkins_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Checkins_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Checkins choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Checkins_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Checkins_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Checkins choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Checkins_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Checkins choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Checkins_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Checkins input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Checkins choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Checkins choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Checkins choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Checkins choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Checkins choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve checkins for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Checkins choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Checkins choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Checkins choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Checkins_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Checkins choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Checkins_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Checkins choreography.
     *
     * @param Temboo_Session $session The session that owns this Checkins execution.
     * @param Facebook_Reading_Checkins $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Checkins_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Checkins_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Checkins_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Checkins $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Checkins execution.
     *
     * @return Facebook_Reading_Checkins_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Checkins execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Checkins_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Checkins_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Checkins choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Checkins_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Checkins choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Checkins_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Checkins execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Checkins execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Checkins execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Publishes a link on a given profile.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_PublishLink extends Temboo_Choreography
{
    /**
     * Publishes a link on a given profile.
     *
     * @param Temboo_Session $session The session that owns this PublishLink choreography.
     * @return Facebook_Publishing_PublishLink New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/PublishLink/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this PublishLink choreography.
     *
     * @param Facebook_Publishing_PublishLink_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_PublishLink_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_PublishLink_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_PublishLink_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this PublishLink choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_PublishLink_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_PublishLink_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the PublishLink choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_PublishLink_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the PublishLink choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_PublishLink_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this PublishLink input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this PublishLink choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Caption input for this PublishLink choreography.
     *
     * @param string $value (optional, string) A caption for the link being published.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setCaption($value)
    {
        return $this->set('Caption', $value);
    }

    /**
     * Set the value for the Description input for this PublishLink choreography.
     *
     * @param string $value (optional, string) A description of the link being published.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setDescription($value)
    {
        return $this->set('Description', $value);
    }

    /**
     * Set the value for the Link input for this PublishLink choreography.
     *
     * @param string $value (required, string) The link to publish.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setLink($value)
    {
        return $this->set('Link', $value);
    }

    /**
     * Set the value for the Message input for this PublishLink choreography.
     *
     * @param string $value (optional, string) A message about the link.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setMessage($value)
    {
        return $this->set('Message', $value);
    }

    /**
     * Set the value for the Name input for this PublishLink choreography.
     *
     * @param string $value (optional, string) The name of the link.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setName($value)
    {
        return $this->set('Name', $value);
    }

    /**
     * Set the value for the Picture input for this PublishLink choreography.
     *
     * @param string $value (optional, string) A URL to the thumbnail image to use for the link post.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setPicture($value)
    {
        return $this->set('Picture', $value);
    }

    /**
     * Set the value for the ProfileID input for this PublishLink choreography.
     *
     * @param string $value (optional, string) The id of the profile that the link will be published to. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this PublishLink choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Publishing_PublishLink_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the PublishLink choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_PublishLink_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the PublishLink choreography.
     *
     * @param Temboo_Session $session The session that owns this PublishLink execution.
     * @param Facebook_Publishing_PublishLink $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_PublishLink_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_PublishLink_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_PublishLink_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_PublishLink $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this PublishLink execution.
     *
     * @return Facebook_Publishing_PublishLink_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this PublishLink execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_PublishLink_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_PublishLink_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the PublishLink choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_PublishLink_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the PublishLink choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_PublishLink_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this PublishLink execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Creates an album.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateAlbum extends Temboo_Choreography
{
    /**
     * Creates an album.
     *
     * @param Temboo_Session $session The session that owns this CreateAlbum choreography.
     * @return Facebook_Publishing_CreateAlbum New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/CreateAlbum/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateAlbum choreography.
     *
     * @param Facebook_Publishing_CreateAlbum_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_CreateAlbum_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_CreateAlbum_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_CreateAlbum_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateAlbum choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_CreateAlbum_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_CreateAlbum_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateAlbum choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateAlbum_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateAlbum choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_CreateAlbum_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateAlbum input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_CreateAlbum_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_CreateAlbum_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this CreateAlbum choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_CreateAlbum_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Message input for this CreateAlbum choreography.
     *
     * @param string $value (optional, string) A message to attach to the album.
     * @return Facebook_Publishing_CreateAlbum_Inputs For method chaining.
     */
    public function setMessage($value)
    {
        return $this->set('Message', $value);
    }

    /**
     * Set the value for the Name input for this CreateAlbum choreography.
     *
     * @param string $value (required, string) The name of the album.
     * @return Facebook_Publishing_CreateAlbum_Inputs For method chaining.
     */
    public function setName($value)
    {
        return $this->set('Name', $value);
    }

    /**
     * Set the value for the ProfileID input for this CreateAlbum choreography.
     *
     * @param string $value (optional, string) The id for the profile that the album will be published to. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Publishing_CreateAlbum_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this CreateAlbum choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Publishing_CreateAlbum_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the CreateAlbum choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateAlbum_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateAlbum choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateAlbum execution.
     * @param Facebook_Publishing_CreateAlbum $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_CreateAlbum_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_CreateAlbum_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_CreateAlbum_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_CreateAlbum $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateAlbum execution.
     *
     * @return Facebook_Publishing_CreateAlbum_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this CreateAlbum execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_CreateAlbum_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_CreateAlbum_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateAlbum choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_CreateAlbum_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateAlbum choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_CreateAlbum_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateAlbum execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Allows you to submit multiple FQL statements and retrieve all the results you need in one request.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_MultiQuery extends Temboo_Choreography
{
    /**
     * Allows you to submit multiple FQL statements and retrieve all the results you need in one request.
     *
     * @param Temboo_Session $session The session that owns this MultiQuery choreography.
     * @return Facebook_Searching_MultiQuery New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Searching/MultiQuery/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this MultiQuery choreography.
     *
     * @param Facebook_Searching_MultiQuery_Inputs|array $inputs (optional) Inputs as Facebook_Searching_MultiQuery_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Searching_MultiQuery_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Searching_MultiQuery_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this MultiQuery choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Searching_MultiQuery_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Searching_MultiQuery_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the MultiQuery choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_MultiQuery_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the MultiQuery choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Searching_MultiQuery_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this MultiQuery input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Searching_MultiQuery_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Searching_MultiQuery_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this MultiQuery choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Searching_MultiQuery_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Queries input for this MultiQuery choreography.
     *
     * @param string $value (required, json) A JSON dictionary containing multiple queries to execute. See documentation for formatting examples.
     * @return Facebook_Searching_MultiQuery_Inputs For method chaining.
     */
    public function setQueries($value)
    {
        return $this->set('Queries', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this MultiQuery choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Searching_MultiQuery_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the MultiQuery choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_MultiQuery_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the MultiQuery choreography.
     *
     * @param Temboo_Session $session The session that owns this MultiQuery execution.
     * @param Facebook_Searching_MultiQuery $choreo The choregraphy object for this execution.
     * @param Facebook_Searching_MultiQuery_Inputs|array $inputs (optional) Inputs as Facebook_Searching_MultiQuery_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Searching_MultiQuery_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Searching_MultiQuery $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this MultiQuery execution.
     *
     * @return Facebook_Searching_MultiQuery_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this MultiQuery execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Searching_MultiQuery_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Searching_MultiQuery_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the MultiQuery choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_MultiQuery_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the MultiQuery choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Searching_MultiQuery_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this MultiQuery execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Uploads a photo to a given album.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_UploadPhoto extends Temboo_Choreography
{
    /**
     * Uploads a photo to a given album.
     *
     * @param Temboo_Session $session The session that owns this UploadPhoto choreography.
     * @return Facebook_Publishing_UploadPhoto New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/UploadPhoto/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UploadPhoto choreography.
     *
     * @param Facebook_Publishing_UploadPhoto_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_UploadPhoto_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_UploadPhoto_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_UploadPhoto_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UploadPhoto choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_UploadPhoto_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_UploadPhoto_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UploadPhoto choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_UploadPhoto_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UploadPhoto choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_UploadPhoto_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UploadPhoto input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this UploadPhoto choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the AlbumID input for this UploadPhoto choreography.
     *
     * @param string $value (required, string) The id of the album to upload the photo to.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function setAlbumID($value)
    {
        return $this->set('AlbumID', $value);
    }

    /**
     * Set the value for the Message input for this UploadPhoto choreography.
     *
     * @param string $value (optional, string) A message to attach to the photo.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function setMessage($value)
    {
        return $this->set('Message', $value);
    }

    /**
     * Set the value for the Photo input for this UploadPhoto choreography.
     *
     * @param mixed $value (conditional, any) The image contents formatted as a Base64 encoded string.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function setPhoto($value)
    {
        return $this->set('Photo', $value);
    }

    /**
     * Set the value for the Place input for this UploadPhoto choreography.
     *
     * @param string $value (optional, string) A location associated with a Photo.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function setPlace($value)
    {
        return $this->set('Place', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this UploadPhoto choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Source input for this UploadPhoto choreography.
     *
     * @param string $value (optional, string) A link to the source image of the photo.
     * @return Facebook_Publishing_UploadPhoto_Inputs For method chaining.
     */
    public function setSource($value)
    {
        return $this->set('Source', $value);
    }

}


/**
 * Execution object for the UploadPhoto choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_UploadPhoto_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UploadPhoto choreography.
     *
     * @param Temboo_Session $session The session that owns this UploadPhoto execution.
     * @param Facebook_Publishing_UploadPhoto $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_UploadPhoto_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_UploadPhoto_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_UploadPhoto_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_UploadPhoto $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UploadPhoto execution.
     *
     * @return Facebook_Publishing_UploadPhoto_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this UploadPhoto execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_UploadPhoto_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_UploadPhoto_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UploadPhoto choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_UploadPhoto_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UploadPhoto choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_UploadPhoto_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UploadPhoto execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves information about the specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_User extends Temboo_Choreography
{
    /**
     * Retrieves information about the specified user.
     *
     * @param Temboo_Session $session The session that owns this User choreography.
     * @return Facebook_Reading_User New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/User/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this User choreography.
     *
     * @param Facebook_Reading_User_Inputs|array $inputs (optional) Inputs as Facebook_Reading_User_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_User_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_User_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this User choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_User_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_User_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the User choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_User_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the User choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_User_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this User input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_User_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_User_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this User choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_User_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this User choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_User_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this User choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_User_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the User choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_User_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the User choreography.
     *
     * @param Temboo_Session $session The session that owns this User execution.
     * @param Facebook_Reading_User $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_User_Inputs|array $inputs (optional) Inputs as Facebook_Reading_User_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_User_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_User $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this User execution.
     *
     * @return Facebook_Reading_User_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this User execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_User_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_User_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the User choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_User_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the User choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_User_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this User execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Search public objects across the social graph.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_Search extends Temboo_Choreography
{
    /**
     * Search public objects across the social graph.
     *
     * @param Temboo_Session $session The session that owns this Search choreography.
     * @return Facebook_Searching_Search New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Searching/Search/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Search choreography.
     *
     * @param Facebook_Searching_Search_Inputs|array $inputs (optional) Inputs as Facebook_Searching_Search_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Searching_Search_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Searching_Search_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Search choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Searching_Search_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Searching_Search_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Search choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_Search_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Search choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Searching_Search_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Search input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Search choreography.
     *
     * @param string $value (conditional, string) The access token retrieved from the final step of the OAuth process. This is required for certain object types such as "user".
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Center input for this Search choreography.
     *
     * @param string $value (conditional, string) The coordinates for a place (such as 37.76,122.427). Used only when specifying an object type of "place".
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setCenter($value)
    {
        return $this->set('Center', $value);
    }

    /**
     * Set the value for the Distance input for this Search choreography.
     *
     * @param int $value (optional, integer) The distance search parameter used only when specifying an object type of "place". Defaults to 1000.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setDistance($value)
    {
        return $this->set('Distance', $value);
    }

    /**
     * Set the value for the Fields input for this Search choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Search choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the ObjectType input for this Search choreography.
     *
     * @param string $value (required, string) The type of object to search for such as: post, user, page, event, group, place, location, or checkin.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setObjectType($value)
    {
        return $this->set('ObjectType', $value);
    }

    /**
     * Set the value for the Offset input for this Search choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the Query input for this Search choreography.
     *
     * @param string $value (conditional, string) The Facebook query term to send in the request.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Search choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Search choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Search choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Searching_Search_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Search choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_Search_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Search choreography.
     *
     * @param Temboo_Session $session The session that owns this Search execution.
     * @param Facebook_Searching_Search $choreo The choregraphy object for this execution.
     * @param Facebook_Searching_Search_Inputs|array $inputs (optional) Inputs as Facebook_Searching_Search_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Searching_Search_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Searching_Search $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Search execution.
     *
     * @return Facebook_Searching_Search_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Search execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Searching_Search_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Searching_Search_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Search choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Searching_Search_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Search choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Searching_Search_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Search execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Search execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Search execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves the set of permissions associated with a given access token.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Permissions extends Temboo_Choreography
{
    /**
     * Retrieves the set of permissions associated with a given access token.
     *
     * @param Temboo_Session $session The session that owns this Permissions choreography.
     * @return Facebook_Reading_Permissions New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Permissions/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Permissions choreography.
     *
     * @param Facebook_Reading_Permissions_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Permissions_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Permissions_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Permissions_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Permissions choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Permissions_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Permissions_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Permissions choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Permissions_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Permissions choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Permissions_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Permissions input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Permissions_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Permissions_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Permissions choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Permissions_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the ProfileID input for this Permissions choreography.
     *
     * @param string $value (optional, string) The id of the profile to access. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Permissions_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Permissions choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Permissions_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the Permissions choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Permissions_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Permissions choreography.
     *
     * @param Temboo_Session $session The session that owns this Permissions execution.
     * @param Facebook_Reading_Permissions $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Permissions_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Permissions_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Permissions_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Permissions $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Permissions execution.
     *
     * @return Facebook_Reading_Permissions_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Permissions execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Permissions_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Permissions_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Permissions choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Permissions_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Permissions choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Permissions_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Permissions execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves the current news feed associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_NewsFeed extends Temboo_Choreography
{
    /**
     * Retrieves the current news feed associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this NewsFeed choreography.
     * @return Facebook_Reading_NewsFeed New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/NewsFeed/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this NewsFeed choreography.
     *
     * @param Facebook_Reading_NewsFeed_Inputs|array $inputs (optional) Inputs as Facebook_Reading_NewsFeed_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_NewsFeed_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_NewsFeed_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this NewsFeed choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_NewsFeed_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_NewsFeed_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the NewsFeed choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_NewsFeed_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the NewsFeed choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_NewsFeed_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this NewsFeed input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this NewsFeed choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this NewsFeed choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this NewsFeed choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this NewsFeed choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this NewsFeed choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve a feed for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this NewsFeed choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this NewsFeed choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this NewsFeed choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_NewsFeed_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the NewsFeed choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_NewsFeed_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the NewsFeed choreography.
     *
     * @param Temboo_Session $session The session that owns this NewsFeed execution.
     * @param Facebook_Reading_NewsFeed $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_NewsFeed_Inputs|array $inputs (optional) Inputs as Facebook_Reading_NewsFeed_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_NewsFeed_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_NewsFeed $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this NewsFeed execution.
     *
     * @return Facebook_Reading_NewsFeed_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this NewsFeed execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_NewsFeed_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_NewsFeed_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the NewsFeed choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_NewsFeed_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the NewsFeed choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_NewsFeed_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this NewsFeed execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this NewsFeed execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this NewsFeed execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of messages in a specified user's outbox.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Outbox extends Temboo_Choreography
{
    /**
     * Retrieves a list of messages in a specified user's outbox.
     *
     * @param Temboo_Session $session The session that owns this Outbox choreography.
     * @return Facebook_Reading_Outbox New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Outbox/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Outbox choreography.
     *
     * @param Facebook_Reading_Outbox_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Outbox_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Outbox_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Outbox_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Outbox choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Outbox_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Outbox_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Outbox choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Outbox_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Outbox choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Outbox_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Outbox input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Outbox_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Outbox_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Outbox choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Outbox_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Outbox choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Outbox_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the ProfileID input for this Outbox choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve outgoing messages for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Outbox_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Outbox choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Outbox_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the Outbox choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Outbox_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Outbox choreography.
     *
     * @param Temboo_Session $session The session that owns this Outbox execution.
     * @param Facebook_Reading_Outbox $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Outbox_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Outbox_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Outbox_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Outbox $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Outbox execution.
     *
     * @return Facebook_Reading_Outbox_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Outbox execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Outbox_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Outbox_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Outbox choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Outbox_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Outbox choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Outbox_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Outbox execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Deletes objects in the graph with a given id or path.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteObject extends Temboo_Choreography
{
    /**
     * Deletes objects in the graph with a given id or path.
     *
     * @param Temboo_Session $session The session that owns this DeleteObject choreography.
     * @return Facebook_Deleting_DeleteObject New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Deleting/DeleteObject/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DeleteObject choreography.
     *
     * @param Facebook_Deleting_DeleteObject_Inputs|array $inputs (optional) Inputs as Facebook_Deleting_DeleteObject_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Deleting_DeleteObject_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Deleting_DeleteObject_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DeleteObject choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Deleting_DeleteObject_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Deleting_DeleteObject_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DeleteObject choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteObject_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DeleteObject choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Deleting_DeleteObject_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DeleteObject input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Deleting_DeleteObject_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Deleting_DeleteObject_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this DeleteObject choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Deleting_DeleteObject_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the ObjectID input for this DeleteObject choreography.
     *
     * @param string $value (required, string) The id or path to an object to delete.
     * @return Facebook_Deleting_DeleteObject_Inputs For method chaining.
     */
    public function setObjectID($value)
    {
        return $this->set('ObjectID', $value);
    }
}


/**
 * Execution object for the DeleteObject choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteObject_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DeleteObject choreography.
     *
     * @param Temboo_Session $session The session that owns this DeleteObject execution.
     * @param Facebook_Deleting_DeleteObject $choreo The choregraphy object for this execution.
     * @param Facebook_Deleting_DeleteObject_Inputs|array $inputs (optional) Inputs as Facebook_Deleting_DeleteObject_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Deleting_DeleteObject_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Deleting_DeleteObject $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DeleteObject execution.
     *
     * @return Facebook_Deleting_DeleteObject_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this DeleteObject execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Deleting_DeleteObject_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Deleting_DeleteObject_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DeleteObject choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteObject_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DeleteObject choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Deleting_DeleteObject_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this DeleteObject execution.
     *
     * @return bool (boolean) The response from Facebook. Returns "true" on success.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves music associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Music extends Temboo_Choreography
{
    /**
     * Retrieves music associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this Music choreography.
     * @return Facebook_Reading_Music New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Music/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Music choreography.
     *
     * @param Facebook_Reading_Music_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Music_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Music_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Music_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Music choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Music_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Music_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Music choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Music_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Music choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Music_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Music input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Music choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Music choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Music choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Music choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Music choreography.
     *
     * @param string $value (optional, string) The id of the profile to retireve music for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Music choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Music choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Music choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Music_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Music choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Music_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Music choreography.
     *
     * @param Temboo_Session $session The session that owns this Music execution.
     * @param Facebook_Reading_Music $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Music_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Music_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Music_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Music $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Music execution.
     *
     * @return Facebook_Reading_Music_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Music execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Music_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Music_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Music choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Music_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Music choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Music_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Music execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Music execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Music execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.  Enhanced Choreo Outputs generated for this field.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Allows you to perform multiple graph operations in one request.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_BatchRequests_Batch extends Temboo_Choreography
{
    /**
     * Allows you to perform multiple graph operations in one request.
     *
     * @param Temboo_Session $session The session that owns this Batch choreography.
     * @return Facebook_BatchRequests_Batch New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/BatchRequests/Batch/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Batch choreography.
     *
     * @param Facebook_BatchRequests_Batch_Inputs|array $inputs (optional) Inputs as Facebook_BatchRequests_Batch_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_BatchRequests_Batch_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_BatchRequests_Batch_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Batch choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_BatchRequests_Batch_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_BatchRequests_Batch_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Batch choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_BatchRequests_Batch_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Batch choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_BatchRequests_Batch_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Batch input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_BatchRequests_Batch_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_BatchRequests_Batch_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Batch choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_BatchRequests_Batch_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Batch input for this Batch choreography.
     *
     * @param string $value (required, json) A JSON object which describes each individual operation you'd like to perform. See documentation for syntax examples.
     * @return Facebook_BatchRequests_Batch_Inputs For method chaining.
     */
    public function setBatch($value)
    {
        return $this->set('Batch', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Batch choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_BatchRequests_Batch_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the Batch choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_BatchRequests_Batch_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Batch choreography.
     *
     * @param Temboo_Session $session The session that owns this Batch execution.
     * @param Facebook_BatchRequests_Batch $choreo The choregraphy object for this execution.
     * @param Facebook_BatchRequests_Batch_Inputs|array $inputs (optional) Inputs as Facebook_BatchRequests_Batch_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_BatchRequests_Batch_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_BatchRequests_Batch $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Batch execution.
     *
     * @return Facebook_BatchRequests_Batch_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Batch execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_BatchRequests_Batch_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_BatchRequests_Batch_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Batch choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_BatchRequests_Batch_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Batch choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_BatchRequests_Batch_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Batch execution.
     *
     * @return string (string) Contains the Base64 encoded value of the image retrieved from Facebook.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of books that a given user has liked.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Books extends Temboo_Choreography
{
    /**
     * Retrieves a list of books that a given user has liked.
     *
     * @param Temboo_Session $session The session that owns this Books choreography.
     * @return Facebook_Reading_Books New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Books/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Books choreography.
     *
     * @param Facebook_Reading_Books_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Books_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Books_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Books_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Books choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Books_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Books_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Books choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Books_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Books choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Books_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Books input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Books choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Books choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Books choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Books choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Books choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve books for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Books choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Books choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Books choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Books_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Books choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Books_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Books choreography.
     *
     * @param Temboo_Session $session The session that owns this Books execution.
     * @param Facebook_Reading_Books $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Books_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Books_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Books_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Books $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Books execution.
     *
     * @return Facebook_Reading_Books_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Books execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Books_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Books_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Books choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Books_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Books choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Books_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Books execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Books execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Books execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Deletes a specified comment.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteComment extends Temboo_Choreography
{
    /**
     * Deletes a specified comment.
     *
     * @param Temboo_Session $session The session that owns this DeleteComment choreography.
     * @return Facebook_Deleting_DeleteComment New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Deleting/DeleteComment/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DeleteComment choreography.
     *
     * @param Facebook_Deleting_DeleteComment_Inputs|array $inputs (optional) Inputs as Facebook_Deleting_DeleteComment_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Deleting_DeleteComment_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Deleting_DeleteComment_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DeleteComment choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Deleting_DeleteComment_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Deleting_DeleteComment_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DeleteComment choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteComment_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DeleteComment choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Deleting_DeleteComment_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DeleteComment input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Deleting_DeleteComment_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Deleting_DeleteComment_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this DeleteComment choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Deleting_DeleteComment_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the CommentID input for this DeleteComment choreography.
     *
     * @param string $value (required, string) The id of the comment to delete
     * @return Facebook_Deleting_DeleteComment_Inputs For method chaining.
     */
    public function setCommentID($value)
    {
        return $this->set('CommentID', $value);
    }
}


/**
 * Execution object for the DeleteComment choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteComment_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DeleteComment choreography.
     *
     * @param Temboo_Session $session The session that owns this DeleteComment execution.
     * @param Facebook_Deleting_DeleteComment $choreo The choregraphy object for this execution.
     * @param Facebook_Deleting_DeleteComment_Inputs|array $inputs (optional) Inputs as Facebook_Deleting_DeleteComment_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Deleting_DeleteComment_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Deleting_DeleteComment $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DeleteComment execution.
     *
     * @return Facebook_Deleting_DeleteComment_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this DeleteComment execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Deleting_DeleteComment_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Deleting_DeleteComment_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DeleteComment choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Deleting_DeleteComment_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DeleteComment choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Deleting_DeleteComment_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this DeleteComment execution.
     *
     * @return bool (boolean) The response from Facebook. Returns "true" on success.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves notes associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Notes extends Temboo_Choreography
{
    /**
     * Retrieves notes associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this Notes choreography.
     * @return Facebook_Reading_Notes New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Notes/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Notes choreography.
     *
     * @param Facebook_Reading_Notes_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Notes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Notes_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Notes_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Notes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Notes_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Notes_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Notes choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Notes_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Notes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Notes_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Notes input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Notes choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Notes choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Notes choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Notes choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Notes choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve notes for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Notes choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Notes choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Notes choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Notes_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Notes choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Notes_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Notes choreography.
     *
     * @param Temboo_Session $session The session that owns this Notes execution.
     * @param Facebook_Reading_Notes $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Notes_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Notes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Notes_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Notes $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Notes execution.
     *
     * @return Facebook_Reading_Notes_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Notes execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Notes_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Notes_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Notes choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Notes_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Notes choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Notes_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Notes execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Notes execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Notes execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves the current profile photo for any object in the Facebook graph, and returns the image as a Base64 encoded string.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Picture extends Temboo_Choreography
{
    /**
     * Retrieves the current profile photo for any object in the Facebook graph, and returns the image as a Base64 encoded string.
     *
     * @param Temboo_Session $session The session that owns this Picture choreography.
     * @return Facebook_Reading_Picture New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Picture/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Picture choreography.
     *
     * @param Facebook_Reading_Picture_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Picture_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Picture_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Picture_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Picture choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Picture_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Picture_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Picture choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Picture_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Picture choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Picture_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Picture input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Picture_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Picture_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Picture choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Picture_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the ProfileID input for this Picture choreography.
     *
     * @param string $value (required, string) The id of the profile to retrieve a profile picture for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Picture_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ReturnSSLResources input for this Picture choreography.
     *
     * @param bool $value (optional, boolean) Set to 1 to return the picture over a secure connection. Defaults to 0.
     * @return Facebook_Reading_Picture_Inputs For method chaining.
     */
    public function setReturnSSLResources($value)
    {
        return $this->set('ReturnSSLResources', $value);
    }

    /**
     * Set the value for the Type input for this Picture choreography.
     *
     * @param string $value (optional, string) The size of the image to retrieve. Valid values: square (50x50), small (50 pixels wide, variable height), normal (100 pixels wide, variable height), and large (about 200 pixels wide, variable height)
     * @return Facebook_Reading_Picture_Inputs For method chaining.
     */
    public function setType($value)
    {
        return $this->set('Type', $value);
    }
}


/**
 * Execution object for the Picture choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Picture_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Picture choreography.
     *
     * @param Temboo_Session $session The session that owns this Picture execution.
     * @param Facebook_Reading_Picture $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Picture_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Picture_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Picture_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Picture $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Picture execution.
     *
     * @return Facebook_Reading_Picture_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Picture execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Picture_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Picture_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Picture choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Picture_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Picture choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Picture_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Picture execution.
     *
     * @return string (string) Contains the Base64 encoded value of the image retrieved from Facebook.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of friend requests for a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_FriendRequests extends Temboo_Choreography
{
    /**
     * Retrieves a list of friend requests for a specified user.
     *
     * @param Temboo_Session $session The session that owns this FriendRequests choreography.
     * @return Facebook_Reading_FriendRequests New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/FriendRequests/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this FriendRequests choreography.
     *
     * @param Facebook_Reading_FriendRequests_Inputs|array $inputs (optional) Inputs as Facebook_Reading_FriendRequests_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_FriendRequests_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_FriendRequests_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this FriendRequests choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_FriendRequests_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_FriendRequests_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the FriendRequests choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_FriendRequests_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the FriendRequests choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_FriendRequests_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this FriendRequests input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this FriendRequests choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this FriendRequests choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limt input for this FriendRequests choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setLimt($value)
    {
        return $this->set('Limt', $value);
    }

    /**
     * Set the value for the Offset input for this FriendRequests choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this FriendRequests choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve friend requests for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this FriendRequests choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this FriendRequests choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this FriendRequests choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_FriendRequests_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the FriendRequests choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_FriendRequests_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the FriendRequests choreography.
     *
     * @param Temboo_Session $session The session that owns this FriendRequests execution.
     * @param Facebook_Reading_FriendRequests $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_FriendRequests_Inputs|array $inputs (optional) Inputs as Facebook_Reading_FriendRequests_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_FriendRequests_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_FriendRequests $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this FriendRequests execution.
     *
     * @return Facebook_Reading_FriendRequests_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this FriendRequests execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_FriendRequests_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_FriendRequests_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the FriendRequests choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_FriendRequests_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the FriendRequests choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_FriendRequests_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this FriendRequests execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of photo albums associated with a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_PhotoAlbums extends Temboo_Choreography
{
    /**
     * Retrieves a list of photo albums associated with a specified user.
     *
     * @param Temboo_Session $session The session that owns this PhotoAlbums choreography.
     * @return Facebook_Reading_PhotoAlbums New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/PhotoAlbums/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this PhotoAlbums choreography.
     *
     * @param Facebook_Reading_PhotoAlbums_Inputs|array $inputs (optional) Inputs as Facebook_Reading_PhotoAlbums_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_PhotoAlbums_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_PhotoAlbums_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this PhotoAlbums choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_PhotoAlbums_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_PhotoAlbums_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the PhotoAlbums choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_PhotoAlbums_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the PhotoAlbums choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_PhotoAlbums_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this PhotoAlbums input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this PhotoAlbums choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this PhotoAlbums choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this PhotoAlbums choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this PhotoAlbums choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this PhotoAlbums choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve photo albums for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this PhotoAlbums choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this PhotoAlbums choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this PhotoAlbums choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_PhotoAlbums_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the PhotoAlbums choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_PhotoAlbums_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the PhotoAlbums choreography.
     *
     * @param Temboo_Session $session The session that owns this PhotoAlbums execution.
     * @param Facebook_Reading_PhotoAlbums $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_PhotoAlbums_Inputs|array $inputs (optional) Inputs as Facebook_Reading_PhotoAlbums_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_PhotoAlbums_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_PhotoAlbums $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this PhotoAlbums execution.
     *
     * @return Facebook_Reading_PhotoAlbums_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this PhotoAlbums execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_PhotoAlbums_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_PhotoAlbums_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the PhotoAlbums choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_PhotoAlbums_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the PhotoAlbums choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_PhotoAlbums_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this PhotoAlbums execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this PhotoAlbums execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this PhotoAlbums execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * RSVP to an event as "attending", "maybe", or "declined".
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_RSVPEvent extends Temboo_Choreography
{
    /**
     * RSVP to an event as "attending", "maybe", or "declined".
     *
     * @param Temboo_Session $session The session that owns this RSVPEvent choreography.
     * @return Facebook_Publishing_RSVPEvent New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/RSVPEvent/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this RSVPEvent choreography.
     *
     * @param Facebook_Publishing_RSVPEvent_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_RSVPEvent_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_RSVPEvent_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_RSVPEvent_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this RSVPEvent choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_RSVPEvent_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_RSVPEvent_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the RSVPEvent choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_RSVPEvent_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the RSVPEvent choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_RSVPEvent_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this RSVPEvent input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_RSVPEvent_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_RSVPEvent_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this RSVPEvent choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_RSVPEvent_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the EventID input for this RSVPEvent choreography.
     *
     * @param string $value (required, string) The id for the event  to rsvp for.
     * @return Facebook_Publishing_RSVPEvent_Inputs For method chaining.
     */
    public function setEventID($value)
    {
        return $this->set('EventID', $value);
    }

    /**
     * Set the value for the RSVP input for this RSVPEvent choreography.
     *
     * @param string $value (required, string) The RSVP for the event. Valid values are: attending, maybe, or declined.
     * @return Facebook_Publishing_RSVPEvent_Inputs For method chaining.
     */
    public function setRSVP($value)
    {
        return $this->set('RSVP', $value);
    }
}


/**
 * Execution object for the RSVPEvent choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_RSVPEvent_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the RSVPEvent choreography.
     *
     * @param Temboo_Session $session The session that owns this RSVPEvent execution.
     * @param Facebook_Publishing_RSVPEvent $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_RSVPEvent_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_RSVPEvent_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_RSVPEvent_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_RSVPEvent $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this RSVPEvent execution.
     *
     * @return Facebook_Publishing_RSVPEvent_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this RSVPEvent execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_RSVPEvent_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_RSVPEvent_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the RSVPEvent choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_RSVPEvent_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the RSVPEvent choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_RSVPEvent_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this RSVPEvent execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of  statuses for a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Statuses extends Temboo_Choreography
{
    /**
     * Retrieves a list of  statuses for a specified user.
     *
     * @param Temboo_Session $session The session that owns this Statuses choreography.
     * @return Facebook_Reading_Statuses New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Statuses/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Statuses choreography.
     *
     * @param Facebook_Reading_Statuses_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Statuses_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Statuses_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Statuses_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Statuses choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Statuses_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Statuses_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Statuses choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Statuses_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Statuses choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Statuses_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Statuses input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Statuses choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final OAuth step.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Statuses choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Statuses choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Statuses choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Statuses choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve a list of statuses for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Statuses choreography.
     *
     * @param string $value (optional, string) The format that response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Statuses choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Statuses choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Statuses_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Statuses choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Statuses_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Statuses choreography.
     *
     * @param Temboo_Session $session The session that owns this Statuses execution.
     * @param Facebook_Reading_Statuses $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Statuses_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Statuses_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Statuses_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Statuses $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Statuses execution.
     *
     * @return Facebook_Reading_Statuses_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Statuses execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Statuses_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Statuses_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Statuses choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Statuses_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Statuses choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Statuses_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Statuses execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Statuses execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Statuses execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of messages in a specified user's inbox.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Inbox extends Temboo_Choreography
{
    /**
     * Retrieves a list of messages in a specified user's inbox.
     *
     * @param Temboo_Session $session The session that owns this Inbox choreography.
     * @return Facebook_Reading_Inbox New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Inbox/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Inbox choreography.
     *
     * @param Facebook_Reading_Inbox_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Inbox_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Inbox_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Inbox_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Inbox choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Inbox_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Inbox_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Inbox choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Inbox_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Inbox choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Inbox_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Inbox input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Inbox_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Inbox_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Inbox choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Inbox_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Inbox choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Inbox_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the ProfileID input for this Inbox choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve messages for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Inbox_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Inbox choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Inbox_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the Inbox choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Inbox_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Inbox choreography.
     *
     * @param Temboo_Session $session The session that owns this Inbox execution.
     * @param Facebook_Reading_Inbox $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Inbox_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Inbox_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Inbox_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Inbox $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Inbox execution.
     *
     * @return Facebook_Reading_Inbox_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Inbox execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Inbox_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Inbox_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Inbox choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Inbox_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Inbox choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Inbox_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Inbox execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves the Likes for a specified user.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Likes extends Temboo_Choreography
{
    /**
     * Retrieves the Likes for a specified user.
     *
     * @param Temboo_Session $session The session that owns this Likes choreography.
     * @return Facebook_Reading_Likes New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Reading/Likes/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Likes choreography.
     *
     * @param Facebook_Reading_Likes_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Likes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Likes_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Reading_Likes_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Likes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Likes_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Reading_Likes_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Likes choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Likes_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Likes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Reading_Likes_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Likes input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Likes choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Fields input for this Likes choreography.
     *
     * @param string $value (optional, string) A comma separated list of fields to return (i.e. id,name).
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setFields($value)
    {
        return $this->set('Fields', $value);
    }

    /**
     * Set the value for the Limit input for this Likes choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the response.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Offset input for this Likes choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Returns results starting from the specified number.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }

    /**
     * Set the value for the ProfileID input for this Likes choreography.
     *
     * @param string $value (optional, string) The id of the profile to retrieve likes for. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this Likes choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this Likes choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the Until input for this Likes choreography.
     *
     * @param string $value (optional, date) Used for time-based pagination. Values can be a unix timestamp or any date accepted by strtotime.
     * @return Facebook_Reading_Likes_Inputs For method chaining.
     */
    public function setUntil($value)
    {
        return $this->set('Until', $value);
    }
}


/**
 * Execution object for the Likes choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Likes_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Likes choreography.
     *
     * @param Temboo_Session $session The session that owns this Likes execution.
     * @param Facebook_Reading_Likes $choreo The choregraphy object for this execution.
     * @param Facebook_Reading_Likes_Inputs|array $inputs (optional) Inputs as Facebook_Reading_Likes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Reading_Likes_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Reading_Likes $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Likes execution.
     *
     * @return Facebook_Reading_Likes_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this Likes execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Reading_Likes_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Reading_Likes_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Likes choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Reading_Likes_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Likes choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Reading_Likes_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "HasNext" output from this Likes execution.
     *
     * @return bool (boolean) A boolean flag indicating that a next page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasNext()
    {
        return $this->get('HasNext');
    }

    /**
     * Retrieve the value for the "HasPrevious" output from this Likes execution.
     *
     * @return bool (boolean) A boolean flag indicating that a previous page exists.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getHasPrevious()
    {
        return $this->get('HasPrevious');
    }

    /**
     * Retrieve the value for the "Response" output from this Likes execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Updates a user's Facebook status.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_SetStatus extends Temboo_Choreography
{
    /**
     * Updates a user's Facebook status.
     *
     * @param Temboo_Session $session The session that owns this SetStatus choreography.
     * @return Facebook_Publishing_SetStatus New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Facebook/Publishing/SetStatus/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SetStatus choreography.
     *
     * @param Facebook_Publishing_SetStatus_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_SetStatus_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_SetStatus_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Facebook_Publishing_SetStatus_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SetStatus choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_SetStatus_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Facebook_Publishing_SetStatus_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SetStatus choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_SetStatus_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SetStatus choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Facebook_Publishing_SetStatus_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SetStatus input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Facebook_Publishing_SetStatus_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Facebook_Publishing_SetStatus_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this SetStatus choreography.
     *
     * @param string $value (required, string) The access token retrieved from the final step of the OAuth process.
     * @return Facebook_Publishing_SetStatus_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Message input for this SetStatus choreography.
     *
     * @param string $value (required, string) The status message to set.
     * @return Facebook_Publishing_SetStatus_Inputs For method chaining.
     */
    public function setMessage($value)
    {
        return $this->set('Message', $value);
    }

    /**
     * Set the value for the ProfileID input for this SetStatus choreography.
     *
     * @param string $value (optional, string) The id of the profile that is being updated. Defaults to "me" indicating the authenticated user.
     * @return Facebook_Publishing_SetStatus_Inputs For method chaining.
     */
    public function setProfileID($value)
    {
        return $this->set('ProfileID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this SetStatus choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Can be set to xml or json. Defaults to json.
     * @return Facebook_Publishing_SetStatus_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the SetStatus choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_SetStatus_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SetStatus choreography.
     *
     * @param Temboo_Session $session The session that owns this SetStatus execution.
     * @param Facebook_Publishing_SetStatus $choreo The choregraphy object for this execution.
     * @param Facebook_Publishing_SetStatus_Inputs|array $inputs (optional) Inputs as Facebook_Publishing_SetStatus_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Facebook_Publishing_SetStatus_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Facebook_Publishing_SetStatus $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SetStatus execution.
     *
     * @return Facebook_Publishing_SetStatus_Results New results object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occured in asynchronous execution.
     * @throws Temboo_Exception_Notfound if execution does not exist.
     * @throws Temboo_Exception if result request fails.
     */
    public function getResults()
    {
        return parent::getResults();
    }

    /**
     * Wraps results in appopriate results class for this SetStatus execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Facebook_Publishing_SetStatus_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Facebook_Publishing_SetStatus_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SetStatus choreography.
 *
 * @package Temboo
 * @subpackage Facebook
 */
class Facebook_Publishing_SetStatus_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SetStatus choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Facebook_Publishing_SetStatus_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this SetStatus execution.
     *
     * @return string The response from Facebook. Corresponds to the ResponseFormat input. Defaults to JSON.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

?>