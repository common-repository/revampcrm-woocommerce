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
class RevampCRM_WooCommerce_Process_Products extends RevampCRM_WooCommerce_Abtstract_Sync
{
    /**
     * @var string
     */
    protected $action = 'revampcrm_woocommerce_process_products';

    public function __construct($resync=false) {
       $this->full_resync = $resync;
    }

    public static function push($resync=false)
    {
        $job = new RevampCRM_WooCommerce_Process_Products($resync);
        $job->flagStartSync();
        wp_queue($job);
    }


    /**
     * @return string
     */
    public function getResourceType()
    {
        return 'products';
    }

    /**
     * @param \RevampCRM\Models\ECommerceProductApiModel $item
     *
     * @return mixed
     */
    protected function iterate($item) {

        if ($item instanceof \RevampCRM\Models\ECommerceProductApiModel) {

            // need to run the delete option on this before submitting because the API does not support PATCH yet.
            //$this->revampcrm()->deleteStoreProduct($this->store_id, $item->getId());

            // add the product.
            try {
                // make the call
                $response = $this->revampcrm()->addStoreProduct($this->store_id, $item, false);

                revampcrm_log('sync.products.success', "addStoreProduct :: #{$response->getId()}");

                return $response;

            } catch (RevampCRM_WooCommerce_ServerError $e) {
                revampcrm_log('sync.products.error', "addStoreProduct :: RevampCRM_WooCommerce_ServerError :: {$e->getMessage()}");
            } catch (RevampCRM_WooCommerce_Error $e) {
                revampcrm_log('sync.products.error', "addStoreProduct :: RevampCRM_WooCommerce_Error :: {$e->getMessage()}");
            } catch (Exception $e) {
                revampcrm_log('sync.products.error', "addStoreProduct :: Uncaught Exception :: {$e->getMessage()}");
            }
        }

        return false;
    }

    protected function saveBatch($items){
        $batch = [];
        foreach ($items as $item) {
            if ($item instanceof \RevampCRM\Models\ECommerceProductApiModel) {
                $batch[] = $item;
            }
        }
        if(count($batch) > 0){
            try {
                // make the call
                $response = $this->revampcrm()->saveStoreProducts($this->store_id, $batch, false);
                if(is_array($response)){
                    revampcrm_log('sync.products.success', "addStoreProduct - sending ". count($batch)." items - :: ".implode(",",$response) ."");
                }else{
                    revampcrm_log('sync.products.success', "addStoreProduct - sending ". count($batch)." items - :: {$response}");
                }

                return $response;

            } catch (RevampCRM_WooCommerce_ServerError $e) {
                    revampcrm_log('sync.products.error', "addStoreProduct :: RevampCRM_WooCommerce_ServerError :: {$e->getMessage()}");
            } catch (RevampCRM_WooCommerce_Error $e) {
                    revampcrm_log('sync.products.error', "addStoreProduct :: RevampCRM_WooCommerce_Error :: {$e->getMessage()}");
            } catch (Exception $e) {
                    revampcrm_log('sync.products.error', "addStoreProduct :: Uncaught Exception :: {$e->getMessage()}");
            }
        }

        return false;
    }

    /**
     * Called after all the products have been iterated and processed into RevampCRM
     */
    protected function complete()
    {
        revampcrm_log('sync.products.completed', 'Done with the product sync :: queuing up the orders next!');

        // add a timestamp for the product sync completion
        $this->setResourceCompleteTime();

        $prevent_order_sync = get_option('revampcrm-woocommerce-sync.orders.prevent', false);

        // only do this if we're not strictly syncing products ( which is the default ).
        if (!$prevent_order_sync) {
            // since the products are all good, let's sync up the orders now.
            wp_queue(new RevampCRM_WooCommerce_Process_Orders($this->full_resync));
        }

        // since we skipped the orders feed we can delete this option.
        delete_option('revampcrm-woocommerce-sync.orders.prevent');
    }
}
