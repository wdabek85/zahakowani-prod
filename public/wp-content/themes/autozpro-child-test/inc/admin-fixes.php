<?php
/**
 * Admin UI fixes — small tweaks for WP admin.
 */

/**
 * Fix Rank Math "SEO Details" column breaking vertically on WC products list.
 * Forces minimum width so content flows horizontally.
 */
/**
 * Bulk action: "Zaktualizuj" — re-save selected products to trigger save hooks.
 * Use when you add global logic and need existing products to pick it up.
 */
add_filter('bulk_actions-edit-product', function ($actions) {
    $actions['child_refresh'] = 'Zaktualizuj (odśwież dane)';
    return $actions;
});

add_filter('handle_bulk_actions-edit-product', function ($redirect_to, $action, $post_ids) {
    if ($action !== 'child_refresh') return $redirect_to;

    $count = 0;
    foreach ($post_ids as $post_id) {
        // wp_update_post triggers save_post, woocommerce_update_product, etc.
        wp_update_post(['ID' => $post_id]);

        // Also trigger WC product save if it's a WC product
        if (function_exists('wc_get_product')) {
            $product = wc_get_product($post_id);
            if ($product) {
                $product->save();
            }
        }

        $count++;
    }

    $redirect_to = add_query_arg('child_refreshed', $count, $redirect_to);
    return $redirect_to;
}, 10, 3);

add_action('admin_notices', function () {
    if (!empty($_REQUEST['child_refreshed'])) {
        $count = (int) $_REQUEST['child_refreshed'];
        echo '<div class="updated notice is-dismissible"><p>';
        echo esc_html(sprintf('Zaktualizowano %d produkt(y/ów).', $count));
        echo '</p></div>';
    }
});

add_action('admin_head', function () {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->id !== 'edit-product') return;
    ?>
    <style>
        /* Rank Math SEO Details column — force horizontal layout */
        .wp-list-table th.column-rank_math_seo_details,
        .wp-list-table td.column-rank_math_seo_details {
            min-width: 220px;
            max-width: 280px;
            word-wrap: break-word;
            word-break: normal;
            white-space: normal;
        }

        .wp-list-table td.column-rank_math_seo_details * {
            word-break: normal;
            overflow-wrap: break-word;
        }

        /* Collapse long content with ellipsis fallback */
        .wp-list-table td.column-rank_math_seo_details {
            font-size: 12px;
            line-height: 1.4;
        }
    </style>
    <?php
});
