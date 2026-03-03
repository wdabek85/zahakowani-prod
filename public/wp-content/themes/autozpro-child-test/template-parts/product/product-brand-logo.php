<?php
$marka = get_product_brand();
if (!$marka) return;

// Pobieramy term_id żeby sprawdzić pole ACF
$terms = get_the_terms(get_the_ID(), 'product_brand');
$term_id = $terms[0]->term_id ?? null;
$autoryzowany = $term_id ? get_field('autoryzowany_dystrybutor', 'product_brand_' . $term_id) : false;
?>

<div class="logo-brand">
    <img src="<?= esc_url($marka['logo']) ?>" alt="<?= esc_attr($marka['name']) ?>">
    
    <?php if ($autoryzowany) : ?>
        <div class="brand-badge">
            <?= get_icon('shield-check', 'icon-lg') ?>
            <span class="text-xs-semibold">Jesteśmy autoryzowanym dystrybutorem marki <?= esc_html($marka['name']) ?></span>
        </div>
    <?php endif; ?>
</div>