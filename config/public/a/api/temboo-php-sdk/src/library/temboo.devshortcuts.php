<?php

/**
 * Temboo PHP SDK DevShortcuts classes
 *
 * Execute Choreographies from the Temboo DevShortcuts bundle.
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
 * @subpackage DevShortcuts
 * @author     Temboo, Inc.
 * @copyright  2012 Temboo, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @version    1.7
 * @link       http://live.temboo.com/sdk/php
 */


/**
 * Converts Excel (.xls) formatted data to CSV.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertExcelToCSV extends Temboo_Choreography
{
    /**
     * Converts Excel (.xls) formatted data to CSV.
     *
     * @param Temboo_Session $session The session that owns this ConvertExcelToCSV choreography.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/DataConversions/ConvertExcelToCSV/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ConvertExcelToCSV choreography.
     *
     * @param DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_DataConversions_ConvertExcelToCSV_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ConvertExcelToCSV choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ConvertExcelToCSV choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ConvertExcelToCSV choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ConvertExcelToCSV input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ExcelFile input for this ConvertExcelToCSV choreography.
     *
     * @param string $value (conditional, string) The base64-encoded contents of the Excel file that you want to convert to CSV. Required unless using the VaultFile input alias (an advanced option used when running Choreos in the Temboo Designer).
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs For method chaining.
     */
    public function setExcelFile($value)
    {
        return $this->set('ExcelFile', $value);
    }

}


/**
 * Execution object for the ConvertExcelToCSV choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertExcelToCSV_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ConvertExcelToCSV choreography.
     *
     * @param Temboo_Session $session The session that owns this ConvertExcelToCSV execution.
     * @param DevShortcuts_DataConversions_ConvertExcelToCSV $choreo The choregraphy object for this execution.
     * @param DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertExcelToCSV_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_DataConversions_ConvertExcelToCSV $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ConvertExcelToCSV execution.
     *
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Results New results object.
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
     * Wraps results in appopriate results class for this ConvertExcelToCSV execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_DataConversions_ConvertExcelToCSV_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ConvertExcelToCSV choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertExcelToCSV_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ConvertExcelToCSV choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertExcelToCSV_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "CSVFile" output from this ConvertExcelToCSV execution.
     *
     * @return string (string) The CSV formatted data.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getCSVFile()
    {
        return $this->get('CSVFile');
    }
}

/**
 * Converts data from XML format to a JSON format.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertXMLToJSON extends Temboo_Choreography
{
    /**
     * Converts data from XML format to a JSON format.
     *
     * @param Temboo_Session $session The session that owns this ConvertXMLToJSON choreography.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/DataConversions/ConvertXMLToJSON/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ConvertXMLToJSON choreography.
     *
     * @param DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_DataConversions_ConvertXMLToJSON_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ConvertXMLToJSON choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ConvertXMLToJSON choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ConvertXMLToJSON choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ConvertXMLToJSON input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the XML input for this ConvertXMLToJSON choreography.
     *
     * @param string $value (required, xml) The XML file that you want to convert to JSON format.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs For method chaining.
     */
    public function setXML($value)
    {
        return $this->set('XML', $value);
    }
}


/**
 * Execution object for the ConvertXMLToJSON choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertXMLToJSON_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ConvertXMLToJSON choreography.
     *
     * @param Temboo_Session $session The session that owns this ConvertXMLToJSON execution.
     * @param DevShortcuts_DataConversions_ConvertXMLToJSON $choreo The choregraphy object for this execution.
     * @param DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertXMLToJSON_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_DataConversions_ConvertXMLToJSON $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ConvertXMLToJSON execution.
     *
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Results New results object.
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
     * Wraps results in appopriate results class for this ConvertXMLToJSON execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_DataConversions_ConvertXMLToJSON_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ConvertXMLToJSON choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertXMLToJSON_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ConvertXMLToJSON choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertXMLToJSON_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "JSON" output from this ConvertXMLToJSON execution.
     *
     * @return string (json) The converted data in JSON format.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getJSON()
    {
        return $this->get('JSON');
    }
}

/**
 * Retrieves weather and UV index data for a given Geo point using the Yahoo Weather, NOAA, and EnviroFacts APIs.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByAddress extends Temboo_Choreography
{
    /**
     * Retrieves weather and UV index data for a given Geo point using the Yahoo Weather, NOAA, and EnviroFacts APIs.
     *
     * @param Temboo_Session $session The session that owns this ByAddress choreography.
     * @return DevShortcuts_Labs_GetWeather_ByAddress New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GetWeather/ByAddress/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ByAddress choreography.
     *
     * @param DevShortcuts_Labs_GetWeather_ByAddress_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetWeather_ByAddress_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GetWeather_ByAddress_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ByAddress choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GetWeather_ByAddress_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ByAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByAddress_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ByAddress choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ByAddress input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this ByAddress choreography.
     *
     * @param string $value (required, json) A JSON dictionary containing a Yahoo App ID. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Address input for this ByAddress choreography.
     *
     * @param string $value (required, string) The street address of the location to get weather for.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Inputs For method chaining.
     */
    public function setAddress($value)
    {
        return $this->set('Address', $value);
    }
}


/**
 * Execution object for the ByAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByAddress_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ByAddress choreography.
     *
     * @param Temboo_Session $session The session that owns this ByAddress execution.
     * @param DevShortcuts_Labs_GetWeather_ByAddress $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GetWeather_ByAddress_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetWeather_ByAddress_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GetWeather_ByAddress $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ByAddress execution.
     *
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Results New results object.
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
     * Wraps results in appopriate results class for this ByAddress execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GetWeather_ByAddress_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ByAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByAddress_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ByAddress choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GetWeather_ByAddress_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ByAddress execution.
     *
     * @return string (json) Contains combined weather data from Yahoo Weather, NOAA, and EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Verifies that a given zip code matches the format expected for Canadian addresses.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_CanadianPostalCodes extends Temboo_Choreography
{
    /**
     * Verifies that a given zip code matches the format expected for Canadian addresses.
     *
     * @param Temboo_Session $session The session that owns this CanadianPostalCodes choreography.
     * @return DevShortcuts_Validation_CanadianPostalCodes New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/CanadianPostalCodes/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CanadianPostalCodes choreography.
     *
     * @param DevShortcuts_Validation_CanadianPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_CanadianPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_CanadianPostalCodes_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CanadianPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_CanadianPostalCodes_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CanadianPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_CanadianPostalCodes_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CanadianPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CanadianPostalCodes input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ZipCode input for this CanadianPostalCodes choreography.
     *
     * @param string $value (required, string) The zip code to validate.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Inputs For method chaining.
     */
    public function setZipCode($value)
    {
        return $this->set('ZipCode', $value);
    }
}


/**
 * Execution object for the CanadianPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_CanadianPostalCodes_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CanadianPostalCodes choreography.
     *
     * @param Temboo_Session $session The session that owns this CanadianPostalCodes execution.
     * @param DevShortcuts_Validation_CanadianPostalCodes $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_CanadianPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_CanadianPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_CanadianPostalCodes $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CanadianPostalCodes execution.
     *
     * @return DevShortcuts_Validation_CanadianPostalCodes_Results New results object.
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
     * Wraps results in appopriate results class for this CanadianPostalCodes execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_CanadianPostalCodes_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CanadianPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_CanadianPostalCodes_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CanadianPostalCodes choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_CanadianPostalCodes_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Match" output from this CanadianPostalCodes execution.
     *
     * @return string (string) Contains a string indicating the result of the match -- "valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getMatch()
    {
        return $this->get('Match');
    }
}

/**
 * Converts Excel (.xls) formatted data to XML.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertExcelToXML extends Temboo_Choreography
{
    /**
     * Converts Excel (.xls) formatted data to XML.
     *
     * @param Temboo_Session $session The session that owns this ConvertExcelToXML choreography.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/DataConversions/ConvertExcelToXML/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ConvertExcelToXML choreography.
     *
     * @param DevShortcuts_DataConversions_ConvertExcelToXML_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertExcelToXML_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_DataConversions_ConvertExcelToXML_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ConvertExcelToXML choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_DataConversions_ConvertExcelToXML_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ConvertExcelToXML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertExcelToXML_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ConvertExcelToXML choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ConvertExcelToXML input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ExcelFile input for this ConvertExcelToXML choreography.
     *
     * @param string $value (conditional, string) The base64-encoded contents of the Excel file that you want to convert to CSV. Required unless using the VaultFile input alias (an advanced option used when running Choreos in the Temboo Designer).
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Inputs For method chaining.
     */
    public function setExcelFile($value)
    {
        return $this->set('ExcelFile', $value);
    }

}


