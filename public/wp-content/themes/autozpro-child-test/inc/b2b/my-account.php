<?php
/**
 * B2B: Endpoint /moje-konto/panel-b2b/
 *
 * Rejestruje endpoint, dodaje pozycję w menu Moje Konto,
 * ładuje szablon panelu B2B. Widoczne tylko dla roli b2b_mechanic.
 */

defined('ABSPATH') || exit;

/**
 * Rejestracja endpointu.
 */
add_action('init', function () {
    add_rewrite_endpoint('panel-b2b', EP_ROOT | EP_PAGES);
});

/**
 * Auto-flush rewrite rules przy pierwszym załadowaniu.
 */
add_action('init', function () {
    if (get_option('azp_b2b_flush_rewrite') !== '1.0') {
        flush_rewrite_rules();
        update_option('azp_b2b_flush_rewrite', '1.0');
    }
}, 99);

/**
 * Dodaj pozycję "Panel B2B" w menu Moje Konto (tylko dla mechaników).
 */
add_filter('woocommerce_account_menu_items', function (array $items): array {
    if (! is_user_logged_in()) {
        return $items;
    }

    $user = wp_get_current_user();
    if (! in_array('b2b_mechanic', $user->roles, true)) {
        return $items;
    }

    // Wstaw przed "Wyloguj"
    $new_items = [];
    foreach ($items as $key => $label) {
        if ($key === 'customer-logout') {
            $new_items['panel-b2b'] = 'Panel B2B';
        }
        $new_items[$key] = $label;
    }

    return $new_items;
});

/**
 * Załaduj szablon panelu B2B.
 */
add_action('woocommerce_account_panel-b2b_endpoint', function () {
    $user = wp_get_current_user();
    if (! in_array('b2b_mechanic', $user->roles, true)) {
        echo '<p>Brak dostępu do panelu B2B.</p>';
        return;
    }

    $template = get_stylesheet_directory() . '/woocommerce/myaccount/b2b-panel.php';
    if (file_exists($template)) {
        include $template;
    }
});

/**
 * Tytuł endpointu.
 */
add_filter('the_title', function (string $title, ?int $id = null): string {
    if (is_wc_endpoint_url('panel-b2b') && in_the_loop() && is_account_page()) {
        $title = 'Panel B2B';
    }
    return $title;
}, 10, 2);
