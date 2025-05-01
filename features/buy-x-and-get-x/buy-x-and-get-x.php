<?php

//  Buy X and get X Meta Values Save here and Bogo Discount added Here

// Save Metabox Data Start Here
    add_action('save_post', 'bogo_discount_meta_save');
    function bogo_discount_meta_save($post_id) {

        // Verify nonce
        if (!isset($_POST['bogo_discount_meta_nonce']) || !wp_verify_nonce($_POST['bogo_discount_meta_nonce'], 'bogo_discount_meta_nonce')) {
            return;
        }

        // Check user permission
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Prevent Auto Save or Quick Edit
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }

        // Save main BOGO config fields
        if (isset($_POST['bogo_discount_type'])) {
            update_post_meta($post_id, '_bogo_discount_type', sanitize_text_field($_POST['bogo_discount_type']));
        }

        if (isset($_POST['wc_bogo_filter_type'])) {
            $filter_type = sanitize_text_field($_POST['wc_bogo_filter_type']);
            update_post_meta($post_id, '_wc_bogo_filter_type', $filter_type);

            // Clear other filters first
            if ($filter_type !== 'product') {
                delete_post_meta($post_id, '_selected_products');
            }
            if ($filter_type !== 'category') {
                delete_post_meta($post_id, '_selected_categories');
            }
            if ($filter_type !== 'tag') {
                delete_post_meta($post_id, '_selected_tags');
            }
        }

        if (isset($_POST['min_qty'])) {
            update_post_meta($post_id, '_min_qty', intval($_POST['min_qty']));
        }
        if (isset($_POST['max_qty'])) {
            update_post_meta($post_id, '_max_qty', intval($_POST['max_qty']));
        }
        if (isset($_POST['free_qty'])) {
            update_post_meta($post_id, '_free_qty', intval($_POST['free_qty']));
        }
        if (isset($_POST['discount_type'])) {
            update_post_meta($post_id, '_discount_type', sanitize_text_field($_POST['discount_type']));
        }
        if (isset($_POST['discount_value'])) {
            update_post_meta($post_id, '_discount_value', floatval($_POST['discount_value']));
        }
        update_post_meta($post_id, '_recursive', isset($_POST['recursive']) ? 1 : 0);

        // Save Cart Discount Fields
        // if (isset($_POST['cart_discount_type'])) {
        //     update_post_meta($post_id, '_cart_discount_type', sanitize_text_field($_POST['cart_discount_type']));
        // }

        // if (isset($_POST['cart_discount_value'])) {
        //     update_post_meta($post_id, '_cart_discount_value', floatval($_POST['cart_discount_value']));
        // }

        // if (isset($_POST['cart_max_discount_value'])) {
        //     update_post_meta($post_id, '_cart_max_discount_value', floatval($_POST['cart_max_discount_value']));
        // }

        // if (isset($_POST['cart_discount_tooltip'])) {
        //     update_post_meta($post_id, '_cart_discount_tooltip', sanitize_textarea_field($_POST['cart_discount_tooltip']));
        // }

        // Conditionally save selected filter data based on active filter type
        switch ($filter_type) {
            case 'product':
                if (isset($_POST['selected_product_ids'])) {
                    $raw_ids = explode(',', sanitize_text_field($_POST['selected_product_ids']));
                    $valid_ids = array_filter($raw_ids, function ($id) {
                        $post = get_post($id);
                        return $post && $post->post_type === 'product';
                    });
                    update_post_meta($post_id, '_selected_products', implode(',', $valid_ids));
                }
                break;

            case 'category':
                if (isset($_POST['selected_category_ids'])) {
                    $raw_cat_ids = explode(',', sanitize_text_field($_POST['selected_category_ids']));
                    $valid_cat_ids = array_filter($raw_cat_ids, function ($cat_id) {
                        $term = get_term($cat_id, 'product_cat');
                        return $term && !is_wp_error($term);
                    });
                    update_post_meta($post_id, '_selected_categories', implode(',', $valid_cat_ids));
                }
                break;

            case 'tags':
                if (isset($_POST['selected_tag_ids'])) {
                    $raw_tag_ids = explode(',', sanitize_text_field($_POST['selected_tag_ids']));
                    $valid_tag_ids = array_filter($raw_tag_ids, function ($tag_id) {
                        $term = get_term($tag_id, 'product_tag');
                        return $term && !is_wp_error($term);
                    });
                    update_post_meta($post_id, '_selected_tags', implode(',', $valid_tag_ids));
                }
                break;
        }
    }
