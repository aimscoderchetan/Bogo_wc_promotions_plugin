	<?php
	// Flash Sales For Buy X and Get X - Corrected Code with shortcode replacement
	add_action( 'wp_footer', 'bogo_sale_flash_js' );
	function bogo_sale_flash_js() {
		if ( ! is_product() ) return;
	
		global $post;
		$product_id = $post->ID;
	
		// Fetch BOGO rule for this product
		$rules = get_bogo_rules_for_product( $product_id, 'x_and_x' );
		if ( empty( $rules['min_qty'] ) || empty( $rules['free_qty'] ) ) {
			return;
		}
	
		$enabled   = get_option( 'enable_flash_sal_buy_x_and_x', 'no' );
		$min_qty  = intval( $rules['min_qty'] );
		$free_qty = intval( $rules['free_qty'] );
		$type     = $rules['discount_type'];
		$val      = floatval( $rules['discount_value'] );
	
		// Default styles
		$bg_color = '#777777';
		$font_color = '#ffffff';
		$text = '';
	
		if ( $enabled === 'yes' ) {
			// Use dynamic message based on type
			if ( $type === 'percentage' && $val > 0 ) {
				$template = get_option( 'buy_x_get_x_percentage_message', 'SALE: buy [buy-quantity] get [free-quantity] — [discount-value]% off' );
				$bg_color = get_option( 'buy_x_get_x_percentage_bg_color', '#ff0000' );
				$font_color = get_option( 'buy_x_get_x_percentage_font_color', '#ffffff' );
			} elseif ( $type === 'fixed' && $val > 0 ) {
				$template = get_option( 'buy_x_get_x_fixed_message', 'SALE: buy [buy-quantity] get [free-quantity] — $[discount-value] off' );
				$bg_color = get_option( 'buy_x_get_x_fixed_bg_color', '#ff0000' );
				$font_color = get_option( 'buy_x_get_x_fixed_font_color', '#ffffff' );
			} else {
				$template = get_option( 'buy_x_get_x_free_message', 'SALE: buy [buy-quantity] get [free-quantity] free!' );
				$bg_color = get_option( 'buy_x_get_x_free_bg_color', '#00aaff' );
				$font_color = get_option( 'buy_x_get_x_free_font_color', '#ffffff' );
				$percent = floor( $free_qty / $min_qty * 100 );
				if ( $percent >= 100 ) {
					$template = get_option( 'buy_x_get_x_fixed_message', 'SALE up to 100% off!' );
					$bg_color = get_option( 'buy_x_get_x_fixed_bg_color', $bg_color );
					$font_color = get_option( 'buy_x_get_x_fixed_font_color', $font_color );
				}
			}
		} else {
			// Show message based on type even when flash sale is disabled
			if ( $type === 'percentage' && $val > 0 ) {
				$template = get_option( 'buy_x_get_x_disabled_percentage_message', 'Deal: Buy [buy-quantity], Get [free-quantity] — [discount-value]% off!' );
				$bg_color = get_option( 'buy_x_get_x_disabled_percentage_bg_color', '#777777' );
				$font_color = get_option( 'buy_x_get_x_disabled_percentage_font_color', '#ffffff' );
			} elseif ( $type === 'fixed' && $val > 0 ) {
				$template = get_option( 'buy_x_get_x_disabled_fixed_message', 'Deal: Buy [buy-quantity], Get [free-quantity] — Save $[discount-value]!' );
				$bg_color = get_option( 'buy_x_get_x_disabled_fixed_bg_color', '#777777' );
				$font_color = get_option( 'buy_x_get_x_disabled_fixed_font_color', '#ffffff' );
			} else {
				$template = get_option( 'buy_x_get_x_disabled_free_message', 'Deal: Buy [buy-quantity], Get [free-quantity] Free!' );
				$bg_color = get_option( 'buy_x_get_x_disabled_free_bg_color', '#777777' );
				$font_color = get_option( 'buy_x_get_x_disabled_free_font_color', '#ffffff' );
			}
		}
	
		// Replace placeholders
		$text = str_replace(
			array('[buy-quantity]', '[free-quantity]', '[discount-value]'),
			array($min_qty, $free_qty, $val),
			$template
		);
	
		if ( empty( $text ) ) return;
		?>
		<style>
		.woocommerce span.onsale,
		.onsale.bogo-sale-badge {
			background-color: <?php echo esc_attr( $bg_color ); ?>;
			color: <?php echo esc_attr( $font_color ); ?>;
			font-weight: bold;
		}
		</style>
		<script type="text/javascript">
		jQuery(function($){
			var badge = '<span class="onsale bogo-sale-badge"><h5><?php echo esc_js( $text ); ?></h5></span>';
			$('.woocommerce-product-gallery__wrapper').after('<div class="bogo-sale-badge-wrapper">'+ badge +'</div>');
		});
		</script>
		<?php
	}
	
	// Filter WooCommerce sale flash
	add_filter( 'woocommerce_sale_flash', 'bogo_custom_sale_flash', 20, 3 );
	function bogo_custom_sale_flash( $original, $post, $product ) {
		if ( 'product' !== $post->post_type ) {
			return $original;
		}

		// Bail if feature is off
		if ( 'yes' !== get_option( 'enable_flash_sal_buy_x_and_x', 'no' ) ) {
			return $original;
		}

		// Fetch your BOGO rule data
		$rules = get_bogo_rules_for_product( $post->ID, 'x_and_x' );
		if ( ! $rules['min_qty'] || ! $rules['free_qty'] ) {
			return $original;
		}

		$min_qty  = intval( $rules['min_qty'] );
		$free_qty = intval( $rules['free_qty'] );
		$type     = $rules['discount_type'];
		$val      = floatval( $rules['discount_value'] );

		$bg = ''; 
		$font = '';

		if ( $type === 'percentage' && $val > 0 ) {
			$template = get_option( 'buy_x_get_x_percentage_message', 'SALE: buy [buy-quantity] get [free-quantity] — [discount-value]% off' );
			$bg = get_option( 'buy_x_get_x_percentage_bg_color', '#ff0000' );
			$font = get_option( 'buy_x_get_x_percentage_font_color', '#ffffff' );
		} elseif ( $free_qty > 0 && $min_qty > 0 ) {
			$percent = floor( $free_qty / $min_qty * 100 );
			$template = get_option( 'buy_x_get_x_free_message', 'SALE: buy [buy-quantity] get [free-quantity] free!' );
			$bg = get_option( 'buy_x_get_x_free_bg_color', '#00aaff' );
			$font = get_option( 'buy_x_get_x_free_font_color', '#ffffff' );
			if ( $percent >= 100 ) {
				$template = get_option( 'buy_x_get_x_fixed_message', 'SALE up to 100% off!' );
				$bg = get_option( 'buy_x_get_x_fixed_bg_color', $bg );
				$font = get_option( 'buy_x_get_x_fixed_font_color', $font );
			}
		} else {
			return $original;
		}

		// Replace shortcodes
		$text = str_replace(
			array('[buy-quantity]', '[free-quantity]', '[discount-value]'),
			array($min_qty, $free_qty, $val),
			$template
		);

		// Output inline CSS + JS with your dynamic values
		?>
		<style>
		.onsale.bogo-sale-badge {
			background-color: <?php echo esc_attr( $bg ); ?>;
			color: <?php echo esc_attr( $font ); ?>;
			font-weight: bold;
		}
		</style>
		<script type="text/javascript">
		var bogoSaleData = {
			text: '<?php echo esc_js( $text ); ?>',
			valid: true
		};
		</script>
		<?php

		return $original;
	}

	// No output directly - JS appends badge
	add_action( 'woocommerce_after_single_product_summary', 'bogo_sale_flash_display', 15 );
	function bogo_sale_flash_display() {
		// No output here, JS will handle appending the badge
	}

	// Hook before single product summary to apply the sale badge
	add_action( 'woocommerce_before_single_product_summary', function() {
		global $product;
		echo apply_filters( 'woocommerce_sale_flash', '', get_post( $product->get_id() ), $product );
	}, 10 );
	?>
