<?php 

//  Buy X and get Y Meta Values Save here and Bogo Discount added Here

function save_bogo_buy_x_and_get_y_discount_meta($post_id) {
    // Bail early if doing autosave or wrong post type
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

    // error log all prevous values for this fields ?
    if (isset($_POST['min_qty_buy_xy'])) {
        update_post_meta($post_id, '_min_qty_buy_xy', intval($_POST['min_qty_buy_xy']));
    }

    if (isset($_POST['max_qty_buy_xy'])) {
        update_post_meta($post_id, '_max_qty_buy_xy', intval($_POST['max_qty_buy_xy']));
    }

    if (isset($_POST['free_qty_buy_xy'])) {
        update_post_meta($post_id, '_free_qty_buy_xy', intval($_POST['free_qty_buy_xy']));
    }

    if (isset($_POST['discount_type_buy_xy'])) {
        update_post_meta($post_id, '_discount_type_buy_xy', sanitize_text_field($_POST['discount_type_buy_xy']));
    }

    if (isset($_POST['discount_value_buy_xy'])) {
        update_post_meta($post_id, '_discount_value_buy_xy', floatval($_POST['discount_value_buy_xy']));
    }
    
    if (isset($_POST['recursive_buy_xy'])) {
        update_post_meta($post_id, '_recursive_buy_xy', isset($_POST['recursive_buy_xy']) ? 1 : 0);
    }

    // SAVE BUY SECTION
    if (isset($_POST['wc_bogo_filter_type_cust_buy'])) {
        $filter_buy = sanitize_text_field($_POST['wc_bogo_filter_type_cust_buy']);
        update_post_meta($post_id, '_wc_bogo_filter_type_cust_buy', $filter_buy);

        // Reset unused
        if ($filter_buy !== 'product') delete_post_meta($post_id, '_selected_products_cust_buy');
        if ($filter_buy !== 'category') delete_post_meta($post_id, '_selected_categories_cust_buy');
        if ($filter_buy !== 'tags') delete_post_meta($post_id, '_selected_tags_cust_buy');

        switch ($filter_buy) {
            case 'product':
                if (isset($_POST['selected_product_ids_cust_buy'])) {
                    $raw_ids = explode(',', sanitize_text_field($_POST['selected_product_ids_cust_buy']));
                    $valid_ids = array_filter($raw_ids, function ($id) {
                        return get_post($id) && get_post_type($id) === 'product';
                    });
                    update_post_meta($post_id, '_selected_products_cust_buy', implode(',', $valid_ids));
                }
                break;

            case 'category':
                if (isset($_POST['selected_category_ids_cust_buy'])) {
                    $raw_ids = explode(',', sanitize_text_field($_POST['selected_category_ids_cust_buy']));
                    $valid_ids = array_filter($raw_ids, function ($id) {
                        $term = get_term($id, 'product_cat');
                        return $term && !is_wp_error($term);
                    });
                    update_post_meta($post_id, '_selected_categories_cust_buy', implode(',', $valid_ids));
                }
                break;

            case 'tags':
                if (isset($_POST['selected_tag_ids_cust_buy'])) {
                    $raw_ids = explode(',', sanitize_text_field($_POST['selected_tag_ids_cust_buy']));
                    $valid_ids = array_filter($raw_ids, function ($id) {
                        $term = get_term($id, 'product_tag');
                        return $term && !is_wp_error($term);
                    });
                    update_post_meta($post_id, '_selected_tags_cust_buy', implode(',', $valid_ids));
                }
                break;
        }
    }

    // SAVE GET SECTION
    if (isset($_POST['wc_bogo_filter_type_cust_get'])) {
        $filter_get = sanitize_text_field($_POST['wc_bogo_filter_type_cust_get']);
        update_post_meta($post_id, '_wc_bogo_filter_type_cust_get', $filter_get);

        // Reset unused
        if ($filter_get !== 'product') delete_post_meta($post_id, '_selected_products_cust_get');
        if ($filter_get !== 'category') delete_post_meta($post_id, '_selected_categories_cust_get');
        if ($filter_get !== 'tags') delete_post_meta($post_id, '_selected_tags_cust_get');

        switch ($filter_get) {
            case 'product':
                if (isset($_POST['selected_product_ids_cust_get'])) {
                    $raw_ids = explode(',', sanitize_text_field($_POST['selected_product_ids_cust_get']));
                    $valid_ids = array_filter($raw_ids, function ($id) {
                        return get_post($id) && get_post_type($id) === 'product';
                    });
                    update_post_meta($post_id, '_selected_products_cust_get', implode(',', $valid_ids));
                }
                break;

            case 'category':
                if (isset($_POST['selected_category_ids_cust_get'])) {
                    $raw_ids = explode(',', sanitize_text_field($_POST['selected_category_ids_cust_get']));
                    $valid_ids = array_filter($raw_ids, function ($id) {
                        $term = get_term($id, 'product_cat');
                        return $term && !is_wp_error($term);
                    });
                    update_post_meta($post_id, '_selected_categories_cust_get', implode(',', $valid_ids));
                }
                break;

            case 'tags':
                if (isset($_POST['selected_tag_ids_cust_get'])) {
                    $raw_ids = explode(',', sanitize_text_field($_POST['selected_tag_ids_cust_get']));
                    $valid_ids = array_filter($raw_ids, function ($id) {
                        $term = get_term($id, 'product_tag');
                        return $term && !is_wp_error($term);
                    });
                    update_post_meta($post_id, '_selected_tags_cust_get', implode(',', $valid_ids));
                }
                break;
        }
    }
}
add_action('save_post', 'save_bogo_buy_x_and_get_y_discount_meta');

