<?php 
// save-options.php

/**
 *  Meta Box Registration and Display
 */
    function bogo_discount_save_btn_meta_box() {
        add_meta_box(
            'bogo_discount_save_btn_meta_box',           // Unique identifier for the meta box
            'BOGO Toggle Button Status Options',         // Title of the meta box
            'bogo_discount_save_btn_meta_box_callback',    // Callback function to render the meta box
            'wc_bogo',                                    // Post type to attach the meta box to
            'normal',                                     // Position within the edit screen
            'high'                                        // Priority (high = show at the top)
        );
    }   
    add_action('add_meta_boxes', 'bogo_discount_save_btn_meta_box');

    function bogo_discount_save_btn_meta_box_callback($post) {
        // Retrieve stored meta values.
        $status             = get_post_meta($post->ID, '_bogo_deal_status', true);
        $checked            = ($status == 'yes') ? 'checked' : '';

        // Get scheduling toggle and date fields values.
        $schedule_enabled   = get_post_meta($post->ID, '_bogo_schedule_enabled', true);
        $schedule_checked   = ($schedule_enabled === 'yes') ? 'checked' : '';

        $start_date         = get_post_meta($post->ID, '_bogo_start_date', true);
        $end_date           = get_post_meta($post->ID, '_bogo_end_date', true);

        // If dates are not set, use the current time in IST.
        $ist_timezone       = new DateTimeZone('Asia/Kolkata');
        $now_ist            = new DateTime('now', $ist_timezone);
        $now_ist_formatted  = $now_ist->format('Y-m-d\TH:i');

        $start_value = !empty($start_date) ? date('Y-m-d\TH:i', strtotime($start_date)) : $now_ist_formatted;
        $end_value   = !empty($end_date)   ? date('Y-m-d\TH:i', strtotime($end_date))   : $now_ist_formatted;

        wp_nonce_field('bogo_toggle_nonce', 'bogo_toggle_nonce');
        ?>
        <!-- Scheduling Toggle -->
        <p>
            <label class="toggle-switch">
                <!-- Hidden field ensures a value is always posted -->
                <input type="hidden" name="bogo_schedule_enabled" value="no">
                <input type="checkbox" id="bogo_schedule_toggle" name="bogo_schedule_enabled" value="yes" 
                       <?php echo esc_attr($schedule_checked); ?>>
                <span class="slider"></span>
            </label>  Enable Scheduling 
        </p>

        <!-- Date Fields Container: shown only if scheduling is enabled -->
        <div id="bogo_schedule_fields" style="<?php echo ($schedule_enabled === 'yes') ? '' : 'display:none;'; ?>">
            <p>
                <label>Start Date & Time:</label>
                <input type="datetime-local" name="bogo_start_date" 
                       value="<?php echo esc_attr($start_value); ?>">
            </p>
            <p>
                <label>End Date & Time:</label>
                <input type="datetime-local" name="bogo_end_date" 
                       value="<?php echo esc_attr($end_value); ?>">
            </p>
        </div> 
        <br>

        <!-- Deal Status Toggle -->
        <p>
            <label class="toggle-switch">
                <!-- Hidden field to ensure a value is always posted -->
                <input type="hidden" name="bogo_deal_status" value="no">
                <input type="checkbox" id="bogo_deal_toggle" name="bogo_deal_status" value="yes" 
                       <?php echo esc_attr($checked); ?> data-post-id="<?php echo $post->ID; ?>">
                <span class="slider"></span>
            </label> Enable This Option to Affect this Rule
        </p>
        <?php
    }

/**
 *  AJAX Handler for the Toggle
 */
    add_action('wp_ajax_update_bogo_status', function() {
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'bogo_toggle_nonce')) {
            wp_send_json_error("Invalid nonce");
        }

        $post_id = intval($_POST['post_id']);
        $status  = sanitize_text_field($_POST['status']);

        // Update the deal status.
        update_post_meta($post_id, '_bogo_deal_status', $status);
        
        // Optionally update the date fields if passed in the AJAX request.
        if (isset($_POST['bogo_start_date'])) {
            update_post_meta($post_id, '_bogo_start_date', sanitize_text_field($_POST['bogo_start_date']));
        }
        if (isset($_POST['bogo_end_date'])) {
            update_post_meta($post_id, '_bogo_end_date', sanitize_text_field($_POST['bogo_end_date']));
        }
        
        wp_send_json_success("Status updated");
    });

