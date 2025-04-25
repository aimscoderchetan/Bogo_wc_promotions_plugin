// product Search on Filter type in Product Ajax function called from start here [Note :- Only work for buy_x_get_x option ]
    jQuery(document).ready(function (jQuery) {
        function toggleBogoFields() {
            let selectedDiscount = jQuery("#bogo_discount_type").val();
            // jQuery("#bogo_fields_container_buy_x_get_x").toggle(selectedDiscount === "buy_x_get_x");
            jQuery("#bogo_fields_container_buy_x_get_x").toggle(selectedDiscount === "buy_x_get_x");
        }

        jQuery("#bogo_discount_type").change(toggleBogoFields);
        toggleBogoFields();



        // Setup AJAX Search with Select2
        jQuery("#bogo_search_field").select2({
            ajax: {
                url: ajaxurl,
                type: "POST",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        action: "bogo_search_ajax",
                        query: params.term,
                        filter: jQuery("#wc_bogo_filter_type").val(),
                    };
                },
                processResults: function (data) {
                    return {
                        results: jQuery.map(data, function (item) {
                            return { id: item.id, text: item.text };
                        }),
                    };
                },
            },
            minimumInputLength: 2,
            placeholder: "Search products...",
            allowClear: true,
        });

        // Add selected product to list
        jQuery("#bogo_search_field").on("select2:select", function (e) {
            let selected = e.params.data;
            let selectedIds = jQuery("#selected_product_ids").val().split(",");

            if (!selectedIds.includes(selected.id.toString())) {
                jQuery("#bogo_selected_products").append(
                    '<div data-id="' + selected.id + '">' + selected.text + ' <button type="button" class="remove-product">x</button></div>'
                );
                selectedIds.push(selected.id);
                jQuery("#selected_product_ids").val(selectedIds.join(","));
            }
        });

        // Remove selected product
        jQuery(document).on("click", ".remove-product", function () {
            let productElement = jQuery(this).parent();
            let productId = productElement.data("id");
            let selectedIds = jQuery("#selected_product_ids").val().split(",");
            selectedIds = selectedIds.filter((id) => id !== productId.toString());
            jQuery("#selected_product_ids").val(selectedIds.join(","));
            productElement.remove();
        });

        // Reset button functionality
        jQuery("#custom-reset-button").click(function () {
            jQuery("#wc_bogo_filter_type").val("all_products").trigger("change");
            jQuery("#discount_type").val("free").trigger("change");

            jQuery("input[name='min_qty'], input[name='max_qty'], input[name='free_qty'], input[name='discount_value']").val("");
            jQuery("input[name='recursive']").prop("checked", false);

            jQuery("#selected_product_ids").val("");
            jQuery("#bogo_selected_products").empty();
            jQuery("#discount_value").hide();
        });

        // Discount Type toggle
        var discountType = jQuery("#discount_type");
        var discountValue = jQuery("#discount_value");

        function toggleDiscountValue() {
            discountValue.toggle(discountType.val() === "percentage" || discountType.val() === "fixed");
        }

        discountType.change(toggleDiscountValue);
        toggleDiscountValue();
    });
// product Search on Filter type in Product Ajax function called from End here [Note :- Only work for buy_x_get_x option ]

// product Search on Filter type in category Ajax function called from start here [Note :- Only work for buy_x_get_x option ]
    jQuery(document).ready(function ($) {
        // --- Common Toggle Logic ---
        function toggleBogoFields() {
            let selectedDiscount = $("#bogo_discount_type").val();
            $("#bogo_fields_container_buy_x_get_x").toggle(selectedDiscount === "buy_x_get_x");
        }
        $("#bogo_discount_type").change(toggleBogoFields);
        toggleBogoFields();

        // --- Category Search (AJAX + Select2) ---
        $("#bogo_category_search_field").select2({
            ajax: {
                url: ajaxurl,
                type: "POST",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        action: "bogo_search_category_ajax",
                        query: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return { id: item.id, text: item.text };
                        }),
                    };
                },
            },
            minimumInputLength: 2,
            placeholder: "Search category...",
            allowClear: true,
        });

        // --- Tag Search (AJAX + Select2) ---
        $("#bogo_tag_search_field").select2({
            ajax: {
                url: ajaxurl,
                type: "POST",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        action: "bogo_search_tag_ajax",
                        query: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return { id: item.id, text: item.text };
                        }),
                    };
                },
            },
            minimumInputLength: 2,
            placeholder: "Search tag...",
            allowClear: true,
        });

        // --- Add selected category to DOM & hidden field ---
        $("#bogo_category_search_field").on("select2:select", function (e) {
            let selected = e.params.data;
            let selectedIds = $("#selected_category_ids").val().split(",").filter(Boolean);

            if (!selectedIds.includes(selected.id.toString())) {
                $("#bogo_selected_categories").append(
                    '<div data-id="' + selected.id + '">' +
                    selected.text +
                    ' <button type="button" class="remove-category">x</button></div>'
                );
                selectedIds.push(selected.id);
                $("#selected_category_ids").val(selectedIds.join(","));
            }
        });

        // --- Add selected tag to DOM & hidden field ---
        $("#bogo_tag_search_field").on("select2:select", function (e) {
            let selected = e.params.data;
            let selectedIds = $("#selected_tag_ids").val().split(",").filter(Boolean);

            if (!selectedIds.includes(selected.id.toString())) {
                $("#bogo_selected_tags").append(
                    '<div data-id="' + selected.id + '">' +
                    selected.text +
                    ' <button type="button" class="remove-tag">x</button></div>'
                );
                selectedIds.push(selected.id);
                $("#selected_tag_ids").val(selectedIds.join(","));
            }
        });

        // --- Remove category item ---
        $(document).on("click", ".remove-category", function () {
            let element = $(this).parent();
            let id = element.data("id").toString();
            let selectedIds = $("#selected_category_ids").val().split(",").filter(Boolean);
            selectedIds = selectedIds.filter((val) => val !== id);
            $("#selected_category_ids").val(selectedIds.join(","));
            element.remove();
        });

        // --- Remove tag item ---
        $(document).on("click", ".remove-tag", function () {
            let element = $(this).parent();
            let id = element.data("id").toString();
            let selectedIds = $("#selected_tag_ids").val().split(",").filter(Boolean);
            selectedIds = selectedIds.filter((val) => val !== id);
            $("#selected_tag_ids").val(selectedIds.join(","));
            element.remove();
        });

        // --- Reset Fields ---
        $("#custom-reset-button").click(function () {
            $("#wc_bogo_filter_type").val("all_products").trigger("change");
            $("#discount_type").val("free").trigger("change");
            $("input[name='min_qty'], input[name='max_qty'], input[name='free_qty'], input[name='discount_value']").val("");
            $("input[name='recursive']").prop("checked", false);

            // Reset products
            $("#selected_product_ids").val("");
            $("#bogo_selected_products").empty();

            // Reset categories
            $("#selected_category_ids").val("");
            $("#bogo_selected_categories").empty();

            // Reset tags
            $("#selected_tag_ids").val("");
            $("#bogo_selected_tags").empty();

            $("#discount_value").hide();
        });

        // --- Show/hide discount value field ---
        var discountType = $("#discount_type");
        var discountValue = $("#discount_value");

        function toggleDiscountValue() {
            discountValue.toggle(discountType.val() === "percentage" || discountType.val() === "fixed");
        }

        discountType.change(toggleDiscountValue);
        toggleDiscountValue();
    });
// product Search on Filter type in category Ajax function called from End here [Note :- Only work for buy_x_get_x option ]