// create a new function

function log_selected_products_and_terms($post_id) {
    // Check if the post is of the correct type
    if (get_post_type($post_id) !== 'wc_bogo') {
        return;
    }

    // Check and log selected products for buying
    $selected_products_cust_buy = get_post_meta($post_id, '_selected_products_cust_buy', true);
    $selected_categories_cust_buy = get_post_meta($post_id, '_selected_categories_cust_buy', true);
    $selected_tags_cust_buy = get_post_meta($post_id, '_selected_tags_cust_buy', true);

    // Log 'buy' selection
    if (!empty($selected_products_cust_buy)) {
        error_log('Selected Products for Buy: ' . $selected_products_cust_buy);
    }

    if (!empty($selected_categories_cust_buy)) {
        error_log('Selected Categories for Buy: ' . $selected_categories_cust_buy);
    }

    if (!empty($selected_tags_cust_buy)) {
        error_log('Selected Tags for Buy: ' . $selected_tags_cust_buy);
    }

    // Check and log selected products for getting
    $selected_products_cust_get = get_post_meta($post_id, '_selected_products_cust_get', true);
    $selected_categories_cust_get = get_post_meta($post_id, '_selected_categories_cust_get', true);
    $selected_tags_cust_get = get_post_meta($post_id, '_selected_tags_cust_get', true);
    $status = get_post_meta(get_the_ID(), '_bogo_deal_status', true);
    $min_qty = (int) get_post_meta($post_id, '_min_qty_buy_xy', true);
    $discount_type = get_post_meta(get_the_ID(), '_discount_type_buy_xy', true);
    $discount_value = floatval(get_post_meta(get_the_ID(), '_discount_value_buy_xy', true));

    $recursive = get_post_meta($post_id, '_recursive_buy_xy', true) ? 1 : 0;
     if (!empty($recursive)) {
        error_log('recursive Products for Get: ' . $recursive);
    }

    if ($discount_type == 'free') {
        error_log('free');
    } elseif ($discount_type == 'percentage') {
        error_log('percentage: ' . $discount_value);
    } elseif ($discount_type == 'fixed') {
        error_log('fixed: ' . $discount_value);
    } else { 

    }

    if (!empty($status)) {
        error_log('status Products for Get: ' . $status);
    }

    if (!empty($min_qty)) {
        error_log('min_qty Products for Get: ' . $min_qty);
    }
    // Log 'get' selection
    if (!empty($selected_products_cust_get)) {
        error_log('Selected Products for Get: ' . $selected_products_cust_get);
    }

    if (!empty($selected_categories_cust_get)) {
        error_log('Selected Categories for Get: ' . $selected_categories_cust_get);
    }

    if (!empty($selected_tags_cust_get)) {
        error_log('Selected Tags for Get: ' . $selected_tags_cust_get);
    }
}
add_action('save_post', 'log_selected_products_and_terms', 10);

