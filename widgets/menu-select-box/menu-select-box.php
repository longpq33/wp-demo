<?php
/**
 * Menu Select Box Widget
 */

// Include split templates
require_once get_template_directory() . '/widgets/menu-select-box/templates/widget.php';
require_once get_template_directory() . '/widgets/menu-select-box/templates/form.php';
require_once get_template_directory() . '/widgets/menu-select-box/templates/update.php';

class MSB_Menu_Select_Box_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_menu_select_box',
            __('Menu Select Box', 'msb-app-theme'),
            array(
                'classname' => 'msb-menu-select-box-widget',
                'description' => __('Dropdown input với modal chứa menu items', 'msb-app-theme'),
                'panels_groups' => array('msb')
            )
        );
    }

    public function widget($args, $instance) {
        msb_menu_select_box_widget($args, $instance);
       
    }

    public function form($instance) {
        msb_menu_select_box_form($instance, $this);
    }

    public function update($new_instance, $old_instance) {
        return msb_menu_select_box_update($new_instance, $old_instance); 
    }
}

// Register widget
function msb_register_menu_select_box_widget() {
    register_widget('MSB_Menu_Select_Box_Widget');
}
add_action('widgets_init', 'msb_register_menu_select_box_widget');