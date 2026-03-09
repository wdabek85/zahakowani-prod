<?php
/**
 * CPT: Katalog haków (spec_katalog)
 *
 * Each post = one catalog number (e.g. W/007) with a spec repeater.
 * Products link to a catalog entry via Post Object field.
 */

defined('ABSPATH') || exit;

/* ──────────────────────────────────────────────
 * 1. Register CPT — hidden, under WooCommerce menu
 * ────────────────────────────────────────────── */

add_action('init', function () {
    register_post_type('spec_katalog', [
        'labels' => [
            'name'               => 'Katalog haków',
            'singular_name'      => 'Wpis katalogowy',
            'add_new'            => 'Dodaj wpis',
            'add_new_item'       => 'Dodaj wpis katalogowy',
            'edit_item'          => 'Edytuj wpis katalogowy',
            'new_item'           => 'Nowy wpis katalogowy',
            'view_item'          => 'Zobacz wpis',
            'search_items'       => 'Szukaj w katalogu',
            'not_found'          => 'Nie znaleziono wpisów',
            'not_found_in_trash' => 'Brak w koszu',
            'all_items'          => 'Katalog haków',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => 'woocommerce',
        'supports'     => ['title', 'editor'],
        'capability_type' => 'product',
        'map_meta_cap'    => true,
    ]);
});

/* ──────────────────────────────────────────────
 * 2. ACF fields — repeater on catalog posts
 *    + Post Object on products
 * ────────────────────────────────────────────── */

add_action('acf/include_fields', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    // A) Spec repeater + gallery on catalog entries
    acf_add_local_field_group([
        'key'    => 'group_spec_katalog',
        'title'  => 'Specyfikacja haka',
        'fields' => [
            [
                'key'           => 'field_katalog_wariant',
                'label'         => 'Wybierz wariant',
                'name'          => 'katalog_wariant',
                'type'          => 'checkbox',
                'choices'       => [
                    'zestaw'       => 'Zestaw',
                    'modul_13pin'  => 'Moduł 13-Pin',
                    'modul_7pin'   => 'Moduł 7-Pin',
                    'wiazka_13pin' => 'Wiązka 13-Pin',
                    'wiazka_7pin'  => 'Wiązka 7-Pin',
                    'bestseller'   => 'Bestseller',
                    'nowość'       => 'Nowość',
                ],
                'layout'        => 'horizontal',
                'return_format' => 'value',
                'instructions'  => 'Odznaki przypisywane automatycznie do produktów powiązanych z tym katalogiem.',
            ],
            [
                'key'           => 'field_katalog_miniaturka',
                'label'         => 'Miniaturka',
                'name'          => 'katalog_miniaturka',
                'type'          => 'image',
                'return_format' => 'id',
                'preview_size'  => 'medium',
                'library'       => 'all',
                'instructions'  => 'Główne zdjęcie produktu. Dziedziczone gdy produkt nie ma własnej miniaturki.',
            ],
            [
                'key'          => 'field_katalog_galeria',
                'label'        => 'Galeria zdjęć',
                'name'         => 'katalog_galeria',
                'type'         => 'gallery',
                'return_format' => 'id',
                'library'      => 'all',
                'insert'       => 'append',
                'preview_size' => 'medium',
                'instructions' => 'Dodatkowe zdjęcia. Dziedziczone gdy produkt nie ma własnej galerii.',
            ],
            [
                'key'          => 'field_katalog_certyfikaty',
                'label'        => 'Certyfikaty',
                'name'         => 'katalog_certyfikaty',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => 'Dodaj certyfikat',
                'sub_fields'   => [
                    [
                        'key'     => 'field_katalog_cert_tytul',
                        'label'   => 'Tytuł',
                        'name'    => 'tytul',
                        'type'    => 'text',
                    ],
                    [
                        'key'     => 'field_katalog_cert_typ',
                        'label'   => 'Typ certyfikatu',
                        'name'    => 'typ_certyfikatu',
                        'type'    => 'select',
                        'choices' => [
                            'e20_hak'   => 'E20 Hak',
                            'e20_modul' => 'E20 Moduł',
                            'pja'       => 'PJA',
                        ],
                    ],
                    [
                        'key'           => 'field_katalog_cert_pdf',
                        'label'         => 'Plik PDF',
                        'name'          => 'plik_pdf',
                        'type'          => 'file',
                        'return_format' => 'array',
                        'mime_types'    => 'pdf',
                    ],
                    [
                        'key'   => 'field_katalog_cert_link',
                        'label' => 'Link info',
                        'name'  => 'link_info',
                        'type'  => 'url',
                    ],
                ],
            ],
            [
                'key'          => 'field_katalog_parametry',
                'label'        => 'Parametry',
                'name'         => 'katalog_parametry',
                'type'         => 'repeater',
                'layout'       => 'table',
                'button_label' => 'Dodaj parametr',
                'sub_fields'   => [
                    [
                        'key'   => 'field_katalog_param_nazwa',
                        'label' => 'Nazwa parametru',
                        'name'  => 'nazwa_parametru',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_katalog_param_wartosc',
                        'label' => 'Wartość parametru',
                        'name'  => 'wartosc_parametru',
                        'type'  => 'text',
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'spec_katalog',
                ],
            ],
        ],
    ]);

    // B) Post Object field on products — pick catalog entry
    acf_add_local_field_group([
        'key'    => 'group_product_katalog',
        'title'  => 'Numer katalogowy haka',
        'fields' => [
            [
                'key'           => 'field_product_numer_katalogowy',
                'label'         => 'Numer katalogowy',
                'name'          => 'numer_katalogowy',
                'type'          => 'post_object',
                'post_type'     => ['spec_katalog'],
                'return_format' => 'id',
                'allow_null'    => 1,
                'ui'            => 1,
                'instructions'  => 'Wybierz numer katalogowy haka — specyfikacja pobierze się automatycznie.',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'product',
                ],
            ],
        ],
        'position' => 'side',
    ]);
});

