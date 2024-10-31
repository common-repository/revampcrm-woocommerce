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
            'nonorders'=>0,
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
                    if($post instanceof WP_Post){
                        $response->items[] = $this->transform($post);
                    }else if($post instanceof WC_Order){
                        // Handle querying for order directly
                        $response->items[] = $this->transformOrder($post);
                    }else{
                        $response->nonOrders++;
                        continue;
                    }
                    $response->valid++;
                } catch (\Exception $e) {
                    revampcrm_debug("revampcrm.transformorderswc3", " Post " . $post->ID . "  -> " . $e->getMessage(), $e->getTraceAsString());
                }
            }
        }
        // revampcrm_debug("transformed.order.obj","Data for page: ".$page,$response->items);
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
        return $this->transformOrder($woo);
    }
    public function transformOrder(WC_Order $woo)
    {
        $order = new \RevampCRM\Models\ECommerceOrderApiModel();

        $order = new \RevampCRM\Models\ECommerceOrderApiModel();

        $order->setProviderOrderId($woo->get_order_number());
        $order->setSourceName("www");
        $order->setProviderOrderReference($woo->get_order_number());
        $order->setProviderStoreId(revampcrm_get_store_id());
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

        if ($status == "cancelled") {
            $order->setCancelReason("other");
        }
        // map the fulfillment and financial statuses based on the map above.
        $fulfillment_status = array_key_exists($status, $statuses) ? $statuses[$status]->fulfillment : null;
        $financial_status = array_key_exists($status, $statuses) ? $statuses[$status]->financial : $status;

        // set the fulfillment_status
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
        $order->setShippingPrice($woo->get_shipping_total());

        // set the order discount
        $order->setTotalDiscount($woo->get_total_discount());

        // Set refund
        $order->setTotalRefund($woo->get_total_refunded());

        // Set refunded quantity
        $order->setTotalRefundedQuantity(abs($woo->get_total_qty_refunded()));

        // set the customer
        $order->setCustomer($this->buildCustomerFromOrder($woo));
        $order->setOrderNote($woo->get_customer_note());
        $order->setSubTotalPrice($woo->get_subtotal());
        $order->setTags("status-" . $status);
        // // apply the addresses to the order
        // $order->setShippingAddress($this->transformShippingAddress($woo));
        // $order->setBillingAddress($this->transformBillingAddress($woo));

        // loop through all the order items
        $num_of_lines = 0;
        $num_of_items = 0;
        $items_to_add = [];
        foreach ($woo->get_items() as $key => $order_detail) {
            /** @var WC_Order_Item_Product $order_detail */

            // add it into the order item container.
            $item = $this->transformLineItem($key, $order_detail);

            // if we don't have a product post with this id, we need to add a deleted product to the Revamp CRM side
            if (!($product = $order_detail->get_product()) || 'trash' === $product->get_status()) {

                $pid = $order_detail->get_product_id();

                // check if it exists, otherwise create a new one.
                if (($deleted_product = RevampCRM_WooCommerce_Transform_Products::deleted($pid))) {
                    // swap out the old item id and product variant id with the deleted version.
                    $item->setProviderProductId("deleted_{$pid}");
                    $item->setProviderProductVariantId("deleted_{$pid}");

                    // add the item and continue on the loop.
                    // $order->addItem($item);
                    $num_of_items += $item->getQuantity();
                    $num_of_lines += 1;
                    $items_to_add[] = $item;
                    continue;
                }

                revampcrm_log('order.items.error', "Order #{$woo->get_id()} :: Product {$pid} does not exist!");
                continue;
            }
            $num_of_items += $item->getQuantity();
            $num_of_lines += 1;
            $items_to_add[] = $item;
        }
        $order->setLineItems($items_to_add);
        $order->setNumOfLines($num_of_lines);
        $order->setNumOfItems($num_of_items);
        //if (($refund = $woo->get_total_refunded()) && $refund > 0){
        // this is where we would be altering the submission to tell us about the refund.
        //}

        // Coupons used in the order LOOP (as they can be multiple)
        foreach ($woo->get_used_coupons() as $coupon_name) {
            $order->setCoupon($coupon_name);
        }

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
            if (!empty($order->get_billing_first_name()) || !empty($order->get_billing_last_name())) {
                $_idValueToUse = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
            } else {
                // Finally fallback to order number
                $_idValueToUse = $order->get_order_number();
            }
        }
        $customer->setExternalProviderId(md5(trim(strtolower($_idValueToUse))));
        $customer->setExternalProviderName('WooCommerce');
        $customer->setOrganization($order->get_billing_company());
        $customer->setEmailValue(trim($order->get_billing_email()));
        $customer->setContactInfoPrimaryEmail(trim($order->get_billing_email()));
        $customer->setContactInfoSecondaryEmail(trim($order->get_billing_email()));
        $customer->setFirstName($order->get_billing_first_name());
        $customer->setLastName($order->get_billing_last_name());
        if (!empty($customer->getFirstName()) && !empty($customer->getLastName())) {
            $customer->setName($order->get_billing_first_name() . " " . $order->get_billing_last_name());
        }
        $customer->setContactSource('WooCommerce');
        $customer->setTags("WooCommerce"); //.get_option('siteurl'));
        $customer->setCreatedOn($order->get_date_created()->setTimezone(new \DateTimeZone('UTC')));
        $customer->setUpdatedOn($order->get_date_modified()->setTimezone(new \DateTimeZone('UTC')));

        // TODO: Get the info from Frontend
        // // we are saving the post meta for subscribers on each order... so if they have subscribed on checkout
        // $subscriber_meta = get_post_meta($order->get_id(), 'revampcrm_woocommerce_is_subscribed', true);
        // $subscribed_on_order = $subscriber_meta === '' ? false : (bool) $subscriber_meta;

        // $customer->setOptInStatus($subscribed_on_order);

        // use the info from the order to compile an address.
        $customer->setContactInfoAddressLine1($order->get_billing_address_1());
        $customer->setContactInfoAddressLine2($order->get_billing_address_2());
        $customer->setContactInfoAddressCity($order->get_billing_city());
        $customer->setContactInfoAddressState($order->get_billing_state());
        $customer->setContactInfoAddressPostalCode($order->get_billing_postcode());
        $customer->setContactInfoAddressCountry($order->get_billing_country());
        $customer->setContactInfoPrimaryPhoneNumber($order->get_billing_phone());

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
        // revampcrm_debug("transformed.customer.obj",$customer);
        return $customer;
    }

    /**
     * @param $key
     * @param WC_Order_Item_Product $order_detail
     * @return RevampCRM_WooCommerce_LineItem
     */
    protected function transformLineItem($key, $order_detail)
    {
        // fire up a new MC line item
        $item = new \RevampCRM\Models\ECommerceCheckOutLineApiModel();

        $item->setPrice($order_detail->get_total());
        $item->setProviderProductId($order_detail->get_product_id());
        $variation_id = $order_detail->get_variation_id();
        if (empty($variation_id)) $variation_id = $order_detail->get_product_id();
        $item->setProviderProductVariantId($variation_id);
        $item->setQuantity($order_detail->get_quantity());

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
        if(function_exists("wc_get_orders")){
            $filters = array(
                // 'post_type'   => 'shop_order',
                // 'post_status' => array_keys(wc_get_order_statuses()),
                'limit' => $posts,
                'paged' => $page,
                'orderby' => 'ID',
                'order' => 'ASC'
            );
            if (!empty($this->startingFrom) && @is_a($this->startingFrom, 'DateTime')) {
                try {
                    $filters["date_modified"] = '>= '. $this->startingFrom->sub(date_interval_create_from_date_string('1 day'))->format('Y-m-d H:i:s T');
                } catch (\Exception $ex) { }
            }
//            var_dump("IN WC GET ORDERS PART", $filters);
  //          revampcrm_log("IN WC GET ORDERS PART", "Filters ", $filters);
            $orders = wc_get_orders($filters);
    //        revampcrm_log("IN WC GET ORDERS PART", "Return For trial 1 ", $orders);
            if (empty($orders)) {
                sleep(2);

                $orders = wc_get_orders($filters);
      //          revampcrm_log("IN WC GET ORDERS PART", "Return For trial 2 ", $orders);
                if (empty($orders)) {
                    return false;
                }
            }

            return $orders;
        }else{
            // Code below does not work with recent woocomemrce, using High performance in othher tables
            // |__ This users shop_order_plachold, but with regular post status .. and stores info in separate table
            $filters = array(
                'post_type'   => 'shop_order',
                'post_status' => array_keys(wc_get_order_statuses()),
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
        //    revampcrm_debug("WC3-GetOrderPosts-Transform","Gettting the posts based on filters",$filters);
            $orders = get_posts($filters);
          //  revampcrm_debug("WC3-GetOrderPosts-Transform","Results for trial 1",$orders);

            if (empty($orders)) {
                sleep(2);

                $orders = get_posts($filters);
            //    revampcrm_debug("WC3-GetOrderPosts-Transform","Results for Trial 2",$orders);

                if (empty($orders)) {
                    return false;
                }
            }

            return $orders;
        }
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

    /**
     * After the resources have been loaded and pushed
     */
    protected function complete()
    {
        revampcrm_log('sync.orders.completed', 'Done with the order sync.');

        // add a timestamp for the orders sync completion
        $this->setResourceCompleteTime();

        // this is the last thing we're doing so it's complete as of now.
        $this->flagStopSync();
    }
}
