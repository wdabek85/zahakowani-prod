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
 * Add type="module" to scripts that use ES import/export.
 */
add_filter('script_loader_tag', function ($tag, $handle) {
    $module_scripts = ['child-mobile-filter', 'child-vehicle-search'];
    if (in_array($handle, $module_scripts, true)) {
        $tag = str_replace(' src', ' type="module" src', $tag);
    }
    return $tag;
}, 10, 2);
