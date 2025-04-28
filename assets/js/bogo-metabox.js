
// Reset BUtton on Buy X and Get X functionality Js Start here 
    document.addEventListener("DOMContentLoaded", function () {
        // Reset Button Functionality
        document.getElementById("custom-reset-button").addEventListener("click", function () {
            // Reset select fields to their default values
            document.getElementById("wc_bogo_filter_type").value = "all_products";
            document.getElementById("discount_type").value = "free";

            // Clear numeric input fields
            document.querySelectorAll("input[name='min_qty'], input[name='max_qty'], input[name='free_qty'], input[name='discount_value']").forEach(input => input.value = "");

            // Uncheck recursive checkbox
            document.querySelector("input[name='recursive']").checked = false;

            // Clear product selection display field
            document.getElementById("selected_product_ids").value = "";
            document.getElementById("bogo_selected_products").innerHTML = "";

            // Hide discount value input if discount type is 'free'
            document.getElementById("discount_value").style.display = "none";

            // Uncheck all BOGO toggle switches
            document.querySelectorAll(".bogo-toggle").forEach(toggle => toggle.checked = false);
        });
    });
//  Reset BUtton on Buy X and Get X functionality Js Start here 


