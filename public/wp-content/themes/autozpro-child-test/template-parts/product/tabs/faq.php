<?php
/**
 * Zakładka: FAQ
 */

defined('ABSPATH') || exit;

$nazwa = get_field('nazwa');
$faq = get_field('faq_field');

if (empty($faq)) return;
?>

<section id="faq" class="product-tab-section">
    <div class="container">
        <div class="tab-section__header">
            <?= get_icon('question-mark-circle', 'icon-lg') ?>
            <h2 class="tab-section__title text-xxl-bold"><?= esc_html($nazwa ?: 'FAQ') ?></h2>
        </div>
        
        <div class="tab-section__content">
            <div class="faq-list">
                <?php foreach ($faq as $item) : ?>
                    <div class="faq-item">
                        <h3 class="faq-question text-md-bold">
                            <?= esc_html($item['pytanie']) ?>
                        </h3>
                        <div class="faq-answer text-md-regular">
                            <?= wp_kses_post($item['odpowiedz']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
</section>