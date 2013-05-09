<?php

/**
 * Temboo PHP SDK EnviroFacts classes
 *
 * Execute Choreographies from the Temboo EnviroFacts bundle.
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
 * @subpackage EnviroFacts
 * @author     Temboo, Inc.
 * @copyright  2012 Temboo, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @version    1.7
 * @link       http://live.temboo.com/sdk/php
 */


/**
 * Retrieves a list of the annual release quantities of toxic chemicals at EPA-regulated facilities into air, water, on-site land, and underground wells.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ToxinReleaseByFacility extends Temboo_Choreography
{
    /**
     * Retrieves a list of the annual release quantities of toxic chemicals at EPA-regulated facilities into air, water, on-site land, and underground wells.
     *
     * @param Temboo_Session $session The session that owns this ToxinReleaseByFacility choreography.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/Toxins/ToxinReleaseByFacility/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ToxinReleaseByFacility choreography.
     *
     * @param EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs|array $inputs (optional) Inputs as EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_Toxins_ToxinReleaseByFacility_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ToxinReleaseByFacility choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ToxinReleaseByFacility choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ToxinReleaseByFacility choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ToxinReleaseByFacility input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the FacilityID input for this ToxinReleaseByFacility choreography.
     *
     * @param string $value (required, string) FacilityID for which a toxin release report is to be generated. Found by first running the FacilitiesSearch Choreo.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs For method chaining.
     */
    public function setFacilityID($value)
    {
        return $this->set('FacilityID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ToxinReleaseByFacility choreography.
     *
     * @param string $value (optional, string) Specify the desired response format. Valid formats are: xml (the default) and csv.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the RowEnd input for this ToxinReleaseByFacility choreography.
     *
     * @param int $value (optional, integer) Number 1 or greater indicates the ending row number of the results displayed. Default is 4999 when RowStart is 0. Up to 5000 entries are returned in the output.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs For method chaining.
     */
    public function setRowEnd($value)
    {
        return $this->set('RowEnd', $value);
    }

    /**
     * Set the value for the RowStart input for this ToxinReleaseByFacility choreography.
     *
     * @param int $value (optional, integer) Indicates the starting row number of the results displayed. Default is 0.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs For method chaining.
     */
    public function setRowStart($value)
    {
        return $this->set('RowStart', $value);
    }
}


/**
 * Execution object for the ToxinReleaseByFacility choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ToxinReleaseByFacility_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ToxinReleaseByFacility choreography.
     *
     * @param Temboo_Session $session The session that owns this ToxinReleaseByFacility execution.
     * @param EnviroFacts_Toxins_ToxinReleaseByFacility $choreo The choregraphy object for this execution.
     * @param EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs|array $inputs (optional) Inputs as EnviroFacts_Toxins_ToxinReleaseByFacility_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_Toxins_ToxinReleaseByFacility $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ToxinReleaseByFacility execution.
     *
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Results New results object.
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
     * Wraps results in appopriate results class for this ToxinReleaseByFacility execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_Toxins_ToxinReleaseByFacility_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ToxinReleaseByFacility choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ToxinReleaseByFacility_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ToxinReleaseByFacility choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_Toxins_ToxinReleaseByFacility_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ToxinReleaseByFacility execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves EPA hourly Ultraviolet (UV) Index readings in a given city. 
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_HourlyUVByCity extends Temboo_Choreography
{
    /**
     * Retrieves EPA hourly Ultraviolet (UV) Index readings in a given city. 
     *
     * @param Temboo_Session $session The session that owns this HourlyUVByCity choreography.
     * @return EnviroFacts_UVForecast_HourlyUVByCity New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/UVForecast/HourlyUVByCity/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this HourlyUVByCity choreography.
     *
     * @param EnviroFacts_UVForecast_HourlyUVByCity_Inputs|array $inputs (optional) Inputs as EnviroFacts_UVForecast_HourlyUVByCity_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_UVForecast_HourlyUVByCity_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this HourlyUVByCity choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_UVForecast_HourlyUVByCity_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the HourlyUVByCity choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_HourlyUVByCity_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the HourlyUVByCity choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this HourlyUVByCity input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the City input for this HourlyUVByCity choreography.
     *
     * @param string $value (required, string) A valid City Name in the United States.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Inputs For method chaining.
     */
    public function setCity($value)
    {
        return $this->set('City', $value);
    }

    /**
     * Set the value for the ResponseType input for this HourlyUVByCity choreography.
     *
     * @param string $value (optional, string) Results can be retrieved in either JSON or XML. Defaults to XML.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Inputs For method chaining.
     */
    public function setResponseType($value)
    {
        return $this->set('ResponseType', $value);
    }

    /**
     * Set the value for the State input for this HourlyUVByCity choreography.
     *
     * @param string $value (required, string) The abbreviation of the state that the city resides in.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Inputs For method chaining.
     */
    public function setState($value)
    {
        return $this->set('State', $value);
    }
}


