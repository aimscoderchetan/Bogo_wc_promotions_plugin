<?php

// AJAX Handler for Searching Products on buy X and Get X Same product  Start here 
    add_action('wp_ajax_bogo_search_ajax', 'bogo_search_ajax_handler');
    add_action('wp_ajax_nopriv_bogo_search_ajax', 'bogo_search_ajax_handler');
    function bogo_search_ajax_handler() {
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
// AJAX Handler for Searching Products on buy X and Get X Same product End here 

// AJAX Handler for Searching Products on buy X and Get X Same Category & Tags Start here 

	// AJAX search for product categories
	add_action('wp_ajax_bogo_search_category_ajax', 'bogo_search_category_ajax');
    add_action('wp_ajax_nopriv_bogo_search_category_ajax', 'bogo_search_category_ajax');	
	function bogo_search_category_ajax() {
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
		add_action('wp_ajax_bogo_search_tag_ajax', 'bogo_search_tag_ajax');
		add_action('wp_ajax_nopriv_bogo_search_tag_ajax', 'bogo_search_tag_ajax');
		function bogo_search_tag_ajax() {
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
// AJAX Handler for Searching Products on buy X and Get X Same Category & Tags End here 
