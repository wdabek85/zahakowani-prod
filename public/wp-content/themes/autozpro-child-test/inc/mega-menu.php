<?php
/**
 * Automatic Mega Menu — built from WooCommerce product_cat taxonomy.
 * No manual menu management needed — categories/subcategories appear automatically.
 *
 * Two panel types:
 *  - "rich" (many subcategories, e.g. Kompletne Haki) → grid of brands + models
 *  - "simple" (few/no subcategories, e.g. Bagażniki) → description + image + CTA
 */

/**
 * Get top-level product categories with their children (3 levels deep).
 */
function child_get_mega_menu_data() {
    static $data = null;
    if ($data !== null) return $data;

    $data = [];

    $parents = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => 0,
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
    ]);

    if (is_wp_error($parents) || empty($parents)) return $data;

    $exclude_slugs = ['uncategorized', 'bez-kategorii'];

    foreach ($parents as $parent) {
        if (in_array($parent->slug, $exclude_slugs, true)) continue;

        $children = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $parent->term_id,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ]);

        $child_data = [];
        if (!is_wp_error($children)) {
            foreach ($children as $child) {
                $grandchildren = get_terms([
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                    'parent'     => $child->term_id,
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                ]);

                // Level 3 → level 4 (e.g. model → generation)
                $gc_data = [];
                if (!is_wp_error($grandchildren)) {
                    foreach ($grandchildren as $gc) {
                        $greatgrandchildren = get_terms([
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => false,
                            'parent'     => $gc->term_id,
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                        ]);

                        $gc_data[] = [
                            'term'     => $gc,
                            'url'      => get_term_link($gc),
                            'children' => is_wp_error($greatgrandchildren) ? [] : $greatgrandchildren,
                        ];
                    }
                }

                $child_data[] = [
                    'term'     => $child,
                    'url'      => get_term_link($child),
                    'children' => $gc_data,
                ];
            }
        }

        $data[] = [
            'term'     => $parent,
            'url'      => get_term_link($parent),
            'children' => $child_data,
        ];
    }

    return $data;
}

/**
 * Is this a "rich" category (many subcategories with grandchildren)?
 */
function child_is_rich_category($category_data) {
    if (count($category_data['children']) < 3) return false;
    foreach ($category_data['children'] as $child) {
        if (!empty($child['children'])) return true;
    }
    return false;
}

/**
 * Render a "rich" panel — grid of brands → models → generations.
 * Supports 4 levels: Category → Brand → Model → Generation
 */
function child_render_rich_panel($category_data) {
    $children = $category_data['children'];
    $count = count($children);
    $cols = min($count, 4);
    ?>
    <div class="mega-panel mega-panel--rich" data-cols="<?php echo $cols; ?>">
        <?php foreach ($children as $child) : ?>
            <div class="mega-column">
                <?php // Level 2: Brand header ?>
                <a href="<?php echo esc_url($child['url']); ?>" class="mega-column-title">
                    <?php echo esc_html($child['term']->name); ?>
                    <?php if ($child['term']->count > 0) : ?>
                        <span class="mega-count"><?php echo $child['term']->count; ?></span>
                    <?php endif; ?>
                </a>
                <?php if (!empty($child['children'])) : ?>
                    <ul class="mega-links">
                        <?php foreach ($child['children'] as $gc) : ?>
                            <li class="<?php echo !empty($gc['children']) ? 'has-sublinks' : ''; ?>">
                                <?php // Level 3: Model ?>
                                <a href="<?php echo esc_url($gc['url']); ?>" class="mega-link-model">
                                    <?php echo esc_html($gc['term']->name); ?>
                                </a>
                                <?php // Level 4: Generations ?>
                                <?php if (!empty($gc['children'])) : ?>
                                    <ul class="mega-sublinks">
                                        <?php foreach ($gc['children'] as $ggc) : ?>
                                            <li>
                                                <a href="<?php echo esc_url(get_term_link($ggc)); ?>">
                                                    <?php echo esc_html($ggc->name); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Render a "simple" panel — description + thumbnail + CTA (for Bagażniki, Kule, etc.)
 */
function child_render_simple_panel($category_data) {
    $term = $category_data['term'];
    $desc = term_description($term->term_id, 'product_cat');
    $thumb_id = get_term_meta($term->term_id, 'thumbnail_id', true);
    $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : '';
    $children = $category_data['children'];
    ?>
    <div class="mega-panel mega-panel--simple">
        <div class="mega-simple-content">
            <h3 class="mega-simple-title"><?php echo esc_html($term->name); ?></h3>
            <?php if ($desc) : ?>
                <div class="mega-simple-desc"><?php echo wp_kses_post($desc); ?></div>
            <?php endif; ?>

            <?php if (!empty($children)) : ?>
                <ul class="mega-simple-links">
                    <?php foreach ($children as $child) : ?>
                        <li>
                            <a href="<?php echo esc_url($child['url']); ?>">
                                <?php echo esc_html($child['term']->name); ?>
                                <?php if ($child['term']->count > 0) : ?>
                                    <span class="mega-count"><?php echo $child['term']->count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <a href="<?php echo esc_url($category_data['url']); ?>" class="mega-simple-cta">
                Zobacz wszystkie
                <?php echo get_icon('chevron-right', 'icon-xs'); ?>
            </a>
        </div>
        <?php if ($thumb_url) : ?>
            <div class="mega-simple-image">
                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($term->name); ?>">
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render appropriate panel based on category type.
 */
function child_render_mega_panel($category_data) {
    if (child_is_rich_category($category_data)) {
        child_render_rich_panel($category_data);
    } else {
        child_render_simple_panel($category_data);
    }
}

/**
 * Render the full mega menu dropdown (Produkty button).
 */
function child_render_mega_menu() {
    $categories = child_get_mega_menu_data();
    if (empty($categories)) return;
    ?>
    <div class="mega-menu" id="mega-menu">
        <div class="mega-menu-inner">
            <nav class="mega-sidebar">
                <?php foreach ($categories as $i => $cat) : ?>
                    <a href="<?php echo esc_url($cat['url']); ?>"
                       class="mega-sidebar-item<?php echo $i === 0 ? ' is-active' : ''; ?>"
                       data-panel="mega-panel-<?php echo $cat['term']->term_id; ?>">
                        <span class="mega-sidebar-name"><?php echo esc_html($cat['term']->name); ?></span>
                        <?php echo get_icon('chevron-right', 'icon-xs'); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="mega-content">
                <?php foreach ($categories as $i => $cat) : ?>
                    <div class="mega-content-panel<?php echo $i === 0 ? ' is-active' : ''; ?>"
                         id="mega-panel-<?php echo $cat['term']->term_id; ?>">
                        <?php child_render_mega_panel($cat); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render standalone mega dropdown for "Kompletne Haki" nav item.
 * Shows only the rich panel (brands → models).
 */
function child_render_haki_mega_dropdown() {
    $categories = child_get_mega_menu_data();

    // Find "Kompletne Haki" category
    $haki = null;
    foreach ($categories as $cat) {
        if (child_is_rich_category($cat)) {
            $haki = $cat;
            break;
        }
    }

    if (!$haki) return;
    ?>
    <div class="nav-mega-dropdown" id="nav-mega-haki">
        <div class="nav-mega-inner">
            <?php child_render_rich_panel($haki); ?>
            <div class="nav-mega-footer">
                <a href="<?php echo esc_url($haki['url']); ?>" class="mega-simple-cta">
                    Zobacz wszystkie <?php echo esc_html($haki['term']->name); ?>
                    <?php echo get_icon('chevron-right', 'icon-xs'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}
