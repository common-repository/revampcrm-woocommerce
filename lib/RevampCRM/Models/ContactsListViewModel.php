<?php
/**
 * ContactsListViewModel
 *
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

namespace RevampCRM\Models;

use \ArrayAccess;

/**
 * ContactsListViewModel Class Doc Comment
 *
 * @category    Class */
/** 
 * @package     RevampCRM\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class ContactsListViewModel implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'ContactsListViewModel';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'results_info' => '\RevampCRM\Models\PagerViewModel',
        'contacts' => '\RevampCRM\Models\ContactListItemViewModel[]',
        'is_search' => 'bool',
        'show_create_group' => 'bool',
        'is_edit' => 'bool',
        'search_result' => 'int',
        'group_id' => 'string',
        'is_saved_search' => 'bool',
        'requested_search' => '\RevampCRM\Models\SearchParametersViewModel',
        'current_user_id' => 'string'
    );

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = array(
        'results_info' => 'resultsInfo',
        'contacts' => 'contacts',
        'is_search' => 'IsSearch',
        'show_create_group' => 'ShowCreateGroup',
        'is_edit' => 'IsEdit',
        'search_result' => 'SearchResult',
        'group_id' => 'GroupId',
        'is_saved_search' => 'IsSavedSearch',
        'requested_search' => 'RequestedSearch',
        'current_user_id' => 'CurrentUserId'
    );

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = array(
        'results_info' => 'setResultsInfo',
        'contacts' => 'setContacts',
        'is_search' => 'setIsSearch',
        'show_create_group' => 'setShowCreateGroup',
        'is_edit' => 'setIsEdit',
        'search_result' => 'setSearchResult',
        'group_id' => 'setGroupId',
        'is_saved_search' => 'setIsSavedSearch',
        'requested_search' => 'setRequestedSearch',
        'current_user_id' => 'setCurrentUserId'
    );

    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = array(
        'results_info' => 'getResultsInfo',
        'contacts' => 'getContacts',
        'is_search' => 'getIsSearch',
        'show_create_group' => 'getShowCreateGroup',
        'is_edit' => 'getIsEdit',
        'search_result' => 'getSearchResult',
        'group_id' => 'getGroupId',
        'is_saved_search' => 'getIsSavedSearch',
        'requested_search' => 'getRequestedSearch',
        'current_user_id' => 'getCurrentUserId'
    );

    public static function getters()
    {
        return self::$getters;
    }

    

    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = array();

    /**
     * Constructor
     * @param mixed[] $data Associated array of property value initalizing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['results_info'] = isset($data['results_info']) ? $data['results_info'] : null;
        $this->container['contacts'] = isset($data['contacts']) ? $data['contacts'] : null;
        $this->container['is_search'] = isset($data['is_search']) ? $data['is_search'] : null;
        $this->container['show_create_group'] = isset($data['show_create_group']) ? $data['show_create_group'] : null;
        $this->container['is_edit'] = isset($data['is_edit']) ? $data['is_edit'] : null;
        $this->container['search_result'] = isset($data['search_result']) ? $data['search_result'] : null;
        $this->container['group_id'] = isset($data['group_id']) ? $data['group_id'] : null;
        $this->container['is_saved_search'] = isset($data['is_saved_search']) ? $data['is_saved_search'] : null;
        $this->container['requested_search'] = isset($data['requested_search']) ? $data['requested_search'] : null;
        $this->container['current_user_id'] = isset($data['current_user_id']) ? $data['current_user_id'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properteis are valid
     */
    public function valid()
    {
        return true;
    }


    /**
     * Gets results_info
     * @return \RevampCRM\Models\PagerViewModel
     */
    public function getResultsInfo()
    {
        return $this->container['results_info'];
    }

    /**
     * Sets results_info
     * @param \RevampCRM\Models\PagerViewModel $results_info
     * @return $this
     */
    public function setResultsInfo($results_info)
    {
        $this->container['results_info'] = $results_info;

        return $this;
    }

    /**
     * Gets contacts
     * @return \RevampCRM\Models\ContactListItemViewModel[]
     */
    public function getContacts()
    {
        return $this->container['contacts'];
    }

    /**
     * Sets contacts
     * @param \RevampCRM\Models\ContactListItemViewModel[] $contacts
     * @return $this
     */
    public function setContacts($contacts)
    {
        $this->container['contacts'] = $contacts;

        return $this;
    }

    /**
     * Gets is_search
     * @return bool
     */
    public function getIsSearch()
    {
        return $this->container['is_search'];
    }

    /**
     * Sets is_search
     * @param bool $is_search
     * @return $this
     */
    public function setIsSearch($is_search)
    {
        $this->container['is_search'] = $is_search;

        return $this;
    }

    /**
     * Gets show_create_group
     * @return bool
     */
    public function getShowCreateGroup()
    {
        return $this->container['show_create_group'];
    }

    /**
     * Sets show_create_group
     * @param bool $show_create_group
     * @return $this
     */
    public function setShowCreateGroup($show_create_group)
    {
        $this->container['show_create_group'] = $show_create_group;

        return $this;
    }

    /**
     * Gets is_edit
     * @return bool
     */
    public function getIsEdit()
    {
        return $this->container['is_edit'];
    }

    /**
     * Sets is_edit
     * @param bool $is_edit
     * @return $this
     */
    public function setIsEdit($is_edit)
    {
        $this->container['is_edit'] = $is_edit;

        return $this;
    }

    /**
     * Gets search_result
     * @return int
     */
    public function getSearchResult()
    {
        return $this->container['search_result'];
    }

    /**
     * Sets search_result
     * @param int $search_result
     * @return $this
     */
    public function setSearchResult($search_result)
    {
        $this->container['search_result'] = $search_result;

        return $this;
    }

    /**
     * Gets group_id
     * @return string
     */
    public function getGroupId()
    {
        return $this->container['group_id'];
    }

    /**
     * Sets group_id
     * @param string $group_id
     * @return $this
     */
    public function setGroupId($group_id)
    {
        $this->container['group_id'] = $group_id;

        return $this;
    }

    /**
     * Gets is_saved_search
     * @return bool
     */
    public function getIsSavedSearch()
    {
        return $this->container['is_saved_search'];
    }

    /**
     * Sets is_saved_search
     * @param bool $is_saved_search
     * @return $this
     */
    public function setIsSavedSearch($is_saved_search)
    {
        $this->container['is_saved_search'] = $is_saved_search;

        return $this;
    }

    /**
     * Gets requested_search
     * @return \RevampCRM\Models\SearchParametersViewModel
     */
    public function getRequestedSearch()
    {
        return $this->container['requested_search'];
    }

    /**
     * Sets requested_search
     * @param \RevampCRM\Models\SearchParametersViewModel $requested_search
     * @return $this
     */
    public function setRequestedSearch($requested_search)
    {
        $this->container['requested_search'] = $requested_search;

        return $this;
    }

    /**
     * Gets current_user_id
     * @return string
     */
    public function getCurrentUserId()
    {
        return $this->container['current_user_id'];
    }

    /**
     * Sets current_user_id
     * @param string $current_user_id
     * @return $this
     */
    public function setCurrentUserId($current_user_id)
    {
        $this->container['current_user_id'] = $current_user_id;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\RevampCRM\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\RevampCRM\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}


