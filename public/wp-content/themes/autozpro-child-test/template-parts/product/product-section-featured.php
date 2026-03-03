<?php
/**
 * Sekcja "Polecane produkty" na stronie single product
 */

defined('ABSPATH') || exit;

global $product;

$featured = wc_get_products([
    'featured' => true,
    'limit'    => 5,
    'exclude'  => [$product->get_id()],
    'orderby'  => 'rand',
    'return'   => 'ids',
]);

if (empty($featured)) {
    return;
}
?>

<section class="product-section product-section--featured">
    <div class="product-section__container">
        <h2 class="product-section__heading">Polecane produkty</h2>
        <div class="product-section__cards">
            <?php foreach ($featured as $featured_id) :
                $post_object = get_post($featured_id);
                setup_postdata($GLOBALS['post'] = &$post_object);
                wc_setup_product_data($post_object);
            ?>
                <?php get_template_part('template-parts/product/card-vertical'); ?>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
