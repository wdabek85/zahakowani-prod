<?php
/**
 * Zakładka: Certyfikaty
 */

defined('ABSPATH') || exit;

$certyfikaty = array_merge(
    get_catalog_certyfikaty(),
    get_field('certyfikaty') ?: []
);
if (empty($certyfikaty)) return;

// Mapowanie typów na miniaturki
$cert_images = [
    'e20_hak'   => get_stylesheet_directory_uri() . '/assets/images/certificates/e20-hak.png',
    'e20_modul' => get_stylesheet_directory_uri() . '/assets/images/certificates/e20-modul.png',
    'pja'       => get_stylesheet_directory_uri() . '/assets/images/certificates/pja.png',
];
?>

<section id="certyfikaty" class="product-tab-section">
    <div class="container">
        <div class="tab-section__header">
            <?= get_icon('document-text', 'icon-lg') ?>
            <h2 class="tab-section__title text-xxl-bold">Certyfikaty</h2>
        </div>
        
        <div class="tab-section__content">
            <div class="certyfikaty-grid">
                <?php foreach ($certyfikaty as $cert) : 
                    $image = $cert_images[$cert['typ_certyfikatu']] ?? '';
                    
                    // Priorytet: PDF > link_info
                    $cert_url = !empty($cert['plik_pdf']) 
                        ? $cert['plik_pdf']['url'] 
                        : ($cert['link_info'] ?? '');
                    
                    $is_download = !empty($cert['plik_pdf']);
                ?>
                    <div class="cert-card">
                        
                        <!-- Miniaturka - klikalna -->
                        <?php if ($image && $cert_url) : ?>
                            <a href="<?= esc_url($cert_url) ?>" 
                               <?= $is_download ? 'download' : 'target="_blank"' ?> 
                               class="cert-card__image">
                                <img src="<?= esc_url($image) ?>" alt="<?= esc_attr($cert['tytul']) ?>">
                            </a>
                        <?php elseif ($image) : ?>
                            <div class="cert-card__image">
                                <img src="<?= esc_url($image) ?>" alt="<?= esc_attr($cert['tytul']) ?>">
                            </div>
                        <?php endif; ?>
                        
                        <!-- Tytuł -->
                        <h3 class="cert-card__title text-md-semibold">
                            <?= esc_html($cert['tytul']) ?>
                        </h3>
                        
                        <!-- Przycisk Pobierz / Zobacz -->
                        <?php if ($cert_url) : ?>
                            <a href="<?= esc_url($cert_url) ?>" 
                               <?= $is_download ? 'download' : 'target="_blank"' ?> 
                               class="cert-download text-sm-bold">
                                <?= get_icon('arrow-down-tray', 'icon-sm') ?>
                                <?= $is_download ? 'Pobierz' : 'Pobierz' ?>
                            </a>
                        <?php endif; ?>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>