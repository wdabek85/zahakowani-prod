<?php
/**
 * CTA "Nie wiesz co wybrać?" — inline baner pod chipami wariantów.
 * Linkuje do tabeli porównawczej (#porownanie-wariantow).
 */

defined('ABSPATH') || exit;
?>

<div class="vc-cta">
    <div class="vc-cta__inner">
        <svg class="vc-cta__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <span class="vc-cta__text">Nie wiesz który wariant wiązki wybrać?</span>
        <a href="#porownanie-wariantow" class="vc-cta__link">
            Porównaj warianty
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                <path d="M8 3v10M3 8l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
</div>
