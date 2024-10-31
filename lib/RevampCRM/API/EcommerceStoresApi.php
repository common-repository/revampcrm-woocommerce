<?php
/**
 * EcommerceStoresApi
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
 * EcommerceStoresApi Class Doc Comment
 *
 * @category Class
 * @package  RevampCRM\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class EcommerceStoresApi
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
     * @return EcommerceStoresApi
     */
    public function setApiClient(\RevampCRM\Client\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Operation eCommerceStoreDelete
     *
     * Delete a single store
     *
     * @param string $store_id  (required)
     * @return \RevampCRM\Models\EcommerceStoreResponse
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreDelete($store_id)
    {
        list($response) = $this->eCommerceStoreDeleteWithHttpInfo($store_id);
        return $response;
    }

    /**
     * Operation eCommerceStoreDeleteWithHttpInfo
     *
     * Delete a single store
     *
     * @param string $store_id  (required)
     * @return Array of \RevampCRM\Models\EcommerceStoreResponse, HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreDeleteWithHttpInfo($store_id)
    {
        // verify the required parameter 'store_id' is set
        if ($store_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $store_id when calling eCommerceStoreDelete');
        }
        // parse inputs
        $resourcePath = "/api/1.0/Stores/Delete/{StoreId}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/json', 'text/json', 'application/xml', 'text/xml', 'text/html', 'application/xhtml', 'application/xhtml+xml', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array());

        // path params
        if ($store_id !== null) {
            $resourcePath = str_replace(
                "{" . "StoreId" . "}",
                $this->apiClient->getSerializer()->toPathValue($store_id),
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
                'DELETE',
                $queryParams,
                $httpBody,
                $headerParams,
                '\RevampCRM\Models\EcommerceStoreResponse',
                '/api/1.0/Stores/Delete/{StoreId}'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\RevampCRM\Models\EcommerceStoreResponse', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\RevampCRM\Models\EcommerceStoreResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation eCommerceStoreGet
     *
     * Gets Account stores
     *
     * @param string $provider_name Store Provider (e.g. Shopify ) (required)
     * @param string $id Store Id (required)
     * @return \RevampCRM\Models\ECommerceStoreApiModel[]
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreGet($provider_name, $id)
    {
        list($response) = $this->eCommerceStoreGetWithHttpInfo($provider_name, $id);
        return $response;
    }

    /**
     * Operation eCommerceStoreGetWithHttpInfo
     *
     * Gets Account stores
     *
     * @param string $provider_name Store Provider (e.g. Shopify ) (required)
     * @param string $id Store Id (required)
     * @return Array of \RevampCRM\Models\ECommerceStoreApiModel[], HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreGetWithHttpInfo($provider_name, $id)
    {
        // verify the required parameter 'provider_name' is set
        if ($provider_name === null) {
            throw new \InvalidArgumentException('Missing the required parameter $provider_name when calling eCommerceStoreGet');
        }
        // verify the required parameter 'id' is set
        if ($id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $id when calling eCommerceStoreGet');
        }
        // parse inputs
        $resourcePath = "/api/1.0/Stores/Get/{providerName}/{id}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/json', 'text/json', 'application/xml', 'text/xml', 'text/html', 'application/xhtml', 'application/xhtml+xml', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array());

        // path params
        if ($provider_name !== null) {
            $resourcePath = str_replace(
                "{" . "providerName" . "}",
                $this->apiClient->getSerializer()->toPathValue($provider_name),
                $resourcePath
            );
        }
        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                "{" . "id" . "}",
                $this->apiClient->getSerializer()->toPathValue($id),
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
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                '\RevampCRM\Models\ECommerceStoreApiModel[]',
                '/api/1.0/Stores/Get/{providerName}/{id}'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\RevampCRM\Models\ECommerceStoreApiModel[]', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\RevampCRM\Models\ECommerceStoreApiModel[]', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation eCommerceStoreSave
     *
     * Save a single store
     *
     * @param \RevampCRM\Models\ECommerceStoreApiModel $store  (required)
     * @return \RevampCRM\Models\EcommerceStoreResponse
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreSave($store)
    {
        list($response) = $this->eCommerceStoreSaveWithHttpInfo($store);
        return $response;
    }

    /**
     * Operation eCommerceStoreSaveWithHttpInfo
     *
     * Save a single store
     *
     * @param \RevampCRM\Models\ECommerceStoreApiModel $store  (required)
     * @return Array of \RevampCRM\Models\EcommerceStoreResponse, HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreSaveWithHttpInfo($store)
    {
        // verify the required parameter 'store' is set
        if ($store === null) {
            throw new \InvalidArgumentException('Missing the required parameter $store when calling eCommerceStoreSave');
        }
        // parse inputs
        $resourcePath = "/api/1.0/Stores/Save";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/json', 'text/json', 'application/xml', 'text/xml', 'text/html', 'application/xhtml', 'application/xhtml+xml', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/json','text/json','application/xml','text/xml','application/x-www-form-urlencoded','text/csv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'));

        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($store)) {
            $_tempBody = $store;
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
                '\RevampCRM\Models\EcommerceStoreResponse',
                '/api/1.0/Stores/Save'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\RevampCRM\Models\EcommerceStoreResponse', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\RevampCRM\Models\EcommerceStoreResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation eCommerceStoreUpdate
     *
     * Save a single store
     *
     * @param string $store_id  (required)
     * @param \RevampCRM\Models\ECommerceStoreApiModel $store  (required)
     * @return \RevampCRM\Models\EcommerceStoreResponse
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreUpdate($store_id, $store)
    {
        list($response) = $this->eCommerceStoreUpdateWithHttpInfo($store_id, $store);
        return $response;
    }

    /**
     * Operation eCommerceStoreUpdateWithHttpInfo
     *
     * Save a single store
     *
     * @param string $store_id  (required)
     * @param \RevampCRM\Models\ECommerceStoreApiModel $store  (required)
     * @return Array of \RevampCRM\Models\EcommerceStoreResponse, HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreUpdateWithHttpInfo($store_id, $store)
    {
        // verify the required parameter 'store_id' is set
        if ($store_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $store_id when calling eCommerceStoreUpdate');
        }
        // verify the required parameter 'store' is set
        if ($store === null) {
            throw new \InvalidArgumentException('Missing the required parameter $store when calling eCommerceStoreUpdate');
        }
        // parse inputs
        $resourcePath = "/api/1.0/Stores/Update/{StoreId}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/json', 'text/json', 'application/xml', 'text/xml', 'text/html', 'application/xhtml', 'application/xhtml+xml', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/json','text/json','application/xml','text/xml','application/x-www-form-urlencoded','text/csv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'));

        // path params
        if ($store_id !== null) {
            $resourcePath = str_replace(
                "{" . "StoreId" . "}",
                $this->apiClient->getSerializer()->toPathValue($store_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($store)) {
            $_tempBody = $store;
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
                '\RevampCRM\Models\EcommerceStoreResponse',
                '/api/1.0/Stores/Update/{StoreId}'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\RevampCRM\Models\EcommerceStoreResponse', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\RevampCRM\Models\EcommerceStoreResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

     /**
     * Operation eCommerceStoreFlagStartSyncing
     *
     * Save a single store
     *
     * @param string $store_id  (required)
     * @param \RevampCRM\Models\ECommerceStoreApiModel $store  (required)
     * @return \RevampCRM\Models\EcommerceStoreResponse
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreFlagStartSyncing($provider_name,$store_id)
    {
        list($response) = $this->eCommerceStoreFlagStartSyncingWithHttpInfo($provider_name,$store_id);
        return $response;
    }

    /**
     * Operation eCommerceStoreFlagStartSyncingWithHttpInfo
     *
     * Save a single store
     *
     * @param string $store_id  (required)
     * @return Array of \RevampCRM\Models\EcommerceStoreResponse, HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreFlagStartSyncingWithHttpInfo($provider_name,$store_id)
    {
        // verify the required parameter 'store_id' is set
        if ($store_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $store_id when calling eCommerceStoreUpdate');
        }
        // parse inputs
        $resourcePath = "/api/1.0/Stores/{ProviderName}/{StoreId}/SyncingStarted";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/json', 'text/json', 'application/xml', 'text/xml', 'text/html', 'application/xhtml', 'application/xhtml+xml', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/json','text/json','application/xml','text/xml','application/x-www-form-urlencoded','text/csv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'));

        // path params
        if ($store_id !== null) {
            $resourcePath = str_replace(
                "{" . "StoreId" . "}",
                $this->apiClient->getSerializer()->toPathValue($store_id),
                $resourcePath
            );
        }
        if ($provider_name !== null) {
            $resourcePath = str_replace(
                "{" . "ProviderName" . "}",
                $this->apiClient->getSerializer()->toPathValue($provider_name),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($store)) {
            $_tempBody = $store;
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
                '\RevampCRM\Models\EcommerceStoreResponse',
                '/api/1.0/Stores/{ProviderName}/{StoreId}/SyncingStarted'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\RevampCRM\Models\EcommerceStoreResponse', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\RevampCRM\Models\EcommerceStoreResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

     /**
     * Operation eCommerceStoreFlagCompletedSyncing
     *
     * Save a single store
     *
     * @param string $store_id  (required)
     * @param \RevampCRM\Models\ECommerceStoreApiModel $store  (required)
     * @return \RevampCRM\Models\EcommerceStoreResponse
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreFlagCompletedSyncing($provider_name,$store_id)
    {
        list($response) = $this->eCommerceStoreFlagCompletedSyncingWithHttpInfo($provider_name,$store_id);
        return $response;
    }

    /**
     * Operation eCommerceStoreFlagCompletedSyncingWithHttpInfo
     *
     * Save a single store
     *
     * @param string $store_id  (required)
     * @return Array of \RevampCRM\Models\EcommerceStoreResponse, HTTP status code, HTTP response headers (array of strings)
     * @throws \RevampCRM\Client\ApiException on non-2xx response
     */
    public function eCommerceStoreFlagCompletedSyncingWithHttpInfo($provider_name,$store_id)
    {
        // verify the required parameter 'store_id' is set
        if ($store_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $store_id when calling eCommerceStoreUpdate');
        }
        // parse inputs
        $resourcePath = "/api/1.0/Stores/{ProviderName}/{StoreId}/SyncingCompleted";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/json', 'text/json', 'application/xml', 'text/xml', 'text/html', 'application/xhtml', 'application/xhtml+xml', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/json','text/json','application/xml','text/xml','application/x-www-form-urlencoded','text/csv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'));

        // path params
        if ($store_id !== null) {
            $resourcePath = str_replace(
                "{" . "StoreId" . "}",
                $this->apiClient->getSerializer()->toPathValue($store_id),
                $resourcePath
            );
        }
        if ($provider_name !== null) {
            $resourcePath = str_replace(
                "{" . "ProviderName" . "}",
                $this->apiClient->getSerializer()->toPathValue($provider_name),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($store)) {
            $_tempBody = $store;
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
                '\RevampCRM\Models\EcommerceStoreResponse',
                '/api/1.0/Stores/{ProviderName}/{StoreId}/SyncingCompleted'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\RevampCRM\Models\EcommerceStoreResponse', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\RevampCRM\Models\EcommerceStoreResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }
}
