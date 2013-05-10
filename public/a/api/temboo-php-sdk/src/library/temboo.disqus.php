<?php

/**
 * Temboo PHP SDK Disqus classes
 *
 * Execute Choreographies from the Temboo Disqus bundle.
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
 * @subpackage Disqus
 * @author     Temboo, Inc.
 * @copyright  2012 Temboo, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @version    1.7
 * @link       http://live.temboo.com/sdk/php
 */


/**
 * Report (flag) a post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ReportPost extends Temboo_Choreography
{
    /**
     * Report (flag) a post.
     *
     * @param Temboo_Session $session The session that owns this ReportPost choreography.
     * @return Disqus_Posts_ReportPost New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/ReportPost/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ReportPost choreography.
     *
     * @param Disqus_Posts_ReportPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_ReportPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_ReportPost_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_ReportPost_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ReportPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_ReportPost_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_ReportPost_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ReportPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ReportPost_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ReportPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_ReportPost_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ReportPost input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_ReportPost_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_ReportPost_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this ReportPost choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_ReportPost_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PostID input for this ReportPost choreography.
     *
     * @param int $value (required, integer) The post ID which is to be reported (flagged).
     * @return Disqus_Posts_ReportPost_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this ReportPost choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_ReportPost_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ReportPost choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_ReportPost_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

}


/**
 * Execution object for the ReportPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ReportPost_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ReportPost choreography.
     *
     * @param Temboo_Session $session The session that owns this ReportPost execution.
     * @param Disqus_Posts_ReportPost $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_ReportPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_ReportPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_ReportPost_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_ReportPost $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ReportPost execution.
     *
     * @return Disqus_Posts_ReportPost_Results New results object.
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
     * Wraps results in appopriate results class for this ReportPost execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_ReportPost_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_ReportPost_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ReportPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ReportPost_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ReportPost choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_ReportPost_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ReportPost execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Close a thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_CloseThread extends Temboo_Choreography
{
    /**
     * Close a thread.
     *
     * @param Temboo_Session $session The session that owns this CloseThread choreography.
     * @return Disqus_Threads_CloseThread New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/CloseThread/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CloseThread choreography.
     *
     * @param Disqus_Threads_CloseThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_CloseThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_CloseThread_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_CloseThread_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CloseThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_CloseThread_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_CloseThread_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CloseThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_CloseThread_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CloseThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_CloseThread_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CloseThread input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this CloseThread choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Forum input for this CloseThread choreography.
     *
     * @param int $value (optional, integer) A forum ID. Required if setting either ThreadByIdentification, or ThreadByLink.
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the PublicKey input for this CloseThread choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this CloseThread choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this CloseThread choreography.
     *
     * @param int $value (conditional, integer) The ID of a thread that is to be closed. Required unless specifying ThreadIdentifier or ThreadLink. If using this parameter, ThreadByIdentification cannot be set.
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this CloseThread choreography.
     *
     * @param string $value (conditional, string) The identifier to retrieve associated thread details.  Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this CloseThread choreography.
     *
     * @param string $value (conditional, string) The link pointing to the thread that is to be closed. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_CloseThread_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }

}


/**
 * Execution object for the CloseThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_CloseThread_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CloseThread choreography.
     *
     * @param Temboo_Session $session The session that owns this CloseThread execution.
     * @param Disqus_Threads_CloseThread $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_CloseThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_CloseThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_CloseThread_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_CloseThread $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CloseThread execution.
     *
     * @return Disqus_Threads_CloseThread_Results New results object.
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
     * Wraps results in appopriate results class for this CloseThread execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_CloseThread_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_CloseThread_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CloseThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_CloseThread_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CloseThread choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_CloseThread_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CloseThread execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Vote on a thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_VoteOnThread extends Temboo_Choreography
{
    /**
     * Vote on a thread.
     *
     * @param Temboo_Session $session The session that owns this VoteOnThread choreography.
     * @return Disqus_Threads_VoteOnThread New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/VoteOnThread/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this VoteOnThread choreography.
     *
     * @param Disqus_Threads_VoteOnThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_VoteOnThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_VoteOnThread_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_VoteOnThread_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this VoteOnThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_VoteOnThread_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_VoteOnThread_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the VoteOnThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_VoteOnThread_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the VoteOnThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_VoteOnThread_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this VoteOnThread input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Forum input for this VoteOnThread choreography.
     *
     * @param int $value (optional, integer) The forum ID of a thread that is being voted on. Required if setting either ThreadByIdentification, or ThreadByLink.
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the PublicKey input for this VoteOnThread choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this VoteOnThread choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this VoteOnThread choreography.
     *
     * @param int $value (optional, integer) The ID of a thread that is being voted on. Required unless specifying the ThreadIdentifier or ThreadLink. If using this parameter, ThreadIdentifier cannot be set.
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this VoteOnThread choreography.
     *
     * @param string $value (optional, string) The identifier for the thread that is being voted on. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this VoteOnThread choreography.
     *
     * @param string $value (optional, string) A link pointing to the thread that is being voted on. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }

    /**
     * Set the value for the Vote input for this VoteOnThread choreography.
     *
     * @param int $value (required, integer) A numberic value for your vote. Valid choices are: -1, 0, or 1.
     * @return Disqus_Threads_VoteOnThread_Inputs For method chaining.
     */
    public function setVote($value)
    {
        return $this->set('Vote', $value);
    }

}


