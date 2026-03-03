<?php
/**
 * Zakładka: Dostawa
 */

defined('ABSPATH') || exit;

global $product;

$cena = $product->get_price();
$darmowa_dostawa = $cena >= 450;

// Kurierzy - logo w assets/images/couriers/
$kurierzy = [
    [
        'nazwa' => 'Kurier InPost',
        'logo'  => get_stylesheet_directory_uri() . '/assets/images/couriers/inpost.png',
    ],
    [
        'nazwa' => 'Kurier DPD',
        'logo'  => get_stylesheet_directory_uri() . '/assets/images/couriers/dpd.png',
    ],
    [
        'nazwa' => 'Kurier GLS',
        'logo'  => get_stylesheet_directory_uri() . '/assets/images/couriers/gls.png',
    ],
];
?>

<section id="dostawa" class="product-tab-section">
    <div class="container">
        <div class="tab-section__header">
            <?= get_icon('truck', 'icon-lg') ?>
            <h2 class="tab-section__title text-xxl-bold">Dostawa</h2>
        </div>
        
        <div class="tab-section__content">
            <h3 class="delivery-subtitle text-xl-semibold">Kurier</h3>
            
            <div class="delivery-methods">
                <?php foreach ($kurierzy as $kurier) : ?>
                    <div class="delivery-method">
                        <div class="delivery-method__name text-md-regular">
                            <?= esc_html($kurier['nazwa']) ?>
                        </div>
                        
                        <div class="delivery-method__logo">
                            <img src="<?= esc_url($kurier['logo']) ?>" alt="<?= esc_attr($kurier['nazwa']) ?>">
                        </div>
                        
                        <div class="delivery-method__price">
                            <?php if ($darmowa_dostawa) : ?>
                                <span class="text-md-bold">Dostawa <strong class="text-accent">GRATIS!</strong></span>
                                <span class="text-sm-regular text-muted">Darmowa Dostawa od 450zł</span>
                            <?php else : ?>
                                <span class="text-md-regular text-muted">Koszt dostawy ustalany przy zamówieniu</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>