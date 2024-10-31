


<?php

if (isset($options['revampcrm_api_key']) && !$handler->hasValidApiKey()) {
    include_once __DIR__.'/errors/missing_api_key.php';
}

?>
<input type="hidden" name="revampcrm_active_settings_tab" value="api_access"/>

<h2 style="padding-top: 1em;">API Information</h2>
<p>To find your Revamp CRM API key, log into your account and copy your API key from <a href="https://app.revampcrm.com/UserUI/ApiKey" target="_blank">API Key</a> screen. </p>

<!-- remove some meta and generators from the <head> -->
<fieldset>
    <legend class="screen-reader-text">
        <span>Revamp CRM Account Email</span>
    </legend>
    <label for="<?php echo $this->plugin_name; ?>-revampcrm-account-info-email">
        <input style="width: 30%;" type="text" id="<?php echo $this->plugin_name; ?>-revampcrm-account-info-email" name="<?php echo $this->plugin_name; ?>[revampcrm_account_info_email]" value="<?php echo isset($options['revampcrm_account_info_email']) ? $options['revampcrm_account_info_email'] : '' ?>" />
        <span><?php esc_attr_e('Enter your Revamp CRM Account Email.', $this->plugin_name); ?></span>
    </label>
</fieldset>
<fieldset>
    <legend class="screen-reader-text">
        <span>Revamp CRM API Key</span>
    </legend>
    <label for="<?php echo $this->plugin_name; ?>-revampcrm-api-key">
        <input style="width: 30%;" type="password" id="<?php echo $this->plugin_name; ?>-revampcrm-api-key" name="<?php echo $this->plugin_name; ?>[revampcrm_api_key]" value="<?php echo isset($options['revampcrm_api_key']) ? $options['revampcrm_api_key'] : '' ?>" />
        <span><?php esc_attr_e('Enter your Revamp CRM API key.', $this->plugin_name); ?></span>
    </label>
</fieldset>
<fieldset>
    <legend class="screen-reader-text">
        <span>Enable Debugging</span>
    </legend>
    <label for="<?php echo $this->plugin_name; ?>-revampcrm-debugging">
        <input  type="checkbox" id="<?php echo $this->plugin_name; ?>-revampcrm-debugging" name="<?php echo $this->plugin_name; ?>[revampcrm_debugging]" value="1" <?php echo isset($options['revampcrm_debugging'])  && $options['revampcrm_debugging']? "checked": '' ?> />
        <span><?php esc_attr_e('Used for advanced debugging with logging plugins.', $this->plugin_name); ?></span>
    </label>
</fieldset>