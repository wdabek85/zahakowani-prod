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
