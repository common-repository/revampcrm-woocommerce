<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.revampcrm.com
 * @since      0.9.0
 *
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.9.0
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/includes
 * @author     Revamp CRM <crm@revampco.com>
 */
class Revampcrm_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.9.0
	 * @access   protected
	 * @var      Revampcrm_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.9.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.9.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

/**
	 * @var string
	 */
	protected $environment = 'production';

	protected static $logging_config = null;

	/**
	 * @return object
	 */
	public static function getLoggingConfig()
	{
		if (is_object(static::$logging_config)) {
			return static::$logging_config;
		}

		$plugin_options = get_option('revampcrm-woocommerce');
		$is_options = is_array($plugin_options);

		$api_key = $is_options && array_key_exists('revampcrm_api_key', $plugin_options) ?
			$plugin_options['revampcrm_api_key'] : false;

		$enable_logging = $is_options &&
			array_key_exists('revampcrm_debugging', $plugin_options) &&
			$plugin_options['revampcrm_debugging'];

		$account_id = $is_options && array_key_exists('revampcrm_account_info_id', $plugin_options) ?
			$plugin_options['revampcrm_account_info_id'] : false;

		$username = $is_options && array_key_exists('revampcrm_account_info_username', $plugin_options) ?
			$plugin_options['revampcrm_account_info_username'] : false;

		$api_key_parts = str_getcsv($api_key, '-');
		$data_center = isset($api_key_parts[1]) ? $api_key_parts[1] : 'us1';

		return static::$logging_config = (object) array(
			'enable_logging' => (bool) $enable_logging,
			'account_id' => $account_id,
			'username' => $username,
		);
	}


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.9.0
	 */
	public function __construct() {
		if ( defined( 'REVCRM_WOO_PLUGIN_VERSION' ) ) {
			$this->version = REVCRM_WOO_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.1';
		}
		$this->plugin_name = 'revampcrm-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->activateRevampCRMService();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Revampcrm_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Revampcrm_Woocommerce_i18n. Defines internationalization functionality.
	 * - Revampcrm_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Revampcrm_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.9.0
	 * @access   private
	 */
	private function load_dependencies() {

		

		$path = plugin_dir_path( dirname( __FILE__ ) );
/** The abstract options class.*/
		require_once $path . 'includes/class-revampcrm-woocommerce-options.php';

		/** The class responsible for orchestrating the actions and filters of the core plugin.*/
		require_once $path . 'includes/class-revampcrm-woocommerce-loader.php';

		/** The class responsible for defining internationalization functionality of the plugin. */
		require_once $path . 'includes/class-revampcrm-woocommerce-i18n.php';

		/** The service class.*/
		require_once $path . 'includes/class-revampcrm-woocommerce-service.php';

		/** The class responsible for defining all actions that occur in the admin area.*/
		require_once $path . 'admin/class-revampcrm-woocommerce-admin.php';

		/** The class responsible for defining all actions that occur in the public-facing side of the site. */
		require_once $path . 'public/class-revampcrm-woocommerce-public.php';

		/** Require all the RevampCRM Assets for the API */
		require_once $path . 'includes/api/class-revampcrm-api.php';
		require_once $path . 'includes/api/class-revampcrm-woocommerce-api.php';
		require_once $path . 'includes/api/class-revampcrm-woocommerce-transform-products.php';

		/** Require all the revampcrm api asset classes */
		require_once $path . 'lib/RevampCRM/Models/AccountMessageListViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/AccountMessageViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/AccountOption.php';
		require_once $path . 'lib/RevampCRM/Models/AddressViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/AttachmentSummary.php';
		require_once $path . 'lib/RevampCRM/Models/BsonElement.php';
		require_once $path . 'lib/RevampCRM/Models/ContactCSVApiModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactDealListViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactDealsDetailsViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactEditViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactInfoViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactListByAlphabetItemViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactListItemViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactsActionsViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactsListByAlphabetViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactsListViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/ContactStageViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/CustomFieldEntity.php';
		require_once $path . 'lib/RevampCRM/Models/Deal.php';
		require_once $path . 'lib/RevampCRM/Models/DealByStageGroupViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealByStageViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealByTimeViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealCSVViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealEditViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealSearchParametersViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealsListViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealsListWithFiltersViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealStageViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealTimeLineGroupedViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/DealViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/EcommerceCategoryResponse.php';
		require_once $path . 'lib/RevampCRM/Models/ECommerceCategoryApiModel.php';
		require_once $path . 'lib/RevampCRM/Models/ECommerceCheckOutLineApiModel.php';
		require_once $path . 'lib/RevampCRM/Models/EcommerceCheckOutResponse.php';
		require_once $path . 'lib/RevampCRM/Models/ECommerceCheckOutApiModel.php';
		require_once $path . 'lib/RevampCRM/Models/ECommerceOrderCampaignApiModel.php';
		require_once $path . 'lib/RevampCRM/Models/EcommerceOrderResponse.php';
		require_once $path . 'lib/RevampCRM/Models/EcommerceOrdersSummary.php';
		require_once $path . 'lib/RevampCRM/Models/ECommerceOrderApiModel.php';
		require_once $path . 'lib/RevampCRM/Models/EcommerceProductResponse.php';
		require_once $path . 'lib/RevampCRM/Models/ECommerceProductVariant.php';
		require_once $path . 'lib/RevampCRM/Models/ECommerceProductApiModel.php';
		require_once $path . 'lib/RevampCRM/Models/EcommerceStoreResponse.php';
		require_once $path . 'lib/RevampCRM/Models/ECommerceStoreApiModel.php';
		require_once $path . 'lib/RevampCRM/Models/ExternalProvider.php';
		require_once $path . 'lib/RevampCRM/Models/FollowUpTask.php';
		require_once $path . 'lib/RevampCRM/Models/GroupMembership.php';
		require_once $path . 'lib/RevampCRM/Models/GroupMembershipViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/HttpPostedFileBase.php';
		require_once $path . 'lib/RevampCRM/Models/ImportResultViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/Note.php';
		require_once $path . 'lib/RevampCRM/Models/Object.php';
		require_once $path . 'lib/RevampCRM/Models/OptionGroupViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/PagerViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/RelatedCounts.php';
		require_once $path . 'lib/RevampCRM/Models/ReminderPagedViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/SearchParametersViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/SelectViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/SettingsMenuViewModel.php';
		require_once $path . 'lib/RevampCRM/Models/Stream.php';
		require_once $path . 'lib/RevampCRM/Models/UpdateDealStatusModel.php';

		require_once $path . 'lib/ApiClient.php';
		require_once $path . 'lib/ApiException.php';
		require_once $path . 'lib/Configuration.php';
		require_once $path . 'lib/ObjectSerializer.php';
		require_once $path . 'lib/RevampCRM/API/AccountApi.php';
		require_once $path . 'lib/RevampCRM/API/AccountMessagesApi.php';
		require_once $path . 'lib/RevampCRM/API/AccountOptionsApi.php';
		require_once $path . 'lib/RevampCRM/API/AccountRemindersApi.php';
		require_once $path . 'lib/RevampCRM/API/ContactsApi.php';
		require_once $path . 'lib/RevampCRM/API/DealsApi.php';
		require_once $path . 'lib/RevampCRM/API/EcommerceCategoriesApi.php';
		require_once $path . 'lib/RevampCRM/API/EcommerceCheckOutsApi.php';
		require_once $path . 'lib/RevampCRM/API/EcommerceOrdersApi.php';
		require_once $path . 'lib/RevampCRM/API/EcommerceProductsApi.php';
		require_once $path . 'lib/RevampCRM/API/EcommerceStoresApi.php';
		require_once $path . 'lib/RevampCRM/API/NoteApi.php';
		require_once $path . 'lib/RevampCRM/API/NotesApi.php';
		/** Require all the api error helpers */
		require_once $path . 'includes/api/errors/class-revampcrm-error.php';
		require_once $path . 'includes/api/errors/class-revampcrm-server-error.php';

		/** Require the various helper scripts */
		require_once $path . 'includes/api/helpers/class-revampcrm-woocommerce-api-currency-codes.php';

		/** Background job sync tools */

		// make sure the queue exists first since the other files depend on it.
		require_once $path . 'includes/vendor/queue.php';

		// the abstract bulk sync class
		// TODO:  WM-TODO ..  replace with Revamp Actual Objects
		require_once $path.'includes/processes/class-revampcrm-woocommerce-abstract-sync.php';

		// bulk data sync
		// TODO:  WM-TODO ..  replace with Revamp Actual Objects
		require_once $path.'includes/processes/class-revampcrm-woocommerce-process-orders.php';
		require_once $path.'includes/processes/class-revampcrm-woocommerce-process-products.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-revampcrm-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-revampcrm-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-revampcrm-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-revampcrm-woocommerce-public.php';
		
		$this->loader = new Revampcrm_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Revampcrm_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.9.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Revampcrm_Woocommerce_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.9.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Revampcrm_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add menu item
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php');
		$this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

		// make sure we're listening for the admin init
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');

		// put the menu on the admin top bar.
		//$this->loader->add_action('admin_bar_menu', $plugin_admin, 'admin_bar', 100);

		$this->loader->add_action('plugins_loaded', $plugin_admin, 'update_db_check');

		// The Hourly sync action
		$this->loader->add_action('revampcrm_sync_task_hook', $plugin_admin, 'scheduled_sync_task');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.9.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Revampcrm_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

/**
	 * Handle all the service hooks here.
	 */
	private function activateRevampCRMService()
	{
		$service = new RevampCRM_Service();

		if ($service->isConfigured()) {

			$service->setEnvironment($this->environment);
			$service->setVersion($this->version);

			// core hook setup
			$this->loader->add_action('admin_init', $service, 'adminReady');
			$this->loader->add_action('woocommerce_init', $service, 'wooIsRunning');
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.9.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.9.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.9.0
	 * @return    Revampcrm_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.9.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
