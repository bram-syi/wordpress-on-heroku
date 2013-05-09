<?php

/**
 * Temboo PHP SDK Zendesk classes
 *
 * Execute Choreographies from the Temboo Zendesk bundle.
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
 * @subpackage Zendesk
 * @author     Temboo, Inc.
 * @copyright  2012 Temboo, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @version    1.7
 * @link       http://live.temboo.com/sdk/php
 */


/**
 * Retrieves a list of all tickets involving a specified organization.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListUsersByOrganization extends Temboo_Choreography
{
    /**
     * Retrieves a list of all tickets involving a specified organization.
     *
     * @param Temboo_Session $session The session that owns this ListUsersByOrganization choreography.
     * @return Zendesk_Users_ListUsersByOrganization New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/ListUsersByOrganization/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListUsersByOrganization choreography.
     *
     * @param Zendesk_Users_ListUsersByOrganization_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ListUsersByOrganization_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ListUsersByOrganization_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_ListUsersByOrganization_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListUsersByOrganization choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ListUsersByOrganization_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_ListUsersByOrganization_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListUsersByOrganization choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListUsersByOrganization_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListUsersByOrganization choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ListUsersByOrganization_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListUsersByOrganization input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_ListUsersByOrganization_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_ListUsersByOrganization_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListUsersByOrganization choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Users_ListUsersByOrganization_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the OrganizationID input for this ListUsersByOrganization choreography.
     *
     * @param int $value (required, integer) The ID number of the organization.
     * @return Zendesk_Users_ListUsersByOrganization_Inputs For method chaining.
     */
    public function setOrganizationID($value)
    {
        return $this->set('OrganizationID', $value);
    }

    /**
     * Set the value for the Password input for this ListUsersByOrganization choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Users_ListUsersByOrganization_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListUsersByOrganization choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Users_ListUsersByOrganization_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListUsersByOrganization choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListUsersByOrganization_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListUsersByOrganization choreography.
     *
     * @param Temboo_Session $session The session that owns this ListUsersByOrganization execution.
     * @param Zendesk_Users_ListUsersByOrganization $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_ListUsersByOrganization_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ListUsersByOrganization_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ListUsersByOrganization_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_ListUsersByOrganization $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListUsersByOrganization execution.
     *
     * @return Zendesk_Users_ListUsersByOrganization_Results New results object.
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
     * Wraps results in appopriate results class for this ListUsersByOrganization execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_ListUsersByOrganization_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_ListUsersByOrganization_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListUsersByOrganization choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListUsersByOrganization_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListUsersByOrganization choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_ListUsersByOrganization_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListUsersByOrganization execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Updates an existing ticket.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_UpdateTicket extends Temboo_Choreography
{
    /**
     * Updates an existing ticket.
     *
     * @param Temboo_Session $session The session that owns this UpdateTicket choreography.
     * @return Zendesk_Tickets_UpdateTicket New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/UpdateTicket/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UpdateTicket choreography.
     *
     * @param Zendesk_Tickets_UpdateTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_UpdateTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_UpdateTicket_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_UpdateTicket_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UpdateTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_UpdateTicket_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_UpdateTicket_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UpdateTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_UpdateTicket_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UpdateTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_UpdateTicket_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UpdateTicket input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Comment input for this UpdateTicket choreography.
     *
     * @param string $value (conditional, string) The text for a ticket comment.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setComment($value)
    {
        return $this->set('Comment', $value);
    }

    /**
     * Set the value for the Email input for this UpdateTicket choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Metadata input for this UpdateTicket choreography.
     *
     * @param string $value (optional, json) Ticket metadata formatted in JSON. See docs for more information on the input format.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setMetadata($value)
    {
        return $this->set('Metadata', $value);
    }

    /**
     * Set the value for the Password input for this UpdateTicket choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Public input for this UpdateTicket choreography.
     *
     * @param bool $value (optional, boolean) A flag indicating if this update is public or not. Defaults to "true"
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setPublic($value)
    {
        return $this->set('Public', $value);
    }

    /**
     * Set the value for the Server input for this UpdateTicket choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. temboocare.zendesk.com).
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the Status input for this UpdateTicket choreography.
     *
     * @param string $value (conditional, string) The status of the ticket (i.e. solved, pending, open).
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setStatus($value)
    {
        return $this->set('Status', $value);
    }

    /**
     * Set the value for the TicketData input for this UpdateTicket choreography.
     *
     * @param string $value (optional, json) A JSON string containing the ticket information. This can be used as an alternative to the serialized ticket inputs in case you need to update tickets that have custom fields.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setTicketData($value)
    {
        return $this->set('TicketData', $value);
    }

    /**
     * Set the value for the TicketID input for this UpdateTicket choreography.
     *
     * @param int $value (required, integer) The id of the ticket being updated.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setTicketID($value)
    {
        return $this->set('TicketID', $value);
    }

    /**
     * Set the value for the Token input for this UpdateTicket choreography.
     *
     * @param string $value (optional, string) The token associated with an upload to attach to this ticket. Note that tokens can be retrieved by running the UploadFile Choreo.
     * @return Zendesk_Tickets_UpdateTicket_Inputs For method chaining.
     */
    public function setToken($value)
    {
        return $this->set('Token', $value);
    }
}