// add_action('woocommerce_before_calculate_totals', 'apply_bogo_discount_if_matches', 10);
// function apply_bogo_discount_if_matches($cart) {
//     if (is_admin() && !defined('DOING_AJAX')) return;
//     if (did_action('apply_bogo_discount_if_matches')) return;
//     do_action('apply_bogo_discount_if_matches');

//     $bogo_posts = get_posts([
//         'post_type' => 'wc_bogo',
//         'posts_per_page' => -1,
//         'post_status' => 'publish',
//     ]);

//     foreach ($bogo_posts as $bogo_post) {   
//         $status = get_post_meta($bogo_post->ID, '_bogo_deal_status', true);  
//         if ($status !== 'yes') {
//             foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
//                 if (isset($cart_item['bogo_applied']) && $cart_item['bogo_applied'] == $bogo_post->ID) {
//                     $cart->remove_cart_item($cart_item_key);
//                 }
//             }
//             continue;
//         }

//         $filter_type = get_post_meta($bogo_post->ID, '_wc_bogo_filter_type_cust_buy', true);
//         $bonus_product_ids = get_post_meta($bogo_post->ID, '_selected_products_cust_get', true);
//         $bonus_product_ids = is_array($bonus_product_ids) ? $bonus_product_ids : explode(',', $bonus_product_ids);

//         $min_qty = (int) get_post_meta($bogo_post->ID, '_min_qty_buy_xy', true);
//         $max_qty = (int) get_post_meta($bogo_post->ID, '_max_qty_buy_xy', true);
//         $free_qty = (int) get_post_meta($bogo_post->ID, '_free_qty_buy_xy', true);
//         $recursive = get_post_meta($bogo_post->ID, '_recursive_buy_xy', true) ? 1 : 0;

//         $discount_type = get_post_meta($bogo_post->ID, '_discount_type_buy_xy', true);
//         $discount_value = floatval(get_post_meta($bogo_post->ID, '_discount_value_buy_xy', true));

//         $eligible_total_free_qty = 0;

//         foreach ($cart->get_cart() as $cart_item) {
//             $product_id = $cart_item['product_id'];
//             $match = false;

//             switch ($filter_type) {
//                 case 'product':
//                     $selected = get_post_meta($bogo_post->ID, '_selected_products_cust_buy', true);
//                     $selected = is_array($selected) ? $selected : explode(',', $selected);
//                     $match = in_array($product_id, $selected);
//                     break;
//                 case 'category':
//                     $selected = get_post_meta($bogo_post->ID, '_selected_categories_cust_buy', true);
//                     $selected = is_array($selected) ? $selected : explode(',', $selected);
//                     $product_terms = wc_get_product_term_ids($product_id, 'product_cat');
//                     $match = !empty(array_intersect($selected, $product_terms));
//                     break;
//                 case 'tags':
//                     $selected = get_post_meta($bogo_post->ID, '_selected_tags_cust_buy', true);
//                     $selected = is_array($selected) ? $selected : explode(',', $selected);
//                     $product_terms = wc_get_product_term_ids($product_id, 'product_tag');
//                     $match = !empty(array_intersect($selected, $product_terms));
//                     break;
//             }

//             if ($match) {
//                 $qty = $cart_item['quantity'];
//                 if ($qty >= $min_qty) {
//                     $times = $recursive ? floor($qty / $min_qty) : 1;
//                     // error_log('times'. $times);
//                     $eligible_total_free_qty += ($free_qty * $times);
//                 }
//             }

//         }

//         if ($max_qty > 0) {
//             $eligible_total_free_qty = min($eligible_total_free_qty, $max_qty);
//         }

//         // Remove non-matching bonus items
//         foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
//             if (
//                 isset($cart_item['bogo_applied']) &&
//                 $cart_item['bogo_applied'] == $bogo_post->ID &&
//                 !in_array($cart_item['product_id'], $bonus_product_ids)
//             ) {
//                 $cart->remove_cart_item($cart_item_key);
//             }
//         }

//         // Apply percentage/fixed discount
//         if (($discount_type === 'percentage' || $discount_type === 'fixed') && $eligible_total_free_qty > 0) {
//             foreach ($bonus_product_ids as $bonus_id) {
//                 foreach ($cart->get_cart() as $cart_item) {
//                     if (
//                         $cart_item['product_id'] == $bonus_id &&
//                         isset($cart_item['bogo_applied']) &&
//                         $cart_item['bogo_applied'] == $bogo_post->ID
//                     ) {
//                         $item_price = $cart_item['data']->get_price();
//                         $discount = $discount_type === 'percentage'
//                             ? ($item_price * $discount_value / 100) * $eligible_total_free_qty
//                             : $discount_value * $eligible_total_free_qty;

