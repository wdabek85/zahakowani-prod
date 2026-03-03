<?php
/**
 * Prawa kolumna - sidebar produktu
 */

defined('ABSPATH') || exit;
?>

<div class="product-sidebar">
    <?php
    get_template_part('template-parts/product/sidebar/promo-banner');
    get_template_part('template-parts/product/product-brand-logo');
    get_template_part('template-parts/product/sidebar/buy-box');
    get_template_part('template-parts/product/sidebar/trust-icons');
    ?>
</div>