/* ──────────────────────────────────────────────
 * 3. get_catalog_specifications()
 *
 * Returns spec data from the linked catalog entry.
 * Output matches specyfikacja_rozszerzona structure.
 * ────────────────────────────────────────────── */

function get_catalog_specifications($product_id = null) {
    if (! $product_id) {
        $product_id = get_the_ID();
    }

    $katalog_id = get_field('numer_katalogowy', $product_id);

    if (empty($katalog_id)) {
        return [];
    }

    $params = get_field('katalog_parametry', $katalog_id);

    if (empty($params)) {
        return [];
    }

    return [
        [
            'nazwa_produktu' => 'Dane techniczne haka holowniczego',
            'parametry'      => $params,
        ],
    ];
}

/* ──────────────────────────────────────────────
 * 4. get_catalog_certyfikaty()
 *
 * Returns certificates from the linked catalog entry.
 * Same structure as the product `certyfikaty` repeater.
 * ────────────────────────────────────────────── */

function get_catalog_certyfikaty($product_id = null) {
    if (! $product_id) {
        $product_id = get_the_ID();
    }

    $katalog_id = get_field('numer_katalogowy', $product_id);

    if (empty($katalog_id)) {
        return [];
    }

    $certs = get_field('katalog_certyfikaty', $katalog_id);

    return ! empty($certs) ? $certs : [];
}

/* ──────────────────────────────────────────────
 * 5. WC filters — inherit thumbnail & gallery
 *    from catalog when product has none of its own.
 *    Works everywhere: product page, archives, cart.
 * ────────────────────────────────────────────── */

add_filter('woocommerce_product_get_image_id', function ($image_id, $product) {
    if ($image_id) {
        return $image_id;
    }

    $katalog_id = get_field('numer_katalogowy', $product->get_id());
    if (empty($katalog_id)) {
        return $image_id;
    }

    return get_field('katalog_miniaturka', $katalog_id) ?: $image_id;
}, 10, 2);

add_filter('woocommerce_product_get_gallery_image_ids', function ($ids, $product) {
    if (! empty($ids)) {
        return $ids;
    }

    $katalog_id = get_field('numer_katalogowy', $product->get_id());
    if (empty($katalog_id)) {
        return $ids;
    }

    $gallery = get_field('katalog_galeria', $katalog_id);

    return ! empty($gallery) ? $gallery : $ids;
}, 10, 2);

/* ──────────────────────────────────────────────
 * 5. Admin metabox — preview inherited media
 *    on the product edit screen.
 * ────────────────────────────────────────────── */

add_action('add_meta_boxes', function () {
    add_meta_box(
        'katalog_media_preview',
        'Zdjęcia z katalogu',
        'render_katalog_media_preview',
        'product',
        'side',
        'low'
    );

    add_meta_box(
        'katalog_linked_products',
        'Powiązane oferty',
        'render_katalog_linked_products',
        'spec_katalog',
        'side',
        'default'
    );
});