//                         $label = $discount_type === 'percentage'
//                             ? sprintf(__('BOGO Percentage Discount (%s%%)', 'your-text-domain'), $discount_value)
//                             : sprintf(__('BOGO Fixed Discount (-$%.2f)', 'your-text-domain'), $discount_value);

//                         $cart->add_fee($label, -$discount, false);
//                         break 2;
//                     }
//                 }
//             }
//         }

//         // Handle "free" discount type: adjust quantities instead of removing
//         if ($eligible_total_free_qty > 0 && !empty($bonus_product_ids) && $discount_type === 'free') {
//             foreach ($bonus_product_ids as $bonus_id) {
//                 $existing_qty = 0;
//                 $cart_item_key_to_update = null;

//                 foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
//                     if (
//                         $cart_item['product_id'] == $bonus_id &&
//                         isset($cart_item['bogo_applied']) &&
//                         $cart_item['bogo_applied'] == $bogo_post->ID
//                     ) {
//                         $existing_qty = $cart_item['quantity'];
//                         $cart_item_key_to_update = $cart_item_key;
//                         break;
//                     }
//                 }

//                 $to_adjust = $eligible_total_free_qty - $existing_qty;

//                 if ($to_adjust > 0) {
//                     $cart->add_to_cart($bonus_id, $to_adjust, 0, [], [
//                         'bogo_applied' => $bogo_post->ID
//                     ]);
//                 } elseif ($to_adjust < 0 && $cart_item_key_to_update) {
//                     $cart->cart_contents[$cart_item_key_to_update]['quantity'] = $eligible_total_free_qty;
//                 }
//             }
//         } elseif ($eligible_total_free_qty <= 0) {
//             foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
//                 if (
//                     isset($cart_item['bogo_applied']) &&
//                     $cart_item['bogo_applied'] == $bogo_post->ID
//                 ) {
//                     $cart->remove_cart_item($cart_item_key);
//                 }
//             }
//         }
//     }
// }
add_action('woocommerce_before_calculate_totals', 'apply_bogo_discount_if_matches', 10);
function apply_bogo_discount_if_matches($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    if (did_action('apply_bogo_discount_if_matches')) return;
    do_action('apply_bogo_discount_if_matches');

    $bogo_posts = get_posts([
        'post_type' => 'wc_bogo',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    foreach ($bogo_posts as $bogo_post) {
        $status = get_post_meta($bogo_post->ID, '_bogo_deal_status', true);
        if ($status !== 'yes') {
            foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                if (isset($cart_item['bogo_applied']) && $cart_item['bogo_applied'] == $bogo_post->ID) {
                    $cart->remove_cart_item($cart_item_key);
                }
            }
            continue;
        }

        $filter_type = get_post_meta($bogo_post->ID, '_wc_bogo_filter_type_cust_buy', true);
        $bonus_product_ids = get_post_meta($bogo_post->ID, '_selected_products_cust_get', true);
        $bonus_product_ids = is_array($bonus_product_ids) ? $bonus_product_ids : explode(',', $bonus_product_ids);

        $min_qty = (int) get_post_meta($bogo_post->ID, '_min_qty_buy_xy', true);
        $max_qty = (int) get_post_meta($bogo_post->ID, '_max_qty_buy_xy', true);
        $free_qty = (int) get_post_meta($bogo_post->ID, '_free_qty_buy_xy', true);
        $recursive = get_post_meta($bogo_post->ID, '_recursive_buy_xy', true) ? 1 : 0;

        $discount_type = get_post_meta($bogo_post->ID, '_discount_type_buy_xy', true);
        $discount_value = floatval(get_post_meta($bogo_post->ID, '_discount_value_buy_xy', true));

        $eligible_total_free_qty = 0;

        foreach ($cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $match = false;

            switch ($filter_type) {
                case 'product':
                    $selected = get_post_meta($bogo_post->ID, '_selected_products_cust_buy', true);
                    $selected = is_array($selected) ? $selected : explode(',', $selected);
                    $match = in_array($product_id, $selected);
                    break;
                case 'category':
                    $selected = get_post_meta($bogo_post->ID, '_selected_categories_cust_buy', true);
                    $selected = is_array($selected) ? $selected : explode(',', $selected);
                    $product_terms = wc_get_product_term_ids($product_id, 'product_cat');
                    $match = !empty(array_intersect($selected, $product_terms));
                    break;
                case 'tags':
                    $selected = get_post_meta($bogo_post->ID, '_selected_tags_cust_buy', true);
                    $selected = is_array($selected) ? $selected : explode(',', $selected);
                    $product_terms = wc_get_product_term_ids($product_id, 'product_tag');
                    $match = !empty(array_intersect($selected, $product_terms));
                    break;
            }

            if ($match) {
                $qty = $cart_item['quantity'];
                if ($qty >= $min_qty) {
                    $times = $recursive ? floor($qty / $min_qty) : 1;
                    $eligible_total_free_qty += ($free_qty * $times);
                }
            }
        }

        if ($max_qty > 0) {
            $eligible_total_free_qty = min($eligible_total_free_qty, $max_qty);
        }

        // Remove non-matching bonus items
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            if (
                isset($cart_item['bogo_applied']) &&
                $cart_item['bogo_applied'] == $bogo_post->ID &&
                !in_array($cart_item['product_id'], $bonus_product_ids)
            ) {
                $cart->remove_cart_item($cart_item_key);
            }
        }

        // Apply percentage/fixed discount
        if (($discount_type === 'percentage' || $discount_type === 'fixed') && $eligible_total_free_qty > 0) {
            foreach ($bonus_product_ids as $bonus_id) {
                foreach ($cart->get_cart() as $cart_item) {
                    if (
                        $cart_item['product_id'] == $bonus_id &&
                        isset($cart_item['bogo_applied']) &&
                        $cart_item['bogo_applied'] == $bogo_post->ID
                    ) {
                        $item_price = $cart_item['data']->get_price();
                        $discount = $discount_type === 'percentage'
                            ? ($item_price * $discount_value / 100) * $eligible_total_free_qty
                            : $discount_value * $eligible_total_free_qty;

                        $label = $discount_type === 'percentage'
                            ? sprintf(__('BOGO Percentage Discount (%s%%)', 'your-text-domain'), $discount_value)
                            : sprintf(__('BOGO Fixed Discount (-$%.2f)', 'your-text-domain'), $discount_value);

                        $cart->add_fee($label, -$discount, false);
                        break 2;
                    }
                }
            }
        }

        // Handle "free" discount type
        if ($eligible_total_free_qty > 0 && !empty($bonus_product_ids) && $discount_type === 'free') {
            foreach ($bonus_product_ids as $bonus_id) {
                $existing_qty = 0;
                $cart_item_key_to_update = null;

                foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                    if (
                        $cart_item['product_id'] == $bonus_id &&
                        isset($cart_item['bogo_applied']) &&
                        $cart_item['bogo_applied'] == $bogo_post->ID
                    ) {
                        $existing_qty = $cart_item['quantity'];
                        $cart_item_key_to_update = $cart_item_key;
                        break;
                    }
                }

                $to_adjust = $eligible_total_free_qty - $existing_qty;

                if ($to_adjust > 0) {
                    $cart->add_to_cart($bonus_id, $to_adjust, 0, [], [
                        'bogo_applied' => $bogo_post->ID
                    ]);
                } elseif ($to_adjust < 0 && $cart_item_key_to_update) {
                    $cart->cart_contents[$cart_item_key_to_update]['quantity'] = $eligible_total_free_qty;
                }
            }

            // Set bonus item prices to 0
            foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                if (
                    in_array($cart_item['product_id'], $bonus_product_ids) &&
                    isset($cart_item['bogo_applied']) &&
                    $cart_item['bogo_applied'] == $bogo_post->ID
                ) {
                    $cart_item['data']->set_price(0);
                }
            }

        } elseif ($eligible_total_free_qty <= 0) {
            foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                if (
                    isset($cart_item['bogo_applied']) &&
                    $cart_item['bogo_applied'] == $bogo_post->ID
                ) {
                    $cart->remove_cart_item($cart_item_key);
                }
            }
        }
    }
}