/**
 * Execution object for the UpdateTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_UpdateTicket_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UpdateTicket choreography.
     *
     * @param Temboo_Session $session The session that owns this UpdateTicket execution.
     * @param Zendesk_Tickets_UpdateTicket $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_UpdateTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_UpdateTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_UpdateTicket_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_UpdateTicket $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UpdateTicket execution.
     *
     * @return Zendesk_Tickets_UpdateTicket_Results New results object.
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
     * Wraps results in appopriate results class for this UpdateTicket execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_UpdateTicket_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_UpdateTicket_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UpdateTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_UpdateTicket_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UpdateTicket choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_UpdateTicket_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UpdateTicket execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Applies a given macro to all applicable tickets.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ApplyMacroToAllTickets extends Temboo_Choreography
{
    /**
     * Applies a given macro to all applicable tickets.
     *
     * @param Temboo_Session $session The session that owns this ApplyMacroToAllTickets choreography.
     * @return Zendesk_Macros_ApplyMacroToAllTickets New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Macros/ApplyMacroToAllTickets/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ApplyMacroToAllTickets choreography.
     *
     * @param Zendesk_Macros_ApplyMacroToAllTickets_Inputs|array $inputs (optional) Inputs as Zendesk_Macros_ApplyMacroToAllTickets_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Macros_ApplyMacroToAllTickets_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ApplyMacroToAllTickets choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Macros_ApplyMacroToAllTickets_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ApplyMacroToAllTickets choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ApplyMacroToAllTickets_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ApplyMacroToAllTickets choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ApplyMacroToAllTickets input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ApplyMacroToAllTickets choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the MacroID input for this ApplyMacroToAllTickets choreography.
     *
     * @param int $value (required, integer) The ID of the macro to apply.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Inputs For method chaining.
     */
    public function setMacroID($value)
    {
        return $this->set('MacroID', $value);
    }

    /**
     * Set the value for the Password input for this ApplyMacroToAllTickets choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ApplyMacroToAllTickets choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ApplyMacroToAllTickets choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ApplyMacroToAllTickets_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ApplyMacroToAllTickets choreography.
     *
     * @param Temboo_Session $session The session that owns this ApplyMacroToAllTickets execution.
     * @param Zendesk_Macros_ApplyMacroToAllTickets $choreo The choregraphy object for this execution.
     * @param Zendesk_Macros_ApplyMacroToAllTickets_Inputs|array $inputs (optional) Inputs as Zendesk_Macros_ApplyMacroToAllTickets_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Macros_ApplyMacroToAllTickets $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ApplyMacroToAllTickets execution.
     *
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Results New results object.
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
     * Wraps results in appopriate results class for this ApplyMacroToAllTickets execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Macros_ApplyMacroToAllTickets_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ApplyMacroToAllTickets choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ApplyMacroToAllTickets_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ApplyMacroToAllTickets choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Macros_ApplyMacroToAllTickets_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ApplyMacroToAllTickets execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of all active macros available to the current user.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ListMacros extends Temboo_Choreography
{
    /**
     * Retrieves a list of all active macros available to the current user.
     *
     * @param Temboo_Session $session The session that owns this ListMacros choreography.
     * @return Zendesk_Macros_ListMacros New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Macros/ListMacros/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListMacros choreography.
     *
     * @param Zendesk_Macros_ListMacros_Inputs|array $inputs (optional) Inputs as Zendesk_Macros_ListMacros_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Macros_ListMacros_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Macros_ListMacros_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListMacros choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Macros_ListMacros_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Macros_ListMacros_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListMacros choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ListMacros_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListMacros choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Macros_ListMacros_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListMacros input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Macros_ListMacros_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Macros_ListMacros_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListMacros choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Macros_ListMacros_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this ListMacros choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Macros_ListMacros_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListMacros choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Macros_ListMacros_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListMacros choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ListMacros_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListMacros choreography.
     *
     * @param Temboo_Session $session The session that owns this ListMacros execution.
     * @param Zendesk_Macros_ListMacros $choreo The choregraphy object for this execution.
     * @param Zendesk_Macros_ListMacros_Inputs|array $inputs (optional) Inputs as Zendesk_Macros_ListMacros_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Macros_ListMacros_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Macros_ListMacros $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListMacros execution.
     *
     * @return Zendesk_Macros_ListMacros_Results New results object.
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
     * Wraps results in appopriate results class for this ListMacros execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Macros_ListMacros_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Macros_ListMacros_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListMacros choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ListMacros_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListMacros choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Macros_ListMacros_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListMacros execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of all existing users.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListAllUsers extends Temboo_Choreography
{
    /**
     * Retrieves a list of all existing users.
     *
     * @param Temboo_Session $session The session that owns this ListAllUsers choreography.
     * @return Zendesk_Users_ListAllUsers New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/ListAllUsers/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListAllUsers choreography.
     *
     * @param Zendesk_Users_ListAllUsers_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ListAllUsers_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ListAllUsers_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_ListAllUsers_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListAllUsers choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ListAllUsers_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_ListAllUsers_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListAllUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListAllUsers_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListAllUsers choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ListAllUsers_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListAllUsers input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_ListAllUsers_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_ListAllUsers_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListAllUsers choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Users_ListAllUsers_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Number input for this ListAllUsers choreography.
     *
     * @param int $value (optional, integer) The number of results to return per page. Maximum is 100 and default is 100.
     * @return Zendesk_Users_ListAllUsers_Inputs For method chaining.
     */
    public function setNumber($value)
    {
        return $this->set('Number', $value);
    }

    /**
     * Set the value for the Page input for this ListAllUsers choreography.
     *
     * @param int $value (optional, integer) The page number of the results to be returned. Used together with the Number parameter to paginate a large set of results.
     * @return Zendesk_Users_ListAllUsers_Inputs For method chaining.
     */
    public function setPage($value)
    {
        return $this->set('Page', $value);
    }

    /**
     * Set the value for the Password input for this ListAllUsers choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Users_ListAllUsers_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListAllUsers choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Users_ListAllUsers_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListAllUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListAllUsers_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListAllUsers choreography.
     *
     * @param Temboo_Session $session The session that owns this ListAllUsers execution.
     * @param Zendesk_Users_ListAllUsers $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_ListAllUsers_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ListAllUsers_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ListAllUsers_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_ListAllUsers $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListAllUsers execution.
     *
     * @return Zendesk_Users_ListAllUsers_Results New results object.
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
     * Wraps results in appopriate results class for this ListAllUsers execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_ListAllUsers_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_ListAllUsers_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListAllUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListAllUsers_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListAllUsers choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_ListAllUsers_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListAllUsers execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Updates a user's profile image.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_UpdateUserImage extends Temboo_Choreography
{
    /**
     * Updates a user's profile image.
     *
     * @param Temboo_Session $session The session that owns this UpdateUserImage choreography.
     * @return Zendesk_Users_UpdateUserImage New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/UpdateUserImage/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UpdateUserImage choreography.
     *
     * @param Zendesk_Users_UpdateUserImage_Inputs|array $inputs (optional) Inputs as Zendesk_Users_UpdateUserImage_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_UpdateUserImage_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_UpdateUserImage_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UpdateUserImage choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_UpdateUserImage_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_UpdateUserImage_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UpdateUserImage choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_UpdateUserImage_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UpdateUserImage choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_UpdateUserImage_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UpdateUserImage input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_UpdateUserImage_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_UpdateUserImage_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Response input for this UpdateUserImage choreography.
     *
     * @param string $value (required, json) The response from Zendesk.
     * @return Zendesk_Users_UpdateUserImage_Inputs For method chaining.
     */
    public function setResponse($value)
    {
        return $this->set('Response', $value);
    }

    /**
     * Set the value for the Email input for this UpdateUserImage choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Users_UpdateUserImage_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ImageURL input for this UpdateUserImage choreography.
     *
     * @param string $value (required, string) Set the URL of the image.
     * @return Zendesk_Users_UpdateUserImage_Inputs For method chaining.
     */
    public function setImageURL($value)
    {
        return $this->set('ImageURL', $value);
    }

    /**
     * Set the value for the Password input for this UpdateUserImage choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Users_UpdateUserImage_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this UpdateUserImage choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. temboocare.zendesk.com).
     * @return Zendesk_Users_UpdateUserImage_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the UserID input for this UpdateUserImage choreography.
     *
     * @param int $value (required, integer) The id of the user being updated.
     * @return Zendesk_Users_UpdateUserImage_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }
}


/**
 * Execution object for the UpdateUserImage choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_UpdateUserImage_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UpdateUserImage choreography.
     *
     * @param Temboo_Session $session The session that owns this UpdateUserImage execution.
     * @param Zendesk_Users_UpdateUserImage $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_UpdateUserImage_Inputs|array $inputs (optional) Inputs as Zendesk_Users_UpdateUserImage_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_UpdateUserImage_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_UpdateUserImage $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UpdateUserImage execution.
     *
     * @return Zendesk_Users_UpdateUserImage_Results New results object.
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
     * Wraps results in appopriate results class for this UpdateUserImage execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_UpdateUserImage_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_UpdateUserImage_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UpdateUserImage choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_UpdateUserImage_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UpdateUserImage choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_UpdateUserImage_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UpdateUserImage execution.
     *
     * @return string (required, json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves all the incidents associated with a given ticket.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketIncidents extends Temboo_Choreography
{
    /**
     * Retrieves all the incidents associated with a given ticket.
     *
     * @param Temboo_Session $session The session that owns this ListTicketIncidents choreography.
     * @return Zendesk_Tickets_ListTicketIncidents New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/ListTicketIncidents/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListTicketIncidents choreography.
     *
     * @param Zendesk_Tickets_ListTicketIncidents_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListTicketIncidents_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListTicketIncidents_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_ListTicketIncidents_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListTicketIncidents choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListTicketIncidents_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_ListTicketIncidents_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListTicketIncidents choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketIncidents_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListTicketIncidents choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListTicketIncidents_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListTicketIncidents input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_ListTicketIncidents_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_ListTicketIncidents_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListTicketIncidents choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListTicketIncidents_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ID input for this ListTicketIncidents choreography.
     *
     * @param int $value (required, integer) The ID number of a ticket.
     * @return Zendesk_Tickets_ListTicketIncidents_Inputs For method chaining.
     */
    public function setID($value)
    {
        return $this->set('ID', $value);
    }

    /**
     * Set the value for the Password input for this ListTicketIncidents choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListTicketIncidents_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListTicketIncidents choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tickets_ListTicketIncidents_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListTicketIncidents choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketIncidents_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListTicketIncidents choreography.
     *
     * @param Temboo_Session $session The session that owns this ListTicketIncidents execution.
     * @param Zendesk_Tickets_ListTicketIncidents $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_ListTicketIncidents_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListTicketIncidents_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListTicketIncidents_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_ListTicketIncidents $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListTicketIncidents execution.
     *
     * @return Zendesk_Tickets_ListTicketIncidents_Results New results object.
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
     * Wraps results in appopriate results class for this ListTicketIncidents execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_ListTicketIncidents_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_ListTicketIncidents_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListTicketIncidents choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketIncidents_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListTicketIncidents choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_ListTicketIncidents_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListTicketIncidents execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of all tickets involving a specified organization.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketsByOrganization extends Temboo_Choreography
{
    /**
     * Retrieves a list of all tickets involving a specified organization.
     *
     * @param Temboo_Session $session The session that owns this ListTicketsByOrganization choreography.
     * @return Zendesk_Tickets_ListTicketsByOrganization New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/ListTicketsByOrganization/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListTicketsByOrganization choreography.
     *
     * @param Zendesk_Tickets_ListTicketsByOrganization_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListTicketsByOrganization_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_ListTicketsByOrganization_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListTicketsByOrganization choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_ListTicketsByOrganization_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListTicketsByOrganization choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketsByOrganization_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListTicketsByOrganization choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListTicketsByOrganization input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListTicketsByOrganization choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the OrganizationID input for this ListTicketsByOrganization choreography.
     *
     * @param int $value (required, integer) The ID number of the organization.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Inputs For method chaining.
     */
    public function setOrganizationID($value)
    {
        return $this->set('OrganizationID', $value);
    }

    /**
     * Set the value for the Password input for this ListTicketsByOrganization choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListTicketsByOrganization choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tickets_ListTicketsByOrganization_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListTicketsByOrganization choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketsByOrganization_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListTicketsByOrganization choreography.
     *
     * @param Temboo_Session $session The session that owns this ListTicketsByOrganization execution.
     * @param Zendesk_Tickets_ListTicketsByOrganization $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_ListTicketsByOrganization_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListTicketsByOrganization_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_ListTicketsByOrganization $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListTicketsByOrganization execution.
     *
     * @return Zendesk_Tickets_ListTicketsByOrganization_Results New results object.
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
     * Wraps results in appopriate results class for this ListTicketsByOrganization execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_ListTicketsByOrganization_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListTicketsByOrganization choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketsByOrganization_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListTicketsByOrganization choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_ListTicketsByOrganization_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListTicketsByOrganization execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Deletes a given user.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_DeleteUser extends Temboo_Choreography
{
    /**
     * Deletes a given user.
     *
     * @param Temboo_Session $session The session that owns this DeleteUser choreography.
     * @return Zendesk_Users_DeleteUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/DeleteUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DeleteUser choreography.
     *
     * @param Zendesk_Users_DeleteUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_DeleteUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_DeleteUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_DeleteUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DeleteUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_DeleteUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_DeleteUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DeleteUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_DeleteUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DeleteUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_DeleteUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DeleteUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_DeleteUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_DeleteUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this DeleteUser choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Users_DeleteUser_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ID input for this DeleteUser choreography.
     *
     * @param int $value (required, integer) The ID of the user to delete.
     * @return Zendesk_Users_DeleteUser_Inputs For method chaining.
     */
    public function setID($value)
    {
        return $this->set('ID', $value);
    }

    /**
     * Set the value for the Password input for this DeleteUser choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Users_DeleteUser_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this DeleteUser choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Users_DeleteUser_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the DeleteUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_DeleteUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DeleteUser choreography.
     *
     * @param Temboo_Session $session The session that owns this DeleteUser execution.
     * @param Zendesk_Users_DeleteUser $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_DeleteUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_DeleteUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_DeleteUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_DeleteUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DeleteUser execution.
     *
     * @return Zendesk_Users_DeleteUser_Results New results object.
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
     * Wraps results in appopriate results class for this DeleteUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_DeleteUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_DeleteUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DeleteUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_DeleteUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DeleteUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_DeleteUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this DeleteUser execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Suspends an existing user.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_SuspendUser extends Temboo_Choreography
{
    /**
     * Suspends an existing user.
     *
     * @param Temboo_Session $session The session that owns this SuspendUser choreography.
     * @return Zendesk_Users_SuspendUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/SuspendUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SuspendUser choreography.
     *
     * @param Zendesk_Users_SuspendUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_SuspendUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_SuspendUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_SuspendUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SuspendUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_SuspendUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_SuspendUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SuspendUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_SuspendUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SuspendUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_SuspendUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SuspendUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_SuspendUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_SuspendUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this SuspendUser choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Users_SuspendUser_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this SuspendUser choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Users_SuspendUser_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this SuspendUser choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. temboocare.zendesk.com).
     * @return Zendesk_Users_SuspendUser_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the UserID input for this SuspendUser choreography.
     *
     * @param int $value (required, integer) The id of the user being updated.
     * @return Zendesk_Users_SuspendUser_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }
}


/**
 * Execution object for the SuspendUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_SuspendUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SuspendUser choreography.
     *
     * @param Temboo_Session $session The session that owns this SuspendUser execution.
     * @param Zendesk_Users_SuspendUser $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_SuspendUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_SuspendUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_SuspendUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_SuspendUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SuspendUser execution.
     *
     * @return Zendesk_Users_SuspendUser_Results New results object.
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
     * Wraps results in appopriate results class for this SuspendUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_SuspendUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_SuspendUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SuspendUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_SuspendUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SuspendUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_SuspendUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this SuspendUser execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of all recent tickets.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListRecent extends Temboo_Choreography
{
    /**
     * Retrieves a list of all recent tickets.
     *
     * @param Temboo_Session $session The session that owns this ListRecent choreography.
     * @return Zendesk_Tickets_ListRecent New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/ListRecent/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListRecent choreography.
     *
     * @param Zendesk_Tickets_ListRecent_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListRecent_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListRecent_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_ListRecent_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListRecent choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListRecent_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_ListRecent_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListRecent choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListRecent_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListRecent choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListRecent_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListRecent input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_ListRecent_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_ListRecent_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListRecent choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListRecent_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this ListRecent choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListRecent_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListRecent choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tickets_ListRecent_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListRecent choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListRecent_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListRecent choreography.
     *
     * @param Temboo_Session $session The session that owns this ListRecent execution.
     * @param Zendesk_Tickets_ListRecent $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_ListRecent_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListRecent_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListRecent_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_ListRecent $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListRecent execution.
     *
     * @return Zendesk_Tickets_ListRecent_Results New results object.
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
     * Wraps results in appopriate results class for this ListRecent execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_ListRecent_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_ListRecent_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListRecent choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListRecent_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListRecent choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_ListRecent_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListRecent execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns a list of users who match the search parameters.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_SearchUsers extends Temboo_Choreography
{
    /**
     * Returns a list of users who match the search parameters.
     *
     * @param Temboo_Session $session The session that owns this SearchUsers choreography.
     * @return Zendesk_Users_SearchUsers New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/SearchUsers/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SearchUsers choreography.
     *
     * @param Zendesk_Users_SearchUsers_Inputs|array $inputs (optional) Inputs as Zendesk_Users_SearchUsers_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_SearchUsers_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_SearchUsers_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SearchUsers choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_SearchUsers_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_SearchUsers_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SearchUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_SearchUsers_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SearchUsers choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_SearchUsers_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SearchUsers input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_SearchUsers_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_SearchUsers_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this SearchUsers choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Users_SearchUsers_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this SearchUsers choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Users_SearchUsers_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Query input for this SearchUsers choreography.
     *
     * @param string $value (required, string) Perform a search across usernames or email addresses.
     * @return Zendesk_Users_SearchUsers_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the Server input for this SearchUsers choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Users_SearchUsers_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the SearchUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_SearchUsers_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SearchUsers choreography.
     *
     * @param Temboo_Session $session The session that owns this SearchUsers execution.
     * @param Zendesk_Users_SearchUsers $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_SearchUsers_Inputs|array $inputs (optional) Inputs as Zendesk_Users_SearchUsers_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_SearchUsers_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_SearchUsers $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SearchUsers execution.
     *
     * @return Zendesk_Users_SearchUsers_Results New results object.
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
     * Wraps results in appopriate results class for this SearchUsers execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_SearchUsers_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_SearchUsers_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SearchUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_SearchUsers_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SearchUsers choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_SearchUsers_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this SearchUsers execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Creates many new users at one time.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_CreateManyUsers extends Temboo_Choreography
{
    /**
     * Creates many new users at one time.
     *
     * @param Temboo_Session $session The session that owns this CreateManyUsers choreography.
     * @return Zendesk_Users_CreateManyUsers New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/CreateManyUsers/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateManyUsers choreography.
     *
     * @param Zendesk_Users_CreateManyUsers_Inputs|array $inputs (optional) Inputs as Zendesk_Users_CreateManyUsers_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_CreateManyUsers_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_CreateManyUsers_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateManyUsers choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_CreateManyUsers_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_CreateManyUsers_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateManyUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_CreateManyUsers_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateManyUsers choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_CreateManyUsers_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateManyUsers input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_CreateManyUsers_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_CreateManyUsers_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this CreateManyUsers choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Users_CreateManyUsers_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this CreateManyUsers choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Users_CreateManyUsers_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this CreateManyUsers choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. support.temboo.com or temboo.zendesk.com).
     * @return Zendesk_Users_CreateManyUsers_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the Users input for this CreateManyUsers choreography.
     *
     * @param string $value (required, json) The JSON formatted list of new users. See documentation for required format.
     * @return Zendesk_Users_CreateManyUsers_Inputs For method chaining.
     */
    public function setUsers($value)
    {
        return $this->set('Users', $value);
    }
}


