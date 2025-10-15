<?php
/**
 * Empty Box Widget
 */

// Include required files
require_once get_template_directory() . '/widgets/empty-box/templates/form.php';
require_once get_template_directory() . '/widgets/empty-box/templates/update.php';
require_once get_template_directory() . '/widgets/empty-box/templates/widget.php';

class MSB_Empty_Box_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_empty_box',
            __('Empty Box', 'msb-app-theme'),
            array(
                'panels_groups' => array('msb'),
                'description' => __('Empty Box (MSB).', 'msb-app-theme'),
                'classname' => 'msb-empty-box-widget'
            )
        );
    }

    public function widget($args, $instance) {
        msb_empty_box_widget($args, $instance);
    }

    public function form($instance) {
        msb_empty_box_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_empty_box_update($new_instance, $old_instance);
    }
}

// Register widget
function msb_register_empty_box_widget() {
    register_widget('MSB_Empty_Box_Widget');
}
add_action('widgets_init', 'msb_register_empty_box_widget');
