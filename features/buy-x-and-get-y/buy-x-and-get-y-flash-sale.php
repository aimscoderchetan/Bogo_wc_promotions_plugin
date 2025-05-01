<?php 

add_filter('woocommerce_sale_flash', 'custom_bogo_flash_message', 20, 3);
function custom_bogo_flash_message($original, $post, $product) {
    if ('product' !== $post->post_type) return $original;

    $bogo_posts = get_posts([
        'post_type' => 'wc_bogo',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    if (empty($bogo_posts)) return $original;

    $product_id = $product->get_id();
    $enable_flash_sale = get_option('enable_flash_sal_buy_x_and_y', 'no');

    foreach ($bogo_posts as $bogo_post) {
        $status = get_post_meta($bogo_post->ID, '_bogo_deal_status', true);
        if ($status !== 'yes') continue;

        $filter_type = get_post_meta($bogo_post->ID, '_wc_bogo_filter_type_cust_buy', true);
        $match = false;

        switch ($filter_type) {
            case 'product':
                $products = (array) get_post_meta($bogo_post->ID, '_selected_products_cust_buy', true);
                $match = in_array($product_id, $products);
                break;

            case 'category':
                $categories = (array) get_post_meta($bogo_post->ID, '_selected_categories_cust_buy', true);
                $product_cats = wc_get_product_term_ids($product_id, 'product_cat');
                $match = !empty(array_intersect($categories, $product_cats));
                break;

            case 'tags':
                $tags = (array) get_post_meta($bogo_post->ID, '_selected_tags_cust_buy', true);
                $product_tags = wc_get_product_term_ids($product_id, 'product_tag');
                $match = !empty(array_intersect($tags, $product_tags));
                break;
        }

        if (!$match) continue;

        $bonus_ids = (array) get_post_meta($bogo_post->ID, '_selected_products_cust_get', true);
        if (empty($bonus_ids)) continue;

        $bonus_product = wc_get_product($bonus_ids[0]);
        if (!$bonus_product) continue;

        $discount_type = get_post_meta($bogo_post->ID, '_discount_type_buy_xy', true);
        $discount_val = floatval(get_post_meta($bogo_post->ID, '_discount_value_buy_xy', true));
        $min_qty = (int) get_post_meta($bogo_post->ID, '_min_qty_buy_xy', true);
        $free_qty = (int) get_post_meta($bogo_post->ID, '_free_qty_buy_xy', true);

        if ($min_qty <= 0 || $free_qty <= 0) continue;

        $msg_fixed      = get_option('buy_x_get_y_fixed_message', 'Buy [min_qty], get [free_qty] off!');
        $msg_percent    = get_option('buy_x_get_y_percentage_message', 'Buy [min_qty], get {discount_val}% off!');
        $msg_free       = get_option('buy_x_get_y_free_message', 'Buy [min_qty], get [free_qty] free!');

        $bonus_img = wp_get_attachment_image_src($bonus_product->get_image_id(), 'thumbnail')[0];
        $bonus_name = $bonus_product->get_name();


        $discount_type = get_post_meta($bogo_post->ID, '_discount_type_buy_xy', true);
        $discount_val = floatval(get_post_meta($bogo_post->ID, '_discount_value_buy_xy', true));
        $discount_text = 'FREE';

        if ($discount_type === 'percentage') {
            $discount_text = $discount_val . '% OFF';
        } elseif ($discount_type === 'fixed') {
            $discount_text = $discount_val . '$ OFF';
        }

        $bg_color = '#fff3f3';
        $font_color = '#e63946';
        $discount_label = '';
        $msg = '';

        switch ($discount_type) {
            case 'percentage':
                $discount_label = $discount_val . '% OFF';
                $msg = str_replace(
                    ['[min_qty]', '[free_qty]', '{discount_val}'],
                    [$min_qty, $free_qty, $discount_val],
                    $msg_percent
                );
                $bg_color = get_option('buy_x_get_y_percentage_bg_color', $bg_color);
                $font_color = get_option('buy_x_get_y_percentage_font_color', $font_color);
                break;

            case 'fixed':
                $discount_label = '$' . $discount_val . ' OFF';
                $msg = str_replace(
                    ['[min_qty]', '[free_qty]', '{discount_val}'],
                    [$min_qty, $free_qty, $discount_val],
                    $msg_fixed
                );
                $bg_color = get_option('buy_x_get_y_fixed_bg_color', $bg_color);
                $font_color = get_option('buy_x_get_y_fixed_font_color', $font_color);
                break;

            default:
                $discount_label = 'FREE';
                $msg = str_replace(
                    ['[min_qty]', '[free_qty]'],
                    [$min_qty, $free_qty],
                    $msg_free
                );
                $bg_color = get_option('buy_x_get_y_free_bg_color', $bg_color);
                $font_color = get_option('buy_x_get_y_free_font_color', $font_color);
                break;
        }

        if ($enable_flash_sale === 'yes') {
            ob_start(); ?>
            <div class="bogo-flash-sale" style="border: 2px dashed <?php echo esc_attr($font_color); ?>; padding: 8px; background: <?php echo esc_attr($bg_color); ?>; margin-bottom: 10px; color: <?php echo esc_attr($font_color); ?>;">
                <strong><?php echo esc_html($msg); ?></strong><br>
                <img src="<?php echo esc_url($bonus_img); ?>" style="width: 50px; height: auto; vertical-align: middle;" />
                <span><?php echo esc_html($bonus_name); ?> – <em><?php echo esc_html($discount_label); ?></em></span>
            </div>
            <?php
            return ob_get_clean();
        } else {
            // return $original; // Return the Woo default flash (e.g., “Sale!”)
            ?>
           <div class="bogo-flash-sale" style="border: 2px dashed #e63946; padding: 8px; background: #fff3f3; margin-bottom: 10px;">
            <strong>Buy this <?php echo $min_qty;?> product and get: <?php echo $free_qty;?></strong><br>
            <img src="<?php echo esc_url($bonus_img); ?>" style="width: 50px; height: auto; vertical-align: middle;" />
            <span><?php echo esc_html($bonus_name); ?> - <strong><?php echo esc_html($discount_text); ?></strong> </span>
        </div>
         <script type="text/javascript">
            jQuery(document).ready(function($) {
                var html = <?php echo json_encode($bogo_html); ?>;
                $('.wp-block-woocommerce-product-image-gallery').after(html);
            });
        </script>
     <?php    }
    }

    return $original;
}