/**
 * Execution object for the ConvertExcelToXML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertExcelToXML_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ConvertExcelToXML choreography.
     *
     * @param Temboo_Session $session The session that owns this ConvertExcelToXML execution.
     * @param DevShortcuts_DataConversions_ConvertExcelToXML $choreo The choregraphy object for this execution.
     * @param DevShortcuts_DataConversions_ConvertExcelToXML_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertExcelToXML_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_DataConversions_ConvertExcelToXML $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ConvertExcelToXML execution.
     *
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Results New results object.
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
     * Wraps results in appopriate results class for this ConvertExcelToXML execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_DataConversions_ConvertExcelToXML_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ConvertExcelToXML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertExcelToXML_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ConvertExcelToXML choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertExcelToXML_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "XMLFile" output from this ConvertExcelToXML execution.
     *
     * @return string (xml) The data in XML format.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getXMLFile()
    {
        return $this->get('XMLFile');
    }
}

/**
 * Retrieves weather and UV index data based on coordinates returned from a Foursquare recent check-in.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByFoursquare extends Temboo_Choreography
{
    /**
     * Retrieves weather and UV index data based on coordinates returned from a Foursquare recent check-in.
     *
     * @param Temboo_Session $session The session that owns this ByFoursquare choreography.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GetWeather/ByFoursquare/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ByFoursquare choreography.
     *
     * @param DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GetWeather_ByFoursquare_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ByFoursquare choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ByFoursquare choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ByFoursquare choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ByFoursquare input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this ByFoursquare choreography.
     *
     * @param string $value (required, json) A JSON dictionary containing your Foursquare and Yahoo credentials. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Shout input for this ByFoursquare choreography.
     *
     * @param string $value (optional, string) A message about your check-in. The maximum length of this field is 140 characters.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs For method chaining.
     */
    public function setShout($value)
    {
        return $this->set('Shout', $value);
    }

    /**
     * Set the value for the VenueID input for this ByFoursquare choreography.
     *
     * @param string $value (optional, string) The venue where the user is checking in. Required if creating a Foursquare checkin.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs For method chaining.
     */
    public function setVenueID($value)
    {
        return $this->set('VenueID', $value);
    }
}


/**
 * Execution object for the ByFoursquare choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByFoursquare_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ByFoursquare choreography.
     *
     * @param Temboo_Session $session The session that owns this ByFoursquare execution.
     * @param DevShortcuts_Labs_GetWeather_ByFoursquare $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetWeather_ByFoursquare_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GetWeather_ByFoursquare $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ByFoursquare execution.
     *
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Results New results object.
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
     * Wraps results in appopriate results class for this ByFoursquare execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GetWeather_ByFoursquare_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ByFoursquare choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByFoursquare_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ByFoursquare choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GetWeather_ByFoursquare_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ByFoursquare execution.
     *
     * @return string (json) Contains weather information based on the coordinates returned from the Foursquare checkin.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Verifies that a given email address matches an expected standard pattern.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_EmailAddress extends Temboo_Choreography
{
    /**
     * Verifies that a given email address matches an expected standard pattern.
     *
     * @param Temboo_Session $session The session that owns this EmailAddress choreography.
     * @return DevShortcuts_Validation_EmailAddress New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/EmailAddress/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this EmailAddress choreography.
     *
     * @param DevShortcuts_Validation_EmailAddress_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_EmailAddress_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_EmailAddress_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_EmailAddress_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this EmailAddress choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_EmailAddress_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_EmailAddress_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the EmailAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_EmailAddress_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the EmailAddress choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_EmailAddress_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this EmailAddress input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_EmailAddress_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_EmailAddress_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the EmailAddress input for this EmailAddress choreography.
     *
     * @param string $value (required, string) The email address to validate.
     * @return DevShortcuts_Validation_EmailAddress_Inputs For method chaining.
     */
    public function setEmailAddress($value)
    {
        return $this->set('EmailAddress', $value);
    }
}


/**
 * Execution object for the EmailAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_EmailAddress_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the EmailAddress choreography.
     *
     * @param Temboo_Session $session The session that owns this EmailAddress execution.
     * @param DevShortcuts_Validation_EmailAddress $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_EmailAddress_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_EmailAddress_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_EmailAddress_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_EmailAddress $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this EmailAddress execution.
     *
     * @return DevShortcuts_Validation_EmailAddress_Results New results object.
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
     * Wraps results in appopriate results class for this EmailAddress execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_EmailAddress_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_EmailAddress_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the EmailAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_EmailAddress_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the EmailAddress choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_EmailAddress_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Match" output from this EmailAddress execution.
     *
     * @return string (string) Contains a string indicating the result of the match -- "valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getMatch()
    {
        return $this->get('Match');
    }
}

/**
 * Retrieves your social contacts from multiple APIs in one API call.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_GetContacts extends Temboo_Choreography
{
    /**
     * Retrieves your social contacts from multiple APIs in one API call.
     *
     * @param Temboo_Session $session The session that owns this GetContacts choreography.
     * @return DevShortcuts_Labs_Social_GetContacts New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/Social/GetContacts/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this GetContacts choreography.
     *
     * @param DevShortcuts_Labs_Social_GetContacts_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_Social_GetContacts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_Social_GetContacts_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_Social_GetContacts_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this GetContacts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_Social_GetContacts_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_Social_GetContacts_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the GetContacts choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_GetContacts_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the GetContacts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_Social_GetContacts_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this GetContacts input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_Social_GetContacts_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_Social_GetContacts_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this GetContacts choreography.
     *
     * @param string $value (conditional, json) A list of credentials for the APIs you wish to access. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_Social_GetContacts_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the ScreenName input for this GetContacts choreography.
     *
     * @param string $value (conditional, string) The Twitter screen name to retrieve followers for.
     * @return DevShortcuts_Labs_Social_GetContacts_Inputs For method chaining.
     */
    public function setScreenName($value)
    {
        return $this->set('ScreenName', $value);
    }
}


/**
 * Execution object for the GetContacts choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_GetContacts_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the GetContacts choreography.
     *
     * @param Temboo_Session $session The session that owns this GetContacts execution.
     * @param DevShortcuts_Labs_Social_GetContacts $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_Social_GetContacts_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_Social_GetContacts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_Social_GetContacts_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_Social_GetContacts $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this GetContacts execution.
     *
     * @return DevShortcuts_Labs_Social_GetContacts_Results New results object.
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
     * Wraps results in appopriate results class for this GetContacts execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_Social_GetContacts_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_Social_GetContacts_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the GetContacts choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_GetContacts_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the GetContacts choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_Social_GetContacts_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this GetContacts execution.
     *
     * @return string (json) Contains the merged results from the API responses.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Verifies that a given zip code matches the format expected for US addresses.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_USPostalCodes extends Temboo_Choreography
{
    /**
     * Verifies that a given zip code matches the format expected for US addresses.
     *
     * @param Temboo_Session $session The session that owns this USPostalCodes choreography.
     * @return DevShortcuts_Validation_USPostalCodes New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/USPostalCodes/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this USPostalCodes choreography.
     *
     * @param DevShortcuts_Validation_USPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_USPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_USPostalCodes_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_USPostalCodes_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this USPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_USPostalCodes_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_USPostalCodes_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the USPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_USPostalCodes_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the USPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_USPostalCodes_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this USPostalCodes input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_USPostalCodes_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_USPostalCodes_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ZipCode input for this USPostalCodes choreography.
     *
     * @param string $value (required, string) The zip code to validate.
     * @return DevShortcuts_Validation_USPostalCodes_Inputs For method chaining.
     */
    public function setZipCode($value)
    {
        return $this->set('ZipCode', $value);
    }
}


