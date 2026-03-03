<?php
// inc/helpers.php

/**
 * Pobiera dane marki produktu
 * Użycie: $marka = get_product_brand();
 * Potem: $marka['name'], $marka['logo'], $marka['url']
 */
function get_product_brand() {
    $terms = get_the_terms(get_the_ID(), 'product_brand');

    if (empty($terms) || is_wp_error($terms)) return null;

    $marka        = $terms[0];
    $thumbnail_id = get_term_meta($marka->term_id, 'thumbnail_id', true);

    return [
        'name' => $marka->name,
        'logo' => wp_get_attachment_url($thumbnail_id),
        'url'  => get_term_link($marka),
    ];
}