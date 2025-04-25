// console.log('buy-x-and-get-y.js i am here ');

// --------------------------------------------------
//  Buy X and Get Y Customer Buys functions goes here 
// --------------------------------------------------

    // product Search on Filter type in Product Ajax function called from start here [Note :- Only work for buy_x_get_y option ]
        jQuery(document).ready(function (jQuery) {
            function toggleBogoFields() {
                let selectedDiscount = jQuery("#bogo_discount_type").val();
                // jQuery("#bogo_fields_container_buy_x_get_x").toggle(selectedDiscount === "buy_x_get_x");
                jQuery("#bogo_fields_container_buy_x_get_y").toggle(selectedDiscount === "buy_x_get_y");
            }

            jQuery("#bogo_discount_type").change(toggleBogoFields);
            toggleBogoFields();


    	    // Setup AJAX Search with Select2
            jQuery("#bogo_search_field_cust_buy").select2({
                ajax: {
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            action: "bogo_search_ajax_cust_buy",
                            query: params.term,
                            filter: jQuery("#wc_bogo_filter_type_cust_buy").val(),
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
            jQuery("#bogo_search_field_cust_buy").on("select2:select", function (e) {
                let selected = e.params.data;
                let selectedIds = jQuery("#selected_product_ids_cust_buy").val().split(",");

                if (!selectedIds.includes(selected.id.toString())) {
                    jQuery("#bogo_selected_products_cust_buy").append(
                        '<div data-id="' + selected.id + '">' + selected.text + ' <button type="button" class="remove-product-buy">x</button></div>'
                    );
                    selectedIds.push(selected.id);
                    jQuery("#selected_product_ids_cust_buy").val(selectedIds.join(","));
                }
            });

            // Remove selected product
            jQuery(document).on("click", ".remove-product-buy", function () {
                let productElement = jQuery(this).parent();
                let productId = productElement.data("id");
                let selectedIds = jQuery("#selected_product_ids_cust_buy").val().split(",");
                selectedIds = selectedIds.filter((id) => id !== productId.toString());
                jQuery("#selected_product_ids_cust_buy").val(selectedIds.join(","));
                productElement.remove();
            });

            // Reset button functionality
            jQuery("#custom-reset-button").click(function () {
                jQuery("#wc_bogo_filter_type_cust_buy").val("all_products").trigger("change");
                jQuery("#discount_type_buy_xy").val("free").trigger("change");

                jQuery("input[name='min_qty'], input[name='max_qty'], input[name='free_qty'], input[name='discount_value']").val("");
                jQuery("input[name='recursive_buy_xy']").prop("checked", false);

                jQuery("#selected_product_ids_cust_buy").val("");
                jQuery("#bogo_selected_products").empty();
                jQuery("#discount_value_buy_xy").hide();
            });

            // Discount Type toggle
            var discountType = jQuery("#discount_type_buy_xy");
            var discountValue = jQuery("#discount_value_buy_xy");

            function toggleDiscountValue() {
                discountValue.toggle(discountType.val() === "percentage" || discountType.val() === "fixed");
            }

            discountType.change(toggleDiscountValue);
            toggleDiscountValue();
    	});
    // product Search on Filter type in Product Ajax function called from End here [Note :- Only work for buy_x_get_y option ]

    // product Search on Filter type in category & Tag  Ajax function called from start here [Note :- Only work for buy_x_get_y option ]
        jQuery(document).ready(function ($) {
            // --- Common Toggle Logic ---
            function toggleBogoFields() {
                let selectedDiscount = $("#bogo_discount_type").val();
                $("#bogo_fields_container_buy_x_get_x").toggle(selectedDiscount === "buy_x_get_x");
            }
            $("#bogo_discount_type").change(toggleBogoFields);
            toggleBogoFields();

            // --- Category Search (AJAX + Select2) ---
            $("#bogo_category_search_field_cust_buy").select2({
                ajax: {
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            action: "bogo_search_category_ajax_cust_buy",
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
            $("#bogo_tag_search_field_cust_buy").select2({
                ajax: {
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            action: "bogo_search_tag_ajax_cust_buy",
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
            $("#bogo_category_search_field_cust_buy").on("select2:select", function (e) {
                let selected = e.params.data;
                let selectedIds = $("#selected_category_ids_cust_buy").val().split(",").filter(Boolean);

                if (!selectedIds.includes(selected.id.toString())) {
                    $("#bogo_selected_categories_cust_buy").append(
                        '<div data-id="' + selected.id + '">' +
                        selected.text +
                        ' <button type="button" class="remove-category-buy">x</button></div>'
                    );
                    selectedIds.push(selected.id);
                    $("#selected_category_ids_cust_buy").val(selectedIds.join(","));
                }
            });

            // --- Add selected tag to DOM & hidden field ---
            $("#bogo_tag_search_field_cust_buy").on("select2:select", function (e) {
                let selected = e.params.data;
                let selectedIds = $("#selected_tag_ids_cust_buy").val().split(",").filter(Boolean);

                if (!selectedIds.includes(selected.id.toString())) {
                    $("#bogo_selected_tags_cust_buy").append(
                        '<div data-id="' + selected.id + '">' +
                        selected.text +
                        ' <button type="button" class="remove-tag-buy">x</button></div>'
                    );
                    selectedIds.push(selected.id);
                    $("#selected_tag_ids_cust_buy").val(selectedIds.join(","));
                }
            });

            // --- Remove category item ---
            $(document).on("click", ".remove-category-buy", function () {
                let element = $(this).parent();
                let id = element.data("id").toString();
                let selectedIds = $("#selected_category_ids_cust_buy").val().split(",").filter(Boolean);
                selectedIds = selectedIds.filter((val) => val !== id);
                $("#selected_category_ids_cust_buy").val(selectedIds.join(","));
                element.remove();
            });

            // --- Remove tag item ---
            $(document).on("click", ".remove-tag-buy", function () {
                let element = $(this).parent();
                let id = element.data("id").toString();
                let selectedIds = $("#selected_tag_ids_cust_buy").val().split(",").filter(Boolean);
                selectedIds = selectedIds.filter((val) => val !== id);
                $("#selected_tag_ids_cust_buy").val(selectedIds.join(","));
                element.remove();
            });

            // --- Reset Fields ---
            $("#custom-reset-button").click(function () {
                $("#wc_bogo_filter_type").val("all_products").trigger("change");
                $("#discount_type_buy_xy").val("free").trigger("change");
                $("input[name='min_qty'], input[name='max_qty'], input[name='free_qty'], input[name='discount_value']").val("");
                $("input[name='recursive_buy_xy']").prop("checked", false);

                // Reset products
                $("#selected_product_ids").val("");
                $("#bogo_selected_products").empty();

                // Reset categories
                $("#selected_category_ids_cust_buy").val("");
                $("#bogo_selected_categories").empty();

                // Reset tags
                $("#selected_tag_ids_cust_buy").val("");
                $("#bogo_selected_tags").empty();

                $("#discount_value_buy_xy").hide();
            });

            // --- Show/hide discount value field ---
            var discountType = $("#discount_type_buy_xy");
            var discountValue = $("#discount_value_buy_xy");

            function toggleDiscountValue() {
                discountValue.toggle(discountType.val() === "percentage" || discountType.val() === "fixed");
            }

            discountType.change(toggleDiscountValue);
            toggleDiscountValue();
        });
    // product Search on Filter type in category & Tag  Ajax function called from End here [Note :- Only work for buy_x_get_y option ]

// --------------------------------------------------
//  Buy X and Get Y Customer Buys functions goes here 
// --------------------------------------------------


// --------------------------------------------------
//  Buy X and Get Y Customer gets functions goes here 
// --------------------------------------------------

    // product Search on Filter type in Product Ajax function called from start here [Note :- Only work for buy_x_get_y option ]
        jQuery(document).ready(function (jQuery) {
            function toggleBogoFields() {
                let selectedDiscount = jQuery("#bogo_discount_type").val();
                // jQuery("#bogo_fields_container_buy_x_get_x").toggle(selectedDiscount === "buy_x_get_x");
                jQuery("#bogo_fields_container_buy_x_get_y").toggle(selectedDiscount === "buy_x_get_y");
            }

            jQuery("#bogo_discount_type").change(toggleBogoFields);
            toggleBogoFields();


    	    // Setup AJAX Search with Select2
            jQuery("#bogo_search_field_cust_get").select2({
                ajax: {
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            action: "bogo_search_ajax_cust_get",
                            query: params.term,
                            filter: jQuery("#wc_bogo_filter_type_cust_get").val(),
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
            jQuery("#bogo_search_field_cust_get").on("select2:select", function (e) {
                let selected = e.params.data;
                let selectedIds = jQuery("#selected_product_ids_cust_get").val().split(",");

                if (!selectedIds.includes(selected.id.toString())) {
                    jQuery("#bogo_selected_products_cust_get").append(
                        '<div data-id="' + selected.id + '">' + selected.text + ' <button type="button" class="remove-product-get">x</button></div>'
                    );
                    selectedIds.push(selected.id);
                    jQuery("#selected_product_ids_cust_get").val(selectedIds.join(","));
                }
            });

            // Remove selected product
            jQuery(document).on("click", ".remove-product-get", function () {
                let productElement = jQuery(this).parent();
                let productId = productElement.data("id");
                let selectedIds = jQuery("#selected_product_ids_cust_get").val().split(",");
                selectedIds = selectedIds.filter((id) => id !== productId.toString());
                jQuery("#selected_product_ids_cust_get").val(selectedIds.join(","));
                productElement.remove();
            });

            // Reset button functionality
            jQuery("#custom-reset-button").click(function () {
                jQuery("#wc_bogo_filter_type_cust_buy").val("all_products").trigger("change");
                jQuery("#discount_type_buy_xy").val("free").trigger("change");

                jQuery("input[name='min_qty'], input[name='max_qty'], input[name='free_qty'], input[name='discount_value']").val("");
                jQuery("input[name='recursive_buy_xy']").prop("checked", false);

                jQuery("#selected_product_ids_cust_get").val("");
                jQuery("#bogo_selected_products").empty();
                jQuery("#discount_value_buy_xy").hide();
            });

            // Discount Type toggle
            var discountType = jQuery("#discount_type_buy_xy");
            var discountValue = jQuery("#discount_value_buy_xy");

            function toggleDiscountValue() {
                discountValue.toggle(discountType.val() === "percentage" || discountType.val() === "fixed");
            }

            discountType.change(toggleDiscountValue);
            toggleDiscountValue();
    	});
    // product Search on Filter type in Product Ajax function called from End here [Note :- Only work for buy_x_get_y option ]

    // product Search on Filter type in category & Tag  Ajax function called from start here [Note :- Only work for buy_x_get_y option ]
        jQuery(document).ready(function ($) {
            // --- Common Toggle Logic ---
            function toggleBogoFields() {
                let selectedDiscount = $("#bogo_discount_type").val();
                $("#bogo_fields_container_buy_x_get_y").toggle(selectedDiscount === "buy_x_get_y");
            }
            $("#bogo_discount_type").change(toggleBogoFields);
            toggleBogoFields();

            // --- Category Search (AJAX + Select2) ---
            $("#bogo_category_search_field_cust_get").select2({
                ajax: {
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            action: "bogo_search_category_ajax_cust_get",
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
            $("#bogo_tag_search_field_cust_get").select2({
                ajax: {
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            action: "bogo_search_tag_ajax_cust_get",
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
            $("#bogo_category_search_field_cust_get").on("select2:select", function (e) {
                let selected = e.params.data;
                let selectedIds = $("#selected_category_ids_cust_get").val().split(",").filter(Boolean);

                if (!selectedIds.includes(selected.id.toString())) {
                    $("#bogo_selected_categories_cust_get").append(
                        '<div data-id="' + selected.id + '">' +
                        selected.text +
                        ' <button type="button" class="remove-category-get">x</button></div>'
                    );
                    selectedIds.push(selected.id);
                    $("#selected_category_ids_cust_get").val(selectedIds.join(","));
                }
            });

            // --- Add selected tag to DOM & hidden field ---
            $("#bogo_tag_search_field_cust_get").on("select2:select", function (e) {
                let selected = e.params.data;
                let selectedIds = $("#selected_tag_ids_cust_get").val().split(",").filter(Boolean);

                if (!selectedIds.includes(selected.id.toString())) {
                    $("#bogo_selected_tags_cust_get").append(
                        '<div data-id="' + selected.id + '">' +
                        selected.text +
                        ' <button type="button" class="remove-tag-get">x</button></div>'
                    );
                    selectedIds.push(selected.id);
                    $("#selected_tag_ids_cust_get").val(selectedIds.join(","));
                }
            });

            // --- Remove category item ---
            $(document).on("click", ".remove-category-get", function () {
                let element = $(this).parent();
                let id = element.data("id").toString();
                let selectedIds = $("#selected_category_ids_cust_get").val().split(",").filter(Boolean);
                selectedIds = selectedIds.filter((val) => val !== id);
                $("#selected_category_ids_cust_get").val(selectedIds.join(","));
                element.remove();
            });

            // --- Remove tag item ---
            $(document).on("click", ".remove-tag-get", function () {
                let element = $(this).parent();
                let id = element.data("id").toString();
                let selectedIds = $("#selected_tag_ids_cust_get").val().split(",").filter(Boolean);
                selectedIds = selectedIds.filter((val) => val !== id);
                $("#selected_tag_ids_cust_get").val(selectedIds.join(","));
                element.remove();
            });

            // --- Reset Fields ---
            $("#custom-reset-button").click(function () {
                $("#wc_bogo_filter_type").val("all_products").trigger("change");
                $("#discount_type_buy_xy").val("free").trigger("change");
                $("input[name='min_qty'], input[name='max_qty'], input[name='free_qty'], input[name='discount_value_buy_xy']").val("");
                $("input[name='recursive_buy_xy']").prop("checked", false);

                // Reset products
                $("#selected_product_ids").val("");
                $("#bogo_selected_products").empty();

                // Reset categories
                $("#selected_category_ids_cust_get").val("");
                $("#bogo_selected_categories_cust_get").empty();

                // Reset tags
                $("#selected_tag_ids_cust_get").val("");
                $("#bogo_selected_tags_cust_get").empty();

                $("#discount_value_buy_xy").hide();
            });

            // --- Show/hide discount value field ---
            var discountType = $("#discount_type_buy_xy");
            var discountValue = $("#discount_value_buy_xy");

            function toggleDiscountValue() {
                discountValue.toggle(discountType.val() === "percentage" || discountType.val() === "fixed");
            }

            discountType.change(toggleDiscountValue);
            toggleDiscountValue();
        });
    // product Search on Filter type in category & Tag  Ajax function called from End here [Note :- Only work for buy_x_get_y option ]

// --------------------------------------------------
//  Buy X and Get Y Customer gets functions goes here 
// ----------------------   ----------------------------




