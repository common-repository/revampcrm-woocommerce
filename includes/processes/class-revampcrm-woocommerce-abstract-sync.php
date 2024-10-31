<?php

/**
 * Created by Vextras. 
 * Modified by RevampCo.
 *
 * Name: Ryan Hungate
 * Email: ryan@mailchimp.com
 * Date: 7/14/16
 * Time: 11:54 AM
 * 
 * Name: Waleed Meligy
 * Email: wmeligy@revampco.com
 * Date: 10/15/17
 * Time: 8:29 AM
 * 
 */
abstract class RevampCRM_WooCommerce_Abtstract_Sync extends WP_Job
{
    /**
     * @var RevampCRM_WooCommerce_Api
     */
    private $api;

    /**
     * @var RevampCRM_WooCommerce_RevampCRMApi
     */
    private $mc;

    /**
     * @var string
     */
    private $plugin_name = 'revampcrm-woocommerce';

    /**
     * @var string
     */
    protected $store_id = '';

    protected $full_resync= false;
    /**
     * @return mixed
     */
    abstract public function getResourceType();

    /**
     * @param $item
     * @return mixed
     */
    abstract protected function iterate($item);

    /**
     * @return mixed
     */
    abstract protected function complete();

    /**
     * @return mixed
     */
    public function go()
    {
        return $this->handle();
    }

    /**
     * @return string
     */
    public function getStoreID()
    {
        return revampcrm_get_store_id();
    }

    /**
     * Task
     *
     * Override this method to perform any actions required on each
     * queue item. Return the modified item for further processing
     * in the next pass through. Or, return false to remove the
     * item from the queue.
     *
     * @param mixed $item Queue item to iterate over
     *
     * @return mixed
     */
    public function handle() {

        if (!($this->store_id = $this->getStoreID())) {
            revampcrm_debug(get_called_class().'@handle', 'store id not loaded');
            return false;
        }

        $page = $this->getResources();

        if (empty($page)) {
            revampcrm_debug(get_called_class().'@handle', 'could not find any more '.$this->getResourceType().' records ending on page '.$this->getResourcePagePointer());
            // call the completed event to process further
            $this->resourceComplete($this->getResourceType());
            $this->complete();
            return false;
        }

        $this->setResourcePagePointer(($page->page + 1), $this->getResourceType());

        // if we've got a 0 count, that means we're done.
        if ($page->count <= 0) {

            revampcrm_debug(get_called_class().'@handle', $this->getResourceType().' :: count is 0 in page ' . ($page->page + 1).' : completing now!');
            // reset the resource page back to 1
            $this->resourceComplete($this->getResourceType());

            // call the completed event to process further
            $this->complete();

            return false;
        }else{
//            revampcrm_debug(get_called_class().'@handle', $this->getResourceType().' :: count is ' . $page->count . ' in page ' . ($page->page + 1).' ...');
        }

        // // iterate through the items and send each one through the pipeline based on this class.
        // foreach ($page->items as $resource) {
        //     $this->iterate($resource);
        // }
        // Save a batch for each page
        $this->saveBatch($page->items);

        revampcrm_debug(get_called_class().'@handle', 'queuing up the next job');

        // this will paginate through all records for the resource type until they return no records.
        wp_queue(new static($this->full_resync));

        return false;
    }

    /**
     * @return $this
     */
    public function flagStartSync()
    {
        $job = new RevampCRM_Service();

        $job->removeSyncPointers();

        $this->setData('sync.config.resync', false);
        $this->setData('sync.orders.current_page', 1);
        $this->setData('sync.products.current_page', 1);
        $this->setData('sync.syncing', true);
        $this->setData('sync.started_at', time());

        global $wpdb;
        try {
            $wpdb->show_errors(false);
            $wpdb->query("DELETE FROM {$wpdb->prefix}queue");
            $wpdb->show_errors(true);
        } catch (\Exception $e) {}

        revampcrm_log('sync.started', "Starting Sync :: ".date('D, M j, Y g:i A'));

        // flag the store as syncing
        revampcrm_get_api()->flagStoreSync(revampcrm_get_store_id(), true);

        return $this;
    }

