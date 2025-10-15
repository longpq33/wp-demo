<?php
/**
 * Interest Calculator Widget
 */

if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/widgets/interest-calculator/templates/widget.php';
require_once get_template_directory() . '/widgets/interest-calculator/templates/form.php';
require_once get_template_directory() . '/widgets/interest-calculator/templates/update.php';

class MSB_WP_Interest_Calculator_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_interest_calculator',
            __('Interest Calculator', 'msb-app-theme'),
            [
                'description'   => __('Interest Calculator (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Interest Calculator', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        msb_interest_calculator_widget($args, $instance);
    }

    public function form($instance) {
        msb_interest_calculator_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_interest_calculator_update($new_instance, $old_instance);
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Interest_Calculator_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});


