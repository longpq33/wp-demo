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
        $subtitle   = isset($instance['subtitle']) ? $instance['subtitle'] : '';
        $description = isset($instance['description']) ? $instance['description'] : '';
        // Styles
        $t_fs = isset($instance['title_fs']) ? trim($instance['title_fs']) : '';
        $t_cl = isset($instance['title_cl']) ? trim($instance['title_cl']) : '';
        $t_fw = isset($instance['title_fw']) ? trim($instance['title_fw']) : '';
        $s_fs = isset($instance['subtitle_fs']) ? trim($instance['subtitle_fs']) : '';
        $s_cl = isset($instance['subtitle_cl']) ? trim($instance['subtitle_cl']) : '';
        $s_fw = isset($instance['subtitle_fw']) ? trim($instance['subtitle_fw']) : '';
        $d_fs = isset($instance['desc_fs']) ? trim($instance['desc_fs']) : '';
        $d_cl = isset($instance['desc_cl']) ? trim($instance['desc_cl']) : '';
        $d_fw = isset($instance['desc_fw']) ? trim($instance['desc_fw']) : '';

        echo $args['before_widget'];
        $tag = 'div';
        $classes = 'msb-heading';
       
        echo '<' . $tag . ' class="' . $classes . '" id="' . $heading_id . '">';

        if ($title) {
            $style = '';
            if ($t_fs !== '') $style .= 'font-size:' . esc_attr($t_fs) . ';';
            if ($t_cl !== '') $style .= 'color:' . esc_attr($t_cl) . ';';
            if ($t_fw !== '') $style .= 'font-weight:' . esc_attr($t_fw) . ';';
            echo '<h3 class="msb-icon-box__title"' . ($style ? ' style="' . $style . '"' : '') . '>' . esc_html($title) . '</h3>';
        }
        if ($subtitle) {
            $style = '';
            if ($s_fs !== '') $style .= 'font-size:' . esc_attr($s_fs) . ';';
            if ($s_cl !== '') $style .= 'color:' . esc_attr($s_cl) . ';';
            if ($s_fw !== '') $style .= 'font-weight:' . esc_attr($s_fw) . ';';
            echo '<h4 class="msb-icon-box__subtitle"' . ($style ? ' style="' . $style . '"' : '') . '>' . esc_html($subtitle) . '</h4>';
        }

        if ($description) {
            $style = '';
            if ($d_fs !== '') $style .= 'font-size:' . esc_attr($d_fs) . ';';
            if ($d_cl !== '') $style .= 'color:' . esc_attr($d_cl) . ';';
            if ($d_fw !== '') $style .= 'font-weight:' . esc_attr($d_fw) . ';';
            echo '<p class="msb-icon-box__description"' . ($style ? ' style="' . $style . '"' : '') . '>' . esc_html($description) . '</p>';
      }

        echo '</' . $tag . '>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title      = isset($instance['title']) ? $instance['title'] : '';
        $subtitle   = isset($instance['subtitle']) ? $instance['subtitle'] : '';
        $description = isset($instance['description']) ? $instance['description'] : '';
        // style defaults
        $title_fs = isset($instance['title_fs']) ? $instance['title_fs'] : '';
        $title_cl = isset($instance['title_cl']) ? $instance['title_cl'] : '';
        $title_fw = isset($instance['title_fw']) ? $instance['title_fw'] : '';
        $subtitle_fs = isset($instance['subtitle_fs']) ? $instance['subtitle_fs'] : '';
        $subtitle_cl = isset($instance['subtitle_cl']) ? $instance['subtitle_cl'] : '';
        $subtitle_fw = isset($instance['subtitle_fw']) ? $instance['subtitle_fw'] : '';
        $desc_fs = isset($instance['desc_fs']) ? $instance['desc_fs'] : '';
        $desc_cl = isset($instance['desc_cl']) ? $instance['desc_cl'] : '';
        $desc_fw = isset($instance['desc_fw']) ? $instance['desc_fw'] : '';
        $field_id = function($k){ return $this->get_field_id($k); };
        $field_n  = function($k){ return $this->get_field_name($k); };
        if ( function_exists('wp_enqueue_media') ) { wp_enqueue_media(); }
        ?>
        <p>
            <label for="<?php echo esc_attr($field_id('title')); ?>"><?php _e('Tiêu đề:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('title')); ?>" name="<?php echo esc_attr($field_n('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($field_id('subtitle')); ?>"><?php _e('Tiêu đề phụ:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('subtitle')); ?>" name="<?php echo esc_attr($field_n('subtitle')); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($field_id('description')); ?>"><?php _e('Nội dung:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('description')); ?>" name="<?php echo esc_attr($field_n('description')); ?>" type="text" value="<?php echo esc_attr($description); ?>">
        </p>
        <hr>
        <p><strong><?php _e('Style - Title', 'msb-app-theme'); ?></strong></p>
        <p>
            <label><?php _e('Font size (vd: 24px, 1.5rem):', 'msb-app-theme'); ?></label>
            <input class="widefat" name="<?php echo esc_attr($field_n('title_fs')); ?>" type="text" value="<?php echo esc_attr($title_fs); ?>">
        </p>
        <p>
            <label><?php _e('Color:', 'msb-app-theme'); ?></label>
            <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('title_cl')); ?>" type="text" value="<?php echo esc_attr($title_cl); ?>">
        </p>
        <p>
            <label><?php _e('Font weight (vd: 400, 600, bold):', 'msb-app-theme'); ?></label>
            <input class="widefat" name="<?php echo esc_attr($field_n('title_fw')); ?>" type="text" value="<?php echo esc_attr($title_fw); ?>">
        </p>
        <p><strong><?php _e('Style - Subtitle', 'msb-app-theme'); ?></strong></p>
        <p>
            <label><?php _e('Font size:', 'msb-app-theme'); ?></label>
            <input class="widefat" name="<?php echo esc_attr($field_n('subtitle_fs')); ?>" type="text" value="<?php echo esc_attr($subtitle_fs); ?>">
        </p>
        <p>
            <label><?php _e('Color:', 'msb-app-theme'); ?></label>
            <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('subtitle_cl')); ?>" type="text" value="<?php echo esc_attr($subtitle_cl); ?>">
        </p>
        <p>
            <label><?php _e('Font weight:', 'msb-app-theme'); ?></label>
            <input class="widefat" name="<?php echo esc_attr($field_n('subtitle_fw')); ?>" type="text" value="<?php echo esc_attr($subtitle_fw); ?>">
        </p>
        <p><strong><?php _e('Style - Description', 'msb-app-theme'); ?></strong></p>
        <p>
            <label><?php _e('Font size:', 'msb-app-theme'); ?></label>
            <input class="widefat" name="<?php echo esc_attr($field_n('desc_fs')); ?>" type="text" value="<?php echo esc_attr($desc_fs); ?>">
        </p>
        <p>
            <label><?php _e('Color:', 'msb-app-theme'); ?></label>
            <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('desc_cl')); ?>" type="text" value="<?php echo esc_attr($desc_cl); ?>">
        </p>
        <p>
            <label><?php _e('Font weight:', 'msb-app-theme'); ?></label>
            <input class="widefat" name="<?php echo esc_attr($field_n('desc_fw')); ?>" type="text" value="<?php echo esc_attr($desc_fw); ?>">
        </p>
        <script type="text/javascript">
            (function ($) {
                $(function () {
                    $('.msb-color-field').wpColorPicker();
                })
            })(jQuery);
            </script>
        <?php
        // Enqueue WP color picker (JS init handled via admin enqueue script)
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    public function update($new_instance, $old_instance) {
        $inst = [];
        $inst['title']      = sanitize_text_field($new_instance['title'] ?? '');
        $inst['subtitle']   = sanitize_text_field($new_instance['subtitle'] ?? '');
        $inst['description'] = sanitize_text_field($new_instance['description'] ?? '');
        // styles
        $inst['title_fs'] = sanitize_text_field($new_instance['title_fs'] ?? '');
        $inst['title_cl'] = sanitize_text_field($new_instance['title_cl'] ?? '');
        $inst['title_fw'] = sanitize_text_field($new_instance['title_fw'] ?? '');
        $inst['subtitle_fs'] = sanitize_text_field($new_instance['subtitle_fs'] ?? '');
        $inst['subtitle_cl'] = sanitize_text_field($new_instance['subtitle_cl'] ?? '');
        $inst['subtitle_fw'] = sanitize_text_field($new_instance['subtitle_fw'] ?? '');
        $inst['desc_fs'] = sanitize_text_field($new_instance['desc_fs'] ?? '');
        $inst['desc_cl'] = sanitize_text_field($new_instance['desc_cl'] ?? '');
        $inst['desc_fw'] = sanitize_text_field($new_instance['desc_fw'] ?? '');
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


