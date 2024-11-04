<?php
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields($fields) {
    // Remove the default display of specific billing fields
    unset($fields['billing']['billing_company']);
//     unset($fields['billing']['billing_country']);
//     unset($fields['billing']['billing_address_1']);
//     unset($fields['billing']['billing_address_2']);
//     unset($fields['billing']['billing_city']);
//     unset($fields['billing']['billing_state']);
//     unset($fields['billing']['billing_postcode']);
	
    unset($fields['shipping']['shipping_first_name']);
    unset($fields['shipping']['shipping_last_name']);
    unset($fields['shipping']['shipping_company']);
    
    return $fields;
}

remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
add_action('woocommerce_checkout_payment_hook', 'woocommerce_checkout_payment', 20);

add_filter('woocommerce_checkout_fields', 'custom_modify_billing_field_ids');

function custom_modify_billing_field_ids($fields) {
    $excluded_fields = ['billing_first_name', 'billing_last_name', 'billing_phone', 'billing_email'];

    foreach ($fields['billing'] as $key => $field) {
        if (!in_array($key, $excluded_fields)) {
            $fields['billing'][$key]['id'] = 'f_' . $key;
        }
    }

    return $fields;
}


add_action('wp_ajax_apply_coupon', 'apply_coupon');
add_action('wp_ajax_nopriv_apply_coupon', 'apply_coupon');

function apply_coupon() {
    // Check if coupon code is provided
//     wc_get_logger()->debug( 'in' );
    if (isset($_POST['coupon_code'])) {
        $coupon_code = sanitize_text_field($_POST['coupon_code']);

        // Attempt to apply the coupon
        if (WC()->cart->apply_coupon($coupon_code)) {
            wp_send_json_success('Coupon applied successfully.');

        } else {
            wp_send_json_error('Coupon could not be applied.: ' . $_POST['coupon_code']);

        }
    }


    wp_die();
}
