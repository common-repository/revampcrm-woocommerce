<?php

/**
 * Created by Vextras. 
 * Modified By RevampCo.
 *
 * Name: Ryan Hungate
 * Email: ryan@mailchimp.com
 * Date: 7/13/16
 * Time: 8:29 AM
 * 
 * Name: Waleed Meligy
 * Email: wmeligy@revampco.com
 * Date: 10/15/17
 * Time: 8:29 AM
 * 
 */
class RevampCRM_WooCommerce_Transform_Orders
{
    protected $use_user_address = false;
    protected $startingFrom = null;
    function __construct($startingFrom = null)
    {
        $this->startingFrom = $startingFrom;
    }


    /**
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
    public function compile($page = 1, $limit = 5, $startSyncTime = null)
    {
        $this->startingFrom = $startSyncTime;
        $response = (object) array(
            'endpoint' => 'orders',
            'page' => $page ? $page : 1,
            'limit' => (int) $limit,
            'count' => 0,
            'valid' => 0,
            'drafts' => 0,
            'stuffed' => false,
            'items' => array(),
        );

        if ((($orders = $this->getOrderPosts($page, $limit)) && !empty($orders))) {
            foreach ($orders as $post) {
                $response->count++;
                if ($post->post_status === 'auto-draft') {
                    $response->drafts++;
                    continue;
                }

                try {

                    $response->items[] = $this->transform($post);
                    $response->valid++;
                } catch (\Exception $e) {
                    revampcrm_debug("revampcrm.transformorder", " Post " . $post->ID . "  -> " . $e->getMessage(), $e->getTraceAsString());
                }
            }
        }

        $response->stuffed = ($response->count > 0 && (int) $response->count === (int) $limit) ? true : false;

        return $response;
    }

    /**
     * @param WP_Post $post
     * @return RevampCRM\Models\ECommerceOrderApiModel
     */
    public function transform(WP_Post $post)
    {
        $woo = new WC_Order($post);

        $order = new \RevampCRM\Models\ECommerceOrderApiModel();

        $order->setProviderOrderId($woo->get_order_number());
        $order->setProviderOrderReference($woo->get_order_number());
        $order->setSourceName("www");
        $order->setProviderStoreId(revamcrm_get_store_id());
        $order->setEcommProviderName('WooCommerce');
        // if we have a campaign id let's set it now.
        if (!empty($this->campaign_id)) {
            $campaign_model = new \RevampCRM\Models\ECommerceOrderCampaignApiModel();
            $campaign_model->setCampaign($this->campaign_id);
            $order->setOrderCampaign($campaign_model);
        }
        /*
        'account_id' => 'setAccountId',
        'contact_id' => 'setContactId',
        'user_id' => 'setUserId',
        'created_on' => '',
        'updated_on' => '',
        'ecomm_provider_name' => '',
        'provider_store_id' => '',
        'provider_order_id' => '',
        'provider_order_reference' => '',
        'order_note' => '',
        'order_custom_data' => 'setOrderCustomData',
        'sub_total_price' => '',
        'total_tax' => '',
        'total_discount' => '',
        'total_price' => '',
        'num_of_lines' => '',
        'num_of_items' => '',
        'financial_status' => '',
        'shipping_status' => '',
        'currency' => '',
        'line_items' => '',
        'order_campaign' => '',
        'customer' => ''
        */
        $order->setCreatedOn($woo->get_date_created()->setTimezone(new \DateTimeZone('UTC')));
        $order->setUpdatedOn($woo->get_date_modified()->setTimezone(new \DateTimeZone('UTC')));
        $order->setCurrency($woo->get_currency());

        // grab the current statuses - this will end up being custom at some point.
        $statuses = $this->getOrderStatuses();

        // grab the order status
        $status = $woo->get_status();

        // map the fulfillment and financial statuses based on the map above.
        $fulfillment_status = array_key_exists($status, $statuses) ? $statuses[$status]->fulfillment : null;
        $financial_status = array_key_exists($status, $statuses) ? $statuses[$status]->financial : $status;

        // Set cancel reason
        if ($status == "cancelled") {
            $order->setCancelReason("other");
        }
        // set the shippinh_status
        $order->setShippingStatus($fulfillment_status);

        // set the financial status
        $order->setFinancialStatus($financial_status);

        // // if the status is processing, we need to send this one first, then send a 'paid' status right after.
        // if ($status === 'processing') {
        //     $order->confirmAndPay(true);
        // }

        // // only set this if the order is cancelled.
        // if ($status === 'cancelled') {
        //     $order->setCancelledAt($woo->get_date_modified()->setTimezone(new \DateTimeZone('UTC')));
        // }

        // set the total
        $order->setTotalPrice($woo->get_total());

        // if we have any tax
        $order->setTotalTax($woo->get_total_tax());

        // if we have shipping.
        $order->setShippingPrice($woo->get_total_shipping());

        // set the order discount
        $order->setTotalDiscount($woo->get_total_discount());

        // Set refund
        $order->setTotalRefund($woo->get_total_refunded());

        // Set refunded quantity
        $order->setTotalRefundedQuantity($woo->get_total_qty_refunded());

        // set the customer
        $order->setCustomer($this->buildCustomerFromOrder($woo));

        $order->setTags("status-" . $status);
        $order->setOrderNote($woo->get_customer_note());
        $order->setSubTotalPrice($woo->get_subtotal());

        // // apply the addresses to the order
        // $addresses = $this->getOrderAddresses($woo);
        // $order->setShippingAddress($addresses->shipping);
        // $order->setBillingAddress($addresses->billing);

        // loop through all the order items
        $num_of_lines = 0;
        $num_of_items = 0;
        $items_to_add = [];
        foreach ($woo->get_items() as $key => $order_detail) {

            // add it into the order item container.
            $item = $this->buildLineItem($key, $order_detail);

            // if we don't have a product post with this id, we need to add a deleted product to the Revamp CRM side
            if (!($product_post = get_post($item->getProductId()))) {

                // check if it exists, otherwise create a new one.
                if (($deleted_product = RevampCRM_WooCommerce_Transform_Products::deleted($item->getProductId()))) {

                    $deleted_product_id = "deleted_{$item->getProductId()}";

                    // swap out the old item id and product variant id with the deleted version.
                    $item->setProviderProductId($deleted_product_id);
                    $item->setProviderProductVariantId($deleted_product_id);

                    // add the item and continue on the loop.
                    // $order->addItem($item);
                    $num_of_lines += 1;
                    $num_of_items += $item->getQuantity();
                    $items_to_add[] = $item;
                    continue;
                }

                revampcrm_log('order.items.error', "Order #{$woo->get_order_number()} :: Product {$item->getProductId()} does not exist!");
                continue;
            }
            $num_of_items += $item->getQuantity();
            $num_of_lines += 1;

            $items_to_add[] = $item;
        }
        $order->setLineItems($items_to_add);
        $order->setNumOfLines($num_of_lines);
        $order->setNumOfItems($num_of_items);

        // Coupons used in the order LOOP (as they can be multiple)
        foreach ($woo->get_used_coupons() as $coupon_name) {
            $order->setCoupon($coupon_name);
        }

        //if (($refund = $woo->get_total_refunded()) && $refund > 0){
        // this is where we would be altering the submission to tell us about the refund.
        //}

        return $order;
    }

