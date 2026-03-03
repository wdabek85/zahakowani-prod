<?php
/**
 * Shared: Trust Stats grid — 4 karty zaufania
 * Reużywalne w: strona produktu, homepage
 */

defined('ABSPATH') || exit;

$stats = [
    [
        'color' => '#10b981',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>',
        'value' => '5 000+',
        'label' => 'Sprzedanych haków',
        'desc'  => 'Tysiące klientów zaufało naszemu doświadczeniu przy wyborze haka holowniczego',
    ],
    [
        'color' => '#3b82f6',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        'value' => '12 lat',
        'label' => 'Doświadczenia',
        'desc'  => 'Działamy na rynku od 2014 roku. Znamy każdy model haka jak własną kieszeń',
    ],
    [
        'color' => '#f59e0b',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'value' => '4.9/5',
        'label' => 'Średnia ocen',
        'desc'  => 'Bazując na opiniach z Google, Opineo i bezpośrednich recenzjach klientów',
    ],
    [
        'color' => '#ef4444',
        'icon'  => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
        'value' => '98%',
        'label' => 'Poleca nas dalej',
        'desc'  => 'Prawie każdy klient mówi, że poleciłby nas znajomym i rodzinie',
    ],
];
?>

<div class="trust-stats__grid">
    <?php foreach ($stats as $stat) : ?>
        <div class="trust-stats__card">
            <span class="trust-stats__icon" style="background: <?php echo esc_attr($stat['color']); ?>1a; color: <?php echo esc_attr($stat['color']); ?>">
                <?php echo $stat['icon']; ?>
            </span>
            <span class="trust-stats__value" style="color: <?php echo esc_attr($stat['color']); ?>">
                <?php echo esc_html($stat['value']); ?>
            </span>
            <strong class="trust-stats__label"><?php echo esc_html($stat['label']); ?></strong>
            <p class="trust-stats__desc"><?php echo esc_html($stat['desc']); ?></p>
        </div>
    <?php endforeach; ?>
</div>