/**
 * Execution object for the HourlyUVByCity choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_HourlyUVByCity_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the HourlyUVByCity choreography.
     *
     * @param Temboo_Session $session The session that owns this HourlyUVByCity execution.
     * @param EnviroFacts_UVForecast_HourlyUVByCity $choreo The choregraphy object for this execution.
     * @param EnviroFacts_UVForecast_HourlyUVByCity_Inputs|array $inputs (optional) Inputs as EnviroFacts_UVForecast_HourlyUVByCity_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_UVForecast_HourlyUVByCity $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this HourlyUVByCity execution.
     *
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Results New results object.
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
     * Wraps results in appopriate results class for this HourlyUVByCity execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_UVForecast_HourlyUVByCity_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the HourlyUVByCity choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_HourlyUVByCity_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the HourlyUVByCity choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_UVForecast_HourlyUVByCity_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this HourlyUVByCity execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves EPA daily Ultraviolet (UV) Index readings in a given zip code. 
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_DailyUVByZipCode extends Temboo_Choreography
{
    /**
     * Retrieves EPA daily Ultraviolet (UV) Index readings in a given zip code. 
     *
     * @param Temboo_Session $session The session that owns this DailyUVByZipCode choreography.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/UVForecast/DailyUVByZipCode/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DailyUVByZipCode choreography.
     *
     * @param EnviroFacts_UVForecast_DailyUVByZipCode_Inputs|array $inputs (optional) Inputs as EnviroFacts_UVForecast_DailyUVByZipCode_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_UVForecast_DailyUVByZipCode_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DailyUVByZipCode choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_UVForecast_DailyUVByZipCode_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DailyUVByZipCode choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_DailyUVByZipCode_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DailyUVByZipCode choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DailyUVByZipCode input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ResponseType input for this DailyUVByZipCode choreography.
     *
     * @param string $value (optional, string) Results can be retrieved in either JSON or XML. Defaults to XML.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Inputs For method chaining.
     */
    public function setResponseType($value)
    {
        return $this->set('ResponseType', $value);
    }

    /**
     * Set the value for the Zip input for this DailyUVByZipCode choreography.
     *
     * @param int $value (required, integer) A valid United States Postal Service (USPS) ZIP Code or Postal Code.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Inputs For method chaining.
     */
    public function setZip($value)
    {
        return $this->set('Zip', $value);
    }
}


