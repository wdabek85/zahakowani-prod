<?php
/**
 * Sidebar block: "Dlaczego my?" — 5 USP trust points
 */

defined('ABSPATH') || exit;

$items = [
    [
        'title' => 'Autoryzowany dystrybutor',
        'desc'  => 'Imioła Hak-Pol — gwarancja oryginalności',
    ],
    [
        'title' => '31 dni na zwrot',
        'desc'  => 'bez podania przyczyny',
    ],
    [
        'title' => 'Gwarancja dopasowania',
        'desc'  => 'potwierdzamy kompatybilność z VIN',
    ],
    [
        'title' => 'Darmowa dostawa od 450 zł',
        'desc'  => 'wysyłka w 24h',
    ],
    [
        'title' => '2 lata gwarancji producenta',
        'desc'  => 'na każdy hak holowniczy',
    ],
];
?>

<div class="sidebar-why-us">
    <div class="sidebar-why-us__header">
        <?php echo get_icon('shield-check', 'sidebar-why-us__icon'); ?>
        <h3 class="sidebar-why-us__title">Dlaczego my?</h3>
    </div>

    <ul class="sidebar-why-us__list">
        <?php foreach ($items as $item) : ?>
            <li class="sidebar-why-us__item">
                <svg class="sidebar-why-us__check" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 1 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0z" fill="#10b981"/>
                </svg>
                <span class="sidebar-why-us__text">
                    <strong><?php echo esc_html($item['title']); ?></strong> — <?php echo esc_html($item['desc']); ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