    /**
     * @param WC_Order $order
     * @return RevampCRM_WooCommerce_Customer
     */
    public function buildCustomerFromOrder(WC_Order $order)
    {
        $customer = new \RevampCRM\Models\ContactCSVApiModel();

        // Use email as id
        $_idValueToUse = $order->get_billing_email();
        if (empty($_idValueToUse)) {
            // if no email, fallback to customer name
            if (!empty($order->billing_first_name) || !empty($order->billing_last_name)) {
                $_idValueToUse = $order->billing_first_name . " " . $order->billing_last_name;
            } else {
                // Finally fallback to order number
                $_idValueToUse = $order->get_order_number();
            }
        }
        $customer->setExternalProviderId(md5(trim(strtolower($_idValueToUse))));
        $customer->setExternalProviderName('WooCommerce');
        $customer->setOrganization($order->billing_company);
        $customer->setEmailValue(trim($order->billing_email));
        $customer->setContactInfoPrimaryEmail(trim($order->billing_email));
        $customer->setContactInfoSecondaryEmail(trim($order->shipping_email));
        $customer->setContactInfoWebsite(trim($order->billing_));
        $customer->setFirstName($order->billing_first_name);
        $customer->setLastName($order->billing_last_name);
        if (!empty($customer->getFirstName()) && !empty($customer->getLastName())) {
            $customer->setName($order->billing_first_name . " " . $order->billing_last_name);
        }
        $customer->setContactSource('WooCommerce');
        $customer->setTags("WooCommerce," . get_option('siteurl'));

        $customer->setCreatedOn($order->get_date_created()->setTimezone(new \DateTimeZone('UTC')));
        $customer->setUpdatedOn($order->get_date_modified()->setTimezone(new \DateTimeZone('UTC')));

        // TODO: Get the info from Frontend
        // // we are saving the post meta for subscribers on each order... so if they have subscribed on checkout
        // $subscriber_meta = get_post_meta($order->get_id(), 'revampcrm_woocommerce_is_subscribed', true);
        // $subscribed_on_order = $subscriber_meta === '' ? false : (bool) $subscriber_meta;

        // $customer->setOptInStatus($subscribed_on_order);

        // use the info from the order to compile an address.
        $customer->setContactInfoAddressLine1($order->billing_address_1);
        $customer->setContactInfoAddressLine2($order->billing_address_2);
        $customer->setContactInfoAddressCity($order->billing_city);
        $customer->setContactInfoAddressState($order->billing_state);
        $customer->setContactInfoAddressPostalCode($order->billing_postcode);
        $customer->setContactInfoAddressCountry($order->billing_country);
        $customer->setContactInfoPrimaryPhoneNumber($order->billing_phone);

        if (($user = get_userdata($order->customer_user))) {
            /**
             * IF we wanted to use the user data instead we would do it here.
             * but we discussed using the billing address instead.
             */
            $customer->setContactInfoWebsite($user->user_url);
            if ($this->use_user_address) {
                $customer->setExternalProviderId($user->ID);
                // $customer->setEmailAddress($user->user_email);

                // $customer->setFirstName($user->first_name);
                // $customer->setLastName($user->last_name);

                // if (($address = $this->getUserAddress($user->ID))) {
                //     if (count($address->toArray()) > 3) {
                //         $customer->setAddress($address);
                //     }
                // }
            }
        }

        return $customer;
    }

