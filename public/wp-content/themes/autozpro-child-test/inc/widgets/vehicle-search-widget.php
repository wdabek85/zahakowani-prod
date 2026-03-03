<?php
/**
 * Vehicle Search Widget
 *
 * Lightweight replacement for the Elementor-based vehicle search.
 * Renders the PHP template in template-parts/sidebar/vehicle-search.php.
 */
class Vehicle_Search_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'vehicle_search',
            'Vehicle Search',
            [
                'description' => 'Wyszukiwarka pojazdów (Rok > Marka > Model > Część)',
            ]
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        get_template_part( 'template-parts/sidebar/vehicle-search' );
        echo $args['after_widget'];
    }
}

function vehicle_search_register_widget() {
    register_widget( 'Vehicle_Search_Widget' );
}
add_action( 'widgets_init', 'vehicle_search_register_widget' );
