<?php
$marka = get_product_brand();
if (!$marka) return;
?>

<span class="product-brand__name display-xs-bold"><?= esc_html($marka['name']) ?></span>