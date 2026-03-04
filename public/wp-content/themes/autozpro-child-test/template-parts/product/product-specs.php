<?php
/**
 * Tabela specyfikacji produktu
 */

defined('ABSPATH') || exit;

// Definicja parametrów - kontrolujesz kolejność i nazwy
$parametry = [
    'uciag'                     => 'Uciąg',
    'nacisk_na_kule_haka'       => 'Nacisk na kule haka',
    'montaz_bez_ciecia_zderzaka'=> 'Montaż bez cięcia zderzaka',
    'homologacja_haka'          => 'Homologacja haka',
    'gwarancja'                 => 'Gwarancja',
    'kula_haka'                 => 'Kula Haka',
    'pasuje_do_aut:'            => 'Pasuje do aut',
];
?>

<div class="product-spec" aria-label="Specyfikacja">
    <div class="product-spec__list">
        <?php foreach ($parametry as $slug => $label) : ?>
            <?php $wartosc = get_field($slug); ?>
            <?php if ($wartosc) : ?>
                <div class="product-spec__row">
                    <span class="product-spec__label text-xs-medium"><?= esc_html($label) ?></span>
                    <span class="product-spec__value text-xs-bold"><?= esc_html($wartosc) ?></span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="under-spec">
        <a class="text-xs-bold" href="#opis-produktu">Zobacz Pełen opis</a>
        <a class="text-xs-bold" href="<?= get_field('certyfikat_pdf') ?>" target="_blank">Pobierz certyfikat</a>
    </div>
</div>