/**
 * Execution object for the VoteOnThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_VoteOnThread_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the VoteOnThread choreography.
     *
     * @param Temboo_Session $session The session that owns this VoteOnThread execution.
     * @param Disqus_Threads_VoteOnThread $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_VoteOnThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_VoteOnThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_VoteOnThread_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_VoteOnThread $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this VoteOnThread execution.
     *
     * @return Disqus_Threads_VoteOnThread_Results New results object.
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
     * Wraps results in appopriate results class for this VoteOnThread execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_VoteOnThread_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_VoteOnThread_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the VoteOnThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_VoteOnThread_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the VoteOnThread choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_VoteOnThread_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this VoteOnThread execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieve a list of reactions for a given thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListThreadReactions extends Temboo_Choreography
{
    /**
     * Retrieve a list of reactions for a given thread.
     *
     * @param Temboo_Session $session The session that owns this ListThreadReactions choreography.
     * @return Disqus_Threads_ListThreadReactions New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/ListThreadReactions/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListThreadReactions choreography.
     *
     * @param Disqus_Threads_ListThreadReactions_Inputs|array $inputs (optional) Inputs as Disqus_Threads_ListThreadReactions_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_ListThreadReactions_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_ListThreadReactions_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListThreadReactions choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_ListThreadReactions_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_ListThreadReactions_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListThreadReactions choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListThreadReactions_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListThreadReactions choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_ListThreadReactions_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListThreadReactions input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Cursor input for this ListThreadReactions choreography.
     *
     * @param string $value (optional, string) Default is set to null.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setCursor($value)
    {
        return $this->set('Cursor', $value);
    }

    /**
     * Set the value for the Forum input for this ListThreadReactions choreography.
     *
     * @param int $value (optional, integer) A Disqus forum ID (AKA a short name). If null, threads from all forums moderated by the authenticating user will be retrieved.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the Limit input for this ListThreadReactions choreography.
     *
     * @param int $value (optional, integer) The number of records to return. Maximum value is 100.  Defaults to 25.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the PublicKey input for this ListThreadReactions choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ListThreadReactions choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this ListThreadReactions choreography.
     *
     * @param string $value (conditional, string) The ID of the Thread to list reactions for. Required unless specifying ThreadIdentifier or ThreadLink.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this ListThreadReactions choreography.
     *
     * @param string $value (conditional, string) An identifier for which thread reactions will be retrieved.  Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this ListThreadReactions choreography.
     *
     * @param string $value (conditional, string) A link pointing to the thread that is to be retrieved. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_ListThreadReactions_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }
}


/**
 * Execution object for the ListThreadReactions choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListThreadReactions_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListThreadReactions choreography.
     *
     * @param Temboo_Session $session The session that owns this ListThreadReactions execution.
     * @param Disqus_Threads_ListThreadReactions $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_ListThreadReactions_Inputs|array $inputs (optional) Inputs as Disqus_Threads_ListThreadReactions_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_ListThreadReactions_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_ListThreadReactions $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListThreadReactions execution.
     *
     * @return Disqus_Threads_ListThreadReactions_Results New results object.
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
     * Wraps results in appopriate results class for this ListThreadReactions execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_ListThreadReactions_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_ListThreadReactions_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListThreadReactions choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListThreadReactions_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListThreadReactions choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_ListThreadReactions_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListThreadReactions execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Obtain information about a post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_PostDetails extends Temboo_Choreography
{
    /**
     * Obtain information about a post.
     *
     * @param Temboo_Session $session The session that owns this PostDetails choreography.
     * @return Disqus_Posts_PostDetails New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/PostDetails/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this PostDetails choreography.
     *
     * @param Disqus_Posts_PostDetails_Inputs|array $inputs (optional) Inputs as Disqus_Posts_PostDetails_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_PostDetails_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_PostDetails_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this PostDetails choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_PostDetails_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_PostDetails_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the PostDetails choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_PostDetails_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the PostDetails choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_PostDetails_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this PostDetails input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_PostDetails_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_PostDetails_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this PostDetails choreography.
     *
     * @param string $value (optional, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_PostDetails_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PostID input for this PostDetails choreography.
     *
     * @param int $value (required, integer) The post ID for which information will be returned.
     * @return Disqus_Posts_PostDetails_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this PostDetails choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_PostDetails_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the Related input for this PostDetails choreography.
     *
     * @param string $value (optional, string) Specify a related thread or forum that are to be included in the response.  Valid entries include: thread, or forum.
     * @return Disqus_Posts_PostDetails_Inputs For method chaining.
     */
    public function setRelated($value)
    {
        return $this->set('Related', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this PostDetails choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_PostDetails_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

}


/**
 * Execution object for the PostDetails choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_PostDetails_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the PostDetails choreography.
     *
     * @param Temboo_Session $session The session that owns this PostDetails execution.
     * @param Disqus_Posts_PostDetails $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_PostDetails_Inputs|array $inputs (optional) Inputs as Disqus_Posts_PostDetails_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_PostDetails_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_PostDetails $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this PostDetails execution.
     *
     * @return Disqus_Posts_PostDetails_Results New results object.
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
     * Wraps results in appopriate results class for this PostDetails execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_PostDetails_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_PostDetails_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the PostDetails choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_PostDetails_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the PostDetails choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_PostDetails_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this PostDetails execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Unsubscribe from a thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_UnsubscribeFromThread extends Temboo_Choreography
{
    /**
     * Unsubscribe from a thread.
     *
     * @param Temboo_Session $session The session that owns this UnsubscribeFromThread choreography.
     * @return Disqus_Threads_UnsubscribeFromThread New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/UnsubscribeFromThread/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UnsubscribeFromThread choreography.
     *
     * @param Disqus_Threads_UnsubscribeFromThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_UnsubscribeFromThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_UnsubscribeFromThread_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_UnsubscribeFromThread_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UnsubscribeFromThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_UnsubscribeFromThread_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UnsubscribeFromThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_UnsubscribeFromThread_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UnsubscribeFromThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UnsubscribeFromThread input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this UnsubscribeFromThread choreography.
     *
     * @param string $value (optional, string) The email address that will be unsubsribed from the thread.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Forum input for this UnsubscribeFromThread choreography.
     *
     * @param int $value (optional, integer) The forum ID of a thread that is to be unsubscribed from. Required if setting either ThreadByIdentification, or ThreadByLink.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the PublicKey input for this UnsubscribeFromThread choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this UnsubscribeFromThread choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this UnsubscribeFromThread choreography.
     *
     * @param int $value (conditional, integer) The ID of a thread that is to be unsubscribed from. Required unless specifying the ThreadIdentifier or ThreadLink. If using this parameter, ThreadIdentifier cannot be set.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this UnsubscribeFromThread choreography.
     *
     * @param string $value (optional, string) The identifier for the thread that is to be unsubscribed from.  Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this UnsubscribeFromThread choreography.
     *
     * @param string $value (optional, string) A link pointing to the thread that is to be unsubscribed from. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_UnsubscribeFromThread_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }

}


/**
 * Execution object for the UnsubscribeFromThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_UnsubscribeFromThread_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UnsubscribeFromThread choreography.
     *
     * @param Temboo_Session $session The session that owns this UnsubscribeFromThread execution.
     * @param Disqus_Threads_UnsubscribeFromThread $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_UnsubscribeFromThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_UnsubscribeFromThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_UnsubscribeFromThread_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_UnsubscribeFromThread $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UnsubscribeFromThread execution.
     *
     * @return Disqus_Threads_UnsubscribeFromThread_Results New results object.
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
     * Wraps results in appopriate results class for this UnsubscribeFromThread execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_UnsubscribeFromThread_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_UnsubscribeFromThread_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UnsubscribeFromThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_UnsubscribeFromThread_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UnsubscribeFromThread choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_UnsubscribeFromThread_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UnsubscribeFromThread execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Highlight a post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_HighlightPost extends Temboo_Choreography
{
    /**
     * Highlight a post.
     *
     * @param Temboo_Session $session The session that owns this HighlightPost choreography.
     * @return Disqus_Posts_HighlightPost New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/HighlightPost/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this HighlightPost choreography.
     *
     * @param Disqus_Posts_HighlightPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_HighlightPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_HighlightPost_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_HighlightPost_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this HighlightPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_HighlightPost_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_HighlightPost_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the HighlightPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_HighlightPost_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the HighlightPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_HighlightPost_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this HighlightPost input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_HighlightPost_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_HighlightPost_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this HighlightPost choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_HighlightPost_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PostID input for this HighlightPost choreography.
     *
     * @param int $value (required, integer) The post ID which is to be highlighted.
     * @return Disqus_Posts_HighlightPost_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this HighlightPost choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_HighlightPost_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this HighlightPost choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_HighlightPost_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

}


/**
 * Execution object for the HighlightPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_HighlightPost_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the HighlightPost choreography.
     *
     * @param Temboo_Session $session The session that owns this HighlightPost execution.
     * @param Disqus_Posts_HighlightPost $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_HighlightPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_HighlightPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_HighlightPost_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_HighlightPost $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this HighlightPost execution.
     *
     * @return Disqus_Posts_HighlightPost_Results New results object.
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
     * Wraps results in appopriate results class for this HighlightPost execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_HighlightPost_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_HighlightPost_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the HighlightPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_HighlightPost_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the HighlightPost choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_HighlightPost_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this HighlightPost execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns a list of trending threads.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Trends_ListThreads extends Temboo_Choreography
{
    /**
     * Returns a list of trending threads.
     *
     * @param Temboo_Session $session The session that owns this ListThreads choreography.
     * @return Disqus_Trends_ListThreads New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Trends/ListThreads/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListThreads choreography.
     *
     * @param Disqus_Trends_ListThreads_Inputs|array $inputs (optional) Inputs as Disqus_Trends_ListThreads_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Trends_ListThreads_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Trends_ListThreads_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListThreads choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Trends_ListThreads_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Trends_ListThreads_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListThreads choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Trends_ListThreads_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListThreads choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Trends_ListThreads_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListThreads input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this ListThreads choreography.
     *
     * @param string $value (optional, string) A valid OAuth 2.0 access token.
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Callback input for this ListThreads choreography.
     *
     * @param string $value (optional, string) The name of a callback function to wrap the respone in. Used when setting ResponseFormat to "jsonp".
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function setCallback($value)
    {
        return $this->set('Callback', $value);
    }

    /**
     * Set the value for the Forum input for this ListThreads choreography.
     *
     * @param string $value (optional, string) Allows you to look up a forum by ID (aka the short name).
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the Limit input for this ListThreads choreography.
     *
     * @param int $value (optional, integer) The number of records to return. Defaults to 10.
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the PublicKey input for this ListThreads choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the The Client ID).
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the Related input for this ListThreads choreography.
     *
     * @param string $value (optional, string) Indicates the relations to include with your response. Valid values are: forum, author, category.
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function setRelated($value)
    {
        return $this->set('Related', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ListThreads choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Trends_ListThreads_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }
}


/**
 * Execution object for the ListThreads choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Trends_ListThreads_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListThreads choreography.
     *
     * @param Temboo_Session $session The session that owns this ListThreads execution.
     * @param Disqus_Trends_ListThreads $choreo The choregraphy object for this execution.
     * @param Disqus_Trends_ListThreads_Inputs|array $inputs (optional) Inputs as Disqus_Trends_ListThreads_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Trends_ListThreads_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Trends_ListThreads $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListThreads execution.
     *
     * @return Disqus_Trends_ListThreads_Results New results object.
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
     * Wraps results in appopriate results class for this ListThreads execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Trends_ListThreads_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Trends_ListThreads_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListThreads choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Trends_ListThreads_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListThreads choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Trends_ListThreads_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListThreads execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieve a list of threads ordered by date of creation.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListThreads extends Temboo_Choreography
{
    /**
     * Retrieve a list of threads ordered by date of creation.
     *
     * @param Temboo_Session $session The session that owns this ListThreads choreography.
     * @return Disqus_Threads_ListThreads New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/ListThreads/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListThreads choreography.
     *
     * @param Disqus_Threads_ListThreads_Inputs|array $inputs (optional) Inputs as Disqus_Threads_ListThreads_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_ListThreads_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_ListThreads_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListThreads choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_ListThreads_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_ListThreads_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListThreads choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListThreads_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListThreads choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_ListThreads_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListThreads input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this ListThreads choreography.
     *
     * @param string $value (optional, string) A valid OAuth 2.0 access token.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the AuthorID input for this ListThreads choreography.
     *
     * @param int $value (optional, integer) A Disqus User ID, for which threads will be retrieved. If AuthorID is set, then AuthorUsername must be null.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setAuthorID($value)
    {
        return $this->set('AuthorID', $value);
    }

    /**
     * Set the value for the AuthorUsername input for this ListThreads choreography.
     *
     * @param string $value (optional, string) A Disqus username for which threads will be retrieved.  If AuthorUsername is being set, then AuthorID must be null.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setAuthorUsername($value)
    {
        return $this->set('AuthorUsername', $value);
    }

    /**
     * Set the value for the Category input for this ListThreads choreography.
     *
     * @param int $value (optional, integer) Specify a category ID for which threads wil be retrieved.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setCategory($value)
    {
        return $this->set('Category', $value);
    }

    /**
     * Set the value for the Cursor input for this ListThreads choreography.
     *
     * @param string $value (optional, string) Default is set to null.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setCursor($value)
    {
        return $this->set('Cursor', $value);
    }

    /**
     * Set the value for the Forum input for this ListThreads choreography.
     *
     * @param string $value (optional, string) A Disqus forum name to display all threads contained in that specified forum.  If null, threads from all forums moderated by the authenticating user will be retrieved.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the Include input for this ListThreads choreography.
     *
     * @param string $value (optional, string) Specify a post status parameter to filter results by. Valid parameters include: open, closed, killed.  Default is set to: open, closed.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setInclude($value)
    {
        return $this->set('Include', $value);
    }

    /**
     * Set the value for the Limit input for this ListThreads choreography.
     *
     * @param int $value (optional, integer) The number of records to return. Maximum value is 100.  Defaults to 25.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Order input for this ListThreads choreography.
     *
     * @param string $value (optional, string) The sort order for the results. Valid values are: asc or desc. Default is set to: asc.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setOrder($value)
    {
        return $this->set('Order', $value);
    }

    /**
     * Set the value for the PublicKey input for this ListThreads choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the Related input for this ListThreads choreography.
     *
     * @param string $value (optional, string) Specify a related thread or forum that are to be included in the response.  Valid entries include: author, or category.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setRelated($value)
    {
        return $this->set('Related', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ListThreads choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the SinceID input for this ListThreads choreography.
     *
     * @param int $value (optional, integer) A Unix timestamp (or ISO datetime standard) to obtain results from. Default is set to null.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setSinceID($value)
    {
        return $this->set('SinceID', $value);
    }

    /**
     * Set the value for the ThreadID input for this ListThreads choreography.
     *
     * @param string $value (optional, string) A Thread ID to narrow search results.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this ListThreads choreography.
     *
     * @param string $value (optional, string) An identifier to retrieve associated threads. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this ListThreads choreography.
     *
     * @param string $value (optional, string) A link pointing to the thread that is to be retrieved. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_ListThreads_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }
}


/**
 * Execution object for the ListThreads choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListThreads_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListThreads choreography.
     *
     * @param Temboo_Session $session The session that owns this ListThreads execution.
     * @param Disqus_Threads_ListThreads $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_ListThreads_Inputs|array $inputs (optional) Inputs as Disqus_Threads_ListThreads_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_ListThreads_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_ListThreads $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListThreads execution.
     *
     * @return Disqus_Threads_ListThreads_Results New results object.
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
     * Wraps results in appopriate results class for this ListThreads execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_ListThreads_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_ListThreads_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListThreads choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListThreads_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListThreads choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_ListThreads_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListThreads execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Mark a post as spam.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_MarkAsSpam extends Temboo_Choreography
{
    /**
     * Mark a post as spam.
     *
     * @param Temboo_Session $session The session that owns this MarkAsSpam choreography.
     * @return Disqus_Posts_MarkAsSpam New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/MarkAsSpam/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this MarkAsSpam choreography.
     *
     * @param Disqus_Posts_MarkAsSpam_Inputs|array $inputs (optional) Inputs as Disqus_Posts_MarkAsSpam_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_MarkAsSpam_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_MarkAsSpam_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this MarkAsSpam choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_MarkAsSpam_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_MarkAsSpam_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the MarkAsSpam choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_MarkAsSpam_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the MarkAsSpam choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_MarkAsSpam_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this MarkAsSpam input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_MarkAsSpam_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_MarkAsSpam_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this MarkAsSpam choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_MarkAsSpam_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PostID input for this MarkAsSpam choreography.
     *
     * @param int $value (required, integer) The post ID which is to be marked as spam.
     * @return Disqus_Posts_MarkAsSpam_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this MarkAsSpam choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_MarkAsSpam_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this MarkAsSpam choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_MarkAsSpam_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

}


/**
 * Execution object for the MarkAsSpam choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_MarkAsSpam_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the MarkAsSpam choreography.
     *
     * @param Temboo_Session $session The session that owns this MarkAsSpam execution.
     * @param Disqus_Posts_MarkAsSpam $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_MarkAsSpam_Inputs|array $inputs (optional) Inputs as Disqus_Posts_MarkAsSpam_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_MarkAsSpam_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_MarkAsSpam $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this MarkAsSpam execution.
     *
     * @return Disqus_Posts_MarkAsSpam_Results New results object.
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
     * Wraps results in appopriate results class for this MarkAsSpam execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_MarkAsSpam_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_MarkAsSpam_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the MarkAsSpam choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_MarkAsSpam_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the MarkAsSpam choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_MarkAsSpam_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this MarkAsSpam execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Unhighlight a previously highlighted post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_UnhighlightPost extends Temboo_Choreography
{
    /**
     * Unhighlight a previously highlighted post.
     *
     * @param Temboo_Session $session The session that owns this UnhighlightPost choreography.
     * @return Disqus_Posts_UnhighlightPost New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/UnhighlightPost/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UnhighlightPost choreography.
     *
     * @param Disqus_Posts_UnhighlightPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_UnhighlightPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_UnhighlightPost_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_UnhighlightPost_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UnhighlightPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_UnhighlightPost_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_UnhighlightPost_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UnhighlightPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_UnhighlightPost_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UnhighlightPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_UnhighlightPost_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UnhighlightPost input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_UnhighlightPost_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_UnhighlightPost_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this UnhighlightPost choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_UnhighlightPost_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PostID input for this UnhighlightPost choreography.
     *
     * @param int $value (required, integer) The post ID which is to be unhighlighted.
     * @return Disqus_Posts_UnhighlightPost_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this UnhighlightPost choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_UnhighlightPost_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this UnhighlightPost choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_UnhighlightPost_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

}


/**
 * Execution object for the UnhighlightPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_UnhighlightPost_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UnhighlightPost choreography.
     *
     * @param Temboo_Session $session The session that owns this UnhighlightPost execution.
     * @param Disqus_Posts_UnhighlightPost $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_UnhighlightPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_UnhighlightPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_UnhighlightPost_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_UnhighlightPost $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UnhighlightPost execution.
     *
     * @return Disqus_Posts_UnhighlightPost_Results New results object.
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
     * Wraps results in appopriate results class for this UnhighlightPost execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_UnhighlightPost_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_UnhighlightPost_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UnhighlightPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_UnhighlightPost_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UnhighlightPost choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_UnhighlightPost_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UnhighlightPost execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Check whether the provided username is available.  An error is returned if the entered username is either taken, or invalid.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_CheckUsername extends Temboo_Choreography
{
    /**
     * Check whether the provided username is available.  An error is returned if the entered username is either taken, or invalid.
     *
     * @param Temboo_Session $session The session that owns this CheckUsername choreography.
     * @return Disqus_Users_CheckUsername New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Users/CheckUsername/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CheckUsername choreography.
     *
     * @param Disqus_Users_CheckUsername_Inputs|array $inputs (optional) Inputs as Disqus_Users_CheckUsername_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_CheckUsername_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Users_CheckUsername_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CheckUsername choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_CheckUsername_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Users_CheckUsername_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CheckUsername choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_CheckUsername_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CheckUsername choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_CheckUsername_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CheckUsername input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Users_CheckUsername_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Users_CheckUsername_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this CheckUsername choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Users_CheckUsername_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Callback input for this CheckUsername choreography.
     *
     * @param string $value (optional, string) The name of a callback function to wrap the respone in. Used when setting ResponseFormat to "jsonp".
     * @return Disqus_Users_CheckUsername_Inputs For method chaining.
     */
    public function setCallback($value)
    {
        return $this->set('Callback', $value);
    }

    /**
     * Set the value for the PublicKey input for this CheckUsername choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Users_CheckUsername_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this CheckUsername choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Users_CheckUsername_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Username input for this CheckUsername choreography.
     *
     * @param string $value (required, string) Enter a Disqus username.
     * @return Disqus_Users_CheckUsername_Inputs For method chaining.
     */
    public function setUsername($value)
    {
        return $this->set('Username', $value);
    }

}


