<?php


if (!defined('ABSPATH')) {
    exit;
}

class MSB_WP_Search_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_search_widget',
            __('Search Box', 'msb-app-theme'),
            [
                'description'   => __('Search Box (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Search Box', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : __('Thẻ tín dụng, báo cáo tài chính...', 'msb-app-theme');
        $alignment   = !empty($instance['align']) ? $instance['align'] : 'center';
        $background_color = !empty($instance['background_color']) ? $instance['background_color'] : '#fff';
        $border_color = !empty($instance['border_color']) ? $instance['border_color'] : '#ccc';
        $border_radius = !empty($instance['border_radius']) ? $instance['border_radius'] : '10';
        // Ensure border radius has px unit if numeric
        $border_radius_css = is_numeric($border_radius) ? ($border_radius . 'px') : $border_radius;

        echo $args['before_widget'];
        $style = sprintf(
            'background-color:%1$s; border-color:%2$s; border-radius:%3$s;',
            esc_attr($background_color),
            esc_attr($border_color),
            esc_attr($border_radius_css)
        );
        ?>
        <form role="search" method="get" class="msb-search-widget align-<?php echo esc_attr($alignment); ?>" action="<?php echo esc_url(home_url('/')); ?>" >
            <input type="search" class="search-field" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s" style="<?php echo esc_attr($style); ?>" />
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
        $background_color = !empty($instance['background_color']) ? $instance['background_color'] : '#ffffff';
        $border_color = !empty($instance['border_color']) ? $instance['border_color'] : '#cccccc';
        // Store number only for admin field; add px in frontend
        $border_radius = isset($instance['border_radius']) && $instance['border_radius'] !== ''
            ? preg_replace('/[^0-9.]/', '', (string) $instance['border_radius'])
            : '10';

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
        <p>
            <label for="<?php echo esc_attr($field_id('border_radius')); ?>"><?php _e('Bán kính viền:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('border_radius')); ?>" name="<?php echo esc_attr($field_n('border_radius')); ?>" type="number" min="0" step="1" value="<?php echo esc_attr($border_radius); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($field_id('background_color')); ?>"><?php _e('Màu nền:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('background_color')); ?>" name="<?php echo esc_attr($field_n('background_color')); ?>" type="color" value="<?php echo esc_attr($background_color); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($field_id('border_color')); ?>"><?php _e('Màu viền:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('border_color')); ?>" name="<?php echo esc_attr($field_n('border_color')); ?>" type="color" value="<?php echo esc_attr($border_color); ?>">
        </p>    
        <?php
    }

    public function update($new_instance, $old_instance) {
        $updated = [];

        $updated['placeholder'] = sanitize_text_field($new_instance['placeholder']);
        $updated['align'] = in_array($new_instance['align'], ['left', 'center', 'right'], true) ? $new_instance['align'] : 'center';

        $bg = isset($new_instance['background_color']) ? sanitize_hex_color($new_instance['background_color']) : '';
        $bd = isset($new_instance['border_color']) ? sanitize_hex_color($new_instance['border_color']) : '';
        $radius = isset($new_instance['border_radius']) ? sanitize_text_field($new_instance['border_radius']) : '';

        // Fallback to previous or defaults if invalid
        $updated['background_color'] = $bg ? $bg : (!empty($old_instance['background_color']) ? $old_instance['background_color'] : '#ffffff');
        $updated['border_color'] = $bd ? $bd : (!empty($old_instance['border_color']) ? $old_instance['border_color'] : '#cccccc');

        // Normalize border radius: accept number or number+unit; store as number or px
        if ($radius === '' && isset($old_instance['border_radius'])) {
            $radius = $old_instance['border_radius'];
        }
        if (is_numeric($radius)) {
            $updated['border_radius'] = (string) intval($radius); // store number only
        } else {
            // Keep as-is if it already has units
            $updated['border_radius'] = $radius;
        }

        return $updated;
    }
}

add_action('widgets_init', function () {
    register_widget('MSB_WP_Search_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});
