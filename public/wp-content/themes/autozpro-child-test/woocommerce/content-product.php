<?php
/**
 * Routing karty produktu
 */

defined('ABSPATH') || exit;

// TYLKO w archiwum (nie w single product) - poziomy layout
// is_product_taxonomy() obejmuje: product_cat, product_tag, pa_* (atrybuty), product_brand
if (!is_product() && (is_shop() || is_product_taxonomy())) {
    get_template_part('woocommerce/content-product-archive');
    return;
}

// Wszędzie indziej (strona główna, related, upsells) - domyślny grid
global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<li <?php wc_product_class('', $product); ?>>
    <?php
    do_action('woocommerce_before_shop_loop_item');
    do_action('woocommerce_before_shop_loop_item_title');
    do_action('woocommerce_shop_loop_item_title');
    do_action('woocommerce_after_shop_loop_item_title');
    do_action('woocommerce_after_shop_loop_item');
    ?>
</li>