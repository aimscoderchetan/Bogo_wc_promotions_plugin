<!-- nothing here  -->

<form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
    <input type="hidden" name="action" value="save_flash_sale_settings">
    <?php wp_nonce_field( 'wc_bogo_flash_sale_save', 'wc_bogo_flash_sale_nonce' ); ?>

    <div class="bogo-toggle-wrapper">
        <label for="enable_flash_sal_buy_x_and_x" class="bogo-toggle-label">
            <?php esc_html_e( 'Enable Flash Sale on Products? Buy X and Get X', 'wc-bogo' ); ?>
        </label>
        <label class="bogo-switch">
            <input type="checkbox" id="enable_flash_sal_buy_x_and_x" name="enable_flash_sal_buy_x_and_x" value="yes" <?php checked( $enable_flash_sal_buy_x_and_x, 'yes' ); ?> data-testing="<?php echo esc_attr( $enable_flash_sal_buy_x_and_x ); ?>">
            <span class="bogo-slider"></span>
        </label>
    </div>

    <div id="buy_x_get_x_fields" class="bogo-fields">
        <h2>Message Guidelines (Buy X Get X)</h2>

        <ul>
            <li> <strong>[buy-quantity] - Enter Min quanity which you need to store  </strong></li>
            <li> <strong>[free-quantity] - Enter Free quanity which you need to store </strong> </li>
        </ul>

        <!-- FREE Message -->
        <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div style="flex: 1;">
                <label>Free Message:</label><br>
                <input type="text" name="buy_x_get_x_free_message" value="<?php echo esc_attr( $buy_x_get_x_free_message ); ?>" placeholder="Enter free message"><br>

                <label>Background Color:</label>
                <input type="color" name="buy_x_get_x_free_bg_color" class="bg-color" value="<?php echo esc_attr( $buy_x_get_x_free_bg_color ); ?>">
                <input type="text" name="buy_x_get_x_free_bg_code" class="bg-code" value="<?php echo esc_attr( $buy_x_get_x_free_bg_code ); ?>"><br>

                <label>Font Color:</label>
                <input type="color" name="buy_x_get_x_free_font_color" class="font-color" value="<?php echo esc_attr( $buy_x_get_x_free_font_color ); ?>">
                <input type="text" name="buy_x_get_x_free_font_code" class="font-code" value="<?php echo esc_attr( $buy_x_get_x_free_font_code ); ?>">
            </div>

            <div id="buy-x-get-x-preview" style="flex: 1; margin-top:10px; padding:10px; border:1px solid #ccc; min-width:150px;">
                <?php echo esc_html( $buy_x_get_x_free_message ?: 'Your preview will appear here' ); ?>
            </div>
        </div>

        <hr>

        <!-- PERCENTAGE Message -->
        <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div style="flex: 1;">
                <label>Percentage Message:</label><br>
                <input type="text" name="buy_x_get_x_percentage_message" value="<?php echo esc_attr( $buy_x_get_x_percentage_message ); ?>" placeholder="Enter percentage message"><br>

                <label>Background Color:</label>
                <input type="color" name="buy_x_get_x_percentage_bg_color" class="bg-color" value="<?php echo esc_attr( $buy_x_get_x_percentage_bg_color ); ?>">
                <input type="text" name="buy_x_get_x_percentage_bg_code" class="bg-code" value="<?php echo esc_attr( $buy_x_get_x_percentage_bg_code ); ?>"><br>

                <label>Font Color:</label>
                <input type="color" name="buy_x_get_x_percentage_font_color" class="font-color" value="<?php echo esc_attr( $buy_x_get_x_percentage_font_color ); ?>">
                <input type="text" name="buy_x_get_x_percentage_font_code" class="font-code" value="<?php echo esc_attr( $buy_x_get_x_percentage_font_code ); ?>">
            </div>

            <div id="buy-x-get-x-preview-percentage" style="flex: 1; margin-top:10px; padding:10px; border:1px solid #ccc; min-width:150px;">
                <?php echo esc_html( $buy_x_get_x_percentage_message ?: 'Your preview will appear here' ); ?>
            </div>
        </div>

        <hr>

        <!-- FIXED Message -->
        <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div style="flex: 1;">
                <label>Fixed Message:</label><br>
                <input type="text" name="buy_x_get_x_fixed_message" value="<?php echo esc_attr( $buy_x_get_x_fixed_message ); ?>" placeholder="Enter fixed message"><br>

                <label>Background Color:</label>
                <input type="color" name="buy_x_get_x_fixed_bg_color" class="bg-color" value="<?php echo esc_attr( $buy_x_get_x_fixed_bg_color ); ?>">
                <input type="text" name="buy_x_get_x_fixed_bg_code" class="bg-code" value="<?php echo esc_attr( $buy_x_get_x_fixed_bg_code ); ?>"><br>

                <label>Font Color:</label>
                <input type="color" name="buy_x_get_x_fixed_font_color" class="font-color" value="<?php echo esc_attr( $buy_x_get_x_fixed_font_color ); ?>">
                <input type="text" name="buy_x_get_x_fixed_font_code" class="font-code" value="<?php echo esc_attr( $buy_x_get_x_fixed_font_code ); ?>">
            </div>

            <div id="buy-x-get-x-preview-fixed" style="flex: 1; margin-top:10px; padding:10px; border:1px solid #ccc; min-width:150px;">
                <?php echo esc_html( $buy_x_get_x_fixed_message ?: 'Your preview will appear here' ); ?>
            </div>
        </div>
    </div>

    <hr>

    <div class="bogo-toggle-wrapper">
        <label for="enable_flash_sal_buy_x_and_y" class="bogo-toggle-label">
            <?php esc_html_e( 'Enable Flash Sale on Products? Buy X and Get Y', 'wc-bogo' ); ?>
        </label>
        <label class="bogo-switch">
            <input type="checkbox" id="enable_flash_sal_buy_x_and_y" name="enable_flash_sal_buy_x_and_y" value="yes" <?php checked( $enable_flash_sal_buy_x_and_y, 'yes' ); ?> data-testing="<?php echo esc_attr( $enable_flash_sal_buy_x_and_y ); ?>">
            <span class="bogo-slider"></span>
        </label>
    </div>


        
        

    <div id="buy_x_get_y_fields" class="bogo-fields">
        <h2>Message Guidelines (Buy X Get Y)</h2>

        <ul>
            <li> <strong>[min_qty] - Enter Min product quanity which you need to store  </strong></li>
            <li> <strong>[free_qty] - Enter Free product quantity  which you need to store </strong> </li>
        </ul>

        <!-- FREE -->
        <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div style="flex: 1;">
                <label>Free Message:</label><br>
                <input type="text" name="buy_x_get_y_free_message" value="<?php echo esc_attr( $buy_x_get_y_free_message ); ?>" placeholder="Enter free message"><br>

                <label>Background Color:</label>
                <input type="color" name="buy_x_get_y_free_bg_color" class="bg-color" value="<?php echo esc_attr( $buy_x_get_y_free_bg_color ); ?>">
                <input type="text" name="buy_x_get_y_free_bg_code" class="bg-code" value="<?php echo esc_attr( $buy_x_get_y_free_bg_code ); ?>"><br>

                <label>Font Color:</label>
                <input type="color" name="buy_x_get_y_free_font_color" class="font-color" value="<?php echo esc_attr( $buy_x_get_y_free_font_color ); ?>">
                <input type="text" name="buy_x_get_y_free_font_code" class="font-code" value="<?php echo esc_attr( $buy_x_get_y_free_font_code ); ?>">
            </div>

            <div id="buy-x-get-y-preview-free" style="flex: 1; margin-top:10px; padding:10px; border:1px solid #ccc; min-width:150px;">
                <?php echo esc_html( $buy_x_get_y_free_message ?: 'Your preview will appear here' ); ?>
            </div>
        </div>

        <hr>

        <!-- PERCENTAGE -->
        <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div style="flex: 1;">
                <label>Percentage Message:</label><br>
                <input type="text" name="buy_x_get_y_percentage_message" value="<?php echo esc_attr( $buy_x_get_y_percentage_message ); ?>" placeholder="Enter percentage message"><br>

                <label>Background Color:</label>
                <input type="color" name="buy_x_get_y_percentage_bg_color" class="bg-color" value="<?php echo esc_attr( $buy_x_get_y_percentage_bg_color ); ?>">
                <input type="text" name="buy_x_get_y_percentage_bg_code" class="bg-code" value="<?php echo esc_attr( $buy_x_get_y_percentage_bg_code ); ?>"><br>

                <label>Font Color:</label>
                <input type="color" name="buy_x_get_y_percentage_font_color" class="font-color" value="<?php echo esc_attr( $buy_x_get_y_percentage_font_color ); ?>">
                <input type="text" name="buy_x_get_y_percentage_font_code" class="font-code" value="<?php echo esc_attr( $buy_x_get_y_percentage_font_code ); ?>">
            </div>

            <div id="buy-x-get-y-preview-percentage" style="flex: 1; margin-top:10px; padding:10px; border:1px solid #ccc; min-width:150px;">
                <?php echo esc_html( $buy_x_get_y_percentage_message ?: 'Your preview will appear here' ); ?>
            </div>
        </div>

        <hr>

        <!-- FIXED -->
        <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div style="flex: 1;">
                <label>Fixed Message:</label><br>
                <input type="text" name="buy_x_get_y_fixed_message" value="<?php echo esc_attr( $buy_x_get_y_fixed_message ); ?>" placeholder="Enter fixed message"><br>

                <label>Background Color:</label>
                <input type="color" name="buy_x_get_y_fixed_bg_color" class="bg-color" value="<?php echo esc_attr( $buy_x_get_y_fixed_bg_color ); ?>">
                <input type="text" name="buy_x_get_y_fixed_bg_code" class="bg-code" value="<?php echo esc_attr( $buy_x_get_y_fixed_bg_code ); ?>"><br>

                <label>Font Color:</label>
                <input type="color" name="buy_x_get_y_fixed_font_color" class="font-color" value="<?php echo esc_attr( $buy_x_get_y_fixed_font_color ); ?>">
                <input type="text" name="buy_x_get_y_fixed_font_code" class="font-code" value="<?php echo esc_attr( $buy_x_get_y_fixed_font_code ); ?>">
            </div>

            <div id="buy-x-get-y-preview-fixed" style="flex: 1; margin-top:10px; padding:10px; border:1px solid #ccc; min-width:150px;">
                <?php echo esc_html( $buy_x_get_y_fixed_message ?: 'Your preview will appear here' ); ?>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="save-container">
        <button type="submit" class="button button-primary">
            <?php esc_html_e( 'Save Flash Sale Settings', 'wc-bogo' ); ?>
        </button>
        <button type="reset" class="button" style="margin-left: 10px;">
            <?php esc_html_e( 'Reset', 'wc-bogo' ); ?>
        </button>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resetButton = document.querySelector('button[type="reset"]');

        if (resetButton) {
            resetButton.addEventListener('click', function (e) {
                e.preventDefault(); // prevent native reset behavior

                const form = this.closest('form');
                form.querySelectorAll('input[type="text"], input[type="color"]').forEach(input => {
                    input.value = '';
                });

                form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Optionally reset preview areas
                document.querySelectorAll('#buy-x-get-x-preview, #buy-x-get-x-preview-percentage, #buy-x-get-x-preview-fixed, #buy-x-get-y-preview-free, #buy-x-get-y-preview-percentage, #buy-x-get-y-preview-fixed').forEach(preview => {
                    preview.innerText = 'Your preview will appear here';
                });
            });
        }
    });
