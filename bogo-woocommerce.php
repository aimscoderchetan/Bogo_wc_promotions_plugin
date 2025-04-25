<?php
/**
 * Plugin Name: WooCommerce BOGO Discounts
 * Description: Adds BOGO (Buy One Get One) discount functionality to WooCommerce.
 * Version: 1.0
 * Author: Aims InfoSoft 
 * Text Domain: wc-bogo
 */

if (!defined('ABSPATH')) {
    exit; 
}

// Define a global constant for the plugin path Start here
    define('WOCOMMERCE_BOGO_PLUGIN', plugin_dir_path(__FILE__));
// Define a global constant for the plugin path End here


// WooCommerce Dependies code Start here 
   // WooCommerce Dependency Check
    function my_woocommerce_plugin_is_woocommerce_active() {
        return class_exists('WooCommerce');
    }

    // Prevent plugin activation if WooCommerce is not active
    function my_woocommerce_plugin_activation_check() {
        if (!my_woocommerce_plugin_is_woocommerce_active()) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(
                __('<b>WooCommerce BOGO Discounts</b> requires WooCommerce to be installed and activated.', 'my-woocommerce-plugin'),
                'Plugin Activation Error',
                array('back_link' => true)
            );
        }
    }
    register_activation_hook(__FILE__, 'my_woocommerce_plugin_activation_check');
// WooCommerce Dependies code End here 

// Enqueueing custom function Start here
    require_once WOCOMMERCE_BOGO_PLUGIN . 'includes/custom-function.php';  
    require_once WOCOMMERCE_BOGO_PLUGIN . 'includes/bogo-detail-page-option.php';  
    require_once WOCOMMERCE_BOGO_PLUGIN . 'includes/save-options.php';  
    // require_once WOCOMMERCE_BOGO_PLUGIN . 'includes/shop-listing-and-detail-page.php'; 
    require_once WOCOMMERCE_BOGO_PLUGIN . 'includes/ajax-functionality.php'; 
    require_once WOCOMMERCE_BOGO_PLUGIN . 'includes/bogo-plugin-setting-page.php'; 

    // Buy X and Get X Functionality Code 
    require_once WOCOMMERCE_BOGO_PLUGIN . '/features/buy-x-and-get-x/buy-x-and-get-x.php'; 
    require_once WOCOMMERCE_BOGO_PLUGIN . '/features/buy-x-and-get-x/buy-x-and-get-x-ajax.php';
    require_once WOCOMMERCE_BOGO_PLUGIN . '/features/buy-x-and-get-x/buy-x-and-get-x-flash-sale.php';
    

    // Buy X and Get Y Functionality Code 
    require_once WOCOMMERCE_BOGO_PLUGIN . '/features/buy-x-and-get-y/buy-x-and-get-y.php'; 
    require_once WOCOMMERCE_BOGO_PLUGIN . '/features/buy-x-and-get-y/buy-x-and-get-y-ajax.php';
    require_once WOCOMMERCE_BOGO_PLUGIN . '/features/buy-x-and-get-y/buy-x-and-get-y-flash-sale.php';


    // Bogo Flash Sales Code for Sale on product Image
    require_once WOCOMMERCE_BOGO_PLUGIN . 'includes/bogo-flash-sales.php'; 
  
    // Cart Adjustment Functionality Code 
    require_once WOCOMMERCE_BOGO_PLUGIN . '/features/cart-adjustment/cart-adjustment.php';

    // Bogo Deal Scope  Functionality Code 
    require_once WOCOMMERCE_BOGO_PLUGIN . 'includes/class-wc-bogo-deals.php'; 

    function wocommerce_bogo_enqueue_styles() {
        wp_enqueue_style(
            'wocommerce-bogo-style',
            plugin_dir_url(__FILE__) . 'style.css',
            array(),
            '1.0.0',
            'all'
        );
    }
    add_action('wp_enqueue_scripts', 'wocommerce_bogo_enqueue_styles'); 

    function enqueue_bogo_metabox_scripts($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        global $post;
        if ('wc_bogo' !== $post->post_type) {
            return;
        }

        // Enqueue Select2 (if not already loaded)
        wp_enqueue_script('select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', ['jquery'], '4.0.13', true);
        wp_enqueue_style('select2-css', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');

        // Enqueue our custom JS file
        wp_enqueue_script('bogo-metabox-js', plugin_dir_url(__FILE__) . 'assets/js/bogo-metabox.js', ['jquery', 'select2'], '1.0', true);

        // Buy X and Get X functionality JavaScript 
        wp_enqueue_script('buy-x-and-get-x-js', plugin_dir_url(__FILE__) . 'features/buy-x-and-get-x/buy-x-and-get-x.js', ['jquery', 'select2'], '1.0', true);

        // Buy X and Get Y functionality JavaScript 
        wp_enqueue_script('buy-x-and-get-y-javascript', plugin_dir_url(__FILE__) . 'features/buy-x-and-get-y/buy-x-and-get-y.js', ['jquery', 'select2'], '1.0', true);

        // feature Cart Adjustment Js go Here 
        wp_enqueue_script('cart-adjustment-js-functionality', plugin_dir_url(__FILE__) . 'features/cart-adjustment/cart-adjustment-js.js', ['jquery', 'select2'], null, true);

        wp_enqueue_script('wc-bogo-admin-script', plugin_dir_url(__FILE__) . 'assets/js/wc-bogo-admin.js',  ['jquery', 'select2'], '1.0', true);
    }
    add_action('admin_enqueue_scripts', 'enqueue_bogo_metabox_scripts');

    function enqueue_bogo_admin_styles($hook) {
        global $post;
        if (!isset($post) || 'wc_bogo' !== $post->post_type) {
            return;
        }

        // Enqueue the admin CSS file
        wp_enqueue_style('bogo-admin-css', plugin_dir_url(__FILE__) . 'assets/css/bogo-admin.css', [], '1.0');
    }
    add_action('admin_enqueue_scripts', 'enqueue_bogo_admin_styles');
// Enqueueing custom function End here

// Register the Custom Post Type Start here 
    function wc_bogo_register_cpt() {
        $args = array(
            'label'         => __('BOGO Promotions', 'wc-bogo'),
            'public'        => false, 
            'show_ui'       => true, 
            'show_in_menu'  => true, 
            'menu_position' => 25,
            'menu_icon'     => 'dashicons-cart',
            'capability_type' => 'post',
            'supports'      => ['title','revisions'],
            'show_in_rest'  => true,
            'hierarchical'  => false, 
            'has_archive'   => false,
            'exclude_from_search' => true,
            'labels' => array(
            'add_new'            => __('Add New Rule', 'wc-bogo'), 
            'add_new_item'       => __('Add New Rule', 'wc-bogo')
             ),
        );
        register_post_type('wc_bogo', $args);
        }
    add_action('init', 'wc_bogo_register_cpt');
// Register the Custom Post Type End here 

// Register the settings menu under your CPT Start here
    function wc_bogo_add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=wc_bogo', 
            __('BOGO Settings', 'wc-bogo'),
            __('BOGO Settings', 'wc-bogo'),
            'manage_options',
            'wc-bogo-settings',
            'wc_bogo_render_settings_page'
        );
    }
    add_action('admin_menu', 'wc_bogo_add_settings_page');
