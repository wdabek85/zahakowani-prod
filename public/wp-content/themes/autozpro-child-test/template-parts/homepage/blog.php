<?php
/**
 * Homepage — Blog section
 *
 * "Najpopularniejsze artykuły" — 3 latest blog posts
 * with thumbnail, author, date, title, excerpt, and read-more link.
 *
 * @package autozpro-child-test
 */

$posts = new WP_Query( [
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

if ( ! $posts->have_posts() ) {
    return;
}
?>
<section class="hp-blog">
    <h2 class="hp-blog__title">Najpopularniejsze artykuły</h2>
    <div class="hp-blog__grid">
        <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
            <article class="hp-blog__card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>" class="hp-blog__thumb">
                        <?php the_post_thumbnail( 'medium_large', [
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                        ] ); ?>
                    </a>
                <?php endif; ?>
                <div class="hp-blog__body">
                    <p class="hp-blog__meta">
                        <?php echo esc_html( get_the_author() ); ?> | <?php echo esc_html( get_the_date() ); ?>
                    </p>
                    <a href="<?php the_permalink(); ?>" class="hp-blog__heading">
                        <?php the_title(); ?>
                    </a>
                    <p class="hp-blog__excerpt">
                        <?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '...' ) ); ?>
                    </p>
                    <a href="<?php the_permalink(); ?>" class="hp-blog__more">Czytaj Więcej&nbsp;&gt;</a>
                </div>
            </article>
        <?php endwhile;
        wp_reset_postdata(); ?>
    </div>
</section>
