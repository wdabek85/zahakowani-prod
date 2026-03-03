<?php
/**
 * Homepage — Featured Products section
 *
 * "Najczęściej Przeglądane i Kupowane" — 5 best-selling products
 * rendered with the existing card-vertical.php template part.
 *
 * @package autozpro-child-test
 */

$products = wc_get_products( [
    'status'  => 'publish',
    'limit'   => 5,
    'orderby' => 'popularity',
    'order'   => 'DESC',
    'return'  => 'objects',
] );

if ( empty( $products ) ) {
    return;
}
?>
<section class="hp-featured">
    <h2 class="hp-featured__title">Najczęściej Przeglądane i Kupowane</h2>
    <div class="hp-featured__grid">
        <?php foreach ( $products as $product_obj ) :
            $GLOBALS['product'] = $product_obj;
            setup_postdata( $product_obj->get_id() );
            get_template_part( 'template-parts/product/card-vertical' );
        endforeach;
        wp_reset_postdata(); ?>
    </div>
</section>
