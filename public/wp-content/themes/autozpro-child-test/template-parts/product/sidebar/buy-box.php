<?php
/**
 * Buy box - cena, dostawa, przycisk zakupu
 */

defined('ABSPATH') || exit;

global $product;

$delivery = get_delivery_message();

// Informacje o dostawie - możesz to później przenieść do ACF
$dostawa_info_prefix = 'Darmowa Dostawa';
$dostawa_info_highlight = 'od 450zł';
$dostawa_czas_2_prefix = 'Odbierz do';
$dostawa_czas_2_highlight = '18:00 w Starogardzie';
$telefon = '+48 536 731 515';
?>

<div class="product-buy-box">
    
    <!-- Cena -->
    <div class="buy-box__price">
        <div class="buy-box__price-amount">
            <span class="price-number display-lg-bold">
                <?= number_format($product->get_price(), 2, ',', ' ') ?>
            </span>
            <span class="price-currency display-xs-medium">
                <?= get_woocommerce_currency_symbol() ?>
            </span>
        </div>
        <p class="buy-box__price-note text-xs-regular">
            Cena zawiera 23% VAT, nie obejmuje kosztów dostawy
        </p>
        <?php do_action( 'iworks_omnibus_wc_lowest_price_message', $product->get_id() ); ?>
    </div>

    <!-- Raty Przelewy24 -->
    <?php if ( class_exists( 'WC_P24\Installments\Installments' ) && WC_P24\Installments\Installments::is_enabled() ) : ?>
        <div class="buy-box__installments">
            <p24-installment
                id="p24_installments"
                show-modal="<?= WC_P24\Installments\Installments::show_simulator() ? 'true' : 'false' ?>"
                type="<?= esc_attr( WC_P24\Installments\Installments::get_type_of_widget() ) ?>"
            ></p24-installment>
        </div>
    <?php endif; ?>

    <!-- Informacje o dostawie -->
    <div class="buy-box__delivery">
        <p class="buy-box__delivery-free text-md-semibold">
            <?= esc_html($dostawa_info_prefix) ?> 
            <strong class="text-accent"><?= esc_html($dostawa_info_highlight) ?></strong>
        </p>
        <p class="buy-box__delivery-time text-sm-regular">
            <?= get_icon('clock', 'icon-sm') ?>
            <?= esc_html($delivery['prefix']) ?>
            <strong class="text-accent"><?= esc_html($delivery['strong']) ?></strong>
        </p>
        <p class="buy-box__delivery-time buy-box__pickup text-sm-regular">
            <?= get_icon('map-pin', 'icon-sm') ?>
            <?= esc_html($dostawa_czas_2_prefix) ?>
            <strong class="text-accent"><?= esc_html($dostawa_czas_2_highlight) ?></strong>
            <span class="pickup-tooltip">
                <strong>Odbiór osobisty</strong>
                ul. Lubichowska 2c<br>
                83-200 Starogard Gdański<br><br>
                <strong>Godziny otwarcia:</strong><br>
                Pon – Pt: 8:00 – 18:00<br><br>
                <em>Dotyczy zamówień złożonych do 16:00</em>
            </span>
        </p>
    </div>

    <!-- Przycisk -->
    <div class="buy-box__actions">
        <form class="cart" method="post" enctype="multipart/form-data">
            <?php do_action('woocommerce_before_add_to_cart_button'); ?> 
    
            <input type="hidden" name="quantity" value="1" />

            <button type="submit" name="add-to-cart" value="<?= esc_attr($product->get_id()) ?>" class="btn btn-primary btn-add-to-cart">
                Kup Teraz
            </button>

        </form>
    </div>

    <!-- Telefon -->
    <div class="buy-box__phone">
        <p class="text-sm-bold">Zamów Telefonicznie</p>
        <a href="tel:<?= esc_attr(str_replace(' ', '', $telefon)) ?>" class="text-lg-bold">
            <?= esc_html($telefon) ?>
        </a>
    </div>

</div>