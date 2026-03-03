<?php
/**
 * Homepage template — replaces Elementor page content.
 *
 * WordPress automatically uses front-page.php when it exists in the active theme,
 * overriding both Elementor page content and the default page.php.
 *
 * We close .col-full opened by header.php so the homepage can go full-width,
 * then reopen it before footer.php which expects to close it.
 *
 * @package autozpro-child-test
 */

get_header(); ?>

<?php // Close .col-full opened by header.php ?>
</div><!-- .col-full -->

<div class="homepage">
    <?php get_template_part( 'template-parts/homepage/hero' ); ?>
    <?php get_template_part( 'template-parts/homepage/brands' ); ?>
    <?php get_template_part( 'template-parts/homepage/featured-products' ); ?>
    <?php get_template_part( 'template-parts/homepage/promo-strip' ); ?>
    <?php get_template_part( 'template-parts/homepage/reviews' ); ?>
    <?php get_template_part( 'template-parts/homepage/trust-stats' ); ?>
    <?php get_template_part( 'template-parts/homepage/guides' ); ?>
    <?php get_template_part( 'template-parts/homepage/blog' ); ?>
    <?php get_template_part( 'template-parts/homepage/seo-text' ); ?>
</div>

<?php // Reopen .col-full for footer.php to close ?>
<div class="col-full">

<?php get_footer(); ?>
