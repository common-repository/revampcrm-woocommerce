<?php

use RevampCRM\API;
use RevampCRM\Client;
/**
 * Created by PhpStorm.
 * Modified By RevampCo.
 * 
 * User: kingpin
 * Email: ryan@mailchimp.com
 * Date: 11/4/15
 * Time: 3:35 PM
 * 
 * Name: Waleed Meligy
 * Email: wmeligy@revampco.com
 * Date: 10/15/17
 * Time: 8:29 AM
 * 
 */
class RevampCRM_WooCommerce_RevampCRMApi
{
    protected $version = '1.0';
    protected $api_key = null;
    protected $api_username = null;
    protected $auth_type = 'key';

    /**
     * RevampCRMService constructor.
     * @param mixed $api_username
     * @param null $api_key
     */
    public function __construct($api_username = null,$api_key = null)
    {
        if (!empty($api_username)) {
            $this->api_username =  $api_username;
        }

        if (!empty($api_key)) {
            $this->api_key = $api_key;
        }


    }

    protected function _getClient(){
            $clientConfig = new \RevampCRM\Client\Configuration();
            $clientConfig->setUsername($this->api_username);
            $clientConfig->setPassword($this->api_key);
            $clientConfig->setSSLVerification(false);
            $clientConfig->setHost("https://app.revampcrm.com/");
            return  new \RevampCRM\Client\ApiClient($clientConfig);

    }
    /**
     * @param bool $return_profile
     * @return array|bool
     */
    public function ping($return_profile = false)
    {
        try {
            $accountApi = new \RevampCRM\API\AccountApi($this->_getClient());
            $profile = $accountApi->accountOptionApiGetStages();
            return $return_profile ? $profile : true;
        }catch(\RevampCRM\Client\ApiException $e){
            return false;
        }
         catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }

    // /**
    //  * @param $list_id
    //  * @param $email
    //  * @param bool $subscribed
    //  * @param array $merge_fields
    //  * @param array $list_interests
    //  * @return array|bool
    //  */
    // public function update($list_id, $email, $subscribed = true, $merge_fields = array(), $list_interests = array())
    // {
    //     $hash = md5(strtolower($email));

    //     $data = array(
    //         'email_address' => $email,
    //         'status' => ($subscribed === null ? 'cleaned' : ($subscribed === true ? 'subscribed' : 'unsubscribed')),
    //         'merge_fields' => $merge_fields,
    //         'interests' => $list_interests,
    //     );

    //     if (empty($data['merge_fields'])) {
    //         unset($data['merge_fields']);
    //     }


    //     if (empty($data['interests'])) {
    //         unset($data['interests']);
    //     }

    //     return $this->patch("lists/$list_id/members/$hash", $data);
    // }

    // /**
    //  * @param $list_id
    //  * @param $email
    //  * @param bool $subscribed
    //  * @param array $merge_fields
    //  * @param array $list_interests
    //  * @return array|bool
    //  */
    // public function updateOrCreate($list_id, $email, $subscribed = true, $merge_fields = array(), $list_interests = array())
    // {
    //     $hash = md5(strtolower($email));

    //     $data = array(
    //         'email_address' => $email,
    //         'status' => ($subscribed === null ? 'cleaned' : ($subscribed === true ? 'subscribed' : 'unsubscribed')),
    //         'status_if_new' => ($subscribed === true ? 'subscribed' : 'pending'),
    //         'merge_fields' => $merge_fields,
    //         'interests' => $list_interests,
    //     );

    //     if (empty($data['merge_fields'])) {
    //         unset($data['merge_fields']);
    //     }

    //     if (empty($data['interests'])) {
    //         unset($data['interests']);
    //     }

    //     return $this->put("lists/$list_id/members/$hash", $data);
    // }

     /**
     * @param $store_id
     * @param int $page
     * @param int $count
     * @return array|bool
     */
    public function ordersCount($store_id)
    {
        $ProductsApi = new \RevampCRM\API\EcommerceOrdersApi($this->_getClient());


        return $ProductsApi->ecommerceOrderCountEcommerceOrders("WooCommerce",$store_id);
    }

