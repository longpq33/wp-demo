<?php
/**
 * Navbar Box Widget
 */

if (!defined('ABSPATH')) { exit; }

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'msb-navbar-box-style',
        get_template_directory_uri() . '/widgets/navbar-box/navbar-box.less',
        [],
        filemtime(get_template_directory() . '/widgets/navbar-box/navbar-box.less')
    );
});

require_once get_template_directory() . '/widgets/navbar-box/templates/widget.php';
require_once get_template_directory() . '/widgets/navbar-box/templates/form.php';
require_once get_template_directory() . '/widgets/navbar-box/templates/update.php';

class MSB_WP_Navbar_Box_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_navbar_box',
            __('Navbar Box', 'msb-app-theme'),
            [
                'description'   => __('Navbar Box', 'msb-app-theme'),
                'panels_groups' => ['msb'],
                'panels_title'  => __('Navbar Box', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        msb_navbar_box_widget($args, $instance);
    }

    public function form($instance) {
        msb_navbar_box_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_navbar_box_update($new_instance, $old_instance);
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Navbar_Box_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});