function render_katalog_media_preview($post) {
    $katalog_id = get_field('numer_katalogowy', $post->ID);

    if (empty($katalog_id)) {
        echo '<p style="color:#888">Wybierz numer katalogowy, aby dziedziczyć zdjęcia.</p>';
        return;
    }

    $katalog_title = get_the_title($katalog_id);
    $miniaturka_id = get_field('katalog_miniaturka', $katalog_id);
    $galeria_ids   = get_field('katalog_galeria', $katalog_id);
    $has_own_thumb  = has_post_thumbnail($post->ID);
    $wc_product     = wc_get_product($post->ID);
    $has_own_gallery = $wc_product && ! empty($wc_product->get_gallery_image_ids());

    $edit_url = get_edit_post_link($katalog_id);

    echo '<p style="margin-bottom:8px">Katalog: <a href="' . esc_url($edit_url) . '"><strong>' . esc_html($katalog_title) . '</strong></a></p>';

    // Thumbnail preview
    if ($miniaturka_id) {
        $status = $has_own_thumb ? ' <span style="color:#888">(nadpisana)</span>' : ' <span style="color:#2271b1">(aktywna)</span>';
        echo '<p style="margin:4px 0 2px"><strong>Miniaturka</strong>' . $status . '</p>';
        echo '<div style="margin-bottom:8px">' . wp_get_attachment_image($miniaturka_id, [80, 80], false, ['style' => 'border-radius:4px']) . '</div>';
    }

    // Gallery preview
    if (! empty($galeria_ids)) {
        $status = $has_own_gallery ? ' <span style="color:#888">(nadpisana)</span>' : ' <span style="color:#2271b1">(aktywna)</span>';
        echo '<p style="margin:4px 0 2px"><strong>Galeria</strong>' . $status . '</p>';
        echo '<div style="display:flex;flex-wrap:wrap;gap:4px">';
        foreach ($galeria_ids as $img_id) {
            echo wp_get_attachment_image($img_id, [50, 50], false, ['style' => 'border-radius:3px']);
        }
        echo '</div>';
    }

    if (empty($miniaturka_id) && empty($galeria_ids)) {
        echo '<p style="color:#888">Katalog nie ma jeszcze zdjęć. <a href="' . esc_url($edit_url) . '">Dodaj</a></p>';
    }
}

function render_katalog_linked_products($post) {
    $products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'meta_key'       => 'numer_katalogowy',
        'meta_value'     => $post->ID,
        'post_status'    => ['publish', 'draft', 'pending', 'private'],
    ]);

    $count = count($products);

    echo '<p style="margin-bottom:8px"><strong>' . $count . '</strong> ' . _n('oferta', 'ofert', $count) . '</p>';

    if (empty($products)) {
        echo '<p style="color:#888">Brak produktów powiązanych z tym katalogiem.</p>';
        return;
    }

    echo '<ul style="margin:0;padding:0;list-style:none">';
    foreach ($products as $product) {
        $edit_url = get_edit_post_link($product->ID);
        $status   = get_post_status($product->ID);
        $badge    = $status !== 'publish' ? ' <span style="color:#888">(' . $status . ')</span>' : '';
        echo '<li style="padding:4px 0;border-bottom:1px solid #f0f0f0">';
        echo '<a href="' . esc_url($edit_url) . '">' . esc_html($product->post_title) . '</a>' . $badge;
        echo '</li>';
    }
    echo '</ul>';
}

/* ──────────────────────────────────────────────
 * 7. Auto-fill product description from catalog.
 *
 * On product save: if the description is empty and
 * a catalog is linked, copy the catalog's description
 * into the product. User can then edit freely.
 * ────────────────────────────────────────────── */

add_action('save_post_product', function ($post_id, $post) {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    // Only seed when description is empty.
    if (! empty(trim($post->post_content))) {
        return;
    }

    $katalog_id = get_field('numer_katalogowy', $post_id);
    if (empty($katalog_id)) {
        return;
    }

    $katalog_opis = get_post_field('post_content', $katalog_id);
    if (empty($katalog_opis)) {
        return;
    }

    // Unhook to prevent recursion, update, rehook.
    remove_action('save_post_product', __FUNCTION__);
    wp_update_post([
        'ID'           => $post_id,
        'post_content' => $katalog_opis,
    ]);
    add_action('save_post_product', __FUNCTION__, 10, 2);
}, 10, 2);

/* ──────────────────────────────────────────────
 * 8. Sync katalog_wariant → product_badges
 *
 * a) On catalog save: propagate badges to all linked products.
 * b) On product save: inherit badges from linked catalog.
 * ────────────────────────────────────────────── */

// a) Catalog saved → update all linked products
add_action('save_post_spec_katalog', function ($katalog_id) {
    if (wp_is_post_revision($katalog_id) || wp_is_post_autosave($katalog_id)) {
        return;
    }

    $warianty = get_field('katalog_wariant', $katalog_id);
    if (! is_array($warianty)) {
        $warianty = [];
    }

    $products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'meta_key'       => 'numer_katalogowy',
        'meta_value'     => $katalog_id,
        'post_status'    => ['publish', 'draft', 'pending', 'private'],
        'fields'         => 'ids',
    ]);

    foreach ($products as $product_id) {
        update_field('product_badges', $warianty, $product_id);
    }
});