/**
 * Execution object for the CheckUsername choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_CheckUsername_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CheckUsername choreography.
     *
     * @param Temboo_Session $session The session that owns this CheckUsername execution.
     * @param Disqus_Users_CheckUsername $choreo The choregraphy object for this execution.
     * @param Disqus_Users_CheckUsername_Inputs|array $inputs (optional) Inputs as Disqus_Users_CheckUsername_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_CheckUsername_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Users_CheckUsername $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CheckUsername execution.
     *
     * @return Disqus_Users_CheckUsername_Results New results object.
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
     * Wraps results in appopriate results class for this CheckUsername execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Users_CheckUsername_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Users_CheckUsername_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CheckUsername choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_CheckUsername_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CheckUsername choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Users_CheckUsername_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CheckUsername execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Register a vote on a post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_VoteOnPost extends Temboo_Choreography
{
    /**
     * Register a vote on a post.
     *
     * @param Temboo_Session $session The session that owns this VoteOnPost choreography.
     * @return Disqus_Posts_VoteOnPost New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/VoteOnPost/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this VoteOnPost choreography.
     *
     * @param Disqus_Posts_VoteOnPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_VoteOnPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_VoteOnPost_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_VoteOnPost_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this VoteOnPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_VoteOnPost_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_VoteOnPost_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the VoteOnPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_VoteOnPost_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the VoteOnPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_VoteOnPost_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this VoteOnPost input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_VoteOnPost_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_VoteOnPost_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the PostID input for this VoteOnPost choreography.
     *
     * @param int $value (required, integer) The post ID for which a vote is being registered.
     * @return Disqus_Posts_VoteOnPost_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this VoteOnPost choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_VoteOnPost_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this VoteOnPost choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_VoteOnPost_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Vote input for this VoteOnPost choreography.
     *
     * @param int $value (required, integer) A numeric value for your vote. Valid choices are: -1, 0, or 1.
     * @return Disqus_Posts_VoteOnPost_Inputs For method chaining.
     */
    public function setVote($value)
    {
        return $this->set('Vote', $value);
    }

}


