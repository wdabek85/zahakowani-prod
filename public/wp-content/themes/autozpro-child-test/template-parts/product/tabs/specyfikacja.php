<?php
/**
 * Zakładka: Specyfikacja rozszerzona
 */

defined('ABSPATH') || exit;

$specyfikacja = array_merge(
    get_field('specyfikacja_rozszerzona') ?: [],
    get_catalog_specifications(),
    get_badge_specifications()
);
if (empty($specyfikacja)) return;
?>

<section id="specyfikacja" class="product-tab-section">
    <div class="container">
        <div class="tab-section__header">
            <?= get_icon('cog', 'icon-lg') ?>
            <h2 class="tab-section__title display-md-medium">Specyfikacja</h2>
        </div>
        
        <div class="tab-section__content">
            <?php foreach ($specyfikacja as $kategoria) : ?>
                <div class="spec-category">
                    <h3 class="spec-category__title display-xs-medium">
                        <?= esc_html($kategoria['nazwa_produktu']) ?>
                    </h3>
                    
                    <table class="spec-table">
                        <tbody>
                            <?php foreach ($kategoria['parametry'] as $param) : ?>
                                <tr>
                                    <td class="spec-table__label text-sm-regular">
                                        <?= esc_html($param['nazwa_parametru']) ?>
                                    </td>
                                    <td class="spec-table__value text-sm-semibold">
                                        <?= esc_html($param['wartosc_parametru']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>