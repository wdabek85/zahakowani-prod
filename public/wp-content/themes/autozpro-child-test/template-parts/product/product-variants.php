<?php
/**
 * Warianty produktu
 */

defined('ABSPATH') || exit;

$variants = get_product_variants();

// Nie pokazuj sekcji jeśli nie ma wariantów
if (empty($variants['current']) && empty($variants['related'])) {
    return;
}
?>

<div class="product-variants" aria-label="Warianty produktu">
    <p class="variant-tittle text-md-bold">Warianty Produktu:</p>
    
    <div class="product-variants__chips">
        <?php if (!empty($variants['current'])) : ?>
            <span class="product-variants__chip text-xs-bold is-active" aria-current="true">
                <?= esc_html($variants['current']) ?>
            </span>
        <?php endif; ?>

        <?php foreach ($variants['related'] as $v) : ?>
            <a class="product-variants__chip text-xs-bold" href="<?= esc_url($v['url']) ?>">
                <?= esc_html($v['label']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>