    /**
     * @param $store_id
     * @return RevampCRM_WooCommerce_Store|bool
     */
    public function getStore($store_id)
    {
        try {
            $storesApi = new \RevampCRM\API\EcommerceStoresApi($this->_getClient());
            $store=  $storesApi->eCommerceStoreGet("WooCommerce",$store_id);
            if($store == null){
                return false;
            }else{
                return is_array($store)? $store[0]: $store;
            }
        }catch(\RevampCRM\Client\ApiException $e){
            return false;
        }
         catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }



    /**
     * @param $store_id
     * @param $is_syncing
     * @return array|bool|mixed|null|object
     */
    public function flagStoreSync($store_id, $is_syncing)
    {
        try {
            // pull the store to make sure we have one.
            if (!($store = $this->getStore($store_id))) {
                return false;
            }
            $storesApi = new \RevampCRM\API\EcommerceStoresApi($this->_getClient());
            if($is_syncing){
                $ret = $storesApi->eCommerceStoreFlagStartSyncing("WooCommerce",$store_id);     
            }else{
                $ret = $storesApi->eCommerceStoreFlagCompletedSyncing("WooCommerce",$store_id);
            }

            return $ret->getSuccess();

        } catch (\Exception $e) {
            revampcrm_log('flag.store_sync', $e->getMessage(). ' :: in '.$e->getFile().' :: on '.$e->getLine());
        }
        return false;
    }

    /**
     * @param ECommerceStoreApiModel $store
     * @param bool $silent
     * @return bool|ECommerceStoreApiModel
     * @throws Exception
     */
    public function addStore(\RevampCRM\Models\ECommerceStoreApiModel $store, $silent = true)
    {
        try {
            $storesApi = new \RevampCRM\API\EcommerceStoresApi($this->_getClient());
            $response = $storesApi->eCommerceStoreSave($store);
            if($response->GetSuccess() == true){
                return $response->GetEntity();
            }else{
                return false;
            }
            // $this->validateStoreSubmission($store);
            // $data = $this->post("ecommerce/stores", $store->toArray());
            // $store = new RevampCRM_WooCommerce_Store();
            // return $store->fromArray($data);
        } catch (\Exception $e) {
            if (!$silent) throw $e;
            return false;
        }
    }

    /**
     * @param RevampCRM_WooCommerce_Store $store
     * @param bool $silent
     * @return bool|RevampCRM_WooCommerce_Store
     * @throws Exception
     */
    public function updateStore(\RevampCRM\Models\ECommerceStoreApiModel $store, $silent = true)
    {
        try {
            //$this->validateStoreSubmission($store);
             $storesApi = new \RevampCRM\API\EcommerceStoresApi($this->_getClient());
            $response = $storesApi->eCommerceStoreUpdate($store->getStoreId() ,$store);
            if($response->GetSuccess() == true){
                return $response->GetEntity();
            }else{
                return false;
            }
        } catch (\Exception $e) {
            if (!$silent) throw $e;
            return false;
        }
    }

    /**
     * @param $store_id
     * @return bool
     */
    public function deleteStore($store_id)
    {
        try {

            $storesApi = new \RevampCRM\API\EcommerceStoresApi($this->_getClient());
            $response = $storesApi->eCommerceStoreDelete($store_id);
            if($response->GetSuccess() == true){
                return true;
            }else{
                return false;
            }
        } catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }

    /**
     * @param $store_id
     * @param string $customer_id
     * @return RevampCRM_WooCommerce_Customer|bool
     */
    public function getCustomer($store_id, $customer_id)
    {
        try {
            $data = $this->get("ecommerce/stores/$store_id/customers/$customer_id");
            $customer = new RevampCRM_WooCommerce_Customer();
            return $customer->fromArray($data);
        } catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }

