<?php
/**
 * Breadcrumbs Widget Form
 */

function msb_breadcrumbs_form($instance, $widget) {
    // Basic settings
    $separator = isset($instance['separator']) ? $instance['separator'] : '>';
    $home_text = isset($instance['home_text']) ? $instance['home_text'] : 'Trang chủ';
    $show_home = isset($instance['show_home']) ? $instance['show_home'] : true;
    $custom_urls = isset($instance['custom_urls']) ? $instance['custom_urls'] : array();
    
    // Style settings
    $text_color = isset($instance['text_color']) ? $instance['text_color'] : '';
    $link_color = isset($instance['link_color']) ? $instance['link_color'] : '';
    $separator_color = isset($instance['separator_color']) ? $instance['separator_color'] : '';
    $hover_bg_color = isset($instance['hover_bg_color']) ? $instance['hover_bg_color'] : '#E84524';
    $hover_text_color = isset($instance['hover_text_color']) ? $instance['hover_text_color'] : '#fff';
    $disable_current_hover = isset($instance['disable_current_hover']) ? $instance['disable_current_hover'] : false;
    $font_size = isset($instance['font_size']) ? $instance['font_size'] : '';
    $font_weight = isset($instance['font_weight']) ? $instance['font_weight'] : '';
    
    $field_id = function($k) use ($widget) { return $widget->get_field_id($k); };
    $field_n  = function($k) use ($widget) { return $widget->get_field_name($k); };
    
    // Enqueue WP color picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    ?>
    
    <p><strong><?php _e('Cài đặt cơ bản', 'msb-app-theme'); ?></strong></p>
    
    <p>
        <label>
            <input type="checkbox" id="<?php echo esc_attr($field_id('show_home')); ?>" name="<?php echo esc_attr($field_n('show_home')); ?>" value="1" <?php checked($show_home, true); ?>>
            <?php _e('Hiển thị trang chủ', 'msb-app-theme'); ?>
        </label>
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('home_text')); ?>"><?php _e('Tên trang chủ:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('home_text')); ?>" name="<?php echo esc_attr($field_n('home_text')); ?>" type="text" value="<?php echo esc_attr($home_text); ?>">
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('separator')); ?>"><?php _e('Ký tự phân cách:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('separator')); ?>" name="<?php echo esc_attr($field_n('separator')); ?>" type="text" value="<?php echo esc_attr($separator); ?>" placeholder=">">
    </p>
    
    <hr>
    
    <p><strong><?php _e('Tùy chỉnh tên hiển thị cho URL', 'msb-app-theme'); ?></strong></p>
    <p><em><?php _e('Nhập URL và tên hiển thị tùy chỉnh. Để trống nếu muốn dùng tên mặc định.', 'msb-app-theme'); ?></em></p>
    
    <div id="<?php echo esc_attr($field_id('custom_urls_container')); ?>" class="msb-breadcrumbs-custom-urls">
        <?php
        if (!empty($custom_urls) && is_array($custom_urls)) {
            foreach ($custom_urls as $index => $url_mapping) {
                $url = isset($url_mapping['url']) ? $url_mapping['url'] : '';
                $label = isset($url_mapping['label']) ? $url_mapping['label'] : '';
                ?>
                <div class="msb-breadcrumbs-url-item" style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; background: #f9f9f9;">
                    <p style="margin: 0 0 5px 0;">
                        <label><?php _e('URL:', 'msb-app-theme'); ?></label>
                        <input class="widefat" name="<?php echo esc_attr($field_n('custom_urls')); ?>[<?php echo $index; ?>][url]" type="text" value="<?php echo esc_attr($url); ?>" placeholder="<?php echo home_url('/ca-nhan/'); ?>">
                    </p>
                    <p style="margin: 0 0 5px 0;">
                        <label><?php _e('Tên hiển thị:', 'msb-app-theme'); ?></label>
                        <input class="widefat" name="<?php echo esc_attr($field_n('custom_urls')); ?>[<?php echo $index; ?>][label]" type="text" value="<?php echo esc_attr($label); ?>" placeholder="Cá nhân">
                    </p>
                    <button type="button" class="button msb-remove-url-item"><?php _e('Xóa', 'msb-app-theme'); ?></button>
                </div>
                <?php
            }
        }
        ?>
    </div>
    
    <p>
        <button type="button" class="button button-secondary" id="<?php echo esc_attr($field_id('add_url_item')); ?>"><?php _e('+ Thêm URL', 'msb-app-theme'); ?></button>
    </p>
    
    <hr>
    
    <p><strong><?php _e('Tùy chỉnh giao diện', 'msb-app-theme'); ?></strong></p>
    
    <p>
        <label><?php _e('Màu chữ hiện tại:', 'msb-app-theme'); ?></label>
        <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('text_color')); ?>" type="text" value="<?php echo esc_attr($text_color); ?>">
    </p>
    
    <p>
        <label><?php _e('Màu link:', 'msb-app-theme'); ?></label>
        <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('link_color')); ?>" type="text" value="<?php echo esc_attr($link_color); ?>">
    </p>
    
    <p>
        <label><?php _e('Màu phân cách:', 'msb-app-theme'); ?></label>
        <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('separator_color')); ?>" type="text" value="<?php echo esc_attr($separator_color); ?>">
    </p>
    
    <p>
        <label><?php _e('Màu background khi hover:', 'msb-app-theme'); ?></label>
        <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('hover_bg_color')); ?>" type="text" value="<?php echo esc_attr($hover_bg_color); ?>">
    </p>
    
    <p>
        <label><?php _e('Màu chữ khi hover:', 'msb-app-theme'); ?></label>
        <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('hover_text_color')); ?>" type="text" value="<?php echo esc_attr($hover_text_color); ?>">
    </p>
    
    <p>
        <label>
            <input type="checkbox" id="<?php echo esc_attr($field_id('disable_current_hover')); ?>" name="<?php echo esc_attr($field_n('disable_current_hover')); ?>" value="1" <?php checked($disable_current_hover, true); ?>>
            <?php _e('Chặn hover cho trang hiện tại', 'msb-app-theme'); ?>
        </label>
    </p>
    
    <p>
        <label><?php _e('Kích thước chữ (vd: 14px, 1rem):', 'msb-app-theme'); ?></label>
        <input class="widefat" name="<?php echo esc_attr($field_n('font_size')); ?>" type="text" value="<?php echo esc_attr($font_size); ?>">
    </p>
    
    <p>
        <label><?php _e('Độ đậm chữ (vd: 400, 600, bold):', 'msb-app-theme'); ?></label>
        <input class="widefat" name="<?php echo esc_attr($field_n('font_weight')); ?>" type="text" value="<?php echo esc_attr($font_weight); ?>">
    </p>
    
    <script type="text/javascript">
    (function ($) {
        $(function () {
            // Color picker
            $('.msb-color-field').wpColorPicker();
            
            // Add URL item
            $('#<?php echo esc_js($field_id('add_url_item')); ?>').on('click', function(e) {
                e.preventDefault();
                var container = $('#<?php echo esc_js($field_id('custom_urls_container')); ?>');
                var index = container.find('.msb-breadcrumbs-url-item').length;
                var fieldName = '<?php echo esc_js($field_n('custom_urls')); ?>';
                
                var html = '<div class="msb-breadcrumbs-url-item" style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; background: #f9f9f9;">' +
                    '<p style="margin: 0 0 5px 0;">' +
                    '<label><?php _e('URL:', 'msb-app-theme'); ?></label>' +
                    '<input class="widefat" name="' + fieldName + '[' + index + '][url]" type="text" placeholder="<?php echo home_url('/ca-nhan/'); ?>">' +
                    '</p>' +
                    '<p style="margin: 0 0 5px 0;">' +
                    '<label><?php _e('Tên hiển thị:', 'msb-app-theme'); ?></label>' +
                    '<input class="widefat" name="' + fieldName + '[' + index + '][label]" type="text" placeholder="Cá nhân">' +
                    '</p>' +
                    '<button type="button" class="button msb-remove-url-item"><?php _e('Xóa', 'msb-app-theme'); ?></button>' +
                    '</div>';
                
                container.append(html);
            });
            
            // Remove URL item
            $(document).on('click', '.msb-remove-url-item', function(e) {
                e.preventDefault();
                $(this).closest('.msb-breadcrumbs-url-item').remove();
            });
        });
    })(jQuery);
    </script>
    
    <style>
        .msb-breadcrumbs-custom-urls {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
    <?php
}

