<?php
/**
 * Register "Poradnik" Custom Post Type.
 *
 * Slug: poradnik / poradniki (archive)
 * Supports: title, editor, thumbnail, excerpt
 *
 * @package autozpro-child-test
 */

add_action( 'init', 'autozpro_register_guide_cpt' );

function autozpro_register_guide_cpt() {
    register_post_type( 'poradnik', [
        'labels' => [
            'name'               => 'Poradniki',
            'singular_name'      => 'Poradnik',
            'add_new'            => 'Dodaj nowy',
            'add_new_item'       => 'Dodaj nowy poradnik',
            'edit_item'          => 'Edytuj poradnik',
            'new_item'           => 'Nowy poradnik',
            'view_item'          => 'Zobacz poradnik',
            'search_items'       => 'Szukaj poradników',
            'not_found'          => 'Nie znaleziono poradników',
            'not_found_in_trash' => 'Nie znaleziono w koszu',
            'all_items'          => 'Wszystkie poradniki',
            'menu_name'          => 'Poradniki',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => [ 'slug' => 'poradniki', 'with_front' => false ],
        'menu_icon'    => 'dashicons-book-alt',
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments' ],
        'show_in_rest' => true,
    ] );
}
