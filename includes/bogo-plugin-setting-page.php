<?php 
// bogo-plugin-setting-page.php

// Tabbing Section on the Setting Page start here
	function wc_bogo_render_settings_page() {
	    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'pricing_rules';

		 // ─── Load ALL your Tab 2 options into variables ───
		 $enable_flash_sal_buy_x_and_x    = get_option( 'enable_flash_sal_buy_x_and_x', 'no' );
		 $buy_x_get_x_free_message        = get_option( 'buy_x_get_x_free_message', '#ffffff' );
		 $buy_x_get_x_free_bg_color       = get_option( 'buy_x_get_x_free_bg_color', '#ffffff' );
		 $buy_x_get_x_free_bg_code        = get_option( 'buy_x_get_x_free_bg_code', '#ffffff' );
		 $buy_x_get_x_free_font_color     = get_option( 'buy_x_get_x_free_font_color', '#ffffff' );
		 $buy_x_get_x_free_font_code      = get_option( 'buy_x_get_x_free_font_code', '#ffffff' );
		 $buy_x_get_x_percentage_message  = get_option( 'buy_x_get_x_percentage_message', '#ffffff' );
		 $buy_x_get_x_percentage_bg_color = get_option( 'buy_x_get_x_percentage_bg_color', '#ffffff' );
		 $buy_x_get_x_percentage_bg_code  = get_option( 'buy_x_get_x_percentage_bg_code', '#ffffff' );
		 $buy_x_get_x_percentage_font_color = get_option( 'buy_x_get_x_percentage_font_color', '#ffffff' );
		 $buy_x_get_x_percentage_font_code  = get_option( 'buy_x_get_x_percentage_font_code', '#ffffff' );
		 $buy_x_get_x_fixed_message       = get_option( 'buy_x_get_x_fixed_message', '#ffffff' );
		 $buy_x_get_x_fixed_bg_color      = get_option( 'buy_x_get_x_fixed_bg_color', '#ffffff' );
		 $buy_x_get_x_fixed_bg_code       = get_option( 'buy_x_get_x_fixed_bg_code', '#ffffff' );
		 $buy_x_get_x_fixed_font_color    = get_option( 'buy_x_get_x_fixed_font_color', '#ffffff' );
		 $buy_x_get_x_fixed_font_code     = get_option( 'buy_x_get_x_fixed_font_code', '#ffffff' );
	 
		 $enable_flash_sal_buy_x_and_y    = get_option( 'enable_flash_sal_buy_x_and_y', 'no' );
		 $buy_x_get_y_free_message        = get_option( 'buy_x_get_y_free_message', '#ffffff' );
		 $buy_x_get_y_free_bg_color       = get_option( 'buy_x_get_y_free_bg_color', '#ffffff' );
		 $buy_x_get_y_free_bg_code        = get_option( 'buy_x_get_y_free_bg_code', '' );
		 $buy_x_get_y_free_font_color     = get_option( 'buy_x_get_y_free_font_color', '#ffffff' );
		 $buy_x_get_y_free_font_code      = get_option( 'buy_x_get_y_free_font_code', '#ffffff' );
		 $buy_x_get_y_percentage_message  = get_option( 'buy_x_get_y_percentage_message', '#ffffff' );
		 $buy_x_get_y_percentage_bg_color = get_option( 'buy_x_get_y_percentage_bg_color', '#ffffff' );
		 $buy_x_get_y_percentage_bg_code  = get_option( 'buy_x_get_y_percentage_bg_code', '#ffffff' );
		 $buy_x_get_y_percentage_font_color = get_option( 'buy_x_get_y_percentage_font_color', '#ffffff' );
		 $buy_x_get_y_percentage_font_code  = get_option( 'buy_x_get_y_percentage_font_code', '#ffffff' );
		 $buy_x_get_y_fixed_message       = get_option( 'buy_x_get_y_fixed_message', '#ffffff' );
		 $buy_x_get_y_fixed_bg_color      = get_option( 'buy_x_get_y_fixed_bg_color', '#ffffff' );
		 $buy_x_get_y_fixed_bg_code       = get_option( 'buy_x_get_y_fixed_bg_code', '#ffffff' );
		 $buy_x_get_y_fixed_font_color    = get_option( 'buy_x_get_y_fixed_font_color', '#ffffff' );
		 $buy_x_get_y_fixed_font_code     = get_option( 'buy_x_get_y_fixed_font_code', '#ffffff' );


		 $selected_discount_type = get_option( 'wc_bogo_discount_based_on', 'regular_price' );
	    ?>
	    <div class="wrap">
	        <h1><?php esc_html_e('BOGO Settings', 'wc-bogo'); ?></h1>

	        <div class="wc-bogo-settings-header">
	            <div class="wc-bogo-tabs">
	                <a href="<?php echo esc_url(add_query_arg('tab', 'pricing_rules')); ?>" class="nav-tab <?php echo ($active_tab === 'pricing_rules') ? 'nav-tab-active' : ''; ?>">
	                    <?php esc_html_e('Product Pricing', 'wc-bogo'); ?>
	                </a>
	                <a href="<?php echo esc_url(add_query_arg('tab', 'cart_discounts')); ?>" class="nav-tab <?php echo ($active_tab === 'cart_discounts') ? 'nav-tab-active' : ''; ?>">
	                    <?php esc_html_e('Cart Discounts', 'wc-bogo'); ?>
	                </a>
	                <a href="<?php echo esc_url(add_query_arg('tab', 'settings')); ?>" class="nav-tab <?php echo ($active_tab === 'settings') ? 'nav-tab-active' : ''; ?>">
	                    <?php esc_html_e('Settings', 'wc-bogo'); ?>
	                </a>
	            </div>	            
	        </div>

	        <div class="wc-bogo-settings-content">
	            <?php if ('pricing_rules' === $active_tab) { ?>
	                <div id="pricing_rules-content" class="common-container">
	                	<div class="yaydp-empty-state__placeholder"><svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_1_13)"><path d="M49.92 21.38L47.48 6.49C47.15 4.45 45.55 2.85 43.51 2.52L28.62 0.0800016C26.6 -0.249998 24.54 0.410002 23.09 1.86L1.85999 23.09C-0.630007 25.58 -0.630007 29.61 1.85999 32.1L17.9 48.13C20.39 50.62 24.42 50.62 26.91 48.13L48.14 26.9C49.58 25.46 50.25 23.4 49.92 21.38Z" fill="#E2E4E7"></path><path d="M17.18 15.62C21.4933 15.62 24.99 12.1233 24.99 7.81C24.99 3.49666 21.4933 0 17.18 0C12.8667 0 9.37 3.49666 9.37 7.81C9.37 12.1233 12.8667 15.62 17.18 15.62Z" fill="#A2AAB2"></path><path d="M20.63 7.04H17.95V4.35C17.95 3.92 17.6 3.58 17.18 3.58C16.75 3.58 16.41 3.93 16.41 4.35V7.03H13.73C13.3 7.03 12.96 7.38 12.96 7.8C12.96 8.23 13.31 8.57 13.73 8.57H16.4V11.25C16.4 11.68 16.75 12.02 17.17 12.02C17.6 12.02 17.94 11.67 17.94 11.25V8.59H20.62C21.05 8.59 21.39 8.24 21.39 7.82C21.41 7.38 21.06 7.04 20.63 7.04Z" fill="#E2E4E7"></path></g><defs><clipPath id="clip0_1_13"><rect width="50" height="50" fill="white"></rect></clipPath></defs></svg></div><!-- Replace with an actual icon class -->
					    <h1>Manage Product Pricing Rule</h1>
					    <h3>Create product pricing rules that affect the price of products. You can also create product fees or discounts.</h3>
					    <button class="btn-primary"><a href="post-new.php?post_type=wc_bogo">Create Rule</a></button>
	                </div>
	            <?php } elseif ('cart_discounts' === $active_tab) { ?>
	                <div id="cart_discounts-content" class="common-container">
	                    <div class="yaydp-empty-state__placeholder"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_1_37)"><path d="M31.192 40H10.08C7.816 40 5.856 38.408 5.4 36.192L1.328 16.496C0.991999 14.888 2.224 13.376 3.872 13.376H37.408C39.056 13.376 40.288 14.888 39.952 16.496L35.88 36.192C35.408 38.408 33.456 40 31.192 40Z" fill="#E2E4E7"></path><path d="M9.552 18.896C9.176 18.896 8.792 18.784 8.456 18.536C7.624 17.928 7.44 16.768 8.048 15.936L19.104 0.768C19.456 0.288 20.008 0.008 20.608 0C21.2 0 21.76 0.28 22.104 0.76L33.208 15.936C33.816 16.768 33.632 17.928 32.808 18.536C31.976 19.144 30.816 18.96 30.208 18.136L20.608 5.016L11.056 18.136C10.696 18.632 10.128 18.896 9.552 18.896Z" fill="#E2E4E7"></path><path d="M17.064 27.376C16.4 27.376 15.848 27.224 15.4 26.904C14.952 26.584 14.728 26.048 14.728 25.272V23.064C14.728 22.296 14.952 21.752 15.4 21.432C15.848 21.112 16.4 20.96 17.064 20.96C17.504 20.96 17.888 21.032 18.248 21.16C18.6 21.296 18.88 21.52 19.088 21.824C19.296 22.128 19.4 22.544 19.4 23.064V25.272C19.4 25.776 19.296 26.192 19.088 26.504C18.88 26.816 18.6 27.04 18.248 27.176C17.896 27.304 17.504 27.376 17.064 27.376ZM17.064 26.024C17.304 26.024 17.496 25.96 17.632 25.848C17.768 25.736 17.84 25.536 17.84 25.28V23.072C17.84 22.816 17.768 22.624 17.632 22.504C17.496 22.384 17.304 22.328 17.064 22.328C16.824 22.328 16.632 22.392 16.504 22.504C16.376 22.616 16.304 22.816 16.304 23.072V25.28C16.304 25.536 16.376 25.728 16.504 25.848C16.64 25.96 16.824 26.024 17.064 26.024ZM17.984 33.048C17.728 33.048 17.52 32.96 17.352 32.784C17.192 32.608 17.112 32.416 17.112 32.224C17.112 32.128 17.136 32.032 17.192 31.928L22.656 20.712C22.784 20.456 23 20.32 23.312 20.32C23.528 20.32 23.744 20.4 23.936 20.544C24.128 20.696 24.232 20.896 24.232 21.144C24.232 21.264 24.208 21.352 24.168 21.44L18.688 32.656C18.632 32.776 18.536 32.864 18.408 32.936C18.264 33.016 18.128 33.048 17.984 33.048ZM24.2 32.384C23.536 32.384 22.984 32.232 22.536 31.912C22.088 31.592 21.864 31.056 21.864 30.28V28.064C21.864 27.304 22.088 26.752 22.536 26.432C22.984 26.112 23.536 25.96 24.2 25.96C24.64 25.96 25.024 26.032 25.384 26.16C25.736 26.296 26.016 26.52 26.224 26.824C26.432 27.128 26.536 27.544 26.536 28.064V30.28C26.536 30.784 26.432 31.2 26.224 31.512C26.016 31.824 25.736 32.048 25.384 32.184C25.04 32.32 24.64 32.384 24.2 32.384ZM24.2 31.032C24.44 31.032 24.632 30.968 24.768 30.856C24.912 30.736 24.976 30.544 24.976 30.288V28.072C24.976 27.816 24.904 27.624 24.768 27.504C24.624 27.384 24.44 27.328 24.2 27.328C23.96 27.328 23.768 27.392 23.64 27.504C23.504 27.624 23.44 27.816 23.44 28.072V30.288C23.44 30.544 23.512 30.736 23.64 30.856C23.784 30.968 23.968 31.032 24.2 31.032Z" fill="#F0F0F1"></path><path d="M6.248 23.288C9.69868 23.288 12.496 20.4907 12.496 17.04C12.496 13.5893 9.69868 10.792 6.248 10.792C2.79732 10.792 0 13.5893 0 17.04C0 20.4907 2.79732 23.288 6.248 23.288Z" fill="#A2AAB2"></path><path d="M9.016 16.416H6.872V14.272C6.872 13.928 6.592 13.656 6.256 13.656C5.912 13.656 5.64 13.936 5.64 14.272V16.416H3.48C3.136 16.416 2.864 16.696 2.864 17.032C2.864 17.376 3.144 17.648 3.48 17.648H5.624V19.792C5.624 20.136 5.904 20.408 6.24 20.408C6.584 20.408 6.856 20.128 6.856 19.792V17.648H9C9.344 17.648 9.616 17.368 9.616 17.032C9.632 16.696 9.36 16.416 9.016 16.416Z" fill="#E2E4E7"></path></g><defs><clipPath id="clip0_1_37"><rect width="40" height="40" fill="white"></rect></clipPath></defs></svg></div>
	                    <h1>Manage Cart Discounts Rule</h1>
					    <h3>Cart discounts rules is used to create cart discount coupons that apply at checkout. You can also combine discount at Settings section.</h3>
	                </div>
	            <?php } elseif ('settings' === $active_tab) { ?>
	                <div id="settings-content" class="common-container">
	                    <h3><?php // esc_html_e('General Settings', 'wc-bogo'); ?></h3>
						    <div id="settings-content" class="common-container">
						        <div class="vertical-tabs-container">
						            <div class="vertical-tab-menu">
						                <!-- <button class="tab-link active" data-tab="tab1">General</button> -->
						                <button class="tab-link active" data-tab="tab2">Flash Sales Setting</button>
						                <button class="tab-link" data-tab="tab3">Discounts</button>
						                <button class="tab-link" data-tab="tab4">Rules</button>
						                <button class="tab-link" data-tab="tab5">Advanced</button>
						            </div>

						            <div class="vertical-tab-content">
						                <div id="tab1" class="tab-pane">
						                    <p>General settings go here.</p>
						                </div>
										<div id="tab2" class="tab-pane active">
											<?php include WOCOMMERCE_BOGO_PLUGIN . 'features/settings/message-fields-template.php'; ?>
										</div>									
										<div id="tab3" class="tab-pane">
										<?php include WOCOMMERCE_BOGO_PLUGIN . 'features\settings\cart-discount-regular-price.php'; ?>											<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
												
										</div>
						                <div id="tab4" class="tab-pane">
						                    <p>Rule configuration goes here.</p>
						                </div>
						                <div id="tab5" class="tab-pane">
						                    <p>Advanced configurations go here.</p>
						                </div>
						            </div>
						        </div>
						    </div>

						    <script>
						        document.querySelectorAll('.tab-link').forEach(function (button) {
						            button.addEventListener('click', function () {
						                const tabId = this.getAttribute('data-tab');

						                // Remove active classes
						                document.querySelectorAll('.tab-link').forEach(btn => btn.classList.remove('active'));
						                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

						                // Activate the selected tab
						                this.classList.add('active');
						                document.getElementById(tabId).classList.add('active');
						            });
						        });
						    </script>
	                </div>
	            <?php } else { ?>
	            	<div id="else-content" class="common-container">
	                	<h3><?php esc_html_e('Nothing here', 'wc-bogo'); ?></h3>
	                </div>
	            <?php } ?>
	        </div>
	    </div>

	    <?php
	}