/**
 * Execution object for the VoteOnPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_VoteOnPost_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the VoteOnPost choreography.
     *
     * @param Temboo_Session $session The session that owns this VoteOnPost execution.
     * @param Disqus_Posts_VoteOnPost $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_VoteOnPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_VoteOnPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_VoteOnPost_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_VoteOnPost $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this VoteOnPost execution.
     *
     * @return Disqus_Posts_VoteOnPost_Results New results object.
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
     * Wraps results in appopriate results class for this VoteOnPost execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_VoteOnPost_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_VoteOnPost_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the VoteOnPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_VoteOnPost_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the VoteOnPost choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_VoteOnPost_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this VoteOnPost execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Restore a deleted post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_RestorePost extends Temboo_Choreography
{
    /**
     * Restore a deleted post.
     *
     * @param Temboo_Session $session The session that owns this RestorePost choreography.
     * @return Disqus_Posts_RestorePost New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/RestorePost/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this RestorePost choreography.
     *
     * @param Disqus_Posts_RestorePost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_RestorePost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_RestorePost_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_RestorePost_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this RestorePost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_RestorePost_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_RestorePost_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the RestorePost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_RestorePost_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the RestorePost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_RestorePost_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this RestorePost input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_RestorePost_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_RestorePost_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this RestorePost choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_RestorePost_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PostID input for this RestorePost choreography.
     *
     * @param int $value (required, integer) The post ID which is to be restored (undeleted).
     * @return Disqus_Posts_RestorePost_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this RestorePost choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_RestorePost_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this RestorePost choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_RestorePost_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

}


/**
 * Execution object for the RestorePost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_RestorePost_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the RestorePost choreography.
     *
     * @param Temboo_Session $session The session that owns this RestorePost execution.
     * @param Disqus_Posts_RestorePost $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_RestorePost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_RestorePost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_RestorePost_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_RestorePost $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this RestorePost execution.
     *
     * @return Disqus_Posts_RestorePost_Results New results object.
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
     * Wraps results in appopriate results class for this RestorePost execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_RestorePost_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_RestorePost_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the RestorePost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_RestorePost_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the RestorePost choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_RestorePost_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this RestorePost execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * List posts made by a user.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_UnfollowUser extends Temboo_Choreography
{
    /**
     * List posts made by a user.
     *
     * @param Temboo_Session $session The session that owns this UnfollowUser choreography.
     * @return Disqus_Users_UnfollowUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Users/UnfollowUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UnfollowUser choreography.
     *
     * @param Disqus_Users_UnfollowUser_Inputs|array $inputs (optional) Inputs as Disqus_Users_UnfollowUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_UnfollowUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Users_UnfollowUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UnfollowUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_UnfollowUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Users_UnfollowUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UnfollowUser choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_UnfollowUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UnfollowUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_UnfollowUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UnfollowUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Users_UnfollowUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Users_UnfollowUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this UnfollowUser choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Users_UnfollowUser_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PublicKey input for this UnfollowUser choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Users_UnfollowUser_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this UnfollowUser choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Users_UnfollowUser_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the UserID input for this UnfollowUser choreography.
     *
     * @param string $value (conditional, string) The User ID that is to be unfollowed. If UserID is set, then Username must be null.
     * @return Disqus_Users_UnfollowUser_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }

    /**
     * Set the value for the Username input for this UnfollowUser choreography.
     *
     * @param string $value (conditional, string) A Disqus username.  If Username is being set, then UserID must be null.
     * @return Disqus_Users_UnfollowUser_Inputs For method chaining.
     */
    public function setUsername($value)
    {
        return $this->set('Username', $value);
    }
}


/**
 * Execution object for the UnfollowUser choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_UnfollowUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UnfollowUser choreography.
     *
     * @param Temboo_Session $session The session that owns this UnfollowUser execution.
     * @param Disqus_Users_UnfollowUser $choreo The choregraphy object for this execution.
     * @param Disqus_Users_UnfollowUser_Inputs|array $inputs (optional) Inputs as Disqus_Users_UnfollowUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_UnfollowUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Users_UnfollowUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UnfollowUser execution.
     *
     * @return Disqus_Users_UnfollowUser_Results New results object.
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
     * Wraps results in appopriate results class for this UnfollowUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Users_UnfollowUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Users_UnfollowUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UnfollowUser choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_UnfollowUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UnfollowUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Users_UnfollowUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UnfollowUser execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Remove a post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_RemovePost extends Temboo_Choreography
{
    /**
     * Remove a post.
     *
     * @param Temboo_Session $session The session that owns this RemovePost choreography.
     * @return Disqus_Posts_RemovePost New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/RemovePost/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this RemovePost choreography.
     *
     * @param Disqus_Posts_RemovePost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_RemovePost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_RemovePost_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_RemovePost_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this RemovePost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_RemovePost_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_RemovePost_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the RemovePost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_RemovePost_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the RemovePost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_RemovePost_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this RemovePost input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_RemovePost_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_RemovePost_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this RemovePost choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_RemovePost_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PostID input for this RemovePost choreography.
     *
     * @param int $value (required, integer) The post ID which is to be removed.
     * @return Disqus_Posts_RemovePost_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this RemovePost choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_RemovePost_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this RemovePost choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_RemovePost_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

}


/**
 * Execution object for the RemovePost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_RemovePost_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the RemovePost choreography.
     *
     * @param Temboo_Session $session The session that owns this RemovePost execution.
     * @param Disqus_Posts_RemovePost $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_RemovePost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_RemovePost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_RemovePost_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_RemovePost $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this RemovePost execution.
     *
     * @return Disqus_Posts_RemovePost_Results New results object.
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
     * Wraps results in appopriate results class for this RemovePost execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_RemovePost_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_RemovePost_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the RemovePost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_RemovePost_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the RemovePost choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_RemovePost_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this RemovePost execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Create a new post for the authenticated user.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_CreateAuthenticatedPost extends Temboo_Choreography
{
    /**
     * Create a new post for the authenticated user.
     *
     * @param Temboo_Session $session The session that owns this CreateAuthenticatedPost choreography.
     * @return Disqus_Posts_CreateAuthenticatedPost New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/CreateAuthenticatedPost/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateAuthenticatedPost choreography.
     *
     * @param Disqus_Posts_CreateAuthenticatedPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_CreateAuthenticatedPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_CreateAuthenticatedPost_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_CreateAuthenticatedPost_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateAuthenticatedPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_CreateAuthenticatedPost_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateAuthenticatedPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_CreateAuthenticatedPost_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateAuthenticatedPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateAuthenticatedPost input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Date input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (optional, string) The date of the post, either in Unix timestamp format, or ISO datetime standard. You must be a moderator to do this.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setDate($value)
    {
        return $this->set('Date', $value);
    }

    /**
     * Set the value for the IPAddress input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (optional, string) The author's IP address. You must be a moderator to do this.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setIPAddress($value)
    {
        return $this->set('IPAddress', $value);
    }

    /**
     * Set the value for the ParentPost input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (conditional, string) The ID of a parent post to which the new post will be responding to. Either ParentPost, or Thread must be set, or both.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setParentPost($value)
    {
        return $this->set('ParentPost', $value);
    }

    /**
     * Set the value for the PostContent input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (required, string) The text of this post.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setPostContent($value)
    {
        return $this->set('PostContent', $value);
    }

    /**
     * Set the value for the PostState input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (optional, string) Specify the state of the post (comment). Available options include: unapproved, approved, spam, killed. You must be a moderator to do this. If set, pre-approval validation will be skipped.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setPostState($value)
    {
        return $this->set('PostState', $value);
    }

    /**
     * Set the value for the PublicKey input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Thread input for this CreateAuthenticatedPost choreography.
     *
     * @param string $value (conditional, string) The thread ID to attach the new post to. Either ParentPost, or Thread must be set, or both.
     * @return Disqus_Posts_CreateAuthenticatedPost_Inputs For method chaining.
     */
    public function setThread($value)
    {
        return $this->set('Thread', $value);
    }

}


