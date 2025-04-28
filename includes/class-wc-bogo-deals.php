<?php 

// Register custom post meta fields for bogo_scope and bogo_usage_count
// Register custom post meta fields for bogo_scope and bogo_usage_count
function wc_bogo_register_meta_fields() {
    register_post_meta('wc_bogo', 'deal_scope', array(
        'type'         => 'array', // Change type to array to store multiple selections
        'description'  => 'Scope of the BOGO deal (e.g., product, category, tag)',
        'single'       => false, // Allow multiple values
        'show_in_rest' => array(
            'schema' => array(
                'type'  => 'array',  // The meta type is array
                'items' => array(
                    'type' => 'string',  // Define the item type of the array
                ),
            ),
        ),
    ));

    register_post_meta('wc_bogo', 'bogo_usage_count', array(
        'type'         => 'integer',
        'description'  => 'The number of times the BOGO deal has been used',
        'single'       => true,
        'default'      => 0, // Default value of 0
        'show_in_rest' => true, // Enables REST API support
    ));
}
add_action('init', 'wc_bogo_register_meta_fields');

// BOGO Scope Field in Admin Meta Box (with checkboxes)
function wc_bogo_scope_meta_box($post) {
    // Get selected bogo_scope values
    $selected_scopes = get_post_meta($post->ID, 'deal_scope', true);
    if (!is_array($selected_scopes)) {
        $selected_scopes = array(); // Default empty array if no values
    }
    ?>
    <p>
        <label for="bogo_scope"><strong>BOGO Deal Scope</strong></label><br>
        
        <!-- Product Scope Checkbox -->
        <input type="checkbox" name="bogo_scope[]" value="product" <?php echo (in_array('product', $selected_scopes)) ? 'checked' : ''; ?> /> Product <br>

        <!-- Category Scope Checkbox -->
        <input type="checkbox" name="bogo_scope[]" value="category" <?php echo (in_array('category', $selected_scopes)) ? 'checked' : ''; ?> /> Category <br>

        <!-- Tag Scope Checkbox -->
        <input type="checkbox" name="bogo_scope[]" value="tag" <?php echo (in_array('tag', $selected_scopes)) ? 'checked' : ''; ?> /> Tag <br>
    </p>
    <?php
}

add_action('add_meta_boxes_wc_bogo', function() {
    add_meta_box('bogo_scope_meta_box', __('BOGO Deal Scope', 'wc-bogo'), 'wc_bogo_scope_meta_box', 'wc_bogo', 'normal', 'high');
});

// Save bogo_scope and initialize bogo_usage_count
function wc_bogo_save_meta($post_id) {
    // Verify this is the correct post type and check for autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['post_type']) || $_POST['post_type'] != 'wc_bogo') return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save bogo_scope (store selected checkboxes as an array)
    if (isset($_POST['bogo_scope']) && is_array($_POST['bogo_scope'])) {
        update_post_meta($post_id, 'bogo_scope', array_map('sanitize_text_field', $_POST['bogo_scope']));
    } else {
        delete_post_meta($post_id, 'bogo_scope'); // Clear if no checkbox is selected
    }

    // Initialize bogo_usage_count if not already set
    if (get_post_meta($post_id, 'bogo_usage_count', true) === '') {
        update_post_meta($post_id, 'bogo_usage_count', 0); // Initialize usage count as 0
    }
}
add_action('save_post_wc_bogo', 'wc_bogo_save_meta');

// Add columns for bogo_scope and bogo_usage_count in the admin list view
add_filter('manage_wc_bogo_posts_columns', function($columns) {
    $columns['deal_scope'] = __('Scope', 'wc-bogo');
    $columns['usage_count'] = __('Usage Count', 'wc-bogo');
    return $columns;
});

// Populate the custom columns with data
add_action('manage_wc_bogo_posts_custom_column', function($column, $post_id) {
    if ($column === 'deal_scope') {
        // Get the selected bogo_scope values
        $scopes = get_post_meta($post_id, 'bogo_scope', true);
        if (is_array($scopes)) {
            echo implode(', ', array_map('ucfirst', $scopes)); // Display as comma-separated
        } else {
            echo '—'; // If no scope is set
        }
    }

    if ($column === 'usage_count') {
        $usage = get_post_meta($post_id, 'bogo_usage_count', true);
        echo intval($usage); // Show the usage count
    }
}, 10, 2);

// Increment the usage count for a BOGO deal
function wc_bogo_increment_usage_count($bogo_id) {
    $current_usage = get_post_meta($bogo_id, 'bogo_usage_count', true);
    $current_usage = $current_usage ? intval($current_usage) : 0;
    update_post_meta($bogo_id, 'bogo_usage_count', $current_usage + 1);
}

// Make usage_count sortable
add_filter('manage_edit-wc_bogo_sortable_columns', function($columns) {
    $columns['usage_count'] = 'usage_count';
    return $columns;
});

// Handle sorting by usage_count
add_action('pre_get_posts', function($query) {
    if (!is_admin() || !$query->is_main_query()) return;

    if ($query->get('orderby') === 'usage_count') {
        $query->set('meta_key', 'bogo_usage_count');
        $query->set('orderby', 'meta_value_num');
    }
});
