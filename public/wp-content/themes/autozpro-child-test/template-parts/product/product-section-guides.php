<?php
/**
 * Product page — Guides section
 *
 * Displays latest 4 posts from CPT "poradnik" below product tabs.
 * Reuses the same CSS classes as the homepage guides section.
 *
 * @package autozpro-child-test
 */

$guides = new WP_Query( [
    'post_type'      => 'poradnik',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

if ( ! $guides->have_posts() ) {
    return;
}
?>
<section class="product-section-guides">
    <div class="container">
        <div class="hp-guides__header">
            <h2 class="hp-guides__title">Poradniki</h2>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'poradnik' ) ); ?>" class="hp-guides__more">Zobacz Więcej&nbsp;&gt;</a>
        </div>
        <div class="hp-guides__grid">
            <?php while ( $guides->have_posts() ) : $guides->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="hp-guides__card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="hp-guides__thumb">
                            <?php the_post_thumbnail( 'medium', [
                                'loading'  => 'lazy',
                                'decoding' => 'async',
                            ] ); ?>
                        </div>
                    <?php endif; ?>
                    <div class="hp-guides__text">
                        <h3 class="hp-guides__card-title"><?php the_title(); ?></h3>
                        <span class="hp-guides__card-link">Czytaj Więcej&nbsp;&gt;</span>
                    </div>
                </a>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    </div>
</section>
