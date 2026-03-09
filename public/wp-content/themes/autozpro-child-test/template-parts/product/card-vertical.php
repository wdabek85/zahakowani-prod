<?php
/**
 * Reużywalna pionowa karta produktu
 * Użycie: get_template_part('template-parts/product/card-vertical');
 * Wymaga: global $product (setup_postdata + wc_setup_product_data)
 */

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}

$marka    = get_product_brand();
$delivery = get_delivery_message();
$rating   = $product->get_average_rating();
$reviews  = $product->get_review_count();
$cats     = get_the_terms($product->get_id(), 'product_cat');
$cat_names = [];
if ($marka) {
    $cat_names[] = $marka['name'];
}
if (!empty($cats) && !is_wp_error($cats)) {
    foreach ($cats as $cat) {
        $cat_names[] = $cat->name;
    }
}
?>

<a href="<?= esc_url($product->get_permalink()) ?>" class="v-product-card">
    <div class="v-product-card__top">
        <div class="v-product-card__header">
            <div class="v-product-card__image">
                <?= $product->get_image('woocommerce_thumbnail', ['loading' => 'lazy', 'decoding' => 'async']) ?>
            </div>

            <div class="v-product-card__rating-row">
                <div class="v-product-card__rating">
                    <div class="v-product-card__stars">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <span class="v-product-card__star <?= ($rating > 0 && $i <= round($rating)) ? 'v-product-card__star--filled' : 'v-product-card__star--empty' ?>">
                                <?= get_icon('star', 'v-product-card__star-icon') ?>
                            </span>
                        <?php endfor; ?>
                    <?php if ($reviews > 0) : ?>
                        <span class="v-product-card__reviews">(<?= esc_html($reviews) ?>)</span>
                    <?php endif; ?>
                    </div>
                </div>
                <span class="v-product-card__info-icon"><?= get_icon('information-circle', 'v-product-card__info-svg') ?></span>
            </div>

            <div class="v-product-card__text">
                <h3 class="v-product-card__title"><?= esc_html($product->get_name()) ?></h3>
                <?php if (!empty($cat_names)) : ?>
                    <p class="v-product-card__categories"><?= esc_html(implode(', ', $cat_names)) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php $b2b = azp_b2b_get_product_pricing($product); ?>
        <div class="v-product-card__price-block">
            <p class="v-product-card__price">
                <?= number_format($b2b ? $b2b['b2b_price'] : $product->get_price(), 2, '.', '') ?>zł
                <?php if ($b2b) : ?>
                    <span class="buy-box__b2b-badge">-<?= $b2b['discount_percent'] ?>%</span>
                <?php endif; ?>
            </p>
            <?php if ($b2b) : ?>
                <p class="buy-box__catalog-price text-xs-regular">
                    Cena katalogowa: <span class="buy-box__catalog-price-amount"><?= number_format($b2b['catalog_price'], 2, '.', '') ?> zł</span>
                </p>
            <?php endif; ?>
            <p class="v-product-card__vat">Cena zawiera 23% VAT, nie obejmuje <strong>kosztów dostawy</strong></p>
        </div>
    </div>

    <p class="v-product-card__delivery">
        <span class="v-product-card__delivery-prefix"><?= esc_html($delivery['prefix']) ?></span>
        <strong class="v-product-card__delivery-strong"><?= esc_html($delivery['strong']) ?></strong>
    </p>

    <span class="v-product-card__cta">Kup Teraz</span>
</a>