/**
 * Execution object for the USPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_USPostalCodes_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the USPostalCodes choreography.
     *
     * @param Temboo_Session $session The session that owns this USPostalCodes execution.
     * @param DevShortcuts_Validation_USPostalCodes $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_USPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_USPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_USPostalCodes_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_USPostalCodes $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this USPostalCodes execution.
     *
     * @return DevShortcuts_Validation_USPostalCodes_Results New results object.
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
     * Wraps results in appopriate results class for this USPostalCodes execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_USPostalCodes_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_USPostalCodes_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the USPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_USPostalCodes_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the USPostalCodes choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_USPostalCodes_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Match" output from this USPostalCodes execution.
     *
     * @return string (string) Contains a string indicating the result of the match --"valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getMatch()
    {
        return $this->get('Match');
    }
}

/**
 * Retrieves weather and UV index data for a given Geo point using the Yahoo Weather, NOAA, and EnviroFacts APIs.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByCoordinates extends Temboo_Choreography
{
    /**
     * Retrieves weather and UV index data for a given Geo point using the Yahoo Weather, NOAA, and EnviroFacts APIs.
     *
     * @param Temboo_Session $session The session that owns this ByCoordinates choreography.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GetWeather/ByCoordinates/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ByCoordinates choreography.
     *
     * @param DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GetWeather_ByCoordinates_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ByCoordinates choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ByCoordinates choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ByCoordinates input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this ByCoordinates choreography.
     *
     * @param string $value (required, json) A JSON dictionary containing a Yahoo App ID. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Latitude input for this ByCoordinates choreography.
     *
     * @param float $value (required, decimal) The latitude coordinate to use when looking up weather information.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs For method chaining.
     */
    public function setLatitude($value)
    {
        return $this->set('Latitude', $value);
    }

    /**
     * Set the value for the Longitude input for this ByCoordinates choreography.
     *
     * @param float $value (required, decimal) The longitude coordinate to use when looking up weather information.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs For method chaining.
     */
    public function setLongitude($value)
    {
        return $this->set('Longitude', $value);
    }
}


/**
 * Execution object for the ByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByCoordinates_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ByCoordinates choreography.
     *
     * @param Temboo_Session $session The session that owns this ByCoordinates execution.
     * @param DevShortcuts_Labs_GetWeather_ByCoordinates $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetWeather_ByCoordinates_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GetWeather_ByCoordinates $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ByCoordinates execution.
     *
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Results New results object.
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
     * Wraps results in appopriate results class for this ByCoordinates execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GetWeather_ByCoordinates_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByCoordinates_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ByCoordinates choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GetWeather_ByCoordinates_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ByCoordinates execution.
     *
     * @return string (json) Contains combined weather data from Yahoo Weather, NOAA, and EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Converts a CSV formatted file to Base64 encoded Excel data.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel extends Temboo_Choreography
{
    /**
     * Converts a CSV formatted file to Base64 encoded Excel data.
     *
     * @param Temboo_Session $session The session that owns this ConvertCSVToBase64EncodedExcel choreography.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/DataConversions/ConvertCSVToBase64EncodedExcel/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ConvertCSVToBase64EncodedExcel choreography.
     *
     * @param DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ConvertCSVToBase64EncodedExcel choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ConvertCSVToBase64EncodedExcel choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ConvertCSVToBase64EncodedExcel choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ConvertCSVToBase64EncodedExcel input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the CSVFile input for this ConvertCSVToBase64EncodedExcel choreography.
     *
     * @param string $value (conditional, multiline) The CSV data you want to convert to XLS format. Required unless using the VaultFile input alias (an advanced option used when running Choreos in the Temboo Designer).
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs For method chaining.
     */
    public function setCSVFile($value)
    {
        return $this->set('CSVFile', $value);
    }

}


/**
 * Execution object for the ConvertCSVToBase64EncodedExcel choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ConvertCSVToBase64EncodedExcel choreography.
     *
     * @param Temboo_Session $session The session that owns this ConvertCSVToBase64EncodedExcel execution.
     * @param DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel $choreo The choregraphy object for this execution.
     * @param DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ConvertCSVToBase64EncodedExcel execution.
     *
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Results New results object.
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
     * Wraps results in appopriate results class for this ConvertCSVToBase64EncodedExcel execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ConvertCSVToBase64EncodedExcel choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ConvertCSVToBase64EncodedExcel choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertCSVToBase64EncodedExcel_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "ExcelFile" output from this ConvertCSVToBase64EncodedExcel execution.
     *
     * @return string (string) The Base64 encoded Excel data.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getExcelFile()
    {
        return $this->get('ExcelFile');
    }
}

/**
 * Verifies that a given zip code matches the format expected for UK addresses.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_UKPostalCodes extends Temboo_Choreography
{
    /**
     * Verifies that a given zip code matches the format expected for UK addresses.
     *
     * @param Temboo_Session $session The session that owns this UKPostalCodes choreography.
     * @return DevShortcuts_Validation_UKPostalCodes New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/UKPostalCodes/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UKPostalCodes choreography.
     *
     * @param DevShortcuts_Validation_UKPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_UKPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_UKPostalCodes_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_UKPostalCodes_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UKPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_UKPostalCodes_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_UKPostalCodes_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UKPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_UKPostalCodes_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UKPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_UKPostalCodes_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UKPostalCodes input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_UKPostalCodes_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_UKPostalCodes_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ZipCode input for this UKPostalCodes choreography.
     *
     * @param string $value (required, string) The zip code to validate.
     * @return DevShortcuts_Validation_UKPostalCodes_Inputs For method chaining.
     */
    public function setZipCode($value)
    {
        return $this->set('ZipCode', $value);
    }
}


/**
 * Execution object for the UKPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_UKPostalCodes_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UKPostalCodes choreography.
     *
     * @param Temboo_Session $session The session that owns this UKPostalCodes execution.
     * @param DevShortcuts_Validation_UKPostalCodes $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_UKPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_UKPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_UKPostalCodes_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_UKPostalCodes $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UKPostalCodes execution.
     *
     * @return DevShortcuts_Validation_UKPostalCodes_Results New results object.
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
     * Wraps results in appopriate results class for this UKPostalCodes execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_UKPostalCodes_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_UKPostalCodes_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UKPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_UKPostalCodes_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UKPostalCodes choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_UKPostalCodes_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Match" output from this UKPostalCodes execution.
     *
     * @return string (string) Contains a string indicating the result of the match -- "valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getMatch()
    {
        return $this->get('Match');
    }
}

/**
 * Searches Foursquare recent check-ins and the Facebook social graph with a given set of Geo coordinates
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_GetNearbyContacts extends Temboo_Choreography
{
    /**
     * Searches Foursquare recent check-ins and the Facebook social graph with a given set of Geo coordinates
     *
     * @param Temboo_Session $session The session that owns this GetNearbyContacts choreography.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/Social/GetNearbyContacts/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this GetNearbyContacts choreography.
     *
     * @param DevShortcuts_Labs_Social_GetNearbyContacts_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_Social_GetNearbyContacts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_Social_GetNearbyContacts_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this GetNearbyContacts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_Social_GetNearbyContacts_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the GetNearbyContacts choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_GetNearbyContacts_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the GetNearbyContacts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this GetNearbyContacts input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this GetNearbyContacts choreography.
     *
     * @param string $value (required, json) A JSON dictionary containing Facebook and Foursquare credentials.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the AfterTimestamp input for this GetNearbyContacts choreography.
     *
     * @param string $value (optional, date) Seconds after which to look for checkins, e.g. for looking for new checkins since the last fetch.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs For method chaining.
     */
    public function setAfterTimestamp($value)
    {
        return $this->set('AfterTimestamp', $value);
    }

    /**
     * Set the value for the Latitude input for this GetNearbyContacts choreography.
     *
     * @param float $value (required, decimal) The latitude coordinate of the location to search for.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs For method chaining.
     */
    public function setLatitude($value)
    {
        return $this->set('Latitude', $value);
    }

    /**
     * Set the value for the Limit input for this GetNearbyContacts choreography.
     *
     * @param int $value (optional, integer) Used to page through results. Limits the number of records returned in the API responses.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Longitude input for this GetNearbyContacts choreography.
     *
     * @param float $value (conditional, decimal) The longitude coordinate of the location to search for.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs For method chaining.
     */
    public function setLongitude($value)
    {
        return $this->set('Longitude', $value);
    }

    /**
     * Set the value for the Offset input for this GetNearbyContacts choreography.
     *
     * @param int $value (optional, integer) Used to page through Facebook results. Returns results starting from the specified number.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Inputs For method chaining.
     */
    public function setOffset($value)
    {
        return $this->set('Offset', $value);
    }
}