    /**
     * @param RevampCRM_WooCommerce_Customer $store
     * @return RevampCRM_WooCommerce_Customer
     * @throws RevampCRM_WooCommerce_Error
     */
    public function addCustomer(RevampCRM_WooCommerce_Customer $store)
    {
        $this->validateStoreSubmission($store);
        $data = $this->post("ecommerce/stores", $store->toArray());
        $customer = new RevampCRM_WooCommerce_Customer();
        return $customer->fromArray($data);
    }

    // /**
    //  * @param $store_id
    //  * @param int $page
    //  * @param int $count
    //  * @return array|bool
    //  */
    // public function carts($store_id, $page = 1, $count = 10)
    // {
    //     $result = $this->get('ecommerce/stores/'.$store_id.'/carts', array(
    //         'start' => $page,
    //         'count' => $count,
    //         'offset' => ($page * $count),
    //     ));

    //     return $result;
    // }

    // /**
    //  * @param $store_id
    //  * @param RevampCRM_WooCommerce_Cart $cart
    //  * @param bool $silent
    //  * @return bool|RevampCRM_WooCommerce_Cart
    //  * @throws RevampCRM_WooCommerce_Error
    //  */
    // public function addCart($store_id, RevampCRM_WooCommerce_Cart $cart, $silent = true)
    // {
    //     try {
    //         $data = $this->post("ecommerce/stores/$store_id/carts", $cart->toArray());
    //         $cart = new RevampCRM_WooCommerce_Cart();
    //         return $cart->setStoreID($store_id)->fromArray($data);
    //     } catch (RevampCRM_WooCommerce_Error $e) {
    //         if (!$silent) throw $e;
    //         revampcrm_log('api.addCart', $e->getMessage());
    //         return false;
    //     }
    // }

    // /**
    //  * @param $store_id
    //  * @param RevampCRM_WooCommerce_Cart $cart
    //  * @param bool $silent
    //  * @return bool|RevampCRM_WooCommerce_Cart
    //  * @throws RevampCRM_WooCommerce_Error
    //  */
    // public function updateCart($store_id, RevampCRM_WooCommerce_Cart $cart, $silent = true)
    // {
    //     try {
    //         $data = $this->patch("ecommerce/stores/$store_id/carts/{$cart->getId()}", $cart->toArrayForUpdate());
    //         $cart = new RevampCRM_WooCommerce_Cart();
    //         return $cart->setStoreID($store_id)->fromArray($data);
    //     } catch (RevampCRM_WooCommerce_Error $e) {
    //         if (!$silent) throw $e;
    //         revampcrm_log('api.updateCart', $e->getMessage());
    //         return false;
    //     }
    // }

    // /**
    //  * @param $store_id
    //  * @param $id
    //  * @return bool|RevampCRM_WooCommerce_Cart
    //  */
    // public function getCart($store_id, $id)
    // {
    //     try {
    //         $data = $this->get("ecommerce/stores/$store_id/carts/$id");
    //         $cart = new RevampCRM_WooCommerce_Cart();
    //         return $cart->setStoreID($store_id)->fromArray($data);
    //     } catch (RevampCRM_WooCommerce_Error $e) {
    //         return false;
    //     }
    // }

    // /**
    //  * @param $store_id
    //  * @param $id
    //  * @return bool
    //  */
    // public function deleteCartByID($store_id, $id)
    // {
    //     try {
    //         $this->delete("ecommerce/stores/$store_id/carts/$id");
    //         return true;
    //     } catch (RevampCRM_WooCommerce_Error $e) {
    //         return false;
    //     }
    // }

    /**
     * @param $store_id
     * @param RevampCRM_WooCommerce_Customer $customer
     * @param bool $silent
     * @return bool|RevampCRM_WooCommerce_Customer
     * @throws RevampCRM_WooCommerce_Error
     */
    public function updateCustomer($store_id, RevampCRM_WooCommerce_Customer $customer, $silent = true)
    {
        try {
            $this->validateStoreSubmission($customer);
            $data = $this->patch("ecommerce/stores/$store_id/customers/{$customer->getId()}", $customer->toArray());
            $customer = new RevampCRM_WooCommerce_Customer();
            return $customer->fromArray($data);
        } catch (RevampCRM_WooCommerce_Error $e) {
            if (!$silent) throw $e;
            return false;
        }
    }