/**
 * 3. Save Meta Box Data on Post Save
 */
    function bogo_discount_save_post($post_id) {
        // Verify nonce.
        if (!isset($_POST['bogo_toggle_nonce']) || !wp_verify_nonce($_POST['bogo_toggle_nonce'], 'bogo_toggle_nonce')) {
            return;
        }
        // Prevent autosave.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        // Check permission.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save the deal status.
        $status = (isset($_POST['bogo_deal_status']) && $_POST['bogo_deal_status'] === 'yes') ? 'yes' : 'no';
        update_post_meta($post_id, '_bogo_deal_status', $status);

        // Save the scheduling toggle.
        $schedule_enabled = (isset($_POST['bogo_schedule_enabled']) && $_POST['bogo_schedule_enabled'] === 'yes') ? 'yes' : 'no';
        update_post_meta($post_id, '_bogo_schedule_enabled', $schedule_enabled);

        // Save the date fields only if scheduling is enabled.
        if ($schedule_enabled === 'yes') {
            if (isset($_POST['bogo_start_date'])) {
                update_post_meta($post_id, '_bogo_start_date', sanitize_text_field($_POST['bogo_start_date']));
            }
            if (isset($_POST['bogo_end_date'])) {
                update_post_meta($post_id, '_bogo_end_date', sanitize_text_field($_POST['bogo_end_date']));
            }
        } else {
            // Optionally, clear the date fields if scheduling is disabled.
            update_post_meta($post_id, '_bogo_start_date', '');
            update_post_meta($post_id, '_bogo_end_date', '');
        }
    }
    add_action('save_post_wc_bogo', 'bogo_discount_save_post');

/**
 *  JavaScript for AJAX Updates and Toggle Behavior
 */
    function wc_bogo_admin_custom_scripts() {
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Scheduling toggle: Show/hide date fields.
            var scheduleToggle = document.getElementById('bogo_schedule_toggle');
            var scheduleFields = document.getElementById('bogo_schedule_fields');
            if (scheduleToggle && scheduleFields) {
                scheduleToggle.addEventListener('change', function() {
                    scheduleFields.style.display = this.checked ? '' : 'none';
                });
            }

            // Centralized function to update BOGO status via AJAX.
            function updateBogoStatus(postId, newStatus) {
                var data = {
                    action: "update_bogo_status",
                    post_id: postId,
                    status: newStatus,
                    security: "<?php echo wp_create_nonce('bogo_toggle_nonce'); ?>"
                };

                fetch(ajaxurl, {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams(data)
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(responseData) {
                    if (responseData.success) {
                        // Refresh both meta box and listing toggles
                        refreshBogoStatusUI(postId);
                    } else {
                        console.error("Error updating status: " + responseData.data);
                    }
                })
                .catch(function(error) {
                    console.error("Fetch error in updateBogoStatus: ", error);
                });
            }

            // Function to refresh UI toggles based on the current status from the server.
            function refreshBogoStatusUI(postId) {
                var data = {
                    action: "get_bogo_status",
                    post_id: postId,
                    security: "<?php echo wp_create_nonce('bogo_toggle_nonce'); ?>"
                };

                fetch(ajaxurl, {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams(data)
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(statusResponse) {
                    if(statusResponse.success) {
                        var currentStatus = statusResponse.data.status;
                        // Update meta box toggle if it exists and matches the post id.
                        var metaBoxToggle = document.getElementById("bogo_deal_toggle");
                        if (metaBoxToggle && metaBoxToggle.dataset.postId == postId) {
                            metaBoxToggle.checked = (currentStatus === "yes");
                        }
                        // Update listing toggles for the same post.
                        var listToggles = document.querySelectorAll(".bogo-toggle[data-post-id='" + postId + "']");
                        listToggles.forEach(function(toggle) {
                            toggle.checked = (currentStatus === "yes");
                        });
                    } else {
                        console.error("Error refreshing status: " + statusResponse.data);
                    }
                })
                .catch(function(error) {
                    console.error("Fetch error in refreshBogoStatusUI: ", error);
                });
            }

            // Add event listener to the meta box toggle.
            var metaBoxToggle = document.getElementById("bogo_deal_toggle");
            if (metaBoxToggle) {
                metaBoxToggle.addEventListener("change", function () {
                    var postId = this.dataset.postId;
                    var newStatus = this.checked ? "yes" : "no";
                    updateBogoStatus(postId, newStatus);
                });
            }

            // Add event listeners to all listing toggles.
            var listToggles = document.querySelectorAll(".bogo-toggle");
            if (listToggles.length > 0) {
                listToggles.forEach(function(toggle) {
                    toggle.addEventListener("change", function () {
                        var postId = this.dataset.postId;
                        var newStatus = this.checked ? "yes" : "no";
                        updateBogoStatus(postId, newStatus);
                    });
                });
            }
        });
        </script>
        <?php
    }
    add_action('admin_footer', 'wc_bogo_admin_custom_scripts');

