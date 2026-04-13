<?php
/**
 * Category Filter Widget
 *
 * Renders the custom category filter (accordion desktop / drill-down mobile).
 * Uses template-parts/sidebar/category-filter.php.
 */
class Category_Filter_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'category_filter',
            'Filtr Kategorii (custom)',
            [
                'description' => 'Accordion kategorii produktów z auto-rozwijaniem aktualnej ścieżki.',
            ]
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        get_template_part( 'template-parts/sidebar/category-filter' );
        echo $args['after_widget'];
    }
}

function category_filter_register_widget() {
    register_widget( 'Category_Filter_Widget' );
}
add_action( 'widgets_init', 'category_filter_register_widget' );
