<?php
/**
 * Note
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
 * Note Class Doc Comment
 *
 * @category    Class */
/** 
 * @package     RevampCRM\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Note implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'Note';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        '_id' => 'string',
        'account_id' => 'string',
        'deal_id' => 'string',
        'user_id' => 'string',
        'contact_id' => 'string',
        'user_name' => 'string',
        'created_on' => '\DateTime',
        'updated_on' => '\DateTime',
        'type' => 'string',
        'automation_step_type' => 'string',
        'attachment_summary' => '\RevampCRM\Models\AttachmentSummary[]',
        'data' => '\RevampCRM\Models\BsonElement[]',
        'text_meta_score' => 'double'
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
        '_id' => '_id',
        'account_id' => 'AccountId',
        'deal_id' => 'DealId',
        'user_id' => 'UserId',
        'contact_id' => 'ContactId',
        'user_name' => 'UserName',
        'created_on' => 'CreatedOn',
        'updated_on' => 'UpdatedOn',
        'type' => 'Type',
        'automation_step_type' => 'AutomationStepType',
        'attachment_summary' => 'AttachmentSummary',
        'data' => 'Data',
        'text_meta_score' => 'TextMetaScore'
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
        '_id' => 'setId',
        'account_id' => 'setAccountId',
        'deal_id' => 'setDealId',
        'user_id' => 'setUserId',
        'contact_id' => 'setContactId',
        'user_name' => 'setUserName',
        'created_on' => 'setCreatedOn',
        'updated_on' => 'setUpdatedOn',
        'type' => 'setType',
        'automation_step_type' => 'setAutomationStepType',
        'attachment_summary' => 'setAttachmentSummary',
        'data' => 'setData',
        'text_meta_score' => 'setTextMetaScore'
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
        '_id' => 'getId',
        'account_id' => 'getAccountId',
        'deal_id' => 'getDealId',
        'user_id' => 'getUserId',
        'contact_id' => 'getContactId',
        'user_name' => 'getUserName',
        'created_on' => 'getCreatedOn',
        'updated_on' => 'getUpdatedOn',
        'type' => 'getType',
        'automation_step_type' => 'getAutomationStepType',
        'attachment_summary' => 'getAttachmentSummary',
        'data' => 'getData',
        'text_meta_score' => 'getTextMetaScore'
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
        $this->container['_id'] = isset($data['_id']) ? $data['_id'] : null;
        $this->container['account_id'] = isset($data['account_id']) ? $data['account_id'] : null;
        $this->container['deal_id'] = isset($data['deal_id']) ? $data['deal_id'] : null;
        $this->container['user_id'] = isset($data['user_id']) ? $data['user_id'] : null;
        $this->container['contact_id'] = isset($data['contact_id']) ? $data['contact_id'] : null;
        $this->container['user_name'] = isset($data['user_name']) ? $data['user_name'] : null;
        $this->container['created_on'] = isset($data['created_on']) ? $data['created_on'] : null;
        $this->container['updated_on'] = isset($data['updated_on']) ? $data['updated_on'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['automation_step_type'] = isset($data['automation_step_type']) ? $data['automation_step_type'] : null;
        $this->container['attachment_summary'] = isset($data['attachment_summary']) ? $data['attachment_summary'] : null;
        $this->container['data'] = isset($data['data']) ? $data['data'] : null;
        $this->container['text_meta_score'] = isset($data['text_meta_score']) ? $data['text_meta_score'] : null;
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
     * Gets _id
     * @return string
     */
    public function getId()
    {
        return $this->container['_id'];
    }

    /**
     * Sets _id
     * @param string $_id
     * @return $this
     */
    public function setId($_id)
    {
        $this->container['_id'] = $_id;

        return $this;
    }

    /**
     * Gets account_id
     * @return string
     */
    public function getAccountId()
    {
        return $this->container['account_id'];
    }

    /**
     * Sets account_id
     * @param string $account_id
     * @return $this
     */
    public function setAccountId($account_id)
    {
        $this->container['account_id'] = $account_id;

        return $this;
    }

    /**
     * Gets deal_id
     * @return string
     */
    public function getDealId()
    {
        return $this->container['deal_id'];
    }

    /**
     * Sets deal_id
     * @param string $deal_id
     * @return $this
     */
    public function setDealId($deal_id)
    {
        $this->container['deal_id'] = $deal_id;

        return $this;
    }

    /**
     * Gets user_id
     * @return string
     */
    public function getUserId()
    {
        return $this->container['user_id'];
    }

    /**
     * Sets user_id
     * @param string $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->container['user_id'] = $user_id;

        return $this;
    }

    /**
     * Gets contact_id
     * @return string
     */
    public function getContactId()
    {
        return $this->container['contact_id'];
    }

    /**
     * Sets contact_id
     * @param string $contact_id
     * @return $this
     */
    public function setContactId($contact_id)
    {
        $this->container['contact_id'] = $contact_id;

        return $this;
    }

    /**
     * Gets user_name
     * @return string
     */
    public function getUserName()
    {
        return $this->container['user_name'];
    }

    /**
     * Sets user_name
     * @param string $user_name
     * @return $this
     */
    public function setUserName($user_name)
    {
        $this->container['user_name'] = $user_name;

        return $this;
    }

    /**
     * Gets created_on
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->container['created_on'];
    }

    /**
     * Sets created_on
     * @param \DateTime $created_on
     * @return $this
     */
    public function setCreatedOn($created_on)
    {
        $this->container['created_on'] = $created_on;

        return $this;
    }

    /**
     * Gets updated_on
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->container['updated_on'];
    }

    /**
     * Sets updated_on
     * @param \DateTime $updated_on
     * @return $this
     */
    public function setUpdatedOn($updated_on)
    {
        $this->container['updated_on'] = $updated_on;

        return $this;
    }

    /**
     * Gets type
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets automation_step_type
     * @return string
     */
    public function getAutomationStepType()
    {
        return $this->container['automation_step_type'];
    }

    /**
     * Sets automation_step_type
     * @param string $automation_step_type
     * @return $this
     */
    public function setAutomationStepType($automation_step_type)
    {
        $this->container['automation_step_type'] = $automation_step_type;

        return $this;
    }

    /**
     * Gets attachment_summary
     * @return \RevampCRM\Models\AttachmentSummary[]
     */
    public function getAttachmentSummary()
    {
        return $this->container['attachment_summary'];
    }

    /**
     * Sets attachment_summary
     * @param \RevampCRM\Models\AttachmentSummary[] $attachment_summary
     * @return $this
     */
    public function setAttachmentSummary($attachment_summary)
    {
        $this->container['attachment_summary'] = $attachment_summary;

        return $this;
    }

    /**
     * Gets data
     * @return \RevampCRM\Models\BsonElement[]
     */
    public function getData()
    {
        return $this->container['data'];
    }

    /**
     * Sets data
     * @param \RevampCRM\Models\BsonElement[] $data
     * @return $this
     */
    public function setData($data)
    {
        $this->container['data'] = $data;

        return $this;
    }

    /**
     * Gets text_meta_score
     * @return double
     */
    public function getTextMetaScore()
    {
        return $this->container['text_meta_score'];
    }

    /**
     * Sets text_meta_score
     * @param double $text_meta_score
     * @return $this
     */
    public function setTextMetaScore($text_meta_score)
    {
        $this->container['text_meta_score'] = $text_meta_score;

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


