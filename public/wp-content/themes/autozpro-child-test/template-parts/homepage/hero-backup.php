<?php
/**
 * Homepage — Hero section
 *
 * Full-width #D4D4D4 background with decorative image.
 * Desktop: two columns (search left | text+CTA right)
 * Mobile: stacked vertically (search top, text+CTA below)
 *
 * @package autozpro-child-test
 */
?>
<section class="hp-hero">
    <div class="hp-hero__container">
        <div class="hp-hero__search">
            <?php get_template_part( 'template-parts/sidebar/vehicle-search' ); ?>
        </div>
        <div class="hp-hero__content">
            <div class="hp-hero__heading">
                <div class="hp-hero__heading-left">
                    <span class="hp-hero__get">GET</span>
                    <span class="hp-hero__upto">UP TO</span>
                </div>
                <h1 class="hp-hero__title">Haki Holownicze</h1>
            </div>
            <p class="hp-hero__subtitle">On All Engine Oil Products</p>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="hp-hero__cta">
                SPRAWDŹ OFERTĘ
            </a>
        </div>
    </div>
</section>
