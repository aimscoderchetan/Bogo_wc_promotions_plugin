// console.log("cart-adjustment-js.js i am Start here ");

// Discount Type condition from js input box Start Here 
	document.addEventListener('DOMContentLoaded', function () {
	    const discountTypeSelect = document.getElementById('cart_discount_type');

	    // Create fields if not already rendered
	    const discountValueField = document.getElementById('cart_discount_value');
	    const maxDiscountField = document.getElementById('cart_max_discount_value');

	    function toggleDiscountFields() {
	        const selectedType = discountTypeSelect.value;

	        if (discountValueField) {
	            if (selectedType === 'fixed' || selectedType === 'percentage' || selectedType === 'fixed_per_item') {
	                discountValueField.style.display = 'inline-block';
	            } else {
	                discountValueField.style.display = 'none';
	            }
	        }

	        if (maxDiscountField) {
	            if (selectedType === 'percentage' || selectedType === 'fixed_per_item') {
	                maxDiscountField.style.display = 'inline-block';
	            } else {
	                maxDiscountField.style.display = 'none';
	            }
	        }
	    }

	    discountTypeSelect.addEventListener('change', toggleDiscountFields);

	    // Initial check on load
	    toggleDiscountFields();
	});
// Discount Type condition from js input box End Here 

// Cart Discount logic Start Here
    jQuery(document).ready(function ($) {
        function toggleBogoFields() {
            let selectedDiscount = $("#bogo_discount_type").val();
            $("#bogo_fields_container_cart_adjustment").toggle(selectedDiscount === "cart_adjustment");
        }

        $("#bogo_discount_type").change(toggleBogoFields);
        toggleBogoFields();

        // Setup AJAX Search with Select2
       
    });
// Cart Discount logic End Here

// console.log("cart-adjustment-js.js i am End here ");