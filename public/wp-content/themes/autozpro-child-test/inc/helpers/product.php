<?php
/**
 * Zwraca dane wariantów produktu
 * Pole ACF: wariant (select) + wielowariantowosc (relacja)
 */
function get_product_variants($product_id = null) {
    if (!$product_id) {
        $product_id = get_the_ID();
    }

    // Aktualny wariant
    $current = get_field('wariant', $product_id);
    $current = !empty($current) ? (string) $current : null;

    // Powiązane produkty (relacja)
    $rel = get_field('wielowariantowosc', $product_id);
    
    $ids = [];
    if (!empty($rel)) {
        foreach ((array) $rel as $item) {
            if (is_numeric($item)) {
                $ids[] = (int) $item;
            } elseif (is_object($item)) {
                if (isset($item->ID)) {
                    $ids[] = (int) $item->ID;
                } elseif (method_exists($item, 'get_id')) {
                    $ids[] = (int) $item->get_id();
                }
            }
        }
    }

    $ids = array_values(array_unique(array_filter($ids)));
    $ids = array_values(array_diff($ids, [$product_id])); // usuń siebie

    // Buduj listę wariantów
    $related = [];
    foreach ($ids as $id) {
        $url = get_permalink($id);
        if (empty($url)) continue;

        $label = get_field('wariant', $id);
        $label = !empty($label) ? (string) $label : get_the_title($id);

        $related[] = [
            'id'    => $id,
            'label' => $label,
            'url'   => $url,
        ];
    }

    return [
        'current' => $current,
        'related' => $related,
        'count'   => count($related),
    ];
}