    /**
     * @param $store_id
     * @param $customer_id
     * @return bool
     */
    public function deleteCustomer($store_id, $customer_id)
    {
        try {
            $this->delete("ecommerce/stores/$store_id/customers/$customer_id");
            return true;
        } catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }

    /**
     * @param $store_id
     * @param RevampCRM\Models\ECommerceOrderApiModel $order
     * @param bool $silent
     * @return bool|RevampCRM\Models\ECommerceOrderApiModel
     * @throws Exception
     */
    public function addStoreOrder($store_id, RevampCRM\Models\ECommerceOrderApiModel $order, $silent = true)
    {
        try {
            if (!$this->validateStoreSubmission($order)) {
                return false;
            }

            // submit the first one
            $data = $this->post("ecommerce/stores/$store_id/orders", $order->toArray());

            // if the order is in pending status, we need to submit the order again with a paid status.
            if ($order->shouldConfirmAndPay() && $order->getFinancialStatus() !== 'paid') {
                $order->setFinancialStatus('paid');
                $data = $this->patch("ecommerce/stores/{$store_id}/orders/{$order->getId()}", $order->toArray());
            }

            update_option('revampcrm-woocommerce-resource-last-updated', time());
            $order = new RevampCRM\Models\ECommerceOrderApiModel($data);
            return $order;
            // return $order->fromArray($data);
        } catch (\Exception $e) {
            if (!$silent) throw $e;
            revampcrm_log('api.add_order.error', $e->getMessage(), array('submission' => $order->toArray()));
            return false;
        }
    }

    /**
     * @param $store_id
     * @param RevampCRM\Models\ECommerceOrderApiModel $order
     * @param bool $silent
     * @return bool|RevampCRM\Models\ECommerceOrderApiModel
     * @throws Exception
     */
    public function updateStoreOrder($store_id, RevampCRM\Models\ECommerceOrderApiModel $order, $silent = true)
    {
        try {
            if (!$this->validateStoreSubmission($order)) {
                return false;
            }
            $id = $order->getId();
            $data = $this->patch("ecommerce/stores/{$store_id}/orders/{$id}", $order->toArray());

            // if the order is in pending status, we need to submit the order again with a paid status.
            if ($order->shouldConfirmAndPay() && $order->getFinancialStatus() !== 'paid') {
                $order->setFinancialStatus('paid');
                $data = $this->patch("ecommerce/stores/{$store_id}/orders/{$id}", $order->toArray());
            }

            $order = new RevampCRM\Models\ECommerceOrderApiModel($data);
            return $order;
            // return $order->fromArray($data);
        } catch (\Exception $e) {
            if (!$silent) throw $e;
            revampcrm_log('api.update_order.error', $e->getMessage(), array('submission' => $order->toArray()));
            return false;
        }
    }

    /**
     * @param $store_id
     * @param $order_id
     * @return RevampCRM\Models\ECommerceOrderApiModel|bool
     */
    public function getStoreOrder($store_id, $order_id)
    {
        try {
            $data = $this->get("ecommerce/stores/$store_id/orders/$order_id");
            $order = new RevampCRM\Models\ECommerceOrderApiModel($data);
            // return $order->fromArray($data);
            return $order;
        } catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }

    /**
     * @param $store_id
     * @param $order_id
     * @return bool
     */
    public function deleteStoreOrder($store_id, $order_id)
    {
        try {
            $this->delete("ecommerce/stores/$store_id/orders/$order_id");
            return true;
        } catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }

    /**
     * @param $store_id
     * @param $product_id
     * @return RevampCRM\Models\ECommerceProductApiModel|bool
     */
    public function getStoreProduct($store_id, $product_id)
    {
        try {
            $data = $this->get("ecommerce/stores/$store_id/products/$product_id");
            $product = new RevampCRM\Models\ECommerceProductApiModel($data);
            return $product;
        } catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }

    /**
     * @param $store_id
     * @param int $page
     * @param int $count
     * @return array|bool
     */
    public function productsCount($store_id)
    {
        $ProductsApi = new \RevampCRM\API\EcommerceProductsApi($this->_getClient());


        return $ProductsApi->ecommerceProductCountEcommerceProducts("WooCommerce",$store_id);
    }

    /**
     * @param $store_id
     * @param array $products
     * @param bool $silent
     * @return bool|string
     * @throws Exception
     */
    public function saveStoreProducts($store_id, array $products, $silent = true)
    {
        try {
            // $this->validateStoreSubmission($product);
            $productApi = new \RevampCRM\API\EcommerceProductsApi($this->_getClient());
            $productResponse = $productApi->ecommerceProductSave("WooCommerce",revampcrm_get_store_id(),$products);
            update_option('revampcrm-woocommerce-resource-last-updated', time());
            return $productResponse->getMessages();
        } catch (\Exception $e) {
            if (!$silent) throw $e;
            revampcrm_log('api.add_product.error', $e->getMessage(), array('submission' => $product->toArray()));
            return false;
        }
    }

    /**
     * @param $store_id
     * @param array $orders
     * @param bool $silent
     * @return bool|string
     * @throws Exception
     */
    public function saveStoreOrders($store_id, array $orders, $silent = true)
    {
        try {
            // $this->validateStoreSubmission($orders);
            $orderApi = new \RevampCRM\API\EcommerceOrdersApi($this->_getClient());
            $orderResponse = $orderApi->ecommerceOrderSave("WooCommerce",revampcrm_get_store_id(),$orders);
            update_option('revampcrm-woocommerce-resource-last-updated', time());
            return $orderResponse->getMessages();
        } catch (\Exception $e) {
            if (!$silent) throw $e;
            revampcrm_log('api.add_order.error', $e->getMessage(), array('submission' => $orders->toArray()));
            return false;
        }
    }

    /**
     * @param $store_id
     * @param $product_id
     * @return bool
     */
    public function deleteStoreProduct($store_id, $product_id)
    {
        try {
            $this->delete("ecommerce/stores/$store_id/products/$product_id");
            return true;
        } catch (RevampCRM_WooCommerce_Error $e) {
            return false;
        }
    }

    /**
     * @param RevampCRM_WooCommerce_Store|RevampCRM\Models\ECommerceOrderApiModel|RevampCRM_WooCommerce_Product|RevampCRM_WooCommerce_Customer $target
     * @return bool
     * @throws RevampCRM_WooCommerce_Error
     */
    protected function validateStoreSubmission($target)
    {
        if ($target instanceof RevampCRM\Models\ECommerceOrderApiModel) {
            return $this->validateStoreOrder($target);
        }
        return true;
    }

    /**
     * @param RevampCRM\Models\ECommerceOrderApiModel $order
     * @return bool
     */
    protected function validateStoreOrder(RevampCRM\Models\ECommerceOrderApiModel $order)
    {
        if (revampcrm_string_contains($order->getCustomer()->getEmailAddress(), array('marketplace.amazon.com'))) {
            revampcrm_log('validation.amazon', "Order #{$order->getId()} was placed through Amazon. Skipping!");
            return false;
        }
        return true;
    }

    /**
     * @param $url
     * @param null $params
     * @return array|bool
     * @throws RevampCRM_WooCommerce_Error
     */
    protected function delete($url, $params = null)
    {

        $options = $this->getHeaders('DELETE', $url, $params);

        return $this->processWpRemoteResponse($url,$options);
    }

    /**
     * @param $url
     * @param null $params
     * @return array|bool
     * @throws RevampCRM_WooCommerce_Error
     */
    protected function get($url, $params = null)
    {

        $options = $this->getHeaders('GET', $url, $params);
        return $this->processWpRemoteResponse($url, $options);
    }

    /**
     * @param $url
     * @param $body
     * @return array|mixed|null|object
     * @throws Exception
     * @throws RevampCRM_WooCommerce_Error
     */
    protected function patch($url, $body)
    {
        // process the patch request the normal way
        $options = $this->getHeaders('PATCH', $url, array());
        $options['body'] = json_encode($body);


        return $this->processWpRemoteResponse($url, $options);
    }

