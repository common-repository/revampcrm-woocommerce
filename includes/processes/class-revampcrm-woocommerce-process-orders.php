<?php

/**
 * Created by Vextras.
 * Modified By RevampCo.
 *
 * Name: Ryan Hungate
 * Email: ryan@mailchimp.com
 * Date: 7/14/16
 * Time: 10:57 AM
 * 
 * Name: Waleed Meligy
 * Email: wmeligy@revampco.com
 * Date: 10/15/17
 * Time: 8:29 AM
 * 
 */

class RevampCRM_WooCommerce_Process_Orders extends RevampCRM_WooCommerce_Abtstract_Sync
{

    /**
     * @var string
     */
    protected $action = 'revampcrm_woocommerce_process_orders';
    public $items = array();

    public function __construct($resync=false) {
       $this->full_resync = $resync;
    }

    /**
     * @return string
     */
    public function getResourceType()
    {
        return 'orders';
    }

    /**
     * @param RevampCRM\Models\ECommerceOrderApiModel $item
     *
     * @return mixed
     */
    protected function iterate($item)
    {
        if ($item instanceof RevampCRM\Models\ECommerceOrderApiModel) {

            // since we're syncing the customer for the first time, this is where we need to add the override
            // for subscriber status. We don't get the checkbox until this plugin is actually installed and working!

            // if ((bool) $this->getOption('revampcrm_auto_subscribe', true)) {
            //     $item->getCustomer()->setOptInStatus(true);
            // }

            $type = $this->revampcrm()->getStoreOrder($this->store_id, $item->getId()) ? 'update' : 'create';
            $call = $type === 'create' ? 'addStoreOrder' : 'updateStoreOrder';

            try {

                $log = "$call :: #{$item->getId()} :: email: {$item->getCustomer()->getEmailAddress()}";

                revampcrm_log('sync.orders.submitting', $log);

                // make the call
                $response = $this->revampcrm()->$call($this->store_id, $item, false);

                if (empty($response)) {
                    return $response;
                }

                revampcrm_log('sync.orders.success', $log);

                $this->items[] = array('response' => $response, 'item' => $item);

                return $response;

            } catch (RevampCRM_WooCommerce_ServerError $e) {
                revampcrm_log('sync.orders.error', "$call :: RevampCRM_WooCommerce_ServerError :: {$e->getMessage()}");
                return false;
            } catch (RevampCRM_WooCommerce_Error $e) {
                revampcrm_log('sync.orders.error', "$call :: RevampCRM_WooCommerce_Error :: {$e->getMessage()}");
                return false;
            } catch (Exception $e) {
                revampcrm_log('sync.orders.error', "$call :: Uncaught Exception :: {$e->getMessage()}");
                return false;
            }
        }

        revampcrm_debug('iterate.order', 'no order found', $item);

        return false;
    }

    protected function saveBatch($items){
        $batch = [];
        foreach ($items as $item) {
            if ($item instanceof \RevampCRM\Models\ECommerceOrderApiModel) {
                $batch[] = $item;
            }
        }
        if(count($batch) > 0){
            try {
                // make the call
                $response = $this->revampcrm()->saveStoreOrders($this->store_id, $batch, false);
                if(is_array($response)){
                    revampcrm_log('sync.orders.success', "addStoreOrder - sending ". count($batch)." items -  :: ". implode(",", $response) );
                }else{
                    revampcrm_log('sync.orders.success', "addStoreOrder - sending ". count($batch)." items -  :: {$response}");
                }

                return $response;

            } catch (RevampCRM_WooCommerce_ServerError $e) {
                    revampcrm_log('sync.orders.error', "addStoreOrder :: RevampCRM_WooCommerce_ServerError :: {$e->getMessage()}");
            } catch (RevampCRM_WooCommerce_Error $e) {
                    revampcrm_log('sync.orders.error', "addStoreOrder :: RevampCRM_WooCommerce_Error :: {$e->getMessage()}");
            } catch (Exception $e) {
                    revampcrm_log('sync.orders.error', "addStoreOrder :: Uncaught Exception :: {$e->getMessage()}");
            }
        }

        return false;
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