/**
 * Execution object for the DailyUVByZipCode choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_DailyUVByZipCode_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DailyUVByZipCode choreography.
     *
     * @param Temboo_Session $session The session that owns this DailyUVByZipCode execution.
     * @param EnviroFacts_UVForecast_DailyUVByZipCode $choreo The choregraphy object for this execution.
     * @param EnviroFacts_UVForecast_DailyUVByZipCode_Inputs|array $inputs (optional) Inputs as EnviroFacts_UVForecast_DailyUVByZipCode_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_UVForecast_DailyUVByZipCode $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DailyUVByZipCode execution.
     *
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Results New results object.
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
     * Wraps results in appopriate results class for this DailyUVByZipCode execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_UVForecast_DailyUVByZipCode_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DailyUVByZipCode choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_DailyUVByZipCode_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DailyUVByZipCode choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_UVForecast_DailyUVByZipCode_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this DailyUVByZipCode execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of EPA-regulated facilities in the Toxins Release Inventory (TRI) database within a given area code.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_FacilitiesSearchByZip extends Temboo_Choreography
{
    /**
     * Retrieves a list of EPA-regulated facilities in the Toxins Release Inventory (TRI) database within a given area code.
     *
     * @param Temboo_Session $session The session that owns this FacilitiesSearchByZip choreography.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/Toxins/FacilitiesSearchByZip/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this FacilitiesSearchByZip choreography.
     *
     * @param EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs|array $inputs (optional) Inputs as EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_Toxins_FacilitiesSearchByZip_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this FacilitiesSearchByZip choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the FacilitiesSearchByZip choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the FacilitiesSearchByZip choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this FacilitiesSearchByZip input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ResponseFormat input for this FacilitiesSearchByZip choreography.
     *
     * @param string $value (optional, string) Specify the desired response format. Valid formats are: xml (the default) and csv.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the RowEnd input for this FacilitiesSearchByZip choreography.
     *
     * @param int $value (optional, integer) Number 1 or greater indicates the ending row number of the results displayed. Default is 4999 when RowStart is 0. Up to 5000 entries are returned in the output.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs For method chaining.
     */
    public function setRowEnd($value)
    {
        return $this->set('RowEnd', $value);
    }

    /**
     * Set the value for the RowStart input for this FacilitiesSearchByZip choreography.
     *
     * @param int $value (optional, integer) Indicates the starting row number of the results displayed. Default is 0.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs For method chaining.
     */
    public function setRowStart($value)
    {
        return $this->set('RowStart', $value);
    }

    /**
     * Set the value for the Zip input for this FacilitiesSearchByZip choreography.
     *
     * @param string $value (required, string) Zip code to be searched.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs For method chaining.
     */
    public function setZip($value)
    {
        return $this->set('Zip', $value);
    }
}


/**
 * Execution object for the FacilitiesSearchByZip choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_FacilitiesSearchByZip_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the FacilitiesSearchByZip choreography.
     *
     * @param Temboo_Session $session The session that owns this FacilitiesSearchByZip execution.
     * @param EnviroFacts_Toxins_FacilitiesSearchByZip $choreo The choregraphy object for this execution.
     * @param EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs|array $inputs (optional) Inputs as EnviroFacts_Toxins_FacilitiesSearchByZip_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_Toxins_FacilitiesSearchByZip $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this FacilitiesSearchByZip execution.
     *
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Results New results object.
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
     * Wraps results in appopriate results class for this FacilitiesSearchByZip execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_Toxins_FacilitiesSearchByZip_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the FacilitiesSearchByZip choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_FacilitiesSearchByZip_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the FacilitiesSearchByZip choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_Toxins_FacilitiesSearchByZip_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this FacilitiesSearchByZip execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Looks up products by sector in the EPA Design for the Environment database
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_SearchBySector extends Temboo_Choreography
{
    /**
     * Looks up products by sector in the EPA Design for the Environment database
     *
     * @param Temboo_Session $session The session that owns this SearchBySector choreography.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/DesignForEnvironment/SearchBySector/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SearchBySector choreography.
     *
     * @param EnviroFacts_DesignForEnvironment_SearchBySector_Inputs|array $inputs (optional) Inputs as EnviroFacts_DesignForEnvironment_SearchBySector_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_DesignForEnvironment_SearchBySector_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SearchBySector choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_DesignForEnvironment_SearchBySector_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SearchBySector choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_SearchBySector_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SearchBySector choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SearchBySector input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Category input for this SearchBySector choreography.
     *
     * @param string $value (conditional, string) Specify either Industrial or Consumer to retrieve a list of products that fall into either category. If a specific SectorKeyword or SectorID is given, this input is ignored.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function setCategory($value)
    {
        return $this->set('Category', $value);
    }

    /**
     * Set the value for the Operator input for this SearchBySector choreography.
     *
     * @param string $value (optional, string) Default output is "CONTAINING" and does not require an operator, but users can enter "<", " >", "!=", "BEGINNING", "=" for more customized searches.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function setOperator($value)
    {
        return $this->set('Operator', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this SearchBySector choreography.
     *
     * @param string $value (conditional, string) Response can be returned in JSON or XML. Defaults to XML.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the RowEnd input for this SearchBySector choreography.
     *
     * @param int $value (optional, integer) Number 1 or greater indicates the ending row number of the results displayed. Default is 4999 when RowStart is 0. Up to 5000 entries are returned in the output.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function setRowEnd($value)
    {
        return $this->set('RowEnd', $value);
    }

    /**
     * Set the value for the RowStart input for this SearchBySector choreography.
     *
     * @param int $value (optional, integer) Indicates the starting row number of the results displayed. Default is 0.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function setRowStart($value)
    {
        return $this->set('RowStart', $value);
    }

    /**
     * Set the value for the SectorID input for this SearchBySector choreography.
     *
     * @param int $value (conditional, integer) A number representing the unique identifier for the product's sector in the EnviroFacts database.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function setSectorID($value)
    {
        return $this->set('SectorID', $value);
    }

    /**
     * Set the value for the SectorKeyword input for this SearchBySector choreography.
     *
     * @param string $value (conditional, string) A keyword in the name of the sector to search for. If a specific SectorID is given, this input is ignored.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Inputs For method chaining.
     */
    public function setSectorKeyword($value)
    {
        return $this->set('SectorKeyword', $value);
    }
}


