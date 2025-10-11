<?php
/**
 * Featured Offers Slider Widget
 */

// Include required files
require_once get_template_directory() . '/widgets/featured-offers-slider/templates/widget.php';
require_once get_template_directory() . '/widgets/featured-offers-slider/templates/form.php';
require_once get_template_directory() . '/widgets/featured-offers-slider/templates/update.php';

class MSB_Featured_Offer_Products_Slider_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_featured_offers_products_slider',
            __('Featured Offer Products Slider', 'msb-app-theme'),
            array(
                'panels_groups' => array('msb'),
                'description' => __('Products Offers Featured Slider (MSB).', 'msb-app-theme'),
                'classname' => 'msb-featured-products-slider-widget'
            )
        );
    }

    public function widget($args, $instance) {
        msb_featured_offers_slider_widget($args, $instance);
    }

    public function form($instance) {
        msb_featured_offers_slider_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_featured_offers_slider_update($new_instance, $old_instance);
    }
}

// Register widget
function msb_register_featured_offer_products_slider_widget() {
    register_widget('MSB_Featured_Offer_Products_Slider_Widget');
}
add_action('widgets_init', 'msb_register_featured_offer_products_slider_widget');