    /**
     * @param $key
     * @param $order_detail
     * @return RevampCRM_WooCommerce_LineItem
     */
    protected function buildLineItem($key, $order_detail)
    {
        // fire up a new MC line item
        $item = new \RevampCRM\Models\ECommerceCheckOutLineApiModel();

        /*
                'quantity' => 'setQuantity',
        'price' => '',
        'provider_product_variant_id' => 'setProviderProductVariantId',
        'provider_product_id' => 'setProviderProductId'
        */

        if (isset($order_detail['item_meta']) && is_array($order_detail['item_meta'])) {

            foreach ($order_detail['item_meta'] as $meta_key => $meta_data_array) {

                if (!isset($meta_data_array[0])) {
                    continue;
                }

                switch ($meta_key) {

                    case '_line_subtotal':
                        $item->setPrice($meta_data_array[0]);
                        break;

                    case '_product_id':
                        $item->setProviderProductId($meta_data_array[0]);
                        break;

                    case '_variation_id':
                        $item->setProviderProductVariantId($meta_data_array[0]);
                        break;

                    case '_qty':
                        $item->setQuantity($meta_data_array[0]);
                        break;
                }
            }

            if ($item->getProviderProductVariantId() <= 0) {
                $item->setProviderProductVariantId($item->getProductId());
            }
        } elseif (isset($order_detail['item_meta_array']) && is_array($order_detail['item_meta_array'])) {

            /// Some users have the newer version of the item meta.

            foreach ($order_detail['item_meta_array'] as $meta_id => $object) {

                if (!isset($object->key)) {
                    continue;
                }

                switch ($object->key) {

                    case '_line_subtotal':
                        $item->setPrice($object->value);
                        break;

                    case '_product_id':
                        $item->setProviderProductId($object->value);
                        break;

                    case '_variation_id':
                        $item->setProviderProductVariantId($object->value);
                        break;

                    case '_qty':
                        $item->setQuantity($object->value);
                        break;
                }
            }

            if ($item->getProviderProductVariantId() <= 0) {
                $item->setProviderProductVariantId($item->getProductId());
            }
        }

        // WM: We don't use UnitPrice, and use Price as the total per line
        // if ($item->getQuantity() > 1) {
        //     $current_price = $item->getPrice();
        //     $price = ($current_price/$item->getQuantity());
        //     $item->setPrice($price);
        // }

        return $item;
    }

