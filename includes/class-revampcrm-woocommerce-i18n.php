<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.revampcrm.com
 * @since      0.9.0
 *
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.9.0
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/includes
 * @author     Revamp CRM <crm@revampco.com>
 */
class Revampcrm_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.9.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'revampcrm-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
