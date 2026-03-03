<?php
/**
 * Baner gwarancji dopasowania — wyciąga nazwę auta z tytułu produktu
 *
 * Tytuły mają wzorzec: "Hak holowniczy + Moduł X-Pin MARKA MODEL ROCZNIK"
 * lub "Hak holowniczy MARKA MODEL ROCZNIK"
 * Wycinamy nazwę auta po ostatnim wystąpieniu słów kluczowych.
 */

defined('ABSPATH') || exit;

if (!has_term('kompletne-haki-holownicze', 'product_cat')) {
    return;
}

$title = get_the_title();

// Usuń prefiks: "Hak holowniczy", potem opcjonalne "+WIĄZKA/WIAZKA/MODUŁ/MODUL/Wiązka/Moduł Xpin/X-Pin"
// Wzorce tytułów:
//   Hak holowniczy MARKA MODEL
//   Hak holowniczy+WIAZKA7pin MARKA MODEL
//   Hak holowniczy+MODUŁ13pin MARKA MODEL
//   Hak holowniczy + Wiązka 7pin MARKA MODEL
//   Hak holowniczy + Moduł 7-Pin MARKA MODEL
$vehicle = preg_replace(
    '/^Hak\s+holowniczy\s*[+]?\s*(?:wi[aą]zk[aę]|modu[lł])\s*\d+\s*-?\s*pin\s*/iu',
    '',
    $title
);

// Jeśli nie było wiązki/modułu, usuń sam prefix "Hak holowniczy"
$vehicle = preg_replace('/^Hak\s+holowniczy\s*/iu', '', $vehicle);
$vehicle = trim($vehicle);

if (empty($vehicle)) {
    $vehicle = $title;
}
?>

<div class="product-guarantee">
    <span class="product-guarantee__badge">WAŻNE</span>

    <div class="product-guarantee__inner">
        <span class="product-guarantee__icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 12l2 2 4-4"/>
                <circle cx="12" cy="12" r="10"/>
            </svg>
        </span>
        <div class="product-guarantee__text">
            <strong>&#10003; Gwarantujemy dopasowanie do Twojego <?php echo esc_html($vehicle); ?></strong>
            <p>Jeśli produkt nie pasuje do Twojego auta – wystarczy go odesłać, a zwrócimy pieniądze. Bez pytań.</p>
        </div>
    </div>
</div>
