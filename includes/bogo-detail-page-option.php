<?php
// File: bogo-detail-page-option.php

// Add Metabox Start here
    function bogo_discount_meta_box() {
        add_meta_box(
            'bogo_discount_meta_box',
            'BOGO Discount Options',
            'bogo_discount_meta_box_callback',
            'wc_bogo',
            'normal',
            'high'
        );
    }
    add_action('add_meta_boxes', 'bogo_discount_meta_box');
// Add Metabox End here

// Metabox Callback Function Start Here 
    function bogo_discount_meta_box_callback($post) {
        wp_nonce_field('bogo_discount_meta_nonce', 'bogo_discount_meta_nonce');

        // Load saved data
        $bogo_discount_type = get_post_meta($post->ID, '_bogo_discount_type', true);
        $wc_bogo_filter_type = get_post_meta($post->ID, '_wc_bogo_filter_type', true);
        $min_qty = get_post_meta($post->ID, '_min_qty', true);  
        $max_qty = get_post_meta($post->ID, '_max_qty', true);
        $free_qty = get_post_meta($post->ID, '_free_qty', true);
        $discount_type = get_post_meta($post->ID, '_discount_type', true);
        $discount_value = get_post_meta($post->ID, '_discount_value', true);
        $recursive = get_post_meta($post->ID, '_recursive', true);
        
        // Buy X and Get X (same Product module) Start Here 
        // Retrieve saved products 
        $selected_products_raw = get_post_meta($post->ID, '_selected_products', true);
        $selected_products = (is_string($selected_products_raw) && !empty($selected_products_raw))
            ? explode(',', $selected_products_raw)
            : (is_array($selected_products_raw) ? $selected_products_raw : []);

        // Retrieve saved categories
        $selected_categories_raw = get_post_meta($post->ID, '_selected_categories', true);
        $selected_categories = is_string($selected_categories_raw) && !empty($selected_categories_raw)
            ? explode(',', $selected_categories_raw)
            : (is_array($selected_categories_raw) ? $selected_categories_raw : []);

        // Retrieve saved tags
        $selected_tags_raw = get_post_meta($post->ID, '_selected_tags', true);
        $selected_tags = is_string($selected_tags_raw) && !empty($selected_tags_raw)
            ? explode(',', $selected_tags_raw)
            : (is_array($selected_tags_raw) ? $selected_tags_raw : []);

        // Buy X and Get X (same Product module) Start Here 


        $min_qty_buy_xy = get_post_meta($post->ID, '_min_qty_buy_xy', true);  
        $max_qty_buy_xy = get_post_meta($post->ID, '_max_qty_buy_xy', true);
        $free_qty_buy_xy = get_post_meta($post->ID, '_free_qty_buy_xy', true);
        $discount_type_buy_xy = get_post_meta($post->ID, '_discount_type_buy_xy', true);
        $discount_value_buy_xy = get_post_meta($post->ID, '_discount_value_buy_xy', true);
        $recursive_buy_xy = get_post_meta($post->ID, '_recursive_buy_xy', true);

        // Buy X and Get Y Customer Buys here Start Here             
        $wc_bogo_filter_type_cust_buy = get_post_meta($post->ID, '_wc_bogo_filter_type_cust_buy', true);
        // Retrieve saved products 
        $selected_products_raw_cust_buy = get_post_meta($post->ID, '_selected_products_cust_buy', true);
        $selected_products_cust_buy = (is_string($selected_products_raw_cust_buy) && !empty($selected_products_raw_cust_buy))
            ? explode(',', $selected_products_raw_cust_buy)
            : (is_array($selected_products_raw_cust_buy) ? $selected_products_raw_cust_buy : []);

        // Retrieve saved categories
        $selected_categories_raw_cust_buy = get_post_meta($post->ID, '_selected_categories_cust_buy', true);
        $selected_categories_cust_buy = is_string($selected_categories_raw_cust_buy) && !empty($selected_categories_raw_cust_buy)
            ? explode(',', $selected_categories_raw_cust_buy)
            : (is_array($selected_categories_raw_cust_buy) ? $selected_categories_raw_cust_buy : []);

        // Retrieve saved tags
        $selected_tags_raw_cust_buy = get_post_meta($post->ID, '_selected_tags_cust_buy', true);
        $selected_tags_cust_buy = is_string($selected_tags_raw_cust_buy) && !empty($selected_tags_raw_cust_buy)
            ? explode(',', $selected_tags_raw_cust_buy)
            : (is_array($selected_tags_raw_cust_buy) ? $selected_tags_raw_cust_buy : []);

        // Buy X and Get Y Customer Buys here Start Here 

        // Buy X and Get Y Customer Gets here Start Here  

        $wc_bogo_filter_type_cust_get = get_post_meta($post->ID, '_wc_bogo_filter_type_cust_get', true);
        // Retrieve saved products 
        $selected_products_raw_cust_get = get_post_meta($post->ID, '_selected_products_cust_get', true);
        $selected_products_cust_get = (is_string($selected_products_raw_cust_get) && !empty($selected_products_raw_cust_get))
            ? explode(',', $selected_products_raw_cust_get)
            : (is_array($selected_products_raw_cust_get) ? $selected_products_raw_cust_get : []);

        // Retrieve saved categories
        $selected_categories_raw_cust_get = get_post_meta($post->ID, '_selected_categories_cust_get', true);
        $selected_categories_cust_get = is_string($selected_categories_raw_cust_get) && !empty($selected_categories_raw_cust_get)
            ? explode(',', $selected_categories_raw_cust_get)
            : (is_array($selected_categories_raw_cust_get) ? $selected_categories_raw_cust_get : []);

        // Retrieve saved tags
        $selected_tags_raw_cust_get = get_post_meta($post->ID, '_selected_tags_cust_get', true);
        $selected_tags_cust_get = is_string($selected_tags_raw_cust_get) && !empty($selected_tags_raw_cust_get)
            ? explode(',', $selected_tags_raw_cust_get)
            : (is_array($selected_tags_raw_cust_get) ? $selected_tags_raw_cust_get : []);

        // Buy X and Get Y Customer Gets here Start Here   

        $status = get_post_meta($post->ID, '_bogo_deal_status', true);

        // Check if the current time is between the start and end dates
        $start_date = get_post_meta($post->ID, '_bogo_start_date', true);
        $end_date   = get_post_meta($post->ID, '_bogo_end_date', true);

        // Setup timestamps
        $ist_timezone = new DateTimeZone('Asia/Kolkata');
        $current_time = new DateTime('now', $ist_timezone);
        $current_timestamp = $current_time->getTimestamp();

        $start_timestamp = !empty($start_date) ? strtotime($start_date) : 0;
        $end_timestamp   = !empty($end_date) ? strtotime($end_date) : 0;

        // Cart Adjustement global variable goes here
        $cart_discount_type = get_post_meta($post->ID, '_cart_discount_type', true);
        // Custom Message on Cart Page 
        $cart_discount_tooltip = get_post_meta($post->ID, '_cart_discount_tooltip', true);
        $cart_discount_value = get_post_meta($post->ID, '_cart_discount_value', true);
        $cart_max_discount_value = get_post_meta($post->ID, '_cart_max_discount_value', true);

        ?>

        <div class="main-class-option-bogo-dropdown">
            <label for="bogo_discount_type">Choose a Discount Type</label>
            <select id="bogo_discount_type" name="bogo_discount_type">
                <option value="not_selected" <?php selected($bogo_discount_type, 'not_selected'); ?>>Select Discount Type</option>
                <optgroup label="Simple Discount">
                    <!-- <option value="product_adjustment" <?php // selected($bogo_discount_type, 'product_adjustment'); ?>>Product Adjustment</option> -->
                    <option value="cart_adjustment" <?php selected($bogo_discount_type, 'cart_adjustment'); ?>>Cart Discount</option>
                    <!-- <option value="free_shipping" <?php // selected($bogo_discount_type, 'free_shipping'); ?>>Free Shipping</option> -->
                </optgroup>
                <!-- <optgroup label="Bulk Discount"> -->
                    <!-- <option value="bulk_discount" <?php // selected($bogo_discount_type, 'bulk_discount'); ?>>Bulk Discount</option> -->
                    <!-- <option value="set_discount" <?php // selected($bogo_discount_type, 'set_discount'); ?>>Bundle (Set) Discount</option> -->
                <!-- </optgroup> -->
                <optgroup label="BOGO Discount">
                    <option value="buy_x_get_x" <?php selected($bogo_discount_type, 'buy_x_get_x'); ?>>Buy X Get X</option>
                    <option value="buy_x_get_y" <?php selected($bogo_discount_type, 'buy_x_get_y'); ?>>Buy X Get Y</option>
                </optgroup>
            </select>
        </div>

       <?php // Show or hide the BOGO fields based on the status
        if ($status === 'yes') { ?>
            <div id="bogo_fields_container_buy_x_get_x" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        <div class="awdr-discount-container">
                            <h2 class="awdr-discount-heading">Filter By</h2>

                            <div class="awdr-discount-content">
                                 <p>Choose on which products the discount should be applied (This can be products/categories/SKU) </p>
                                 </div>
                            </div>

                            <div id="repeater-container">
                            <div class="repeater-group">
                                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin: 50px 0;">

                                    <select id="wc_bogo_filter_type" name="wc_bogo_filter_type">
                                        <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                        <option value="product" <?php selected($wc_bogo_filter_type, 'product'); ?>>Product</option>
                                        <option value="category" <?php selected($wc_bogo_filter_type, 'category'); ?>>Categories</option>
                                        <option value="tags" <?php selected($wc_bogo_filter_type, 'tags'); ?>>Tags</option>
                                    </select>

                                    
                                    <?php if ($wc_bogo_filter_type === 'product' || $wc_bogo_filter_type === 'all_products' ) { ?> 
                                        <!-- Product Section here Start here -->
                                        <select id="bogo_search_field" style="width: 300px;"></select>

                                        <div id="bogo_selected_products">
                                            <?php
                                            foreach ($selected_products as $product_id) {
                                                $product = get_post($product_id);
                                                $post_title = $product->post_title ?? null;
                                                echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product">X</button></div>';
                                            }
                                            ?>
                                        </div>

                                        <input type="hidden" id="selected_product_ids" name="selected_product_ids" value="<?php echo esc_attr(implode(',', $selected_products)); ?>" />
                                        
                                        <!-- Product Section here End here  -->
                                    <?php } elseif ($wc_bogo_filter_type === 'category') { ?>
                                         <!-- <p>Hello i am category here </p> -->
                                        <!-- category Section here Start here -->
                                        <!-- === Categories Section Start === -->
                                        <!-- <label><strong>Select Product Categories</strong></label> -->
                                        <select id="bogo_category_search_field" style="width: 300px;"></select>
                                        <div id="bogo_selected_categories">
                                            <?php
                                            foreach ($selected_categories as $term_id) {
                                                $term = get_term($term_id, 'product_cat');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_category_ids" name="selected_category_ids" value="<?php echo esc_attr(implode(',', $selected_categories)); ?>" />
                                        <!-- === Categories Section End === -->
                                        <!-- category Section here Start here -->
                                       
                                    <?php } elseif ($wc_bogo_filter_type === 'tags') { ?>
                                        <!-- <p>Hello i am tags here </p> -->
                                        <!-- tags Section here Start here -->
                                            <!-- === Tags Section Start === -->
                                                <!-- <label><strong>Select Product Tags</strong></label> -->
                                                <select id="bogo_tag_search_field" style="width: 300px;"></select>
                                                <div id="bogo_selected_tags">
                                                    <?php
                                                    foreach ($selected_tags as $term_id) {
                                                        $term = get_term($term_id, 'product_tag');
                                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag">X</button></div>';
                                                    }
                                                    ?>
                                                </div>
                                                <input type="hidden" id="selected_tag_ids" name="selected_tag_ids" value="<?php echo esc_attr(implode(',', $selected_tags)); ?>" />
                                            <!-- === Tags Section End === -->
                                        <!-- tags Section here Start here -->
                                    <?php } else { ?>
                                       <select id="bogo_search_field" style="width: 300px;"></select>

                                        <div id="bogo_selected_products">
                                            <?php
                                            foreach ($selected_products as $product_id) {
                                                $product = get_post($product_id);
                                                $post_title = $product->post_title ?? null;
                                                echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product">X</button></div>';
                                            }
                                            ?>
                                        </div>

                                        <input type="hidden" id="selected_product_ids" name="selected_product_ids" value="<?php echo esc_attr(implode(',', $selected_products)); ?>" />
                                    <?php } ?>
                                
                                <!-- <button type="button" class="remove-group" style="background: red; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Remove</button> -->
                                </div> 
                                <p style="color: red"> Pls update changes before switching Product, Category & Tags </p>
                            </div>
                            </div>      

                            <!-- <button type="button" id="add-repeater" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Add</button> -->

                        <div class="awdr-discount-container">
                            <h2 class="awdr-discount-heading">Discount Type</h2>
                            <div class="awdr-discount-content">
                                <p>Enter the min/max ranges and choose free item quantity.</p>
                                <p>Note: Enable recursive checkbox if the discounts should be applied in sequential ranges.</p>
                                <p>Example: Buy 1 get 1, Buy 2 get 2, Buy 3 get 3, and so on.</p>
                            </div>
                        </div>

                       
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 50px;">
                                <label>Min Qty</label>
                                <input type="number" name="min_qty" value="<?php echo esc_attr($min_qty); ?>" style="width: 60px;" />

                                <label>Max Qty</label>
                                <input type="number" name="max_qty" value="<?php echo esc_attr($max_qty); ?>" style="width: 60px;" />

                                <label>Free Qty</label>
                                <input type="number" name="free_qty" value="<?php echo esc_attr($free_qty); ?>" style="width: 60px;" />

                                <label for="discount_type">Discount Type</label>
                                <select name="discount_type" id="discount_type">
                                    <option value="select" >--Select--</option>
                                    <option value="free" <?php selected($discount_type, 'free'); ?>>Free</option>
                                    <option value="percentage" <?php selected($discount_type, 'percentage'); ?>>Percentage Discount</option>
                                    <option value="fixed" <?php selected($discount_type, 'fixed'); ?>>Fixed Amount</option>
                                </select>

                                <input type="number" name="discount_value" id="discount_value" value="<?php echo esc_attr($discount_value); ?>" placeholder="Discount value" style="width: 80px; display: <?php echo ($discount_type == 'percentage' || $discount_type == 'fixed') ? 'inline-block' : 'none'; ?>;" />

                                <label>Recursive?</label>
                                <input type="hidden" name="recursive" value="0" />
                                <input type="checkbox" name="recursive" value="1" <?php checked($recursive, 1); ?> />

                                <button id="custom-reset-button" type="button" style="background: #0073aa; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Reset</button>
                        </div>
            </div>

            <div id="bogo_fields_container_cart_adjustment" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <!-- hello chetan here -->
                <div class="awdr-discount-container" style="margin-top: 30px;">
                    <h2 class="awdr-discount-heading">Cart Discount Options</h2>
                    <div class="awdr-discount-content">
                        <p>Select the discount type and provide appropriate values.</p>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; margin-top: 20px;">
                        <label for="cart_discount_type"><strong>Discount Type</strong></label>
                        <select name="cart_discount_type" id="cart_discount_type">
                            <option value="">-- Select --</option>
                            <option value="fixed" <?php selected($cart_discount_type, 'fixed'); ?>>Fixed Discount</option>
                            <option value="percentage" <?php selected($cart_discount_type, 'percentage'); ?>>Percentage Discount</option>
                            <option value="fixed_per_item" <?php selected($cart_discount_type, 'fixed_per_item'); ?>>Fixed Per Item</option>
                        </select>

                        <!-- Discount Value -->
                        <input type="number" name="cart_discount_value" id="cart_discount_value"
                               value="<?php echo esc_attr($cart_discount_value); ?>"
                               placeholder="Discount Value"
                               style="width: 190px; display: <?php echo ($cart_discount_type == 'fixed' || $cart_discount_type == 'percentage' || $cart_discount_type == 'fixed_per_item') ? 'inline-block' : 'none'; ?>;" />

                        <!-- Max Discount Cap -->
                        <input type="number" name="cart_max_discount_value" id="cart_max_discount_value"
                               value="<?php echo esc_attr($cart_max_discount_value); ?>"
                               placeholder="Max Discount Cap"
                               style="width: 190px; display: <?php echo ($cart_discount_type == 'percentage' || $cart_discount_type == 'fixed_per_item') ? 'inline-block' : 'none'; ?>;" />
                        <!-- Tooltip Message -->                       
                    </div>
                </div>
                <div class="lefting-tooltip-msg">
                    <label for="cart_discount_tooltip"><h3>Tooltip on cart message</h3></label>
                    <textarea name="cart_discount_tooltip" id="cart_discount_tooltip" rows="3" cols="50"><?php echo esc_textarea($cart_discount_tooltip); ?></textarea>
                </div>
            </div>

            <div id="bogo_fields_container_buy_x_get_y" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <!-- <h2 class="awdr-discount-heading">Customer Buy </h2>                 -->
                            <h2> Customer Buys  </h2>

                <div id="repeater-container">
                    <div class="repeater-group">
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin: 50px 0;">

                            <select id="wc_bogo_filter_type_cust_buy" name="wc_bogo_filter_type_cust_buy">
                                <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                <option value="product" <?php selected($wc_bogo_filter_type_cust_buy, 'product'); ?>>Product</option>
                                <option value="category" <?php selected($wc_bogo_filter_type_cust_buy, 'category'); ?>>Categories</option>
                                <option value="tags" <?php selected($wc_bogo_filter_type_cust_buy, 'tags'); ?>>Tags</option>
                            </select>
                          
                            <?php if ($wc_bogo_filter_type_cust_buy === 'product') { ?> 

                                <!-- Product Section here Start here -->
                                <select id="bogo_search_field_cust_buy" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_buy">
                                    <?php
                                    foreach ($selected_products_cust_buy as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-buy">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_buy" name="selected_product_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_products_cust_buy)); ?>" />

                                <!-- Product Section here End here  -->
                            <?php } elseif ($wc_bogo_filter_type_cust_buy === 'category') { ?>
                                 <!-- <p>Hello i am category here </p> -->
                                <!-- category Section here Start here -->
                                <!-- === Categories Section Start === -->
                                <!-- <label><strong>Select Product Categories</strong></label> -->
                                <select id="bogo_category_search_field_cust_buy" style="width: 300px;"></select>
                                <div id="bogo_selected_categories_cust_buy">
                                    <?php
                                    foreach ($selected_categories_cust_buy as $term_id) {
                                        $term = get_term($term_id, 'product_cat');
                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category-buy">X</button></div>';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" id="selected_category_ids_cust_buy" name="selected_category_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_categories_cust_buy)); ?>" />
                                <!-- === Categories Section End === -->
                                <!-- category Section here Start here -->
                               
                            <?php } elseif ($wc_bogo_filter_type_cust_buy === 'tags') { ?>
                                <!-- <p>Hello i am tags here </p> -->
                                <!-- tags Section here Start here -->
                                    <!-- === Tags Section Start === -->
                                        <!-- <label><strong>Select Product Tags</strong></label> -->
                                        <select id="bogo_tag_search_field_cust_buy" style="width: 300px;"></select>
                                        <div id="bogo_selected_tags_cust_buy">
                                            <?php
                                            foreach ($selected_tags_cust_buy as $term_id) {
                                                $term = get_term($term_id, 'product_tag');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag-buy">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_tag_ids_cust_buy" name="selected_tag_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_tags_cust_buy)); ?>" />
                                    <!-- === Tags Section End === -->
                                <!-- tags Section here Start here -->
                            <?php } else { ?>
                                <h2> Customer Buys  </h2>
                                <select id="bogo_search_field_cust_buy" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_buy">
                                    <?php
                                    foreach ($selected_products_cust_buy as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-buy">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_buy" name="selected_product_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_products_cust_buy)); ?>" />

                            <?php } ?>


                              <h2> Customer Gets  </h2>

                            <select id="wc_bogo_filter_type_cust_get" name="wc_bogo_filter_type_cust_get">
                                <!-- <option value="">-- Select --</option> -->
                                <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                <option value="product" <?php selected($wc_bogo_filter_type_cust_get, 'product'); ?>>Product</option>
                                <option value="category" <?php selected($wc_bogo_filter_type_cust_get, 'category'); ?>>Categories</option>
                                <option value="tags" <?php selected($wc_bogo_filter_type_cust_get, 'tags'); ?>>Tags</option>
                            </select>


                            <?php if ($wc_bogo_filter_type_cust_get === 'product') { ?>

                             <!-- Product Section here Start here -->
                                <select id="bogo_search_field_cust_get" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_get">
                                    <?php
                                    foreach ($selected_products_cust_get as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-get">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_get" name="selected_product_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_products_cust_get)); ?>" />

                                <?php } elseif ($wc_bogo_filter_type_cust_get === 'category') { ?>
                                 <!-- <p>Hello i am category here </p> -->
                                <!-- category Section here Start here -->
                                <!-- === Categories Section Start === -->
                                <!-- <label><strong>Select Product Categories</strong></label> -->
                                <select id="bogo_category_search_field_cust_get" style="width: 300px;"></select>
                                <div id="bogo_selected_categories_cust_get">
                                    <?php
                                    foreach ($selected_categories_cust_get as $term_id) {
                                        $term = get_term($term_id, 'product_cat');
                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category-get">X</button></div>';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" id="selected_category_ids_cust_get" name="selected_category_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_categories_cust_get)); ?>" />
                                <!-- === Categories Section End === -->
                                <!-- category Section here Start here -->

                            <?php } elseif ($wc_bogo_filter_type_cust_get === 'tags') { ?>
                                <!-- <p>Hello i am tags here </p> -->
                                <!-- tags Section here Start here -->
                                    <!-- === Tags Section Start === -->
                                        <!-- <label><strong>Select Product Tags</strong></label> -->
                                        <select id="bogo_tag_search_field_cust_get" style="width: 300px;"></select>
                                        <div id="bogo_selected_tags_cust_get">
                                            <?php
                                            foreach ($selected_tags_cust_get as $term_id) {
                                                $term = get_term($term_id, 'product_tag');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag-get">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_tag_ids_cust_get" name="selected_tag_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_tags_cust_get)); ?>" />
                                    <!-- === Tags Section End === -->
                            <?php } else { ?>

                                <select id="bogo_search_field_cust_get" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_get">
                                    <?php
                                    foreach ($selected_products_cust_get as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-get">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_get" name="selected_product_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_products_cust_get)); ?>" />
                                
                            <?php  } ?>

                        </div> 
                        <p style="color: red"> Pls update changes before switching Product, Category & Tags </p>
                    </div>
                </div>  

                <div class="awdr-discount-container">
                    <h2 class="awdr-discount-heading">Discount Type</h2>
                    <div class="awdr-discount-content">
                        <p>Enter the min/max ranges and choose free item quantity.</p>
                        <p>Note: Enable recursive checkbox if the discounts should be applied in sequential ranges.</p>
                        <p>Example: Buy 1 get 1, Buy 2 get 2, Buy 3 get 3, and so on.</p>
                    </div>
                </div>
                       
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 50px;">
                        <label>Min Qty</label>
                        <input type="number" name="min_qty_buy_xy" value="<?php echo esc_attr($min_qty_buy_xy); ?>" style="width: 60px;" />

                        <label>Max Qty</label>
                        <input type="number" name="max_qty_buy_xy" value="<?php echo esc_attr($max_qty_buy_xy); ?>" style="width: 60px;" />

                        <label>Free Qty</label>
                        <input type="number" name="free_qty_buy_xy" value="<?php echo esc_attr($free_qty_buy_xy); ?>" style="width: 60px;" />

                        <label for="discount_type_buy_xy">Discount Type</label>
                        <select name="discount_type_buy_xy" id="discount_type_buy_xy">
                            <option value="">-- Select --</option>
                            <option value="free" <?php selected($discount_type_buy_xy, 'free'); ?>>Free</option>
                            <option value="percentage" <?php selected($discount_type_buy_xy, 'percentage'); ?>>Percentage Discount</option>
                            <option value="fixed" <?php selected($discount_type_buy_xy, 'fixed'); ?>>Fixed Amount</option>
                        </select>

                        <input type="number" name="discount_value_buy_xy" id="discount_value_buy_xy" value="<?php echo esc_attr($discount_value_buy_xy); ?>" placeholder="Discount value" style="width: 80px; display: <?php echo ($discount_type_buy_xy == 'percentage' || $discount_type_buy_xy == 'fixed') ? 'inline-block' : 'none'; ?>;" />

                        <label>Recursive?</label>
                        <input type="hidden" name="recursive_buy_xy" value="0" />
                        <input type="checkbox" name="recursive_buy_xy" value="1" <?php checked($recursive_buy_xy, 1); ?> />

                        <button id="custom-reset-button" type="button" style="background: #0073aa; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Reset</button>
                </div>
            </div>
        <?php } elseif ($status === 'no') { ?>
            <div id="bogo_fields_container_buy_x_get_x" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        <div class="awdr-discount-container">
                            <h2 class="awdr-discount-heading">Filter By</h2>

                            <div class="awdr-discount-content">
                                 <p>Choose on which products the discount should be applied (This can be products/categories/SKU) </p>
                                 </div>
                            </div>

                            <div id="repeater-container">
                            <div class="repeater-group">
                                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin: 50px 0;">

                                    <select id="wc_bogo_filter_type" name="wc_bogo_filter_type">
                                        <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                        <option value="product" <?php selected($wc_bogo_filter_type, 'product'); ?>>Product</option>
                                        <option value="category" <?php selected($wc_bogo_filter_type, 'category'); ?>>Categories</option>
                                        <option value="tags" <?php selected($wc_bogo_filter_type, 'tags'); ?>>Tags</option>
                                    </select>

                                    
                                    <?php if ($wc_bogo_filter_type === 'product' || $wc_bogo_filter_type === 'all_products' ) { ?> 
                                        <!-- Product Section here Start here -->
                                        <select id="bogo_search_field" style="width: 300px;"></select>

                                        <div id="bogo_selected_products">
                                            <?php
                                            foreach ($selected_products as $product_id) {
                                                $product = get_post($product_id);
                                                $post_title = $product->post_title ?? null;
                                                echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product">X</button></div>';
                                            }
                                            ?>
                                        </div>

                                        <input type="hidden" id="selected_product_ids" name="selected_product_ids" value="<?php echo esc_attr(implode(',', $selected_products)); ?>" />
                                        
                                        <!-- Product Section here End here  -->
                                    <?php } elseif ($wc_bogo_filter_type === 'category') { ?>
                                         <!-- <p>Hello i am category here </p> -->
                                        <!-- category Section here Start here -->
                                        <!-- === Categories Section Start === -->
                                        <!-- <label><strong>Select Product Categories</strong></label> -->
                                        <select id="bogo_category_search_field" style="width: 300px;"></select>
                                        <div id="bogo_selected_categories">
                                            <?php
                                            foreach ($selected_categories as $term_id) {
                                                $term = get_term($term_id, 'product_cat');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_category_ids" name="selected_category_ids" value="<?php echo esc_attr(implode(',', $selected_categories)); ?>" />
                                        <!-- === Categories Section End === -->
                                        <!-- category Section here Start here -->
                                       
                                    <?php } elseif ($wc_bogo_filter_type === 'tags') { ?>
                                        <!-- <p>Hello i am tags here </p> -->
                                        <!-- tags Section here Start here -->
                                            <!-- === Tags Section Start === -->
                                                <!-- <label><strong>Select Product Tags</strong></label> -->
                                                <select id="bogo_tag_search_field" style="width: 300px;"></select>
                                                <div id="bogo_selected_tags">
                                                    <?php
                                                    foreach ($selected_tags as $term_id) {
                                                        $term = get_term($term_id, 'product_tag');
                                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag">X</button></div>';
                                                    }
                                                    ?>
                                                </div>
                                                <input type="hidden" id="selected_tag_ids" name="selected_tag_ids" value="<?php echo esc_attr(implode(',', $selected_tags)); ?>" />
                                            <!-- === Tags Section End === -->
                                        <!-- tags Section here Start here -->
                                    <?php } else { ?>
                                       <select id="bogo_search_field" style="width: 300px;"></select>

                                        <div id="bogo_selected_products">
                                            <?php
                                            foreach ($selected_products as $product_id) {
                                                $product = get_post($product_id);
                                                $post_title = $product->post_title ?? null;
                                                echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product">X</button></div>';
                                            }
                                            ?>
                                        </div>

                                        <input type="hidden" id="selected_product_ids" name="selected_product_ids" value="<?php echo esc_attr(implode(',', $selected_products)); ?>" />
                                    <?php } ?>
                                
                                <!-- <button type="button" class="remove-group" style="background: red; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Remove</button> -->
                                </div> 
                                <p style="color: red"> Pls update changes before switching Product, Category & Tags </p>
                            </div>
                            </div>      

                            <!-- <button type="button" id="add-repeater" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Add</button> -->

                        <div class="awdr-discount-container">
                            <h2 class="awdr-discount-heading">Discount Type</h2>
                            <div class="awdr-discount-content">
                                <p>Enter the min/max ranges and choose free item quantity.</p>
                                <p>Note: Enable recursive checkbox if the discounts should be applied in sequential ranges.</p>
                                <p>Example: Buy 1 get 1, Buy 2 get 2, Buy 3 get 3, and so on.</p>
                            </div>
                        </div>

                       
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 50px;">
                                <label>Min Qty</label>
                                <input type="number" name="min_qty" value="<?php echo esc_attr($min_qty); ?>" style="width: 60px;" />

                                <label>Max Qty</label>
                                <input type="number" name="max_qty" value="<?php echo esc_attr($max_qty); ?>" style="width: 60px;" />

                                <label>Free Qty</label>
                                <input type="number" name="free_qty" value="<?php echo esc_attr($free_qty); ?>" style="width: 60px;" />

                                <label for="discount_type">Discount Type</label>
                                <select name="discount_type" id="discount_type">
                                    <option value="select" >--Select--</option>
                                    <option value="free" <?php selected($discount_type, 'free'); ?>>Free</option>
                                    <option value="percentage" <?php selected($discount_type, 'percentage'); ?>>Percentage Discount</option>
                                    <option value="fixed" <?php selected($discount_type, 'fixed'); ?>>Fixed Amount</option>
                                </select>

                                <input type="number" name="discount_value" id="discount_value" value="<?php echo esc_attr($discount_value); ?>" placeholder="Discount value" style="width: 80px; display: <?php echo ($discount_type == 'percentage' || $discount_type == 'fixed') ? 'inline-block' : 'none'; ?>;" />

                                <label>Recursive?</label>
                                <input type="hidden" name="recursive" value="0" />
                                <input type="checkbox" name="recursive" value="1" <?php checked($recursive, 1); ?> />

                                <button id="custom-reset-button" type="button" style="background: #0073aa; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Reset</button>
                        </div>
            </div>

            <div id="bogo_fields_container_cart_adjustment" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <!-- hello chetan here -->
                <div class="awdr-discount-container" style="margin-top: 30px;">
                    <h2 class="awdr-discount-heading">Cart Discount Options</h2>
                    <div class="awdr-discount-content">
                        <p>Select the discount type and provide appropriate values.</p>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; margin-top: 20px;">
                        <label for="cart_discount_type"><strong>Discount Type</strong></label>
                        <select name="cart_discount_type" id="cart_discount_type">
                            <option value="">-- Select --</option>
                            <option value="fixed" <?php selected($cart_discount_type, 'fixed'); ?>>Fixed Discount</option>
                            <option value="percentage" <?php selected($cart_discount_type, 'percentage'); ?>>Percentage Discount</option>
                            <option value="fixed_per_item" <?php selected($cart_discount_type, 'fixed_per_item'); ?>>Fixed Per Item</option>
                        </select>

                        <!-- Discount Value -->
                        <input type="number" name="cart_discount_value" id="cart_discount_value"
                               value="<?php echo esc_attr($cart_discount_value); ?>"
                               placeholder="Discount Value"
                               style="width: 190px; display: <?php echo ($cart_discount_type == 'fixed' || $cart_discount_type == 'percentage' || $cart_discount_type == 'fixed_per_item') ? 'inline-block' : 'none'; ?>;" />

                        <!-- Max Discount Cap -->
                        <input type="number" name="cart_max_discount_value" id="cart_max_discount_value"
                               value="<?php echo esc_attr($cart_max_discount_value); ?>"
                               placeholder="Max Discount Cap"
                               style="width: 190px; display: <?php echo ($cart_discount_type == 'percentage' || $cart_discount_type == 'fixed_per_item') ? 'inline-block' : 'none'; ?>;" />
                        <!-- Tooltip Message -->                       
                    </div>
                </div>
                <div class="lefting-tooltip-msg">
                    <label for="cart_discount_tooltip"><h3>Tooltip on cart message</h3></label>
                    <textarea name="cart_discount_tooltip" id="cart_discount_tooltip" rows="3" cols="50"><?php echo esc_textarea($cart_discount_tooltip); ?></textarea>
                </div>
            </div>

            <div id="bogo_fields_container_buy_x_get_y" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <!-- <h2 class="awdr-discount-heading">Customer Buy </h2>                 -->
                            <h2> Customer Buys  </h2>

                <div id="repeater-container">
                    <div class="repeater-group">
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin: 50px 0;">

                            <select id="wc_bogo_filter_type_cust_buy" name="wc_bogo_filter_type_cust_buy">
                                <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                <option value="product" <?php selected($wc_bogo_filter_type_cust_buy, 'product'); ?>>Product</option>
                                <option value="category" <?php selected($wc_bogo_filter_type_cust_buy, 'category'); ?>>Categories</option>
                                <option value="tags" <?php selected($wc_bogo_filter_type_cust_buy, 'tags'); ?>>Tags</option>
                            </select>
                          
                            <?php if ($wc_bogo_filter_type_cust_buy === 'product') { ?> 

                                <!-- Product Section here Start here -->
                                <select id="bogo_search_field_cust_buy" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_buy">
                                    <?php
                                    foreach ($selected_products_cust_buy as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-buy">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_buy" name="selected_product_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_products_cust_buy)); ?>" />

                                <!-- Product Section here End here  -->
                            <?php } elseif ($wc_bogo_filter_type_cust_buy === 'category') { ?>
                                 <!-- <p>Hello i am category here </p> -->
                                <!-- category Section here Start here -->
                                <!-- === Categories Section Start === -->
                                <!-- <label><strong>Select Product Categories</strong></label> -->
                                <select id="bogo_category_search_field_cust_buy" style="width: 300px;"></select>
                                <div id="bogo_selected_categories_cust_buy">
                                    <?php
                                    foreach ($selected_categories_cust_buy as $term_id) {
                                        $term = get_term($term_id, 'product_cat');
                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category-buy">X</button></div>';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" id="selected_category_ids_cust_buy" name="selected_category_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_categories_cust_buy)); ?>" />
                                <!-- === Categories Section End === -->
                                <!-- category Section here Start here -->
                               
                            <?php } elseif ($wc_bogo_filter_type_cust_buy === 'tags') { ?>
                                <!-- <p>Hello i am tags here </p> -->
                                <!-- tags Section here Start here -->
                                    <!-- === Tags Section Start === -->
                                        <!-- <label><strong>Select Product Tags</strong></label> -->
                                        <select id="bogo_tag_search_field_cust_buy" style="width: 300px;"></select>
                                        <div id="bogo_selected_tags_cust_buy">
                                            <?php
                                            foreach ($selected_tags_cust_buy as $term_id) {
                                                $term = get_term($term_id, 'product_tag');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag-buy">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_tag_ids_cust_buy" name="selected_tag_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_tags_cust_buy)); ?>" />
                                    <!-- === Tags Section End === -->
                                <!-- tags Section here Start here -->
                            <?php } else { ?>
                                <h2> Customer Buys  </h2>
                                <select id="bogo_search_field_cust_buy" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_buy">
                                    <?php
                                    foreach ($selected_products_cust_buy as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-buy">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_buy" name="selected_product_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_products_cust_buy)); ?>" />

                            <?php } ?>


                              <h2> Customer Gets  </h2>

                            <select id="wc_bogo_filter_type_cust_get" name="wc_bogo_filter_type_cust_get">
                                <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                <option value="product" <?php selected($wc_bogo_filter_type_cust_get, 'product'); ?>>Product</option>
                                <option value="category" <?php selected($wc_bogo_filter_type_cust_get, 'category'); ?>>Categories</option>
                                <option value="tags" <?php selected($wc_bogo_filter_type_cust_get, 'tags'); ?>>Tags</option>
                            </select>


                            <?php if ($wc_bogo_filter_type_cust_get === 'product') { ?>

                             <!-- Product Section here Start here -->
                                <select id="bogo_search_field_cust_get" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_get">
                                    <?php
                                    foreach ($selected_products_cust_get as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-get">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_get" name="selected_product_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_products_cust_get)); ?>" />

                                <?php } elseif ($wc_bogo_filter_type_cust_get === 'category') { ?>
                                 <!-- <p>Hello i am category here </p> -->
                                <!-- category Section here Start here -->
                                <!-- === Categories Section Start === -->
                                <!-- <label><strong>Select Product Categories</strong></label> -->
                                <select id="bogo_category_search_field_cust_get" style="width: 300px;"></select>
                                <div id="bogo_selected_categories_cust_get">
                                    <?php
                                    foreach ($selected_categories_cust_get as $term_id) {
                                        $term = get_term($term_id, 'product_cat');
                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category-get">X</button></div>';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" id="selected_category_ids_cust_get" name="selected_category_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_categories_cust_get)); ?>" />
                                <!-- === Categories Section End === -->
                                <!-- category Section here Start here -->

                            <?php } elseif ($wc_bogo_filter_type_cust_get === 'tags') { ?>
                                <!-- <p>Hello i am tags here </p> -->
                                <!-- tags Section here Start here -->
                                    <!-- === Tags Section Start === -->
                                        <!-- <label><strong>Select Product Tags</strong></label> -->
                                        <select id="bogo_tag_search_field_cust_get" style="width: 300px;"></select>
                                        <div id="bogo_selected_tags_cust_get">
                                            <?php
                                            foreach ($selected_tags_cust_get as $term_id) {
                                                $term = get_term($term_id, 'product_tag');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag-get">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_tag_ids_cust_get" name="selected_tag_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_tags_cust_get)); ?>" />
                                    <!-- === Tags Section End === -->
                            <?php } else { ?>

                                <select id="bogo_search_field_cust_get" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_get">
                                    <?php
                                    foreach ($selected_products_cust_get as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-get">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_get" name="selected_product_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_products_cust_get)); ?>" />
                                
                            <?php  } ?>

                        </div> 
                        <p style="color: red"> Pls update changes before switching Product, Category & Tags </p>
                    </div>
                </div>  

                <div class="awdr-discount-container">
                    <h2 class="awdr-discount-heading">Discount Type</h2>
                    <div class="awdr-discount-content">
                        <p>Enter the min/max ranges and choose free item quantity.</p>
                        <p>Note: Enable recursive checkbox if the discounts should be applied in sequential ranges.</p>
                        <p>Example: Buy 1 get 1, Buy 2 get 2, Buy 3 get 3, and so on.</p>
                    </div>
                </div>
                       
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 50px;">
                        <label>Min Qty</label>
                        <input type="number" name="min_qty_buy_xy" value="<?php echo esc_attr($min_qty_buy_xy); ?>" style="width: 60px;" />

                        <label>Max Qty</label>
                        <input type="number" name="max_qty_buy_xy" value="<?php echo esc_attr($max_qty_buy_xy); ?>" style="width: 60px;" />

                        <label>Free Qty</label>
                        <input type="number" name="free_qty_buy_xy" value="<?php echo esc_attr($free_qty_buy_xy); ?>" style="width: 60px;" />

                        <label for="discount_type_buy_xy">Discount Type</label>
                        <select name="discount_type_buy_xy" id="discount_type_buy_xy">
                            <option value="free" <?php selected($discount_type_buy_xy, 'free'); ?>>Free</option>
                            <option value="percentage" <?php selected($discount_type_buy_xy, 'percentage'); ?>>Percentage Discount</option>
                            <option value="fixed" <?php selected($discount_type_buy_xy, 'fixed'); ?>>Fixed Amount</option>
                        </select>

                        <input type="number" name="discount_value_buy_xy" id="discount_value_buy_xy" value="<?php echo esc_attr($discount_value_buy_xy); ?>" placeholder="Discount value" style="width: 80px; display: <?php echo ($discount_type_buy_xy == 'percentage' || $discount_type_buy_xy == 'fixed') ? 'inline-block' : 'none'; ?>;" />

                        <label>Recursive?</label>
                        <input type="hidden" name="recursive_buy_xy" value="0" />
                        <input type="checkbox" name="recursive_buy_xy" value="1" <?php checked($recursive_buy_xy, 1); ?> />

                        <button id="custom-reset-button" type="button" style="background: #0073aa; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Reset</button>
                </div>
            </div>
        <?php } else { ?>
            <!-- <p>Please enable the BOGO deal by toggling the status ON.</p> -->
            <div id="bogo_fields_container_buy_x_get_x" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        <div class="awdr-discount-container">
                            <h2 class="awdr-discount-heading">Filter By</h2>

                            <div class="awdr-discount-content">
                                 <p>Choose on which products the discount should be applied (This can be products/categories/SKU) </p>
                                 </div>
                            </div>

                            <div id="repeater-container">
                            <div class="repeater-group">
                                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin: 50px 0;">

                                    <select id="wc_bogo_filter_type" name="wc_bogo_filter_type">
                                        <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                        <option value="product" <?php selected($wc_bogo_filter_type, 'product'); ?>>Product</option>
                                        <option value="category" <?php selected($wc_bogo_filter_type, 'category'); ?>>Categories</option>
                                        <option value="tags" <?php selected($wc_bogo_filter_type, 'tags'); ?>>Tags</option>
                                    </select>

                                    
                                    <?php if ($wc_bogo_filter_type === 'product' || $wc_bogo_filter_type === 'all_products' ) { ?> 
                                        <!-- Product Section here Start here -->
                                        <select id="bogo_search_field" style="width: 300px;"></select>

                                        <div id="bogo_selected_products">
                                            <?php
                                            foreach ($selected_products as $product_id) {
                                                $product = get_post($product_id);
                                                $post_title = $product->post_title ?? null;
                                                echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product">X</button></div>';
                                            }
                                            ?>
                                        </div>

                                        <input type="hidden" id="selected_product_ids" name="selected_product_ids" value="<?php echo esc_attr(implode(',', $selected_products)); ?>" />
                                        
                                        <!-- Product Section here End here  -->
                                    <?php } elseif ($wc_bogo_filter_type === 'category') { ?>
                                         <!-- <p>Hello i am category here </p> -->
                                        <!-- category Section here Start here -->
                                        <!-- === Categories Section Start === -->
                                        <!-- <label><strong>Select Product Categories</strong></label> -->
                                        <select id="bogo_category_search_field" style="width: 300px;"></select>
                                        <div id="bogo_selected_categories">
                                            <?php
                                            foreach ($selected_categories as $term_id) {
                                                $term = get_term($term_id, 'product_cat');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_category_ids" name="selected_category_ids" value="<?php echo esc_attr(implode(',', $selected_categories)); ?>" />
                                        <!-- === Categories Section End === -->
                                        <!-- category Section here Start here -->
                                       
                                    <?php } elseif ($wc_bogo_filter_type === 'tags') { ?>
                                        <!-- <p>Hello i am tags here </p> -->
                                        <!-- tags Section here Start here -->
                                            <!-- === Tags Section Start === -->
                                                <!-- <label><strong>Select Product Tags</strong></label> -->
                                                <select id="bogo_tag_search_field" style="width: 300px;"></select>
                                                <div id="bogo_selected_tags">
                                                    <?php
                                                    foreach ($selected_tags as $term_id) {
                                                        $term = get_term($term_id, 'product_tag');
                                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag">X</button></div>';
                                                    }
                                                    ?>
                                                </div>
                                                <input type="hidden" id="selected_tag_ids" name="selected_tag_ids" value="<?php echo esc_attr(implode(',', $selected_tags)); ?>" />
                                            <!-- === Tags Section End === -->
                                        <!-- tags Section here Start here -->
                                    <?php } else { ?>
                                       <select id="bogo_search_field" style="width: 300px;"></select>

                                        <div id="bogo_selected_products">
                                            <?php
                                            foreach ($selected_products as $product_id) {
                                                $product = get_post($product_id);
                                                $post_title = $product->post_title ?? null;
                                                echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product">X</button></div>';
                                            }
                                            ?>
                                        </div>

                                        <input type="hidden" id="selected_product_ids" name="selected_product_ids" value="<?php echo esc_attr(implode(',', $selected_products)); ?>" />
                                    <?php } ?>
                                
                                <!-- <button type="button" class="remove-group" style="background: red; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Remove</button> -->
                                </div> 
                                <p style="color: red"> Pls update changes before switching Product, Category & Tags </p>
                            </div>
                            </div>      

                            <!-- <button type="button" id="add-repeater" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Add</button> -->

                        <div class="awdr-discount-container">
                            <h2 class="awdr-discount-heading">Discount Type</h2>
                            <div class="awdr-discount-content">
                                <p>Enter the min/max ranges and choose free item quantity.</p>
                                <p>Note: Enable recursive checkbox if the discounts should be applied in sequential ranges.</p>
                                <p>Example: Buy 1 get 1, Buy 2 get 2, Buy 3 get 3, and so on.</p>
                            </div>
                        </div>

                       
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 50px;">
                                <label>Min Qty</label>
                                <input type="number" name="min_qty" value="<?php echo esc_attr($min_qty); ?>" style="width: 60px;" />

                                <label>Max Qty</label>
                                <input type="number" name="max_qty" value="<?php echo esc_attr($max_qty); ?>" style="width: 60px;" />

                                <label>Free Qty</label>
                                <input type="number" name="free_qty" value="<?php echo esc_attr($free_qty); ?>" style="width: 60px;" />

                                <label for="discount_type">Discount Type</label>
                                <select name="discount_type" id="discount_type">
                                    <option value="select" >--Select--</option>
                                    <option value="free" <?php selected($discount_type, 'free'); ?>>Free</option>
                                    <option value="percentage" <?php selected($discount_type, 'percentage'); ?>>Percentage Discount</option>
                                    <option value="fixed" <?php selected($discount_type, 'fixed'); ?>>Fixed Amount</option>
                                </select>

                                <input type="number" name="discount_value" id="discount_value" value="<?php echo esc_attr($discount_value); ?>" placeholder="Discount value" style="width: 80px; display: <?php echo ($discount_type == 'percentage' || $discount_type == 'fixed') ? 'inline-block' : 'none'; ?>;" />

                                <label>Recursive?</label>
                                <input type="hidden" name="recursive" value="0" />
                                <input type="checkbox" name="recursive" value="1" <?php checked($recursive, 1); ?> />

                                <button id="custom-reset-button" type="button" style="background: #0073aa; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Reset</button>
                        </div>
            </div>

            <div id="bogo_fields_container_cart_adjustment" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <!-- hello chetan here -->
                <div class="awdr-discount-container" style="margin-top: 30px;">
                    <h2 class="awdr-discount-heading">Cart Discount Options</h2>
                    <div class="awdr-discount-content">
                        <p>Select the discount type and provide appropriate values.</p>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; margin-top: 20px;">
                        <label for="cart_discount_type"><strong>Discount Type</strong></label>
                        <select name="cart_discount_type" id="cart_discount_type">
                            <option value="">-- Select --</option>
                            <option value="fixed" <?php selected($cart_discount_type, 'fixed'); ?>>Fixed Discount</option>
                            <option value="percentage" <?php selected($cart_discount_type, 'percentage'); ?>>Percentage Discount</option>
                            <option value="fixed_per_item" <?php selected($cart_discount_type, 'fixed_per_item'); ?>>Fixed Per Item</option>
                        </select>

                        <!-- Discount Value -->
                        <input type="number" name="cart_discount_value" id="cart_discount_value"
                               value="<?php echo esc_attr($cart_discount_value); ?>"
                               placeholder="Discount Value"
                               style="width: 190px; display: <?php echo ($cart_discount_type == 'fixed' || $cart_discount_type == 'percentage' || $cart_discount_type == 'fixed_per_item') ? 'inline-block' : 'none'; ?>;" />

                        <!-- Max Discount Cap -->
                        <input type="number" name="cart_max_discount_value" id="cart_max_discount_value"
                               value="<?php echo esc_attr($cart_max_discount_value); ?>"
                               placeholder="Max Discount Cap"
                               style="width: 190px; display: <?php echo ($cart_discount_type == 'percentage' || $cart_discount_type == 'fixed_per_item') ? 'inline-block' : 'none'; ?>;" />
                        <!-- Tooltip Message -->                       
                    </div>
                </div>
                <div class="lefting-tooltip-msg">
                    <label for="cart_discount_tooltip"><h3>Tooltip on cart message</h3></label>
                    <textarea name="cart_discount_tooltip" id="cart_discount_tooltip" rows="3" cols="50"><?php echo esc_textarea($cart_discount_tooltip); ?></textarea>
                </div>
            </div>

            <div id="bogo_fields_container_buy_x_get_y" style="display: none; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <!-- <h2 class="awdr-discount-heading">Customer Buy </h2>                 -->
                            <h2> Customer Buys  </h2>

                <div id="repeater-container">
                    <div class="repeater-group">
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin: 50px 0;">

                            <select id="wc_bogo_filter_type_cust_buy" name="wc_bogo_filter_type_cust_buy">
                                <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                <option value="product" <?php selected($wc_bogo_filter_type_cust_buy, 'product'); ?>>Product</option>
                                <option value="category" <?php selected($wc_bogo_filter_type_cust_buy, 'category'); ?>>Categories</option>
                                <option value="tags" <?php selected($wc_bogo_filter_type_cust_buy, 'tags'); ?>>Tags</option>
                            </select>
                          
                            <?php if ($wc_bogo_filter_type_cust_buy === 'product') { ?> 

                                <!-- Product Section here Start here -->
                                <select id="bogo_search_field_cust_buy" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_buy">
                                    <?php
                                    foreach ($selected_products_cust_buy as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-buy">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_buy" name="selected_product_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_products_cust_buy)); ?>" />

                                <!-- Product Section here End here  -->
                            <?php } elseif ($wc_bogo_filter_type_cust_buy === 'category') { ?>
                                 <!-- <p>Hello i am category here </p> -->
                                <!-- category Section here Start here -->
                                <!-- === Categories Section Start === -->
                                <!-- <label><strong>Select Product Categories</strong></label> -->
                                <select id="bogo_category_search_field_cust_buy" style="width: 300px;"></select>
                                <div id="bogo_selected_categories_cust_buy">
                                    <?php
                                    foreach ($selected_categories_cust_buy as $term_id) {
                                        $term = get_term($term_id, 'product_cat');
                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category-buy">X</button></div>';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" id="selected_category_ids_cust_buy" name="selected_category_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_categories_cust_buy)); ?>" />
                                <!-- === Categories Section End === -->
                                <!-- category Section here Start here -->
                               
                            <?php } elseif ($wc_bogo_filter_type_cust_buy === 'tags') { ?>
                                <!-- <p>Hello i am tags here </p> -->
                                <!-- tags Section here Start here -->
                                    <!-- === Tags Section Start === -->
                                        <!-- <label><strong>Select Product Tags</strong></label> -->
                                        <select id="bogo_tag_search_field_cust_buy" style="width: 300px;"></select>
                                        <div id="bogo_selected_tags_cust_buy">
                                            <?php
                                            foreach ($selected_tags_cust_buy as $term_id) {
                                                $term = get_term($term_id, 'product_tag');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag-buy">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_tag_ids_cust_buy" name="selected_tag_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_tags_cust_buy)); ?>" />
                                    <!-- === Tags Section End === -->
                                <!-- tags Section here Start here -->
                            <?php } else { ?>
                                <h2> Customer Buys  </h2>
                                <select id="bogo_search_field_cust_buy" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_buy">
                                    <?php
                                    foreach ($selected_products_cust_buy as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-buy">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_buy" name="selected_product_ids_cust_buy" value="<?php echo esc_attr(implode(',', $selected_products_cust_buy)); ?>" />

                            <?php } ?>


                              <h2> Customer Gets  </h2>

                            <select id="wc_bogo_filter_type_cust_get" name="wc_bogo_filter_type_cust_get">
                                <!-- <option value="all_products" <?php // selected($wc_bogo_filter_type, 'all_products'); ?>>All Products</option> -->
                                <option value="product" <?php selected($wc_bogo_filter_type_cust_get, 'product'); ?>>Product</option>
                                <option value="category" <?php selected($wc_bogo_filter_type_cust_get, 'category'); ?>>Categories</option>
                                <option value="tags" <?php selected($wc_bogo_filter_type_cust_get, 'tags'); ?>>Tags</option>
                            </select>


                            <?php if ($wc_bogo_filter_type_cust_get === 'product') { ?>

                             <!-- Product Section here Start here -->
                                <select id="bogo_search_field_cust_get" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_get">
                                    <?php
                                    foreach ($selected_products_cust_get as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-get">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_get" name="selected_product_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_products_cust_get)); ?>" />

                                <?php } elseif ($wc_bogo_filter_type_cust_get === 'category') { ?>
                                 <!-- <p>Hello i am category here </p> -->
                                <!-- category Section here Start here -->
                                <!-- === Categories Section Start === -->
                                <!-- <label><strong>Select Product Categories</strong></label> -->
                                <select id="bogo_category_search_field_cust_get" style="width: 300px;"></select>
                                <div id="bogo_selected_categories_cust_get">
                                    <?php
                                    foreach ($selected_categories_cust_get as $term_id) {
                                        $term = get_term($term_id, 'product_cat');
                                        echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-category-get">X</button></div>';
                                    }
                                    ?>
                                </div>
                                <input type="hidden" id="selected_category_ids_cust_get" name="selected_category_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_categories_cust_get)); ?>" />
                                <!-- === Categories Section End === -->
                                <!-- category Section here Start here -->

                            <?php } elseif ($wc_bogo_filter_type_cust_get === 'tags') { ?>
                                <!-- <p>Hello i am tags here </p> -->
                                <!-- tags Section here Start here -->
                                    <!-- === Tags Section Start === -->
                                        <!-- <label><strong>Select Product Tags</strong></label> -->
                                        <select id="bogo_tag_search_field_cust_get" style="width: 300px;"></select>
                                        <div id="bogo_selected_tags_cust_get">
                                            <?php
                                            foreach ($selected_tags_cust_get as $term_id) {
                                                $term = get_term($term_id, 'product_tag');
                                                echo '<div data-id="' . esc_attr($term_id) . '">' . esc_html($term->name) . ' <button type="button" class="remove-tag-get">X</button></div>';
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" id="selected_tag_ids_cust_get" name="selected_tag_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_tags_cust_get)); ?>" />
                                    <!-- === Tags Section End === -->
                            <?php } else { ?>

                                <select id="bogo_search_field_cust_get" style="width: 300px;"></select>

                                <div id="bogo_selected_products_cust_get">
                                    <?php
                                    foreach ($selected_products_cust_get as $product_id) {
                                        $product = get_post($product_id);
                                        $post_title = $product->post_title ?? null;
                                        echo '<div data-id="' . esc_attr($product_id) . '">' . esc_html($post_title) . ' <button type="button" class="remove-product-get">X</button></div>';
                                    }
                                    ?>
                                </div>

                                <input type="hidden" id="selected_product_ids_cust_get" name="selected_product_ids_cust_get" value="<?php echo esc_attr(implode(',', $selected_products_cust_get)); ?>" />
                                
                            <?php  } ?>

                        </div> 
                        <p style="color: red"> Pls update changes before switching Product, Category & Tags </p>
                    </div>
                </div>  

                <div class="awdr-discount-container">
                    <h2 class="awdr-discount-heading">Discount Type</h2>
                    <div class="awdr-discount-content">
                        <p>Enter the min/max ranges and choose free item quantity.</p>
                        <p>Note: Enable recursive checkbox if the discounts should be applied in sequential ranges.</p>
                        <p>Example: Buy 1 get 1, Buy 2 get 2, Buy 3 get 3, and so on.</p>
                    </div>
                </div>
                       
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 50px;">
                        <label>Min Qty</label>
                        <input type="number" name="min_qty_buy_xy" value="<?php echo esc_attr($min_qty_buy_xy); ?>" style="width: 60px;" />

                        <label>Max Qty</label>
                        <input type="number" name="max_qty_buy_xy" value="<?php echo esc_attr($max_qty_buy_xy); ?>" style="width: 60px;" />

                        <label>Free Qty</label>
                        <input type="number" name="free_qty_buy_xy" value="<?php echo esc_attr($free_qty_buy_xy); ?>" style="width: 60px;" />

                        <label for="discount_type_buy_xy">Discount Type</label>
                        <select name="discount_type_buy_xy" id="discount_type_buy_xy">
                            <option value="">-- Select --</option>
                            <option value="free" <?php selected($discount_type_buy_xy, 'free'); ?>>Free</option>
                            <option value="percentage" <?php selected($discount_type_buy_xy, 'percentage'); ?>>Percentage Discount</option>
                            <option value="fixed" <?php selected($discount_type_buy_xy, 'fixed'); ?>>Fixed Amount</option>
                        </select>

                        <input type="number" name="discount_value_buy_xy" id="discount_value_buy_xy" value="<?php echo esc_attr($discount_value_buy_xy); ?>" placeholder="Discount value" style="width: 80px; display: <?php echo ($discount_type_buy_xy == 'percentage' || $discount_type_buy_xy == 'fixed') ? 'inline-block' : 'none'; ?>;" />

                        <label>Recursive?</label>
                        <input type="hidden" name="recursive_buy_xy" value="0" />
                        <input type="checkbox" name="recursive_buy_xy" value="1" <?php checked($recursive_buy_xy, 1); ?> />

                        <button id="custom-reset-button" type="button" style="background: #0073aa; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Reset</button>
                </div>
            </div>
        <?php }
    }
// Metabox Callback Function End Here

