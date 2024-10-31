<?php
/**
 * AccountRemindersApi
 * PHP version 5
 *
 * @category Class
 * @package  RevampCRM\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * RevampCRM Developer API
 *
 * You can use this API to access Account related info and contacts. We currently support Basic Authentication. Using 'Username' and either 'Password' Or 'API Key' as the password
 *
 * OpenAPI spec version: v1
 * Contact: crm@revampco.com
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace RevampCRM\API;

use \RevampCRM\Client\Configuration;
use \RevampCRM\Client\ApiClient;
use \RevampCRM\Client\ApiException;
use \RevampCRM\Client\ObjectSerializer;

/**
 * AccountRemindersApi Class Doc Comment
 *
 * @category Class
 * @package  RevampCRM\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class AccountRemindersApi
{

    /**
     * API Client
     *
     * @var \RevampCRM\Client\ApiClient instance of the ApiClient
     */
    protected $apiClient;

    /**
     * Constructor
     *
     * @param \RevampCRM\Client\ApiClient|null $apiClient The api client to use
     */
    public function __construct(\RevampCRM\Client\ApiClient $apiClient = null)
    {
        if ($apiClient == null) {
            $apiClient = new ApiClient();
            $apiClient->getConfig()->setHost('https://app.revampcrm.com:443');
        }

        $this->apiClient = $apiClient;
    }

    /**
     * Get API client
     *
     * @return \RevampCRM\Client\ApiClient get the API client
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set the API client
     *
     * @param \RevampCRM\Client\ApiClient $apiClient set the API client
     *
     * @return AccountRemindersApi
     */
    public function setApiClient(\RevampCRM\Client\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Operation dashboardAddTaskForFollowUp
     *
     * Signals a Follow up was already done on that reminder and provides the done Task details and note
     *
     * @param \RevampCRM\Models\FollowUpTask $followed_up_details  (required)
     * @return void
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function dashboardAddTaskForFollowUp($followed_up_details)
    {
        list($response) = $this->dashboardAddTaskForFollowUpWithHttpInfo($followed_up_details);
        return $response;
    }

    /**
     * Operation dashboardAddTaskForFollowUpWithHttpInfo
     *
     * Signals a Follow up was already done on that reminder and provides the done Task details and note
     *
     * @param \RevampCRM\Models\FollowUpTask $followed_up_details  (required)
     * @return Array of null, HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function dashboardAddTaskForFollowUpWithHttpInfo($followed_up_details)
    {
        // verify the required parameter 'followed_up_details' is set
        if ($followed_up_details === null) {
            throw new \InvalidArgumentException('Missing the required parameter $followed_up_details when calling dashboardAddTaskForFollowUp');
        }
        // parse inputs
        $resourcePath = "/api/1.0/AccountReminders/FollowedUp";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array());
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/json','text/json','application/xml','text/xml','application/x-www-form-urlencoded','text/csv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'));

        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($followed_up_details)) {
            $_tempBody = $followed_up_details;
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/api/1.0/AccountReminders/FollowedUp'
            );

            return array(null, $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation dashboardCompleteReminder
     *
     * 
     *
     * @param string $reminder_id  (required)
     * @return void
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function dashboardCompleteReminder($reminder_id)
    {
        list($response) = $this->dashboardCompleteReminderWithHttpInfo($reminder_id);
        return $response;
    }

    /**
     * Operation dashboardCompleteReminderWithHttpInfo
     *
     * 
     *
     * @param string $reminder_id  (required)
     * @return Array of null, HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function dashboardCompleteReminderWithHttpInfo($reminder_id)
    {
        // verify the required parameter 'reminder_id' is set
        if ($reminder_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $reminder_id when calling dashboardCompleteReminder');
        }
        // parse inputs
        $resourcePath = "/api/1.0/AccountReminders/Complete/{reminderId}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array());
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array());

        // path params
        if ($reminder_id !== null) {
            $resourcePath = str_replace(
                "{" . "reminderId" . "}",
                $this->apiClient->getSerializer()->toPathValue($reminder_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/api/1.0/AccountReminders/Complete/{reminderId}'
            );

            return array(null, $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation dashboardSnooze
     *
     * 
     *
     * @param string $reminder_id  (required)
     * @param string $snooze_day  (required)
     * @return void
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function dashboardSnooze($reminder_id, $snooze_day)
    {
        list($response) = $this->dashboardSnoozeWithHttpInfo($reminder_id, $snooze_day);
        return $response;
    }

    /**
     * Operation dashboardSnoozeWithHttpInfo
     *
     * 
     *
     * @param string $reminder_id  (required)
     * @param string $snooze_day  (required)
     * @return Array of null, HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function dashboardSnoozeWithHttpInfo($reminder_id, $snooze_day)
    {
        // verify the required parameter 'reminder_id' is set
        if ($reminder_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $reminder_id when calling dashboardSnooze');
        }
        // verify the required parameter 'snooze_day' is set
        if ($snooze_day === null) {
            throw new \InvalidArgumentException('Missing the required parameter $snooze_day when calling dashboardSnooze');
        }
        // parse inputs
        $resourcePath = "/api/1.0/AccountReminders/Snooze/{reminderId}/{snoozeDay}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array());
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array());

        // path params
        if ($reminder_id !== null) {
            $resourcePath = str_replace(
                "{" . "reminderId" . "}",
                $this->apiClient->getSerializer()->toPathValue($reminder_id),
                $resourcePath
            );
        }
        // path params
        if ($snooze_day !== null) {
            $resourcePath = str_replace(
                "{" . "snoozeDay" . "}",
                $this->apiClient->getSerializer()->toPathValue($snooze_day),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/api/1.0/AccountReminders/Snooze/{reminderId}/{snoozeDay}'
            );

            return array(null, $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

}
