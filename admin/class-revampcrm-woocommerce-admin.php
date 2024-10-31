<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.revampcrm.com
 * @since      0.9.0
 *
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Revampcrm_Woocommerce
 * @subpackage Revampcrm_Woocommerce/admin
 * @author     Revamp CRM <crm@revampco.com>
 */
class Revampcrm_Woocommerce_Admin extends RevampCRM_Woocommerce_Options{

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.9.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.9.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	public static function connect()
	{
		$env = revampcrm_environment_variables();

		return new self('revampcrm-woocommerce', $env->version);
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.9.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.9.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Revampcrm_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Revampcrm_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/revampcrm-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.9.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Revampcrm_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Revampcrm_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/revampcrm-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    0.9.0
	 */

	public function add_plugin_admin_menu() {
		/*
         *  Documentation : http://codex.wordpress.org/Administration_Menus
         */
		add_options_page( 'Revamp CRM - WooCommerce Setup', 'Revamp CRM', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    0.9.0
	 */
	public function add_action_links($links) {
		/*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);

		return array_merge($settings_link, $links);
	}

	/**
	 * Admin bar
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function admin_bar( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$wp_admin_bar->add_menu( array(
			'id'    => 'revampcrm-woocommerce',
			'title' => __('Revamp CRM', 'revampcrm-woocommerce' ),
			'href'  => '#',
		));
		$wp_admin_bar->add_menu( array(
			'parent' => 'revampcrm-woocommerce',
			'id'     => 'revampcrm-woocommerce-api-access',
			'title'  => __('API Access', 'revampcrm-woocommerce' ),
			'href'   => wp_nonce_url(admin_url('options-general.php?page=revampcrm-woocommerce&tab=api_key'), 'mc-api-access'),
		));
		$wp_admin_bar->add_menu( array(
			'parent' => 'revampcrm-woocommerce',
			'id'     => 'revampcrm-woocommerce-store-info',
			'title'  => __('Store Info', 'revampcrm-woocommerce' ),
			'href'   => wp_nonce_url(admin_url('options-general.php?page=revampcrm-woocommerce&tab=store_info'), 'mc-store-info'),
		));


		// only display this button if the data is not syncing and we have a valid api key
		if ((bool) $this->getData('sync.syncing', false) === false) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'revampcrm-woocommerce',
				'id'     => 'revampcrm-woocommerce-sync',
				'title'  => __('Sync', 'revampcrm-woocommerce'),
				'href'   => wp_nonce_url(admin_url('?revampcrm-woocommerce[action]=sync&revampcrm-woocommerce[action]=sync'), 'mc-sync'),
			));
		}
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    0.9.0
	 */
	public function display_plugin_setup_page() {
		include_once( 'partials/revampcrm-woocommerce-admin-tabs.php' );
	}

	/**
	 *
	 */
	public function options_update() {

		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	/**
	 * Depending on the version we're on we may need to run some sort of migrations.
	 */
	public function update_db_check() {
		// grab the current version set in the plugin variables
		$version = revampcrm_environment_variables()->version;

		// grab the saved version or default to 1.0.3 since that's when we first did this.
		$saved_version = get_site_option('revampcrm_woocommerce_version', '1.0.3');

		// if the saved version is less than the current version
		if (version_compare($version, $saved_version) > 0) {
			// resave the site option so this only fires once.
			update_site_option('revampcrm_woocommerce_version', $version);
		}
	}

	public function scheduled_sync_task(){
		$this->startSync();
	}

	/**
	 * @param $input
	 * @return array
	 */
	public function validate($input) {

		$active_tab = isset($input['revampcrm_active_tab']) ? $input['revampcrm_active_tab'] : null;

		if (empty($active_tab)) {
			return $this->getOptions();
		}

		switch ($active_tab) {

			case 'api_access':
				$data = $this->validatePostApiKey($input);
				break;

			case 'store_info':
				$data = $this->validatePostStoreInfo($input);
				break;

			case 'sync':
				$this->startSync($_POST['submit'] == "Resync Everything");
				$this->showSyncStartedMessage();
                $this->setData('sync.config.resync', true);
				break;
		}

		return (isset($data) && is_array($data)) ? array_merge($this->getOptions(), $data) : $this->getOptions();
	}

	/**
	 * STEP 1.
	 *
	 * Handle the 'api_key' tab post.
	 *
	 * @param $input
	 * @return array
	 */
	protected function validatePostApiKey($input)
	{
		$data = array(
			'revampcrm_account_info_email' => isset($input['revampcrm_account_info_email']) ?$input['revampcrm_account_info_email']:false,
			'revampcrm_api_key' => isset($input['revampcrm_api_key']) ? $input['revampcrm_api_key'] : false,
			'revampcrm_debugging' => isset($input['revampcrm_debugging']) ? $input['revampcrm_debugging'] : false,
			'revampcrm_account_info_id' => null,
			'revampcrm_account_info_username' => null,
		);

		$api = new RevampCRM_WooCommerce_RevampCRMApi($data['revampcrm_account_info_email'], $data['revampcrm_api_key']);

		$valid = true;

		if (empty($data['revampcrm_api_key']) || empty($data['revampcrm_account_info_email'])|| !($profile = $api->ping(true))) {
			unset($data['revampcrm_api_key']);
			$valid = false;
		}

		// tell our reporting system whether or not we had a valid ping.
		$this->setData('validation.api.ping', $valid);

		$data['active_tab'] = $valid ? 'store_info' : 'api_access';

		if ($valid && isset($profile) && is_array($profile) && array_key_exists('account_id', $profile)) {
			$data['revampcrm_account_info_id'] = $profile['account_id'];
			$data['revampcrm_account_info_username'] = $profile['username'];
		}

		return $data;
	}

	/**
	 * STEP 2.
	 *
	 * Handle the 'store_info' tab post.
	 *
	 * @param $input
	 * @return array
	 */
	protected function validatePostStoreInfo($input)
	{
		$data = $this->compileStoreInfoData($input);
		if (!$this->hasValidStoreInfo($data)) {
		    if ($this->hasInvalidStoreAddress($data)) {
		        $this->addInvalidAddressAlert();
            }

            if ($this->hasInvalidStorePhone($data)) {
		        $this->addInvalidPhoneAlert();
            }

            if ($this->hasInvalidStoreName($data)) {
		        $this->addInvalidStoreNameAlert();
            }

			$this->setData('validation.store_info', false);

            $data['active_tab'] = 'store_info';
			return array();
		}
		$this->setData('validation.store_info', true);

        $data['active_tab'] = 'sync';
		//if ($this->hasValidRevampCRMStore()) {
			$this->syncStore(array_merge($this->getOptions(), $data));

			// start the sync automatically if the sync is false
			if ((bool) $this->getData('sync.started_at', false) === false) {
				$this->startSync(true);
				$this->showSyncStartedMessage();
			}

            $data['active_tab'] = 'sync';
		//}

		return $data;
	}

	/**
	 * @param null|array $data
	 * @return bool
	 */
	public function hasValidApiKey($data = null)
	{
		if (!$this->validateOptions(array('revampcrm_api_key','revampcrm_account_info_email'), $data)) {
			return false;
		}

		if (($pinged = $this->getCached('api-ping-check', null)) === null) {
			
			if (($pinged = $this->api()->ping())) {
				$this->setCached('api-ping-check', true, 120);
			}
			return $pinged;
		}
		return $pinged;
	}

	/**
	 * @param null|array $data
	 * @return bool
	 */
	public function hasValidStoreInfo($data = null)
	{
		return $this->validateOptions(array(
			'store_name', 'store_street', 'store_city', 'store_state',
			'store_postal_code', 'store_country', 'store_phone',
			/*'store_locale',*/ 'store_timezone',/* 'store_currency_code',*/
			'store_phone'
			//, 'store_id'
		), $data);
	}

    /**
     * @param $input
     * @return array
     */
	protected function compileStoreInfoData($input)
    {
        return array(
            // store basics
            'store_name' => trim((isset($input['store_name']) ? $input['store_name'] : get_option('blogname'))),
            'store_street' => isset($input['store_street']) ? $input['store_street'] : false,
            'store_city' => isset($input['store_city']) ? $input['store_city'] : false,
            'store_state' => isset($input['store_state']) ? $input['store_state'] : false,
            'store_postal_code' => isset($input['store_postal_code']) ? $input['store_postal_code'] : false,
            'store_country' => isset($input['store_country']) ? $input['store_country'] : false,
            'store_phone' => isset($input['store_phone']) ? $input['store_phone'] : false,
            // locale info
            //'store_locale' => isset($input['store_locale']) ? $input['store_locale'] : false,
            'store_timezone' => isset($input['store_timezone']) ? $input['store_timezone'] : false,
            //'store_currency_code' => isset($input['store_currency_code']) ? $input['store_currency_code'] : false,
            'admin_email' => isset($input['admin_email']) && is_email($input['admin_email']) ? $input['admin_email'] : $this->getOption('admin_email', false),
        );
    }

    /**
     * @param array $data
     * @return array|bool
     */
	protected function hasInvalidStoreAddress($data)
    {
        $address_keys = array(
            'admin_email',
            'store_city',
            'store_state',
            'store_postal_code',
            'store_country',
            'store_street'
        );

        $invalid = array();
        foreach ($address_keys as $address_key) {
            if (empty($data[$address_key])) {
                $invalid[] = $address_key;
            }
        }
        return empty($invalid) ? false : $invalid;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function hasInvalidStorePhone($data)
    {
        if (empty($data['store_phone']) || strlen($data['store_phone']) <= 6) {
            return true;
        }

        return false;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function hasInvalidStoreName($data)
    {
        if (empty($data['store_name'])) {
            return true;
        }
        return false;
    }

    /**
     *
     */
	protected function addInvalidAddressAlert()
    {
        add_settings_error('revampcrm_store_settings', '', 'As part of the Revamp CRM Terms of Use, we require a contact email and a physical mailing address.');
    }

    /**
     *
     */
    protected function addInvalidPhoneAlert()
    {
        add_settings_error('revampcrm_store_settings', '', 'As part of the Revamp CRM Terms of Use, we require a valid phone number for your store.');
    }

    /**
     *
     */
    protected function addInvalidStoreNameAlert()
    {
        add_settings_error('revampcrm_store_settings', '', 'Revamp CRM for WooCommerce requires a Store Name to connect your store.');
    }

	/**
	 * @param array $required
	 * @param null $options
	 * @return bool
	 */
	private function validateOptions(array $required, $options = null)
	{
		$options = is_array($options) ? $options : $this->getOptions();

		foreach ($required as $requirement) {
			if (!isset($options[$requirement]) || empty($options[$requirement])) {
				return false;
			}
		}

		return true;
	}


	/**
	 * @param null $data
	 * @return bool
	 */
	private function syncStore($data = null)
	{
		if (empty($data)) {
			$data = $this->getOptions();
		}

		$site_url = $this->getUniqueStoreID();

		$new = false;

		if (!($store = $this->api()->getStore($site_url))) {
			$new = true;
			$store = new \RevampCRM\Models\ECommerceStoreApiModel();
		}

/*
        '_id' => 'setId',
        'account_id' => 'setAccountId',
        'contact_id' => 'setContactId',
        'created_on' => 'setCreatedOn',
        'updated_on' => 'setUpdatedOn',
        'title' => 'setTitle',
        'description' => 'setDescription',
        'access_token' => 'setAccessToken',
        //'provider_name' => 'setProviderName',
        //'store_id' => 'setStoreId',
        'last_shopify_sync_date' => 'setLastShopifySyncDate',
        //'store_email' => 'setStoreEmail',
        //'store_domain' => 'setStoreDomain',
        'uninstall' => 'setUninstall',
        'province_name' => 'setProvinceName',
        'province_code' => 'setProvinceCode',
        'address1' => 'setAddress1',
        'zip' => 'setZip',
        'city' => 'setCity',
        'store_phone' => 'setStorePhone',
        'country_code' => 'setCountryCode',
        'country_name' => 'setCountryName',
        'store_display_name' => 'setStoreDisplayName',
        'store_address' => 'setStoreAddress',
        'is_deleted' => 'setIsDeleted'

*/
		$call = $new ? 'addStore' : 'updateStore';
		$time_key = $new ? 'store_created_at' : 'store_updated_at';
		$store->setStoreId($site_url);
		$store->setStoreName($site_url);
		$store->setProviderName('WooCommerce');

		// set the locale data
		//$store->setPrimaryLocale($this->array_get($data, 'store_locale', 'en'));
		$store->setTimezone($this->array_get($data, 'store_timezone', 'America\New_York'));
		//$store->setCurrencyCode($this->array_get($data, 'store_currency_code', 'USD'));

		// set the basics
		$store->setStoreDisplayName($this->array_get($data, 'store_name'));
		$store->setStoreDomain(get_option('siteurl'));

        // don't know why we did this before
        //$store->setEmailAddress($this->array_get($data, 'campaign_from_email'));
        $store->setStoreEmail($this->array_get($data, 'admin_email'));

		$store->setAddress1($this->array_get($data, 'store_street'));
		$store->setCity($this->array_get($data, 'store_city'));
		$store->setProvinceName($this->array_get($data, 'store_state'));
		$store->setZip($this->array_get($data, 'store_postal_code'));
		$store->setCountryCode($this->array_get($data, 'store_country'));
		$store->setStorePhone($this->array_get($data, 'store_phone'));

		$store->setStorePhone($this->array_get($data, 'store_phone'));

		try {
			// let's create a new store for this user through the API
			$this->api()->$call($store);

			// apply extra meta for store created at
			$this->setData('errors.store_info', false);
			$this->setData($time_key, time());

			return true;

		} catch (\Exception $e) {
			$this->setData('errors.store_info', $e->getMessage());
		}

		return false;
	}

	/**
	 * @return array|bool|mixed|null
	 */
	public function getAccountDetails()
	{
		if (!$this->hasValidApiKey()) {
			return false;
		}

		try {
			if (($account = $this->getCached('api-account-name', null)) === null) {
				if (($account = $this->api()->getProfile())) {
					$this->setCached('api-account-name', $account, 120);
				}
			}
			return $account;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Start the sync
	 */
	private function startSync($force_all_sync=false)
	{
		$job = new RevampCRM_WooCommerce_Process_Products($force_all_sync);
		$job->flagStartSync();
		wp_queue($job, 10);
		if ( ! wp_next_scheduled( 'revampcrm_sync_task_hook' ) ) {
			// Schedule the call in cron to the same sync job
			wp_schedule_event( time()+(1.5*3600), 'twicedaily', 'revampcrm_sync_task_hook' );
		}
	}

	/**
	 * Show the sync started message right when they sync things.
	 */
	private function showSyncStartedMessage()
	{
		$text = 'Starting the sync process…<br/>'.
			'<p id="sync-status-message">Please hang tight while we sync is running. Sometimes the sync can take a while, '.
			'especially on sites with lots of orders and/or products. You may refresh this page at '.
			'anytime to check on the progress.</p>';

		add_settings_error('revampcrm-woocommerce_notice', $this->plugin_name, __($text), 'updated');
	}

	/**
	 * Show the sync started message right when they sync things.
	 */
	private function showSyncRunningMessage()
	{
		$text = 'Sync process is running…<br/>'.
			'<p id="sync-status-message">Please hang tight while we sync is running. Sometimes the sync can take a while, '.
			'especially on sites with lots of orders and/or products. You may refresh this page at '.
			'anytime to check on the progress.</p>';

		add_settings_error('revampcrm-woocommerce_running_notice', $this->plugin_name, __($text), 'updated');
	}
}
