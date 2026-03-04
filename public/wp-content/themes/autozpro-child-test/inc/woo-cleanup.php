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
