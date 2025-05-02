<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
    <?php wp_nonce_field( 'wc_bogo_flash_sale_save', 'wc_bogo_flash_sale_nonce' ); ?>
    <input type="hidden" name="action" value="save_flash_sale_settings">

    <p><?php esc_html_e( 'Discount options go here.', 'wc-bogo' ); ?></p>

    <label for="discount_based_on">
    <table style="width:50%">
        <tr>
            <td> 
                <label><strong> Choose Do you need Discount Based on Regular Price or Sale Price ? </strong>  </label>
            </td>
            <td>
                <select id="discount_based_on" name="discount_based_on">
                    <option value="regular_price" <?php selected( $selected_discount_type, 'regular_price' ); ?>>
                        <?php esc_html_e( 'Regular Price', 'wc-bogo' ); ?>
                    </option>
                    <option value="sales_price" <?php selected( $selected_discount_type, 'sales_price' ); ?>>
                        <?php esc_html_e( 'Sales Price', 'wc-bogo' ); ?>
                    </option>
                </select>
            </td>
            </label>
            </tr>
            <table style="width:50%">
            <tr><td>

            <label><strong> Minimum price to avial Discount </strong>  </label> </td>
            <td> <input type="number" name="min_price_for_discount" value="<?php echo esc_attr($min_price_for_discount); ?>" style="width: 60px;" />
            <br><br></td>
            </tr>
            </table>
    <style>
table, th, td {
  border:1px solid black;
}
</style>
<br><br>
    <div class="save-container">
        <button type="submit" class="button button-primary tab-3">
            <?php esc_html_e( 'Save Flash Sale Settings', 'wc-bogo' ); ?>
        </button>
        <button type="reset" class="button" style="margin-left: 10px;">
            <?php esc_html_e( 'Reset', 'wc-bogo' ); ?>
        </button>
    </div>
</form>