<?php
/**
 * Image Box Widget
 */

if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/widgets/image-box/templates/widget.php';
require_once get_template_directory() . '/widgets/image-box/templates/form.php';
require_once get_template_directory() . '/widgets/image-box/templates/update.php';

class MSB_WP_Image_Box_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_image_box',
            __('Image Box', 'msb-app-theme'),
            [
                'description'   => __('Image Box (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Image Box', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        msb_image_box_widget($args, $instance);
    }

    public function form($instance) {
        msb_image_box_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_image_box_update($new_instance, $old_instance);
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Image_Box_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});


