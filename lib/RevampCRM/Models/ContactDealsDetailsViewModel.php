<?php
/**
 * ContactDealsDetailsViewModel
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
 * ContactDealsDetailsViewModel Class Doc Comment
 *
 * @category    Class */
/** 
 * @package     RevampCRM\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class ContactDealsDetailsViewModel implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'ContactDealsDetailsViewModel';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'deals_summary_won_value' => 'double',
        'deals_summary_won_count' => 'int',
        'contact_name' => 'string',
        'contact_id' => 'string',
        'email' => 'string',
        'phone_number' => 'string',
        'img_url' => 'string',
        'last_contacted_on' => '\DateTime',
        'profile_pic_id' => 'string',
        'last_won_deal_id' => 'string',
        'last_won_deal_name' => 'string',
        'deal_closure_date' => '\DateTime',
        'last_won_deal_value' => 'double'
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
        'deals_summary_won_value' => 'DealsSummaryWonValue',
        'deals_summary_won_count' => 'DealsSummaryWonCount',
        'contact_name' => 'ContactName',
        'contact_id' => 'ContactId',
        'email' => 'Email',
        'phone_number' => 'PhoneNumber',
        'img_url' => 'imgUrl',
        'last_contacted_on' => 'LastContactedOn',
        'profile_pic_id' => 'ProfilePicId',
        'last_won_deal_id' => 'LastWonDealId',
        'last_won_deal_name' => 'LastWonDealName',
        'deal_closure_date' => 'DealClosureDate',
        'last_won_deal_value' => 'LastWonDealValue'
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
        'deals_summary_won_value' => 'setDealsSummaryWonValue',
        'deals_summary_won_count' => 'setDealsSummaryWonCount',
        'contact_name' => 'setContactName',
        'contact_id' => 'setContactId',
        'email' => 'setEmail',
        'phone_number' => 'setPhoneNumber',
        'img_url' => 'setImgUrl',
        'last_contacted_on' => 'setLastContactedOn',
        'profile_pic_id' => 'setProfilePicId',
        'last_won_deal_id' => 'setLastWonDealId',
        'last_won_deal_name' => 'setLastWonDealName',
        'deal_closure_date' => 'setDealClosureDate',
        'last_won_deal_value' => 'setLastWonDealValue'
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
        'deals_summary_won_value' => 'getDealsSummaryWonValue',
        'deals_summary_won_count' => 'getDealsSummaryWonCount',
        'contact_name' => 'getContactName',
        'contact_id' => 'getContactId',
        'email' => 'getEmail',
        'phone_number' => 'getPhoneNumber',
        'img_url' => 'getImgUrl',
        'last_contacted_on' => 'getLastContactedOn',
        'profile_pic_id' => 'getProfilePicId',
        'last_won_deal_id' => 'getLastWonDealId',
        'last_won_deal_name' => 'getLastWonDealName',
        'deal_closure_date' => 'getDealClosureDate',
        'last_won_deal_value' => 'getLastWonDealValue'
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
        $this->container['deals_summary_won_value'] = isset($data['deals_summary_won_value']) ? $data['deals_summary_won_value'] : null;
        $this->container['deals_summary_won_count'] = isset($data['deals_summary_won_count']) ? $data['deals_summary_won_count'] : null;
        $this->container['contact_name'] = isset($data['contact_name']) ? $data['contact_name'] : null;
        $this->container['contact_id'] = isset($data['contact_id']) ? $data['contact_id'] : null;
        $this->container['email'] = isset($data['email']) ? $data['email'] : null;
        $this->container['phone_number'] = isset($data['phone_number']) ? $data['phone_number'] : null;
        $this->container['img_url'] = isset($data['img_url']) ? $data['img_url'] : null;
        $this->container['last_contacted_on'] = isset($data['last_contacted_on']) ? $data['last_contacted_on'] : null;
        $this->container['profile_pic_id'] = isset($data['profile_pic_id']) ? $data['profile_pic_id'] : null;
        $this->container['last_won_deal_id'] = isset($data['last_won_deal_id']) ? $data['last_won_deal_id'] : null;
        $this->container['last_won_deal_name'] = isset($data['last_won_deal_name']) ? $data['last_won_deal_name'] : null;
        $this->container['deal_closure_date'] = isset($data['deal_closure_date']) ? $data['deal_closure_date'] : null;
        $this->container['last_won_deal_value'] = isset($data['last_won_deal_value']) ? $data['last_won_deal_value'] : null;
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
     * Gets deals_summary_won_value
     * @return double
     */
    public function getDealsSummaryWonValue()
    {
        return $this->container['deals_summary_won_value'];
    }

    /**
     * Sets deals_summary_won_value
     * @param double $deals_summary_won_value
     * @return $this
     */
    public function setDealsSummaryWonValue($deals_summary_won_value)
    {
        $this->container['deals_summary_won_value'] = $deals_summary_won_value;

        return $this;
    }

    /**
     * Gets deals_summary_won_count
     * @return int
     */
    public function getDealsSummaryWonCount()
    {
        return $this->container['deals_summary_won_count'];
    }

    /**
     * Sets deals_summary_won_count
     * @param int $deals_summary_won_count
     * @return $this
     */
    public function setDealsSummaryWonCount($deals_summary_won_count)
    {
        $this->container['deals_summary_won_count'] = $deals_summary_won_count;

        return $this;
    }

    /**
     * Gets contact_name
     * @return string
     */
    public function getContactName()
    {
        return $this->container['contact_name'];
    }

    /**
     * Sets contact_name
     * @param string $contact_name
     * @return $this
     */
    public function setContactName($contact_name)
    {
        $this->container['contact_name'] = $contact_name;

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
     * Gets email
     * @return string
     */
    public function getEmail()
    {
        return $this->container['email'];
    }

    /**
     * Sets email
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->container['email'] = $email;

        return $this;
    }

    /**
     * Gets phone_number
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->container['phone_number'];
    }

    /**
     * Sets phone_number
     * @param string $phone_number
     * @return $this
     */
    public function setPhoneNumber($phone_number)
    {
        $this->container['phone_number'] = $phone_number;

        return $this;
    }

    /**
     * Gets img_url
     * @return string
     */
    public function getImgUrl()
    {
        return $this->container['img_url'];
    }

    /**
     * Sets img_url
     * @param string $img_url
     * @return $this
     */
    public function setImgUrl($img_url)
    {
        $this->container['img_url'] = $img_url;

        return $this;
    }

    /**
     * Gets last_contacted_on
     * @return \DateTime
     */
    public function getLastContactedOn()
    {
        return $this->container['last_contacted_on'];
    }

    /**
     * Sets last_contacted_on
     * @param \DateTime $last_contacted_on
     * @return $this
     */
    public function setLastContactedOn($last_contacted_on)
    {
        $this->container['last_contacted_on'] = $last_contacted_on;

        return $this;
    }

    /**
     * Gets profile_pic_id
     * @return string
     */
    public function getProfilePicId()
    {
        return $this->container['profile_pic_id'];
    }

    /**
     * Sets profile_pic_id
     * @param string $profile_pic_id
     * @return $this
     */
    public function setProfilePicId($profile_pic_id)
    {
        $this->container['profile_pic_id'] = $profile_pic_id;

        return $this;
    }

    /**
     * Gets last_won_deal_id
     * @return string
     */
    public function getLastWonDealId()
    {
        return $this->container['last_won_deal_id'];
    }

    /**
     * Sets last_won_deal_id
     * @param string $last_won_deal_id
     * @return $this
     */
    public function setLastWonDealId($last_won_deal_id)
    {
        $this->container['last_won_deal_id'] = $last_won_deal_id;

        return $this;
    }

    /**
     * Gets last_won_deal_name
     * @return string
     */
    public function getLastWonDealName()
    {
        return $this->container['last_won_deal_name'];
    }

    /**
     * Sets last_won_deal_name
     * @param string $last_won_deal_name
     * @return $this
     */
    public function setLastWonDealName($last_won_deal_name)
    {
        $this->container['last_won_deal_name'] = $last_won_deal_name;

        return $this;
    }

    /**
     * Gets deal_closure_date
     * @return \DateTime
     */
    public function getDealClosureDate()
    {
        return $this->container['deal_closure_date'];
    }

    /**
     * Sets deal_closure_date
     * @param \DateTime $deal_closure_date
     * @return $this
     */
    public function setDealClosureDate($deal_closure_date)
    {
        $this->container['deal_closure_date'] = $deal_closure_date;

        return $this;
    }

    /**
     * Gets last_won_deal_value
     * @return double
     */
    public function getLastWonDealValue()
    {
        return $this->container['last_won_deal_value'];
    }

    /**
     * Sets last_won_deal_value
     * @param double $last_won_deal_value
     * @return $this
     */
    public function setLastWonDealValue($last_won_deal_value)
    {
        $this->container['last_won_deal_value'] = $last_won_deal_value;

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