    /**
     * @param $url
     * @param $body
     * @return array|bool
     * @throws RevampCRM_WooCommerce_Error
     */
    protected function post($url, $body)
    {

        $options = $this->getHeaders('POST', $url, array());
        $options['body'] = json_encode($body);

        return $this->processWpRemoteResponse($url,$options);
    }

    /**
     * @param $url
     * @param $body
     * @return array|bool
     * @throws RevampCRM_WooCommerce_Error
     */
    protected function put($url, $body)
    {

        $options = $this->getHeaders('PUT', $url, array());
        $options['body'] = json_encode($body);

        return $this->processWpRemoteResponse($url, $options);
    }

    /**
     * @param string $extra
     * @param null|array $params
     * @return string
     */
    protected function url($extra = '', $params = null)
    {
        $url = "https://app.revampcrm.com/api/{$this->version}/";

        if (!empty($extra)) {
            $url .= $extra;
        }

        if (!empty($params)) {
            $url .= '?'.(is_array($params) ? http_build_query($params) : $params);
        }

        return $url;
    }

    /**
     * @param $method
     * @param $url
     * @param $body
     * @return array|WP_Error
     */
    protected function sendWithHttpClient($method, $url, $body)
    {
        return _wp_http_get_object()->request($this->url($url), array(
            'method' => strtoupper($method),
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode('revampcrm:'.$this->api_key),
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($body),
        ));
    }

    /**
     * @param $method
     * @param $url
     * @param array $params
     * @return array
     */
    protected function getHeaders($method, $url, $params = array())
    {
        $env = revampcrm_environment_variables();

        return array(
            'method' => strtoupper($method),
            // 'URL' =>$this->url($url, $params),
            'timeout' => '30',
            'redirection' => '10',
            'httpversion' => '1.1',
            'headers'=>array(
                'Authroization' => 'Basic '. base64_encode( $this->api_username . ':' . $this->api_key ),
                'content-type' => 'application/json',
                "user-agent' => 'RevampCRM for WooCommerce/{$env->version}; WordPress/{$env->wp_version}",
            )

            );
      
    }

    /**
     * @param $url
     * @param mixed $options headers , method and/or body
     * @return array|mixed|null|object
     * @throws Exception
     * @throws RevampCRM_WooCommerce_Error
     * @throws RevampCRM_WooCommerce_ServerError
     */
    protected function processWpRemoteResponse($url,$options)
    {
        $response = wp_remote_request( $url, $options );
        $response_code = wp_remote_retrieve_response_code($response);
        
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode($body, true);

        if (empty($response_code) || ($response_code >= 200 && $response_code <= 400)) {
            if (is_array($data)) {
                try {
                    $this->checkForErrors($data);
                } catch (\Exception $e) {
                    throw $e;
                }
            }
            return $data;
        }

        if ($response_code>= 400 && $response_code <= 500) {
            throw new RevampCRM_WooCommerce_Error($data['title'] .' :: '.$data['detail'], $data['status']);
        }

        if ($response_code >= 500) {
            throw new RevampCRM_WooCommerce_ServerError($data['detail'], $data['status']);
        }

        return null;
    }

    /**
     * @param array $data
     * @return bool
     * @throws RevampCRM_WooCommerce_Error
     */
    protected function checkForErrors(array $data)
    {
        // if we have an array of error data push it into a message
        if (isset($data['errors'])) {
            $message = '';
            foreach ($data['errors'] as $error) {
                $message .= '<p>'.$error['field'].': '.$error['message'].'</p>';
            }
            throw new RevampCRM_WooCommerce_Error($message, $data['status']);
        }

        // make sure the response is correct from the data in the response array
        if (isset($data['status']) && $data['status'] >= 400) {
            throw new RevampCRM_WooCommerce_Error($data['detail'], $data['status']);
        }

        return false;
    }
}
