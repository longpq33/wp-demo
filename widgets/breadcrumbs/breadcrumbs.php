<?php
/**
 * Breadcrumbs Widget
 */

if (!defined('ABSPATH')) { exit; }

// Include required files
require_once get_template_directory() . '/widgets/breadcrumbs/templates/widget.php';
require_once get_template_directory() . '/widgets/breadcrumbs/templates/form.php';
require_once get_template_directory() . '/widgets/breadcrumbs/templates/update.php';

class MSB_WP_Breadcrumbs_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_breadcrumbs',
            __('Breadcrumbs', 'msb-app-theme'),
            [
                'description'   => __('Breadcrumbs navigation with custom URL labels (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Breadcrumbs', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        msb_breadcrumbs_widget($args, $instance);
    }

    public function form($instance) {
        msb_breadcrumbs_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_breadcrumbs_update($new_instance, $old_instance);
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Breadcrumbs_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});

