<?php
/**
 * Category Filter — replaces widget_product_categories.
 *
 * Desktop: accordion with auto-expanded current category path.
 * Mobile: drill-down slides (like mega menu).
 *
 * Uses child_get_mega_menu_data() from mega-menu.php.
 */

$categories = child_get_mega_menu_data();
if (empty($categories)) return;

// Find current category trail for auto-expand
$current_term = get_queried_object();
$current_id = ($current_term instanceof WP_Term) ? $current_term->term_id : 0;
$ancestor_ids = $current_id ? get_ancestors($current_id, 'product_cat', 'taxonomy') : [];
$active_ids = $ancestor_ids;
$active_ids[] = $current_id;
?>

<?php // ═══════ DESKTOP: Accordion ═══════ ?>
<div class="cat-filter">
    <span class="cat-filter__title">Kategorie</span>
    <ul class="cat-filter__list">
        <?php foreach ($categories as $cat) :
            $is_active = in_array($cat['term']->term_id, $active_ids, true);
            $is_current = ($cat['term']->term_id === $current_id);
            $has_children = !empty($cat['children']);
        ?>
            <li class="cat-filter__item <?php echo $has_children ? 'has-children' : ''; ?> <?php echo $is_active ? 'is-expanded' : ''; ?>">
                <div class="cat-filter__row">
                    <a href="<?php echo esc_url($cat['url']); ?>"
                       class="cat-filter__link <?php echo $is_current ? 'is-current' : ''; ?>">
                        <?php echo esc_html($cat['term']->name); ?>
                        <?php if ($cat['term']->count > 0) : ?>
                            <span class="cat-filter__count"><?php echo $cat['term']->count; ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if ($has_children) : ?>
                        <button class="cat-filter__toggle" aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>" aria-label="Rozwiń">
                            <?php echo get_icon('chevron-down', 'icon-xs'); ?>
                        </button>
                    <?php endif; ?>
                </div>

                <?php if ($has_children) : ?>
                    <ul class="cat-filter__children" <?php echo $is_active ? '' : 'style="display:none"'; ?>>
                        <?php foreach ($cat['children'] as $child) :
                            $child_active = in_array($child['term']->term_id, $active_ids, true);
                            $child_current = ($child['term']->term_id === $current_id);
                            $child_has_kids = !empty($child['children']);
                        ?>
                            <li class="cat-filter__item <?php echo $child_has_kids ? 'has-children' : ''; ?> <?php echo $child_active ? 'is-expanded' : ''; ?>">
                                <div class="cat-filter__row">
                                    <a href="<?php echo esc_url($child['url']); ?>"
                                       class="cat-filter__link <?php echo $child_current ? 'is-current' : ''; ?>">
                                        <?php echo esc_html($child['term']->name); ?>
                                        <?php if ($child['term']->count > 0) : ?>
                                            <span class="cat-filter__count"><?php echo $child['term']->count; ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <?php if ($child_has_kids) : ?>
                                        <button class="cat-filter__toggle" aria-expanded="<?php echo $child_active ? 'true' : 'false'; ?>" aria-label="Rozwiń">
                                            <?php echo get_icon('chevron-down', 'icon-xs'); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <?php if ($child_has_kids) : ?>
                                    <ul class="cat-filter__children" <?php echo $child_active ? '' : 'style="display:none"'; ?>>
                                        <?php foreach ($child['children'] as $gc) :
                                            $gc_active = in_array($gc['term']->term_id, $active_ids, true);
                                            $gc_current = ($gc['term']->term_id === $current_id);
                                            $gc_has_kids = !empty($gc['children']);
                                        ?>
                                            <li class="cat-filter__item <?php echo $gc_has_kids ? 'has-children' : ''; ?> <?php echo $gc_active ? 'is-expanded' : ''; ?>">
                                                <div class="cat-filter__row">
                                                    <a href="<?php echo esc_url($gc['url']); ?>"
                                                       class="cat-filter__link <?php echo $gc_current ? 'is-current' : ''; ?>">
                                                        <?php echo esc_html($gc['term']->name); ?>
                                                    </a>
                                                    <?php if ($gc_has_kids) : ?>
                                                        <button class="cat-filter__toggle" aria-expanded="<?php echo $gc_active ? 'true' : 'false'; ?>" aria-label="Rozwiń">
                                                            <?php echo get_icon('chevron-down', 'icon-xs'); ?>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($gc_has_kids) : ?>
                                                    <ul class="cat-filter__children" <?php echo $gc_active ? '' : 'style="display:none"'; ?>>
                                                        <?php foreach ($gc['children'] as $ggc) :
                                                            $ggc_current = ($ggc->term_id === $current_id);
                                                        ?>
                                                            <li class="cat-filter__item">
                                                                <div class="cat-filter__row">
                                                                    <a href="<?php echo esc_url(get_term_link($ggc)); ?>"
                                                                       class="cat-filter__link <?php echo $ggc_current ? 'is-current' : ''; ?>">
                                                                        <?php echo esc_html($ggc->name); ?>
                                                                    </a>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