// Register the settings menu under your CPT End here 

// enqueue Scripts Start here
    function wc_bogo_enqueue_admin_assets($hook) {
        
        if (strpos($hook, 'wc-bogo') === false) {
            return;
        }
        wp_enqueue_style('wc-bogo-admin-style', plugins_url('assets/css/wc-bogo-admin.css', __FILE__), array(), time(), 'all');
    }
    add_action('admin_enqueue_scripts', 'wc_bogo_enqueue_admin_assets');
// enqueue Scripts End here

// Add custom columns to BOGO Deals post type Start Here 
    function wc_bogo_add_custom_columns($columns) {
        $columns = array(
            'cb'             => '<input type="checkbox" />', // Checkbox for bulk actions
            'title'          => __('Title', 'wc-bogo'),
            'discount_type'  => __('Discount Type', 'wc-bogo'),
            'start_date'     => __('Start Date', 'wc-bogo'),
            'expired_on'     => __('Expired On', 'wc-bogo'),
            'status'         => __('Status', 'wc-bogo'),
            'date'           => __('Date'),
            'author'          => __('Author'),
            'usage_count'     => __('Usage Count', 'wc-bogo'),
            'deal_scope'      => __('Scope', 'wc-bogo'),
        );
        return $columns;
    }
    add_filter('manage_wc_bogo_posts_columns', 'wc_bogo_add_custom_columns');
// Add custom columns to BOGO Deals post type End Here  