</script>
<script>
    jQuery(function($){
        function setupPreview(messageSelector, bgColorSelector, bgCodeSelector, fontColorSelector, fontCodeSelector, previewSelector, defaultText = 'Your preview will appear here') {
            var $msg        = $(messageSelector),
                $bgColor    = $(bgColorSelector),
                $bgCode     = $(bgCodeSelector),
                $fontColor  = $(fontColorSelector),
                $fontCode   = $(fontCodeSelector),
                $preview    = $(previewSelector);

            function updatePreview(){
                var text = $msg.val() || defaultText,
                    bg   = $bgColor.val() || $bgCode.val(),
                    fc   = $fontColor.val() || $fontCode.val();

                $preview.text(text)
                        .css({
                            'background-color': bg,
                            'color': fc
                        });
            }

            function syncPair($picker, $code){
                $picker.on('input change', function(){
                    $code.val(this.value);
                    updatePreview();
                });
                $code.on('input', function(){
                    $picker.val(this.value);
                    updatePreview();
                });
            }

            $msg.on('input', updatePreview);
            syncPair($bgColor, $bgCode);
            syncPair($fontColor, $fontCode);

            updatePreview();
        }

        // BUY X GET X
        setupPreview('input[name="buy_x_get_x_free_message"]', 'input[name="buy_x_get_x_free_bg_color"]', 'input[name="buy_x_get_x_free_bg_code"]', 'input[name="buy_x_get_x_free_font_color"]', 'input[name="buy_x_get_x_free_font_code"]', '#buy-x-get-x-preview');
        setupPreview('input[name="buy_x_get_x_percentage_message"]', 'input[name="buy_x_get_x_percentage_bg_color"]', 'input[name="buy_x_get_x_percentage_bg_code"]', 'input[name="buy_x_get_x_percentage_font_color"]', 'input[name="buy_x_get_x_percentage_font_code"]', '#buy-x-get-x-preview-percentage');
        setupPreview('input[name="buy_x_get_x_fixed_message"]', 'input[name="buy_x_get_x_fixed_bg_color"]', 'input[name="buy_x_get_x_fixed_bg_code"]', 'input[name="buy_x_get_x_fixed_font_color"]', 'input[name="buy_x_get_x_fixed_font_code"]', '#buy-x-get-x-preview-fixed');

        // BUY X GET Y
        setupPreview('input[name="buy_x_get_y_free_message"]', 'input[name="buy_x_get_y_free_bg_color"]', 'input[name="buy_x_get_y_free_bg_code"]', 'input[name="buy_x_get_y_free_font_color"]', 'input[name="buy_x_get_y_free_font_code"]', '#buy-x-get-y-preview-free');
        setupPreview('input[name="buy_x_get_y_percentage_message"]', 'input[name="buy_x_get_y_percentage_bg_color"]', 'input[name="buy_x_get_y_percentage_bg_code"]', 'input[name="buy_x_get_y_percentage_font_color"]', 'input[name="buy_x_get_y_percentage_font_code"]', '#buy-x-get-y-preview-percentage');
        setupPreview('input[name="buy_x_get_y_fixed_message"]', 'input[name="buy_x_get_y_fixed_bg_color"]', 'input[name="buy_x_get_y_fixed_bg_code"]', 'input[name="buy_x_get_y_fixed_font_color"]', 'input[name="buy_x_get_y_fixed_font_code"]', '#buy-x-get-y-preview-fixed');
    });
