<?php
$ids = [2020, 2442, 2500, 3667, 186];
$total = 0;
foreach ($ids as $id) {
    $data = get_post_meta($id, '_elementor_data', true);
    if (!$data) continue;
    $count = substr_count($data, '"widgetType"');
    $title = get_the_title($id);
    echo "$id | $title | $count widgets\n";
    $total += $count;
}
echo "\nHeader+Footer+Megamenus: $total widgets\n";

$front = get_option('page_on_front');
if ($front) {
    $data = get_post_meta($front, '_elementor_data', true);
    $home_count = $data ? substr_count($data, '"widgetType"') : 0;
    echo "Homepage (ID $front): $home_count widgets\n";
    echo "Content only (to remove): " . max(0, $home_count) . " widgets\n";
}
