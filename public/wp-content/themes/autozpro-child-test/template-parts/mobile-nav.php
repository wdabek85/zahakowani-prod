<?php
/**
 * Mobile Navigation — drill-down style.
 * Built from WooCommerce categories + wp_nav_menu locations.
 * Full-width, slide animation between levels.
 */

$categories = child_get_mega_menu_data();

// Static pages from 'handheld' menu — exclude product category links
$menu_locations = get_nav_menu_locations();
$handheld_items = [];

// Collect category URLs to filter out duplicates
$cat_urls = [];
foreach ($categories as $cat) {
    $cat_urls[] = trailingslashit($cat['url']);
}
// Also match /shop/ since "Sklep" = product archive
$cat_urls[] = trailingslashit(get_permalink(wc_get_page_id('shop')));

if (!empty($menu_locations['handheld'])) {
    $items = wp_get_nav_menu_items($menu_locations['handheld']);
    if ($items) {
        foreach ($items as $item) {
            if ((int) $item->menu_item_parent !== 0) continue;
            // Skip items linking to product categories or shop
            $item_url = trailingslashit($item->url);
            $is_duplicate = false;
            foreach ($cat_urls as $cu) {
                if ($item_url === $cu) {
                    $is_duplicate = true;
                    break;
                }
            }
            if (!$is_duplicate) {
                $handheld_items[] = $item;
            }
        }
    }
}
?>

