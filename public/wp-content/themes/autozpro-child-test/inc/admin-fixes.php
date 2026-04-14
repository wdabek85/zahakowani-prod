<?php
/**
 * Admin UI fixes — small tweaks for WP admin.
 */

/**
 * Fix Rank Math "SEO Details" column breaking vertically on WC products list.
 * Forces minimum width so content flows horizontally.
 */
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
