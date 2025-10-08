<?php


if (!defined('ABSPATH')) {
    exit;
}

class MSB_WP_Search_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_search_widget',
            __('MSB Search Box', 'msb-app-theme'),
            [
                'description'   => __('Thanh tìm kiếm tuỳ chỉnh (MSB Style).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Search Box', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : __('Thẻ tín dụng, báo cáo tài chính...', 'msb-app-theme');
        $alignment   = !empty($instance['align']) ? $instance['align'] : 'center';

        echo $args['before_widget'];
        ?>
        <form role="search" method="get" class="msb-search-widget align-<?php echo esc_attr($alignment); ?>" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" class="search-field" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="search-submit" aria-label="<?php echo esc_attr__('Tìm kiếm', 'msb-app-theme'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#aaa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </button>
        </form>

        <?php
        echo $args['after_widget'];
    }
    
    
    
    public function form($instance) {
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : __('Thẻ tín dụng, báo cáo tài chính...', 'msb-app-theme');
        $alignment   = !empty($instance['align']) ? $instance['align'] : 'center';

        $field_id = fn($k) => $this->get_field_id($k);
        $field_n  = fn($k) => $this->get_field_name($k);
        ?>
        <p>
            <label for="<?php echo esc_attr($field_id('placeholder')); ?>"><?php _e('Placeholder:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('placeholder')); ?>" name="<?php echo esc_attr($field_n('placeholder')); ?>" type="text" value="<?php echo esc_attr($placeholder); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($field_id('align')); ?>"><?php _e('Căn lề:', 'msb-app-theme'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($field_id('align')); ?>" name="<?php echo esc_attr($field_n('align')); ?>">
                <option value="left" <?php selected($alignment, 'left'); ?>><?php _e('Trái','msb-app-theme'); ?></option>
                <option value="center" <?php selected($alignment, 'center'); ?>><?php _e('Giữa','msb-app-theme'); ?></option>
                <option value="right" <?php selected($alignment, 'right'); ?>><?php _e('Phải','msb-app-theme'); ?></option>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        return [
            'placeholder' => sanitize_text_field($new_instance['placeholder']),
            'align'       => in_array($new_instance['align'], ['left', 'center', 'right'], true) ? $new_instance['align'] : 'center',
        ];
    }
}

add_action('widgets_init', function () {
    register_widget('MSB_WP_Search_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});