// Save Metabox Data End Here

// Apply BOGO discount to cart items Start Here
    add_action('woocommerce_before_calculate_totals', 'apply_bogo_discount');
    function apply_bogo_discount($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        $parent_products = [];
        $bogo_products   = [];
        $bogo_free_items = [];

        // Identify eligible products in the cart
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            // Skip if free item flag is already set.
            if (!isset($cart_item['bogo_free'])) {
                $product_id = $cart_item['product_id'];
                // Try to get BOGO rules by product, category, and tag (in this order)
                $bogo_rules = get_bogo_rules_for_product($product_id);

                if (!$bogo_rules) {
                    // Check product categories
                    $terms_cat = get_the_terms($product_id, 'product_cat');
                    if ($terms_cat && !is_wp_error($terms_cat)) {
                        foreach ($terms_cat as $term) {
                            $bogo_rules = get_bogo_rules_for_category($term->term_id);
                            if ($bogo_rules) break;
                        }
                    }
                }
                if (!$bogo_rules) {
                    // Check product tags
                    $terms_tag = get_the_terms($product_id, 'product_tag');
                    if ($terms_tag && !is_wp_error($terms_tag)) {
                        foreach ($terms_tag as $term) {
                            $bogo_rules = get_bogo_rules_for_tag($term->term_id);
                            if ($bogo_rules) break;
                        }
                    }
                }

                if ($bogo_rules) {
                    $parent_products[$product_id] = $cart_item['quantity'];
                    $bogo_products[] = $product_id;
                }
            }
        }

        // Process discount application for each eligible cart item.
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $bogo_rules = get_bogo_rules_for_product($product_id);

            // Check category rule if no product rule exists.
            if (!$bogo_rules) {
                $terms_cat = get_the_terms($product_id, 'product_cat');
                if ($terms_cat && !is_wp_error($terms_cat)) {
                    foreach ($terms_cat as $term) {
                        $bogo_rules = get_bogo_rules_for_category($term->term_id);
                        if ($bogo_rules) break;
                    }
                }
            }

            // Check tag rule if still no rule found.
            if (!$bogo_rules) {
                $terms_tag = get_the_terms($product_id, 'product_tag');
                if ($terms_tag && !is_wp_error($terms_tag)) {
                    foreach ($terms_tag as $term) {
                        $bogo_rules = get_bogo_rules_for_tag($term->term_id);
                        if ($bogo_rules) break;
                    }
                }
            }

            if ($bogo_rules) {
                $quantity      = $cart_item['quantity'];
                $min_qty       = intval($bogo_rules['min_qty']);
                $max_qty       = intval($bogo_rules['max_qty']);
                $free_qty      = intval($bogo_rules['free_qty']);
                $discount_type = $bogo_rules['discount_type'];
                $discount_value= $bogo_rules['discount_value'];
                $recursive     = isset($bogo_rules['recursive']) ? intval($bogo_rules['recursive']) : 0;

                if ($quantity >= $min_qty) {
                    // Limit quantity to maximum defined.
                    $allowed_qty = min($quantity, $max_qty);

                    // Calculate free items based on recursive flag.
                    if ($recursive === 1) {
                        $num_free_items = floor($allowed_qty / $min_qty) * $free_qty;
                    } else {
                        $num_free_items = $free_qty;
                    }

                    // Check how many free items have already been added.
                    $existing_free_qty = 0;
                    foreach ($cart->get_cart() as $inner_cart_item) {
                        if (isset($inner_cart_item['bogo_free']) && $inner_cart_item['product_id'] == $product_id) {
                            $existing_free_qty += $inner_cart_item['quantity'];
                        }
                    }

                    // Add missing free items.
                    $items_to_add = $num_free_items - $existing_free_qty;
                    if ($items_to_add > 0) {
                        $cart->add_to_cart($product_id, $items_to_add, 0, array(), array('bogo_free' => true));
                    }

                    if ($discount_type === 'free') {
                        // Any item flagged bogo_free for this product gets a zero price
                        foreach ($cart->get_cart() as $ci_key => $ci) {
                            if (!empty($ci['bogo_free']) && $ci['product_id'] === $product_id) {
                                $ci['data']->set_price(0);
                            }
                        }
                        // skip the fixed/percentage fee logic entirely
                        continue; 
                    }                   

                    // Apply discount fee if set.
                    if ($discount_value > 0) {
                        if ($discount_type === 'fixed') {
                            $label = sprintf(__('BOGO Fixed Discount (-$%.1f)', 'your-text-domain'), $discount_value);
                            $cart->add_fee($label, -($discount_value * $num_free_items), false);
                        } elseif ($discount_type === 'percentage') {
                            $discount = ($cart_item['data']->get_price() * $discount_value / 100) * $num_free_items;
                            $label = sprintf(__('BOGO Percentage Discount (%s%%)', 'your-text-domain'), $discount_value);
                            $cart->add_fee($label, -$discount, false);
                        } 
                    }

                    $bogo_free_items[$product_id] = $num_free_items;
                }
            }
        }

        // Clean up free items if the parent product is no longer eligible.
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            if (isset($cart_item['bogo_free']) && $cart_item['bogo_free'] === true) {
                $parent_id = $cart_item['product_id'];
                if (!isset($parent_products[$parent_id]) || !in_array($parent_id, $bogo_products)) {
                    $cart->remove_cart_item($cart_item_key);
                } else {
                    $parent_qty = $parent_products[$parent_id];
                    // Try to retrieve the applicable BOGO rule.
                    $bogo_rules = get_bogo_rules_for_product($parent_id);
                    if (!$bogo_rules) {
                        $terms_cat = get_the_terms($parent_id, 'product_cat');
                        if ($terms_cat && !is_wp_error($terms_cat)) {
                            foreach ($terms_cat as $term) {
                                $bogo_rules = get_bogo_rules_for_category($term->term_id);
                                if ($bogo_rules) break;
                            }
                        }
                    }
                    if (!$bogo_rules) {
                        $terms_tag = get_the_terms($parent_id, 'product_tag');
                        if ($terms_tag && !is_wp_error($terms_tag)) {
                            foreach ($terms_tag as $term) {
                                $bogo_rules = get_bogo_rules_for_tag($term->term_id);
                                if ($bogo_rules) break;
                            }
                        }
                    }
                    if ($bogo_rules) {
                        $min_qty       = intval($bogo_rules['min_qty']);
                        $free_qty      = intval($bogo_rules['free_qty']);
                        $new_free_qty  = floor($parent_qty / $min_qty) * $free_qty;
                        if ($cart_item['quantity'] != $new_free_qty) {
                            $cart->set_quantity($cart_item_key, $new_free_qty);
                        }
                    }
                }
            }
        }
    }
