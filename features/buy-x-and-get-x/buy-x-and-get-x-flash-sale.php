<?php

//  Flash Sales For Buy X and Get X code goes here.

// Checking the product has been enable for Dicount or Bogo Offer then Appelies Start here 
	add_filter( 'woocommerce_sale_flash', 'bogo_custom_sale_flash', 20, 3 );
	function bogo_custom_sale_flash( $original, $post, $product ) {
	    // Only show on simple products (you can adjust as needed)
	    if ( 'product' !== $post->post_type ) {
	        return $original;
	    }

	    // Fetch BOGO rules
	    $rules = get_bogo_rules_for_product( $post->ID );
	    if ( ! $rules ) {
	        return $original;
	    }

	    $min_qty       = intval( $rules['min_qty'] );
	    $free_qty      = intval( $rules['free_qty'] );
	    $discount_type = $rules['discount_type'];
	    $discount_val  = floatval( $rules['discount_value'] );

	    // Determine display text
	    $text = '';
	    if ( $discount_type === 'percentage' && $discount_val > 0 ) {
	        $text = sprintf( 'SALE: buy %d and get %d - %g%% off', $min_qty, $free_qty, $discount_val );
	    } elseif ( $free_qty > 0 && $min_qty > 0 ) {
	        $percent = floor( $free_qty / $min_qty * 100 );
	        if ( $percent >= 100 ) {
	            $text = 'SALE up to 100%';
	        } else {
	            $text = sprintf( 'SALE: buy %d and get %d - %d%% off', $min_qty, $free_qty,$percent );
	        }
	    }
	    
	    // Output JavaScript with necessary data
	    if ( $text ) {
	        ?>
	        <script type="text/javascript">
	            var bogoSaleData = {
	                text: '<?php echo esc_js( $text ); ?>',
	                valid: true
	            };
	        </script>
	        <?php
	    }

	    return $original;
	}

	add_action( 'woocommerce_after_single_product_summary', 'bogo_sale_flash_display', 15 );
	function bogo_sale_flash_display() {
	    // No output here, as we are using JavaScript to append the sale badge
	}
// Checking the product has been enable for Dicount or Bogo Offer then Appelies Start here 

// Adding to Flash checking from product ID Start Here 
	add_action( 'woocommerce_before_single_product_summary', function() {
	    global $product;
	    echo apply_filters( 'woocommerce_sale_flash', '', get_post( $product->get_id() ), $product );
	}, 10 );
// Adding to Flash checking from product ID End Here 

// Load the JS for Bogo Sales Flash Start Here 
	add_action( 'wp_footer', 'bogo_sale_flash_js' );
	function bogo_sale_flash_js() {
	    ?>
	    <style type="text/css">
	         .woocommerce span.onsale {
	            background-color: red; /* Change this to your desired color */
	            color: white; /* Optional: Change text color for better contrast */
	            font-weight: bold;
	        }
	    </style>
	    <script type="text/javascript">
	        jQuery(document).ready(function($) {
	            // Check if bogoSaleData exists and is valid
	            if (typeof bogoSaleData !== 'undefined' && bogoSaleData.valid) {
	                var saleBadgeHtml = '<span class="onsale bogo-sale-badge"> <h5>' + bogoSaleData.text + '</h5></span>';
	                
	                // Append the sale badge to the product gallery wrapper
	                $('.woocommerce-product-gallery__wrapper').after('<div class="bogo-sale-badge-wrapper">' + saleBadgeHtml + '</div>');	   
	            }
	        });
	    </script>
	    <?php
	}
// Load the JS for Bogo Sales Flash Start Here 