/**
 * Execution object for the GetNearbyContacts choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_GetNearbyContacts_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the GetNearbyContacts choreography.
     *
     * @param Temboo_Session $session The session that owns this GetNearbyContacts execution.
     * @param DevShortcuts_Labs_Social_GetNearbyContacts $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_Social_GetNearbyContacts_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_Social_GetNearbyContacts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_Social_GetNearbyContacts $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this GetNearbyContacts execution.
     *
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Results New results object.
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
     * Wraps results in appopriate results class for this GetNearbyContacts execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_Social_GetNearbyContacts_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the GetNearbyContacts choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_GetNearbyContacts_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the GetNearbyContacts choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_Social_GetNearbyContacts_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this GetNearbyContacts execution.
     *
     * @return string (json) A merged result of Foursquare and Facebook location based searches.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Sends an email using a specified email server.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Email_SendEmail extends Temboo_Choreography
{
    /**
     * Sends an email using a specified email server.
     *
     * @param Temboo_Session $session The session that owns this SendEmail choreography.
     * @return DevShortcuts_Email_SendEmail New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Email/SendEmail/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SendEmail choreography.
     *
     * @param DevShortcuts_Email_SendEmail_Inputs|array $inputs (optional) Inputs as DevShortcuts_Email_SendEmail_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Email_SendEmail_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Email_SendEmail_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SendEmail choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Email_SendEmail_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Email_SendEmail_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SendEmail choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Email_SendEmail_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SendEmail choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Email_SendEmail_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SendEmail input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AttachmentName input for this SendEmail choreography.
     *
     * @param string $value (optional, string) The name of the file to attach to the email.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setAttachmentName($value)
    {
        return $this->set('AttachmentName', $value);
    }

    /**
     * Set the value for the Attachment input for this SendEmail choreography.
     *
     * @param string $value (optional, string) The Base64 encoded contents of the file to attach to the email.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setAttachment($value)
    {
        return $this->set('Attachment', $value);
    }

    /**
     * Set the value for the BCC input for this SendEmail choreography.
     *
     * @param string $value (optional, string) An email address to BCC on the email you're sending. Can be a comma separated list of email addresses.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setBCC($value)
    {
        return $this->set('BCC', $value);
    }

    /**
     * Set the value for the CC input for this SendEmail choreography.
     *
     * @param string $value (optional, string) An email address to CC on the email you're sending. Can be a comma separated list of email addresses.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setCC($value)
    {
        return $this->set('CC', $value);
    }

    /**
     * Set the value for the MessageBody input for this SendEmail choreography.
     *
     * @param string $value (required, string) The message body for the email.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setMessageBody($value)
    {
        return $this->set('MessageBody', $value);
    }

    /**
     * Set the value for the Password input for this SendEmail choreography.
     *
     * @param string $value (required, password) The password for your email account.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Port input for this SendEmail choreography.
     *
     * @param int $value (required, integer) Specify the port number (i.e. 25 or 465).
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setPort($value)
    {
        return $this->set('Port', $value);
    }

    /**
     * Set the value for the Server input for this SendEmail choreography.
     *
     * @param string $value (required, string) The name or IP address of the email server.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the Subject input for this SendEmail choreography.
     *
     * @param string $value (required, string) The subject line of the email.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setSubject($value)
    {
        return $this->set('Subject', $value);
    }

    /**
     * Set the value for the ToAddress input for this SendEmail choreography.
     *
     * @param string $value (required, string) The email address that you want to send an email to. Can be a comma separated list of email addresses.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setToAddress($value)
    {
        return $this->set('ToAddress', $value);
    }

    /**
     * Set the value for the UseSSL input for this SendEmail choreography.
     *
     * @param bool $value (optional, boolean) Set to 1 to connect over SSL. Set to 0 for no SSL. Defaults to 1.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setUseSSL($value)
    {
        return $this->set('UseSSL', $value);
    }

    /**
     * Set the value for the Username input for this SendEmail choreography.
     *
     * @param string $value (required, string) Your username for your email account. Note, this will used to authenticate your account and as the From Address for the email you are sending.
     * @return DevShortcuts_Email_SendEmail_Inputs For method chaining.
     */
    public function setUsername($value)
    {
        return $this->set('Username', $value);
    }
}


/**
 * Execution object for the SendEmail choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Email_SendEmail_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SendEmail choreography.
     *
     * @param Temboo_Session $session The session that owns this SendEmail execution.
     * @param DevShortcuts_Email_SendEmail $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Email_SendEmail_Inputs|array $inputs (optional) Inputs as DevShortcuts_Email_SendEmail_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Email_SendEmail_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Email_SendEmail $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SendEmail execution.
     *
     * @return DevShortcuts_Email_SendEmail_Results New results object.
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
     * Wraps results in appopriate results class for this SendEmail execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Email_SendEmail_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Email_SendEmail_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SendEmail choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Email_SendEmail_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SendEmail choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Email_SendEmail_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }
}

/**
 * Verifies that a given zip code matches the format expected for Russian addresses.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_RussianPostalCodes extends Temboo_Choreography
{
    /**
     * Verifies that a given zip code matches the format expected for Russian addresses.
     *
     * @param Temboo_Session $session The session that owns this RussianPostalCodes choreography.
     * @return DevShortcuts_Validation_RussianPostalCodes New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/RussianPostalCodes/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this RussianPostalCodes choreography.
     *
     * @param DevShortcuts_Validation_RussianPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_RussianPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_RussianPostalCodes_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_RussianPostalCodes_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this RussianPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_RussianPostalCodes_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_RussianPostalCodes_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the RussianPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_RussianPostalCodes_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the RussianPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_RussianPostalCodes_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this RussianPostalCodes input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_RussianPostalCodes_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_RussianPostalCodes_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ZipCode input for this RussianPostalCodes choreography.
     *
     * @param string $value (required, string) The zip code to validate.
     * @return DevShortcuts_Validation_RussianPostalCodes_Inputs For method chaining.
     */
    public function setZipCode($value)
    {
        return $this->set('ZipCode', $value);
    }
}