    /**
     * @param int $page
     * @param int $posts
     * @return array|bool
     */
    public function getOrderPosts($page = 1, $posts = 5)
    {
        $filters = array(
            'post_type' => 'shop_order',
            //'post_status' => 'publish',
            'posts_per_page' => $posts,
            'paged' => $page,
            'orderby' => 'ID',
            'order' => 'ASC'
        );
        if (!empty($this->startingFrom) && @is_a($this->startingFrom, 'DateTime')) {
            try {
                $filters["date_query"] = array(
                    array(
                        'after'         => $this->startingFrom->sub(date_interval_create_from_date_string('1 day'))->format('Y-m-d H:i:s T'),
                        'inclusive'     => true,
                    ),
                );
            } catch (\Exception $ex) { }
        }
        $orders = get_posts($filters);

        if (empty($orders)) {

            sleep(2);

            $orders = get_posts($filters);

            if (empty($orders)) {
                return false;
            }
        }

        return $orders;
    }


    /**
     * @param $user_id
     * @param string $type
     * @return RevampCRM_WooCommerce_Address
     */
    public function getUserAddress($user_id, $type = 'billing')
    {
        $address = new RevampCRM_WooCommerce_Address();

        // pull all the meta for this user.
        $meta = get_user_meta($user_id);

        // loop through all the possible address properties, and if we have on on the user, set the property
        // because it's more up to date.
        $address_props = array(
            $type . '_address_1' => 'setContactInfoAddressLine1',
            $type . '_address_2' => 'setContactInfoAddressLine2',
            $type . '_city' => 'setContactInfoCity',
            $type . '_state' => 'setContactInfoState',
            $type . '_postcode' => 'setContactInfoPostalCode',
            $type . '_country' => 'setContactInfoCountry',
            $type . '_phone' => 'setContactInfoPrimaryPhoneNumber',
        );

        // loop through all the address properties and set the values if we have one.
        foreach ($address_props as $address_key => $address_call) {
            if (isset($meta[$address_key]) && !empty($meta[$address_key]) && isset($meta[$address_key][0])) {
                $address->$address_call($meta[$address_key][0]);
            }
        }

        return $address;
    }

    /**
     * @return array
     */
    public function getOrderStatuses()
    {
        return array(
            // Order received (unpaid)
            'pending'       => (object) array(
                'financial' => 'pending',
                'fulfillment' => null
            ),
            // Payment received and stock has been reduced – the order is awaiting fulfillment.
            // All product orders require processing, except those for digital downloads
            'processing'    => (object) array(
                'financial' => 'paid',
                'fulfillment' => null
            ),
            // Awaiting payment – stock is reduced, but you need to confirm payment
            'on-hold'       => (object) array(
                'financial' => 'pending',
                'fulfillment' => null
            ),
            // Order fulfilled and complete – requires no further action
            'completed'     => (object) array(
                'financial' => 'paid',
                'fulfillment' => 'fulfilled'
            ),
            // Cancelled by an admin or the customer – no further action required
            'cancelled'     => (object) array(
                'financial' => 'voided',
                'fulfillment' => null
            ),
            // Refunded by an admin – no further action required
            'refunded'      => (object) array(
                'financial' => 'refunded',
                'fulfillment' => null
            ),
            // Payment failed or was declined (unpaid). Note that this status may not show immediately and
            // instead show as Pending until verified (i.e., PayPal)
            'failed'        => (object) array(
                'financial' => 'other',
                'fulfillment' => null
            ),
        );
    }
}