// Apply BOGO discount to cart items End Here

// Ensure free product price is set to zero Start Here
    add_action('woocommerce_cart_calculate_fees', function() {
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

            if (isset($cart_item['bogo_free']) && $cart_item['bogo_free'] === true) {
                $product_id = $cart_item['product_id'];
                
                // Get BOGO rules for this product
                $bogo_rules = get_bogo_rules_for_product($product_id);

                // Check if the discount type is "free"
                if ($bogo_rules && isset($bogo_rules['discount_type']) && $bogo_rules['discount_type'] === 'free') {
                    $cart_item['data']->set_price(0);
                }
            }
        }
    });
// Ensure free product price is set to zero End Here

// Function to retrieve BOGO rules for a product Start Here
    function get_bogo_rules_for_product($product_id) {
        $args = array(
            'post_type'  => 'wc_bogo',
            'meta_query' => array(
                array(
                    'key'     => '_selected_products',
                    'value'   => (string)$product_id,
                    'compare' => 'LIKE'
                )
            )
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $status = get_post_meta(get_the_ID(), '_bogo_deal_status', true);
                if ($status !== 'yes') {
                    continue;
                }
                $bogo_rules = array(
                    'min_qty'       => intval(get_post_meta(get_the_ID(), '_min_qty', true)),
                    'free_qty'      => intval(get_post_meta(get_the_ID(), '_free_qty', true)),
                    'max_qty'       => intval(get_post_meta(get_the_ID(), '_max_qty', true)),
                    'discount_type' => get_post_meta(get_the_ID(), '_discount_type', true),
                    'discount_value'=> floatval(get_post_meta(get_the_ID(), '_discount_value', true)),
                    'recursive'     => get_post_meta(get_the_ID(), '_recursive', true),
                );
                wp_reset_postdata();
                return $bogo_rules;
            }
        }
        return false;
    }
