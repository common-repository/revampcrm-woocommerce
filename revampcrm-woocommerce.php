<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.revampcrm.com
 * @since             0.9.0
 * @package           Revampcrm_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Revamp CRM For WooCommerce
 * Description:       Revamp CRM - Synchronize your WooCommerce Orders, products and customers into the CRM.
 * Version:           1.1.2
 * Author:            Revamp CRM
 * Author URI:        www.revampcrm.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       revampcrm-woocommerce
 * Domain Path:       /languages
 * Requires at least: 4.4
 * Tested up to: 6.5
 * */


// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define('REVCRM_WOO_PLUGIN_VERSION', '1.1.2');

// ======================================
/**
 * @return string
 */
function revampcrm_get_store_id()
{
	$store_id = revampcrm_get_data('store_id', false);
	if (empty($store_id)) {
		// this is for the previous installs that had been applying the MC store id as the siteurl.
		// patched to the random hash because people were changing this value for various reasons.
		$store_id = str_replace(['http://', 'https://'], '', get_option('siteurl'));
		$store_id = str_replace("/", "_", $store_id);
		$store_id = urlencode(str_replace(strstr($store_id, ':'), '', $store_id));
		revampcrm_set_data('store_id', $store_id, 'yes');
	}
	return $store_id;
}

/**
 * @return bool|RevampCRM_WooCommerce_RevampCRMApi
 */
function revampcrm_get_api()
{
	if (($options = get_option('revampcrm-woocommerce', false)) && is_array($options)) {
		if (isset($options['revampcrm_api_key']) && isset($options['revampcrm_account_info_email'])) {
			return new RevampCRM_WooCommerce_RevampCRMApi($options['revampcrm_account_info_email'], $options['revampcrm_api_key']);
		}
	}
	return false;
}

/**
 * @param $key
 * @param null $default
 * @return null
 */
function revampcrm_get_option($key, $default = null)
{
	$options = get_option('revampcrm-woocommerce');
	if (!is_array($options)) {
		return $default;
	}
	if (!array_key_exists($key, $options)) {
		return $default;
	}
	return $options[$key];
}

/**
 * @param $key
 * @param null $default
 * @return mixed
 */
function revampcrm_get_data($key, $default = null)
{
	return get_option('revampcrm-woocommerce-' . $key, $default);
}

/**
 * @param $key
 * @param $value
 * @param string $autoload
 * @return bool
 */
function revampcrm_set_data($key, $value, $autoload = 'yes')
{
	return update_option('revampcrm-woocommerce-' . $key, $value, $autoload);
}

/**
 * @param $date
 * @return DateTime
 */
function revampcrm_date_utc($date)
{
	$timezone = wc_timezone_string();
	//$timezone = revampcrm_get_option('store_timezone', 'America/New_York');
	if (is_numeric($date)) {
		$stamp = $date;
		$date = new \DateTime('now', new DateTimeZone($timezone));
		$date->setTimestamp($stamp);
	} else {
		$date = new \DateTime($date, new DateTimeZone($timezone));
	}

	$date->setTimezone(new DateTimeZone('UTC'));
	return $date;
}

/**
 * @param $date
 * @return DateTime
 */
function revampcrm_date_local($date)
{
	$timezone = revampcrm_get_option('store_timezone', 'America/New_York');
	if (is_numeric($date)) {
		$stamp = $date;
		$date = new \DateTime('now', new DateTimeZone('UTC'));
		$date->setTimestamp($stamp);
	} else {
		$date = new \DateTime($date, new DateTimeZone('UTC'));
	}

	$date->setTimezone(new DateTimeZone($timezone));
	return $date;
}

/**
 * @param array $data
 * @return mixed
 */
function revampcrm_array_remove_empty($data)
{
	if (empty($data) || !is_array($data)) {
		return array();
	}
	foreach ($data as $key => $value) {
		if ($value === null || $value === '') {
			unset($data[$key]);
		}
	}
	return $data;
}

/**
 * @return array
 */
function revampcrm_get_timezone_list()
{
	$zones_array = array();
	$timestamp = time();
	$current = date_default_timezone_get();

	foreach (timezone_identifiers_list() as $key => $zone) {
		date_default_timezone_set($zone);
		$zones_array[$key]['zone'] = $zone;
		$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
	}

	date_default_timezone_set($current);

	return $zones_array;
}