/**
 * Execution object for the RussianPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_RussianPostalCodes_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the RussianPostalCodes choreography.
     *
     * @param Temboo_Session $session The session that owns this RussianPostalCodes execution.
     * @param DevShortcuts_Validation_RussianPostalCodes $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_RussianPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_RussianPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_RussianPostalCodes_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_RussianPostalCodes $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this RussianPostalCodes execution.
     *
     * @return DevShortcuts_Validation_RussianPostalCodes_Results New results object.
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
     * Wraps results in appopriate results class for this RussianPostalCodes execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_RussianPostalCodes_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_RussianPostalCodes_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the RussianPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_RussianPostalCodes_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the RussianPostalCodes choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_RussianPostalCodes_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Match" output from this RussianPostalCodes execution.
     *
     * @return string (string) Contains a string indicating the result of the match -- "valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getMatch()
    {
        return $this->get('Match');
    }
}

/**
 * Returns a host of eco-conscious environmental information for a specified location based on zip code.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_EcoByZip extends Temboo_Choreography
{
    /**
     * Returns a host of eco-conscious environmental information for a specified location based on zip code.
     *
     * @param Temboo_Session $session The session that owns this EcoByZip choreography.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GoodCitizen/EcoByZip/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this EcoByZip choreography.
     *
     * @param DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GoodCitizen_EcoByZip_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this EcoByZip choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the EcoByZip choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the EcoByZip choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this EcoByZip input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this EcoByZip choreography.
     *
     * @param string $value (optional, string) A JSON dictionary containing credentials for Genability. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Limit input for this EcoByZip choreography.
     *
     * @param int $value (optional, integer) The number of facility records to search for in the Envirofacts database.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Zip input for this EcoByZip choreography.
     *
     * @param int $value (required, integer) The zip code for the user's current location.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs For method chaining.
     */
    public function setZip($value)
    {
        return $this->set('Zip', $value);
    }
}


/**
 * Execution object for the EcoByZip choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_EcoByZip_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the EcoByZip choreography.
     *
     * @param Temboo_Session $session The session that owns this EcoByZip execution.
     * @param DevShortcuts_Labs_GoodCitizen_EcoByZip $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GoodCitizen_EcoByZip_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GoodCitizen_EcoByZip $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this EcoByZip execution.
     *
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Results New results object.
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
     * Wraps results in appopriate results class for this EcoByZip execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GoodCitizen_EcoByZip_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the EcoByZip choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_EcoByZip_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the EcoByZip choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByZip_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this EcoByZip execution.
     *
     * @return string (json) The response from the Eco Choreo.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Validates XML for basic well-formedness and allows you to check XML against a specified XSD schema file.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_XML extends Temboo_Choreography
{
    /**
     * Validates XML for basic well-formedness and allows you to check XML against a specified XSD schema file.
     *
     * @param Temboo_Session $session The session that owns this XML choreography.
     * @return DevShortcuts_Validation_XML New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/XML/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this XML choreography.
     *
     * @param DevShortcuts_Validation_XML_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_XML_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_XML_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_XML_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this XML choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_XML_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_XML_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the XML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_XML_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the XML choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_XML_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this XML input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_XML_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_XML_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the XMLFile input for this XML choreography.
     *
     * @param string $value (required, xml) The XML file you want to validate.
     * @return DevShortcuts_Validation_XML_Inputs For method chaining.
     */
    public function setXMLFile($value)
    {
        return $this->set('XMLFile', $value);
    }

    /**
     * Set the value for the XSDFile input for this XML choreography.
     *
     * @param string $value (optional, xml) The XSD file to validate an XML file against.
     * @return DevShortcuts_Validation_XML_Inputs For method chaining.
     */
    public function setXSDFile($value)
    {
        return $this->set('XSDFile', $value);
    }
}


/**
 * Execution object for the XML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_XML_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the XML choreography.
     *
     * @param Temboo_Session $session The session that owns this XML execution.
     * @param DevShortcuts_Validation_XML $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_XML_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_XML_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_XML_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_XML $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this XML execution.
     *
     * @return DevShortcuts_Validation_XML_Results New results object.
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
     * Wraps results in appopriate results class for this XML execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_XML_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_XML_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the XML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_XML_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the XML choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_XML_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Error" output from this XML execution.
     *
     * @return string (string) The error description that is generated if a validation error occurs.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getError()
    {
        return $this->get('Error');
    }

    /**
     * Retrieve the value for the "Result" output from this XML execution.
     *
     * @return string (string) The result of the validation. Returns the string "valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResult()
    {
        return $this->get('Result');
    }
}

/**
 * Shares a post across multiple social networks such as Facebook, Tumblr, and Twitter.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_UpdateAllStatuses extends Temboo_Choreography
{
    /**
     * Shares a post across multiple social networks such as Facebook, Tumblr, and Twitter.
     *
     * @param Temboo_Session $session The session that owns this UpdateAllStatuses choreography.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/Social/UpdateAllStatuses/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UpdateAllStatuses choreography.
     *
     * @param DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_Social_UpdateAllStatuses_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UpdateAllStatuses choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UpdateAllStatuses choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UpdateAllStatuses choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UpdateAllStatuses input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this UpdateAllStatuses choreography.
     *
     * @param string $value (required, string) A list of credentials for the APIs you wish to access. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Message input for this UpdateAllStatuses choreography.
     *
     * @param string $value (required, string) The message to be posted across social networks.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs For method chaining.
     */
    public function setMessage($value)
    {
        return $this->set('Message', $value);
    }
}


/**
 * Execution object for the UpdateAllStatuses choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_UpdateAllStatuses_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UpdateAllStatuses choreography.
     *
     * @param Temboo_Session $session The session that owns this UpdateAllStatuses execution.
     * @param DevShortcuts_Labs_Social_UpdateAllStatuses $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_Social_UpdateAllStatuses_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_Social_UpdateAllStatuses $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UpdateAllStatuses execution.
     *
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Results New results object.
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
     * Wraps results in appopriate results class for this UpdateAllStatuses execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_Social_UpdateAllStatuses_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UpdateAllStatuses choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_Social_UpdateAllStatuses_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UpdateAllStatuses choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_Social_UpdateAllStatuses_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UpdateAllStatuses execution.
     *
     * @return string (json) A list of results for each API.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves information from multiple APIs about places near a set of coordinates retrieved from Google Latitude.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByGoogleLat extends Temboo_Choreography
{
    /**
     * Retrieves information from multiple APIs about places near a set of coordinates retrieved from Google Latitude.
     *
     * @param Temboo_Session $session The session that owns this ByGoogleLat choreography.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GetPlaces/ByGoogleLat/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ByGoogleLat choreography.
     *
     * @param DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GetPlaces_ByGoogleLat_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ByGoogleLat choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ByGoogleLat choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ByGoogleLat choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ByGoogleLat input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this ByGoogleLat choreography.
     *
     * @param string $value (required, json) A JSON dictionary of credentials for the APIs you wish to access. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Limit input for this ByGoogleLat choreography.
     *
     * @param int $value (optional, integer) Limits the number of Foursquare venues returned.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Query input for this ByGoogleLat choreography.
     *
     * @param string $value (optional, string) This keyword input can be used to narrow search results for Google Places and Foursquare.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ByGoogleLat choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are json (the default) and xml.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Type input for this ByGoogleLat choreography.
     *
     * @param string $value (optional, string) Filters results by type of place, such as: bar, dentist, etc. This is used to filter results for Google Places and Yelp.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs For method chaining.
     */
    public function setType($value)
    {
        return $this->set('Type', $value);
    }
}


