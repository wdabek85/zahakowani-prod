<?php
/**
 * Sidebar for WooCommerce shop/archive pages.
 *
 * Overrides parent theme sidebar.php when called via get_sidebar('shop').
 * Renders widget area (filters) + two informational blocks below.
 */

$sidebar = apply_filters('autozpro_theme_sidebar', '');
if (!$sidebar) {
    return;
}
?>

<div id="secondary" class="widget-area" role="complementary">
    <?php dynamic_sidebar($sidebar); ?>

    <?php get_template_part('template-parts/sidebar/why-us'); ?>
    <?php get_template_part('template-parts/sidebar/compare-variants'); ?>
</div><!-- #secondary -->
