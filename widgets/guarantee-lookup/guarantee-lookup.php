<?php
/**
 * Guarantee Lookup Widget
 */

if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/widgets/guarantee-lookup/templates/widget.php';
require_once get_template_directory() . '/widgets/guarantee-lookup/templates/form.php';
require_once get_template_directory() . '/widgets/guarantee-lookup/templates/update.php';

class MSB_WP_Guarantee_Lookup_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_guarantee_lookup',
            __('Guarantee Lookup', 'msb-app-theme'),
            [
                'description'   => __('Guarantee Lookup (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Guarantee Lookup', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        msb_guarantee_lookup_widget($args, $instance);
    }

    public function form($instance) {
        msb_guarantee_lookup_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_guarantee_lookup_update($new_instance, $old_instance);
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Guarantee_Lookup_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});
