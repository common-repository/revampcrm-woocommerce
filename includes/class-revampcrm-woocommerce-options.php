<?php

/**
 * Created by MailChimp.
 * Modified By RevampCo.
 *
 * Name: Ryan Hungate
 * Email: ryan@mailchimp.com
 * Date: 2/22/16
 * Time: 3:45 PM
 * 
 * Name: Waleed Meligy
 * Email: wmeligy@revampco.com
 * Date: 10/15/17
 * Time: 8:29 AM
 * 
 */
abstract class RevampCRM_Woocommerce_Options
{
    /**
     * @var RevampCRM_WooCommerce_RevampCRMApi
     */
    protected $api;
    protected $plugin_name = 'revampcrm-woocommerce';
    protected $environment = 'production';
    protected $version = '0.9.0';
    protected $plugin_options = null;
    protected $is_admin = false;

    /**
     * hook calls this so that we know the admin is here.
     */
    public function adminReady()
    {
        $this->is_admin = current_user_can('administrator');
        if (get_option('revampcrm_woocommerce_plugin_do_activation_redirect', false)) {
            delete_option('revampcrm_woocommerce_plugin_do_activation_redirect');
            if (!isset($_GET['activate-multi'])) {
                wp_redirect("options-general.php?page=revampcrm-woocommerce");
            }
        }
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @param $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getUniqueStoreID()
    {
        return revampcrm_get_store_id();
    }

    /**
     * @param $env
     * @return $this
     */
    public function setEnvironment($env)
    {
        $this->environment = $env;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function getOption($key, $default = null)
    {
        $options = $this->getOptions();
        if (isset($options[$key])) {
            return $options[$key];
        }
        return $default;
    }

    /**
     * @param $key
     * @param bool $default
     * @return bool
     */
    public function hasOption($key, $default = false)
    {
        return (bool) $this->getOption($key, $default);
    }

    /**
     * @return array
     */
    public function resetOptions()
    {
        return $this->plugin_options = get_option($this->plugin_name);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->plugin_options)) {
            $this->plugin_options = get_option($this->plugin_name);
        }
        return is_array($this->plugin_options) ? $this->plugin_options : array();
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setData($key, $value)
    {
        update_option($this->plugin_name.'-'.$key, $value, 'yes');
        return $this;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|void
     */
    public function getData($key, $default = null)
    {
        return get_option($this->plugin_name.'-'.$key, $default);
    }


    /**
     * @param $key
     * @return bool
     */
    public function removeData($key)
    {
        return delete_option($this->plugin_name.'-'.$key);
    }

    /**
     * @param $key
     * @param null $default
     * @return null|mixed
     */
    public function getCached($key, $default = null)
    {
        $cached = $this->getData("cached-$key", false);
        if (empty($cached) || !($cached = unserialize($cached))) {
            return $default;
        }

        if (empty($cached['till']) || (time() > $cached['till'])) {
            $this->removeData("cached-$key");
            return $default;
        }

        return $cached['value'];
    }

    /**
     * @param $key
     * @param $value
     * @param $seconds
     * @return $this
     */
    public function setCached($key, $value, $seconds = 60)
    {
        $time = time();
        $data = array('at' => $time, 'till' => $time + $seconds, 'value' => $value);
        $this->setData("cached-$key", serialize($data));

        return $this;
    }

    /**
     * @param $key
     * @param $callable
     * @param int $seconds
     * @return mixed|null
     */
    public function getCachedWithSetDefault($key, $callable, $seconds = 60)
    {
        if (!($value = $this->getCached($key, false))) {
            $value = call_user_func($callable);
            $this->setCached($key, $value, $seconds);
        }
        return $value;
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        return true;
        //return $this->getOption('public_key', false) && $this->getOption('secret_key', false);
    }

    /**
     * @return RevampCRM_WooCommerce_RevampCRMApi
     */
    public function api()
    {
        if (empty($this->api)) {
            $this->api = new RevampCRM_WooCommerce_RevampCRMApi($this->getOption('revampcrm_account_info_email', false),$this->getOption('revampcrm_api_key', false));
        }

        return $this->api;
    }

    /**
     * @param array $data
     * @param $key
     * @param null $default
     * @return null|mixed
     */
    public function array_get(array $data, $key, $default = null)
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    /**
     * @param bool $products
     * @param bool $orders
     * @return $this
     */
    public function removePointers($products = true, $orders = true)
    {
        if ($products) {
            $this->removeProductPointers();
        }

        if ($orders) {
            $this->removeOrderPointers();
        }

        $this->removeSyncPointers();

        $this->removeMiscPointers();

        return $this;
    }

    public function removeProductPointers()
    {
        delete_option('revampcrm-woocommerce-sync.products.completed_at');
        delete_option('revampcrm-woocommerce-sync.products.current_page');
    }

    public function removeOrderPointers()
    {
        delete_option('revampcrm-woocommerce-sync.orders.prevent');
        delete_option('revampcrm-woocommerce-sync.orders.completed_at');
        delete_option('revampcrm-woocommerce-sync.orders.current_page');
    }

    public function removeSyncPointers()
    {
        delete_option('revampcrm-woocommerce-sync.orders.prevent');
        delete_option('revampcrm-woocommerce-sync.syncing');
        delete_option('revampcrm-woocommerce-sync.started_at');
        delete_option('revampcrm-woocommerce-sync.completed_at');
    }

    public function removeMiscPointers()
    {
        delete_option('revampcrm-woocommerce-errors.store_info');
        delete_option('revampcrm-woocommerce-validation.api.ping');
        delete_option('revampcrm-woocommerce-cached-api-lists');
        delete_option('revampcrm-woocommerce-cached-api-ping-check');
    }
}