/**
 * Execution object for the CreateAuthenticatedPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_CreateAuthenticatedPost_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateAuthenticatedPost choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateAuthenticatedPost execution.
     * @param Disqus_Posts_CreateAuthenticatedPost $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_CreateAuthenticatedPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_CreateAuthenticatedPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_CreateAuthenticatedPost_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_CreateAuthenticatedPost $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateAuthenticatedPost execution.
     *
     * @return Disqus_Posts_CreateAuthenticatedPost_Results New results object.
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
     * Wraps results in appopriate results class for this CreateAuthenticatedPost execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_CreateAuthenticatedPost_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_CreateAuthenticatedPost_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateAuthenticatedPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_CreateAuthenticatedPost_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateAuthenticatedPost choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_CreateAuthenticatedPost_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateAuthenticatedPost execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Follows the specified user.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_FollowUser extends Temboo_Choreography
{
    /**
     * Follows the specified user.
     *
     * @param Temboo_Session $session The session that owns this FollowUser choreography.
     * @return Disqus_Users_FollowUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Users/FollowUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this FollowUser choreography.
     *
     * @param Disqus_Users_FollowUser_Inputs|array $inputs (optional) Inputs as Disqus_Users_FollowUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_FollowUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Users_FollowUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this FollowUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_FollowUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Users_FollowUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the FollowUser choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_FollowUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the FollowUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_FollowUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this FollowUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Users_FollowUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Users_FollowUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this FollowUser choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Users_FollowUser_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Callback input for this FollowUser choreography.
     *
     * @param string $value (optional, string) The name of a callback function to wrap the respone in. Used when setting ResponseFormat to "jsonp".
     * @return Disqus_Users_FollowUser_Inputs For method chaining.
     */
    public function setCallback($value)
    {
        return $this->set('Callback', $value);
    }

    /**
     * Set the value for the PublicKey input for this FollowUser choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Users_FollowUser_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this FollowUser choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Users_FollowUser_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the UserID input for this FollowUser choreography.
     *
     * @param string $value (conditional, string) The User ID that is to be followed. If UserID is set, then Username must be null.
     * @return Disqus_Users_FollowUser_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }

    /**
     * Set the value for the Username input for this FollowUser choreography.
     *
     * @param string $value (conditional, string) The Disqus username that is to be followed.  If Username is being set, then UserID must be null.
     * @return Disqus_Users_FollowUser_Inputs For method chaining.
     */
    public function setUsername($value)
    {
        return $this->set('Username', $value);
    }

}


/**
 * Execution object for the FollowUser choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_FollowUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the FollowUser choreography.
     *
     * @param Temboo_Session $session The session that owns this FollowUser execution.
     * @param Disqus_Users_FollowUser $choreo The choregraphy object for this execution.
     * @param Disqus_Users_FollowUser_Inputs|array $inputs (optional) Inputs as Disqus_Users_FollowUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_FollowUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Users_FollowUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this FollowUser execution.
     *
     * @return Disqus_Users_FollowUser_Results New results object.
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
     * Wraps results in appopriate results class for this FollowUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Users_FollowUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Users_FollowUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the FollowUser choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_FollowUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the FollowUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Users_FollowUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this FollowUser execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieve a list of forums a user has been active on.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListActiveForums extends Temboo_Choreography
{
    /**
     * Retrieve a list of forums a user has been active on.
     *
     * @param Temboo_Session $session The session that owns this ListActiveForums choreography.
     * @return Disqus_Users_ListActiveForums New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Users/ListActiveForums/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListActiveForums choreography.
     *
     * @param Disqus_Users_ListActiveForums_Inputs|array $inputs (optional) Inputs as Disqus_Users_ListActiveForums_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_ListActiveForums_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Users_ListActiveForums_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListActiveForums choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_ListActiveForums_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Users_ListActiveForums_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListActiveForums choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListActiveForums_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListActiveForums choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_ListActiveForums_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListActiveForums input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Cursor input for this ListActiveForums choreography.
     *
     * @param string $value (optional, string) Default is set to null.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setCursor($value)
    {
        return $this->set('Cursor', $value);
    }

    /**
     * Set the value for the Limit input for this ListActiveForums choreography.
     *
     * @param int $value (optional, integer) The number of records to return. Defaults to 25.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Order input for this ListActiveForums choreography.
     *
     * @param string $value (optional, string) The sort order for the results. Valid values are: asc or desc. Default is set to: asc.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setOrder($value)
    {
        return $this->set('Order', $value);
    }

    /**
     * Set the value for the PublicKey input for this ListActiveForums choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ListActiveForums choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the SinceID input for this ListActiveForums choreography.
     *
     * @param int $value (optional, integer) A Unix timestamp (or ISO datetime standard) to obtain results from. Default is set to null.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setSinceID($value)
    {
        return $this->set('SinceID', $value);
    }

    /**
     * Set the value for the UserID input for this ListActiveForums choreography.
     *
     * @param string $value (conditional, string) the Disqus User ID, for which active forum information will be retrieved.  If UserID is set, then Username must be null.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }

    /**
     * Set the value for the Username input for this ListActiveForums choreography.
     *
     * @param string $value (conditional, string) A Disqus username.  If Username is being set, then UserID must be null.
     * @return Disqus_Users_ListActiveForums_Inputs For method chaining.
     */
    public function setUsername($value)
    {
        return $this->set('Username', $value);
    }
}


/**
 * Execution object for the ListActiveForums choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListActiveForums_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListActiveForums choreography.
     *
     * @param Temboo_Session $session The session that owns this ListActiveForums execution.
     * @param Disqus_Users_ListActiveForums $choreo The choregraphy object for this execution.
     * @param Disqus_Users_ListActiveForums_Inputs|array $inputs (optional) Inputs as Disqus_Users_ListActiveForums_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_ListActiveForums_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Users_ListActiveForums $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListActiveForums execution.
     *
     * @return Disqus_Users_ListActiveForums_Results New results object.
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
     * Wraps results in appopriate results class for this ListActiveForums execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Users_ListActiveForums_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Users_ListActiveForums_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListActiveForums choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListActiveForums_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListActiveForums choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Users_ListActiveForums_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListActiveForums execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Subscribe to a thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_SubscribeToThread extends Temboo_Choreography
{
    /**
     * Subscribe to a thread.
     *
     * @param Temboo_Session $session The session that owns this SubscribeToThread choreography.
     * @return Disqus_Threads_SubscribeToThread New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/SubscribeToThread/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SubscribeToThread choreography.
     *
     * @param Disqus_Threads_SubscribeToThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_SubscribeToThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_SubscribeToThread_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_SubscribeToThread_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SubscribeToThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_SubscribeToThread_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_SubscribeToThread_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SubscribeToThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_SubscribeToThread_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SubscribeToThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_SubscribeToThread_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SubscribeToThread input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this SubscribeToThread choreography.
     *
     * @param string $value (optional, string) An email address to use when subscribing to the thread.
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Forum input for this SubscribeToThread choreography.
     *
     * @param int $value (optional, integer) The forum ID of a thread that is to be subscribed to. Required if setting either ThreadByIdentification, or ThreadByLink.
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the PublicKey input for this SubscribeToThread choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this SubscribeToThread choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this SubscribeToThread choreography.
     *
     * @param int $value (conditional, integer) Enter an ID of a thread that is to be subscribed to. Required unless specifying ThreadIdentifier or ThreadLink. If using this parameter, ThreadIdentifier cannot be set.
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this SubscribeToThread choreography.
     *
     * @param string $value (conditional, string) The identifier for the thread that is to be subscribed to.  Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this SubscribeToThread choreography.
     *
     * @param string $value (conditional, string) A link pointing to the thread that is to be subscribed to. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_SubscribeToThread_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }

}


/**
 * Execution object for the SubscribeToThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_SubscribeToThread_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SubscribeToThread choreography.
     *
     * @param Temboo_Session $session The session that owns this SubscribeToThread execution.
     * @param Disqus_Threads_SubscribeToThread $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_SubscribeToThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_SubscribeToThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_SubscribeToThread_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_SubscribeToThread $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SubscribeToThread execution.
     *
     * @return Disqus_Threads_SubscribeToThread_Results New results object.
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
     * Wraps results in appopriate results class for this SubscribeToThread execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_SubscribeToThread_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_SubscribeToThread_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SubscribeToThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_SubscribeToThread_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SubscribeToThread choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_SubscribeToThread_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this SubscribeToThread execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * List posts made by a user.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListPosts extends Temboo_Choreography
{
    /**
     * List posts made by a user.
     *
     * @param Temboo_Session $session The session that owns this ListPosts choreography.
     * @return Disqus_Users_ListPosts New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Users/ListPosts/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListPosts choreography.
     *
     * @param Disqus_Users_ListPosts_Inputs|array $inputs (optional) Inputs as Disqus_Users_ListPosts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_ListPosts_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Users_ListPosts_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListPosts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_ListPosts_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Users_ListPosts_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListPosts_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListPosts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_ListPosts_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListPosts input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Cursor input for this ListPosts choreography.
     *
     * @param string $value (optional, string) Default is set to null.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setCursor($value)
    {
        return $this->set('Cursor', $value);
    }

    /**
     * Set the value for the Included input for this ListPosts choreography.
     *
     * @param string $value (optional, string) Specify the type of posts that are to be retrieved. Valid values are: unapproved, approved, spam, deleted, flagged, highlighted.  Default is: approved.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setIncluded($value)
    {
        return $this->set('Included', $value);
    }

    /**
     * Set the value for the Limit input for this ListPosts choreography.
     *
     * @param int $value (optional, integer) The number of records to return. Defaults to 25.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Order input for this ListPosts choreography.
     *
     * @param string $value (optional, string) The sort order for the results. valid values are: asc or desc. Default is set to: asc.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setOrder($value)
    {
        return $this->set('Order', $value);
    }

    /**
     * Set the value for the PublicKey input for this ListPosts choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the Related input for this ListPosts choreography.
     *
     * @param string $value (optional, string) Indicates the relations to include with your response. Valid values are: forum, author, category.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setRelated($value)
    {
        return $this->set('Related', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ListPosts choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the SinceID input for this ListPosts choreography.
     *
     * @param int $value (optional, integer) Default is set to null.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setSinceID($value)
    {
        return $this->set('SinceID', $value);
    }

    /**
     * Set the value for the UserID input for this ListPosts choreography.
     *
     * @param string $value (optional, string) The Disqus User ID, for which active forum information will be retrieved.  If UserID is set, then Username must be null.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }

    /**
     * Set the value for the Username input for this ListPosts choreography.
     *
     * @param string $value (optional, string) A Disqus username. If Username is being set, then UserID must be null.
     * @return Disqus_Users_ListPosts_Inputs For method chaining.
     */
    public function setUsername($value)
    {
        return $this->set('Username', $value);
    }
}


