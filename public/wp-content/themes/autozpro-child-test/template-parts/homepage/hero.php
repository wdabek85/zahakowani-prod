<?php
/**
 * Homepage — Hero section (v3)
 *
 * Full-width dark gradient background.
 * Left: badge, heading, description, 3 inline proof points.
 * Right: white search card + VIN text below.
 *
 * @package autozpro-child-test
 */
?>
<section class="hp-hero">
    <div class="hp-hero__container">

        <!-- LEWA: treść -->
        <div class="hp-hero__content">
            <span class="hp-hero__badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                Autoryzowany dystrybutor Imioła Hak-Pol
            </span>

            <h1 class="hp-hero__title">
                Haki holownicze<br>
                z <span class="hp-hero__title--accent">gwarancją<br>dopasowania</span>
            </h1>

            <p class="hp-hero__desc">
                Wybierz swoje auto — pokażemy haki które pasują na 100%. Każdy zestaw z gwarancją producenta i potwierdzoną kompatybilnością.
            </p>

            <div class="hp-hero__proofs">
                <div class="hp-hero__proof">
                    <span class="hp-hero__proof-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </span>
                    <div class="hp-hero__proof-text">
                        <strong>Gwarancja producenta</strong>
                        <span>2 lata na każdy hak</span>
                    </div>
                </div>
                <div class="hp-hero__proof">
                    <span class="hp-hero__proof-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5a2 2 0 0 1-2 2h-1"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </span>
                    <div class="hp-hero__proof-text">
                        <strong>Wysyłka w 24h</strong>
                        <span>Darmowa od 450 zł</span>
                    </div>
                </div>
                <div class="hp-hero__proof">
                    <span class="hp-hero__proof-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </span>
                    <div class="hp-hero__proof-text">
                        <strong>Bezpłatna konsultacja</strong>
                        <span>Weryfikacja po VIN</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRAWA: wyszukiwarka -->
        <div class="hp-hero__search-col">
            <div class="hp-hero__search">
                <div class="hp-hero__search-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <strong>Znajdź hak do swojego auta</strong>
                </div>
                <p class="hp-hero__search-sub">3 kroki — 15 sekund</p>
                <?php get_template_part('template-parts/sidebar/vehicle-search'); ?>
            </div>

            <p class="hp-hero__vin-text">
                <a href="#" class="hp-hero__vin-link">Nie znasz modelu?</a>
                Wyślij numer VIN — w 15 min potwierdzimy.
                <a href="tel:+48536731515" class="hp-hero__vin-phone">+48 536 731 515</a>
            </p>
        </div>

    </div>
</section>
