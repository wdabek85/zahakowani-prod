<?php
/**
 * Odznaki nad tytułem produktu
 */

$badges = get_field('product_badges');

if (empty($badges)) return;

$badge_labels = [
    'zestaw'       => 'Zestaw',
    'modul_13pin'  => 'Moduł 13-Pin',
    'modul_7pin'   => 'Moduł 7-Pin',
    'wiazka_13pin' => 'Wiązka 13-Pin',
    'wiazka_7pin'  => 'Wiązka 7-Pin',
    'bestseller'   => 'Bestseller',
    'nowość'       => 'Nowość',
];
?>

<div class="product-badges">
    <?php foreach ($badges as $badge) : ?>
        <span class="text-sm-semibold badge badge--<?= esc_attr($badge) ?>">
            <?= esc_html($badge_labels[$badge] ?? ucfirst(str_replace('_', ' ', $badge))) ?>
        </span>
    <?php endforeach; ?>
</div>