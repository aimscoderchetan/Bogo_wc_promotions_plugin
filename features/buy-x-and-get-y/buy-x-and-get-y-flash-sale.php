<?php 

//  Flash Sales For Buy X and Get Y code goes here.
add_filter('woocommerce_sale_flash', 'custom_bogo_flash_message', 20, 3);
function custom_bogo_flash_message($original, $post, $product) {
    if ('product' !== $post->post_type) return $original;

    // Bail if feature is off
    // if ( 'yes' !== get_option( 'enable_flash_sal_buy_x_and_y', 'no' ) ) {
    //     return $original;
    // }

    $bogo_posts = get_posts([
        'post_type' => 'wc_bogo',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    foreach ($bogo_posts as $bogo_post) {
        $status = get_post_meta($bogo_post->ID, '_bogo_deal_status', true);
        if ($status !== 'yes') continue;

        $filter_type = get_post_meta($bogo_post->ID, '_wc_bogo_filter_type_cust_buy', true);

        $match = false;
        switch ($filter_type) {
            case 'product':
                $products = get_post_meta($bogo_post->ID, '_selected_products_cust_buy', true);
                $products = is_array($products) ? $products : explode(',', $products);
                $match = in_array($product->get_id(), $products);
                break;
            case 'category':
                $categories = get_post_meta($bogo_post->ID, '_selected_categories_cust_buy', true);
                $categories = is_array($categories) ? $categories : explode(',', $categories);
                $product_cats = wc_get_product_term_ids($product->get_id(), 'product_cat');
                $match = !empty(array_intersect($categories, $product_cats));
                break;
            case 'tags':
                $tags = get_post_meta($bogo_post->ID, '_selected_tags_cust_buy', true);
                $tags = is_array($tags) ? $tags : explode(',', $tags);
                $product_tags = wc_get_product_term_ids($product->get_id(), 'product_tag');
                $match = !empty(array_intersect($tags, $product_tags));
                break;
        }

        if (!$match) continue;

        // Get bonus product for display
        $bonus_ids = get_post_meta($bogo_post->ID, '_selected_products_cust_get', true);
        $bonus_ids = is_array($bonus_ids) ? $bonus_ids : explode(',', $bonus_ids);
        if (empty($bonus_ids)) continue;

        $bonus_product = wc_get_product($bonus_ids[0]);
        if (!$bonus_product) continue;

        $discount_type = get_post_meta($bogo_post->ID, '_discount_type_buy_xy', true);
        $discount_val = floatval(get_post_meta($bogo_post->ID, '_discount_value_buy_xy', true));
        $discount_text = 'FREE';

        if ($discount_type === 'percentage') {
            $discount_text = $discount_val . '% OFF';
        } elseif ($discount_type === 'fixed') {
            $discount_text = $discount_val . '$ OFF';
        }

        $bonus_img = wp_get_attachment_image_src($bonus_product->get_image_id(), 'thumbnail')[0];
        $bonus_name = $bonus_product->get_name();

        ob_start();
        ?>
        <!-- .woocommerce-product-gallery__wrapper after this we need this  -->
        <div class="bogo-flash-sale" style="border: 2px dashed #e63946; padding: 8px; background: #fff3f3; margin-bottom: 10px;">
            <strong>Buy this and get:</strong><br>
            <img src="<?php echo esc_url($bonus_img); ?>" style="width: 50px; height: auto; vertical-align: middle;" />
            <span><?php echo esc_html($bonus_name); ?> - <strong><?php echo esc_html($discount_text); ?></strong> </span>
        </div>
         <script type="text/javascript">
            jQuery(document).ready(function($) {
                var html = <?php echo json_encode($bogo_html); ?>;
                $('.wp-block-woocommerce-product-image-gallery').after(html);
            });
        </script>
        <?php
        return ob_get_clean();
    }

    return $original;
}