/**
 * Execution object for the SearchBySector choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_SearchBySector_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SearchBySector choreography.
     *
     * @param Temboo_Session $session The session that owns this SearchBySector execution.
     * @param EnviroFacts_DesignForEnvironment_SearchBySector $choreo The choregraphy object for this execution.
     * @param EnviroFacts_DesignForEnvironment_SearchBySector_Inputs|array $inputs (optional) Inputs as EnviroFacts_DesignForEnvironment_SearchBySector_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_DesignForEnvironment_SearchBySector $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SearchBySector execution.
     *
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Results New results object.
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
     * Wraps results in appopriate results class for this SearchBySector execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_DesignForEnvironment_SearchBySector_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SearchBySector choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_SearchBySector_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SearchBySector choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_DesignForEnvironment_SearchBySector_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Count" output from this SearchBySector execution.
     *
     * @return string The total number of records returned for any given search.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getCount()
    {
        return $this->get('Count');
    }

    /**
     * Retrieve the value for the "Response" output from this SearchBySector execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Searches for products in the EPA Design for the Environment database.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_SearchByProduct extends Temboo_Choreography
{
    /**
     * Searches for products in the EPA Design for the Environment database.
     *
     * @param Temboo_Session $session The session that owns this SearchByProduct choreography.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/DesignForEnvironment/SearchByProduct/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SearchByProduct choreography.
     *
     * @param EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs|array $inputs (optional) Inputs as EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_DesignForEnvironment_SearchByProduct_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SearchByProduct choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SearchByProduct choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SearchByProduct choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SearchByProduct input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the CompanyKeyword input for this SearchByProduct choreography.
     *
     * @param string $value (conditional, string) A keyword in the name of the company to search for. If a specific ProductName or ProductID is specified, this input is ignored.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function setCompanyKeyword($value)
    {
        return $this->set('CompanyKeyword', $value);
    }

    /**
     * Set the value for the Operator input for this SearchByProduct choreography.
     *
     * @param string $value (optional, string) Default output is "CONTAINING" and does not require an operator, but users can enter "<", " >", "!=", "BEGINNING", "=" for more customized searches.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function setOperator($value)
    {
        return $this->set('Operator', $value);
    }

    /**
     * Set the value for the ProductID input for this SearchByProduct choreography.
     *
     * @param int $value (conditional, integer) A number representing the unique identifier for a product in the EnviroFacts database.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function setProductID($value)
    {
        return $this->set('ProductID', $value);
    }

    /**
     * Set the value for the ProductKeyword input for this SearchByProduct choreography.
     *
     * @param string $value (conditional, string) A keyword in the name of the product to search for. If a specific ProductID is specified, this input is ignored.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function setProductKeyword($value)
    {
        return $this->set('ProductKeyword', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this SearchByProduct choreography.
     *
     * @param string $value (conditional, string) Response can be returned in JSON or XML. Defaults to XML.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the RowEnd input for this SearchByProduct choreography.
     *
     * @param int $value (optional, integer) Number 1 or greater indicates the ending row number of the results displayed. Default is 4999 when RowStart is 0. Up to 5000 entries are returned in the output.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function setRowEnd($value)
    {
        return $this->set('RowEnd', $value);
    }

    /**
     * Set the value for the RowStart input for this SearchByProduct choreography.
     *
     * @param int $value (optional, integer) Indicates the starting row number of the results displayed. Default is 0.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs For method chaining.
     */
    public function setRowStart($value)
    {
        return $this->set('RowStart', $value);
    }
}