/**
 * Execution object for the CreateManyUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_CreateManyUsers_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateManyUsers choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateManyUsers execution.
     * @param Zendesk_Users_CreateManyUsers $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_CreateManyUsers_Inputs|array $inputs (optional) Inputs as Zendesk_Users_CreateManyUsers_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_CreateManyUsers_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_CreateManyUsers $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateManyUsers execution.
     *
     * @return Zendesk_Users_CreateManyUsers_Results New results object.
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
     * Wraps results in appopriate results class for this CreateManyUsers execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_CreateManyUsers_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_CreateManyUsers_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateManyUsers choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_CreateManyUsers_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateManyUsers choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_CreateManyUsers_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateManyUsers execution.
     *
     * @return string (json) 
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Creates a new ticket.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_CreateTicket extends Temboo_Choreography
{
    /**
     * Creates a new ticket.
     *
     * @param Temboo_Session $session The session that owns this CreateTicket choreography.
     * @return Zendesk_Tickets_CreateTicket New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/CreateTicket/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateTicket choreography.
     *
     * @param Zendesk_Tickets_CreateTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_CreateTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_CreateTicket_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_CreateTicket_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_CreateTicket_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_CreateTicket_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_CreateTicket_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_CreateTicket_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateTicket input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Comment input for this CreateTicket choreography.
     *
     * @param string $value (conditional, string) The comment for the ticket that is being created.
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function setComment($value)
    {
        return $this->set('Comment', $value);
    }

    /**
     * Set the value for the Email input for this CreateTicket choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this CreateTicket choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this CreateTicket choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. temboocare.zendesk.com).
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the Subject input for this CreateTicket choreography.
     *
     * @param string $value (conditional, string) The subject for the ticket that is being created.
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function setSubject($value)
    {
        return $this->set('Subject', $value);
    }

    /**
     * Set the value for the TicketData input for this CreateTicket choreography.
     *
     * @param string $value (optional, json) A JSON string containing the ticket information. This can be used as an alternative to the serialized ticket inputs in case you need to create tickets using custom fields.
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function setTicketData($value)
    {
        return $this->set('TicketData', $value);
    }

    /**
     * Set the value for the Token input for this CreateTicket choreography.
     *
     * @param string $value (optional, string) The token associated with an upload to attach to this ticket. Note that tokens can be retrieved by running the UploadFile Choreo.
     * @return Zendesk_Tickets_CreateTicket_Inputs For method chaining.
     */
    public function setToken($value)
    {
        return $this->set('Token', $value);
    }
}


