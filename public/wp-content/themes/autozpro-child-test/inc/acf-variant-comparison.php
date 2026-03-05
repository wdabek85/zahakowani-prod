<?php
/**
 * ACF: Porównanie wariantów elektryki (wiązka/moduł 7-pin/13-pin)
 *
 * Rejestruje stronę opcji WooCommerce > Porównanie wariantów
 * oraz helper get_variant_comparison().
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
        'page_title'  => 'Porównanie wariantów',
        'menu_title'  => 'Porównanie wariantów',
        'menu_slug'   => 'variant-comparison',
        'parent_slug' => 'woocommerce',
        'capability'  => 'manage_woocommerce',
    ]);
});

/**
 * Rejestracja pól ACF dla tabeli porównawczej.
 */
add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'      => 'group_variant_comparison',
        'title'    => 'Tabela porównawcza wariantów',
        'fields'   => [
            // Podtytuły kolumn
            [
                'key'   => 'field_vc_subtitle_wiazka_7pin',
                'label' => 'Podtytuł: Wiązka 7-pin',
                'name'  => 'vc_subtitle_wiazka_7pin',
                'type'  => 'text',
                'default_value' => 'Podstawowa instalacja',
            ],
            [
                'key'   => 'field_vc_subtitle_wiazka_13pin',
                'label' => 'Podtytuł: Wiązka 13-pin',
                'name'  => 'vc_subtitle_wiazka_13pin',
                'type'  => 'text',
                'default_value' => 'Rozszerzona instalacja',
            ],
            [
                'key'   => 'field_vc_subtitle_modul_7pin',
                'label' => 'Podtytuł: Moduł 7-pin',
                'name'  => 'vc_subtitle_modul_7pin',
                'type'  => 'text',
                'default_value' => 'Inteligentna instalacja',
            ],
            [
                'key'   => 'field_vc_subtitle_modul_13pin',
                'label' => 'Podtytuł: Moduł 13-pin',
                'name'  => 'vc_subtitle_modul_13pin',
                'type'  => 'text',
                'default_value' => 'Pełna inteligentna instalacja',
            ],
            // Repeater wierszy
            [
                'key'        => 'field_variant_comparison_rows',
                'label'      => 'Wiersze tabeli',
                'name'       => 'variant_comparison_rows',
                'type'       => 'repeater',
                'layout'     => 'table',
                'button_label' => 'Dodaj wiersz',
                'sub_fields' => [
                    [
                        'key'   => 'field_vc_nazwa_cechy',
                        'label' => 'Cecha',
                        'name'  => 'nazwa_cechy',
                        'type'  => 'text',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'   => 'field_vc_wiazka_7pin',
                        'label' => 'Wiązka 7-pin',
                        'name'  => 'wiazka_7pin',
                        'type'  => 'text',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'   => 'field_vc_wiazka_13pin',
                        'label' => 'Wiązka 13-pin',
                        'name'  => 'wiazka_13pin',
                        'type'  => 'text',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'   => 'field_vc_modul_7pin',
                        'label' => 'Moduł 7-pin',
                        'name'  => 'modul_7pin',
                        'type'  => 'text',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'   => 'field_vc_modul_13pin',
                        'label' => 'Moduł 13-pin',
                        'name'  => 'modul_13pin',
                        'type'  => 'text',
                        'wrapper' => ['width' => '20'],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'variant-comparison',
                ],
            ],
        ],
    ]);
});

/**
 * Pobierz dane tabeli porównawczej.
 *
 * @return array{subtitles: array, rows: array}
 */
function get_variant_comparison(): array {
    $rows = get_field('variant_comparison_rows', 'option');

    // Fallback — demo dane gdy ACF puste
    if (empty($rows)) {
        $rows = [
            [
                'nazwa_cechy'  => 'Typ elektryki',
                'wiazka_7pin'  => 'Wiązka (prostsza)',
                'wiazka_13pin' => 'Wiązka (prostsza)',
                'modul_7pin'   => 'Moduł (inteligentny)',
                'modul_13pin'  => 'Moduł (inteligentny)',
            ],
            [
                'nazwa_cechy'  => 'Liczba pinów',
                'wiazka_7pin'  => '7-pin',
                'wiazka_13pin' => '13-pin',
                'modul_7pin'   => '7-pin',
                'modul_13pin'  => '13-pin',
            ],
            [
                'nazwa_cechy'  => 'Ładowanie akumulatora przyczepy',
                'wiazka_7pin'  => 'Nie',
                'wiazka_13pin' => 'Tak',
                'modul_7pin'   => 'Nie',
                'modul_13pin'  => 'Tak',
            ],
            [
                'nazwa_cechy'  => 'Światło cofania przyczepy',
                'wiazka_7pin'  => 'Nie',
                'wiazka_13pin' => 'Tak',
                'modul_7pin'   => 'Nie',
                'modul_13pin'  => 'Tak',
            ],
            [
                'nazwa_cechy'  => 'Czujniki parkowania',
                'wiazka_7pin'  => 'Wymaga ręcznej konfiguracji',
                'wiazka_13pin' => 'Wymaga ręcznej konfiguracji',
                'modul_7pin'   => 'Automatyczne wyłączanie',
                'modul_13pin'  => 'Automatyczne wyłączanie',
            ],
            [
                'nazwa_cechy'  => 'Polecany dla',
                'wiazka_7pin'  => 'Przyczepka towarowa, rowery',
                'wiazka_13pin' => 'Przyczepa kempingowa, łódka',
                'modul_7pin'   => 'Przyczepka towarowa, rowery',
                'modul_13pin'  => 'Przyczepa kempingowa, łódka',
            ],
        ];
    }

    $subtitles = [
        'wiazka_7pin'  => get_field('vc_subtitle_wiazka_7pin', 'option') ?: 'Podstawowa instalacja',
        'wiazka_13pin' => get_field('vc_subtitle_wiazka_13pin', 'option') ?: 'Rozszerzona instalacja',
        'modul_7pin'   => get_field('vc_subtitle_modul_7pin', 'option') ?: 'Inteligentna instalacja',
        'modul_13pin'  => get_field('vc_subtitle_modul_13pin', 'option') ?: 'Pełna inteligentna instalacja',
    ];

    return compact('subtitles', 'rows');
}
