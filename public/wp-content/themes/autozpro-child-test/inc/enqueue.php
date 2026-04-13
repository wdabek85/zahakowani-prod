<?php
function child_enqueue_assets() {
    // Style parenta (obowiązek w child theme)
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // CSS child theme
    wp_enqueue_style(
        'child-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        ['parent-style'],
        filemtime(get_stylesheet_directory() . '/assets/css/main.css')
    );

    // JS components
    $js_dir = get_stylesheet_directory() . '/assets/js/components/';
    $js_uri = get_stylesheet_directory_uri() . '/assets/js/components/';
    if (is_dir($js_dir)) {
        foreach (glob($js_dir . '*.js') as $file) {
            $name = 'child-' . basename($file, '.js');
            wp_enqueue_script(
                $name,
                $js_uri . basename($file),
                [],
                filemtime($file),
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'child_enqueue_assets');

/**
 * Dequeue Elementor frontend CSS/JS on pages not built with Elementor.
 * Elementor stays active for admin/editor, but stops bloating frontend.
 */
add_action('wp_enqueue_scripts', function () {
    if (is_admin()) return;

    // Only dequeue on pages NOT built with Elementor
    $post_id = get_the_ID();
    if ($post_id && get_post_meta($post_id, '_elementor_edit_mode', true) === 'builder') {
        return; // This page uses Elementor — keep its assets
    }

    // Dequeue Elementor frontend styles
    wp_dequeue_style('elementor-frontend');
    wp_dequeue_style('elementor-post-css');
    wp_dequeue_style('elementor-global');
    wp_dequeue_style('elementor-icons');
    wp_dequeue_style('elementor-animations');
    wp_dequeue_style('elementor-common');

    // Dequeue Elementor frontend scripts
    wp_dequeue_script('elementor-frontend');
    wp_dequeue_script('elementor-common');
    wp_dequeue_script('elementor-pro-frontend');
}, 999);

/**
 * Self-host Google Fonts (Manrope) — eliminates external DNS + request.
 * Preconnect is already handled by LiteSpeed DNS prefetch.
 */
add_action('wp_enqueue_scripts', function () {
    // Remove duplicate trustindex loader if present
    wp_dequeue_script('flavor-widget-loader');
}, 20);

/**
 * Add type="module" to scripts that use ES import/export.
 */
add_filter('script_loader_tag', function ($tag, $handle) {
    $module_scripts = ['child-mobile-filter', 'child-vehicle-search'];
    if (in_array($handle, $module_scripts, true)) {
        $tag = str_replace(' src', ' type="module" src', $tag);
    }
    return $tag;
}, 10, 2);
