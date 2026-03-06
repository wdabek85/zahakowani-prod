<?php
/**
 * Sekcja opinii Google — TrustedIndex widget pod trust stats na stronie produktu
 */

defined('ABSPATH') || exit;
?>

<section class="product-reviews">
    <div class="product-reviews__container">
        <h2 class="product-reviews__title">Co o nas mówią klienci</h2>
        <?php echo do_shortcode('[trustindex no-registration=google]'); ?>
    </div>
</section>