</script>
<script>
    // Toggle the field visibility
    function toggleFields(toggleId, fieldsId) {
        const toggle = document.getElementById(toggleId);
        const fields = document.getElementById(fieldsId);

        function updateVisibility() {
            fields.style.display = toggle.checked ? 'block' : 'none';
        }

        toggle.addEventListener('change', updateVisibility);

        // Initialize on page load
        updateVisibility();
    }
    toggleFields('enable_flash_sal_buy_x_and_x', 'buy_x_get_x_fields');
    toggleFields('enable_flash_sal_buy_x_and_y', 'buy_x_get_y_fields');
    // Bind color pickers
    function bindColorPickerGroup(scope) {
        const bgColors = scope.querySelectorAll('.bg-color');
        const bgCodes = scope.querySelectorAll('.bg-code');
        const fontColors = scope.querySelectorAll('.font-color');
        const fontCodes = scope.querySelectorAll('.font-code');

        bgColors.forEach((picker, index) => {
            const codeInput = bgCodes[index];
            picker.addEventListener('input', () => codeInput.value = picker.value);
            codeInput.addEventListener('input', () => {
                let val = codeInput.value.trim();
                if (val && !val.startsWith("#")) {
                    val = "#" + val;
                }
                if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                    picker.value = val;
                }
            });
            codeInput.value = picker.value;
        });

        fontColors.forEach((picker, index) => {
            const codeInput = fontCodes[index];
            picker.addEventListener('input', () => codeInput.value = picker.value);
            codeInput.addEventListener('input', () => {
                let val = codeInput.value.trim();
                if (val && !val.startsWith("#")) {
                    val = "#" + val;
                }
                if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                    picker.value = val;
                }
            });
            codeInput.value = picker.value;
        });
    }
    document.querySelectorAll('.bogo-fields').forEach(bindColorPickerGroup);
</script>