/**
 * Execution object for the CreateTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_CreateTicket_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateTicket choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateTicket execution.
     * @param Zendesk_Tickets_CreateTicket $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_CreateTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_CreateTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_CreateTicket_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_CreateTicket $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateTicket execution.
     *
     * @return Zendesk_Tickets_CreateTicket_Results New results object.
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
     * Wraps results in appopriate results class for this CreateTicket execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_CreateTicket_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_CreateTicket_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_CreateTicket_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateTicket choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_CreateTicket_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateTicket execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * 
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketsByUser extends Temboo_Choreography
{
    /**
     * 
     *
     * @param Temboo_Session $session The session that owns this ListTicketsByUser choreography.
     * @return Zendesk_Tickets_ListTicketsByUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/ListTicketsByUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListTicketsByUser choreography.
     *
     * @param Zendesk_Tickets_ListTicketsByUser_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListTicketsByUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListTicketsByUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_ListTicketsByUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListTicketsByUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_ListTicketsByUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListTicketsByUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketsByUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListTicketsByUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListTicketsByUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListTicketsByUser choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this ListTicketsByUser choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListTicketsByUser choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the UserID input for this ListTicketsByUser choreography.
     *
     * @param int $value (required, integer) The ID number of the user.
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }

    /**
     * Set the value for the UserType input for this ListTicketsByUser choreography.
     *
     * @param string $value (optional, string) Specify "requested" to find tickets that the given user requested and "ccd" to find tickets on which a given user was CC'd. Defaults to searching for tickets that the user requested.
     * @return Zendesk_Tickets_ListTicketsByUser_Inputs For method chaining.
     */
    public function setUserType($value)
    {
        return $this->set('UserType', $value);
    }
}


/**
 * Execution object for the ListTicketsByUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketsByUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListTicketsByUser choreography.
     *
     * @param Temboo_Session $session The session that owns this ListTicketsByUser execution.
     * @param Zendesk_Tickets_ListTicketsByUser $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_ListTicketsByUser_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListTicketsByUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListTicketsByUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_ListTicketsByUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListTicketsByUser execution.
     *
     * @return Zendesk_Tickets_ListTicketsByUser_Results New results object.
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
     * Wraps results in appopriate results class for this ListTicketsByUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_ListTicketsByUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_ListTicketsByUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListTicketsByUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketsByUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListTicketsByUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_ListTicketsByUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListTicketsByUser execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns search results across all ticket fields.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Search_SearchAll extends Temboo_Choreography
{
    /**
     * Returns search results across all ticket fields.
     *
     * @param Temboo_Session $session The session that owns this SearchAll choreography.
     * @return Zendesk_Search_SearchAll New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Search/SearchAll/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SearchAll choreography.
     *
     * @param Zendesk_Search_SearchAll_Inputs|array $inputs (optional) Inputs as Zendesk_Search_SearchAll_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Search_SearchAll_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Search_SearchAll_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SearchAll choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Search_SearchAll_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Search_SearchAll_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SearchAll choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Search_SearchAll_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SearchAll choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Search_SearchAll_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SearchAll input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Search_SearchAll_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Search_SearchAll_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this SearchAll choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Search_SearchAll_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this SearchAll choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Search_SearchAll_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Query input for this SearchAll choreography.
     *
     * @param string $value (required, string) Perform a search across usernames or email addresses.
     * @return Zendesk_Search_SearchAll_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the Server input for this SearchAll choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Search_SearchAll_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the SortBy input for this SearchAll choreography.
     *
     * @param string $value (optional, string) Acceptable values: updated_at, created_at, priority, status, ticket_type.
     * @return Zendesk_Search_SearchAll_Inputs For method chaining.
     */
    public function setSortBy($value)
    {
        return $this->set('SortBy', $value);
    }

    /**
     * Set the value for the SortOrder input for this SearchAll choreography.
     *
     * @param string $value (optional, string) Indicate either: relevance, asc (for ascending), desc (for descending). Defaults to relevance.
     * @return Zendesk_Search_SearchAll_Inputs For method chaining.
     */
    public function setSortOrder($value)
    {
        return $this->set('SortOrder', $value);
    }
}


/**
 * Execution object for the SearchAll choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Search_SearchAll_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SearchAll choreography.
     *
     * @param Temboo_Session $session The session that owns this SearchAll execution.
     * @param Zendesk_Search_SearchAll $choreo The choregraphy object for this execution.
     * @param Zendesk_Search_SearchAll_Inputs|array $inputs (optional) Inputs as Zendesk_Search_SearchAll_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Search_SearchAll_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Search_SearchAll $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SearchAll execution.
     *
     * @return Zendesk_Search_SearchAll_Results New results object.
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
     * Wraps results in appopriate results class for this SearchAll execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Search_SearchAll_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Search_SearchAll_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SearchAll choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Search_SearchAll_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SearchAll choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Search_SearchAll_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this SearchAll execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Creates a new ticket as well as a new requester account.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_CreateTicketNewRequester extends Temboo_Choreography
{
    /**
     * Creates a new ticket as well as a new requester account.
     *
     * @param Temboo_Session $session The session that owns this CreateTicketNewRequester choreography.
     * @return Zendesk_Tickets_CreateTicketNewRequester New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/CreateTicketNewRequester/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateTicketNewRequester choreography.
     *
     * @param Zendesk_Tickets_CreateTicketNewRequester_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_CreateTicketNewRequester_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_CreateTicketNewRequester_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateTicketNewRequester choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_CreateTicketNewRequester_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateTicketNewRequester choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_CreateTicketNewRequester_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateTicketNewRequester choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateTicketNewRequester input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Comment input for this CreateTicketNewRequester choreography.
     *
     * @param string $value (required, string) The comment for the ticket that is being created.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setComment($value)
    {
        return $this->set('Comment', $value);
    }

    /**
     * Set the value for the Email input for this CreateTicketNewRequester choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the LocaleID input for this CreateTicketNewRequester choreography.
     *
     * @param int $value (required, integer) LocaleID for the new requester. Indicate 1 for English, 8 for Deutsch, etc.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setLocaleID($value)
    {
        return $this->set('LocaleID', $value);
    }

    /**
     * Set the value for the Name input for this CreateTicketNewRequester choreography.
     *
     * @param string $value (required, string) Name of new requester.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setName($value)
    {
        return $this->set('Name', $value);
    }

    /**
     * Set the value for the Password input for this CreateTicketNewRequester choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the RequesterEmail input for this CreateTicketNewRequester choreography.
     *
     * @param string $value (required, string) Email of new requester.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setRequesterEmail($value)
    {
        return $this->set('RequesterEmail', $value);
    }

    /**
     * Set the value for the Server input for this CreateTicketNewRequester choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. temboocare.zendesk.com).
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the Subject input for this CreateTicketNewRequester choreography.
     *
     * @param string $value (required, string) The subject for the ticket that is being created.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setSubject($value)
    {
        return $this->set('Subject', $value);
    }

    /**
     * Set the value for the Token input for this CreateTicketNewRequester choreography.
     *
     * @param string $value (optional, string) The token associated with an upload to attach to this ticket. Note that tokens can be retrieved by running the UploadFile Choreo.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Inputs For method chaining.
     */
    public function setToken($value)
    {
        return $this->set('Token', $value);
    }
}


