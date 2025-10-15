<?php
/**
 * Saving Rates Widget
 */

if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/widgets/saving-rates/templates/widget.php';
require_once get_template_directory() . '/widgets/saving-rates/templates/form.php';
require_once get_template_directory() . '/widgets/saving-rates/templates/update.php';

class MSB_WP_Saving_Rates_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_saving_rates',
            __('Saving Rates', 'msb-app-theme'),
            [
                'description'   => __('Saving Rates Table (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Saving Rates', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) { msb_saving_rates_widget($args, $instance); }
    public function form($instance) { msb_saving_rates_form($instance, $this); }
    public function update($new, $old) { return msb_saving_rates_update($new, $old); }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Saving_Rates_Widget');
});
