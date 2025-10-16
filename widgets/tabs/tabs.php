<?php
if (!defined('ABSPATH')) { exit; }

class MSB_SOW_Tabs_Container extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            'msb-sow-tabs-container',
            __('Tabs(MSB)', 'msb-app-theme'),
            array(
                'description' => __('Tabs container where each tab holds its own builder content.', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('MSB Tabs', 'msb-app-theme'),
            ),
            array(),
            false,
            plugin_dir_path(__FILE__)
        );
    }

    function initialize() {
        $this->register_frontend_scripts(
            array(
                array(
                    'msb-sow-tabs-js',
                    get_template_directory_uri() . '/widgets/tabs/js/tabs.js',
                    array('jquery'),
                    file_exists(get_template_directory() . '/widgets/tabs/js/tabs.js') ? filemtime(get_template_directory() . '/widgets/tabs/js/tabs.js') : false,
                    true
                )
            )
        );
    }

    function get_widget_form() {
        return array(
            'tabs' => array(
                'type' => 'repeater',
                'label' => __('Tabs', 'msb-app-theme'),
                'item_name'  => __('Tab', 'msb-app-theme'),
                'item_label' => array(
                    'selector' => "[name*='[title]']",
                    'update_event' => 'change',
                    'value_method' => 'val'
                ),
                'fields' => array(
                    'title' => array(
                        'type' => 'text',
                        'label' => __('Tiêu đề', 'msb-app-theme'),
                    ),
                    'content' => array(
                        'type' => 'builder',
                        'label' => __('Nội dung Tab (Add Widget)', 'msb-app-theme'),
                        'description' => __('Nhấn Add Widget để chèn widget vào tab này.', 'msb-app-theme'),
                    ),
                ),
            ),
        );
    }

    function get_template_name($instance){ return 'widget'; }
    function get_style_name($instance){ return ''; }
    function has_preview(){ return false; }
}

siteorigin_widget_register('msb-sow-tabs-container', __FILE__, 'MSB_SOW_Tabs_Container');
?>