/**
 * Execution object for the ByGoogleLat choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByGoogleLat_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ByGoogleLat choreography.
     *
     * @param Temboo_Session $session The session that owns this ByGoogleLat execution.
     * @param DevShortcuts_Labs_GetPlaces_ByGoogleLat $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetPlaces_ByGoogleLat_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GetPlaces_ByGoogleLat $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ByGoogleLat execution.
     *
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Results New results object.
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
     * Wraps results in appopriate results class for this ByGoogleLat execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GetPlaces_ByGoogleLat_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ByGoogleLat choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByGoogleLat_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ByGoogleLat choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByGoogleLat_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ByGoogleLat execution.
     *
     * @return string Contains the merged results from the API responses.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Verifies that a given zip code matches the format expected for German addresses.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_GermanPostalCodes extends Temboo_Choreography
{
    /**
     * Verifies that a given zip code matches the format expected for German addresses.
     *
     * @param Temboo_Session $session The session that owns this GermanPostalCodes choreography.
     * @return DevShortcuts_Validation_GermanPostalCodes New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/GermanPostalCodes/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this GermanPostalCodes choreography.
     *
     * @param DevShortcuts_Validation_GermanPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_GermanPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_GermanPostalCodes_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_GermanPostalCodes_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this GermanPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_GermanPostalCodes_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_GermanPostalCodes_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the GermanPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_GermanPostalCodes_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the GermanPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_GermanPostalCodes_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this GermanPostalCodes input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_GermanPostalCodes_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_GermanPostalCodes_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ZipCode input for this GermanPostalCodes choreography.
     *
     * @param string $value (required, string) The zip code to validate.
     * @return DevShortcuts_Validation_GermanPostalCodes_Inputs For method chaining.
     */
    public function setZipCode($value)
    {
        return $this->set('ZipCode', $value);
    }
}


/**
 * Execution object for the GermanPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_GermanPostalCodes_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the GermanPostalCodes choreography.
     *
     * @param Temboo_Session $session The session that owns this GermanPostalCodes execution.
     * @param DevShortcuts_Validation_GermanPostalCodes $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_GermanPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_GermanPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_GermanPostalCodes_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_GermanPostalCodes $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this GermanPostalCodes execution.
     *
     * @return DevShortcuts_Validation_GermanPostalCodes_Results New results object.
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
     * Wraps results in appopriate results class for this GermanPostalCodes execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_GermanPostalCodes_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_GermanPostalCodes_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the GermanPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_GermanPostalCodes_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the GermanPostalCodes choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_GermanPostalCodes_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Match" output from this GermanPostalCodes execution.
     *
     * @return string (string) Contains a string indicating the result of the match -- "valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getMatch()
    {
        return $this->get('Match');
    }
}

/**
 * Verifies that a given password matches a standard pattern for passwords.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_PasswordCriteria extends Temboo_Choreography
{
    /**
     * Verifies that a given password matches a standard pattern for passwords.
     *
     * @param Temboo_Session $session The session that owns this PasswordCriteria choreography.
     * @return DevShortcuts_Validation_PasswordCriteria New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/PasswordCriteria/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this PasswordCriteria choreography.
     *
     * @param DevShortcuts_Validation_PasswordCriteria_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_PasswordCriteria_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_PasswordCriteria_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_PasswordCriteria_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this PasswordCriteria choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_PasswordCriteria_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_PasswordCriteria_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the PasswordCriteria choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_PasswordCriteria_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the PasswordCriteria choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_PasswordCriteria_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this PasswordCriteria input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_PasswordCriteria_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_PasswordCriteria_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the MaxLength input for this PasswordCriteria choreography.
     *
     * @param int $value (optional, integer) The max length you want to allow for the password. Defaults to 14.
     * @return DevShortcuts_Validation_PasswordCriteria_Inputs For method chaining.
     */
    public function setMaxLength($value)
    {
        return $this->set('MaxLength', $value);
    }

    /**
     * Set the value for the MinLength input for this PasswordCriteria choreography.
     *
     * @param int $value (optional, integer) The minimum length you want to allow for the password. Defaults to 6.
     * @return DevShortcuts_Validation_PasswordCriteria_Inputs For method chaining.
     */
    public function setMinLength($value)
    {
        return $this->set('MinLength', $value);
    }

    /**
     * Set the value for the Password input for this PasswordCriteria choreography.
     *
     * @param string $value (required, string) The password to validate.
     * @return DevShortcuts_Validation_PasswordCriteria_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }
}


/**
 * Execution object for the PasswordCriteria choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_PasswordCriteria_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the PasswordCriteria choreography.
     *
     * @param Temboo_Session $session The session that owns this PasswordCriteria execution.
     * @param DevShortcuts_Validation_PasswordCriteria $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_PasswordCriteria_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_PasswordCriteria_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_PasswordCriteria_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_PasswordCriteria $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this PasswordCriteria execution.
     *
     * @return DevShortcuts_Validation_PasswordCriteria_Results New results object.
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
     * Wraps results in appopriate results class for this PasswordCriteria execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_PasswordCriteria_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_PasswordCriteria_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the PasswordCriteria choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_PasswordCriteria_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the PasswordCriteria choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_PasswordCriteria_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Match" output from this PasswordCriteria execution.
     *
     * @return string (string) Contains a string indicating the result of the match -- "valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getMatch()
    {
        return $this->get('Match');
    }
}

/**
 * Verifies that a given zip code matches the format expected for Dutch addresses.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_DutchPostalCodes extends Temboo_Choreography
{
    /**
     * Verifies that a given zip code matches the format expected for Dutch addresses.
     *
     * @param Temboo_Session $session The session that owns this DutchPostalCodes choreography.
     * @return DevShortcuts_Validation_DutchPostalCodes New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Validation/DutchPostalCodes/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DutchPostalCodes choreography.
     *
     * @param DevShortcuts_Validation_DutchPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_DutchPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_DutchPostalCodes_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Validation_DutchPostalCodes_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DutchPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_DutchPostalCodes_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Validation_DutchPostalCodes_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DutchPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_DutchPostalCodes_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DutchPostalCodes choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Validation_DutchPostalCodes_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DutchPostalCodes input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Validation_DutchPostalCodes_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Validation_DutchPostalCodes_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the ZipCode input for this DutchPostalCodes choreography.
     *
     * @param string $value (required, string) The zip code to validate.
     * @return DevShortcuts_Validation_DutchPostalCodes_Inputs For method chaining.
     */
    public function setZipCode($value)
    {
        return $this->set('ZipCode', $value);
    }
}


/**
 * Execution object for the DutchPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_DutchPostalCodes_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DutchPostalCodes choreography.
     *
     * @param Temboo_Session $session The session that owns this DutchPostalCodes execution.
     * @param DevShortcuts_Validation_DutchPostalCodes $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Validation_DutchPostalCodes_Inputs|array $inputs (optional) Inputs as DevShortcuts_Validation_DutchPostalCodes_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Validation_DutchPostalCodes_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Validation_DutchPostalCodes $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DutchPostalCodes execution.
     *
     * @return DevShortcuts_Validation_DutchPostalCodes_Results New results object.
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
     * Wraps results in appopriate results class for this DutchPostalCodes execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Validation_DutchPostalCodes_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Validation_DutchPostalCodes_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DutchPostalCodes choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Validation_DutchPostalCodes_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DutchPostalCodes choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Validation_DutchPostalCodes_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Match" output from this DutchPostalCodes execution.
     *
     * @return string (string) Contains a string indicating the result of the match -- "valid" or "invalid".
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getMatch()
    {
        return $this->get('Match');
    }
}

/**
 * Returns a host of eco-conscious environmental information for a specified location based on Lattitude and Longitude inputs.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_EcoByCoordinates extends Temboo_Choreography
{
    /**
     * Returns a host of eco-conscious environmental information for a specified location based on Lattitude and Longitude inputs.
     *
     * @param Temboo_Session $session The session that owns this EcoByCoordinates choreography.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GoodCitizen/EcoByCoordinates/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this EcoByCoordinates choreography.
     *
     * @param DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this EcoByCoordinates choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the EcoByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the EcoByCoordinates choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this EcoByCoordinates input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this EcoByCoordinates choreography.
     *
     * @param string $value (optional, string) A JSON dictionary containing credentials for Genability. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Latitude input for this EcoByCoordinates choreography.
     *
     * @param float $value (required, decimal) The latitude coordinate for the user's current location.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs For method chaining.
     */
    public function setLatitude($value)
    {
        return $this->set('Latitude', $value);
    }

    /**
     * Set the value for the Limit input for this EcoByCoordinates choreography.
     *
     * @param int $value (optional, integer) The number of facility records to search for in the Envirofacts database.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Longitude input for this EcoByCoordinates choreography.
     *
     * @param float $value (required, decimal) The longitude coordinate for the user's current location.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs For method chaining.
     */
    public function setLongitude($value)
    {
        return $this->set('Longitude', $value);
    }
}


