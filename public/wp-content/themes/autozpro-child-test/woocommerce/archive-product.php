<?php
/**
 * Archiwum produktów - nadpisany szablon
 */

defined('ABSPATH') || exit;

get_header('shop');
// header.php already opens <div id="content"><div class="col-full">
// footer.php closes them — no extra .col-full wrapper needed
?>

<!-- Breadcrumbs + konsultacja (konsultacja tylko na nie-kategoriowych) -->
<div class="breadcrumb-bar">
    <div class="breadcrumb-bar__left">
        <?php woocommerce_breadcrumb(); ?>
    </div>
    <?php if (is_shop() || ! get_queried_object() instanceof WP_Term) : ?>
        <div class="breadcrumb-bar__right">
            <div class="breadcrumb-consult">
                <div class="breadcrumb-consult__text">
                    <span class="text-xs-regular">Potrzebujesz pomocy?</span>
                    <strong class="text-sm-bold">Skorzystaj z naszej bezpłatnej konsultacji</strong>
                </div>
                <div class="breadcrumb-consult__contact">
                    <a href="mailto:kontakt@autohaki.pl" class="breadcrumb-consult__link">
                        <svg class="icon-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 4-10 8L2 4"/></svg>
                        <span>kontakt@autohaki.pl</span>
                    </a>
                    <a href="tel:+48536731515" class="breadcrumb-consult__link">
                        <svg class="icon-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.81.36 1.6.68 2.35a2 2 0 0 1-.45 2.11L8.09 9.43a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.75.32 1.54.55 2.35.68A2 2 0 0 1 22 16.92z"/></svg>
                        <span>+48 536 731 515</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="woocommerce">

    <?php if (woocommerce_product_loop()) : ?>

        <!-- Hero banner kategorii / fallback do domyślnego opisu WC -->
        <?php get_template_part('template-parts/archive/category-hero'); ?>

        <!-- Sortowanie -->
        <?php do_action('woocommerce_before_shop_loop'); ?>

        <!-- Przycisk filtrów mobile -->
        <button type="button" class="mobile-filter-toggle" id="mobile-filter-toggle">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="16" y2="12"/><line x1="4" y1="18" x2="12" y2="18"/></svg>
            Filtry
        </button>

        <!-- Overlay filtrów mobile -->
        <div class="mobile-filter-overlay" id="mobile-filter-overlay"></div>

        <!-- Lista produktów -->
        <div class="products-wrap">
            <?php
            // Sidebar (zostaje z motywu)
            get_sidebar('shop');

            // Produkty
            woocommerce_product_loop_start();

            if (wc_get_loop_prop('total')) {
                while (have_posts()) {
                    the_post();
                    do_action('woocommerce_shop_loop');
                    wc_get_template_part('content', 'product');
                }
            }

            woocommerce_product_loop_end();
            ?>
        </div>

        <?php do_action('woocommerce_after_shop_loop'); ?>

        <?php
        // ACF SEO Description — pole WYSIWYG przypisane do taksonomii product_cat
        $term = get_queried_object();
        if ($term && !is_wp_error($term) && isset($term->term_id)) {
            $seo_desc = get_field('seo_description', $term);
            if ($seo_desc) {
                echo '<div class="category-seo-description">' . $seo_desc . '</div>';
            }
        }
        ?>

    <?php else : ?>
        <?php do_action('woocommerce_no_products_found'); ?>
    <?php endif; ?>

</div>

<?php
get_footer('shop');