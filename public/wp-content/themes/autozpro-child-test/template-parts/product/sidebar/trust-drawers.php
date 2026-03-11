<?php
/**
 * Trust Drawers — panele wysuwane z prawej strony
 * Każdy klocek trust-icon otwiera odpowiedni drawer
 */

defined('ABSPATH') || exit;
?>

<!-- Overlay -->
<div class="trust-drawer-overlay" id="trust-drawer-overlay"></div>

<!-- Drawer: Zwrot -->
<div class="trust-drawer" id="trust-drawer-zwrot" role="dialog" aria-label="Informacje o zwrocie">
    <div class="trust-drawer__header">
        <div class="trust-drawer__title">
            <?= get_icon('calendar', 'icon-md') ?>
            <span>Zwrot towaru</span>
        </div>
        <button type="button" class="trust-drawer__close" aria-label="Zamknij"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="trust-drawer__content">
        <h3>31 dni na zwrot bez podania przyczyny</h3>
        <p>Masz pełne 31 dni od daty otrzymania przesyłki na zwrot zakupionego towaru — bez podawania przyczyny.</p>

        <h4>Jak zwrócić produkt?</h4>
        <ol>
            <li>Wypełnij formularz zwrotu dostępny na stronie <strong>Moje Konto → Zamówienia</strong></li>
            <li>Nadaj przesyłkę na adres podany w formularzu</li>
        </ol>

        <h4>Ważne informacje</h4>
        <ul>
            <li>Zwrot pieniędzy w ciągu <strong>14 dni roboczych</strong> od otrzymania przesyłki</li>
            <li>Koszt przesyłki zwrotnej pokrywa kupujący</li>
        </ul>

        <p class="trust-drawer__note">Potrzebujesz pomocy? Zadzwoń: <a href="tel:+48536731515"><strong>+48 536 731 515</strong></a></p>
    </div>
</div>

<!-- Drawer: Płatności -->
<div class="trust-drawer" id="trust-drawer-platnosci" role="dialog" aria-label="Informacje o płatnościach">
    <div class="trust-drawer__header">
        <div class="trust-drawer__title">
            <?= get_icon('shield-check', 'icon-md') ?>
            <span>Bezpieczne płatności</span>
        </div>
        <button type="button" class="trust-drawer__close" aria-label="Zamknij"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="trust-drawer__content">
        <h3>Twoje dane są bezpieczne</h3>
        <p>Wszystkie transakcje są szyfrowane protokołem SSL. Współpracujemy z zaufanymi operatorami płatności.</p>

        <h4>Dostępne metody płatności</h4>
        <ul>
            <li><strong>Przelew online</strong> — szybkie przelewy przez Przelewy24</li>
            <li><strong>BLIK</strong> — płatność kodem BLIK</li>
            <li><strong>Karta płatnicza</strong> — Visa, Mastercard</li>
            <li><strong>Przelew tradycyjny</strong> — na konto bankowe</li>
            <li><strong>Pobranie</strong> — płatność przy odbiorze</li>
        </ul>

        <h4>Gwarancja bezpieczeństwa</h4>
        <ul>
            <li>Certyfikat SSL — szyfrowane połączenie</li>
            <li>Dane karty nigdy nie trafiają na nasz serwer</li>
            <li>Operator płatności: Przelewy24</li>
        </ul>

        <p class="trust-drawer__ext-link">
            <?= get_icon('shield-check', 'icon-sm') ?>
            <a href="https://www.przelewy24.pl/bezpieczenstwo" target="_blank" rel="noopener noreferrer">Dowiedz się więcej o bezpieczeństwie Przelewy24</a>
        </p>
    </div>
</div>

<!-- Drawer: Wysyłka -->
<div class="trust-drawer" id="trust-drawer-wysylka" role="dialog" aria-label="Informacje o wysyłce">
    <div class="trust-drawer__header">
        <div class="trust-drawer__title">
            <?= get_icon('truck', 'icon-md') ?>
            <span>Dostawa</span>
        </div>
        <button type="button" class="trust-drawer__close" aria-label="Zamknij"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="trust-drawer__content">
        <h3>Wysyłka w 24h</h3>
        <p>Zamówienia złożone do godziny 14:00 w dni robocze wysyłamy tego samego dnia.</p>

        <table class="trust-drawer__table">
            <thead>
                <tr>
                    <th>Metoda dostawy</th>
                    <th>Koszt</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Kurier DPD</strong>
                        <small>1-2 dni robocze</small>
                    </td>
                    <td>13,99 zł</td>
                </tr>
                <tr>
                    <td>
                        <strong>Kurier DHL</strong>
                        <small>1-2 dni robocze</small>
                    </td>
                    <td>14,99 zł</td>
                </tr>
                <tr>
                    <td>
                        <strong>InPost Paczkomat</strong>
                        <small>1-2 dni robocze</small>
                    </td>
                    <td>12,99 zł</td>
                </tr>
                <tr>
                    <td>
                        <strong>Odbiór osobisty</strong>
                        <small>Starogard Gdański</small>
                    </td>
                    <td>0,00 zł</td>
                </tr>
            </tbody>
        </table>

        <p><strong>Darmowa dostawa od 450 zł!</strong></p>

        <p class="trust-drawer__note">Potrzebujesz więcej informacji? Zadzwoń: <a href="tel:+48536731515"><strong>+48 536 731 515</strong></a></p>
    </div>
</div>