/**
 * Execution object for the SearchByProduct choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_SearchByProduct_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SearchByProduct choreography.
     *
     * @param Temboo_Session $session The session that owns this SearchByProduct execution.
     * @param EnviroFacts_DesignForEnvironment_SearchByProduct $choreo The choregraphy object for this execution.
     * @param EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs|array $inputs (optional) Inputs as EnviroFacts_DesignForEnvironment_SearchByProduct_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_DesignForEnvironment_SearchByProduct $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SearchByProduct execution.
     *
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Results New results object.
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
     * Wraps results in appopriate results class for this SearchByProduct execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_DesignForEnvironment_SearchByProduct_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SearchByProduct choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_SearchByProduct_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SearchByProduct choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_DesignForEnvironment_SearchByProduct_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Count" output from this SearchByProduct execution.
     *
     * @return string The total number of records returned for any given search.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getCount()
    {
        return $this->get('Count');
    }

    /**
     * Retrieve the value for the "Response" output from this SearchByProduct execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of the type of manufacturing activity of toxic chemicals at any EPA-regulated facility.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ChemActivityByFacility extends Temboo_Choreography
{
    /**
     * Retrieves a list of the type of manufacturing activity of toxic chemicals at any EPA-regulated facility.
     *
     * @param Temboo_Session $session The session that owns this ChemActivityByFacility choreography.
     * @return EnviroFacts_Toxins_ChemActivityByFacility New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/Toxins/ChemActivityByFacility/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ChemActivityByFacility choreography.
     *
     * @param EnviroFacts_Toxins_ChemActivityByFacility_Inputs|array $inputs (optional) Inputs as EnviroFacts_Toxins_ChemActivityByFacility_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_Toxins_ChemActivityByFacility_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ChemActivityByFacility choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_Toxins_ChemActivityByFacility_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ChemActivityByFacility choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ChemActivityByFacility_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ChemActivityByFacility choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ChemActivityByFacility input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the FacilityID input for this ChemActivityByFacility choreography.
     *
     * @param string $value (required, string) FacilityID for which a toxin release report is to be generated. Found by first running the FacilitiesSearch Choreo.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Inputs For method chaining.
     */
    public function setFacilityID($value)
    {
        return $this->set('FacilityID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ChemActivityByFacility choreography.
     *
     * @param string $value (optional, string) Specify the desired response format. Valid formats are: xml (the default) and csv.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the RowEnd input for this ChemActivityByFacility choreography.
     *
     * @param int $value (optional, integer) Number 1 or greater indicates the ending row number of the results displayed. Default is 4999 when RowStart is 0. Up to 5000 entries are returned in the output.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Inputs For method chaining.
     */
    public function setRowEnd($value)
    {
        return $this->set('RowEnd', $value);
    }

    /**
     * Set the value for the RowStart input for this ChemActivityByFacility choreography.
     *
     * @param int $value (optional, integer) Indicates the starting row number of the results displayed. Default is 0.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Inputs For method chaining.
     */
    public function setRowStart($value)
    {
        return $this->set('RowStart', $value);
    }
}


/**
 * Execution object for the ChemActivityByFacility choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ChemActivityByFacility_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ChemActivityByFacility choreography.
     *
     * @param Temboo_Session $session The session that owns this ChemActivityByFacility execution.
     * @param EnviroFacts_Toxins_ChemActivityByFacility $choreo The choregraphy object for this execution.
     * @param EnviroFacts_Toxins_ChemActivityByFacility_Inputs|array $inputs (optional) Inputs as EnviroFacts_Toxins_ChemActivityByFacility_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_Toxins_ChemActivityByFacility $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ChemActivityByFacility execution.
     *
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Results New results object.
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
     * Wraps results in appopriate results class for this ChemActivityByFacility execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_Toxins_ChemActivityByFacility_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ChemActivityByFacility choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ChemActivityByFacility_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ChemActivityByFacility choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_Toxins_ChemActivityByFacility_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ChemActivityByFacility execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves information about specific chemicals released by EPA-regulated facilities.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ChemicalSearch extends Temboo_Choreography
{
    /**
     * Retrieves information about specific chemicals released by EPA-regulated facilities.
     *
     * @param Temboo_Session $session The session that owns this ChemicalSearch choreography.
     * @return EnviroFacts_Toxins_ChemicalSearch New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/Toxins/ChemicalSearch/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ChemicalSearch choreography.
     *
     * @param EnviroFacts_Toxins_ChemicalSearch_Inputs|array $inputs (optional) Inputs as EnviroFacts_Toxins_ChemicalSearch_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_Toxins_ChemicalSearch_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_Toxins_ChemicalSearch_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ChemicalSearch choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_Toxins_ChemicalSearch_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_Toxins_ChemicalSearch_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ChemicalSearch choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ChemicalSearch_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ChemicalSearch choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_Toxins_ChemicalSearch_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ChemicalSearch input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_Toxins_ChemicalSearch_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_Toxins_ChemicalSearch_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ChemicalID input for this ChemicalSearch choreography.
     *
     * @param string $value (required, string) EPA ID number of a chemical. Chemical IDs from a given facility can be found by first running the ChemActivityByFacility or ToxinReleaseByFacility Choreos.
     * @return EnviroFacts_Toxins_ChemicalSearch_Inputs For method chaining.
     */
    public function setChemicalID($value)
    {
        return $this->set('ChemicalID', $value);
    }
}


