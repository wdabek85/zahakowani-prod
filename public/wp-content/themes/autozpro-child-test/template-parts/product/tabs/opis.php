<?php
/**
 * Zakładka: Opis produktu
 * Domyślnie przycięty, rozwijany przyciskiem
 */

defined('ABSPATH') || exit;

global $product;
$opis = $product->get_description();

if (empty($opis)) return;
?>

<section id="opis" class="product-tab-section">
    <div class="container">
        <h2 class="tab-section__title display-md-medium">Opis Produktu</h2>
    
        <div class="tab-section__content">
            <div class="product-description" data-collapsed="true">
                <div class="product-description__text">
                    <?= wp_kses_post($opis) ?>
                </div>
                <button class="product-description__toggle btn btn-primary">
                    Rozwiń Pełen Opis
                </button>
            </div>
        </div>
    </div>
</section>