/**
 * Execution object for the CreateTicketNewRequester choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_CreateTicketNewRequester_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateTicketNewRequester choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateTicketNewRequester execution.
     * @param Zendesk_Tickets_CreateTicketNewRequester $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_CreateTicketNewRequester_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_CreateTicketNewRequester_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_CreateTicketNewRequester $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateTicketNewRequester execution.
     *
     * @return Zendesk_Tickets_CreateTicketNewRequester_Results New results object.
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
     * Wraps results in appopriate results class for this CreateTicketNewRequester execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_CreateTicketNewRequester_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateTicketNewRequester choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_CreateTicketNewRequester_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateTicketNewRequester choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_CreateTicketNewRequester_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateTicketNewRequester execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns information about a ticket using its ID.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_GetTicket extends Temboo_Choreography
{
    /**
     * Returns information about a ticket using its ID.
     *
     * @param Temboo_Session $session The session that owns this GetTicket choreography.
     * @return Zendesk_Tickets_GetTicket New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/GetTicket/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this GetTicket choreography.
     *
     * @param Zendesk_Tickets_GetTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_GetTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_GetTicket_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_GetTicket_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this GetTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_GetTicket_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_GetTicket_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the GetTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_GetTicket_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the GetTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_GetTicket_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this GetTicket input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_GetTicket_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_GetTicket_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this GetTicket choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_GetTicket_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ID input for this GetTicket choreography.
     *
     * @param int $value (required, integer) The ID number of a ticket.
     * @return Zendesk_Tickets_GetTicket_Inputs For method chaining.
     */
    public function setID($value)
    {
        return $this->set('ID', $value);
    }

    /**
     * Set the value for the Password input for this GetTicket choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_GetTicket_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this GetTicket choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tickets_GetTicket_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the GetTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_GetTicket_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the GetTicket choreography.
     *
     * @param Temboo_Session $session The session that owns this GetTicket execution.
     * @param Zendesk_Tickets_GetTicket $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_GetTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_GetTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_GetTicket_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_GetTicket $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this GetTicket execution.
     *
     * @return Zendesk_Tickets_GetTicket_Results New results object.
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
     * Wraps results in appopriate results class for this GetTicket execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_GetTicket_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_GetTicket_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the GetTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_GetTicket_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the GetTicket choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_GetTicket_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this GetTicket execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns a list of activities pertaining to the agent executing the request.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_ActivityStream_ListActivity extends Temboo_Choreography
{
    /**
     * Returns a list of activities pertaining to the agent executing the request.
     *
     * @param Temboo_Session $session The session that owns this ListActivity choreography.
     * @return Zendesk_ActivityStream_ListActivity New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/ActivityStream/ListActivity/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListActivity choreography.
     *
     * @param Zendesk_ActivityStream_ListActivity_Inputs|array $inputs (optional) Inputs as Zendesk_ActivityStream_ListActivity_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_ActivityStream_ListActivity_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_ActivityStream_ListActivity_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListActivity choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_ActivityStream_ListActivity_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_ActivityStream_ListActivity_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListActivity choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_ActivityStream_ListActivity_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListActivity choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_ActivityStream_ListActivity_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListActivity input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_ActivityStream_ListActivity_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_ActivityStream_ListActivity_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListActivity choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_ActivityStream_ListActivity_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this ListActivity choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_ActivityStream_ListActivity_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListActivity choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_ActivityStream_ListActivity_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListActivity choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_ActivityStream_ListActivity_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListActivity choreography.
     *
     * @param Temboo_Session $session The session that owns this ListActivity execution.
     * @param Zendesk_ActivityStream_ListActivity $choreo The choregraphy object for this execution.
     * @param Zendesk_ActivityStream_ListActivity_Inputs|array $inputs (optional) Inputs as Zendesk_ActivityStream_ListActivity_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_ActivityStream_ListActivity_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_ActivityStream_ListActivity $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListActivity execution.
     *
     * @return Zendesk_ActivityStream_ListActivity_Results New results object.
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
     * Wraps results in appopriate results class for this ListActivity execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_ActivityStream_ListActivity_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_ActivityStream_ListActivity_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListActivity choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_ActivityStream_ListActivity_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListActivity choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_ActivityStream_ListActivity_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListActivity execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Applies a macro to a given ticket.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ApplyMacroToTicket extends Temboo_Choreography
{
    /**
     * Applies a macro to a given ticket.
     *
     * @param Temboo_Session $session The session that owns this ApplyMacroToTicket choreography.
     * @return Zendesk_Macros_ApplyMacroToTicket New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Macros/ApplyMacroToTicket/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ApplyMacroToTicket choreography.
     *
     * @param Zendesk_Macros_ApplyMacroToTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Macros_ApplyMacroToTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Macros_ApplyMacroToTicket_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Macros_ApplyMacroToTicket_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ApplyMacroToTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Macros_ApplyMacroToTicket_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ApplyMacroToTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ApplyMacroToTicket_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ApplyMacroToTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ApplyMacroToTicket input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ApplyMacroToTicket choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the MacroID input for this ApplyMacroToTicket choreography.
     *
     * @param int $value (required, integer) The ID of the macro to apply.
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs For method chaining.
     */
    public function setMacroID($value)
    {
        return $this->set('MacroID', $value);
    }

    /**
     * Set the value for the Password input for this ApplyMacroToTicket choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ApplyMacroToTicket choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the TicketID input for this ApplyMacroToTicket choreography.
     *
     * @param string $value (required, string) The ID of the ticket.
     * @return Zendesk_Macros_ApplyMacroToTicket_Inputs For method chaining.
     */
    public function setTicketID($value)
    {
        return $this->set('TicketID', $value);
    }
}


