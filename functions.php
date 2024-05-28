// Unique code
function custom_registration_code() {
    return 'SPECIALCODE2024';
}

// The unique field for the registration form
add_action('woocommerce_register_form', 'add_custom_registration_field');
function add_custom_registration_field() {
    ?>
    <p class="form-row form-row-wide">
        <label for="reg_custom_code"><?php _e( 'Regisztrációs kód', 'woocommerce' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="reg_custom_code" id="reg_custom_code" value="<?php if ( ! empty( $_POST['reg_custom_code'] ) ) echo esc_attr( wp_unslash( $_POST['reg_custom_code'] ) ); ?>" />
    </p>
    <?php
}

// Validation of the unique field during registration
add_filter('woocommerce_registration_errors', 'validate_custom_registration_field', 10, 3);
function validate_custom_registration_field($errors, $username, $email) {
    if (isset($_POST['reg_custom_code']) && trim($_POST['reg_custom_code']) === '') {
        $errors->add('custom_code_error', __('A regisztrációs kód megadása kötelező.', 'woocommerce'));
    } elseif (isset($_POST['reg_custom_code']) && trim($_POST['reg_custom_code']) !== custom_registration_code()) {
        $errors->add('custom_code_error', __('Érvénytelen regisztrációs kód.', 'woocommerce'));
    }
    return $errors;
}

// Saving the unique field in user meta data
add_action('woocommerce_created_customer', 'save_custom_registration_field');
function save_custom_registration_field($customer_id) {
    if (isset($_POST['reg_custom_code'])) {
        update_user_meta($customer_id, 'custom_code', sanitize_text_field($_POST['reg_custom_code']));
    }
}
