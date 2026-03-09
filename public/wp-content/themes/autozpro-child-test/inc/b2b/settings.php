<?php
/**
 * B2B: ACF Options Page — Rabaty B2B
 *
 * Strona ustawień WooCommerce > Rabaty B2B.
 * Progi rabatowe, procenty i kategorie objęte rabatem.
 */

defined('ABSPATH') || exit;

/**
 * Rejestracja ACF Options sub-page pod WooCommerce.
 */
add_action('acf/init', function () {
    if (! function_exists('acf_add_options_sub_page')) {
        return;
    }

    acf_add_options_sub_page([
        'page_title'  => 'Rabaty B2B',
        'menu_title'  => 'Rabaty B2B',
        'menu_slug'   => 'b2b-discounts',
        'parent_slug' => 'woocommerce',
        'capability'  => 'manage_woocommerce',
    ]);
});

/**
 * Rejestracja pól ACF dla ustawień B2B.
 */
add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'    => 'group_b2b_settings',
        'title'  => 'Ustawienia rabatów B2B',
        'fields' => [
            [
                'key'           => 'field_b2b_threshold',
                'label'         => 'Próg rabatowy (zł)',
                'name'          => 'b2b_threshold',
                'type'          => 'number',
                'instructions'  => 'Suma zamówień z poprzedniego miesiąca wymagana do wyższego rabatu.',
                'default_value' => 20000,
                'min'           => 0,
                'step'          => 100,
            ],
            [
                'key'           => 'field_b2b_discount_low',
                'label'         => 'Rabat poniżej progu (%)',
                'name'          => 'b2b_discount_low',
                'type'          => 'number',
                'instructions'  => 'Rabat procentowy gdy suma poprzedniego miesiąca < próg.',
                'default_value' => 8,
                'min'           => 0,
                'max'           => 100,
                'step'          => 1,
            ],
            [
                'key'           => 'field_b2b_discount_high',
                'label'         => 'Rabat powyżej progu (%)',
                'name'          => 'b2b_discount_high',
                'type'          => 'number',
                'instructions'  => 'Rabat procentowy gdy suma poprzedniego miesiąca >= próg.',
                'default_value' => 12,
                'min'           => 0,
                'max'           => 100,
                'step'          => 1,
            ],
            [
                'key'           => 'field_b2b_categories',
                'label'         => 'Kategorie objęte rabatem',
                'name'          => 'b2b_categories',
                'type'          => 'taxonomy',
                'instructions'  => 'Wybierz kategorie produktów, na które naliczany jest rabat B2B. Podkategorie są uwzględniane automatycznie.',
                'taxonomy'      => 'product_cat',
                'field_type'    => 'multi_select',
                'allow_null'    => 1,
                'return_format' => 'id',
            ],
            [
                'key'           => 'field_b2b_discount_label',
                'label'         => 'Etykieta rabatu',
                'name'          => 'b2b_discount_label',
                'type'          => 'text',
                'instructions'  => 'Nazwa wyświetlana w koszyku jako ujemna opłata.',
                'default_value' => 'Rabat B2B',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'b2b-discounts',
                ],
            ],
        ],
    ]);
});

/**
 * Pobierz wszystkie ustawienia B2B z fallbackami.
 *
 * @return array{threshold: float, discount_low: float, discount_high: float, categories: int[], label: string}
 */
function azp_b2b_get_settings(): array {
    return [
        'threshold'     => (float) (get_field('b2b_threshold', 'option') ?: 20000),
        'discount_low'  => (float) (get_field('b2b_discount_low', 'option') ?: 8),
        'discount_high' => (float) (get_field('b2b_discount_high', 'option') ?: 12),
        'categories'    => get_field('b2b_categories', 'option') ?: [],
        'label'         => get_field('b2b_discount_label', 'option') ?: 'Rabat B2B',
    ];
}