// b) Product saved → inherit badges from catalog
add_action('save_post_product', function ($post_id) {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    $katalog_id = get_field('numer_katalogowy', $post_id);
    if (empty($katalog_id)) {
        return;
    }

    $warianty = get_field('katalog_wariant', $katalog_id);
    if (! is_array($warianty)) {
        $warianty = [];
    }

    // Only sync if product doesn't have manually set badges different from catalog
    $current = get_field('product_badges', $post_id);
    if ($current === $warianty) {
        return;
    }

    update_field('product_badges', $warianty, $post_id);
}, 20); // priority 20 = after description auto-fill (priority 10)

/* ──────────────────────────────────────────────
 * 9. Sync catalog images → product native meta
 *
 * Copies katalog_miniaturka → _thumbnail_id and
 * katalog_galeria → _product_image_gallery so that
 * external plugins (CTX Feed, Google Shopping, etc.)
 * see images without relying on PHP filters.
 *
 * Only writes when the product has NO own image/gallery.
 * ────────────────────────────────────────────── */

/**
 * Helper: sync images from catalog to a single product.
 */
function azp_sync_catalog_images_to_product($product_id, $katalog_id) {
    // Thumbnail — only if product has no own thumbnail
    if (! has_post_thumbnail($product_id)) {
        $miniaturka_id = get_field('katalog_miniaturka', $katalog_id);
        if ($miniaturka_id) {
            set_post_thumbnail($product_id, $miniaturka_id);
        }
    }

    // Gallery — only if product has no own gallery
    $existing_gallery = get_post_meta($product_id, '_product_image_gallery', true);
    if (empty($existing_gallery)) {
        $galeria_ids = get_field('katalog_galeria', $katalog_id);
        if (! empty($galeria_ids) && is_array($galeria_ids)) {
            update_post_meta($product_id, '_product_image_gallery', implode(',', $galeria_ids));
        }
    }
}

// a) Catalog saved → sync images to all linked products
add_action('save_post_spec_katalog', function ($katalog_id) {
    if (wp_is_post_revision($katalog_id) || wp_is_post_autosave($katalog_id)) {
        return;
    }

    $products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'meta_key'       => 'numer_katalogowy',
        'meta_value'     => $katalog_id,
        'post_status'    => ['publish', 'draft', 'pending', 'private'],
        'fields'         => 'ids',
    ]);

    foreach ($products as $product_id) {
        azp_sync_catalog_images_to_product($product_id, $katalog_id);
    }
}, 20);

// b) Product saved → inherit images from linked catalog
add_action('save_post_product', function ($post_id) {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    $katalog_id = get_field('numer_katalogowy', $post_id);
    if (empty($katalog_id)) {
        return;
    }

    azp_sync_catalog_images_to_product($post_id, $katalog_id);
}, 25); // after badge sync (20)

/* ──────────────────────────────────────────────
 * 10. One-time bulk sync — admin tool
 *
 * Visit: /wp-admin/admin.php?action=azp_sync_catalog_images
 * Syncs catalog images to ALL products missing thumbnails.
 * Safe to run multiple times (skips products with own images).
 * ────────────────────────────────────────────── */

add_action('admin_action_azp_sync_catalog_images', function () {
    if (! current_user_can('manage_woocommerce')) {
        wp_die('Brak uprawnień.');
    }

    $products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => ['publish', 'draft', 'pending', 'private'],
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'     => 'numer_katalogowy',
                'compare' => 'EXISTS',
            ],
        ],
    ]);

    $synced = 0;
    foreach ($products as $product_id) {
        $katalog_id = get_field('numer_katalogowy', $product_id);
        if (empty($katalog_id)) {
            continue;
        }

        $had_thumb   = has_post_thumbnail($product_id);
        $had_gallery = ! empty(get_post_meta($product_id, '_product_image_gallery', true));

        azp_sync_catalog_images_to_product($product_id, $katalog_id);

        if (! $had_thumb || ! $had_gallery) {
            $synced++;
        }
    }

    wp_redirect(admin_url('edit.php?post_type=product&azp_synced=' . $synced));
    exit;
});

// Admin notice after bulk sync
add_action('admin_notices', function () {
    if (! isset($_GET['azp_synced'])) {
        return;
    }
    $count = (int) $_GET['azp_synced'];
    echo '<div class="notice notice-success is-dismissible"><p>Zsynchronizowano obrazki z katalogu dla <strong>' . $count . '</strong> produktów.</p></div>';
});
