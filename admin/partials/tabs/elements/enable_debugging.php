<fieldset>
    <legend class="screen-reader-text">
        <span>Enable Debugging</span>
    </legend>
    <label for="<?php echo $this->plugin_name; ?>-revampcrm-debugging">
        <select name="<?php echo $this->plugin_name; ?>[revampcrm_debugging]" style="width:30%">

            <?php

            $enable_revampcrm_debugging = (array_key_exists('revampcrm_debugging', $options) && !is_null($options['revampcrm_debugging'])) ? $options['revampcrm_debugging'] : '1';

            foreach (array('0' => 'No', '1' => 'Yes') as $key => $value ) {
                echo '<option value="' . esc_attr($key) . '" ' . selected($key == $enable_revampcrm_debugging, true, false ) . '>' . esc_html( $value ) . '</option>';
            }
            ?>

        </select>
        <span><?php esc_attr_e('Enable debugging logs to be sent to Revamp CRM.', $this->plugin_name); ?></span>
    </label>
</fieldset>