/**
 * Execution object for the ApplyMacroToTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ApplyMacroToTicket_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ApplyMacroToTicket choreography.
     *
     * @param Temboo_Session $session The session that owns this ApplyMacroToTicket execution.
     * @param Zendesk_Macros_ApplyMacroToTicket $choreo The choregraphy object for this execution.
     * @param Zendesk_Macros_ApplyMacroToTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Macros_ApplyMacroToTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Macros_ApplyMacroToTicket_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Macros_ApplyMacroToTicket $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ApplyMacroToTicket execution.
     *
     * @return Zendesk_Macros_ApplyMacroToTicket_Results New results object.
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
     * Wraps results in appopriate results class for this ApplyMacroToTicket execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Macros_ApplyMacroToTicket_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Macros_ApplyMacroToTicket_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ApplyMacroToTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Macros_ApplyMacroToTicket_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ApplyMacroToTicket choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Macros_ApplyMacroToTicket_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ApplyMacroToTicket execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves the most popular recent tags in decreasing popularity.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tags_ListTags extends Temboo_Choreography
{
    /**
     * Retrieves the most popular recent tags in decreasing popularity.
     *
     * @param Temboo_Session $session The session that owns this ListTags choreography.
     * @return Zendesk_Tags_ListTags New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tags/ListTags/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListTags choreography.
     *
     * @param Zendesk_Tags_ListTags_Inputs|array $inputs (optional) Inputs as Zendesk_Tags_ListTags_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tags_ListTags_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tags_ListTags_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListTags choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tags_ListTags_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tags_ListTags_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListTags choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tags_ListTags_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListTags choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tags_ListTags_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListTags input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tags_ListTags_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tags_ListTags_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListTags choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tags_ListTags_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this ListTags choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tags_ListTags_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListTags choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tags_ListTags_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListTags choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tags_ListTags_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListTags choreography.
     *
     * @param Temboo_Session $session The session that owns this ListTags execution.
     * @param Zendesk_Tags_ListTags $choreo The choregraphy object for this execution.
     * @param Zendesk_Tags_ListTags_Inputs|array $inputs (optional) Inputs as Zendesk_Tags_ListTags_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tags_ListTags_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tags_ListTags $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListTags execution.
     *
     * @return Zendesk_Tags_ListTags_Results New results object.
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
     * Wraps results in appopriate results class for this ListTags execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tags_ListTags_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tags_ListTags_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListTags choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tags_ListTags_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListTags choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tags_ListTags_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListTags execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns only a list of activities pertaining to the agent executing the request.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_ActivityStream_ShowActivity extends Temboo_Choreography
{
    /**
     * Returns only a list of activities pertaining to the agent executing the request.
     *
     * @param Temboo_Session $session The session that owns this ShowActivity choreography.
     * @return Zendesk_ActivityStream_ShowActivity New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/ActivityStream/ShowActivity/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ShowActivity choreography.
     *
     * @param Zendesk_ActivityStream_ShowActivity_Inputs|array $inputs (optional) Inputs as Zendesk_ActivityStream_ShowActivity_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_ActivityStream_ShowActivity_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_ActivityStream_ShowActivity_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ShowActivity choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_ActivityStream_ShowActivity_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_ActivityStream_ShowActivity_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ShowActivity choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_ActivityStream_ShowActivity_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ShowActivity choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_ActivityStream_ShowActivity_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ShowActivity input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_ActivityStream_ShowActivity_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_ActivityStream_ShowActivity_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ShowActivity choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_ActivityStream_ShowActivity_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ID input for this ShowActivity choreography.
     *
     * @param string $value (required, string) ID of the agent executing the request.
     * @return Zendesk_ActivityStream_ShowActivity_Inputs For method chaining.
     */
    public function setID($value)
    {
        return $this->set('ID', $value);
    }

    /**
     * Set the value for the Password input for this ShowActivity choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_ActivityStream_ShowActivity_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ShowActivity choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_ActivityStream_ShowActivity_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ShowActivity choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_ActivityStream_ShowActivity_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ShowActivity choreography.
     *
     * @param Temboo_Session $session The session that owns this ShowActivity execution.
     * @param Zendesk_ActivityStream_ShowActivity $choreo The choregraphy object for this execution.
     * @param Zendesk_ActivityStream_ShowActivity_Inputs|array $inputs (optional) Inputs as Zendesk_ActivityStream_ShowActivity_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_ActivityStream_ShowActivity_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_ActivityStream_ShowActivity $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ShowActivity execution.
     *
     * @return Zendesk_ActivityStream_ShowActivity_Results New results object.
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
     * Wraps results in appopriate results class for this ShowActivity execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_ActivityStream_ShowActivity_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_ActivityStream_ShowActivity_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ShowActivity choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_ActivityStream_ShowActivity_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ShowActivity choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_ActivityStream_ShowActivity_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ShowActivity execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves all the collaborators associated with a given ticket.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketCollaborators extends Temboo_Choreography
{
    /**
     * Retrieves all the collaborators associated with a given ticket.
     *
     * @param Temboo_Session $session The session that owns this ListTicketCollaborators choreography.
     * @return Zendesk_Tickets_ListTicketCollaborators New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/ListTicketCollaborators/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListTicketCollaborators choreography.
     *
     * @param Zendesk_Tickets_ListTicketCollaborators_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListTicketCollaborators_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListTicketCollaborators_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_ListTicketCollaborators_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListTicketCollaborators choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListTicketCollaborators_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_ListTicketCollaborators_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListTicketCollaborators choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketCollaborators_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListTicketCollaborators choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListTicketCollaborators_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListTicketCollaborators input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_ListTicketCollaborators_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_ListTicketCollaborators_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListTicketCollaborators choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListTicketCollaborators_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ID input for this ListTicketCollaborators choreography.
     *
     * @param int $value (required, integer) The ID number of a ticket.
     * @return Zendesk_Tickets_ListTicketCollaborators_Inputs For method chaining.
     */
    public function setID($value)
    {
        return $this->set('ID', $value);
    }

    /**
     * Set the value for the Password input for this ListTicketCollaborators choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListTicketCollaborators_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListTicketCollaborators choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tickets_ListTicketCollaborators_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListTicketCollaborators choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketCollaborators_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListTicketCollaborators choreography.
     *
     * @param Temboo_Session $session The session that owns this ListTicketCollaborators execution.
     * @param Zendesk_Tickets_ListTicketCollaborators $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_ListTicketCollaborators_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListTicketCollaborators_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListTicketCollaborators_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_ListTicketCollaborators $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListTicketCollaborators execution.
     *
     * @return Zendesk_Tickets_ListTicketCollaborators_Results New results object.
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
     * Wraps results in appopriate results class for this ListTicketCollaborators execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_ListTicketCollaborators_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_ListTicketCollaborators_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListTicketCollaborators choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListTicketCollaborators_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListTicketCollaborators choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_ListTicketCollaborators_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListTicketCollaborators execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Allows anonymous users to search public forums.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Search_SearchAnonymous extends Temboo_Choreography
{
    /**
     * Allows anonymous users to search public forums.
     *
     * @param Temboo_Session $session The session that owns this SearchAnonymous choreography.
     * @return Zendesk_Search_SearchAnonymous New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Search/SearchAnonymous/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this SearchAnonymous choreography.
     *
     * @param Zendesk_Search_SearchAnonymous_Inputs|array $inputs (optional) Inputs as Zendesk_Search_SearchAnonymous_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Search_SearchAnonymous_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Search_SearchAnonymous_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this SearchAnonymous choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Search_SearchAnonymous_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Search_SearchAnonymous_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the SearchAnonymous choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Search_SearchAnonymous_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the SearchAnonymous choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Search_SearchAnonymous_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this SearchAnonymous input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Search_SearchAnonymous_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Search_SearchAnonymous_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this SearchAnonymous choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Search_SearchAnonymous_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this SearchAnonymous choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Search_SearchAnonymous_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Query input for this SearchAnonymous choreography.
     *
     * @param string $value (required, string) Perform a search across usernames or email addresses.
     * @return Zendesk_Search_SearchAnonymous_Inputs For method chaining.
     */
    public function setQuery($value)
    {
        return $this->set('Query', $value);
    }

    /**
     * Set the value for the Server input for this SearchAnonymous choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Search_SearchAnonymous_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the SortBy input for this SearchAnonymous choreography.
     *
     * @param string $value (optional, string) Acceptable values: updated_at, created_at, priority, status, ticket_type.
     * @return Zendesk_Search_SearchAnonymous_Inputs For method chaining.
     */
    public function setSortBy($value)
    {
        return $this->set('SortBy', $value);
    }

    /**
     * Set the value for the SortOrder input for this SearchAnonymous choreography.
     *
     * @param string $value (optional, string) Indicate either: relevance, asc (for ascending), desc (for descending). Defaults to relevance.
     * @return Zendesk_Search_SearchAnonymous_Inputs For method chaining.
     */
    public function setSortOrder($value)
    {
        return $this->set('SortOrder', $value);
    }
}


