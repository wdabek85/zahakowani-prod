<?php
/**
 * Sidebar block: "Nie wiesz co wybrać?" — compare variants CTA
 */

defined('ABSPATH') || exit;
?>

<div class="sidebar-compare">
    <div class="sidebar-compare__header">
<?php echo get_icon('cog', 'sidebar-compare__icon'); ?>
        <h3 class="sidebar-compare__title">Nie wiesz co wybrać?</h3>
    </div>

    <p class="sidebar-compare__desc">Porównaj parametry techniczne haków holowniczych i dobierz idealny wariant do swojego auta.</p>

    <a href="#porownanie-wariantow" class="sidebar-compare__btn">
        Porównaj warianty
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M8 3v10M3 8l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>
