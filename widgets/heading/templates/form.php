<?php
/**
 * Heading Widget Form
 */



function msb_heading_form($instance, $widget) {
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
    $show_more_btn = isset($instance['show_more_btn']) ? $instance['show_more_btn'] : 0;
    $more_btn_text = isset($instance['more_btn_text']) ? $instance['more_btn_text'] : 'Xem thêm';
    $more_btn_url = isset($instance['more_btn_url']) ? $instance['more_btn_url'] : '';
    $field_id = function($k) use ($widget) { return $widget->get_field_id($k); };
    $field_n  = function($k) use ($widget) { return $widget->get_field_name($k); };
    if ( function_exists('wp_enqueue_media') ) { wp_enqueue_media(); }
    
    // Enqueue WP color picker BEFORE HTML output
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
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
    <p><strong><?php _e('Nút Xem Thêm', 'msb-app-theme'); ?></strong></p>
    <p>
        <input class="checkbox" type="checkbox" <?php checked($show_more_btn); ?> id="<?php echo $widget->get_field_id('show_more_btn'); ?>" name="<?php echo $widget->get_field_name('show_more_btn'); ?>" />
        <label for="<?php echo $widget->get_field_id('show_more_btn'); ?>"><?php _e('Hiển thị nút xem thêm', 'msb-app-theme'); ?></label>
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('more_btn_text')); ?>"><?php _e('Text nút:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('more_btn_text')); ?>" name="<?php echo esc_attr($field_n('more_btn_text')); ?>" type="text" value="<?php echo esc_attr($more_btn_text); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('more_btn_url')); ?>"><?php _e('URL nút:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('more_btn_url')); ?>" name="<?php echo esc_attr($field_n('more_btn_url')); ?>" type="url" value="<?php echo esc_attr($more_btn_url); ?>">
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
}
