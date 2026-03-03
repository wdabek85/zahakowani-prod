<?php
/**
 * Hero Banner dla stron kategorii / taksonomii
 *
 * Wyświetla: tytuł, opis, USP badge'e, blok kontaktowy.
 * Działa dla product_cat, product_brand, i innych taksonomii WC.
 * Na stronie sklepu (shop) — fallback do domyślnego WC opisu.
 */

defined('ABSPATH') || exit;

$term = get_queried_object();

// Fallback: strona sklepu lub brak terma — domyślny nagłówek WC
if (is_shop() || ! $term instanceof WP_Term) {
    do_action('woocommerce_archive_description');
    return;
}

$term_name        = $term->name;
$term_description = term_description($term->term_id);
$product_count    = $term->count;
$delivery         = get_delivery_message();
?>

<section class="category-hero">
    <div class="category-hero__container">

        <div class="category-hero__main">
            <h1 class="category-hero__title"><?php echo esc_html($term_name); ?></h1>

            <?php if ($term_description) : ?>
                <div class="category-hero__description"><?php echo $term_description; ?></div>
            <?php endif; ?>

            <div class="category-hero__usps">
                <div class="category-hero__usp">
                    <span class="category-hero__usp-icon"><?php echo get_icon('cog', 'icon-sm'); ?></span>
                    <span class="category-hero__usp-text"><?php echo esc_html($product_count); ?> produktów do wyboru</span>
                </div>
                <div class="category-hero__usp">
                    <span class="category-hero__usp-icon"><?php echo get_icon('check-badge', 'icon-sm'); ?></span>
                    <span class="category-hero__usp-text">Gwarancja dopasowania</span>
                </div>
                <div class="category-hero__usp">
                    <span class="category-hero__usp-icon"><?php echo get_icon('truck', 'icon-sm'); ?></span>
                    <span class="category-hero__usp-text"><?php echo esc_html($delivery['prefix']); ?><strong><?php echo esc_html($delivery['strong']); ?></strong></span>
                </div>
            </div>
        </div>

        <div class="category-hero__consult">
            <span class="category-hero__consult-label">Potrzebujesz pomocy?</span>

            <div class="category-hero__consult-links">
                <a href="tel:+48536731515" class="category-hero__phone">
                    <?php echo get_icon('phone-ring', 'icon-sm'); ?>
                    <span>+48 536 731 515</span>
                </a>
                <a href="mailto:kontakt@autohaki.pl" class="category-hero__email">
                    <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 4-10 8L2 4"/></svg>
                    <span>kontakt@autohaki.pl</span>
                </a>
            </div>

            <a href="tel:+48536731515" class="category-hero__cta-btn">
                <?php echo get_icon('phone-ring', 'icon-sm'); ?>
                Bezpłatna konsultacja
            </a>
        </div>

    </div>
</section>