/**
 * Execution object for the SearchAnonymous choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Search_SearchAnonymous_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the SearchAnonymous choreography.
     *
     * @param Temboo_Session $session The session that owns this SearchAnonymous execution.
     * @param Zendesk_Search_SearchAnonymous $choreo The choregraphy object for this execution.
     * @param Zendesk_Search_SearchAnonymous_Inputs|array $inputs (optional) Inputs as Zendesk_Search_SearchAnonymous_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Search_SearchAnonymous_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Search_SearchAnonymous $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this SearchAnonymous execution.
     *
     * @return Zendesk_Search_SearchAnonymous_Results New results object.
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
     * Wraps results in appopriate results class for this SearchAnonymous execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Search_SearchAnonymous_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Search_SearchAnonymous_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the SearchAnonymous choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Search_SearchAnonymous_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the SearchAnonymous choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Search_SearchAnonymous_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this SearchAnonymous execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Deletes an existing ticket.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_DeleteTicket extends Temboo_Choreography
{
    /**
     * Deletes an existing ticket.
     *
     * @param Temboo_Session $session The session that owns this DeleteTicket choreography.
     * @return Zendesk_Tickets_DeleteTicket New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/DeleteTicket/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this DeleteTicket choreography.
     *
     * @param Zendesk_Tickets_DeleteTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_DeleteTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_DeleteTicket_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_DeleteTicket_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this DeleteTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_DeleteTicket_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_DeleteTicket_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the DeleteTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_DeleteTicket_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the DeleteTicket choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_DeleteTicket_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this DeleteTicket input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_DeleteTicket_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_DeleteTicket_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this DeleteTicket choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_DeleteTicket_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ID input for this DeleteTicket choreography.
     *
     * @param int $value (required, integer) The ID number of a ticket.
     * @return Zendesk_Tickets_DeleteTicket_Inputs For method chaining.
     */
    public function setID($value)
    {
        return $this->set('ID', $value);
    }

    /**
     * Set the value for the Password input for this DeleteTicket choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_DeleteTicket_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this DeleteTicket choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tickets_DeleteTicket_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the DeleteTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_DeleteTicket_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the DeleteTicket choreography.
     *
     * @param Temboo_Session $session The session that owns this DeleteTicket execution.
     * @param Zendesk_Tickets_DeleteTicket $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_DeleteTicket_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_DeleteTicket_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_DeleteTicket_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_DeleteTicket $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this DeleteTicket execution.
     *
     * @return Zendesk_Tickets_DeleteTicket_Results New results object.
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
     * Wraps results in appopriate results class for this DeleteTicket execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_DeleteTicket_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_DeleteTicket_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the DeleteTicket choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_DeleteTicket_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the DeleteTicket choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_DeleteTicket_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this DeleteTicket execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns information about given user.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ShowUser extends Temboo_Choreography
{
    /**
     * Returns information about given user.
     *
     * @param Temboo_Session $session The session that owns this ShowUser choreography.
     * @return Zendesk_Users_ShowUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/ShowUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ShowUser choreography.
     *
     * @param Zendesk_Users_ShowUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ShowUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ShowUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_ShowUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ShowUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ShowUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_ShowUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ShowUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ShowUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ShowUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ShowUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ShowUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_ShowUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_ShowUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ShowUser choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Users_ShowUser_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ID input for this ShowUser choreography.
     *
     * @param int $value (required, integer) The ID number of a user.
     * @return Zendesk_Users_ShowUser_Inputs For method chaining.
     */
    public function setID($value)
    {
        return $this->set('ID', $value);
    }

    /**
     * Set the value for the Password input for this ShowUser choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Users_ShowUser_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ShowUser choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Users_ShowUser_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ShowUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ShowUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ShowUser choreography.
     *
     * @param Temboo_Session $session The session that owns this ShowUser execution.
     * @param Zendesk_Users_ShowUser $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_ShowUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ShowUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ShowUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_ShowUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ShowUser execution.
     *
     * @return Zendesk_Users_ShowUser_Results New results object.
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
     * Wraps results in appopriate results class for this ShowUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_ShowUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_ShowUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ShowUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ShowUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ShowUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_ShowUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ShowUser execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Returns information about the currently authenticated user.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ShowCurrentUser extends Temboo_Choreography
{
    /**
     * Returns information about the currently authenticated user.
     *
     * @param Temboo_Session $session The session that owns this ShowCurrentUser choreography.
     * @return Zendesk_Users_ShowCurrentUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/ShowCurrentUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ShowCurrentUser choreography.
     *
     * @param Zendesk_Users_ShowCurrentUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ShowCurrentUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ShowCurrentUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_ShowCurrentUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ShowCurrentUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ShowCurrentUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_ShowCurrentUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ShowCurrentUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ShowCurrentUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ShowCurrentUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ShowCurrentUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ShowCurrentUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_ShowCurrentUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_ShowCurrentUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ShowCurrentUser choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Users_ShowCurrentUser_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this ShowCurrentUser choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Users_ShowCurrentUser_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ShowCurrentUser choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Users_ShowCurrentUser_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ShowCurrentUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ShowCurrentUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ShowCurrentUser choreography.
     *
     * @param Temboo_Session $session The session that owns this ShowCurrentUser execution.
     * @param Zendesk_Users_ShowCurrentUser $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_ShowCurrentUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ShowCurrentUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ShowCurrentUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_ShowCurrentUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ShowCurrentUser execution.
     *
     * @return Zendesk_Users_ShowCurrentUser_Results New results object.
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
     * Wraps results in appopriate results class for this ShowCurrentUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_ShowCurrentUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_ShowCurrentUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ShowCurrentUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ShowCurrentUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ShowCurrentUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_ShowCurrentUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ShowCurrentUser execution.
     *
     * @return string The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Uploads a file to be used as an attachment to a ticket.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Attachments_UploadAttachment extends Temboo_Choreography
{
    /**
     * Uploads a file to be used as an attachment to a ticket.
     *
     * @param Temboo_Session $session The session that owns this UploadAttachment choreography.
     * @return Zendesk_Attachments_UploadAttachment New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Attachments/UploadAttachment/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UploadAttachment choreography.
     *
     * @param Zendesk_Attachments_UploadAttachment_Inputs|array $inputs (optional) Inputs as Zendesk_Attachments_UploadAttachment_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Attachments_UploadAttachment_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Attachments_UploadAttachment_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UploadAttachment choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Attachments_UploadAttachment_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Attachments_UploadAttachment_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UploadAttachment choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Attachments_UploadAttachment_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UploadAttachment choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Attachments_UploadAttachment_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UploadAttachment input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Attachments_UploadAttachment_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Attachments_UploadAttachment_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this UploadAttachment choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Attachments_UploadAttachment_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the ExistingToken input for this UploadAttachment choreography.
     *
     * @param string $value (optional, string) Allows you to pass in an existing token when uploading multiple attachments to associate with a ticket creation.
     * @return Zendesk_Attachments_UploadAttachment_Inputs For method chaining.
     */
    public function setExistingToken($value)
    {
        return $this->set('ExistingToken', $value);
    }

    /**
     * Set the value for the FileContents input for this UploadAttachment choreography.
     *
     * @param string $value (required, string) The Base64 encoded file contents of the attachment you want to upload.
     * @return Zendesk_Attachments_UploadAttachment_Inputs For method chaining.
     */
    public function setFileContents($value)
    {
        return $this->set('FileContents', $value);
    }

    /**
     * Set the value for the FileName input for this UploadAttachment choreography.
     *
     * @param string $value (required, string) 
     * @return Zendesk_Attachments_UploadAttachment_Inputs For method chaining.
     */
    public function setFileName($value)
    {
        return $this->set('FileName', $value);
    }

    /**
     * Set the value for the Password input for this UploadAttachment choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Attachments_UploadAttachment_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this UploadAttachment choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. support.temboo.com or temboo.zendesk.com).
     * @return Zendesk_Attachments_UploadAttachment_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

}


/**
 * Execution object for the UploadAttachment choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Attachments_UploadAttachment_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UploadAttachment choreography.
     *
     * @param Temboo_Session $session The session that owns this UploadAttachment execution.
     * @param Zendesk_Attachments_UploadAttachment $choreo The choregraphy object for this execution.
     * @param Zendesk_Attachments_UploadAttachment_Inputs|array $inputs (optional) Inputs as Zendesk_Attachments_UploadAttachment_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Attachments_UploadAttachment_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Attachments_UploadAttachment $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UploadAttachment execution.
     *
     * @return Zendesk_Attachments_UploadAttachment_Results New results object.
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
     * Wraps results in appopriate results class for this UploadAttachment execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Attachments_UploadAttachment_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Attachments_UploadAttachment_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UploadAttachment choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Attachments_UploadAttachment_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UploadAttachment choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Attachments_UploadAttachment_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UploadAttachment execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }

    /**
     * Retrieve the value for the "Token" output from this UploadAttachment execution.
     *
     * @return string (string) The token returned from Zendesk for the upload. This can be passed to the ExistingToken input when associating  multiple attachments to the same upload token.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getToken()
    {
        return $this->get('Token');
    }
}

/**
 * Retrieves a list of all existing tickets.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListAllTickets extends Temboo_Choreography
{
    /**
     * Retrieves a list of all existing tickets.
     *
     * @param Temboo_Session $session The session that owns this ListAllTickets choreography.
     * @return Zendesk_Tickets_ListAllTickets New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Tickets/ListAllTickets/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListAllTickets choreography.
     *
     * @param Zendesk_Tickets_ListAllTickets_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListAllTickets_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListAllTickets_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Tickets_ListAllTickets_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListAllTickets choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListAllTickets_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Tickets_ListAllTickets_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListAllTickets choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListAllTickets_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListAllTickets choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Tickets_ListAllTickets_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListAllTickets input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Tickets_ListAllTickets_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Tickets_ListAllTickets_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListAllTickets choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListAllTickets_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Number input for this ListAllTickets choreography.
     *
     * @param int $value (optional, integer) The number of results to return per page. Maximum is 100 and default is 100.
     * @return Zendesk_Tickets_ListAllTickets_Inputs For method chaining.
     */
    public function setNumber($value)
    {
        return $this->set('Number', $value);
    }

    /**
     * Set the value for the Page input for this ListAllTickets choreography.
     *
     * @param int $value (optional, integer) The page number of the results to be returned. Used together with the Number parameter to paginate a large set of results.
     * @return Zendesk_Tickets_ListAllTickets_Inputs For method chaining.
     */
    public function setPage($value)
    {
        return $this->set('Page', $value);
    }

    /**
     * Set the value for the Password input for this ListAllTickets choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Tickets_ListAllTickets_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListAllTickets choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Tickets_ListAllTickets_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListAllTickets choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListAllTickets_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListAllTickets choreography.
     *
     * @param Temboo_Session $session The session that owns this ListAllTickets execution.
     * @param Zendesk_Tickets_ListAllTickets $choreo The choregraphy object for this execution.
     * @param Zendesk_Tickets_ListAllTickets_Inputs|array $inputs (optional) Inputs as Zendesk_Tickets_ListAllTickets_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Tickets_ListAllTickets_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Tickets_ListAllTickets $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListAllTickets execution.
     *
     * @return Zendesk_Tickets_ListAllTickets_Results New results object.
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
     * Wraps results in appopriate results class for this ListAllTickets execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Tickets_ListAllTickets_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Tickets_ListAllTickets_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListAllTickets choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Tickets_ListAllTickets_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListAllTickets choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Tickets_ListAllTickets_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListAllTickets execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Retrieves a list of all users in a specified group.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListUsersByGroup extends Temboo_Choreography
{
    /**
     * Retrieves a list of all users in a specified group.
     *
     * @param Temboo_Session $session The session that owns this ListUsersByGroup choreography.
     * @return Zendesk_Users_ListUsersByGroup New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/ListUsersByGroup/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this ListUsersByGroup choreography.
     *
     * @param Zendesk_Users_ListUsersByGroup_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ListUsersByGroup_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ListUsersByGroup_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_ListUsersByGroup_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this ListUsersByGroup choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ListUsersByGroup_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_ListUsersByGroup_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the ListUsersByGroup choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListUsersByGroup_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the ListUsersByGroup choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_ListUsersByGroup_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this ListUsersByGroup input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_ListUsersByGroup_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_ListUsersByGroup_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this ListUsersByGroup choreography.
     *
     * @param string $value (required, string) The email to authenticate the Zendesk account.
     * @return Zendesk_Users_ListUsersByGroup_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the GroupID input for this ListUsersByGroup choreography.
     *
     * @param int $value (required, integer) The ID number of the group.
     * @return Zendesk_Users_ListUsersByGroup_Inputs For method chaining.
     */
    public function setGroupID($value)
    {
        return $this->set('GroupID', $value);
    }

    /**
     * Set the value for the Password input for this ListUsersByGroup choreography.
     *
     * @param string $value (required, password) The password matching the email to authenticate the Zendesk account.
     * @return Zendesk_Users_ListUsersByGroup_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this ListUsersByGroup choreography.
     *
     * @param string $value (required, string) The server URL associated with your Zendesk account. In many cases this looks like: temboo.zendesk.com but may also be customized: support.temboo.com
     * @return Zendesk_Users_ListUsersByGroup_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }
}


/**
 * Execution object for the ListUsersByGroup choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListUsersByGroup_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the ListUsersByGroup choreography.
     *
     * @param Temboo_Session $session The session that owns this ListUsersByGroup execution.
     * @param Zendesk_Users_ListUsersByGroup $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_ListUsersByGroup_Inputs|array $inputs (optional) Inputs as Zendesk_Users_ListUsersByGroup_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_ListUsersByGroup_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_ListUsersByGroup $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this ListUsersByGroup execution.
     *
     * @return Zendesk_Users_ListUsersByGroup_Results New results object.
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
     * Wraps results in appopriate results class for this ListUsersByGroup execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_ListUsersByGroup_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_ListUsersByGroup_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the ListUsersByGroup choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_ListUsersByGroup_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the ListUsersByGroup choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_ListUsersByGroup_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this ListUsersByGroup execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Creates a new user.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_CreateUser extends Temboo_Choreography
{
    /**
     * Creates a new user.
     *
     * @param Temboo_Session $session The session that owns this CreateUser choreography.
     * @return Zendesk_Users_CreateUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/CreateUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this CreateUser choreography.
     *
     * @param Zendesk_Users_CreateUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_CreateUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_CreateUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_CreateUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this CreateUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_CreateUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_CreateUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the CreateUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_CreateUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the CreateUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_CreateUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this CreateUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_CreateUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_CreateUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this CreateUser choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Users_CreateUser_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this CreateUser choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Users_CreateUser_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this CreateUser choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. support.temboo.com or temboo.zendesk.com).
     * @return Zendesk_Users_CreateUser_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the UserData input for this CreateUser choreography.
     *
     * @param string $value (required, json) A JSON string containing the new user's information. See documentation for formatting details.
     * @return Zendesk_Users_CreateUser_Inputs For method chaining.
     */
    public function setUserData($value)
    {
        return $this->set('UserData', $value);
    }
}