/**
 * Execution object for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListPosts_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListPosts choreography.
     *
     * @param Temboo_Session $session The session that owns this ListPosts execution.
     * @param Disqus_Users_ListPosts $choreo The choregraphy object for this execution.
     * @param Disqus_Users_ListPosts_Inputs|array $inputs (optional) Inputs as Disqus_Users_ListPosts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_ListPosts_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Users_ListPosts $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListPosts execution.
     *
     * @return Disqus_Users_ListPosts_Results New results object.
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
     * Wraps results in appopriate results class for this ListPosts execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Users_ListPosts_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Users_ListPosts_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListPosts_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListPosts choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Users_ListPosts_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListPosts execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieve a list of posts within a thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListPosts extends Temboo_Choreography
{
    /**
     * Retrieve a list of posts within a thread.
     *
     * @param Temboo_Session $session The session that owns this ListPosts choreography.
     * @return Disqus_Threads_ListPosts New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/ListPosts/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListPosts choreography.
     *
     * @param Disqus_Threads_ListPosts_Inputs|array $inputs (optional) Inputs as Disqus_Threads_ListPosts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_ListPosts_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_ListPosts_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListPosts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_ListPosts_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_ListPosts_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListPosts_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListPosts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_ListPosts_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListPosts input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Cursor input for this ListPosts choreography.
     *
     * @param string $value (optional, string) Default is set to null.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setCursor($value)
    {
        return $this->set('Cursor', $value);
    }

    /**
     * Set the value for the Forum input for this ListPosts choreography.
     *
     * @param int $value (optional, integer) A Disqus forum ID (AKA a short name). If null, threads from all forums moderated by the authenticating user will be retrieved.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the Include input for this ListPosts choreography.
     *
     * @param string $value (optional, string) Specify a post status parameter to filter results by. Valid parameters include: unapproved, approved, spam, deleted, flagged.  Default is set to: approved.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setInclude($value)
    {
        return $this->set('Include', $value);
    }

    /**
     * Set the value for the Limit input for this ListPosts choreography.
     *
     * @param int $value (optional, integer) The number of records to return. Maximum value is 100.  Defaults to 25.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Order input for this ListPosts choreography.
     *
     * @param string $value (optional, string) The sort order of the results. Valid values are: asc or desc. Default is set to: asc.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setOrder($value)
    {
        return $this->set('Order', $value);
    }

    /**
     * Set the value for the PublicKey input for this ListPosts choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the Query input for this ListPosts choreography.
     *
     * @param string $value (optional, string) A search string to limit results.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the Related input for this ListPosts choreography.
     *
     * @param string $value (optional, string) Specify a related thread or forum that are to be included in the response.  Valid entries include: forum.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setRelated($value)
    {
        return $this->set('Related', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ListPosts choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Since input for this ListPosts choreography.
     *
     * @param int $value (optional, integer) A Unix timestamp (or ISO datetime standard) to obtain results from. Default is set to null.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setSince($value)
    {
        return $this->set('Since', $value);
    }

    /**
     * Set the value for the ThreadID input for this ListPosts choreography.
     *
     * @param string $value (conditional, string) A Thread ID to narrow post search results. Required unless specifying ThreadIdentifier or ThreadLink.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this ListPosts choreography.
     *
     * @param string $value (optional, string) An identifier to retrieve associated thread details. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this ListPosts choreography.
     *
     * @param string $value (optional, string) A link pointing to the thread that is to be retrieved. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_ListPosts_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }
}


/**
 * Execution object for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListPosts_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListPosts choreography.
     *
     * @param Temboo_Session $session The session that owns this ListPosts execution.
     * @param Disqus_Threads_ListPosts $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_ListPosts_Inputs|array $inputs (optional) Inputs as Disqus_Threads_ListPosts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_ListPosts_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_ListPosts $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListPosts execution.
     *
     * @return Disqus_Threads_ListPosts_Results New results object.
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
     * Wraps results in appopriate results class for this ListPosts execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_ListPosts_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_ListPosts_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ListPosts_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListPosts choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_ListPosts_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListPosts execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Restore a thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_RestoreThread extends Temboo_Choreography
{
    /**
     * Restore a thread.
     *
     * @param Temboo_Session $session The session that owns this RestoreThread choreography.
     * @return Disqus_Threads_RestoreThread New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/RestoreThread/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this RestoreThread choreography.
     *
     * @param Disqus_Threads_RestoreThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_RestoreThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_RestoreThread_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_RestoreThread_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this RestoreThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_RestoreThread_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_RestoreThread_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the RestoreThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_RestoreThread_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the RestoreThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_RestoreThread_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this RestoreThread input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this RestoreThread choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Forum input for this RestoreThread choreography.
     *
     * @param int $value (optional, integer) The forum ID of a thread that is to be restored.  Required if setting either ThreadByIdentification, or ThreadByLink.
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the PublicKey input for this RestoreThread choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this RestoreThread choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this RestoreThread choreography.
     *
     * @param int $value (conditional, integer) The ID of a thread that is to be restored. Required unless specifying ThreadIdentifier or ThreadLink. If using this parameter, ThreadIdentifier cannot be set.
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this RestoreThread choreography.
     *
     * @param string $value (optional, string) The identifier for the thread that is to be restored.  Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this RestoreThread choreography.
     *
     * @param string $value (optional, string) A link pointing to the thread that is to be restored. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_RestoreThread_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }

}


/**
 * Execution object for the RestoreThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_RestoreThread_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the RestoreThread choreography.
     *
     * @param Temboo_Session $session The session that owns this RestoreThread execution.
     * @param Disqus_Threads_RestoreThread $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_RestoreThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_RestoreThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_RestoreThread_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_RestoreThread $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this RestoreThread execution.
     *
     * @return Disqus_Threads_RestoreThread_Results New results object.
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
     * Wraps results in appopriate results class for this RestoreThread execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_RestoreThread_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_RestoreThread_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the RestoreThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_RestoreThread_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the RestoreThread choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_RestoreThread_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this RestoreThread execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieve a list of posts ordered by date of creation.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ListPosts extends Temboo_Choreography
{
    /**
     * Retrieve a list of posts ordered by date of creation.
     *
     * @param Temboo_Session $session The session that owns this ListPosts choreography.
     * @return Disqus_Posts_ListPosts New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/ListPosts/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListPosts choreography.
     *
     * @param Disqus_Posts_ListPosts_Inputs|array $inputs (optional) Inputs as Disqus_Posts_ListPosts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_ListPosts_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_ListPosts_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListPosts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_ListPosts_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_ListPosts_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ListPosts_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListPosts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_ListPosts_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListPosts input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this ListPosts choreography.
     *
     * @param string $value (optional, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Category input for this ListPosts choreography.
     *
     * @param int $value (optional, integer) Specify a category ID for which posts wil be retrieved.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setCategory($value)
    {
        return $this->set('Category', $value);
    }

    /**
     * Set the value for the Cursor input for this ListPosts choreography.
     *
     * @param string $value (optional, string) Default is set to null.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setCursor($value)
    {
        return $this->set('Cursor', $value);
    }

    /**
     * Set the value for the Forum input for this ListPosts choreography.
     *
     * @param string $value (optional, string) A Disqus forum name to display all posts contained in that specified forum.  If null, posts from all forums moderated by the authenticating user will be retrieved.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the Include input for this ListPosts choreography.
     *
     * @param string $value (optional, string) A post status parameter to filter results by. Valid parameters include: unapproved, approved, spam, deleted, flagged, highlighted.  Default is set to: approved.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setInclude($value)
    {
        return $this->set('Include', $value);
    }

    /**
     * Set the value for the Limit input for this ListPosts choreography.
     *
     * @param int $value (optional, integer) The number of records to return. Defaults to 25.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Order input for this ListPosts choreography.
     *
     * @param string $value (optional, string) The sort order of the results. Valid values are: asc or desc. Default is set to: asc.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setOrder($value)
    {
        return $this->set('Order', $value);
    }

    /**
     * Set the value for the PublicKey input for this ListPosts choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the Query input for this ListPosts choreography.
     *
     * @param string $value (optional, string) A search string to retrieve posts mathching the query.  Default is set to null.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the Related input for this ListPosts choreography.
     *
     * @param string $value (optional, string) Specify a related thread or forum that are to be included in the response.  Valid entries include: thread, or forum.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setRelated($value)
    {
        return $this->set('Related', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ListPosts choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the SinceID input for this ListPosts choreography.
     *
     * @param int $value (optional, integer) A Unix timestamp (or ISO datetime standard) to obtain results from. Default is set to null.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setSinceID($value)
    {
        return $this->set('SinceID', $value);
    }

    /**
     * Set the value for the ThreadID input for this ListPosts choreography.
     *
     * @param string $value (optional, string) The Thread ID to narrow post search results.
     * @return Disqus_Posts_ListPosts_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }
}


/**
 * Execution object for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ListPosts_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListPosts choreography.
     *
     * @param Temboo_Session $session The session that owns this ListPosts execution.
     * @param Disqus_Posts_ListPosts $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_ListPosts_Inputs|array $inputs (optional) Inputs as Disqus_Posts_ListPosts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_ListPosts_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_ListPosts $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListPosts execution.
     *
     * @return Disqus_Posts_ListPosts_Results New results object.
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
     * Wraps results in appopriate results class for this ListPosts execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_ListPosts_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_ListPosts_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListPosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ListPosts_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListPosts choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_ListPosts_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListPosts execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Creates an anonymous post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_CreateAnonymousPost extends Temboo_Choreography
{
    /**
     * Creates an anonymous post.
     *
     * @param Temboo_Session $session The session that owns this CreateAnonymousPost choreography.
     * @return Disqus_Posts_CreateAnonymousPost New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/CreateAnonymousPost/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateAnonymousPost choreography.
     *
     * @param Disqus_Posts_CreateAnonymousPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_CreateAnonymousPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_CreateAnonymousPost_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_CreateAnonymousPost_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateAnonymousPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_CreateAnonymousPost_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateAnonymousPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_CreateAnonymousPost_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateAnonymousPost choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateAnonymousPost input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AuthorEmail input for this CreateAnonymousPost choreography.
     *
     * @param string $value (required, string) The email address of the post author.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setAuthorEmail($value)
    {
        return $this->set('AuthorEmail', $value);
    }

    /**
     * Set the value for the AuthorName input for this CreateAnonymousPost choreography.
     *
     * @param string $value (optional, string) The name of the post author.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setAuthorName($value)
    {
        return $this->set('AuthorName', $value);
    }

    /**
     * Set the value for the AuthorURL input for this CreateAnonymousPost choreography.
     *
     * @param string $value (optional, string) The URL of the author's Disqus profile.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setAuthorURL($value)
    {
        return $this->set('AuthorURL', $value);
    }

    /**
     * Set the value for the ParentPost input for this CreateAnonymousPost choreography.
     *
     * @param string $value (conditional, string) The ID of a parent post to which the new post will be responding to. Either ParentPost, or Thread must be set, or both.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setParentPost($value)
    {
        return $this->set('ParentPost', $value);
    }

    /**
     * Set the value for the PostContent input for this CreateAnonymousPost choreography.
     *
     * @param string $value (required, string) The text of this post.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setPostContent($value)
    {
        return $this->set('PostContent', $value);
    }

    /**
     * Set the value for the PublicKey input for this CreateAnonymousPost choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this CreateAnonymousPost choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the Thread input for this CreateAnonymousPost choreography.
     *
     * @param string $value (conditional, string) The thread ID to attach the new post to. Either ParentPost, or Thread must be set, or both.
     * @return Disqus_Posts_CreateAnonymousPost_Inputs For method chaining.
     */
    public function setThread($value)
    {
        return $this->set('Thread', $value);
    }

}


