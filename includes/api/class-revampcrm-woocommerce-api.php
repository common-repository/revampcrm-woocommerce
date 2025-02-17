<?php

/**
 * Created by Vextras.
 *
 * Name: Ryan Hungate
 * Email: ryan@revampcrm.com
 * Date: 7/13/16
 * Time: 2:32 PM
 */
class RevampCRM_WooCommerce_Api
{
    protected static $filterable_actions = array(
        'paginate-resource',
    );

    /**
     * @param int $default_page
     * @param int $default_per
     * @return array
     */
    public static function filter($default_page = null, $default_per = null)
    {
        if (isset($_GET['revampcrm-woocommerce']) && isset($_GET['revampcrm-woocommerce']['action'])) {
            if (in_array($_GET['revampcrm-woocommerce']['action'], static::$filterable_actions)) {
                if (empty($default_page)) {
                    $page = isset($_GET['page']) ? (int) $_GET['page'] : null;
                }
                if (empty($default_per)) {
                    $per = isset($_GET['per']) ? (int) $_GET['per'] : null;
                }
            }
        }

        if (empty($page)) $page = 1;
        if (empty($per)) $per = 5;

        return array($page, $per);
    }

    /**
     * @param null $page
     * @param null $per
     * @return object|stdClass
     */
    public function paginateProducts($page = null, $per = null)
    {
        return $this->paginate('products', $page, $per);
    }

    /**
     * @param null $page
     * @param null $per
     * @return object|stdClass
     */
    public function paginateOrders($page = null, $per = null)
    {
        return $this->paginate('orders', $page, $per);
    }

    /**
     * @param $resource
     * @param int $page
     * @param int $per
     * @return object|stdClass
     */
    public function paginate($resource, $page = 1, $per = 5, $startSyncTime=null)
    {
        if (($sync = $this->engine($resource))) {
            return $sync->compile($page, $per,$startSyncTime);
        }

        return (object) array(
            'endpoint' => $resource,
            'page' => $page,
            'count' => 0,
            'stuffed' => false,
            'items' => array(),
        );
    }

    /**
     * @param $resource
     * @return bool|RevampCRM_WooCommerce_Transform_Orders|RevampCRM_WooCommerce_Transform_Products
     */
    public function engine($resource)
    {
        switch ($resource) {
            case 'products' :
                return new RevampCRM_WooCommerce_Transform_Products();
                break;
            case 'orders' :
                return new RevampCRM_WooCommerce_Transform_Orders();
                break;

            default:
                return false;
        }
    }
}
