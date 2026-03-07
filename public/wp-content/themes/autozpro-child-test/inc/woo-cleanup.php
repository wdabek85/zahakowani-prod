<?php
// Wyłączamy natywną galerię WooCommerce
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

add_action('wp_enqueue_scripts', function() {
    if (is_product()) {
        wp_dequeue_script('flexslider');
        wp_dequeue_script('wc-single-product');
    }
});

// Zmień liczbę kolumn na archiwum produktów na 1 (pełna szerokość)
add_filter('loop_shop_columns', function() {
    return 1;
});

// Liczba produktów na stronę archiwum
add_filter('loop_shop_per_page', function() {
    return 12;
});

// Wyłącz sticky add-to-cart z parent theme (mamy własny sticky-buy-bar.php)
// Bez tego parent theme woła $product->is_purchasable() bez sprawdzenia typu — fatal na Coming Soon
add_action('after_setup_theme', function () {
    remove_action('autozpro_after_footer', 'autozpro_sticky_single_add_to_cart', 999);
});

// --- Rejestracja: walidacja pól ---
add_filter('woocommerce_process_registration_errors', function ( $errors, $username, $email ) {
    if ( empty( $_POST['billing_first_name'] ) ) {
        $errors->add( 'billing_first_name_error', '<strong>Imię</strong> jest wymagane.' );
    }
    if ( empty( $_POST['billing_last_name'] ) ) {
        $errors->add( 'billing_last_name_error', '<strong>Nazwisko</strong> jest wymagane.' );
    }
    if ( empty( $_POST['billing_phone'] ) ) {
        $errors->add( 'billing_phone_error', '<strong>Telefon</strong> jest wymagany.' );
    }
    if ( empty( $_POST['billing_address_1'] ) ) {
        $errors->add( 'billing_address_1_error', '<strong>Ulica i nr domu</strong> jest wymagane.' );
    }
    if ( empty( $_POST['billing_postcode'] ) ) {
        $errors->add( 'billing_postcode_error', '<strong>Kod pocztowy</strong> jest wymagany.' );
    }
    if ( empty( $_POST['billing_city'] ) ) {
        $errors->add( 'billing_city_error', '<strong>Miasto</strong> jest wymagane.' );
    }
    if ( empty( $_POST['terms'] ) ) {
        $errors->add( 'terms_error', 'Musisz zaakceptować <strong>regulamin sklepu</strong>.' );
    }
    if ( empty( $_POST['privacy_policy'] ) ) {
        $errors->add( 'privacy_policy_error', 'Musisz zapoznać się z <strong>polityką prywatności</strong>.' );
    }
    return $errors;
}, 10, 3 );

// --- Rejestracja: zapis danych do user meta ---
add_action('woocommerce_created_customer', function ( $customer_id ) {
    if ( ! empty( $_POST['billing_first_name'] ) ) {
        $first = sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) );
        update_user_meta( $customer_id, 'first_name', $first );
        update_user_meta( $customer_id, 'billing_first_name', $first );
    }
    if ( ! empty( $_POST['billing_last_name'] ) ) {
        $last = sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) );
        update_user_meta( $customer_id, 'last_name', $last );
        update_user_meta( $customer_id, 'billing_last_name', $last );
    }
    if ( ! empty( $_POST['billing_phone'] ) ) {
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) );
    }
    if ( ! empty( $_POST['billing_address_1'] ) ) {
        update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( wp_unslash( $_POST['billing_address_1'] ) ) );
    }
    if ( ! empty( $_POST['billing_postcode'] ) ) {
        update_user_meta( $customer_id, 'billing_postcode', sanitize_text_field( wp_unslash( $_POST['billing_postcode'] ) ) );
    }
    if ( ! empty( $_POST['billing_city'] ) ) {
        update_user_meta( $customer_id, 'billing_city', sanitize_text_field( wp_unslash( $_POST['billing_city'] ) ) );
    }
    // Polski sklep — kraj zawsze PL
    update_user_meta( $customer_id, 'billing_country', 'PL' );
});