function revampcrm_debug($action, $message, $data = null)
{
	$_logdata = array('action' => $action, 'time' => date(DATE_RSS), 'message' => $message, 'data' => $data);
	do_action('logger', $_logdata);
	if (defined('WP_CLI') && WP_CLI) {
		WP_CLI::debug(print_r($_logdata, true), "revampcrm-woocommerce");
	}

	$filename = dirname(__FILE__).'/revampcrm-woocommerce-debug.log';
	// Let's make sure the file exists and is writable first.
	if (is_writable($filename)) {
		$fp = fopen($filename, 'a');
		fwrite($fp, var_export($_logdata));
		fclose($fp);
	}
	
	$options = RevampCRM_Woocommerce::getLoggingConfig();
	if (!$options->enable_logging || !$options->account_id || !$options->username) {
		return false;
	}
}

/**
 * @param $action
 * @param $message
 * @param array $data
 * @return array|WP_Error
 */
function revampcrm_log($action, $message, $data = array())
{
	$_logdata = array('action' => $action, 'message' => $message, 'data' => $data);
	do_action('logger', $_logdata);
	if (defined('WP_CLI') && WP_CLI) {
		WP_CLI::log(print_r(array('message' => $message, 'data' => $data), true));
		return null;
	}

	$options = RevampCRM_Woocommerce::getLoggingConfig();
	if (!$options->enable_logging || !$options->account_id || !$options->username) {
		return false;
	}
}

/**
 * Determine if a given string contains a given substring.
 *
 * @param  string  $haystack
 * @param  string|array  $needles
 * @return bool
 */
function revampcrm_string_contains($haystack, $needles)
{
	foreach ((array) $needles as $needle) {
		if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
			return true;
		}
	}

	return false;
}

/**
 * Create the queue tables
 */
function revampcrm_install_queue()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-revampcrm-woocommerce-activator.php';
	RevampCRM_Woocommerce_Activator::create_queue_tables();
}

// ======================================
/**
 * @return object
 */
function revampcrm_environment_variables()
{
	return require 'env.php';
}


/**
 * @return int
 */
function revampcrm_get_product_count()
{
	$posts = revampcrm_count_posts('product');
	unset($posts['auto-draft'], $posts['trash'], $posts['inherit']);
	$total = 0;
	foreach ($posts as $status => $count) {
		$total += $count;
	}
	return $total;
}

/**
 * @return int
 */
function revampcrm_get_order_count()
{
	$posts = revampcrm_count_posts('shop_order');
	unset($posts['auto-draft'], $posts['trash']);
	$total = 0;
	foreach ($posts as $status => $count) {
		$total += $count;
	}
	// Adjust for 
	$posts_new = revampcrm_count_posts('shop_order_placehold');
	unset($posts_new['auto-draft'], $posts_new['trash']);
	foreach ($posts_new as $status => $count) {
		$total += $count;
	}
	return $total;
}

/**
 * @param $type
 * @return array|null|object
 */
function revampcrm_count_posts($type)
{
	global $wpdb;
	$query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s GROUP BY post_status";
	$posts = $wpdb->get_results($wpdb->prepare($query, $type));
	$response = array();
	foreach ($posts as $post) {
		$response[$post->post_status] = $post->num_posts;
	}
	return $response;
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-revampcrm-woocommerce-activator.php
 */
function activate_revampcrm_woocommerce()
{
	// if we don't have woocommerce we need to display a horrible error message before the plugin is installed.
	if (!is_plugin_active('woocommerce/woocommerce.php')) {

		$active = false;

		// some people may have uploaded a specific version of woo, so we need a fallback checker here.
		foreach (array_keys(get_plugins()) as $plugin) {
			if (revampcrm_string_contains($plugin, 'woocommerce.php')) {
				$active = true;
				break;
			}
		}

		if (!$active) {
			// Deactivate the plugin
			deactivate_plugins(__FILE__);
			$error_message = __('The RevampCRM For WooCommerce plugin requires the <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> plugin to be active!', 'woocommerce');
			wp_die($error_message);
		}
	}
	require_once plugin_dir_path(__FILE__) . 'includes/class-revampcrm-woocommerce-activator.php';
	Revampcrm_Woocommerce_Activator::activate();
}



/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-revampcrm-woocommerce-deactivator.php
 */
function deactivate_revampcrm_woocommerce()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-revampcrm-woocommerce-deactivator.php';
	Revampcrm_Woocommerce_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_revampcrm_woocommerce');
register_deactivation_hook(__FILE__, 'deactivate_revampcrm_woocommerce');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-revampcrm-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.9.0
 */
function run_revampcrm_woocommerce()
{
	$env = revampcrm_environment_variables();
	$plugin = new Revampcrm_Woocommerce();
	$plugin->run();
}

if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$forwarded_address = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	$_SERVER['REMOTE_ADDR'] = $forwarded_address[0];
}

if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/');

// revampcrm_log("dddddd","aaa");
run_revampcrm_woocommerce();

remove_action('shutdown', 'wp_ob_end_flush_all', 1);
