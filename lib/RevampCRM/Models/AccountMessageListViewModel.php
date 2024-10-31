<?php
/**
 * AccountMessageListViewModel
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
 * AccountMessageListViewModel Class Doc Comment
 *
 * @category    Class */
/** 
 * @package     RevampCRM\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class AccountMessageListViewModel implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'AccountMessageListViewModel';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'account_message_list' => '\RevampCRM\Models\AccountMessageViewModel[]',
        'pager' => '\RevampCRM\Models\ReminderPagedViewModel',
        'current_user_id' => 'string',
        'current_account_id' => 'string'
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
        'account_message_list' => 'AccountMessageList',
        'pager' => 'Pager',
        'current_user_id' => 'CurrentUserId',
        'current_account_id' => 'CurrentAccountId'
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
        'account_message_list' => 'setAccountMessageList',
        'pager' => 'setPager',
        'current_user_id' => 'setCurrentUserId',
        'current_account_id' => 'setCurrentAccountId'
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
        'account_message_list' => 'getAccountMessageList',
        'pager' => 'getPager',
        'current_user_id' => 'getCurrentUserId',
        'current_account_id' => 'getCurrentAccountId'
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
        $this->container['account_message_list'] = isset($data['account_message_list']) ? $data['account_message_list'] : null;
        $this->container['pager'] = isset($data['pager']) ? $data['pager'] : null;
        $this->container['current_user_id'] = isset($data['current_user_id']) ? $data['current_user_id'] : null;
        $this->container['current_account_id'] = isset($data['current_account_id']) ? $data['current_account_id'] : null;
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
     * Gets account_message_list
     * @return \RevampCRM\Models\AccountMessageViewModel[]
     */
    public function getAccountMessageList()
    {
        return $this->container['account_message_list'];
    }

    /**
     * Sets account_message_list
     * @param \RevampCRM\Models\AccountMessageViewModel[] $account_message_list
     * @return $this
     */
    public function setAccountMessageList($account_message_list)
    {
        $this->container['account_message_list'] = $account_message_list;

        return $this;
    }

    /**
     * Gets pager
     * @return \RevampCRM\Models\ReminderPagedViewModel
     */
    public function getPager()
    {
        return $this->container['pager'];
    }

    /**
     * Sets pager
     * @param \RevampCRM\Models\ReminderPagedViewModel $pager
     * @return $this
     */
    public function setPager($pager)
    {
        $this->container['pager'] = $pager;

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
     * Gets current_account_id
     * @return string
     */
    public function getCurrentAccountId()
    {
        return $this->container['current_account_id'];
    }

    /**
     * Sets current_account_id
     * @param string $current_account_id
     * @return $this
     */
    public function setCurrentAccountId($current_account_id)
    {
        $this->container['current_account_id'] = $current_account_id;

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


