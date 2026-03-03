<?php
/**
 * Sticky Buy Bar — widoczny po opuszczeniu buy-boxa z viewport
 * Desktop: góra ekranu | Mobile ≤768px: dół ekranu
 */

defined('ABSPATH') || exit;

global $product;

if ( ! $product || ! is_a($product, 'WC_Product') ) {
    return;
}
?>

<div class="sticky-buy-bar" id="sticky-buy-bar">
    <div class="sticky-buy-bar__container">
        <p class="sticky-buy-bar__name text-md-bold"><?= esc_html($product->get_name()) ?></p>
        <div class="sticky-buy-bar__right">
            <span class="sticky-buy-bar__price display-xs-medium">
                <?= number_format($product->get_price(), 2, ',', ' ') ?><?= get_woocommerce_currency_symbol() ?>
            </span>
            <a href="<?= esc_url($product->add_to_cart_url()) ?>" class="sticky-buy-bar__cta btn btn-primary">
                Kup Teraz
            </a>
        </div>
    </div>
</div>
