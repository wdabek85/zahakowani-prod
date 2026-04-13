        </div><!-- .col-full -->
    </div><!-- #content -->

    <?php do_action('autozpro_before_footer'); ?>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="footer-main">
            <div class="footer-inner">

                <?php // ─── Kolumna 1: Logo + opis ─── ?>
                <div class="footer-col footer-col--brand">
                    <?php
                    $logo_img = home_url('/wp-content/uploads/2022/04/Logo-white-zahakowani.png');
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                        <img src="<?php echo esc_url($logo_img); ?>" alt="<?php bloginfo('name'); ?>" width="180">
                    </a>
                    <p class="footer-desc">Oferujemy haki holownicze do wszystkich aut. Działamy na terenie Polski i za granicą, wspierając serwisy, warsztaty i klientów indywidualnych.</p>
                </div>

                <?php // ─── Kolumna 2: Kontakt ─── ?>
                <div class="footer-col footer-col--contact">
                    <h4 class="footer-heading">Kontakt</h4>
                    <ul class="footer-list">
                        <li>
                            <span class="footer-label">Telefon</span>
                            <a href="tel:+48536731515">(+48) 536 731 515</a>
                        </li>
                        <li>
                            <span class="footer-label">Email</span>
                            <a href="mailto:kontakt@zahakowani.pl">kontakt@zahakowani.pl</a>
                        </li>
                        <li>
                            <span class="footer-label">Godziny pracy</span>
                            <span>Pon – Pt: 6:00 – 18:00</span>
                        </li>
                        <li>
                            <span class="footer-label">Adres</span>
                            <span>ul. Dworcowa 35<br>83-240 Lubichowo</span>
                        </li>
                    </ul>
                </div>

                <?php // ─── Kolumna 3: Obsługa klienta ─── ?>
                <div class="footer-col">
                    <h4 class="footer-heading">Obsługa klienta</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>">Moje konto</a></li>
                        <li><a href="<?php echo esc_url(home_url('/moje-konto/orders/')); ?>">Zamówienia</a></li>
                        <li><a href="<?php echo esc_url(home_url('/zwroty/')); ?>">Zwroty</a></li>
                        <li><a href="<?php echo esc_url(home_url('/gwarancja-i-reklamacje/')); ?>">Gwarancja i Reklamacje</a></li>
                    </ul>
                </div>

                <?php // ─── Kolumna 4: O nas + Katalog ─── ?>
                <div class="footer-col">
                    <h4 class="footer-heading">O nas</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/o-nas/')); ?>">Informacje o firmie</a></li>
                        <li><a href="<?php echo esc_url(home_url('/polityka-prywatnosci/')); ?>">Polityka prywatności</a></li>
                        <li><a href="<?php echo esc_url(home_url('/regulamin/')); ?>">Regulamin</a></li>
                    </ul>

                    <h4 class="footer-heading footer-heading--mt">Katalog</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/shop/')); ?>">Cały Asortyment</a></li>
                        <li><a href="<?php echo esc_url(home_url('/kategoria/kompletne-haki-holownicze/')); ?>">Haki Holownicze</a></li>
                        <li><a href="<?php echo esc_url(home_url('/kategoria/akcesoria-samochodowe/')); ?>">Akcesoria</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <?php // ─── Copyright ─── ?>
        <div class="footer-bottom">
            <div class="footer-bottom-inner">
                <span class="footer-copyright">Copyright &copy; <?php echo date('Y'); ?> Zahakowani. Wszelkie prawa zastrzeżone.</span>
                <span class="footer-credit">Sklep stworzony przez: <a href="https://atrivo.pl/" target="_blank" rel="noopener">atrivo</a></span>
            </div>
        </div>
    </footer>

    <?php do_action('autozpro_after_footer'); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
