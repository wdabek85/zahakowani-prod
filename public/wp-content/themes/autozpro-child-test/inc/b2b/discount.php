<?php
/**
 * B2B: Logika rabatu w koszyku
 *
 * Ujemna opłata (fee) w koszyku dla mechaników B2B.
 * Rabat naliczany tylko na produkty z wybranych kategorii (+ podkategorie).
 * Notatka B2B przy cenie w buy-box.
 */

defined('ABSPATH') || exit;

/**
 * Rabat B2B jako ujemna opłata w koszyku.
 */
add_action('woocommerce_cart_calculate_fees', function (WC_Cart $cart): void {
    if (is_admin() && ! defined('DOING_AJAX')) {
        return;
    }

    $user_id = get_current_user_id();
    if (! $user_id) {
        return;
    }

    $user = get_userdata($user_id);
    if (! $user || ! in_array('b2b_mechanic', $user->roles, true)) {
        return;
    }

    $settings   = azp_b2b_get_settings();
    $categories = $settings['categories'];

    if (empty($categories)) {
        return;
    }

    $discount_percent = azp_b2b_get_discount_percent($user_id);
    if ($discount_percent <= 0) {
        return;
    }

    // Oblicz sumę produktów z kategorii B2B
    $eligible_total = 0;

    foreach ($cart->get_cart() as $item) {
        $product_id = $item['product_id'];
        if (azp_b2b_product_in_categories($product_id, $categories)) {
            $eligible_total += (float) $item['line_total'];
        }
    }

    if ($eligible_total <= 0) {
        return;
    }

    $discount_amount = -1 * round($eligible_total * ($discount_percent / 100), 2);
    $label           = $settings['label'] . ' (-' . $discount_percent . '%)';

    $cart->add_fee($label, $discount_amount, true);
});

/**
 * Sprawdź czy produkt należy do kategorii B2B (z podkategoriami).
 *
 * @param int   $product_id
 * @param int[] $categories  ID kategorii z ustawień B2B
 */
function azp_b2b_product_in_categories(int $product_id, array $categories): bool {
    $product_cats = wc_get_product_cat_ids($product_id);

    foreach ($product_cats as $cat_id) {
        if (in_array($cat_id, $categories, true)) {
            return true;
        }
        // Sprawdź czy któryś przodek jest w liście
        $ancestors = get_ancestors($cat_id, 'product_cat', 'taxonomy');
        foreach ($ancestors as $ancestor_id) {
            if (in_array($ancestor_id, $categories, true)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Pobierz dane cenowe B2B dla produktu (lub null jeśli nie dotyczy).
 *
 * @return array{b2b_price: float, catalog_price: float, discount_percent: float}|null
 */
function azp_b2b_get_product_pricing(?WC_Product $product = null): ?array {
    if (! is_user_logged_in()) {
        return null;
    }

    $user_id = get_current_user_id();
    $user    = get_userdata($user_id);

    if (! $user || ! in_array('b2b_mechanic', $user->roles, true)) {
        return null;
    }

    if (! $product) {
        global $product;
    }
    if (! $product instanceof WC_Product) {
        return null;
    }

    $settings   = azp_b2b_get_settings();
    $categories = $settings['categories'];

    if (empty($categories) || ! azp_b2b_product_in_categories($product->get_id(), $categories)) {
        return null;
    }

    $discount_percent = azp_b2b_get_discount_percent($user_id);
    if ($discount_percent <= 0) {
        return null;
    }

    $catalog_price = (float) $product->get_price();

    return [
        'b2b_price'        => round($catalog_price * (1 - $discount_percent / 100), 2),
        'catalog_price'    => $catalog_price,
        'discount_percent' => $discount_percent,
    ];
}

/**
 * Filtr na woocommerce_get_price_html — cena B2B wszędzie
 * (grid, related, upsells, widgety, mini-cart, itp.)
 */
add_filter('woocommerce_get_price_html', function (string $price_html, WC_Product $product): string {
    $b2b = azp_b2b_get_product_pricing($product);
    if (! $b2b) {
        return $price_html;
    }

    $currency = get_woocommerce_currency_symbol();

    return sprintf(
        '<span class="b2b-price-wrap">'
        . '<span class="b2b-price-main">%s %s</span> '
        . '<span class="buy-box__b2b-badge">-%s%%</span>'
        . '<br><span class="buy-box__catalog-price">Cena katalogowa: '
        . '<span class="buy-box__catalog-price-amount">%s %s</span></span>'
        . '</span>',
        number_format($b2b['b2b_price'], 2, ',', ' '),
        $currency,
        $b2b['discount_percent'],
        number_format($b2b['catalog_price'], 2, ',', ' '),
        $currency
    );
}, 10, 2);

/**
 * Legacy wrapper — wyświetla notatkę B2B (zachowana dla kompatybilności).
 */
function azp_b2b_price_note(): void {
    // Obsługiwane bezpośrednio w buy-box.php
}
