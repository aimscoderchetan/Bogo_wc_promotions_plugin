<?php 
// //  Cart Adjustment Using Coupon has been Listed Here

//  Saving Cart Discount meta box Start here 
    function save_bogo_cart_discount_meta($post_id) {
        // Check if it's a valid post save
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
        if ('wc_bogo' != get_post_type($post_id)) return $post_id;

        // Save the discount type
        if (isset($_POST['cart_discount_type'])) {
            update_post_meta($post_id, '_cart_discount_type', sanitize_text_field($_POST['cart_discount_type']));
        }

        // Save the discount value
        if (isset($_POST['cart_discount_value'])) {
            update_post_meta($post_id, '_cart_discount_value', sanitize_text_field($_POST['cart_discount_value']));
        }

        // Save the max discount value
        if (isset($_POST['cart_max_discount_value'])) {
            update_post_meta($post_id, '_cart_max_discount_value', sanitize_text_field($_POST['cart_max_discount_value']));
        }

        // Save the tooltip message
        if (isset($_POST['cart_discount_tooltip'])) {
            update_post_meta($post_id, '_cart_discount_tooltip', sanitize_textarea_field($_POST['cart_discount_tooltip']));
        }

        return $post_id;
    }
    add_action('save_post', 'save_bogo_cart_discount_meta');
//  Saving Cart Discount meta box End here 

// Cart Discount on 
    add_action( 'woocommerce_before_calculate_totals', 'wc_bogo_auto_apply_cart_coupon', 20 );
    add_action( 'woocommerce_cart_loaded_from_session', 'wc_bogo_auto_apply_cart_coupon', 20 );
    function wc_bogo_auto_apply_cart_coupon() {

        if ( is_admin() || ! WC()->cart ) {
            return;
        }

        // Fetch all active BOGO deals that have a cart discount configured
        $deals = get_posts( [
            'post_type'      => 'wc_bogo',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'   => '_bogo_deal_status',
                    'value' => 'yes',
                ],
                [
                    'key'   => '_cart_discount_type',
                    'compare' => 'EXISTS',
                ],
            ],
        ] );

        // Step to manage coupons
        $existing_coupons = WC()->cart->get_coupons();
        $active_coupon_code = '';

        // Check if we have valid deals and act accordingly
        $subtotal = WC()->cart->get_subtotal();
        foreach ( $deals as $deal ) {
            $deal_id = $deal->ID;

            $status = get_post_meta($deal_id, '_bogo_deal_status', true);
            if ($status !== 'yes') {
                continue;  // Skip if the deal status is not 'yes'
            }

            // Read cart discount settings from post meta
            $type    = get_post_meta( $deal_id, '_cart_discount_type',       true );
            $cart_discount_tooltip = get_post_meta($deal_id, '_cart_discount_tooltip', true);
            $value   = floatval(    get_post_meta( $deal_id, '_cart_discount_value',      true ) );
            $max_cap = floatval(    get_post_meta( $deal_id, '_cart_max_discount_value',  true ) );
            $tip     = get_post_meta( $deal_id, '_cart_discount_tooltip',     true );
            // $min_price = get_post_meta( $deal_id, 'min_price_for_discount',     true );
            // sanitize_text_field( $_POST['min_price_for_discount'] ?? '' );
            $min_price_for_discount =  get_option( 'min_price_for_discount', 0 );
            error_log("hello i am in coupoun " . $min_price_for_discount);
            $discount_based_on =  get_option( 'wc_bogo_discount_based_on', "regular_price" );
            error_log("hello i am in coupoun  discount_based_on " . $discount_based_on);

            // Minimum spend fallback
            // $min_price_for_discount = 10;
            if ( $subtotal < $min_price_for_discount ) {
                continue;
            }

            // Compute final discount amount
            switch ( $type ) {
                case 'fixed':
                    $discount_amount = $value;
                    $coupon_type     = 'fixed_cart';
                    break;
                case 'percentage':
                    $discount_amount = $subtotal * ( $value / 100 );
                    if ( $max_cap > 0 && $discount_amount > $max_cap ) {
                        $discount_amount = $max_cap;
                    }
                    $coupon_type     = 'percent';
                    break;
                case 'fixed_per_item':
                    $qty             = 0;
                    foreach ( WC()->cart->get_cart() as $item ) {
                        $qty += $item['quantity'];
                    }
                    $discount_amount = $qty * $value;
                    $coupon_type     = 'fixed_cart';
                    break;
                default:
                    continue 2; // Unsupported type
            }

            // Nothing to do if no discount
            if ( $discount_amount <= 0 ) {
                continue;
            }

            // Build a unique coupon code and create/update it
            $code      = ' ' . $cart_discount_tooltip;
            $coupon_id = wc_get_coupon_id_by_code( $code );

            if ( ! $coupon_id ) {
                $coupon = new WC_Coupon();
                $coupon->set_code( $code );
            } else {
                $coupon = new WC_Coupon( $coupon_id );
            }

            $coupon->set_discount_type( $coupon_type );
            $coupon->set_amount(        $discount_amount );
            $coupon->set_description(   wp_strip_all_tags( $tip ) );
            $coupon->set_individual_use( true );
            $coupon->set_minimum_amount( $min_price_for_discount );
            $coupon->save();

            // Apply it if not already in the cart
            if ( ! WC()->cart->has_discount( $code ) ) {
                WC()->cart->apply_coupon( $code );
                WC()->cart->calculate_totals();
            }

            $active_coupon_code = $code; // Keep track of applied coupon
            // Only one BOGO coupon at a time
            break;
        }

        // Remove invalid coupons
        foreach ( $existing_coupons as $existing_coupon ) {
            if ( strpos( $existing_coupon, 'bogo-' ) === 0 && $existing_coupon !== $active_coupon_code ) {
                WC()->cart->remove_coupon( $existing_coupon );
            }
        }
    }
// Cart Discount on

// function checks for discount based on global setting Regular_Price or Sale_Price start here
    // add_action( 'woocommerce_before_calculate_totals', 'wc_bogo_adjust_cart_item_prices', 30, 1 );
    // function wc_bogo_adjust_cart_item_prices( $cart ) {
    //     if ( is_admin() && ! defined( 'DOING_AJAX' ) || ! WC()->cart ) {
    //         return;
    //     }

    //     // Choose 'regular_price' or 'sale_price'
    //     $based_on = get_option( 'wc_bogo_discount_based_on', 'regular_price' );

    //     foreach ( $cart->get_cart() as $item ) {
    //         $product = $item['data'];

    //         // If sale price is selected but not set, fall back
    //         if ( $based_on == 'sales_price' ) {
    //             $new_price = $product->get_sale_price();
    //             error_log( "get_sale_price" . $new_price);
    //         } else {
    //             $new_price = $product->get_regular_price();
    //             error_log( "get_regular_price". $new_price);
    //         }

    //         $product->set_price( $new_price );
    //     }
    // }
// function checks for discount based on global setting Regular_Price or Sale_Price End here
