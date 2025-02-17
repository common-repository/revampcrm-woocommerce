<?php
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'api_access';
$is_revampcrm_post = isset($_POST['revampcrm_woocommerce_settings_hidden']) && $_POST['revampcrm_woocommerce_settings_hidden'] === 'Y';

$handler = Revampcrm_Woocommerce_Admin::connect();

//Grab all options for this particular tab we're viewing.
$options = get_option($this->plugin_name, array());

if (!isset($_GET['tab']) && isset($options['active_tab'])) {
    $active_tab = $options['active_tab'];
}

$show_sync_tab = isset($_GET['resync']) ? $_GET['resync'] === '1' : false;;
$show_campaign_defaults = true;
$has_valid_api_key = false;
$allow_new_list = true;

$clicked_sync_button = $is_revampcrm_post&& $active_tab == 'sync';

if (isset($options['revampcrm_api_key']) && $handler->hasValidApiKey($options)) {
    $has_valid_api_key = true;

    // only display this button if the data is not syncing and we have a valid api key
    if ((bool) $this->getData('sync.started_at', false)) {
        $show_sync_tab = true;
    }
}
?>

<style>
    #sync-status-message strong {
        font-weight:inherit;
    }
</style>

<?php if (!defined('PHP_VERSION_ID') || (PHP_VERSION_ID < 50600)): ?>
    <div data-dismissible="notice-php-version" class="error notice notice-error is-dismissible">
        <p><?php _e('Revamp CRM says: Please upgrade your PHP version to a minimum of 5.6', 'revampcrm-woocommerce'); ?></p>
    </div>
<?php endif; ?>

<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2>Revamp CRM Settings</h2>

    <h2 class="nav-tab-wrapper">
        <a href="?page=revampcrm-woocommerce&tab=api_access" class="nav-tab <?php echo $active_tab == 'api_key' ? 'nav-tab-active' : ''; ?>">Connect</a>
        <?php if($has_valid_api_key): ?>
        <a href="?page=revampcrm-woocommerce&tab=store_info" class="nav-tab <?php echo $active_tab == 'store_info' ? 'nav-tab-active' : ''; ?>">Store Settings</a>
        <?php if ($handler->hasValidStoreInfo()) : ?>
        <?php if($show_sync_tab): ?>
        <a href="?page=revampcrm-woocommerce&tab=sync" class="nav-tab <?php echo $active_tab == 'sync' ? 'nav-tab-active' : ''; ?>">Sync</a>
        <?php endif; ?>
        <?php endif;?>
        <?php endif; ?>
    </h2>

    <form method="post" name="cleanup_options" action="options.php">

        <input type="hidden" name="revampcrm_woocommerce_settings_hidden" value="Y">

        <?php
        if (!$clicked_sync_button) {
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
            //settings_errors();
            include('tabs/notices.php');
        }
        ?>

        <input type="hidden" name="<?php echo $this->plugin_name; ?>[revampcrm_active_tab]" value="<?php echo $active_tab; ?>"/>

        <?php if( $active_tab == 'api_access' ): ?>
            <?php include_once 'tabs/api_access.php'; ?>
        <?php endif; ?>

        <?php if( $active_tab == 'store_info' && $has_valid_api_key): ?>
            <?php include_once 'tabs/store_info.php'; ?>
        <?php endif; ?>
        <?php if( $active_tab == 'sync' && $show_sync_tab): ?>
            <?php include_once 'tabs/store_sync.php'; ?>
        <?php endif; ?>

        <?php if ($active_tab !== 'sync') submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>

    <?php if ($active_tab == 'sync'): ?>
        <h2 style="padding-top: 1em;">More Information</h2>
        <p>
            Need help troubleshooting or connecting your store? Visit our RevampCRM Help Desk 
            <a href="http://help.revampcrm.com/">here </a>.
            
            <?php /*
            for WooCommerce
            <a href="http://kb.revampcrm.com/integrations/e-commerce/connect-or-disconnect-revampcrm-for-woocommerce/" target="_blank">knowledge base</a> at anytime. Also, be sure to
            <a href="https://wordpress.org/support/plugin/revampcrm-for-woocommerce/reviews/" target="_blank">leave a review</a> and let us know how we're doing.
            */ ?>
        </p>
    <?php endif; ?>

</div><!-- /.wrap -->
