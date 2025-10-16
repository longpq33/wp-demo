<?php
if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/widgets/saving-rates-tabs/templates/widget.php';
require_once get_template_directory() . '/widgets/saving-rates-tabs/templates/form.php';
require_once get_template_directory() . '/widgets/saving-rates-tabs/templates/update.php';

class MSB_WP_Saving_Rates_Tabs_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_saving_rates_tabs',
            __('Saving Rates Tabs (MSB)', 'msb-app-theme'),
            [
                'description'   => __('Display saving rates in 3 tabs: Counter, Online, and Foreign Currency.', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Saving Rates Tabs', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) { 
        msb_saving_rates_tabs_widget($args, $instance, $this->number); 
    }
    public function form($instance) { 
        msb_saving_rates_tabs_form($instance, $this); 
    }
    public function update($new, $old) { 
        return msb_saving_rates_tabs_update($new, $old); 
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Saving_Rates_Tabs_Widget');
});

// Enqueue assets
add_action('wp_enqueue_scripts', function(){
    $js_path = get_template_directory() . '/widgets/saving-rates-tabs/js/frontend.js';
    if (file_exists($js_path)) {
        wp_enqueue_script('msb-saving-rates-tabs', get_template_directory_uri() . '/widgets/saving-rates-tabs/js/frontend.js', array('jquery'), filemtime($js_path), true);
    }
});

?>