/**
 * Execution object for the ChemicalSearch choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ChemicalSearch_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ChemicalSearch choreography.
     *
     * @param Temboo_Session $session The session that owns this ChemicalSearch execution.
     * @param EnviroFacts_Toxins_ChemicalSearch $choreo The choregraphy object for this execution.
     * @param EnviroFacts_Toxins_ChemicalSearch_Inputs|array $inputs (optional) Inputs as EnviroFacts_Toxins_ChemicalSearch_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_Toxins_ChemicalSearch_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_Toxins_ChemicalSearch $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ChemicalSearch execution.
     *
     * @return EnviroFacts_Toxins_ChemicalSearch_Results New results object.
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
     * Wraps results in appopriate results class for this ChemicalSearch execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_Toxins_ChemicalSearch_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_Toxins_ChemicalSearch_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ChemicalSearch choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_Toxins_ChemicalSearch_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ChemicalSearch choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_Toxins_ChemicalSearch_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ChemicalSearch execution.
     *
     * @return string (xml) The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves EPA daily Ultraviolet (UV) Index readings in a given city.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_DailyUVByCity extends Temboo_Choreography
{
    /**
     * Retrieves EPA daily Ultraviolet (UV) Index readings in a given city.
     *
     * @param Temboo_Session $session The session that owns this DailyUVByCity choreography.
     * @return EnviroFacts_UVForecast_DailyUVByCity New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/UVForecast/DailyUVByCity/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DailyUVByCity choreography.
     *
     * @param EnviroFacts_UVForecast_DailyUVByCity_Inputs|array $inputs (optional) Inputs as EnviroFacts_UVForecast_DailyUVByCity_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_UVForecast_DailyUVByCity_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DailyUVByCity choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_UVForecast_DailyUVByCity_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DailyUVByCity choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_DailyUVByCity_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DailyUVByCity choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DailyUVByCity input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the City input for this DailyUVByCity choreography.
     *
     * @param string $value (required, string) A valid City Name in the United States.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Inputs For method chaining.
     */
    public function setCity($value)
    {
        return $this->set('City', $value);
    }

    /**
     * Set the value for the ResponseType input for this DailyUVByCity choreography.
     *
     * @param string $value (optional, string) Results can be retrieved in either JSON or XML. Defaults to XML.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Inputs For method chaining.
     */
    public function setResponseType($value)
    {
        return $this->set('ResponseType', $value);
    }

    /**
     * Set the value for the State input for this DailyUVByCity choreography.
     *
     * @param string $value (required, string) The abbreviation of the state that the city resides in.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Inputs For method chaining.
     */
    public function setState($value)
    {
        return $this->set('State', $value);
    }
}


