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
 * Get featured product ID for a category.
 * 1. Check ACF field `featured_product` on the term
 * 2. Fallback: bestseller (most total_sales) within category
 */
function child_get_category_featured_product_id($term_id) {
    static $cache = [];
    if (isset($cache[$term_id])) return $cache[$term_id];

    // 1. ACF manual selection
    if (function_exists('get_field')) {
        $manual = get_field('featured_product', 'product_cat_' . $term_id);
        if ($manual && is_numeric($manual)) {
            $cache[$term_id] = (int) $manual;
            return (int) $manual;
        }
    }

    // 2. Fallback: bestseller
    $posts = get_posts([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'orderby'        => 'meta_value_num',
        'meta_key'       => 'total_sales',
        'order'          => 'DESC',
        'tax_query'      => [[
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => $term_id,
            'include_children' => true,
        ]],
        'no_found_rows'    => true,
        'fields'           => 'ids',
    ]);

    $id = !empty($posts) ? (int) $posts[0] : 0;
    $cache[$term_id] = $id;
    return $id;
}

/**
 * Render standalone mega dropdown for "Kompletne Haki" nav item.
 * 3-column layout:
 *  - col 1: Brands (level 2)
 *  - col 2: Models + generations (level 3 + 4, shown on brand hover)
 *  - col 3: Representative product card for active brand/category
 */
function child_render_haki_mega_dropdown() {
    $categories = child_get_mega_menu_data();

    $haki = null;
    foreach ($categories as $cat) {
        if (child_is_rich_category($cat)) {
            $haki = $cat;
            break;
        }
    }

    if (!$haki) return;

    $brands = $haki['children'];
    if (empty($brands)) return;

    // Default "featured" product ID = for whole haki category
    $default_featured_id = child_get_category_featured_product_id($haki['term']->term_id);
    ?>
    <div class="nav-mega-dropdown" id="nav-mega-haki">
        <div class="nav-mega-3col">

            <?php // ─── COL 1: Brands ─── ?>
            <nav class="nav-mega-brands" aria-label="Marki">
                <div class="nav-mega-col-heading">Marka</div>
                <ul>
                    <?php foreach ($brands as $i => $brand) :
                        $brand_featured_id = child_get_category_featured_product_id($brand['term']->term_id);
                    ?>
                        <li>
                            <a href="<?php echo esc_url($brand['url']); ?>"
                               class="nav-mega-brand<?php echo $i === 0 ? ' is-active' : ''; ?>"
                               data-brand-id="brand-<?php echo $brand['term']->term_id; ?>"
                               data-featured-id="<?php echo (int) $brand_featured_id; ?>">
                                <span><?php echo esc_html($brand['term']->name); ?></span>
                                <?php echo get_icon('chevron-right', 'icon-xs'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <?php // ─── COL 2: Models + generations (per brand) ─── ?>
            <div class="nav-mega-models">
                <?php foreach ($brands as $i => $brand) : ?>
                    <div class="nav-mega-models-panel<?php echo $i === 0 ? ' is-active' : ''; ?>"
                         data-brand-panel="brand-<?php echo $brand['term']->term_id; ?>">
                        <div class="nav-mega-col-heading">Model</div>
                        <?php if (!empty($brand['children'])) : ?>
                            <ul class="nav-mega-models-list">
                                <?php foreach ($brand['children'] as $model) : ?>
                                    <li>
                                        <a href="<?php echo esc_url($model['url']); ?>" class="nav-mega-model">
                                            <?php echo esc_html($model['term']->name); ?>
                                            <?php if ($model['term']->count > 0) : ?>
                                                <span class="nav-mega-count"><?php echo $model['term']->count; ?></span>
                                            <?php endif; ?>
                                        </a>
                                        <?php if (!empty($model['children'])) : ?>
                                            <ul class="nav-mega-generations">
                                                <?php foreach ($model['children'] as $gen) : ?>
                                                    <li>
                                                        <a href="<?php echo esc_url(get_term_link($gen)); ?>">
                                                            <?php echo esc_html($gen->name); ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p class="nav-mega-empty">Brak modeli</p>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($brand['url']); ?>" class="nav-mega-models-cta">
                            Zobacz wszystkie haki <?php echo esc_html($brand['term']->name); ?>
                            <?php echo get_icon('chevron-right', 'icon-xs'); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php // ─── COL 3: Featured product card (pre-rendered per brand) ─── ?>
            <div class="nav-mega-card" id="nav-mega-card">
                <div class="nav-mega-col-heading">Polecany</div>
                <div class="nav-mega-cards-wrap" data-default-card="card-<?php echo (int) $default_featured_id; ?>">
                    <?php
                    // Collect unique featured product IDs (default + each brand)
                    $featured_ids = [$default_featured_id => 'default'];
                    foreach ($brands as $brand) {
                        $fid = child_get_category_featured_product_id($brand['term']->term_id);
                        if ($fid && !isset($featured_ids[$fid])) {
                            $featured_ids[$fid] = 'brand-' . $brand['term']->term_id;
                        }
                    }
                    // Map brand_id → featured_product_id for JS
                    $brand_to_card = ['default' => $default_featured_id];
                    foreach ($brands as $brand) {
                        $brand_to_card['brand-' . $brand['term']->term_id] = child_get_category_featured_product_id($brand['term']->term_id);
                    }

                    // Render one card per unique featured product
                    foreach ($featured_ids as $fid => $_key) {
                        if (!$fid) continue;
                        $product_obj = wc_get_product($fid);
                        if (!$product_obj || !$product_obj->is_visible()) continue;
                        ?>
                        <div class="nav-mega-card-item" data-card-for="card-<?php echo (int) $fid; ?>">
                            <?php
                            $GLOBALS['product'] = $product_obj;
                            setup_postdata($fid);
                            get_template_part('template-parts/product/card-vertical');
                            wp_reset_postdata();
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>

        <div class="nav-mega-footer">
            <a href="<?php echo esc_url($haki['url']); ?>" class="mega-simple-cta">
                Zobacz wszystkie <?php echo esc_html($haki['term']->name); ?>
                <?php echo get_icon('chevron-right', 'icon-xs'); ?>
            </a>
        </div>
    </div>
    <?php
}
