<?php
/**
 * Helper do renderowania ikon z plików SVG
 * Użycie: echo get_icon('star', 'w-6 h-6');
 */

function get_icon($name, $class = '') {
    $file = get_stylesheet_directory() . '/assets/icons/' . $name . '.svg';
    
    if (!file_exists($file)) {
        return '';
    }
    
    $svg = file_get_contents($file);
    
    // Dodaj klasę do SVG
    if ($class) {
        $svg = str_replace('<svg', '<svg class="' . esc_attr($class) . '"', $svg);
    }
    
    return $svg;
}