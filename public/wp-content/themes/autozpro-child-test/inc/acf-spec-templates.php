<?php
/**
 * ACF Specification Templates
 *
 * Global spec templates linked to product badges.
 * WooCommerce > Szablony specyfikacji
 */

defined('ABSPATH') || exit;

/* ──────────────────────────────────────────────
 * 1. Options sub-page under WooCommerce
 * ────────────────────────────────────────────── */

add_action('acf/init', function () {
    if (! function_exists('acf_add_options_sub_page')) {
        return;
    }

    acf_add_options_sub_page([
        'page_title'  => 'Szablony specyfikacji',
        'menu_title'  => 'Szablony specyfikacji',
        'menu_slug'   => 'spec-templates',
        'parent_slug' => 'woocommerce',
        'capability'  => 'manage_woocommerce',
    ]);
});

/* ──────────────────────────────────────────────
 * 2. ACF field group — 4 tabs with repeaters
 * ────────────────────────────────────────────── */

add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    $tabs = [
        'modul_7pin'   => 'Moduł 7-Pin',
        'modul_13pin'  => 'Moduł 13-Pin',
        'wiazka_7pin'  => 'Wiązka 7-Pin',
        'wiazka_13pin' => 'Wiązka 13-Pin',
    ];

    $fields = [];

    foreach ($tabs as $key => $label) {
        // Tab
        $fields[] = [
            'key'   => 'field_spec_tab_' . $key,
            'label' => $label,
            'name'  => '',
            'type'  => 'tab',
        ];

        // Repeater
        $fields[] = [
            'key'        => 'field_spec_tpl_' . $key,
            'label'      => 'Parametry ' . $label,
            'name'       => 'spec_tpl_' . $key,
            'type'       => 'repeater',
            'layout'     => 'table',
            'button_label' => 'Dodaj parametr',
            'sub_fields' => [
                [
                    'key'   => 'field_spec_tpl_' . $key . '_nazwa',
                    'label' => 'Nazwa parametru',
                    'name'  => 'nazwa_parametru',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_spec_tpl_' . $key . '_wartosc',
                    'label' => 'Wartość parametru',
                    'name'  => 'wartosc_parametru',
                    'type'  => 'text',
                ],
            ],
        ];
    }

    acf_add_local_field_group([
        'key'      => 'group_spec_templates',
        'title'    => 'Szablony specyfikacji',
        'fields'   => $fields,
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'spec-templates',
                ],
            ],
        ],
    ]);
});

/* ──────────────────────────────────────────────
 * 3. get_badge_specifications()
 *
 * Returns spec data from global templates based
 * on the product's badges. Output structure matches
 * the `specyfikacja_rozszerzona` repeater so the
 * same rendering code works without changes.
 * ────────────────────────────────────────────── */

function get_badge_specifications($product_id = null) {
    if (! $product_id) {
        $product_id = get_the_ID();
    }

    $badges = get_field('product_badges', $product_id);

    if (empty($badges) || ! is_array($badges)) {
        return [];
    }

    // Only these badges have associated spec templates.
    $spec_badges = [
        'modul_7pin'   => 'Moduł 7-Pin',
        'modul_13pin'  => 'Moduł 13-Pin',
        'wiazka_7pin'  => 'Wiązka 7-Pin',
        'wiazka_13pin' => 'Wiązka 13-Pin',
    ];

    $specyfikacja = [];

    foreach ($badges as $badge) {
        if (! isset($spec_badges[$badge])) {
            continue;
        }

        $params = get_field('spec_tpl_' . $badge, 'option');

        if (empty($params)) {
            continue;
        }

        $specyfikacja[] = [
            'nazwa_produktu' => $spec_badges[$badge],
            'parametry'      => $params,
        ];
    }

    return $specyfikacja;
}
