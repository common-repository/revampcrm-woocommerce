<?php
/**
 * DealSearchParametersViewModel
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
 * DealSearchParametersViewModel Class Doc Comment
 *
 * @category    Class */
/** 
 * @package     RevampCRM\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class DealSearchParametersViewModel implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'DealSearchParametersViewModel';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'type' => 'string',
        'current_view' => 'string',
        'user_id' => 'string',
        'status' => 'string',
        'created_on_start' => '\DateTime',
        'created_on_end' => '\DateTime',
        'expected_closure_start' => '\DateTime',
        'expected_closure_end' => '\DateTime',
        'closed_on_start' => '\DateTime',
        'closed_on_end' => '\DateTime',
        'time_line_start_date' => 'string',
        'contact_id' => 'string',
        'product_id' => 'string',
        'sales_region_id' => 'string',
        'deal_source_id' => 'string',
        'sort_by' => 'string',
        'sort_direction' => 'string'
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
        'type' => 'Type',
        'current_view' => 'CurrentView',
        'user_id' => 'UserId',
        'status' => 'Status',
        'created_on_start' => 'CreatedOnStart',
        'created_on_end' => 'CreatedOnEnd',
        'expected_closure_start' => 'ExpectedClosureStart',
        'expected_closure_end' => 'ExpectedClosureEnd',
        'closed_on_start' => 'ClosedOnStart',
        'closed_on_end' => 'ClosedOnEnd',
        'time_line_start_date' => 'TimeLineStartDate',
        'contact_id' => 'ContactId',
        'product_id' => 'ProductId',
        'sales_region_id' => 'SalesRegionId',
        'deal_source_id' => 'DealSourceId',
        'sort_by' => 'SortBy',
        'sort_direction' => 'SortDirection'
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
        'type' => 'setType',
        'current_view' => 'setCurrentView',
        'user_id' => 'setUserId',
        'status' => 'setStatus',
        'created_on_start' => 'setCreatedOnStart',
        'created_on_end' => 'setCreatedOnEnd',
        'expected_closure_start' => 'setExpectedClosureStart',
        'expected_closure_end' => 'setExpectedClosureEnd',
        'closed_on_start' => 'setClosedOnStart',
        'closed_on_end' => 'setClosedOnEnd',
        'time_line_start_date' => 'setTimeLineStartDate',
        'contact_id' => 'setContactId',
        'product_id' => 'setProductId',
        'sales_region_id' => 'setSalesRegionId',
        'deal_source_id' => 'setDealSourceId',
        'sort_by' => 'setSortBy',
        'sort_direction' => 'setSortDirection'
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
        'type' => 'getType',
        'current_view' => 'getCurrentView',
        'user_id' => 'getUserId',
        'status' => 'getStatus',
        'created_on_start' => 'getCreatedOnStart',
        'created_on_end' => 'getCreatedOnEnd',
        'expected_closure_start' => 'getExpectedClosureStart',
        'expected_closure_end' => 'getExpectedClosureEnd',
        'closed_on_start' => 'getClosedOnStart',
        'closed_on_end' => 'getClosedOnEnd',
        'time_line_start_date' => 'getTimeLineStartDate',
        'contact_id' => 'getContactId',
        'product_id' => 'getProductId',
        'sales_region_id' => 'getSalesRegionId',
        'deal_source_id' => 'getDealSourceId',
        'sort_by' => 'getSortBy',
        'sort_direction' => 'getSortDirection'
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
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['current_view'] = isset($data['current_view']) ? $data['current_view'] : null;
        $this->container['user_id'] = isset($data['user_id']) ? $data['user_id'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['created_on_start'] = isset($data['created_on_start']) ? $data['created_on_start'] : null;
        $this->container['created_on_end'] = isset($data['created_on_end']) ? $data['created_on_end'] : null;
        $this->container['expected_closure_start'] = isset($data['expected_closure_start']) ? $data['expected_closure_start'] : null;
        $this->container['expected_closure_end'] = isset($data['expected_closure_end']) ? $data['expected_closure_end'] : null;
        $this->container['closed_on_start'] = isset($data['closed_on_start']) ? $data['closed_on_start'] : null;
        $this->container['closed_on_end'] = isset($data['closed_on_end']) ? $data['closed_on_end'] : null;
        $this->container['time_line_start_date'] = isset($data['time_line_start_date']) ? $data['time_line_start_date'] : null;
        $this->container['contact_id'] = isset($data['contact_id']) ? $data['contact_id'] : null;
        $this->container['product_id'] = isset($data['product_id']) ? $data['product_id'] : null;
        $this->container['sales_region_id'] = isset($data['sales_region_id']) ? $data['sales_region_id'] : null;
        $this->container['deal_source_id'] = isset($data['deal_source_id']) ? $data['deal_source_id'] : null;
        $this->container['sort_by'] = isset($data['sort_by']) ? $data['sort_by'] : null;
        $this->container['sort_direction'] = isset($data['sort_direction']) ? $data['sort_direction'] : null;
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
     * Gets current_view
     * @return string
     */
    public function getCurrentView()
    {
        return $this->container['current_view'];
    }

    /**
     * Sets current_view
     * @param string $current_view
     * @return $this
     */
    public function setCurrentView($current_view)
    {
        $this->container['current_view'] = $current_view;

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
     * Gets status
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets created_on_start
     * @return \DateTime
     */
    public function getCreatedOnStart()
    {
        return $this->container['created_on_start'];
    }

    /**
     * Sets created_on_start
     * @param \DateTime $created_on_start
     * @return $this
     */
    public function setCreatedOnStart($created_on_start)
    {
        $this->container['created_on_start'] = $created_on_start;

        return $this;
    }

    /**
     * Gets created_on_end
     * @return \DateTime
     */
    public function getCreatedOnEnd()
    {
        return $this->container['created_on_end'];
    }

    /**
     * Sets created_on_end
     * @param \DateTime $created_on_end
     * @return $this
     */
    public function setCreatedOnEnd($created_on_end)
    {
        $this->container['created_on_end'] = $created_on_end;

        return $this;
    }

    /**
     * Gets expected_closure_start
     * @return \DateTime
     */
    public function getExpectedClosureStart()
    {
        return $this->container['expected_closure_start'];
    }

    /**
     * Sets expected_closure_start
     * @param \DateTime $expected_closure_start
     * @return $this
     */
    public function setExpectedClosureStart($expected_closure_start)
    {
        $this->container['expected_closure_start'] = $expected_closure_start;

        return $this;
    }

    /**
     * Gets expected_closure_end
     * @return \DateTime
     */
    public function getExpectedClosureEnd()
    {
        return $this->container['expected_closure_end'];
    }

    /**
     * Sets expected_closure_end
     * @param \DateTime $expected_closure_end
     * @return $this
     */
    public function setExpectedClosureEnd($expected_closure_end)
    {
        $this->container['expected_closure_end'] = $expected_closure_end;

        return $this;
    }

    /**
     * Gets closed_on_start
     * @return \DateTime
     */
    public function getClosedOnStart()
    {
        return $this->container['closed_on_start'];
    }

    /**
     * Sets closed_on_start
     * @param \DateTime $closed_on_start
     * @return $this
     */
    public function setClosedOnStart($closed_on_start)
    {
        $this->container['closed_on_start'] = $closed_on_start;

        return $this;
    }

    /**
     * Gets closed_on_end
     * @return \DateTime
     */
    public function getClosedOnEnd()
    {
        return $this->container['closed_on_end'];
    }

    /**
     * Sets closed_on_end
     * @param \DateTime $closed_on_end
     * @return $this
     */
    public function setClosedOnEnd($closed_on_end)
    {
        $this->container['closed_on_end'] = $closed_on_end;

        return $this;
    }

    /**
     * Gets time_line_start_date
     * @return string
     */
    public function getTimeLineStartDate()
    {
        return $this->container['time_line_start_date'];
    }

    /**
     * Sets time_line_start_date
     * @param string $time_line_start_date
     * @return $this
     */
    public function setTimeLineStartDate($time_line_start_date)
    {
        $this->container['time_line_start_date'] = $time_line_start_date;

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
     * Gets product_id
     * @return string
     */
    public function getProductId()
    {
        return $this->container['product_id'];
    }

    /**
     * Sets product_id
     * @param string $product_id
     * @return $this
     */
    public function setProductId($product_id)
    {
        $this->container['product_id'] = $product_id;

        return $this;
    }

    /**
     * Gets sales_region_id
     * @return string
     */
    public function getSalesRegionId()
    {
        return $this->container['sales_region_id'];
    }

    /**
     * Sets sales_region_id
     * @param string $sales_region_id
     * @return $this
     */
    public function setSalesRegionId($sales_region_id)
    {
        $this->container['sales_region_id'] = $sales_region_id;

        return $this;
    }

    /**
     * Gets deal_source_id
     * @return string
     */
    public function getDealSourceId()
    {
        return $this->container['deal_source_id'];
    }

    /**
     * Sets deal_source_id
     * @param string $deal_source_id
     * @return $this
     */
    public function setDealSourceId($deal_source_id)
    {
        $this->container['deal_source_id'] = $deal_source_id;

        return $this;
    }

    /**
     * Gets sort_by
     * @return string
     */
    public function getSortBy()
    {
        return $this->container['sort_by'];
    }

    /**
     * Sets sort_by
     * @param string $sort_by
     * @return $this
     */
    public function setSortBy($sort_by)
    {
        $this->container['sort_by'] = $sort_by;

        return $this;
    }

    /**
     * Gets sort_direction
     * @return string
     */
    public function getSortDirection()
    {
        return $this->container['sort_direction'];
    }

    /**
     * Sets sort_direction
     * @param string $sort_direction
     * @return $this
     */
    public function setSortDirection($sort_direction)
    {
        $this->container['sort_direction'] = $sort_direction;

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