/**
 * Execution object for the EcoByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the EcoByCoordinates choreography.
     *
     * @param Temboo_Session $session The session that owns this EcoByCoordinates execution.
     * @param DevShortcuts_Labs_GoodCitizen_EcoByCoordinates $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GoodCitizen_EcoByCoordinates $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this EcoByCoordinates execution.
     *
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Results New results object.
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
     * Wraps results in appopriate results class for this EcoByCoordinates execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the EcoByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the EcoByCoordinates choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GoodCitizen_EcoByCoordinates_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this EcoByCoordinates execution.
     *
     * @return string (json) The response from the Eco Choreo.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Converts an XML file to a Base64 encoded Excel file.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel extends Temboo_Choreography
{
    /**
     * Converts an XML file to a Base64 encoded Excel file.
     *
     * @param Temboo_Session $session The session that owns this ConvertXMLToBase64EncodedExcel choreography.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/DataConversions/ConvertXMLToBase64EncodedExcel/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ConvertXMLToBase64EncodedExcel choreography.
     *
     * @param DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ConvertXMLToBase64EncodedExcel choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ConvertXMLToBase64EncodedExcel choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ConvertXMLToBase64EncodedExcel choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ConvertXMLToBase64EncodedExcel input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the XMLFile input for this ConvertXMLToBase64EncodedExcel choreography.
     *
     * @param string $value (required, xml) The XML file you want to convert to XLS format. See documentation for information on the required XML schema.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs For method chaining.
     */
    public function setXMLFile($value)
    {
        return $this->set('XMLFile', $value);
    }
}


/**
 * Execution object for the ConvertXMLToBase64EncodedExcel choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ConvertXMLToBase64EncodedExcel choreography.
     *
     * @param Temboo_Session $session The session that owns this ConvertXMLToBase64EncodedExcel execution.
     * @param DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel $choreo The choregraphy object for this execution.
     * @param DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ConvertXMLToBase64EncodedExcel execution.
     *
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Results New results object.
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
     * Wraps results in appopriate results class for this ConvertXMLToBase64EncodedExcel execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ConvertXMLToBase64EncodedExcel choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ConvertXMLToBase64EncodedExcel choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertXMLToBase64EncodedExcel_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "ExcelFile" output from this ConvertXMLToBase64EncodedExcel execution.
     *
     * @return string The Base64 encoded Excel data .
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getExcelFile()
    {
        return $this->get('ExcelFile');
    }
}

/**
 * Retrieves information from multiple APIs about places near a set of coordinates retrieved from Foursquare.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByFoursquare extends Temboo_Choreography
{
    /**
     * Retrieves information from multiple APIs about places near a set of coordinates retrieved from Foursquare.
     *
     * @param Temboo_Session $session The session that owns this ByFoursquare choreography.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GetPlaces/ByFoursquare/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ByFoursquare choreography.
     *
     * @param DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GetPlaces_ByFoursquare_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ByFoursquare choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ByFoursquare choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ByFoursquare choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ByFoursquare input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this ByFoursquare choreography.
     *
     * @param string $value (required, json) A JSON dictionary of credentials for the APIs you wish to access. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Limit input for this ByFoursquare choreography.
     *
     * @param int $value (optional, integer) Limits the number of Foursquare venues returned.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Query input for this ByFoursquare choreography.
     *
     * @param string $value (optional, string) This keyword input can be used to narrow search results for Google Places and Foursquare.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ByFoursquare choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are json (the default) and xml.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Shout input for this ByFoursquare choreography.
     *
     * @param string $value (optional, string) A message about your check-in. The maximum length of this field is 140 characters.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function setShout($value)
    {
        return $this->set('Shout', $value);
    }

    /**
     * Set the value for the Type input for this ByFoursquare choreography.
     *
     * @param string $value (optional, string) Filters results by type of place, such as: bar, dentist, etc. This is used to filter results for Google Places and Yelp.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function setType($value)
    {
        return $this->set('Type', $value);
    }

    /**
     * Set the value for the VenueID input for this ByFoursquare choreography.
     *
     * @param string $value (optional, string) The venue where the user is checking in. Required if creating a Foursquare checkin.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs For method chaining.
     */
    public function setVenueID($value)
    {
        return $this->set('VenueID', $value);
    }
}


/**
 * Execution object for the ByFoursquare choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByFoursquare_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ByFoursquare choreography.
     *
     * @param Temboo_Session $session The session that owns this ByFoursquare execution.
     * @param DevShortcuts_Labs_GetPlaces_ByFoursquare $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetPlaces_ByFoursquare_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GetPlaces_ByFoursquare $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ByFoursquare execution.
     *
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Results New results object.
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
     * Wraps results in appopriate results class for this ByFoursquare execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GetPlaces_ByFoursquare_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ByFoursquare choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByFoursquare_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ByFoursquare choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByFoursquare_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ByFoursquare execution.
     *
     * @return string (json) Contains weather information based on the coordinates returned from the Foursquare checkin.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves weather and UV index data based on coordinates returned from Google Latitude.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByGoogleLat extends Temboo_Choreography
{
    /**
     * Retrieves weather and UV index data based on coordinates returned from Google Latitude.
     *
     * @param Temboo_Session $session The session that owns this ByGoogleLat choreography.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GetWeather/ByGoogleLat/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ByGoogleLat choreography.
     *
     * @param DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GetWeather_ByGoogleLat_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ByGoogleLat choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ByGoogleLat choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ByGoogleLat choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ByGoogleLat input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this ByGoogleLat choreography.
     *
     * @param string $value (required, json) A JSON dictionary containing your Google Latitude and Yahoo credentials. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }
}


/**
 * Execution object for the ByGoogleLat choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByGoogleLat_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ByGoogleLat choreography.
     *
     * @param Temboo_Session $session The session that owns this ByGoogleLat execution.
     * @param DevShortcuts_Labs_GetWeather_ByGoogleLat $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetWeather_ByGoogleLat_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GetWeather_ByGoogleLat $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ByGoogleLat execution.
     *
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Results New results object.
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
     * Wraps results in appopriate results class for this ByGoogleLat execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GetWeather_ByGoogleLat_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ByGoogleLat choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetWeather_ByGoogleLat_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ByGoogleLat choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GetWeather_ByGoogleLat_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ByGoogleLat execution.
     *
     * @return string (json) Contains weather information based on the coordinates returned from the Foursquare checkin.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves information about places near a set of coordinates from multiple sources in one API call.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByCoordinates extends Temboo_Choreography
{
    /**
     * Retrieves information about places near a set of coordinates from multiple sources in one API call.
     *
     * @param Temboo_Session $session The session that owns this ByCoordinates choreography.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GetPlaces/ByCoordinates/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ByCoordinates choreography.
     *
     * @param DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GetPlaces_ByCoordinates_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ByCoordinates choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ByCoordinates choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ByCoordinates input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this ByCoordinates choreography.
     *
     * @param string $value (required, json) A JSON dictionary of credentials for the APIs you wish to access. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Latitude input for this ByCoordinates choreography.
     *
     * @param float $value (required, decimal) The latitude of the user's location.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function setLatitude($value)
    {
        return $this->set('Latitude', $value);
    }

    /**
     * Set the value for the Limit input for this ByCoordinates choreography.
     *
     * @param int $value (optional, integer) Limits the number of Foursquare venue results.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Longitude input for this ByCoordinates choreography.
     *
     * @param float $value (required, decimal) The longitude of the user's location.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function setLongitude($value)
    {
        return $this->set('Longitude', $value);
    }

    /**
     * Set the value for the Query input for this ByCoordinates choreography.
     *
     * @param string $value (optional, string) This keyword input can be used to narrow search results for Google Places and Foursquare.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ByCoordinates choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are json (the default) and xml.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Type input for this ByCoordinates choreography.
     *
     * @param string $value (optional, string) Filters results by type of place, such as: bar, dentist, etc. This is used to filter results for Google Places and Yelp.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs For method chaining.
     */
    public function setType($value)
    {
        return $this->set('Type', $value);
    }
}