// Function to retrieve BOGO rules for a product End Here

// Function to retrieve BOGO rules for a Category Start Here
    function get_bogo_rules_for_category($category_id) {
        $args = array(
            'post_type'  => 'wc_bogo',
            'meta_query' => array(
                array(
                    'key'     => '_selected_categories',
                    'value'   => (string)$category_id,
                    'compare' => 'LIKE'
                )
            )
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $status = get_post_meta(get_the_ID(), '_bogo_deal_status', true);
                if ($status !== 'yes') {
                    continue;
                }
                $bogo_rules = array(
                    'min_qty'       => intval(get_post_meta(get_the_ID(), '_min_qty', true)),
                    'free_qty'      => intval(get_post_meta(get_the_ID(), '_free_qty', true)),
                    'max_qty'       => intval(get_post_meta(get_the_ID(), '_max_qty', true)),
                    'discount_type' => get_post_meta(get_the_ID(), '_discount_type', true),
                    'discount_value'=> floatval(get_post_meta(get_the_ID(), '_discount_value', true)),
                    'recursive'     => get_post_meta(get_the_ID(), '_recursive', true),
                );
                wp_reset_postdata();
                return $bogo_rules;
            }
        }
        return false;
    }
// Function to retrieve BOGO rules for a Category Start Here

// Function to retrieve BOGO rules for a Tags Start Here
    function get_bogo_rules_for_tag($tag_id) {
        $args = array(
            'post_type'  => 'wc_bogo',
            'meta_query' => array(
                array(
                    'key'     => '_selected_tags',
                    'value'   => (string)$tag_id,
                    'compare' => 'LIKE'
                )
            )
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $status = get_post_meta(get_the_ID(), '_bogo_deal_status', true);
                if ($status !== 'yes') {
                    continue;
                }
                $bogo_rules = array(
                    'min_qty'       => intval(get_post_meta(get_the_ID(), '_min_qty', true)),
                    'free_qty'      => intval(get_post_meta(get_the_ID(), '_free_qty', true)),
                    'max_qty'       => intval(get_post_meta(get_the_ID(), '_max_qty', true)),
                    'discount_type' => get_post_meta(get_the_ID(), '_discount_type', true),
                    'discount_value'=> floatval(get_post_meta(get_the_ID(), '_discount_value', true)),
                    'recursive'     => get_post_meta(get_the_ID(), '_recursive', true),
                );
                wp_reset_postdata();
                return $bogo_rules;
            }
        }
        return false;
    }
// Function to retrieve BOGO rules for a Tags End Here

// Show 'Free' instead of price for free items Start Here
    add_filter('woocommerce_cart_item_price', 'display_free_product_text', 10, 3);
    function display_free_product_text($price, $cart_item, $cart_item_key) {
        if (isset($cart_item['bogo_free']) && $cart_item['bogo_free'] === true) {
            return __('Free!', 'your-text-domain');
        }
        return $price;
    }
// Show 'Free' instead of price for free items End Here

