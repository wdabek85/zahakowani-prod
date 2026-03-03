<?php
/**
 * Homepage — Car Brands section
 *
 * Dynamically displays all "Marka samochodu" (pa_marka-samochodu) terms
 * that have at least one product assigned.
 * Adding a new car brand and assigning it to a product = appears here automatically.
 *
 * @package autozpro-child-test
 */

$taxonomy = 'pa_marka-samochodu';

if ( ! taxonomy_exists( $taxonomy ) ) {
    return;
}

$brands = get_terms( [
    'taxonomy'   => $taxonomy,
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
] );

if ( empty( $brands ) || is_wp_error( $brands ) ) {
    return;
}
?>
<section class="hp-brands">
    <h2 class="hp-brands__title">Haki Holownicze do najpopularniejszych marek samochodowych</h2>
    <div class="hp-brands__list">
        <?php foreach ( $brands as $brand ) : ?>
            <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" class="hp-brands__pill">
                <?php echo esc_html( $brand->name ); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>
