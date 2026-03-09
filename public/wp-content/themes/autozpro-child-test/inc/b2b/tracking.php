<?php
/**
 * B2B: Śledzenie zamówień — sumy miesięczne
 *
 * Zapisuje/cofa sumy zamówień mechaników B2B w user meta.
 * Format klucza: b2b_monthly_YYYY_MM
 * Flaga _b2b_tracked na zamówieniu zapobiega podwójnemu liczeniu.
 */

defined('ABSPATH') || exit;

/**
 * Dodaj sumę zamówienia do user meta (processing / completed).
 */
add_action('woocommerce_order_status_processing', 'azp_b2b_track_order');
add_action('woocommerce_order_status_completed', 'azp_b2b_track_order');

function azp_b2b_track_order(int $order_id): void {
    $order = wc_get_order($order_id);
    if (! $order) {
        return;
    }

    if ($order->get_meta('_b2b_tracked')) {
        return;
    }

    $user_id = $order->get_customer_id();
    if (! $user_id) {
        return;
    }

    $user = get_userdata($user_id);
    if (! $user || ! in_array('b2b_mechanic', $user->roles, true)) {
        return;
    }

    $date    = $order->get_date_created();
    $key     = 'b2b_monthly_' . $date->format('Y_m');
    $current = (float) get_user_meta($user_id, $key, true);
    $total   = (float) $order->get_total();

    update_user_meta($user_id, $key, $current + $total);
    $order->update_meta_data('_b2b_tracked', 'added');
    $order->update_meta_data('_b2b_tracked_amount', $total);
    $order->save();
}

/**
 * Odejmij sumę zamówienia (cancelled / refunded).
 */
add_action('woocommerce_order_status_cancelled', 'azp_b2b_untrack_order');
add_action('woocommerce_order_status_refunded', 'azp_b2b_untrack_order');

function azp_b2b_untrack_order(int $order_id): void {
    $order = wc_get_order($order_id);
    if (! $order) {
        return;
    }

    if ($order->get_meta('_b2b_tracked') !== 'added') {
        return;
    }

    $user_id = $order->get_customer_id();
    if (! $user_id) {
        return;
    }

    $date    = $order->get_date_created();
    $key     = 'b2b_monthly_' . $date->format('Y_m');
    $current = (float) get_user_meta($user_id, $key, true);
    $amount  = (float) $order->get_meta('_b2b_tracked_amount');

    update_user_meta($user_id, $key, max(0, $current - $amount));
    $order->update_meta_data('_b2b_tracked', 'removed');
    $order->save();
}

/**
 * Pobierz sumę zamówień za dany miesiąc.
 */
function azp_b2b_get_monthly_total(int $user_id, string $year_month): float {
    return (float) get_user_meta($user_id, 'b2b_monthly_' . $year_month, true);
}

/**
 * Suma za poprzedni miesiąc.
 */
function azp_b2b_get_previous_month_total(int $user_id): float {
    $key = date('Y_m', strtotime('first day of last month'));
    return azp_b2b_get_monthly_total($user_id, $key);
}

/**
 * Suma za bieżący miesiąc.
 */
function azp_b2b_get_current_month_total(int $user_id): float {
    $key = date('Y_m');
    return azp_b2b_get_monthly_total($user_id, $key);
}

/**
 * Aktualny procent rabatu na podstawie sumy z poprzedniego miesiąca.
 */
function azp_b2b_get_discount_percent(int $user_id): float {
    $settings   = azp_b2b_get_settings();
    $prev_total = azp_b2b_get_previous_month_total($user_id);

    if ($prev_total >= $settings['threshold']) {
        return $settings['discount_high'];
    }

    return $settings['discount_low'];
}

/**
 * Historia miesięczna — ostatnie N miesięcy.
 *
 * @return array<array{month: string, total: float, discount: float}>
 */
function azp_b2b_get_monthly_history(int $user_id, int $months = 12): array {
    $settings = azp_b2b_get_settings();
    $history  = [];

    for ($i = 0; $i < $months; $i++) {
        $timestamp = strtotime("-{$i} months");
        $key       = date('Y_m', $timestamp);
        $total     = azp_b2b_get_monthly_total($user_id, $key);

        // Rabat dla danego miesiąca = oparty o miesiąc wcześniejszy
        $prev_timestamp = strtotime('-1 month', $timestamp);
        $prev_key       = date('Y_m', $prev_timestamp);
        $prev_total     = azp_b2b_get_monthly_total($user_id, $prev_key);
        $discount       = $prev_total >= $settings['threshold']
            ? $settings['discount_high']
            : $settings['discount_low'];

        $history[] = [
            'month'    => date('Y-m', $timestamp),
            'label'    => date_i18n('F Y', $timestamp),
            'total'    => $total,
            'discount' => $discount,
        ];
    }

    return $history;
}
