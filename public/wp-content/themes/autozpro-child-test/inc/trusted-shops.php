<?php
/**
 * Trusted Shops Integration
 *
 * Ładuje Trustbadge (widgets.trustedshops.com) na wszystkich stronach,
 * ładuje widget.js (etrusted) do widgetów opinii,
 * przekazuje dane zamówienia na stronie thank-you.
 */

defined( 'ABSPATH' ) || exit;

define( 'AZP_TS_ID', 'X2DDFEBBA43E805653ACA085E040F2B27' );

/**
 * 1. Trustbadge — główny skrypt (badge w rogu strony).
 *    Źródło: widgets.trustedshops.com/js/{TS-ID}.js
 */
add_action( 'wp_footer', function () {
    ?>
    <!-- Trusted Shops Trustbadge -->
    <script async
        data-desktop-y-offset="0"
        data-mobile-y-offset="0"
        data-desktop-disable-reviews="false"
        data-desktop-enable-custom="false"
        data-desktop-position="right"
        data-desktop-custom-width="156"
        data-desktop-enable-fadeout="false"
        data-disable-mobile="false"
        data-disable-trustbadge="false"
        data-mobile-custom-width="156"
        data-mobile-disable-reviews="false"
        data-mobile-enable-custom="false"
        data-mobile-position="left"
        data-mobile-enable-topbar="false"
        data-mobile-enable-fadeout="true"
        charset="UTF-8"
        src="//widgets.trustedshops.com/js/<?php echo esc_attr( AZP_TS_ID ); ?>.js">
    </script>
    <!-- End Trusted Shops Trustbadge -->
    <?php
}, 99 );

/**
 * 2. Widget.js (etrusted) — potrzebny do widgetów opinii.
 *    Ładowany async defer, nie blokuje renderowania.
 */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'etrusted-widgets',
        'https://integrations.etrusted.com/applications/widget.js/v2',
        [],
        null,
        true
    );
} );

add_filter( 'script_loader_tag', function ( $tag, $handle ) {
    if ( $handle === 'etrusted-widgets' ) {
        $tag = str_replace( ' src', ' async defer src', $tag );
    }
    return $tag;
}, 10, 2 );

/**
 * 3. Dane zamówienia na stronie thank-you (z produktami dla recenzji).
 *    Wstrzykiwane hookiem — nie wymaga override thankyou.php.
 */
add_action( 'woocommerce_thankyou', function ( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }
    ?>
    <!-- Trusted Shops Checkout Integration -->
    <div id="trustedShopsCheckout" style="display: none;">
        <span id="tsCheckoutOrderNr"><?php echo esc_html( $order->get_order_number() ); ?></span>
        <span id="tsCheckoutBuyerEmail"><?php echo esc_html( $order->get_billing_email() ); ?></span>
        <span id="tsCheckoutOrderAmount"><?php echo esc_html( $order->get_total() ); ?></span>
        <span id="tsCheckoutOrderCurrency"><?php echo esc_html( $order->get_currency() ); ?></span>
        <span id="tsCheckoutOrderPaymentType"><?php echo esc_html( $order->get_payment_method_title() ); ?></span>
        <?php foreach ( $order->get_items() as $item ) :
            $product = $item->get_product();
            if ( ! $product ) continue;
        ?>
        <span class="tsCheckoutProductItem">
            <span class="tsCheckoutProductUrl"><?php echo esc_url( get_permalink( $product->get_id() ) ); ?></span>
            <span class="tsCheckoutProductImageUrl"><?php echo esc_url( wp_get_attachment_url( $product->get_image_id() ) ); ?></span>
            <span class="tsCheckoutProductName"><?php echo esc_html( $item->get_name() ); ?></span>
            <span class="tsCheckoutProductSKU"><?php echo esc_html( $product->get_sku() ); ?></span>
            <span class="tsCheckoutProductGTIN"></span>
        </span>
        <?php endforeach; ?>
    </div>
    <!-- End Trusted Shops Checkout Integration -->
    <?php
}, 20 );
