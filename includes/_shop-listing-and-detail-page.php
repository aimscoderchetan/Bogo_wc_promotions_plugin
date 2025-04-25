<?php
/**
 * ADMIN: Add custom metabox for single‐step BOGO option.
 */
    class Woo_BOGO_Single_Metabox {
        public function __construct() {
            // Hook to add custom meta box to the product editing screen.
            add_action('add_meta_boxes', [$this, 'add_bogo_metabox']);
            // Hook to save the data from the meta box when the post is saved.
            add_action('save_post', [$this, 'save_bogo_metabox_data']);
        }

        // Function to add the BOGO options meta box to the product edit screen.
        public function add_bogo_metabox() {
            add_meta_box(
                'woo_bogo_single_metabox', // Unique ID for the meta box.
                __('BOGO Product Options', 'woocommerce'), // Title of the meta box.
                [$this, 'render_bogo_metabox'], // Callback function that renders the content of the meta box.
                'product', // Post type where the meta box will appear.
                'side', // Context (side or normal).
                'default' // Priority of the meta box.
            );
        }

        // Function to render the BOGO options form inside the meta box.
        public function render_bogo_metabox($post) {
            // Generate a nonce field for security.
            wp_nonce_field('woo_bogo_single_nonce', 'woo_bogo_single_nonce');

            // Retrieve saved values for enabling BOGO and selected products.
            $enabled      = get_post_meta($post->ID, '_woo_bogo_enabled', true) === 'yes';
            $bogo_products = get_post_meta($post->ID, '_woo_bogo_products', true) ?: [];

            // Query all published products to display as options for BOGO.
            $products = get_posts([
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            ]);
            ?>
            <p>
                <label for="woo_bogo_enabled">
                    <input type="checkbox" id="woo_bogo_enabled" name="woo_bogo_enabled" value="yes" <?php checked($enabled); ?>/>
                    <?php esc_html_e('Enable BOGO functionality', 'woocommerce'); ?>
                </label>
            </p>

            <div id="bogo_options" style="display: <?php echo $enabled ? 'block' : 'none'; ?>;">
                <p>
                    <label for="woo_bogo_products"><?php esc_html_e('Select Free Product(s) (choose one or more):', 'woocommerce'); ?></label>
                    <select name="woo_bogo_products[]" id="woo_bogo_products" multiple style="width:100%;">
                        <?php foreach ($products as $product) : ?>
                            <option value="<?php echo esc_attr($product->ID); ?>" <?php echo in_array($product->ID, (array) $bogo_products, true) ? 'selected' : ''; ?>>
                                <?php echo esc_html(get_the_title($product->ID)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>
            
            <script>
                // Toggle options display based on checkbox status.
                document.addEventListener('DOMContentLoaded', function () {
                    var checkbox = document.getElementById('woo_bogo_enabled');
                    var optionsDiv = document.getElementById('bogo_options');

                    checkbox.addEventListener('change', function () {
                        optionsDiv.style.display = this.checked ? 'block' : 'none';
                    });
                });
            </script>
            <?php
        }

        // Function to save the BOGO setting and selected products when the post is saved.
        public function save_bogo_metabox_data($post_id) {
            // Security: Check nonce, autosave, and user permissions.
            if (!isset($_POST['woo_bogo_single_nonce']) || !wp_verify_nonce($_POST['woo_bogo_single_nonce'], 'woo_bogo_single_nonce')) {
                return;
            }
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Save enabled status.
            update_post_meta($post_id, '_woo_bogo_enabled', isset($_POST['woo_bogo_enabled']) ? 'yes' : 'no');

            // Save selected free product(s).
            if (!empty($_POST['woo_bogo_products'])) {
                update_post_meta($post_id, '_woo_bogo_products', array_map('absint', $_POST['woo_bogo_products']));
            } else {
                delete_post_meta($post_id, '_woo_bogo_products');
            }
        }
    }

    new Woo_BOGO_Single_Metabox();

/**
 * FRONTEND: Display BOGO selection options on the product page.
 */
    add_action('woocommerce_before_add_to_cart_button', 'woo_bogo_single_display_custom_interface');
    function woo_bogo_single_display_custom_interface() {
        global $product;

        if (!$product instanceof WC_Product) {
            return;
        }

        $enabled = get_post_meta($product->get_id(), '_woo_bogo_enabled', true) === 'yes';
        if (!$enabled) {
            return; 
        }

        $bogo_products = get_post_meta($product->get_id(), '_woo_bogo_products', true) ?: [];
        if (empty($bogo_products)) {
            return; 
        }

        echo '<div id="woo-bogo-single-interface" style="margin-bottom:20px;">';
        echo '<h3>' . esc_html__('Select Your Free Product Option', 'woocommerce') . '</h3>';
        
        echo '<div class="custom-dropdown" style="position: relative; width: 100%; max-width: 400px;">';
        echo '<input type="hidden" id="selected-bogo-product" name="woo_bogo_single_select" />';
        echo '<div class="dropdown-display" style="padding: 10px; border: 1px solid #ccc; cursor: pointer;">' . esc_html__('Select a product...', 'woocommerce') . '</div>';
        echo '<div class="dropdown-options" style="display: none; position: absolute; background: white; border: 1px solid #ccc; width: 100%; z-index: 10; max-height: 200px; overflow-y: auto;">';

        foreach ($bogo_products as $pid) {
            $product_image = get_the_post_thumbnail($pid, 'thumbnail');
            $product_title = esc_html(get_the_title($pid));
            $price = get_post_meta($pid, '_price', true);

            if ($price) {
                $formatted_price = wc_price($price);
            } else {
                $formatted_price = '';
            }

            echo '<div class="dropdown-option" data-value="' . esc_attr($pid) . '" style="display: flex; align-items: center; padding: 10px; cursor: pointer;">';
            echo $product_image;
            echo '<span style="margin-left: 10px;">' . $product_title . '</span>';
            echo '<span style="margin-left: 10px; color: #888;">' . $formatted_price . '</span>';
            echo '</div>';
        }

        echo '</div>'; // Close options container
        echo '</div>'; // Close custom dropdown
        echo '</div>'; // Close interface div

        ?>
        <script>
            document.querySelector('.dropdown-display').addEventListener('click', function() {
                const options = document.querySelector('.dropdown-options');
                options.style.display = options.style.display === 'none' ? 'block' : 'none';
            });

            document.querySelectorAll('.dropdown-option').forEach(option => {
                option.addEventListener('click', function() {
                    const selectedValue = option.getAttribute('data-value');
                    const selectedTitle = option.textContent;
                    
                    document.getElementById('selected-bogo-product').value = selectedValue;
                    document.querySelector('.dropdown-display').textContent = selectedTitle;
                    document.querySelector('.dropdown-options').style.display = 'none';
                });
            });

            document.addEventListener('click', function(e) {
                const dropdown = document.querySelector('.custom-dropdown');
                if (!dropdown.contains(e.target)) {
                    dropdown.querySelector('.dropdown-options').style.display = 'none';
                }
            });
        </script>
        <?php
    }
    
    add_filter('woocommerce_add_to_cart_validation', 'woo_bogo_single_process_add_to_cart', 10, 3);
    function woo_bogo_single_process_add_to_cart($passed, $product_id, $quantity) {
        if (!isset($_POST['woo_bogo_single_select'])) {
            return $passed;
        }

        $selected_product = absint($_POST['woo_bogo_single_select']);
        error_log("Selected BOGO Product ID: $selected_product"); // Logging

        $enabled = get_post_meta($product_id, '_woo_bogo_enabled', true) === 'yes';
        if ($enabled && $selected_product > 0) {
            // Add the free product to the cart with extra meta data.
            WC()->cart->add_to_cart(
                $selected_product,
                1,
                0,
                [],
                [
                    'bogo_free'        => true,
                    'selected_bogo_id' => $selected_product
                ]
            );

            error_log("Added BOGO Product to Cart: $selected_product"); // Logging
        }

        return $passed;
    }
    
    add_action( 'woocommerce_checkout_create_order_line_item', 'add_bogo_order_item_meta', 10, 4 );
    function add_bogo_order_item_meta( $item, $cart_item_key, $values, $order ) {
        if ( ! empty( $values['selected_bogo_id'] ) ) {
            $item->add_meta_data( __( 'BOGO Selection', 'woocommerce' ), $values['selected_bogo_id'], true );
        }
    }

/**
 * Adjust cart prices for BOGO products to ensure they are free.
 */
    add_action('woocommerce_before_calculate_totals', 'woo_bogo_adjust_cart_prices', 10, 1);
    function woo_bogo_adjust_cart_prices($cart) {
        // Return early if in admin screen and not doing AJAX.
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        // Loop through each item in the cart and set the price of BOGO items to zero.
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            if (!empty($cart_item['bogo_free']) && isset($cart_item['data'])) {
                $cart_item['data']->set_price(0);
            }
        }
    }
    
    add_action('woocommerce_add_to_cart', 'auto_add_bogo_product_to_cart', 10, 6);
    function auto_add_bogo_product_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
        // Check if BOGO is enabled for the product
        $bogo_enabled = get_post_meta($product_id, '_woo_bogo_enabled', true);
        if ($bogo_enabled !== 'yes') {
            return;
        }

        // Retrieve associated BOGO products
        $bogo_products = get_post_meta($product_id, '_woo_bogo_products', true);
        if (empty($bogo_products) || !is_array($bogo_products)) {
            return;
        }

        // Select the first BOGO product
        $free_product_id = (int) $bogo_products[0];

        // Check if the free product is already in the cart to prevent duplicates
        foreach (WC()->cart->get_cart() as $cart_item) {
            if (isset($cart_item['bogo_free']) && $cart_item['bogo_free'] === true && $cart_item['product_id'] === $free_product_id) {
                return;
            }
        }

        // Add the free product to the cart with a quantity matching the original product
        WC()->cart->add_to_cart($free_product_id, $quantity, 0, [], [
            'bogo_free'        => true,
            'bogo_parent_id'   => $product_id,
        ]);
    }

    add_action('woocommerce_before_calculate_totals', 'set_bogo_product_price_zero');
    function set_bogo_product_price_zero($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        // Loop through each cart item
        foreach ($cart->get_cart() as $cart_item) {
            if (isset($cart_item['bogo_free']) && $cart_item['bogo_free'] === true) {
                $cart_item['data']->set_price(0);
            }
        }
    }

    add_action('woocommerce_checkout_create_order_line_item', 'add_bogo_meta_to_order_item', 10, 4);
    function add_bogo_meta_to_order_item($item, $cart_item_key, $values, $order) {
        if (isset($values['bogo_free']) && $values['bogo_free'] === true) {
            $parent_product = wc_get_product($values['bogo_parent_id']);
            if ($parent_product) {
                $item->add_meta_data(__('BOGO From', 'woocommerce'), $parent_product->get_name(), true);
            }
        }
    }
