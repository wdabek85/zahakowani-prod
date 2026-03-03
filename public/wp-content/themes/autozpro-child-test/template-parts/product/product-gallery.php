<?php
/**
 * Własna galeria produktu
 * - $glowne_zdjecie - główne zdjęcie produktu
 * - $galeria - dodatkowe zdjęcia z galerii WooCommerce
 */

global $product;

// Pobieramy ID głównego zdjęcia
$glowne_id = $product->get_image_id();

// Pobieramy IDs pozostałych zdjęć z galerii
$galeria_ids = $product->get_gallery_image_ids();
?>

<div class="product-gallery">

    <!-- Główne zdjęcie -->
    <div class="product-gallery__main">
        <?php if ($glowne_id) : ?>
            <?= wp_get_attachment_image($glowne_id, 'large', false, [
                'class' => 'product-gallery__main-img',
                'id'    => 'product-gallery-main'
            ]) ?>
        <?php endif; ?>
    </div>

    <!-- Miniatury -->
    <?php if ($galeria_ids) : ?>
        <div class="product-gallery__thumbs">

            <?php // Najpierw miniaturka głównego zdjęcia ?>
            <div class="product-gallery__thumb active" data-full="<?= esc_url(wp_get_attachment_url($glowne_id)) ?>">
                <?= wp_get_attachment_image($glowne_id, 'thumbnail') ?>
            </div>

            <?php foreach ($galeria_ids as $id) : ?>
                <div class="product-gallery__thumb" data-full="<?= esc_url(wp_get_attachment_url($id)) ?>">
                    <?= wp_get_attachment_image($id, 'thumbnail') ?>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>