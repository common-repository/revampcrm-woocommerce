<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.revampcrm.com
 * @since      0.9.0
 *
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.9.0
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/includes
 * @author     Revamp CRM <crm@revampco.com>
 */
class Revampcrm_Woocommerce_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.9.0
	 */
	public static function deactivate() {
		// if the api is valid, we need to try to delete the store
		try{
			if (($api = revampcrm_get_api())) {
				$api->deleteStore(revampcrm_get_store_id());
			}
		}catch(\RevampCRM\Client\ApiException $ex){}

		delete_option('revampcrm-woocommerce-sync.started_at');
		delete_option('revampcrm-woocommerce-sync.completed_at');
//		delete_option('revampcrm-woocommerce-resource-last-updated');
	}

}
