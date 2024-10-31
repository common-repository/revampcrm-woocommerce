<?php
$revampcrm_total_products = $revampcrm_total_orders = 0;
$store_id = revampcrm_get_store_id();
$product_count = revampcrm_get_product_count();
$order_count = revampcrm_get_order_count();
$store_syncing = false;
$last_updated_time = get_option('revampcrm-woocommerce-resource-last-updated');
$account_name = 'n/a';
$revampcrm_list_name = 'n/a';

if (!empty($last_updated_time)) {
    $last_updated_time = revampcrm_date_local($last_updated_time);
}

if (($revampcrm_api = revampcrm_get_api()) && ($store = $revampcrm_api->getStore($store_id))) {
    $store_syncing = $store->getIsSyncing();
    $account_name = revampcrm_get_option('revampcrm_account_info_email');
    // if (($account_details = $handler->getAccountDetails())) {
    //     $account_name = $account_details['account_name'];
    // }

    try {
        $revampcrm_total_products = $revampcrm_api->productsCount($store_id);
        if ($revampcrm_total_products > $product_count) $revampcrm_total_products = $product_count;
    } catch (\Exception $e) { $revampcrm_total_products = 0; }

    try {
        $revampcrm_total_orders = $revampcrm_api->ordersCount($store_id);
        if ($revampcrm_total_orders > $order_count) $revampcrm_total_orders = $order_count;
    } catch (\Exception $e) { $revampcrm_total_orders = 0; }

}
?>

<input type="hidden" name="revampcrm_active_settings_tab" value="store_sync"/>

<?php if($store_syncing): ?>
    <h2 style="padding-top: 1em;">Sync Progress</h2>
<?php endif; ?>

<?php if(!$store_syncing): ?>
    <h2 style="padding-top: 1em;">Sync Status</h2>
<?php endif; ?>
<style>
.sync-info-table{
    max-width:1110px;
}
.sync-info-table td.sync-info-label{
    vertical-align: top;
    width:12%;
}
.sync-info-table td.sync-info-data-cell{
    width: 28%;
}
.sync-info-table td.sync-info-note-cell{
    color:#999;
}
.store-sync-running-message{
    color:#666;
    display: block;
    padding: 10px;
    background-color: #fff;
    margin-top:20px;
}
</style>
<table class="sync-info-table">
    <tr>
        <td class="sync-info-label" >
            <strong>User Connected:</strong> 
        </td>
        <td class="sync-info-data-cell">
            <?php echo $account_name; ?>
        </td>
        <td class="sync-info-note-cell">&nbsp;</td>
    </tr>
    <tr>
        <td class="sync-info-label">
            <strong>Products:</strong> 
        </td>
        <td  class="sync-info-data-cell" >
            <?php echo $revampcrm_total_products; ?>/<?php echo $product_count; ?><br />
        </td>
        <td class="sync-info-note-cell">
            <strong>Categories</strong>  are synchronized with the related products.
        </td>
    </tr>
    

    <tr>    
        <td class="sync-info-label">
            <strong>Orders:</strong> 
        </td>
        <td  class="sync-info-data-cell">
            <?php echo $revampcrm_total_orders; ?>/<?php echo $order_count; ?>
        </td>
        <td class="sync-info-note-cell">
            <strong>Customers</strong>  are synchronized with the related orders.
        </td>
    </tr>
    <?php if ($last_updated_time): ?>
    <tr>
        <td class="sync-info-label">
            <strong>Last Updated:</strong>
        </td>
        <td  class="sync-info-data-cell">
            <i><?php echo $last_updated_time->format('D, M j, Y g:i A'); ?></i>
        </td>
        <td class="sync-info-note-cell">&nbsp;</td>
    </tr>
    <?php endif; ?>
    <tr>
        <td colspan=3>
            <?php if($revampcrm_api && (!$store_syncing || isset($_GET['resync']) && $_GET['resync'] === '1')): ?>
                <?php submit_button('Sync Now', 'primary','submit', TRUE); ?>
                <h2 style="padding-top: 1em;">Advanced</h2>
                <p>
                    You may sync your data again if necessary. When this is done, all ecommerce data will be resynced into your Revamp CRM Account - including products, customers and orders.
                </p>
                <?php submit_button('Resync Everything', 'secondary','submit', TRUE); ?>
        <?php endif; ?>

        <?php if($revampcrm_api && $store_syncing): ?>
                <p class="store-sync-running-message">
                Store synchronization is in progress. <br />Sometimes the sync can take a while, 
			    especially on sites with lots of orders and/or products. You may refresh this page at 
			    anytime to check on the progress.
                </p>
            <?php endif; ?>    
        </td>
    </tr>
</table>