/**
 * Execution object for the CreateAnonymousPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_CreateAnonymousPost_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateAnonymousPost choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateAnonymousPost execution.
     * @param Disqus_Posts_CreateAnonymousPost $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_CreateAnonymousPost_Inputs|array $inputs (optional) Inputs as Disqus_Posts_CreateAnonymousPost_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_CreateAnonymousPost_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_CreateAnonymousPost $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateAnonymousPost execution.
     *
     * @return Disqus_Posts_CreateAnonymousPost_Results New results object.
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
     * Wraps results in appopriate results class for this CreateAnonymousPost execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_CreateAnonymousPost_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_CreateAnonymousPost_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateAnonymousPost choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_CreateAnonymousPost_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateAnonymousPost choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_CreateAnonymousPost_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateAnonymousPost execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Obtain thread details.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ThreadDetails extends Temboo_Choreography
{
    /**
     * Obtain thread details.
     *
     * @param Temboo_Session $session The session that owns this ThreadDetails choreography.
     * @return Disqus_Threads_ThreadDetails New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/ThreadDetails/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ThreadDetails choreography.
     *
     * @param Disqus_Threads_ThreadDetails_Inputs|array $inputs (optional) Inputs as Disqus_Threads_ThreadDetails_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_ThreadDetails_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_ThreadDetails_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ThreadDetails choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_ThreadDetails_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_ThreadDetails_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ThreadDetails choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ThreadDetails_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ThreadDetails choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_ThreadDetails_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ThreadDetails input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Forum input for this ThreadDetails choreography.
     *
     * @param int $value (optional, integer) A forum ID.  Required if setting either ThreadByIdentification, or ThreadByLink.
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the PublicKey input for this ThreadDetails choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the Related input for this ThreadDetails choreography.
     *
     * @param string $value (optional, string) Specify a related thread or forum that are to be included in the response.  Valid entries include: author, category, or forum.
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function setRelated($value)
    {
        return $this->set('Related', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ThreadDetails choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this ThreadDetails choreography.
     *
     * @param int $value (conditional, integer) The ID of a thread that is to be retrieved. Required unless specifying ThreadIdentifier or ThreadLink. If using this parameter, ThreadIdentifier cannot be set.
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this ThreadDetails choreography.
     *
     * @param string $value (conditional, string) The identifier to retrieve associated thread details. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this ThreadDetails choreography.
     *
     * @param string $value (conditional, string) A link pointing to the thread that is to be retrieved. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_ThreadDetails_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }

}


/**
 * Execution object for the ThreadDetails choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ThreadDetails_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ThreadDetails choreography.
     *
     * @param Temboo_Session $session The session that owns this ThreadDetails execution.
     * @param Disqus_Threads_ThreadDetails $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_ThreadDetails_Inputs|array $inputs (optional) Inputs as Disqus_Threads_ThreadDetails_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_ThreadDetails_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_ThreadDetails $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ThreadDetails execution.
     *
     * @return Disqus_Threads_ThreadDetails_Results New results object.
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
     * Wraps results in appopriate results class for this ThreadDetails execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_ThreadDetails_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_ThreadDetails_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ThreadDetails choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_ThreadDetails_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ThreadDetails choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_ThreadDetails_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ThreadDetails execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Approves a post.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ApprovePosts extends Temboo_Choreography
{
    /**
     * Approves a post.
     *
     * @param Temboo_Session $session The session that owns this ApprovePosts choreography.
     * @return Disqus_Posts_ApprovePosts New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Posts/ApprovePosts/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ApprovePosts choreography.
     *
     * @param Disqus_Posts_ApprovePosts_Inputs|array $inputs (optional) Inputs as Disqus_Posts_ApprovePosts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_ApprovePosts_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Posts_ApprovePosts_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ApprovePosts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_ApprovePosts_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Posts_ApprovePosts_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ApprovePosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ApprovePosts_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ApprovePosts choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Posts_ApprovePosts_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ApprovePosts input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Posts_ApprovePosts_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Posts_ApprovePosts_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this ApprovePosts choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Posts_ApprovePosts_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the PostID input for this ApprovePosts choreography.
     *
     * @param int $value (required, integer) The post ID which is to be approved. Note that you must be a forum moderator to approve posts.
     * @return Disqus_Posts_ApprovePosts_Inputs For method chaining.
     */
    public function setPostID($value)
    {
        return $this->set('PostID', $value);
    }

    /**
     * Set the value for the PublicKey input for this ApprovePosts choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Posts_ApprovePosts_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ApprovePosts choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Posts_ApprovePosts_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

}


/**
 * Execution object for the ApprovePosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ApprovePosts_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ApprovePosts choreography.
     *
     * @param Temboo_Session $session The session that owns this ApprovePosts execution.
     * @param Disqus_Posts_ApprovePosts $choreo The choregraphy object for this execution.
     * @param Disqus_Posts_ApprovePosts_Inputs|array $inputs (optional) Inputs as Disqus_Posts_ApprovePosts_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Posts_ApprovePosts_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Posts_ApprovePosts $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ApprovePosts execution.
     *
     * @return Disqus_Posts_ApprovePosts_Results New results object.
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
     * Wraps results in appopriate results class for this ApprovePosts execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Posts_ApprovePosts_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Posts_ApprovePosts_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ApprovePosts choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Posts_ApprovePosts_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ApprovePosts choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Posts_ApprovePosts_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ApprovePosts execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Remove a thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_RemoveThread extends Temboo_Choreography
{
    /**
     * Remove a thread.
     *
     * @param Temboo_Session $session The session that owns this RemoveThread choreography.
     * @return Disqus_Threads_RemoveThread New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/RemoveThread/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this RemoveThread choreography.
     *
     * @param Disqus_Threads_RemoveThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_RemoveThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_RemoveThread_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_RemoveThread_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this RemoveThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_RemoveThread_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_RemoveThread_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the RemoveThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_RemoveThread_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the RemoveThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_RemoveThread_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this RemoveThread input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this RemoveThread choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Forum input for this RemoveThread choreography.
     *
     * @param int $value (optional, integer) The forum ID of a thread that is to be removed.  Required if setting either ThreadByIdentification, or ThreadByLink.
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the PublicKey input for this RemoveThread choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this RemoveThread choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this RemoveThread choreography.
     *
     * @param int $value (conditional, integer) The ID of a thread that is to be removed. Required unless specifying ThreadIdentifier or ThreadLink. If using this parameter, ThreadIdentifier cannot be set.
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this RemoveThread choreography.
     *
     * @param string $value (optional, string) The identifier for the thread that is to be removed.  Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this RemoveThread choreography.
     *
     * @param string $value (optional, string) A link pointing to the thread that is to be removed. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_RemoveThread_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }

}


/**
 * Execution object for the RemoveThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_RemoveThread_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the RemoveThread choreography.
     *
     * @param Temboo_Session $session The session that owns this RemoveThread execution.
     * @param Disqus_Threads_RemoveThread $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_RemoveThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_RemoveThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_RemoveThread_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_RemoveThread $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this RemoveThread execution.
     *
     * @return Disqus_Threads_RemoveThread_Results New results object.
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
     * Wraps results in appopriate results class for this RemoveThread execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_RemoveThread_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_RemoveThread_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the RemoveThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_RemoveThread_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the RemoveThread choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_RemoveThread_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this RemoveThread execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieve a list of forums owned by the specified user.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListForums extends Temboo_Choreography
{
    /**
     * Retrieve a list of forums owned by the specified user.
     *
     * @param Temboo_Session $session The session that owns this ListForums choreography.
     * @return Disqus_Users_ListForums New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Users/ListForums/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListForums choreography.
     *
     * @param Disqus_Users_ListForums_Inputs|array $inputs (optional) Inputs as Disqus_Users_ListForums_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_ListForums_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Users_ListForums_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListForums choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_ListForums_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Users_ListForums_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListForums choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListForums_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListForums choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Users_ListForums_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListForums input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Cursor input for this ListForums choreography.
     *
     * @param string $value (optional, string) Default is set to null.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setCursor($value)
    {
        return $this->set('Cursor', $value);
    }

    /**
     * Set the value for the Limit input for this ListForums choreography.
     *
     * @param int $value (optional, integer) The number of records to return. Defaults to 25.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setLimit($value)
    {
        return $this->set('Limit', $value);
    }

    /**
     * Set the value for the Order input for this ListForums choreography.
     *
     * @param string $value (optional, string) The sort order for the results. Valid vaues are: asc or desc. Default is set to: asc.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setOrder($value)
    {
        return $this->set('Order', $value);
    }

    /**
     * Set the value for the PublicKey input for this ListForums choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this ListForums choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default), jsonp, or rss.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the SinceID input for this ListForums choreography.
     *
     * @param int $value (optional, integer) A Unix timestamp (or ISO datetime standard) to obtain results from. Default is set to null.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setSinceID($value)
    {
        return $this->set('SinceID', $value);
    }

    /**
     * Set the value for the UserID input for this ListForums choreography.
     *
     * @param string $value (conditional, string) The Disqus User ID, for which active forum information will be retrieved.  If UserID is set, then Username must be null.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }

    /**
     * Set the value for the Username input for this ListForums choreography.
     *
     * @param string $value (conditional, string) A Disqus username.  If Username is being set, then UserID must be null.
     * @return Disqus_Users_ListForums_Inputs For method chaining.
     */
    public function setUsername($value)
    {
        return $this->set('Username', $value);
    }
}


