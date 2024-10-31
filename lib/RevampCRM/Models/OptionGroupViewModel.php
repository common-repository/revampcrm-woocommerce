<?php
/**
 * OptionGroupViewModel
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
 * OptionGroupViewModel Class Doc Comment
 *
 * @category    Class */
/** 
 * @package     RevampCRM\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class OptionGroupViewModel implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'OptionGroupViewModel';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'option_group_name' => 'string',
        'list' => '\RevampCRM\Models\AccountOption[]',
        'rotten_after' => 'int'
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
        'option_group_name' => 'OptionGroupName',
        'list' => 'List',
        'rotten_after' => 'RottenAfter'
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
        'option_group_name' => 'setOptionGroupName',
        'list' => 'setList',
        'rotten_after' => 'setRottenAfter'
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
        'option_group_name' => 'getOptionGroupName',
        'list' => 'getList',
        'rotten_after' => 'getRottenAfter'
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
        $this->container['option_group_name'] = isset($data['option_group_name']) ? $data['option_group_name'] : null;
        $this->container['list'] = isset($data['list']) ? $data['list'] : null;
        $this->container['rotten_after'] = isset($data['rotten_after']) ? $data['rotten_after'] : null;
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
     * Gets option_group_name
     * @return string
     */
    public function getOptionGroupName()
    {
        return $this->container['option_group_name'];
    }

    /**
     * Sets option_group_name
     * @param string $option_group_name
     * @return $this
     */
    public function setOptionGroupName($option_group_name)
    {
        $this->container['option_group_name'] = $option_group_name;

        return $this;
    }

    /**
     * Gets list
     * @return \RevampCRM\Models\AccountOption[]
     */
    public function getList()
    {
        return $this->container['list'];
    }

    /**
     * Sets list
     * @param \RevampCRM\Models\AccountOption[] $list
     * @return $this
     */
    public function setList($list)
    {
        $this->container['list'] = $list;

        return $this;
    }

    /**
     * Gets rotten_after
     * @return int
     */
    public function getRottenAfter()
    {
        return $this->container['rotten_after'];
    }

    /**
     * Sets rotten_after
     * @param int $rotten_after
     * @return $this
     */
    public function setRottenAfter($rotten_after)
    {
        $this->container['rotten_after'] = $rotten_after;

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


