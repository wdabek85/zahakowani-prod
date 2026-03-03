<?php
/**
 * Register custom WooCommerce product attributes.
 *
 * Creates "Marka samochodu" attribute (pa_marka-samochodu) once.
 * After registration, terms (VW, Fiat, BMW…) can be added in
 * WP Admin → Products → Attributes → Marka samochodu,
 * and assigned to individual products.
 *
 * @package autozpro-child-test
 */

add_action( 'init', 'autozpro_register_car_brand_attribute' );

function autozpro_register_car_brand_attribute() {
    if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
        return;
    }

    $slug = 'marka-samochodu';

    // Check if attribute already exists.
    $existing = wc_get_attribute_taxonomies();
    foreach ( $existing as $attr ) {
        if ( $attr->attribute_name === $slug ) {
            return; // Already registered.
        }
    }

    wc_create_attribute( [
        'name'         => 'Marka samochodu',
        'slug'         => $slug,
        'type'         => 'select',
        'order_by'     => 'name',
        'has_archives' => true,
    ] );

    // Flush rewrite rules once so archive URLs work.
    flush_rewrite_rules();
}
