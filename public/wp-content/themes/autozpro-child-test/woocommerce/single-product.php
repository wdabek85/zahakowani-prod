<?php
/**
 * Strona pojedynczego produktu
 * Lewa kolumna: tytuł, marka, galeria, specyfikacje, warianty
 * Prawa kolumna: sidebar (baner, logo, cena, ikony)
 */

defined('ABSPATH') || exit;

get_header();

// Uruchamia WooCommerce dla tego produktu - WYMAGANE, bez tego nic nie działa
the_post();
?>

<?php
    get_template_part('template-parts/product/product-breadcrumbs');
?>
    
<div class="single-product-wrapper">
    <div class="single-product-grid">
        
        <!-- LEWA KOLUMNA -->
        <div class="single-product__left">
            <div class="single-product__left__header">
                <?php
                // Tytuł produktu - the_title() to standardowy WordPress
                get_template_part('template-parts/product/product-brand');
                the_title('<h1 class="product__title text-xl-bold">', '</h1>');
                get_template_part('template-parts/product/product-badges');
                ?>
            </div>
            <div class="single-product__left__inner">
                <?php
                // Galeria - Zadanie 2
                get_template_part('template-parts/product/product-gallery');
                ?>
                <div class="single-product__left_inner_specs_variants">
                    <?php
                    // Specyfikacje ACF - Zadanie 3
                    get_template_part('template-parts/product/product-specs');

                    // Warianty ACF - Zadanie 4
                    get_template_part('template-parts/product/product-variants');

                    // CTA porównanie wariantów
                    get_template_part('template-parts/product/variant-compare-cta');
                    ?>
                </div>
            </div>

            <?php get_template_part('template-parts/product/product-guarantee'); ?>

        </div>

        <!-- PRAWA KOLUMNA -->
        <div class="single-product__right">

            <?php
            // Sidebar - Zadanie 5
            get_template_part('template-parts/product/sidebar/product-sidebar');
            ?>

        </div>

    </div>
</div>
<!-- Baner promocyjny -->
<div class="promo-strip">
    <div class="container">
        <div class="promo-strip__content">
            <p class="text-md-bold">
                Darmowa Dostawa od 450zł. Oraz 
                <strong class="text-primary">RABAT 5%</strong> 
                na pierwsze zakupy dla Zarejestrowanych użytkowników. 
                <a href="/regulamin-promocji" class="text-primary">Sprawdź Regulamin Promocji</a>
            </p>
        </div>
    </div>
</div>
<!-- Nawigacja zakładek -->
<nav class="product-tabs-nav" id="product-tabs-nav">
    <div class="container">
        <a href="#opis" class="tab-link active">Opis</a>
        <a href="#specyfikacja" class="tab-link">Specyfikacja</a>
        <a href="#faq" class="tab-link">FAQ</a>
        <a href="#certyfikaty" class="tab-link">Certyfikaty</a>
        <a href="#opinie" class="tab-link">Opinie</a>
    </div>
</nav>

<!-- Treść zakładek -->
<div class="product-tabs-content">
    <?php
    get_template_part('template-parts/product/tabs/opis');
    get_template_part('template-parts/product/tabs/specyfikacja');
    get_template_part('template-parts/product/tabs/faq');
    get_template_part('template-parts/product/tabs/certyfikaty');
    get_template_part('template-parts/product/tabs/dostawa');
    get_template_part('template-parts/product/tabs/opinie');
    ?>
</div>

<?php get_template_part('template-parts/product/variant-comparison'); ?>

<?php
get_template_part('template-parts/product/product-section-trust-stats');
get_template_part('template-parts/product/product-section-reviews');
get_template_part('template-parts/product/product-section-guides');
get_template_part('template-parts/product/product-section-related');
get_template_part('template-parts/product/product-section-featured');
?>

<?php
get_template_part('template-parts/product/sticky-buy-bar');
get_template_part('template-parts/product/sidebar/trust-drawers');

get_footer();