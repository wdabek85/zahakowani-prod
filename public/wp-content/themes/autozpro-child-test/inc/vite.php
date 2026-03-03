<?php
/**
 * Vite integration for WordPress.
 *
 * In dev:  loads assets from Vite dev server (HMR)
 * In prod: loads built assets from dist/ via manifest.json
 */

define('VITE_DEV_SERVER', 'http://localhost:5173');
define('VITE_ENTRY', 'assets/js/main.js');

/**
 * Check if Vite dev server is running.
 */
function vite_is_dev(): bool {
    if (defined('WP_ENV') && WP_ENV !== 'development') {
        return false;
    }

    static $is_dev = null;
    if ($is_dev !== null) {
        return $is_dev;
    }

    // Check for the hot file that Vite creates
    $hot_file = get_stylesheet_directory() . '/dist/hot';
    if (file_exists($hot_file)) {
        $is_dev = true;
        return true;
    }

    $is_dev = false;
    return false;
}

/**
 * Enqueue Vite assets.
 */
function vite_enqueue_assets(): void {
    $theme_uri = get_stylesheet_directory_uri();
    $theme_dir = get_stylesheet_directory();

    if (vite_is_dev()) {
        // Dev mode — load from Vite dev server
        // Vite client for HMR
        wp_enqueue_script_module('vite-client', VITE_DEV_SERVER . '/@vite/client');

        // Main entry (CSS is injected by Vite via JS)
        wp_enqueue_script_module('child-main-js', VITE_DEV_SERVER . '/' . VITE_ENTRY);

        return;
    }

    // Production — read manifest and enqueue built files
    $manifest_path = $theme_dir . '/dist/.vite/manifest.json';
    if (!file_exists($manifest_path)) {
        // No build yet — fall back to raw CSS
        wp_enqueue_style(
            'child-main',
            $theme_uri . '/assets/css/main.css',
            [],
            filemtime($theme_dir . '/assets/css/main.css')
        );
        return;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = $manifest[VITE_ENTRY] ?? null;

    if (!$entry) {
        return;
    }

    // Enqueue built CSS
    if (!empty($entry['css'])) {
        foreach ($entry['css'] as $i => $css_file) {
            $css_path = $theme_dir . '/dist/' . $css_file;
            wp_enqueue_style(
                'child-main' . ($i > 0 ? "-$i" : ''),
                $theme_uri . '/dist/' . $css_file,
                [],
                file_exists($css_path) ? filemtime($css_path) : null
            );
        }
    }

    // Enqueue built JS
    if (!empty($entry['file'])) {
        wp_enqueue_script_module(
            'child-main-js',
            $theme_uri . '/dist/' . $entry['file']
        );
    }
}
