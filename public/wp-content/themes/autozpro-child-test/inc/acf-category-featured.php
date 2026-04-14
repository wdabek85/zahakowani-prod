<?php
/**
 * ACF: Polecany produkt dla kategorii (pole na taksonomii product_cat)
 *
 * Pozwala wybrać konkretny produkt, który ma być wyświetlany jako "Polecany"
 * w nav mega menu dla danej kategorii/marki. Fallback: bestseller.
 */

defined('ABSPATH') || exit;

add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'      => 'group_category_featured',
        'title'    => 'Polecany produkt w mega menu',
        'fields'   => [
            [
                'key'           => 'field_category_featured_product',
                'label'         => 'Polecany produkt',
                'name'          => 'featured_product',
                'type'          => 'post_object',
                'instructions'  => 'Wybierz produkt, który ma się wyświetlać jako "Polecany" w mega menu dla tej kategorii/marki. Jeśli nie wybierzesz — system wybierze bestseller automatycznie.',
                'post_type'     => ['product'],
                'return_format' => 'id',
                'multiple'      => 0,
                'allow_null'    => 1,
                'ui'            => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'taxonomy',
                    'operator' => '==',
                    'value'    => 'product_cat',
                ],
            ],
        ],
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'label_placement' => 'top',
    ]);
});