/**
 *  Quick Edit Functionality for Start/End Dates
 */
    function wc_bogo_quick_edit_fields($column_name, $post_type) {
        if ($post_type === 'wc_bogo' && in_array($column_name, ['start_date', 'expired_on'])) {
            ?>
            <fieldset class="inline-edit-col-left">
                <div class="inline-edit-col">
                    <label>
                        <span class="title"><?php echo esc_html(ucwords(str_replace('_', ' ', $column_name))); ?></span>
                        <input type="datetime-local" name="<?php echo esc_attr($column_name); ?>" value="">
                    </label>
                </div>
            </fieldset>
            <?php
        }
    }
    add_action('quick_edit_custom_box', 'wc_bogo_quick_edit_fields', 10, 2);

    function wc_bogo_save_quick_edit($post_id) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['start_date'])) {
            update_post_meta($post_id, '_bogo_start_date', sanitize_text_field($_POST['start_date']));
        }
        if (isset($_POST['expired_on'])) {
            update_post_meta($post_id, '_bogo_end_date', sanitize_text_field($_POST['expired_on']));
        }
    }
    add_action('save_post_wc_bogo', 'wc_bogo_save_quick_edit');

/**
 *  Custom Column Content with Inline Editing
 */
    function wc_bogo_custom_column_content($column, $post_id) {
        switch ($column) {
            case 'status':
                $status  = get_post_meta($post_id, '_bogo_deal_status', true);
                $checked = ($status == 'yes') ? 'checked' : '';
                echo '<label class="bogo-status-toggle">
                        <input type="checkbox" data-post-id="' . $post_id . '" class="bogo-toggle" ' . $checked . '>
                        <span class="slider"></span>
                      </label>';
                break;
            case 'discount_type':
                $discount_type = get_post_meta($post_id, '_bogo_discount_type', true);
                echo !empty($discount_type) ? esc_html(ucwords(str_replace('_', ' ', $discount_type))) : '—';
                break;
            case 'start_date':
                $start_date = get_post_meta($post_id, '_bogo_start_date', true);
                $formatted = !empty($start_date) ? date('M j, Y H:i', strtotime($start_date)) : '—';
                echo '<span class="editable inline-edit" data-post-id="' . $post_id . '" data-meta-key="_bogo_start_date" data-value="' . esc_attr($start_date) . '">
                        <span class="dashicons dashicons-clock"></span>
                        <span class="editable-date">' . esc_html($formatted) . '</span>
                      </span>';
                break;
            case 'expired_on':
                $end_date = get_post_meta($post_id, '_bogo_end_date', true);
                $formatted = !empty($end_date) ? date('M j, Y H:i', strtotime($end_date)) : '—';
                echo '<span class="editable inline-edit" data-post-id="' . $post_id . '" data-meta-key="_bogo_end_date" data-value="' . esc_attr($end_date) . '">
                        <span class="dashicons dashicons-clock"></span>
                        <span class="editable-date">' . esc_html($formatted) . '</span>
                      </span>';
                break;
        }
    }
    add_action('manage_wc_bogo_posts_custom_column', 'wc_bogo_custom_column_content', 10, 2);

