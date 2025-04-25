<?php
// 2. Register Toggle Fields


?>
<div class="bogo-toggle-wrapper">
    <label for="enable_flash_sale" class="bogo-toggle-label">
        <?php esc_html_e('Enable Flash Sale', 'wc-bogo'); ?>
    </label>
    <label class="bogo-switch">
        <input type="checkbox" id="enable_flash_sale" name="enable_flash_sale" value="yes" <?php checked(get_option('enable_flash_sale'), 'yes'); ?>>
        <span class="bogo-slider"></span>
    </label>
</div>

