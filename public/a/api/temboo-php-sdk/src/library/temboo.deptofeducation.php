<?php

/**
 * Temboo PHP SDK DeptOfEducation classes
 *
 * Execute Choreographies from the Temboo DeptOfEducation bundle.
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
 * @subpackage DeptOfEducation
 * @author     Temboo, Inc.
 * @copyright  2012 Temboo, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @version    1.7
 * @link       http://live.temboo.com/sdk/php
 */


/**
 * Allows you to search the U.S. Department of Education college and univeristy listings.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_LookupSchool extends Temboo_Choreography
{
    /**
     * Allows you to search the U.S. Department of Education college and univeristy listings.
     *
     * @param Temboo_Session $session The session that owns this LookupSchool choreography.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/CollegesAndUniversities/LookupSchool/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this LookupSchool choreography.
     *
     * @param DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_CollegesAndUniversities_LookupSchool_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this LookupSchool choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the LookupSchool choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the LookupSchool choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this LookupSchool input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Keyword input for this LookupSchool choreography.
     *
     * @param string $value (required, string) Used to perform a full text search on the data set. For example, you can search for an institution's name or an institution's ID.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs For method chaining.
     */
    public function setKeyword($value)
    {
        return $this->set('Keyword', $value);
    }

    /**
     * Set the value for the MaxRows input for this LookupSchool choreography.
     *
     * @param int $value (optional, integer) Limits the number of rows returned. Defaults to 20.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs For method chaining.
     */
    public function setMaxRows($value)
    {
        return $this->set('MaxRows', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this LookupSchool choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the LookupSchool choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_LookupSchool_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the LookupSchool choreography.
     *
     * @param Temboo_Session $session The session that owns this LookupSchool execution.
     * @param DeptOfEducation_CollegesAndUniversities_LookupSchool $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_LookupSchool_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_CollegesAndUniversities_LookupSchool $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this LookupSchool execution.
     *
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Results New results object.
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
     * Wraps results in appopriate results class for this LookupSchool execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_CollegesAndUniversities_LookupSchool_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the LookupSchool choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_LookupSchool_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the LookupSchool choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_LookupSchool_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this LookupSchool execution.
     *
     * @return string The response from Data.ed.gov.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Allows you to search for majors by id or keyword.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_LookupMajors extends Temboo_Choreography
{
    /**
     * Allows you to search for majors by id or keyword.
     *
     * @param Temboo_Session $session The session that owns this LookupMajors choreography.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/CollegesAndUniversities/LookupMajors/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this LookupMajors choreography.
     *
     * @param DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_CollegesAndUniversities_LookupMajors_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this LookupMajors choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the LookupMajors choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the LookupMajors choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this LookupMajors input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Keyword input for this LookupMajors choreography.
     *
     * @param string $value (conditional, string) A keyword search term to lookup majors (i.e. Agriculture).
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs For method chaining.
     */
    public function setKeyword($value)
    {
        return $this->set('Keyword', $value);
    }

    /**
     * Set the value for the MajorID input for this LookupMajors choreography.
     *
     * @param float $value (conditional, decimal) The unique id associated with a major to return (i.e. 01.0000). Note that these ids are also returned in the output of the DegreesConferred Choreo.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs For method chaining.
     */
    public function setMajorID($value)
    {
        return $this->set('MajorID', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this LookupMajors choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the LookupMajors choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_LookupMajors_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the LookupMajors choreography.
     *
     * @param Temboo_Session $session The session that owns this LookupMajors execution.
     * @param DeptOfEducation_CollegesAndUniversities_LookupMajors $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_LookupMajors_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_CollegesAndUniversities_LookupMajors $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this LookupMajors execution.
     *
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Results New results object.
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
     * Wraps results in appopriate results class for this LookupMajors execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_CollegesAndUniversities_LookupMajors_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the LookupMajors choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_LookupMajors_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the LookupMajors choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_LookupMajors_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this LookupMajors execution.
     *
     * @return string The results from the search in XML or JSON format.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns gradutation statistics about colleges and univeristies.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_GraduationRates extends Temboo_Choreography
{
    /**
     * Returns gradutation statistics about colleges and univeristies.
     *
     * @param Temboo_Session $session The session that owns this GraduationRates choreography.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/CollegesAndUniversities/GraduationRates/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this GraduationRates choreography.
     *
     * @param DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_CollegesAndUniversities_GraduationRates_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this GraduationRates choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the GraduationRates choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the GraduationRates choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this GraduationRates input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the InstitutionID input for this GraduationRates choreography.
     *
     * @param string $value (optional, string) Specify an institutionID to return data on a specific institution. These ids can be retrieved from the LookupSchool Choreo.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs For method chaining.
     */
    public function setInstitutionID($value)
    {
        return $this->set('InstitutionID', $value);
    }

    /**
     * Set the value for the MaxRows input for this GraduationRates choreography.
     *
     * @param int $value (optional, integer) Limits the number of rows returned. Defaults to 20.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs For method chaining.
     */
    public function setMaxRows($value)
    {
        return $this->set('MaxRows', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this GraduationRates choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the GraduationRates choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_GraduationRates_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the GraduationRates choreography.
     *
     * @param Temboo_Session $session The session that owns this GraduationRates execution.
     * @param DeptOfEducation_CollegesAndUniversities_GraduationRates $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_GraduationRates_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_CollegesAndUniversities_GraduationRates $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this GraduationRates execution.
     *
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Results New results object.
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
     * Wraps results in appopriate results class for this GraduationRates execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_CollegesAndUniversities_GraduationRates_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the GraduationRates choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_GraduationRates_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the GraduationRates choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_GraduationRates_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this GraduationRates execution.
     *
     * @return string The response from Data.ed.gov.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns a list of all degrees conferred by a given institution, along with detailed demographic data for each degree awarded.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_DegreesConferred extends Temboo_Choreography
{
    /**
     * Returns a list of all degrees conferred by a given institution, along with detailed demographic data for each degree awarded.
     *
     * @param Temboo_Session $session The session that owns this DegreesConferred choreography.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/CollegesAndUniversities/DegreesConferred/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DegreesConferred choreography.
     *
     * @param DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_CollegesAndUniversities_DegreesConferred_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DegreesConferred choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DegreesConferred choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DegreesConferred choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DegreesConferred input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the InstitutionID input for this DegreesConferred choreography.
     *
     * @param string $value (optional, string) Specify an institutionID to return data on a specific institution. These ids can be retrieved from the LookupSchool Choreo.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs For method chaining.
     */
    public function setInstitutionID($value)
    {
        return $this->set('InstitutionID', $value);
    }

    /**
     * Set the value for the MaxRows input for this DegreesConferred choreography.
     *
     * @param int $value (optional, integer) Limits the number of rows returned. Defaults to 20.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs For method chaining.
     */
    public function setMaxRows($value)
    {
        return $this->set('MaxRows', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this DegreesConferred choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the DegreesConferred choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_DegreesConferred_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DegreesConferred choreography.
     *
     * @param Temboo_Session $session The session that owns this DegreesConferred execution.
     * @param DeptOfEducation_CollegesAndUniversities_DegreesConferred $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_DegreesConferred_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_CollegesAndUniversities_DegreesConferred $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DegreesConferred execution.
     *
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Results New results object.
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
     * Wraps results in appopriate results class for this DegreesConferred execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_CollegesAndUniversities_DegreesConferred_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DegreesConferred choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_DegreesConferred_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DegreesConferred choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_DegreesConferred_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this DegreesConferred execution.
     *
     * @return string The response from Data.ed.gov.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns student financial aid and net price information for colleges and universities.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_FinancialAid extends Temboo_Choreography
{
    /**
     * Returns student financial aid and net price information for colleges and universities.
     *
     * @param Temboo_Session $session The session that owns this FinancialAid choreography.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/CollegesAndUniversities/FinancialAid/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this FinancialAid choreography.
     *
     * @param DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_CollegesAndUniversities_FinancialAid_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this FinancialAid choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the FinancialAid choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the FinancialAid choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this FinancialAid input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the InstitutionID input for this FinancialAid choreography.
     *
     * @param string $value (optional, string) Specify an institutionID to return data on a specific institution. These ids can be retrieved from the LookupSchool Choreo.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs For method chaining.
     */
    public function setInstitutionID($value)
    {
        return $this->set('InstitutionID', $value);
    }

    /**
     * Set the value for the MaxRows input for this FinancialAid choreography.
     *
     * @param int $value (optional, integer) Limits the number of rows returned. Defaults to 20.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs For method chaining.
     */
    public function setMaxRows($value)
    {
        return $this->set('MaxRows', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this FinancialAid choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the FinancialAid choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_FinancialAid_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the FinancialAid choreography.
     *
     * @param Temboo_Session $session The session that owns this FinancialAid execution.
     * @param DeptOfEducation_CollegesAndUniversities_FinancialAid $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_FinancialAid_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_CollegesAndUniversities_FinancialAid $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this FinancialAid execution.
     *
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Results New results object.
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
     * Wraps results in appopriate results class for this FinancialAid execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_CollegesAndUniversities_FinancialAid_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the FinancialAid choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_FinancialAid_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the FinancialAid choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_FinancialAid_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this FinancialAid execution.
     *
     * @return string The response from Data.ed.gov.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns tuition information for colleges and universities.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_StudentCharges extends Temboo_Choreography
{
    /**
     * Returns tuition information for colleges and universities.
     *
     * @param Temboo_Session $session The session that owns this StudentCharges choreography.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/CollegesAndUniversities/StudentCharges/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this StudentCharges choreography.
     *
     * @param DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_CollegesAndUniversities_StudentCharges_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this StudentCharges choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the StudentCharges choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the StudentCharges choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this StudentCharges input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the InstitutionID input for this StudentCharges choreography.
     *
     * @param string $value (optional, string) Specify an institutionID to return data on a specific institution. These ids can be retrieved from the LookupSchool Choreo.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs For method chaining.
     */
    public function setInstitutionID($value)
    {
        return $this->set('InstitutionID', $value);
    }

    /**
     * Set the value for the MaxRows input for this StudentCharges choreography.
     *
     * @param int $value (optional, integer) Limits the number of rows returned. Defaults to 20.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs For method chaining.
     */
    public function setMaxRows($value)
    {
        return $this->set('MaxRows', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this StudentCharges choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the StudentCharges choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_StudentCharges_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the StudentCharges choreography.
     *
     * @param Temboo_Session $session The session that owns this StudentCharges execution.
     * @param DeptOfEducation_CollegesAndUniversities_StudentCharges $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_StudentCharges_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_CollegesAndUniversities_StudentCharges $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this StudentCharges execution.
     *
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Results New results object.
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
     * Wraps results in appopriate results class for this StudentCharges execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_CollegesAndUniversities_StudentCharges_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the StudentCharges choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_StudentCharges_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the StudentCharges choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_StudentCharges_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this StudentCharges execution.
     *
     * @return string The response from Data.ed.gov.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Allows you to search the U.S. Department of Education elementary and secondary school listings which includes general school information, demographic data, and location data.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_ElementaryAndSecondary_LookupSchool extends Temboo_Choreography
{
    /**
     * Allows you to search the U.S. Department of Education elementary and secondary school listings which includes general school information, demographic data, and location data.
     *
     * @param Temboo_Session $session The session that owns this LookupSchool choreography.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/ElementaryAndSecondary/LookupSchool/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this LookupSchool choreography.
     *
     * @param DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs|array $inputs (optional) Inputs as DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_ElementaryAndSecondary_LookupSchool_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this LookupSchool choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the LookupSchool choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the LookupSchool choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this LookupSchool input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Keyword input for this LookupSchool choreography.
     *
     * @param string $value (required, string) Used to perform a full text search on the data set. For example, you can search for a school name or a school's unique ID.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs For method chaining.
     */
    public function setKeyword($value)
    {
        return $this->set('Keyword', $value);
    }

    /**
     * Set the value for the MaxRows input for this LookupSchool choreography.
     *
     * @param int $value (optional, integer) Limits the number of rows returned. Defaults to 20.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs For method chaining.
     */
    public function setMaxRows($value)
    {
        return $this->set('MaxRows', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this LookupSchool choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the LookupSchool choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_ElementaryAndSecondary_LookupSchool_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the LookupSchool choreography.
     *
     * @param Temboo_Session $session The session that owns this LookupSchool execution.
     * @param DeptOfEducation_ElementaryAndSecondary_LookupSchool $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs|array $inputs (optional) Inputs as DeptOfEducation_ElementaryAndSecondary_LookupSchool_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_ElementaryAndSecondary_LookupSchool $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this LookupSchool execution.
     *
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Results New results object.
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
     * Wraps results in appopriate results class for this LookupSchool execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_ElementaryAndSecondary_LookupSchool_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the LookupSchool choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_ElementaryAndSecondary_LookupSchool_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the LookupSchool choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_ElementaryAndSecondary_LookupSchool_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this LookupSchool execution.
     *
     * @return string The response from Data.ed.gov.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns demographic data for colleges and universities.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics extends Temboo_Choreography
{
    /**
     * Returns demographic data for colleges and universities.
     *
     * @param Temboo_Session $session The session that owns this EnrollmentDemographics choreography.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/CollegesAndUniversities/EnrollmentDemographics/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this EnrollmentDemographics choreography.
     *
     * @param DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this EnrollmentDemographics choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the EnrollmentDemographics choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the EnrollmentDemographics choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this EnrollmentDemographics input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the InstitutionID input for this EnrollmentDemographics choreography.
     *
     * @param string $value (optional, string) Specify an institutionID to return data on a specific institution. These ids can be retrieved from the LookupSchool Choreo.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs For method chaining.
     */
    public function setInstitutionID($value)
    {
        return $this->set('InstitutionID', $value);
    }

    /**
     * Set the value for the MaxRows input for this EnrollmentDemographics choreography.
     *
     * @param int $value (optional, integer) Limits the number of rows returned. Defaults to 20.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs For method chaining.
     */
    public function setMaxRows($value)
    {
        return $this->set('MaxRows', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this EnrollmentDemographics choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the EnrollmentDemographics choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the EnrollmentDemographics choreography.
     *
     * @param Temboo_Session $session The session that owns this EnrollmentDemographics execution.
     * @param DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this EnrollmentDemographics execution.
     *
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Results New results object.
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
     * Wraps results in appopriate results class for this EnrollmentDemographics execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the EnrollmentDemographics choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the EnrollmentDemographics choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_EnrollmentDemographics_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this EnrollmentDemographics execution.
     *
     * @return string The response from Data.ed.gov.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns educational offerings and admissions data for any college or university.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_EducationalOfferings extends Temboo_Choreography
{
    /**
     * Returns educational offerings and admissions data for any college or university.
     *
     * @param Temboo_Session $session The session that owns this EducationalOfferings choreography.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/DeptOfEducation/CollegesAndUniversities/EducationalOfferings/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this EducationalOfferings choreography.
     *
     * @param DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this EducationalOfferings choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the EducationalOfferings choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the EducationalOfferings choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this EducationalOfferings input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the InstitutionID input for this EducationalOfferings choreography.
     *
     * @param string $value (optional, string) Specify an institutionID to return data on a specific institution. These ids can be retrieved from the LookupSchool Choreo.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs For method chaining.
     */
    public function setInstitutionID($value)
    {
        return $this->set('InstitutionID', $value);
    }

    /**
     * Set the value for the MaxRows input for this EducationalOfferings choreography.
     *
     * @param int $value (optional, integer) Limits the number of rows returned. Defaults to 20.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs For method chaining.
     */
    public function setMaxRows($value)
    {
        return $this->set('MaxRows', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this EducationalOfferings choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: xml (the default) and json.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the EducationalOfferings choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the EducationalOfferings choreography.
     *
     * @param Temboo_Session $session The session that owns this EducationalOfferings execution.
     * @param DeptOfEducation_CollegesAndUniversities_EducationalOfferings $choreo The choregraphy object for this execution.
     * @param DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs|array $inputs (optional) Inputs as DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, DeptOfEducation_CollegesAndUniversities_EducationalOfferings $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this EducationalOfferings execution.
     *
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Results New results object.
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
     * Wraps results in appopriate results class for this EducationalOfferings execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the EducationalOfferings choreography.
 *
 * @package Temboo
 * @subpackage DeptOfEducation
 */
class DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the EducationalOfferings choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return DeptOfEducation_CollegesAndUniversities_EducationalOfferings_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this EducationalOfferings execution.
     *
     * @return string The response from Data.ed.gov.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

?>