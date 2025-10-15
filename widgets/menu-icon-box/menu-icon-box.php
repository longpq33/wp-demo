<?php
/**
 * Menu Icon Box Widget
 */

if (!defined('ABSPATH')) { exit; }

// Enqueue CSS for Menu Icon Box widget
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'msb-menu-icon-box-style',
        get_template_directory_uri() . '/widgets/menu-icon-box/menu-icon-box.less',
        [],
        filemtime(get_template_directory() . '/widgets/menu-icon-box/menu-icon-box.less')
    );
});

// Include required files
require_once get_template_directory() . '/widgets/menu-icon-box/templates/widget.php';
require_once get_template_directory() . '/widgets/menu-icon-box/templates/form.php';
require_once get_template_directory() . '/widgets/menu-icon-box/templates/update.php';

class MSB_WP_Menu_Icon_Box_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_menu_icon_box',
            __('Menu Icon Box', 'msb-app-theme'),
            [
                'description'   => __('Menu Icon Box (MSB).', 'msb-app-theme'),
                'panels_groups' => ['msb'],
                'panels_title'  => __('Menu Icon Box', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        msb_menu_box_widget($args, $instance);
    }

    public function form($instance) {
        msb_menu_icon_box_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_menu_box_update($new_instance, $old_instance);
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Menu_Icon_Box_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});