/**
 * Execution object for the ListForums choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListForums_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListForums choreography.
     *
     * @param Temboo_Session $session The session that owns this ListForums execution.
     * @param Disqus_Users_ListForums $choreo The choregraphy object for this execution.
     * @param Disqus_Users_ListForums_Inputs|array $inputs (optional) Inputs as Disqus_Users_ListForums_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Users_ListForums_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Users_ListForums $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListForums execution.
     *
     * @return Disqus_Users_ListForums_Results New results object.
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
     * Wraps results in appopriate results class for this ListForums execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Users_ListForums_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Users_ListForums_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListForums choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Users_ListForums_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListForums choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Users_ListForums_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListForums execution.
     *
     * @return string The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Open a thread.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_OpenThread extends Temboo_Choreography
{
    /**
     * Open a thread.
     *
     * @param Temboo_Session $session The session that owns this OpenThread choreography.
     * @return Disqus_Threads_OpenThread New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Disqus/Threads/OpenThread/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this OpenThread choreography.
     *
     * @param Disqus_Threads_OpenThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_OpenThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_OpenThread_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Disqus_Threads_OpenThread_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this OpenThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_OpenThread_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Disqus_Threads_OpenThread_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the OpenThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_OpenThread_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the OpenThread choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Disqus_Threads_OpenThread_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this OpenThread input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the AccessToken input for this OpenThread choreography.
     *
     * @param string $value (required, string) A valid OAuth 2.0 access token.
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function setAccessToken($value)
    {
        return $this->set('AccessToken', $value);
    }

    /**
     * Set the value for the Forum input for this OpenThread choreography.
     *
     * @param int $value (optional, integer) The forum ID of the thread that is to be opened.  Required if setting either ThreadByIdentification, or ThreadByLink.
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function setForum($value)
    {
        return $this->set('Forum', $value);
    }

    /**
     * Set the value for the PublicKey input for this OpenThread choreography.
     *
     * @param string $value (required, string) The Public Key provided by Disqus (AKA the Client ID).
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function setPublicKey($value)
    {
        return $this->set('PublicKey', $value);
    }

    /**
     * Set the value for the ResponseFormat input for this OpenThread choreography.
     *
     * @param string $value (optional, string) The format that the response should be in. Valid values are: json (the default) and jsonp.
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function setResponseFormat($value)
    {
        return $this->set('ResponseFormat', $value);
    }

    /**
     * Set the value for the ThreadID input for this OpenThread choreography.
     *
     * @param int $value (conditional, integer) The ID of a thread. Required unless specifying ThreadIdentifier or ThreadLink. If using this parameter, ThreadIdentifier cannot be set. 
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function setThreadID($value)
    {
        return $this->set('ThreadID', $value);
    }

    /**
     * Set the value for the ThreadIdentifier input for this OpenThread choreography.
     *
     * @param string $value (conditional, string) The identifier of the thread that is to be opened. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadLink cannot be used.
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function setThreadIdentifier($value)
    {
        return $this->set('ThreadIdentifier', $value);
    }

    /**
     * Set the value for the ThreadLink input for this OpenThread choreography.
     *
     * @param string $value (conditional, string) A link pointing to the thread that is to be opened. Note that a Forum must also be provided when using this parameter. If set, ThreadID and ThreadIdentifier cannot be set.
     * @return Disqus_Threads_OpenThread_Inputs For method chaining.
     */
    public function setThreadLink($value)
    {
        return $this->set('ThreadLink', $value);
    }

}


/**
 * Execution object for the OpenThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_OpenThread_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the OpenThread choreography.
     *
     * @param Temboo_Session $session The session that owns this OpenThread execution.
     * @param Disqus_Threads_OpenThread $choreo The choregraphy object for this execution.
     * @param Disqus_Threads_OpenThread_Inputs|array $inputs (optional) Inputs as Disqus_Threads_OpenThread_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Disqus_Threads_OpenThread_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Disqus_Threads_OpenThread $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this OpenThread execution.
     *
     * @return Disqus_Threads_OpenThread_Results New results object.
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
     * Wraps results in appopriate results class for this OpenThread execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Disqus_Threads_OpenThread_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Disqus_Threads_OpenThread_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the OpenThread choreography.
 *
 * @package Temboo
 * @subpackage Disqus
 */
class Disqus_Threads_OpenThread_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the OpenThread choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Disqus_Threads_OpenThread_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this OpenThread execution.
     *
     * @return string (json) The response from Disqus.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

?>