// Tabbing Section on the Setting Page End here

// Save Flash Sale Tab 2 Settings Function start here 
	add_action( 'admin_post_save_flash_sale_settings', function() {
		if (
			! isset( $_POST['wc_bogo_flash_sale_nonce'] ) ||
			! wp_verify_nonce( $_POST['wc_bogo_flash_sale_nonce'], 'wc_bogo_flash_sale_save' )
		) {
			wp_die( esc_html__( 'Security check failed', 'wc-bogo' ) );
		}

		// ✔️ Buy X → X toggle
		update_option( 'enable_flash_sal_buy_x_and_x', isset( $_POST['enable_flash_sal_buy_x_and_x'] ) ? 'yes' : 'no' );


		// ✔️ Buy X → X fields
		update_option( 'buy_x_get_x_free_message', sanitize_text_field( $_POST['buy_x_get_x_free_message'] ?? '' ) );
		update_option( 'buy_x_get_x_free_bg_color', sanitize_hex_color( $_POST['buy_x_get_x_free_bg_color'] ?? '' ) );
		update_option( 'buy_x_get_x_free_bg_code', sanitize_text_field( $_POST['buy_x_get_x_free_bg_code'] ?? '' ) );
		update_option( 'buy_x_get_x_free_font_color', sanitize_hex_color( $_POST['buy_x_get_x_free_font_color'] ?? '' ) );
		update_option( 'buy_x_get_x_free_font_code', sanitize_text_field( $_POST['buy_x_get_x_free_font_code'] ?? '' ) );

		update_option( 'buy_x_get_x_percentage_message', sanitize_text_field( $_POST['buy_x_get_x_percentage_message'] ?? '' ) );
		update_option( 'buy_x_get_x_percentage_bg_color', sanitize_hex_color( $_POST['buy_x_get_x_percentage_bg_color'] ?? '' ) );
		update_option( 'buy_x_get_x_percentage_bg_code', sanitize_text_field( $_POST['buy_x_get_x_percentage_bg_code'] ?? '' ) );
		update_option( 'buy_x_get_x_percentage_font_color', sanitize_hex_color( $_POST['buy_x_get_x_percentage_font_color'] ?? '' ) );
		update_option( 'buy_x_get_x_percentage_font_code', sanitize_text_field( $_POST['buy_x_get_x_percentage_font_code'] ?? '' ) );

		update_option( 'buy_x_get_x_fixed_message', sanitize_text_field( $_POST['buy_x_get_x_fixed_message'] ?? '' ) );
		update_option( 'buy_x_get_x_fixed_bg_color', sanitize_hex_color( $_POST['buy_x_get_x_fixed_bg_color'] ?? '' ) );
		update_option( 'buy_x_get_x_fixed_bg_code', sanitize_text_field( $_POST['buy_x_get_x_fixed_bg_code'] ?? '' ) );
		update_option( 'buy_x_get_x_fixed_font_color', sanitize_hex_color( $_POST['buy_x_get_x_fixed_font_color'] ?? '' ) );
		update_option( 'buy_x_get_x_fixed_font_code', sanitize_text_field( $_POST['buy_x_get_x_fixed_font_code'] ?? '' ) );

		// ✔️ Buy X → Y toggle
		update_option( 'enable_flash_sal_buy_x_and_y', isset( $_POST['enable_flash_sal_buy_x_and_y'] ) ? 'yes' : 'no' );

		// ✔️ Buy X → Y fields
		update_option( 'buy_x_get_y_free_message', sanitize_text_field( $_POST['buy_x_get_y_free_message'] ?? '' ) );
		update_option( 'buy_x_get_y_free_bg_color', sanitize_hex_color( $_POST['buy_x_get_y_free_bg_color'] ?? '' ) );
		update_option( 'buy_x_get_y_free_bg_code', sanitize_text_field( $_POST['buy_x_get_y_free_bg_code'] ?? '' ) );
		update_option( 'buy_x_get_y_free_font_color', sanitize_hex_color( $_POST['buy_x_get_y_free_font_color'] ?? '' ) );
		update_option( 'buy_x_get_y_free_font_code', sanitize_text_field( $_POST['buy_x_get_y_free_font_code'] ?? '' ) );

		update_option( 'buy_x_get_y_percentage_message', sanitize_text_field( $_POST['buy_x_get_y_percentage_message'] ?? '' ) );
		update_option( 'buy_x_get_y_percentage_bg_color', sanitize_hex_color( $_POST['buy_x_get_y_percentage_bg_color'] ?? '' ) );
		update_option( 'buy_x_get_y_percentage_bg_code', sanitize_text_field( $_POST['buy_x_get_y_percentage_bg_code'] ?? '' ) );
		update_option( 'buy_x_get_y_percentage_font_color', sanitize_hex_color( $_POST['buy_x_get_y_percentage_font_color'] ?? '' ) );
		update_option( 'buy_x_get_y_percentage_font_code', sanitize_text_field( $_POST['buy_x_get_y_percentage_font_code'] ?? '' ) );

		update_option( 'buy_x_get_y_fixed_message', sanitize_text_field( $_POST['buy_x_get_y_fixed_message'] ?? '' ) );
		update_option( 'buy_x_get_y_fixed_bg_color', sanitize_hex_color( $_POST['buy_x_get_y_fixed_bg_color'] ?? '' ) );
		update_option( 'buy_x_get_y_fixed_bg_code', sanitize_text_field( $_POST['buy_x_get_y_fixed_bg_code'] ?? '' ) );
		update_option( 'buy_x_get_y_fixed_font_color', sanitize_hex_color( $_POST['buy_x_get_y_fixed_font_color'] ?? '' ) );
		update_option( 'buy_x_get_y_fixed_font_code', sanitize_text_field( $_POST['buy_x_get_y_fixed_font_code'] ?? '' ) );



		update_option( 'enable_flash_sal_buy_x_and_y', isset( $_POST['enable_flash_sal_buy_x_and_y'] ) ? 'yes' : 'no' );
		// update_option( 'wc_bogo_discount_based_on', sanitize_text_field( $_POST['discount_based_on'] ?? 'regular_price' ) );

		$value_to_save = sanitize_text_field( $_POST['discount_based_on'] ?? 'regular_price' );
		update_option( 'wc_bogo_discount_based_on', $value_to_save );
		error_log( '[BOGO LOG] wc_bogo_discount_based_on saved value: ' . $value_to_save );

		// Redirect back to your settings page
		wp_redirect( admin_url( 'admin.php?page=wc-bogo-settings&tab=settings&saved=1' ) );
		exit;
	});
// Save Flash Sale Tab 2 Settings Function End here 

