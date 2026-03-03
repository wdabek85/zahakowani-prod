<?php defined('ABSPATH') || exit; ?>

<div class="product-trust-icons">
    <button type="button" class="trust-icon" data-drawer="zwrot" aria-label="Informacje o zwrocie">
        <?= get_icon('calendar', 'icon-md') ?>
        <span class="text-xs-regular">31 dni <strong>na zwrot</strong></span>
    </button>

    <button type="button" class="trust-icon" data-drawer="platnosci" aria-label="Informacje o płatnościach">
        <?= get_icon('shield-check', 'icon-md') ?>
        <span class="text-xs-regular">Bezpieczne <strong>Płatności</strong></span>
    </button>

    <button type="button" class="trust-icon" data-drawer="wysylka" aria-label="Informacje o wysyłce">
        <?= get_icon('truck', 'icon-md') ?>
        <span class="text-xs-regular">Wysyłka <strong>w 24h</strong></span>
    </button>
</div>