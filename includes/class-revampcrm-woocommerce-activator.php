<?php

/**
 * Fired during plugin activation
 *
 * @link       www.revampcrm.com
 * @since      0.9.0
 *
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.9.0
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/includes
 * @author     Revamp CRM <crm@revampco.com>
 */
class Revampcrm_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.9.0
	 */
	public static function activate() {
		static::create_queue_tables();

		// update the settings so we have them for use.
		update_option('revampcrm-woocommerce', array());

		// add a store id flag which will be a random hash
		$store_id = str_replace(['http://','https://'],'',get_option('siteurl'));
		$store_id = urlencode(str_replace(strstr($store_id,':'),'',$store_id));
		update_option('revampcrm-woocommerce-store_id', $store_id, 'yes');
	}


	/**
	 * Create the queue tables in the DB so we can use it for syncing.
	 */
	public static function create_queue_tables()
	{
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;

		$wpdb->hide_errors();

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}queue (
				id bigint(20) NOT NULL AUTO_INCREMENT,
                job text NOT NULL,
                attempts tinyint(1) NOT NULL DEFAULT 0,
                locked tinyint(1) NOT NULL DEFAULT 0,
                locked_at datetime DEFAULT NULL,
                available_at datetime NOT NULL,
                created_at datetime NOT NULL,
                PRIMARY KEY  (id)
				) $charset_collate;";

		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}failed_jobs (
				id bigint(20) NOT NULL AUTO_INCREMENT,
                job text NOT NULL,
                failed_at datetime NOT NULL,
                PRIMARY KEY  (id)
				) $charset_collate;";

		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}revampcrm_carts (
				id VARCHAR (255) NOT NULL,
				email VARCHAR (100) NOT NULL,
				user_id INT (11) DEFAULT NULL,
                cart text NOT NULL,
                created_at datetime NOT NULL
				) $charset_collate;";

		dbDelta( $sql );

		// set the revampcrm woocommerce version at the time of install
		update_site_option('revampcrm_woocommerce_version', revampcrm_environment_variables()->version);
	}
}
