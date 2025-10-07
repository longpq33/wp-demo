<?php

if (!defined('ABSPATH')) { exit; }

class MSB_WP_Heading_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_heading',
            __('Heading', 'msb-app-theme'),
            [
                'description'   => __('Heading (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Heading', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        $heading_id   = isset($instance['id']) ? intval($instance['id']) : 0;
        $title     = isset($instance['title']) ? $instance['title'] : '';
        $description = isset($instance['description']) ? $instance['description'] : '';

        echo $args['before_widget'];
        $tag = 'div';
        $classes = 'msb-heading';
       
        echo '<' . $tag . ' class="' . $classes . '" id="' . $heading_id . '">';

        if ($title) {
            echo '<h3 class="msb-icon-box__title">' . esc_html($title) . '</h3>';
        }

        if ($description) {
          echo '<p class="msb-icon-box__title">' . esc_html($description) . '</p>';
      }

        echo '</' . $tag . '>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title      = isset($instance['title']) ? $instance['title'] : '';
        $description = isset($instance['description']) ? $instance['description'] : '';
        $field_id = function($k){ return $this->get_field_id($k); };
        $field_n  = function($k){ return $this->get_field_name($k); };
        // Ensure media scripts are available in widget admin form
        if ( function_exists('wp_enqueue_media') ) { wp_enqueue_media(); }
        ?>
        <p>
            <label for="<?php echo esc_attr($field_id('title')); ?>"><?php _e('Tiêu đề:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('title')); ?>" name="<?php echo esc_attr($field_n('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($field_id('description')); ?>"><?php _e('Nội dung:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('description')); ?>" name="<?php echo esc_attr($field_n('description')); ?>" type="text" value="<?php echo esc_attr($description); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $inst = [];
        $inst['title']      = sanitize_text_field($new_instance['title'] ?? '');
        $inst['description'] = sanitize_text_field($new_instance['description'] ?? '');
       return $inst;
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Heading_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});


