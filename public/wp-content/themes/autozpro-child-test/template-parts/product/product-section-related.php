<?php
/**
 * Sekcja "Podobne produkty" na stronie single product
 */

defined('ABSPATH') || exit;

global $product;

$related_ids = wc_get_related_products($product->get_id(), 5);

if (empty($related_ids)) {
    return;
}
?>

<section class="product-section product-section--related">
    <div class="product-section__container">
        <h2 class="product-section__heading">Podobne produkty</h2>
        <div class="product-section__cards">
            <?php foreach ($related_ids as $related_id) :
                $post_object = get_post($related_id);
                setup_postdata($GLOBALS['post'] = &$post_object);
                wc_setup_product_data($post_object);
            ?>
                <?php get_template_part('template-parts/product/card-vertical'); ?>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
