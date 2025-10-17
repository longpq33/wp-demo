<?php
/**
 * Featured Offers Slider V2 Widget
 */

// Include required files
require_once get_template_directory() . '/widgets/featured-offers-slider-v2/templates/widget.php';
require_once get_template_directory() . '/widgets/featured-offers-slider-v2/templates/form.php';
require_once get_template_directory() . '/widgets/featured-offers-slider-v2/templates/update.php';

class MSB_Featured_Offers_Slider_V2_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_featured_offers_slider_v2',
            __('Featured Offers Slider V2', 'msb-app-theme'),
            array(
                'panels_groups' => array('msb'),
                'description' => __('Featured Offers Slider V2 with category filter and options.', 'msb-app-theme'),
                'classname' => 'msb-featured-offers-slider-v2-widget'
            )
        );
    }

    public function widget($args, $instance) {
        msb_featured_offers_slider_v2_widget($args, $instance);
    }

    public function form($instance) {
        msb_featured_offers_slider_v2_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_featured_offers_slider_v2_update($new_instance, $old_instance);
    }
}

// Register widget
function msb_register_featured_offers_slider_v2_widget() {
    register_widget('MSB_Featured_Offers_Slider_V2_Widget');
}
add_action('widgets_init', 'msb_register_featured_offers_slider_v2_widget');

// Enqueue slider script from featured-products-slider
add_action('wp_enqueue_scripts', function(){
    $init_js = get_template_directory() . '/widgets/featured-products-slider/js/init.js';
    if (file_exists($init_js)) {
        wp_enqueue_script(
            'msb-featured-offers-slider-v2-init',
            get_template_directory_uri() . '/widgets/featured-products-slider/js/init.js',
            array('jquery'),
            filemtime($init_js),
            true
        );
    }
});

// Enqueue widget styles
add_action('wp_enqueue_scripts', function(){
    $less_file = get_template_directory() . '/widgets/featured-offers-slider-v2/featured-offers-slider-v2.less';
    if (file_exists($less_file)) {
        wp_enqueue_style(
            'msb-featured-offers-slider-v2-style',
            get_template_directory_uri() . '/widgets/featured-offers-slider-v2/featured-offers-slider-v2.less',
            array(),
            filemtime($less_file)
        );
    }
});