/**
 * Execution object for the ByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByCoordinates_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ByCoordinates choreography.
     *
     * @param Temboo_Session $session The session that owns this ByCoordinates execution.
     * @param DevShortcuts_Labs_GetPlaces_ByCoordinates $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetPlaces_ByCoordinates_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GetPlaces_ByCoordinates $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ByCoordinates execution.
     *
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Results New results object.
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
     * Wraps results in appopriate results class for this ByCoordinates execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GetPlaces_ByCoordinates_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ByCoordinates choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByCoordinates_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ByCoordinates choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByCoordinates_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ByCoordinates execution.
     *
     * @return string Contains the merged results from the API responses.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a host of information about the district and representatives of a specified location.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_Civic extends Temboo_Choreography
{
    /**
     * Retrieves a host of information about the district and representatives of a specified location.
     *
     * @param Temboo_Session $session The session that owns this Civic choreography.
     * @return DevShortcuts_Labs_GoodCitizen_Civic New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GoodCitizen/Civic/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Civic choreography.
     *
     * @param DevShortcuts_Labs_GoodCitizen_Civic_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GoodCitizen_Civic_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GoodCitizen_Civic_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Civic choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GoodCitizen_Civic_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Civic choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_Civic_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Civic choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Civic input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this Civic choreography.
     *
     * @param string $value (optional, json) The JSON dictionary for the Sulight Labs credentials required to operate this choreo. LittleSis credentials are optional. See docs for the format of this input.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Latitude input for this Civic choreography.
     *
     * @param float $value (required, decimal) The latitude coordinate of your location.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Inputs For method chaining.
     */
    public function setLatitude($value)
    {
        return $this->set('Latitude', $value);
    }

    /**
     * Set the value for the Limit input for this Civic choreography.
     *
     * @param int $value (optional, integer) Set the number of records to return for the bills, votes, and relationships of each legislator. Defaults to 5.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Longitude input for this Civic choreography.
     *
     * @param float $value (required, decimal) The longitude coordinate of your locaion.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Inputs For method chaining.
     */
    public function setLongitude($value)
    {
        return $this->set('Longitude', $value);
    }
}


/**
 * Execution object for the Civic choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_Civic_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Civic choreography.
     *
     * @param Temboo_Session $session The session that owns this Civic execution.
     * @param DevShortcuts_Labs_GoodCitizen_Civic $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GoodCitizen_Civic_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GoodCitizen_Civic_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GoodCitizen_Civic $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Civic execution.
     *
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Results New results object.
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
     * Wraps results in appopriate results class for this Civic execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GoodCitizen_Civic_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Civic choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GoodCitizen_Civic_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Civic choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GoodCitizen_Civic_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Civic execution.
     *
     * @return string (string) The response from the Civic Choreo.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves information from multiple APIs about places near a specified street address.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByAddress extends Temboo_Choreography
{
    /**
     * Retrieves information from multiple APIs about places near a specified street address.
     *
     * @param Temboo_Session $session The session that owns this ByAddress choreography.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/Labs/GetPlaces/ByAddress/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ByAddress choreography.
     *
     * @param DevShortcuts_Labs_GetPlaces_ByAddress_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetPlaces_ByAddress_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_Labs_GetPlaces_ByAddress_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ByAddress choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_Labs_GetPlaces_ByAddress_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ByAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByAddress_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ByAddress choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ByAddress input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the APICredentials input for this ByAddress choreography.
     *
     * @param string $value (required, json) A JSON dictionary of credentials for the APIs you wish to access. See Choreo documentation for formatting examples.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs For method chaining.
     */
    public function setAPICredentials($value)
    {
        return $this->set('APICredentials', $value);
    }

    /**
     * Set the value for the Address input for this ByAddress choreography.
     *
     * @param string $value (required, string) The street address of the user's location.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs For method chaining.
     */
    public function setAddress($value)
    {
        return $this->set('Address', $value);
    }

    /**
     * Set the value for the Limit input for this ByAddress choreography.
     *
     * @param int $value (optional, integer) Limits the number of Foursquare venues results.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Query input for this ByAddress choreography.
     *
     * @param string $value (optional, string) This keyword input can be used to narrow search results for Google Places and Foursquare.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ByAddress choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are json (the default) and xml.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Type input for this ByAddress choreography.
     *
     * @param string $value (optional, string) Filters results by type of place, such as: bar, dentist, etc. This is used to filter results for Google Places and Yelp.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Inputs For method chaining.
     */
    public function setType($value)
    {
        return $this->set('Type', $value);
    }
}


/**
 * Execution object for the ByAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByAddress_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ByAddress choreography.
     *
     * @param Temboo_Session $session The session that owns this ByAddress execution.
     * @param DevShortcuts_Labs_GetPlaces_ByAddress $choreo The choregraphy object for this execution.
     * @param DevShortcuts_Labs_GetPlaces_ByAddress_Inputs|array $inputs (optional) Inputs as DevShortcuts_Labs_GetPlaces_ByAddress_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_Labs_GetPlaces_ByAddress $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ByAddress execution.
     *
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Results New results object.
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
     * Wraps results in appopriate results class for this ByAddress execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_Labs_GetPlaces_ByAddress_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ByAddress choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_Labs_GetPlaces_ByAddress_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ByAddress choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_Labs_GetPlaces_ByAddress_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ByAddress execution.
     *
     * @return string (json) Contains combined weather data from Yahoo Weather, NOAA, and EnviroFacts.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Converts data from JSON format to a XML format.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertJSONToXML extends Temboo_Choreography
{
    /**
     * Converts data from JSON format to a XML format.
     *
     * @param Temboo_Session $session The session that owns this ConvertJSONToXML choreography.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DevShortcuts/DataConversions/ConvertJSONToXML/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ConvertJSONToXML choreography.
     *
     * @param DevShortcuts_DataConversions_ConvertJSONToXML_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertJSONToXML_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DevShortcuts_DataConversions_ConvertJSONToXML_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ConvertJSONToXML choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DevShortcuts_DataConversions_ConvertJSONToXML_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ConvertJSONToXML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertJSONToXML_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ConvertJSONToXML choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ConvertJSONToXML input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the JSON input for this ConvertJSONToXML choreography.
     *
     * @param string $value (required, json) The JSON data that you want to convert to XML.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Inputs For method chaining.
     */
    public function setJSON($value)
    {
        return $this->set('JSON', $value);
    }
}


/**
 * Execution object for the ConvertJSONToXML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertJSONToXML_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ConvertJSONToXML choreography.
     *
     * @param Temboo_Session $session The session that owns this ConvertJSONToXML execution.
     * @param DevShortcuts_DataConversions_ConvertJSONToXML $choreo The choregraphy object for this execution.
     * @param DevShortcuts_DataConversions_ConvertJSONToXML_Inputs|array $inputs (optional) Inputs as DevShortcuts_DataConversions_ConvertJSONToXML_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DevShortcuts_DataConversions_ConvertJSONToXML $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ConvertJSONToXML execution.
     *
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Results New results object.
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
     * Wraps results in appopriate results class for this ConvertJSONToXML execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DevShortcuts_DataConversions_ConvertJSONToXML_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ConvertJSONToXML choreography.
 *
 * @package Temboo
 * @subpackage DevShortcuts
 */
class DevShortcuts_DataConversions_ConvertJSONToXML_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ConvertJSONToXML choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DevShortcuts_DataConversions_ConvertJSONToXML_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "XML" output from this ConvertJSONToXML execution.
     *
     * @return string (xml) The converted data in XML format.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getXML()
    {
        return $this->get('XML');
    }
}

?>