    /**
     * @return $this
     */
    public function flagStopSync()
    {
        // this is the last thing we're doing so it's complete as of now.
        $this->setData('sync.syncing', false);
        $this->setData('sync.completed_at', time());

        // set the current sync pages back to 1 if the user hits resync.
        $this->setData('sync.orders.current_page', 1);
        $this->setData('sync.products.current_page', 1);

        revampcrm_log('sync.completed', "Finished Sync :: ".date('D, M j, Y g:i A'));

        // flag the store as sync_finished
        revampcrm_get_api()->flagStoreSync(revampcrm_get_store_id(), false);

        return $this;
    }

    /**
     * @return bool|object|stdClass
     */
    public function getResources()
    {
        $current_page = $this->getResourcePagePointer($this->getResourceType());


        if ($current_page === 'complete') {
            if (!$this->getData('sync.config.resync', false)) {
                return false;
            }

            $current_page = 1;
            $this->setResourcePagePointer($current_page);
            $this->setData('sync.config.resync', false);
        }
        $startSyncTime = null;
        if(!$this->full_resync){
            $startSyncTime = $this->getResourceCompleteTime();
        }
        return $this->api()->paginate($this->getResourceType(), $current_page, 5,$startSyncTime);
    }

    /**
     * @param null|string $resource
     * @return $this
     */
    public function resetResourcePagePointer($resource = null)
    {
        if (empty($resource)) $resource = $this->getResourceType();

        $this->setData('sync.'.$resource.'.current_page', 1);

        return $this;
    }

    /**
     * @param null|string $resource
     * @return null
     */
    public function getResourcePagePointer($resource = null)
    {
        if (empty($resource)) $resource = $this->getResourceType();

        return $this->getData('sync.'.$resource.'.current_page', 1);
    }

    /**
     * @param $page
     * @param null $resource
     * @return RevampCRM_WooCommerce_Abtstract_Sync
     */
    public function setResourcePagePointer($page, $resource = null)
    {
        if (empty($resource)) $resource = $this->getResourceType();

        return $this->setData('sync.'.$resource.'.current_page', $page);
    }

    /**
     * @param null|string $resource
     * @return $this
     */
    protected function resourceComplete($resource = null)
    {
        if (empty($resource)) $resource = $this->getResourceType();

        $this->setData('sync.'.$resource.'.current_page', 'complete');

        return $this;
    }

    /**
     * @return null
     */
    protected function setResourceCompleteTime($resource = null)
    {
        if (empty($resource)) $resource = $this->getResourceType();

        return $this->setData('sync.'.$resource.'.completed_at', time());
    }

    /**
     * @param null $resource
     * @return bool|DateTime
     */
    protected function getResourceCompleteTime($resource = null)
    {
        if (empty($resource)) $resource = $this->getResourceType();

        $time = $this->getData('sync.'.$resource.'.completed_at', false);

        if ($time > 0) {
            try{
                return new \DateTime(strtotime($time));
            }catch(\Exception $ex){
                return new \DateTime($time);
                
            }
        }

        return false;
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
     * @param $value
     * @return $this
     */
    public function setOption($key, $value)
    {
        $options = $this->getOptions();
        $options[$key] = $value;
        update_option($this->plugin_name, $options);
        return $this;
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
    public function getOptions()
    {
        $options = get_option($this->plugin_name);
        return is_array($options) ? $options : array();
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
     * @return RevampCRM_WooCommerce_Api
     */
    protected function api()
    {
        if (empty($this->api)) {
            $this->api = new RevampCRM_WooCommerce_Api();
        }
        return $this->api;
    }

    /**
     * @return RevampCRM_WooCommerce_RevampCRMApi
     */
    protected function revampcrm()
    {
        if (empty($this->mc)) {
            $this->mc = new RevampCRM_WooCommerce_RevampCRMApi($this->getOption('revampcrm_account_info_email', false),$this->getOption('revampcrm_api_key', false));
        }
        return $this->mc;
    }
}
