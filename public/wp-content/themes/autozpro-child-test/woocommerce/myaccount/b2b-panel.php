<?php
/**
 * Panel B2B — szablon w Moje Konto
 *
 * Karty rabatu, podsumowania miesięczne, tabela historii.
 */

defined('ABSPATH') || exit;

$user_id          = get_current_user_id();
$settings         = azp_b2b_get_settings();
$discount_percent = azp_b2b_get_discount_percent($user_id);
$prev_total       = azp_b2b_get_previous_month_total($user_id);
$current_total    = azp_b2b_get_current_month_total($user_id);
$threshold        = $settings['threshold'];
$above_threshold  = $prev_total >= $threshold;
$missing          = max(0, $threshold - $prev_total);
$current_missing  = max(0, $threshold - $current_total);
$history          = azp_b2b_get_monthly_history($user_id, 12);

$prev_month_label    = date_i18n('F Y', strtotime('first day of last month'));
$current_month_label = date_i18n('F Y');
?>

<div class="b2b-panel">

    <!-- Karty podsumowania -->
    <div class="b2b-panel__cards">

        <!-- Aktualny rabat -->
        <div class="b2b-card b2b-card--accent">
            <div class="b2b-card__label">Twój rabat</div>
            <div class="b2b-card__value"><?= $discount_percent ?>%</div>
            <div class="b2b-card__sublabel">
                na podstawie: <?= esc_html($prev_month_label) ?>
            </div>
        </div>

        <!-- Poprzedni miesiąc -->
        <div class="b2b-card">
            <div class="b2b-card__label">Poprzedni miesiąc</div>
            <div class="b2b-card__value"><?= number_format($prev_total, 2, ',', ' ') ?> zł</div>
            <div class="b2b-card__sublabel">
                <?php if ($above_threshold) : ?>
                    <span class="b2b-badge b2b-badge--green">Próg osiągnięty</span>
                <?php else : ?>
                    <span class="b2b-badge b2b-badge--yellow">Brakuje <?= number_format($missing, 2, ',', ' ') ?> zł do <?= $settings['discount_high'] ?>%</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bieżący miesiąc -->
        <div class="b2b-card">
            <div class="b2b-card__label">Bieżący miesiąc</div>
            <div class="b2b-card__value"><?= number_format($current_total, 2, ',', ' ') ?> zł</div>
            <div class="b2b-card__sublabel">
                <?php if ($current_total >= $threshold) : ?>
                    <span class="b2b-badge b2b-badge--green">Próg na następny miesiąc osiągnięty!</span>
                <?php else : ?>
                    Jeszcze <?= number_format($current_missing, 2, ',', ' ') ?> zł do progu <?= number_format($threshold, 0, ',', ' ') ?> zł
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Info box -->
    <div class="b2b-panel__info">
        <strong>Jak działają rabaty B2B?</strong>
        <ul>
            <li>Rabat naliczany jest automatycznie w koszyku na wybrane kategorie produktów.</li>
            <li>Poniżej progu <?= number_format($threshold, 0, ',', ' ') ?> zł/mies. — rabat <strong><?= $settings['discount_low'] ?>%</strong>.</li>
            <li>Powyżej progu <?= number_format($threshold, 0, ',', ' ') ?> zł/mies. — rabat <strong><?= $settings['discount_high'] ?>%</strong>.</li>
            <li>Próg oparty jest o sumę zamówień z <strong>poprzedniego miesiąca</strong>.</li>
        </ul>
    </div>

    <!-- Tabela historii -->
    <div class="b2b-panel__history">
        <h3>Historia miesięczna</h3>
        <table class="b2b-table">
            <thead>
                <tr>
                    <th>Miesiąc</th>
                    <th>Suma zamówień</th>
                    <th>Rabat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $row) : ?>
                    <tr>
                        <td><?= esc_html($row['label']) ?></td>
                        <td><?= number_format($row['total'], 2, ',', ' ') ?> zł</td>
                        <td><?= $row['discount'] ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
