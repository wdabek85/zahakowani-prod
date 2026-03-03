<?php
/**
 * Temporary script — find orphaned media attachments.
 * Run via: php82 ~/wp-cli.phar eval-file find-orphans.php --path=public/wp
 * Delete after use.
 */

global $wpdb;

$attachments = get_posts([
    'post_type'      => 'attachment',
    'post_status'    => 'inherit',
    'posts_per_page' => -1,
    'post_mime_type' => 'image',
]);

$orphaned      = 0;
$orphaned_size = 0;
$orphaned_list = [];

foreach ($attachments as $att) {
    // Check if used in postmeta (featured image, ACF, etc.)
    $in_meta = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_value LIKE %s AND post_id != %d",
        '%' . $att->ID . '%',
        $att->ID
    ));

    // Check if used in post_content
    $file_url = wp_get_attachment_url($att->ID);
    $in_content = 0;
    if ($file_url) {
        $basename = basename($file_url);
        $in_content = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_content LIKE %s AND ID != %d",
            '%' . $basename . '%',
            $att->ID
        ));
    }

    $parent = $att->post_parent;

    if ($in_meta == 0 && $in_content == 0 && $parent == 0) {
        $file = get_attached_file($att->ID);
        $size = file_exists($file) ? filesize($file) : 0;
        $orphaned++;
        $orphaned_size += $size;
        if (count($orphaned_list) < 20) {
            $orphaned_list[] = $att->ID . ' | ' . basename($file) . ' | ' . round($size / 1024) . 'KB';
        }
    }
}

echo "Orphaned: {$orphaned} images\n";
echo "Total size: " . round($orphaned_size / 1024 / 1024, 1) . "MB\n\n";

foreach ($orphaned_list as $l) {
    echo $l . "\n";
}