/**
 *  Inline Edit Styles & Scripts
 */
    function wc_bogo_inline_edit_styles_and_scripts() {
        ?>
        <style>
            .inline-edit {
                cursor: pointer;
                background: #f7f7f7;
                padding: 4px 8px;
                border: 1px solid #ddd;
                border-radius: 3px;
                display: inline-flex;
                align-items: center;
                transition: background-color 0.2s ease;
            }
            .inline-edit:hover {
                background: #eaeaea;
            }
            .inline-edit .dashicons {
                margin-right: 4px;
                color: #0073aa;
            }
            .inline-edit-input {
                padding: 2px 4px;
                font-size: 14px;
                border: 1px solid #0073aa;
                border-radius: 3px;
            }
        </style>
        <script>
        jQuery(document).ready(function($) {
            $('.inline-edit').on('click', function(){
                var $span = $(this);
                if ($span.next().hasClass('inline-edit-input')) {
                    return;
                }
                var postId = $span.data('post-id');
                var metaKey = $span.data('meta-key');
                var currentValue = $span.data('value') || '';
                var $input = $('<input type="datetime-local" class="inline-edit-input">');
                $input.val(currentValue);
                $span.hide().after($input);
                $input.focus();
                $input.on('blur', function(){
                    var newValue = $input.val();
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'update_inline_meta',
                            post_id: postId,
                            meta_key: metaKey,
                            meta_value: newValue,
                            security: "<?php echo wp_create_nonce('inline_meta_nonce'); ?>"
                        },
                        success: function(response) {
                            if(response.success) {
                                var d = new Date(newValue);
                                var options = { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                                var formatted = d.toLocaleString(undefined, options);
                                $span.find('.editable-date').text(formatted);
                                $span.data('value', newValue);
                            } else {
                                alert('Update failed: ' + response.data);
                            }
                            $input.remove();
                            $span.show();
                        },
                        error: function(){
                            alert('An error occurred. Please try again.');
                            $input.remove();
                            $span.show();
                        }
                    });
                });
            });
        });
        </script>
        <?php
    }
    add_action('admin_footer', 'wc_bogo_inline_edit_styles_and_scripts');

/**
 *  AJAX Handler for Inline Meta Updates
 */
    add_action('wp_ajax_update_inline_meta', 'update_inline_meta_callback');
    function update_inline_meta_callback(){
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'inline_meta_nonce') ) {
            wp_send_json_error('Invalid nonce');
        }
        $post_id    = intval($_POST['post_id']);
        $meta_key   = sanitize_text_field($_POST['meta_key']);
        $meta_value = sanitize_text_field($_POST['meta_value']);
        if(update_post_meta($post_id, $meta_key, $meta_value)) {
            wp_send_json_success('Meta updated');
        } else {
            wp_send_json_error('Update failed');
        }
    }

/**
 *  AJAX Handler for Getting BOGO Status
 */
    add_action('wp_ajax_get_bogo_status', 'get_bogo_status_callback');
    function get_bogo_status_callback(){
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'bogo_toggle_nonce') ) {
            wp_send_json_error('Invalid nonce');
        }
        $post_id = intval($_POST['post_id']);
        $status = get_post_meta($post_id, '_bogo_deal_status', true);
        $start_date = get_post_meta($post_id, '_bogo_start_date', true);
        $end_date = get_post_meta($post_id, '_bogo_end_date', true);
        wp_send_json_success([
            'status' => $status,
            'bogo_start_date' => $start_date,
            'bogo_end_date' => $end_date,
        ]);
    }
