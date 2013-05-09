<?php

/**
 * Temboo PHP SDK 23andMe classes
 *
 * Execute Choreographies from the Temboo 23andMe bundle.
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
 * @subpackage 23andMe
 * @author     Temboo, Inc.
 * @copyright  2012 Temboo, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @version    1.7
 * @link       http://live.temboo.com/sdk/php
 */


/**
 * For each of the user's profiles, retrieves the base-pairs for given locations.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Genotype extends Temboo_Choreography
{
    /**
     * For each of the user's profiles, retrieves the base-pairs for given locations.
     *
     * @param Temboo_Session $session The session that owns this Genotype choreography.
     * @return _23andMe_Genotype New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/23andMe/Genotype/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Genotype choreography.
     *
     * @param _23andMe_Genotype_Inputs|array $inputs (optional) Inputs as _23andMe_Genotype_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return _23andMe_Genotype_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new _23andMe_Genotype_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Genotype choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return _23andMe_Genotype_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new _23andMe_Genotype_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Genotype choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Genotype_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Genotype choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return _23andMe_Genotype_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Genotype input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return _23andMe_Genotype_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return _23andMe_Genotype_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Genotype choreography.
     *
     * @param string $value (required, string) The Access Token retrieved after completing the OAuth2 process.
     * @return _23andMe_Genotype_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Locations input for this Genotype choreography.
     *
     * @param string $value (required, string) A space delimited list of SNPs (i.e. rs3094315 rs3737728).
     * @return _23andMe_Genotype_Inputs For method chaining.
     */
    public function setLocations($value)
    {
        return $this->set('Locations', $value);
    }
}


/**
 * Execution object for the Genotype choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Genotype_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Genotype choreography.
     *
     * @param Temboo_Session $session The session that owns this Genotype execution.
     * @param _23andMe_Genotype $choreo The choregraphy object for this execution.
     * @param _23andMe_Genotype_Inputs|array $inputs (optional) Inputs as _23andMe_Genotype_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return _23andMe_Genotype_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, _23andMe_Genotype $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Genotype execution.
     *
     * @return _23andMe_Genotype_Results New results object.
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
     * Wraps results in appopriate results class for this Genotype execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return _23andMe_Genotype_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new _23andMe_Genotype_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Genotype choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Genotype_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Genotype choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return _23andMe_Genotype_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Genotype execution.
     *
     * @return string (json) The response from 23AndMe.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves the user id, and a list of profiles including their ids and whether or not they are genotyped.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_User extends Temboo_Choreography
{
    /**
     * Retrieves the user id, and a list of profiles including their ids and whether or not they are genotyped.
     *
     * @param Temboo_Session $session The session that owns this User choreography.
     * @return _23andMe_User New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/23andMe/User/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this User choreography.
     *
     * @param _23andMe_User_Inputs|array $inputs (optional) Inputs as _23andMe_User_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return _23andMe_User_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new _23andMe_User_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this User choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return _23andMe_User_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new _23andMe_User_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the User choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_User_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the User choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return _23andMe_User_Inputs New instance.
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
     * @return _23andMe_User_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return _23andMe_User_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this User choreography.
     *
     * @param string $value (required, string) The Access Token retrieved after completing the OAuth2 process.
     * @return _23andMe_User_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }
}


/**
 * Execution object for the User choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_User_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the User choreography.
     *
     * @param Temboo_Session $session The session that owns this User execution.
     * @param _23andMe_User $choreo The choregraphy object for this execution.
     * @param _23andMe_User_Inputs|array $inputs (optional) Inputs as _23andMe_User_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return _23andMe_User_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, _23andMe_User $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this User execution.
     *
     * @return _23andMe_User_Results New results object.
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
     * @return _23andMe_User_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new _23andMe_User_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the User choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_User_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the User choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return _23andMe_User_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this User execution.
     *
     * @return string (json) The response from 23AndMe.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves maternal and paternal haplogroups for a user's profiles.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Haplogroups extends Temboo_Choreography
{
    /**
     * Retrieves maternal and paternal haplogroups for a user's profiles.
     *
     * @param Temboo_Session $session The session that owns this Haplogroups choreography.
     * @return _23andMe_Haplogroups New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/23andMe/Haplogroups/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Haplogroups choreography.
     *
     * @param _23andMe_Haplogroups_Inputs|array $inputs (optional) Inputs as _23andMe_Haplogroups_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return _23andMe_Haplogroups_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new _23andMe_Haplogroups_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Haplogroups choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return _23andMe_Haplogroups_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new _23andMe_Haplogroups_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Haplogroups choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Haplogroups_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Haplogroups choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return _23andMe_Haplogroups_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Haplogroups input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return _23andMe_Haplogroups_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return _23andMe_Haplogroups_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Haplogroups choreography.
     *
     * @param string $value (required, string) The Access Token retrieved after completing the OAuth2 process.
     * @return _23andMe_Haplogroups_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }
}


/**
 * Execution object for the Haplogroups choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Haplogroups_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Haplogroups choreography.
     *
     * @param Temboo_Session $session The session that owns this Haplogroups execution.
     * @param _23andMe_Haplogroups $choreo The choregraphy object for this execution.
     * @param _23andMe_Haplogroups_Inputs|array $inputs (optional) Inputs as _23andMe_Haplogroups_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return _23andMe_Haplogroups_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, _23andMe_Haplogroups $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Haplogroups execution.
     *
     * @return _23andMe_Haplogroups_Results New results object.
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
     * Wraps results in appopriate results class for this Haplogroups execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return _23andMe_Haplogroups_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new _23andMe_Haplogroups_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Haplogroups choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Haplogroups_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Haplogroups choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return _23andMe_Haplogroups_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Haplogroups execution.
     *
     * @return string (json) The response from 23AndMe.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves first and last names for the user and user's profiles.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Names extends Temboo_Choreography
{
    /**
     * Retrieves first and last names for the user and user's profiles.
     *
     * @param Temboo_Session $session The session that owns this Names choreography.
     * @return _23andMe_Names New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/23andMe/Names/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this Names choreography.
     *
     * @param _23andMe_Names_Inputs|array $inputs (optional) Inputs as _23andMe_Names_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return _23andMe_Names_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new _23andMe_Names_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this Names choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return _23andMe_Names_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new _23andMe_Names_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the Names choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Names_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the Names choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return _23andMe_Names_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this Names input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return _23andMe_Names_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return _23andMe_Names_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this Names choreography.
     *
     * @param string $value (required, string) The Access Token retrieved after completing the OAuth2 process.
     * @return _23andMe_Names_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }
}


/**
 * Execution object for the Names choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Names_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the Names choreography.
     *
     * @param Temboo_Session $session The session that owns this Names execution.
     * @param _23andMe_Names $choreo The choregraphy object for this execution.
     * @param _23andMe_Names_Inputs|array $inputs (optional) Inputs as _23andMe_Names_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return _23andMe_Names_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, _23andMe_Names $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this Names execution.
     *
     * @return _23andMe_Names_Results New results object.
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
     * Wraps results in appopriate results class for this Names execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return _23andMe_Names_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new _23andMe_Names_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the Names choreography.
 *
 * @package Temboo
 * @subpackage 23andMe
 */
class _23andMe_Names_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the Names choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return _23andMe_Names_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this Names execution.
     *
     * @return string (json) The response from 23AndMe.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

?>