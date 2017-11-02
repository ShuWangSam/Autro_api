<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Rest\Api\V2010\Account\IncomingPhoneNumber\LocalList;
use Twilio\Rest\Api\V2010\Account\IncomingPhoneNumber\MobileList;
use Twilio\Rest\Api\V2010\Account\IncomingPhoneNumber\TollFreeList;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * @property \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumber\LocalList local
 * @property \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumber\MobileList mobile
 * @property \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumber\TollFreeList tollFree
 */
class IncomingPhoneNumberList extends ListResource {
    protected $_local = null;
    protected $_mobile = null;
    protected $_tollFree = null;

    /**
     * Construct the IncomingPhoneNumberList
     * 
     * @param Version $version Version that contains the resource
     * @param string $accountSid The unique sid that identifies this account
     * @return \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberList 
     */
    public function __construct(Version $version, $accountSid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array(
            'accountSid' => $accountSid,
        );

        $this->uri = '/Accounts/' . rawurlencode($accountSid) . '/IncomingPhoneNumbers.json';
    }

    /**
     * Streams IncomingPhoneNumberInstance records from the API as a generator
     * stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     * 
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return \Twilio\Stream stream of results
     */
    public function stream($options = array(), $limit = null, $pageSize = null) {
        $limits = $this->version->readLimits($limit, $pageSize);

        $page = $this->page($options, $limits['pageSize']);

        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }

    /**
     * Reads IncomingPhoneNumberInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     * 
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return IncomingPhoneNumberInstance[] Array of results
     */
    public function read($options = array(), $limit = null, $pageSize = null) {
        return iterator_to_array($this->stream($options, $limit, $pageSize), false);
    }

    /**
     * Retrieve a single page of IncomingPhoneNumberInstance records from the API.
     * Request is executed immediately
     * 
     * @param array|Options $options Optional Arguments
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return \Twilio\Page Page of IncomingPhoneNumberInstance
     */
    public function page($options = array(), $pageSize = Values::NONE, $pageToken = Values::NONE, $pageNumber = Values::NONE) {
        $options = new Values($options);
        $params = Values::of(array(
            'Beta' => Serialize::booleanToString($options['beta']),
            'FriendlyName' => $options['friendlyName'],
            'PhoneNumber' => $options['phoneNumber'],
            'PageToken' => $pageToken,
            'Page' => $pageNumber,
            'PageSize' => $pageSize,
        ));

        $response = $this->version->page(
            'GET',
            $this->uri,
            $params
        );

        return new IncomingPhoneNumberPage($this->version, $response, $this->solution);
    }

    /**
     * Create a new IncomingPhoneNumberInstance
     * 
     * @param array|Options $options Optional Arguments
     * @return IncomingPhoneNumberInstance Newly created IncomingPhoneNumberInstance
     */
    public function create($options = array()) {
        $options = new Values($options);

        $data = Values::of(array(
            'PhoneNumber' => $options['phoneNumber'],
            'AreaCode' => $options['areaCode'],
            'ApiVersion' => $options['apiVersion'],
            'FriendlyName' => $options['friendlyName'],
            'SmsApplicationSid' => $options['smsApplicationSid'],
            'SmsFallbackMethod' => $options['smsFallbackMethod'],
            'SmsFallbackUrl' => $options['smsFallbackUrl'],
            'SmsMethod' => $options['smsMethod'],
            'SmsUrl' => $options['smsUrl'],
            'StatusCallback' => $options['statusCallback'],
            'StatusCallbackMethod' => $options['statusCallbackMethod'],
            'VoiceApplicationSid' => $options['voiceApplicationSid'],
            'VoiceCallerIdLookup' => Serialize::booleanToString($options['voiceCallerIdLookup']),
            'VoiceFallbackMethod' => $options['voiceFallbackMethod'],
            'VoiceFallbackUrl' => $options['voiceFallbackUrl'],
            'VoiceMethod' => $options['voiceMethod'],
            'VoiceUrl' => $options['voiceUrl'],
            'EmergencyStatus' => $options['emergencyStatus'],
            'EmergencyAddressSid' => $options['emergencyAddressSid'],
            'TrunkSid' => $options['trunkSid'],
        ));

        $payload = $this->version->create(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new IncomingPhoneNumberInstance(
            $this->version,
            $payload,
            $this->solution['accountSid']
        );
    }

    /**
     * Access the local
     */
    protected function getLocal() {
        if (!$this->_local) {
            $this->_local = new LocalList(
                $this->version,
                $this->solution['accountSid']
            );
        }

        return $this->_local;
    }

    /**
     * Access the mobile
     */
    protected function getMobile() {
        if (!$this->_mobile) {
            $this->_mobile = new MobileList(
                $this->version,
                $this->solution['accountSid']
            );
        }

        return $this->_mobile;
    }

    /**
     * Access the tollFree
     */
    protected function getTollFree() {
        if (!$this->_tollFree) {
            $this->_tollFree = new TollFreeList(
                $this->version,
                $this->solution['accountSid']
            );
        }

        return $this->_tollFree;
    }

    /**
     * Constructs a IncomingPhoneNumberContext
     * 
     * @param string $sid Fetch by unique incoming-phone-number Sid
     * @return \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberContext 
     */
    public function getContext($sid) {
        return new IncomingPhoneNumberContext(
            $this->version,
            $this->solution['accountSid'],
            $sid
        );
    }

    /**
     * Magic getter to lazy load subresources
     * 
     * @param string $name Subresource to return
     * @return \Twilio\ListResource The requested subresource
     * @throws \Twilio\Exceptions\TwilioException For unknown subresources
     */
    public function __get($name) {
        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     * 
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return \Twilio\InstanceContext The requested resource context
     * @throws \Twilio\Exceptions\TwilioException For unknown resource
     */
    public function __call($name, $arguments) {
        $property = $this->$name;
        if (method_exists($property, 'getContext')) {
            return call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        return '[Twilio.Api.V2010.IncomingPhoneNumberList]';
    }
}