/**
 * Execution object for the DailyUVByCity choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_DailyUVByCity_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DailyUVByCity choreography.
     *
     * @param Temboo_Session $session The session that owns this DailyUVByCity execution.
     * @param EnviroFacts_UVForecast_DailyUVByCity $choreo The choregraphy object for this execution.
     * @param EnviroFacts_UVForecast_DailyUVByCity_Inputs|array $inputs (optional) Inputs as EnviroFacts_UVForecast_DailyUVByCity_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_UVForecast_DailyUVByCity $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DailyUVByCity execution.
     *
     * @return EnviroFacts_UVForecast_DailyUVByCity_Results New results object.
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
     * Wraps results in appopriate results class for this DailyUVByCity execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_UVForecast_DailyUVByCity_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DailyUVByCity choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_DailyUVByCity_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DailyUVByCity choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_UVForecast_DailyUVByCity_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this DailyUVByCity execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Performs a detailed search of the EPA Design for the Environment database.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_AdvancedSearch extends Temboo_Choreography
{
    /**
     * Performs a detailed search of the EPA Design for the Environment database.
     *
     * @param Temboo_Session $session The session that owns this AdvancedSearch choreography.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/DesignForEnvironment/AdvancedSearch/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this AdvancedSearch choreography.
     *
     * @param EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs|array $inputs (optional) Inputs as EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_DesignForEnvironment_AdvancedSearch_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this AdvancedSearch choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the AdvancedSearch choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the AdvancedSearch choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this AdvancedSearch input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Operator input for this AdvancedSearch choreography.
     *
     * @param string $value (optional, string) Default output is "=" when SearchType=sector_id or product_id, and "CONTAINING" when SearchType=partner, product, or sector. Other possible values are: "<", " >", "!=", and "BEGINNING".
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs For method chaining.
     */
    public function setOperator($value)
    {
        return $this->set('Operator', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this AdvancedSearch choreography.
     *
     * @param string $value (conditional, string) Response can be returned in JSON or XML. Defaults to XML.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the RowEnd input for this AdvancedSearch choreography.
     *
     * @param int $value (optional, integer) Number 1 or greater indicates the ending row number of the results displayed. Default is 4999 when RowStart is 0. Up to 5000 entries are returned in the output.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs For method chaining.
     */
    public function setRowEnd($value)
    {
        return $this->set('RowEnd', $value);
    }

    /**
     * Set the value for the RowStart input for this AdvancedSearch choreography.
     *
     * @param int $value (optional, integer) Indicates the starting row number of the results displayed. Default is 0.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs For method chaining.
     */
    public function setRowStart($value)
    {
        return $this->set('RowStart', $value);
    }

    /**
     * Set the value for the SearchType input for this AdvancedSearch choreography.
     *
     * @param string $value (conditional, string) Indicate either "sector", "sector_id", "partner", "product", or "product_id." Used together with SearchValue and the optional Operator input to formulate a specific search of the DfE database.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs For method chaining.
     */
    public function setSearchType($value)
    {
        return $this->set('SearchType', $value);
    }

    /**
     * Set the value for the SearchValue input for this AdvancedSearch choreography.
     *
     * @param int $value (conditional, integer) Indicate the product, code, or sector to search for. Used together with SearchType and the optional Operator input to create a customized search.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs For method chaining.
     */
    public function setSearchValue($value)
    {
        return $this->set('SearchValue', $value);
    }
}


/**
 * Execution object for the AdvancedSearch choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_AdvancedSearch_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the AdvancedSearch choreography.
     *
     * @param Temboo_Session $session The session that owns this AdvancedSearch execution.
     * @param EnviroFacts_DesignForEnvironment_AdvancedSearch $choreo The choregraphy object for this execution.
     * @param EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs|array $inputs (optional) Inputs as EnviroFacts_DesignForEnvironment_AdvancedSearch_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_DesignForEnvironment_AdvancedSearch $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this AdvancedSearch execution.
     *
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Results New results object.
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
     * Wraps results in appopriate results class for this AdvancedSearch execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_DesignForEnvironment_AdvancedSearch_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the AdvancedSearch choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_DesignForEnvironment_AdvancedSearch_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the AdvancedSearch choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_DesignForEnvironment_AdvancedSearch_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Count" output from this AdvancedSearch execution.
     *
     * @return string The total number of records returned for any given search.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getCount()
    {
        return $this->get('Count');
    }

    /**
     * Retrieve the value for the "Response" output from this AdvancedSearch execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves EPA hourly Ultraviolet (UV) Index readings in a given zip code.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_HourlyUVByZipCode extends Temboo_Choreography
{
    /**
     * Retrieves EPA hourly Ultraviolet (UV) Index readings in a given zip code.
     *
     * @param Temboo_Session $session The session that owns this HourlyUVByZipCode choreography.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/EnviroFacts/UVForecast/HourlyUVByZipCode/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this HourlyUVByZipCode choreography.
     *
     * @param EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs|array $inputs (optional) Inputs as EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new EnviroFacts_UVForecast_HourlyUVByZipCode_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this HourlyUVByZipCode choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the HourlyUVByZipCode choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the HourlyUVByZipCode choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this HourlyUVByZipCode input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ResponseType input for this HourlyUVByZipCode choreography.
     *
     * @param string $value (optional, string) Results can be retrieved in either JSON or XML. Defaults to XML.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs For method chaining.
     */
    public function setResponseType($value)
    {
        return $this->set('ResponseType', $value);
    }

    /**
     * Set the value for the Zip input for this HourlyUVByZipCode choreography.
     *
     * @param int $value (required, integer) A valid United States Postal Service (USPS) ZIP Code or Postal Code.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs For method chaining.
     */
    public function setZip($value)
    {
        return $this->set('Zip', $value);
    }
}


/**
 * Execution object for the HourlyUVByZipCode choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_HourlyUVByZipCode_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the HourlyUVByZipCode choreography.
     *
     * @param Temboo_Session $session The session that owns this HourlyUVByZipCode execution.
     * @param EnviroFacts_UVForecast_HourlyUVByZipCode $choreo The choregraphy object for this execution.
     * @param EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs|array $inputs (optional) Inputs as EnviroFacts_UVForecast_HourlyUVByZipCode_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, EnviroFacts_UVForecast_HourlyUVByZipCode $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this HourlyUVByZipCode execution.
     *
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Results New results object.
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
     * Wraps results in appopriate results class for this HourlyUVByZipCode execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new EnviroFacts_UVForecast_HourlyUVByZipCode_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the HourlyUVByZipCode choreography.
 *
 * @package Temboo
 * @subpackage EnviroFacts
 */
class EnviroFacts_UVForecast_HourlyUVByZipCode_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the HourlyUVByZipCode choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return EnviroFacts_UVForecast_HourlyUVByZipCode_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this HourlyUVByZipCode execution.
     *
     * @return string The response from EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

?>