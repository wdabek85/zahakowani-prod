<?php
/**
 * Live product search — AJAX endpoint
 * Returns up to 3 matching products as JSON.
 */

add_action('wp_ajax_header_live_search', 'child_header_live_search');
add_action('wp_ajax_nopriv_header_live_search', 'child_header_live_search');

function child_header_live_search() {
    $query = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';

    if (mb_strlen($query) < 2) {
        wp_send_json([]);
    }

    $products = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        's'              => $query,
        'posts_per_page' => 3,
        'no_found_rows'  => true,
    ]);

    $results = [];

    while ($products->have_posts()) {
        $products->the_post();
        $product = wc_get_product(get_the_ID());
        if (!$product) continue;

        $results[] = [
            'title' => get_the_title(),
            'url'   => get_the_permalink(),
            'price' => $product->get_price_html(),
            'image' => get_the_post_thumbnail_url(get_the_ID(), 'woocommerce_gallery_thumbnail') ?: '',
        ];
    }

    wp_reset_postdata();
    wp_send_json($results);
}

/**
 * Pass AJAX URL to header-nav.js
 */
add_action('wp_enqueue_scripts', function () {
    wp_localize_script('child-header-nav', 'headerSearch', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ]);
}, 20);
