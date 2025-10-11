<?php
/**
 * Icon Box Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include required files
require_once get_template_directory() . '/widgets/icon-box/templates/widget.php';
require_once get_template_directory() . '/widgets/icon-box/templates/form.php';
require_once get_template_directory() . '/widgets/icon-box/templates/update.php';

class MSB_WP_Icon_Box_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'msb_wp_icon_box',
            __('IconBox', 'msb-app-theme'),
            [
                'description' => __('Icon Box (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title' => __('IconBox', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance)
    {
        msb_icon_box_widget($args, $instance, $this);
    }

    public function form($instance)
    {
        msb_icon_box_form($instance, $this);
    }

    public function update($new_instance, $old_instance)
    {
        return msb_icon_box_update($new_instance, $old_instance);
    }
}

add_action('widgets_init', function () {
    register_widget('MSB_WP_Icon_Box_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});