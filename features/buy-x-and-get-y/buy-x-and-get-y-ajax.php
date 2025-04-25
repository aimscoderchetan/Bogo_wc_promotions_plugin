<?php 

// Customer Buys Start Here 

// AJAX Handler for Searching Products on buy X and Get Y will  Start here 
    add_action('wp_ajax_bogo_search_ajax_cust_buy', 'bogo_search_ajax_handler_cust_buy');
    add_action('wp_ajax_nopriv_bogo_search_ajax_cust_buy', 'bogo_search_ajax_handler_cust_buy');
    function bogo_search_ajax_handler_cust_buy() {
        $query = sanitize_text_field($_POST['query'] ?? '');
        $filter = sanitize_text_field($_POST['filter'] ?? '');
        
        $results = [];

        if ($filter === 'product' || $filter === 'all_products') {
            // Search for products by title
            $args = [
                'post_type' => 'product',
                's' => $query,
                'posts_per_page' => 10
            ];
            $products = new WP_Query($args);
            
            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();
                    $results[] = ['id' => get_the_ID(), 'text' => get_the_title()];
                }
                }
                wp_reset_postdata();
        }

        wp_send_json($results);
    }
// AJAX Handler for Searching Products on buy X and Get Y will End here 

// AJAX Handler for Searching Products on buy X and Get Y Category & Tags Start here 

	// AJAX search for product categories
	add_action('wp_ajax_bogo_search_category_ajax_cust_buy', 'bogo_search_category_ajax_cust_buy');
    add_action('wp_ajax_nopriv_bogo_search_category_ajax_cust_buy', 'bogo_search_category_ajax_cust_buy');	
	function bogo_search_category_ajax_cust_buy() {
	    $query = sanitize_text_field($_POST['query'] ?? '');
	    $terms = get_terms([
	        'taxonomy' => 'product_cat',
	        'hide_empty' => false,
	        'search' => $query,
	        'number' => 20,
	    ]);

	    $results = [];
	    foreach ($terms as $term) {
	        $results[] = [
	            'id' => $term->term_id,
	            'text' => $term->name,
	        ];
	    }

	    wp_send_json($results);
	}

	// AJAX search for product tags
		add_action('wp_ajax_bogo_search_tag_ajax_cust_buy', 'bogo_search_tag_ajax_cust_buy');
		add_action('wp_ajax_nopriv_bogo_search_tag_ajax_cust_buy', 'bogo_search_tag_ajax_cust_buy');
		function bogo_search_tag_ajax_cust_buy() {
		    $query = sanitize_text_field($_POST['query'] ?? '');
		    $terms = get_terms([
		        'taxonomy' => 'product_tag',
		        'hide_empty' => false,
		        'search' => $query,
		        'number' => 20,
		    ]);

		    $results = [];
		    foreach ($terms as $term) {
		        $results[] = [
		            'id' => $term->term_id,
		            'text' => $term->name,
		        ];
		    }

		    wp_send_json($results);
		}
// AJAX Handler for Searching Products on buy X and Get Y Category & Tags End here 

// Customer Buys End Here 

// Customer Gets Start Here 

// AJAX Handler for Searching Products on buy X and Get Y will  Start here 
    add_action('wp_ajax_bogo_search_ajax_cust_get', 'bogo_search_ajax_handler_cust_get');
    add_action('wp_ajax_nopriv_bogo_search_ajax_cust_get', 'bogo_search_ajax_handler_cust_get');
    function bogo_search_ajax_handler_cust_get() {
        $query = sanitize_text_field($_POST['query'] ?? '');
        $filter = sanitize_text_field($_POST['filter'] ?? '');
        
        $results = [];

        if ($filter === 'product' || $filter === 'all_products') {
            // Search for products by title
            $args = [
                'post_type' => 'product',
                's' => $query,
                'posts_per_page' => 10
            ];
            $products = new WP_Query($args);
            
            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();
                    $results[] = ['id' => get_the_ID(), 'text' => get_the_title()];
                }
                }
                wp_reset_postdata();
        }

        wp_send_json($results);
    }
// AJAX Handler for Searching Products on buy X and Get Y will End here 

// AJAX Handler for Searching Products on buy X and Get Y Category & Tags Start here 

	// AJAX search for product categories
	add_action('wp_ajax_bogo_search_category_ajax_cust_get', 'bogo_search_category_ajax_cust_get');
    add_action('wp_ajax_nopriv_bogo_search_category_ajax_cust_get', 'bogo_search_category_ajax_cust_get');	
	function bogo_search_category_ajax_cust_get() {
	    $query = sanitize_text_field($_POST['query'] ?? '');
	    $terms = get_terms([
	        'taxonomy' => 'product_cat',
	        'hide_empty' => false,
	        'search' => $query,
	        'number' => 20,
	    ]);

	    $results = [];
	    foreach ($terms as $term) {
	        $results[] = [
	            'id' => $term->term_id,
	            'text' => $term->name,
	        ];
	    }

	    wp_send_json($results);
	}

	// AJAX search for product tags
		add_action('wp_ajax_bogo_search_tag_ajax_cust_get', 'bogo_search_tag_ajax_cust_get');
		add_action('wp_ajax_nopriv_bogo_search_tag_ajax_cust_get', 'bogo_search_tag_ajax_cust_get');
		function bogo_search_tag_ajax_cust_get() {
		    $query = sanitize_text_field($_POST['query'] ?? '');
		    $terms = get_terms([
		        'taxonomy' => 'product_tag',
		        'hide_empty' => false,
		        'search' => $query,
		        'number' => 20,
		    ]);

		    $results = [];
		    foreach ($terms as $term) {
		        $results[] = [
		            'id' => $term->term_id,
		            'text' => $term->name,
		        ];
		    }

		    wp_send_json($results);
		}
// AJAX Handler for Searching Products on buy X and Get Y Category & Tags End here 

// Customer Gets Start Here 