<div class="mobile-nav-overlay" id="mobile-nav-overlay" aria-hidden="true">
    <div class="mobile-nav-panel">

        <?php // ─── Header ─── ?>
        <div class="mobile-nav-header">
            <span class="mobile-nav-title">Menu</span>
            <button class="mobile-nav-close-btn" aria-label="Zamknij">
                <?php echo get_icon('x-mark', 'icon-lg'); ?>
            </button>
        </div>

        <?php // ─── Slides container ─── ?>
        <div class="mobile-nav-slides">

            <?php // ═══ SLIDE 0: Main menu ═══ ?>
            <div class="mobile-nav-slide is-active" data-level="0" id="mobile-slide-root">
                <ul class="mobile-nav-list">
                    <?php // Product categories ?>
                    <?php foreach ($categories as $cat) : ?>
                        <li>
                            <a href="<?php echo esc_url($cat['url']); ?>"
                               class="mobile-nav-link <?php echo !empty($cat['children']) ? 'has-children' : ''; ?>"
                               <?php if (!empty($cat['children'])) : ?>
                                   data-slide="mobile-slide-cat-<?php echo $cat['term']->term_id; ?>"
                               <?php endif; ?>>
                                <span><?php echo esc_html($cat['term']->name); ?></span>
                                <?php if (!empty($cat['children'])) : ?>
                                    <?php echo get_icon('chevron-right', 'icon-sm'); ?>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <?php // Divider ?>
                    <li class="mobile-nav-divider"></li>

                    <?php // Static pages from handheld menu ?>
                    <?php foreach ($handheld_items as $item) : ?>
                        <li>
                            <a href="<?php echo esc_url($item->url); ?>" class="mobile-nav-link">
                                <span><?php echo esc_html($item->title); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php // ═══ SLIDE 1: Category children (brands) ═══ ?>
            <?php foreach ($categories as $cat) : ?>
                <?php if (empty($cat['children'])) continue; ?>
                <div class="mobile-nav-slide" data-level="1" id="mobile-slide-cat-<?php echo $cat['term']->term_id; ?>">
                    <button class="mobile-nav-back" data-back="mobile-slide-root">
                        <?php echo get_icon('chevron-right', 'icon-sm'); ?>
                        <span>Wstecz</span>
                    </button>
                    <a href="<?php echo esc_url($cat['url']); ?>" class="mobile-nav-slide-title">
                        <?php echo esc_html($cat['term']->name); ?>
                    </a>
                    <ul class="mobile-nav-list">
                        <li>
                            <a href="<?php echo esc_url($cat['url']); ?>" class="mobile-nav-link mobile-nav-link--all">
                                <span>Wszystkie <?php echo esc_html($cat['term']->name); ?></span>
                            </a>
                        </li>
                        <?php foreach ($cat['children'] as $child) : ?>
                            <li>
                                <a href="<?php echo esc_url($child['url']); ?>"
                                   class="mobile-nav-link <?php echo !empty($child['children']) ? 'has-children' : ''; ?>"
                                   <?php if (!empty($child['children'])) : ?>
                                       data-slide="mobile-slide-sub-<?php echo $child['term']->term_id; ?>"
                                   <?php endif; ?>>
                                    <span><?php echo esc_html($child['term']->name); ?></span>
                                    <?php if ($child['term']->count > 0) : ?>
                                        <span class="mobile-nav-count"><?php echo $child['term']->count; ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($child['children'])) : ?>
                                        <?php echo get_icon('chevron-right', 'icon-sm'); ?>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php // ═══ SLIDE 2: Subcategory children (models) ═══ ?>
                <?php foreach ($cat['children'] as $child) : ?>
                    <?php if (empty($child['children'])) continue; ?>
                    <div class="mobile-nav-slide" data-level="2" id="mobile-slide-sub-<?php echo $child['term']->term_id; ?>">
                        <button class="mobile-nav-back" data-back="mobile-slide-cat-<?php echo $cat['term']->term_id; ?>">
                            <?php echo get_icon('chevron-right', 'icon-sm'); ?>
                            <span>Wstecz</span>
                        </button>
                        <a href="<?php echo esc_url($child['url']); ?>" class="mobile-nav-slide-title">
                            <?php echo esc_html($child['term']->name); ?>
                        </a>
                        <ul class="mobile-nav-list">
                            <li>
                                <a href="<?php echo esc_url($child['url']); ?>" class="mobile-nav-link mobile-nav-link--all">
                                    <span>Wszystkie <?php echo esc_html($child['term']->name); ?></span>
                                </a>
                            </li>
                            <?php foreach ($child['children'] as $gc) : ?>
                                <li>
                                    <a href="<?php echo esc_url($gc['url']); ?>"
                                       class="mobile-nav-link <?php echo !empty($gc['children']) ? 'has-children' : ''; ?>"
                                       <?php if (!empty($gc['children'])) : ?>
                                           data-slide="mobile-slide-gc-<?php echo $gc['term']->term_id; ?>"
                                       <?php endif; ?>>
                                        <span><?php echo esc_html($gc['term']->name); ?></span>
                                        <?php if (!empty($gc['children'])) : ?>
                                            <?php echo get_icon('chevron-right', 'icon-sm'); ?>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <?php // ═══ SLIDE 3: Grandchild children (generations) ═══ ?>
                    <?php foreach ($child['children'] as $gc) : ?>
                        <?php if (empty($gc['children'])) continue; ?>
                        <div class="mobile-nav-slide" data-level="3" id="mobile-slide-gc-<?php echo $gc['term']->term_id; ?>">
                            <button class="mobile-nav-back" data-back="mobile-slide-sub-<?php echo $child['term']->term_id; ?>">
                                <?php echo get_icon('chevron-right', 'icon-sm'); ?>
                                <span>Wstecz</span>
                            </button>
                            <a href="<?php echo esc_url($gc['url']); ?>" class="mobile-nav-slide-title">
                                <?php echo esc_html($gc['term']->name); ?>
                            </a>
                            <ul class="mobile-nav-list">
                                <li>
                                    <a href="<?php echo esc_url($gc['url']); ?>" class="mobile-nav-link mobile-nav-link--all">
                                        <span>Wszystkie <?php echo esc_html($gc['term']->name); ?></span>
                                    </a>
                                </li>
                                <?php foreach ($gc['children'] as $ggc) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($ggc)); ?>" class="mobile-nav-link">
                                            <span><?php echo esc_html($ggc->name); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>

                <?php endforeach; ?>
            <?php endforeach; ?>

        </div>
    </div>
</div>
