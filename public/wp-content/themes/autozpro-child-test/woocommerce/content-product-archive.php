<?php
/**
 * Karta produktu w archiwum - poziomy layout
 */

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}

// Dane produktu
$marka = get_product_brand();
$badges = get_field('product_badges');
$delivery = get_delivery_message();

// Parametry
$uciag = get_field('uciag');
$nacisk = get_field('nacisk_na_kule_haka');
$homologacja = get_field('homologacja_haka');
$montaz_bez_ciecia = get_field('montaz_bez_ciecia_zderzaka');
$gwarancja = get_field('gwarancja');

$cena = $product->get_price();
$darmowa_dostawa = $cena >= 450;

// Popularny produkt — checkbox ACF (True/False)
$is_popular = get_field('product_popular');
?>

<?php if ($is_popular) : ?>
<div class="product-card__featured-wrap">
    <div class="product-card__featured-bar">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"/></svg>
        <span>Najpopularniejszy Wybór</span>
    </div>
<?php endif; ?>

<li <?php wc_product_class('product-card-horizontal', $product); ?>>
    
    <!-- Środkowa kolumna: Informacje -->
    <div class="product-card__info">
        <div class="product-card__info__header">
            <!-- Badges -->
            <?php if (!empty($badges)) :
                $badge_labels = [
                    'zestaw'       => 'Zestaw',
                    'modul_13pin'  => 'Moduł 13-Pin',
                    'modul_7pin'   => 'Moduł 7-Pin',
                    'wiazka_13pin' => 'Wiązka 13-Pin',
                    'wiazka_7pin'  => 'Wiązka 7-Pin',
                    'bestseller'   => 'Bestseller',
                    'nowość'       => 'Nowość',
                ];
            ?>
                <div class="product-badges product-badges--archive">
                    <?php foreach ($badges as $badge) : ?>
                        <span class="badge badge--<?= esc_attr($badge) ?>">
                            <?= esc_html($badge_labels[$badge] ?? ucfirst(str_replace('_', ' ', $badge))) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Marka z badge autoryzowany -->
            <?php if ($marka) : ?>
                <div class="product-card__brand-box">
                    <!-- Nazwa marki -->
                    <span class="brand-name display-xs-bold"><?= esc_html($marka['name']) ?></span>
                    
                    <?php 
                    $terms = get_the_terms(get_the_ID(), 'product_brand');
                    $term_id = $terms[0]->term_id ?? null;
                    $autoryzowany = $term_id ? get_field('autoryzowany_dystrybutor', 'product_brand_' . $term_id) : false;
                    ?>
                    
                    <?php if ($autoryzowany) : ?>
                        <div class="brand-authorized">
                            <?= get_icon('shield-check', 'icon-xs') ?>
                            <span class="text-xs-regular">Jesteśmy autoryzowanym dystrybutorem marki <?= esc_html($marka['name']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Tytuł -->
            <h2 class="product-card__title">
                <a class="text-xl-semibold" href="<?= esc_url($product->get_permalink()) ?>">
                    <?= esc_html($product->get_name()) ?>
                </a>
            </h2>
        </div>
        
        <div class="product-card__info__inner">
            <!--  Zdjęcie -->
            <div class="product-card__image">
                <a href="<?= esc_url($product->get_permalink()) ?>">
                    <?= $product->get_image('medium', ['loading' => 'lazy', 'decoding' => 'async']) ?>
                </a>
            </div>
            
            <!-- Parametry w okrągłych badge'ach -->
            <div class="product-card__info__inner__parm">
                <div class="product-card__params">
                    <?php if ($uciag) : ?>
                        <div class="param-badge" data-tooltip="Uciąg — maksymalna masa przyczepy z hamulcem, którą hak może ciągnąć.">
                            <?= get_icon('arrow-up-circle', 'icon-sm') ?>
                            <span><?= esc_html($uciag) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($nacisk) : ?>
                        <div class="param-badge" data-tooltip="Nacisk na kulę — dopuszczalne obciążenie pionowe na kuli haka.">
                            <?= get_icon('arrow-down-circle', 'icon-sm') ?>
                            <span><?= esc_html($nacisk) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($homologacja) : ?>
                        <div class="param-badge" data-tooltip="Homologacja — certyfikat potwierdzający zgodność haka z normami bezpieczeństwa.">
                            <?= get_icon('check-badge', 'icon-sm') ?>
                            <span><?= esc_html($homologacja) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($montaz_bez_ciecia) : ?>
                        <div class="param-badge" data-tooltip="Montaż — informacja czy montaż wymaga cięcia lub modyfikacji zderzaka.">
                            <?= get_icon('scissors', 'icon-sm') ?>
                            <span><?= esc_html($montaz_bez_ciecia) ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="param-badge--info" data-tooltip="Parametry techniczne haka holowniczego. Sprawdź pełną specyfikację na stronie produktu.">
                        <?= get_icon('information-circle', 'icon-sm') ?>
                    </div>
                </div>
                
                <!-- Gwarancja -->
                <?php if ($gwarancja) : ?>
                    <div class="product-card__warranty">
                        <?= get_icon('check-badge', 'icon-sm') ?>
                        <span class="text-sm-regular">Gwarancja <strong><?= esc_html($gwarancja) ?></strong></span>
                    </div>
                <?php endif; ?>
                
                <!-- Oceny -->
                <div class="product-card__rating">
                    <?= wc_get_rating_html($product->get_average_rating()) ?>
                    <span class="rating-count text-sm-regular"><?= $product->get_review_count() ?> Opinii</span>
                </div>
            </div>
        </div>        
    </div>
    
    <!-- Prawa kolumna: Buy box -->
    <?php $b2b = azp_b2b_get_product_pricing($product); ?>
    <div class="product-card__buybox">

        <!-- Cena -->
        <div class="product-card__price">
            <span class="price-number display-lg-bold">
                <?= number_format($b2b ? $b2b['b2b_price'] : $product->get_price(), 2, ',', ' ') ?>
            </span>
            <span class="price-currency display-xs-medium">zł/szt.</span>
            <?php if ($b2b) : ?>
                <span class="buy-box__b2b-badge">-<?= $b2b['discount_percent'] ?>%</span>
            <?php endif; ?>
        </div>
        <?php if ($b2b) : ?>
            <p class="buy-box__catalog-price text-sm-regular">
                Cena katalogowa: <span class="buy-box__catalog-price-amount"><?= number_format($b2b['catalog_price'], 2, ',', ' ') ?> zł</span>
            </p>
        <?php endif; ?>

        <p class="price-note text-xs-regular">Cena zawiera 23% VAT, nie obejmuje kosztów dostawy</p>
        
        <!-- Dostawa -->
        <p class="delivery-free text-md-semibold">
            Darmowa Dostawa <strong class="text-accent">od 450zł</strong>
        </p>
        
        <p class="delivery-time text-sm-regular">
            <?= get_icon('clock', 'icon-xs') ?>
            <?= esc_html($delivery['prefix']) ?>
            <strong class="text-accent"><?= esc_html($delivery['strong']) ?></strong>
        </p>
        
        <p class="delivery-time pickup-hover text-sm-regular">
            <?= get_icon('map-pin', 'icon-xs') ?>
            Odbierz do <strong class="text-accent">18:00</strong> w Starogardzie
            <span class="pickup-tooltip">
                <strong>Odbiór osobisty</strong>
                ul. Lubichowska 2c<br>
                83-200 Starogard Gdański<br><br>
                <strong>Godziny otwarcia:</strong><br>
                Pon – Pt: 8:00 – 18:00<br><br>
                <em>Dotyczy zamówień złożonych do 16:00</em>
            </span>
        </p>
        
        <!-- Przycisk -->
        <a href="<?= esc_url($product->get_permalink()) ?>" class="btn btn-primary btn-block text-md-medium">
            Kup Teraz
        </a>
        
        <!-- Telefon -->
        <p class="product-card__phone text-sm-regular">
            Zamów Telefonicznie <a href="tel:+48536731515" class="text-md-bold">+48 536 731 515</a>
        </p>
        
    </div>

</li>

<?php if ($is_popular) : ?>
</div>
<?php endif; ?>