/**
 * Execution object for the CreateUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_CreateUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the CreateUser choreography.
     *
     * @param Temboo_Session $session The session that owns this CreateUser execution.
     * @param Zendesk_Users_CreateUser $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_CreateUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_CreateUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_CreateUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_CreateUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this CreateUser execution.
     *
     * @return Zendesk_Users_CreateUser_Results New results object.
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
     * Wraps results in appopriate results class for this CreateUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_CreateUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_CreateUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the CreateUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_CreateUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the CreateUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_CreateUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this CreateUser execution.
     *
     * @return string (json) 
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

/**
 * Updates an existing user.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_UpdateUser extends Temboo_Choreography
{
    /**
     * Updates an existing user.
     *
     * @param Temboo_Session $session The session that owns this UpdateUser choreography.
     * @return Zendesk_Users_UpdateUser New instance.
     */
    public function __construct(Temboo_Session $session)
    {
        parent::__construct($session, '/Library/Zendesk/Users/UpdateUser/');
    }

    /**
     * Executes this choreography.
     *
     * Execution object provides access to results appropriate for this UpdateUser choreography.
     *
     * @param Zendesk_Users_UpdateUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_UpdateUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_UpdateUser_Execution New execution object.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     * @throws Temboo_Exception if execution request fails.
     */
    public function execute($inputs = array(), $async = false, $store_results = true)
    {
        return new Zendesk_Users_UpdateUser_Execution($this->session, $this, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new inputs object.
     *
     * Includes setters appropriate for this UpdateUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_UpdateUser_Inputs New inputs object.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function newInputs($inputs = array())
    {
        return new Zendesk_Users_UpdateUser_Inputs($inputs);
    }
}


/**
 * Inputs object with appropriate setters for the UpdateUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_UpdateUser_Inputs extends Temboo_Inputs
{
   /**
     * Inputs object with appopriate setters for the UpdateUser choreography.
     *
     * @param array $inputs (optional) Associative array of input names and values.
     * @return Zendesk_Users_UpdateUser_Inputs New instance.
     * @throws Temboo_Exception if provided input set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($inputs = array())
    {
        parent::__construct($inputs);
    }

    /**
     * Set arbitrary input this UpdateUser input set.
     *
     * Input names are case sensitive.
     *
     * @param string $name Input name.
     * @param string $value Input value.
     * @return Zendesk_Users_UpdateUser_Inputs For method chaining.
     */
    public function set($name, $value)
    {
        return parent::set($name, $value);
    }

    /**
     * Set credential
     *
     * @param string $credentialName The name of a Credential in your account specifying presets for this set of inputs.
     * @return Zendesk_Users_UpdateUser_Inputs For method chaining.
     */
    public function setCredential($credentialName)
    {
        return parent::setCredential($credentialName);
    }

    /**
     * Set the value for the Email input for this UpdateUser choreography.
     *
     * @param string $value (required, string) The email address you use to login to your Zendesk account.
     * @return Zendesk_Users_UpdateUser_Inputs For method chaining.
     */
    public function setEmail($value)
    {
        return $this->set('Email', $value);
    }

    /**
     * Set the value for the Password input for this UpdateUser choreography.
     *
     * @param string $value (required, password) Your Zendesk password.
     * @return Zendesk_Users_UpdateUser_Inputs For method chaining.
     */
    public function setPassword($value)
    {
        return $this->set('Password', $value);
    }

    /**
     * Set the value for the Server input for this UpdateUser choreography.
     *
     * @param string $value (required, string) Your Zendesk domain and subdomain (i.e. temboocare.zendesk.com).
     * @return Zendesk_Users_UpdateUser_Inputs For method chaining.
     */
    public function setServer($value)
    {
        return $this->set('Server', $value);
    }

    /**
     * Set the value for the UserData input for this UpdateUser choreography.
     *
     * @param string $value (required, json) A JSON string containing the updated user data. See documentation for formatting details.
     * @return Zendesk_Users_UpdateUser_Inputs For method chaining.
     */
    public function setUserData($value)
    {
        return $this->set('UserData', $value);
    }

    /**
     * Set the value for the UserID input for this UpdateUser choreography.
     *
     * @param int $value (required, integer) The id of the user being updated.
     * @return Zendesk_Users_UpdateUser_Inputs For method chaining.
     */
    public function setUserID($value)
    {
        return $this->set('UserID', $value);
    }
}


/**
 * Execution object for the UpdateUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_UpdateUser_Execution extends Temboo_Choreography_Execution
{
    /**
     * Execution object for the UpdateUser choreography.
     *
     * @param Temboo_Session $session The session that owns this UpdateUser execution.
     * @param Zendesk_Users_UpdateUser $choreo The choregraphy object for this execution.
     * @param Zendesk_Users_UpdateUser_Inputs|array $inputs (optional) Inputs as Zendesk_Users_UpdateUser_Inputs or associative array.
     * @param bool $async Whether to execute in asynchronous mode. Default false.
     * @param bool $store_results Whether to store results of asynchronous execution. Default true.
     * @return Zendesk_Users_UpdateUser_Execution New execution.
     * @throws Temboo_Exception_Authentication if session authentication fails.
     * @throws Temboo_Exception_Execution if runtime errors occur in synchronous execution or execution fails to start. NOT thrown for post-launch errors in asynchronous execution -- check status or results to determine asynchronous success.
     * @throws Temboo_Exception_Notfound if choreography does not exist.
     */
    public function __construct(Temboo_Session $session, Zendesk_Users_UpdateUser $choreo, $inputs = array(), $async = false, $store_results = true)
    {
        parent::__construct($session, $choreo, $inputs, $async, $store_results);
    }

    /**
     * Obtains a new results object.
     *
     * Includes getters appropriate for this UpdateUser execution.
     *
     * @return Zendesk_Users_UpdateUser_Results New results object.
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
     * Wraps results in appopriate results class for this UpdateUser execution.
     *
     * @param array $outputs Associative array of output names and values.
     * @return Zendesk_Users_UpdateUser_Results New results object.
     */
    protected function wrapResults($outputs)
    {
        return new Zendesk_Users_UpdateUser_Results($outputs);
    }
}


/**
 * Results object with appopriate getters for the UpdateUser choreography.
 *
 * @package Temboo
 * @subpackage Zendesk
 */
class Zendesk_Users_UpdateUser_Results extends Temboo_Results
{
    /**
     * Results object with appopriate getters for the UpdateUser choreography.
     *
     * @param array $outputs (optional) Associative array of output names and values.
     * @return Zendesk_Users_UpdateUser_Results New instance.
     * @throws Temboo_Exception if provided output set is invalid. (Note an empty set is considered valid.)
     */
    public function __construct($outputs = array())
    {
        parent::__construct($outputs);
    }

    /**
     * Retrieve the value for the "Response" output from this UpdateUser execution.
     *
     * @return string (json) The response from Zendesk.
     * @throws Temboo_Exception_Notfound if output does not exist. (Note an empty response is considered valid.)
     */
    public function getResponse()
    {
        return $this->get('Response');
    }
}

?>