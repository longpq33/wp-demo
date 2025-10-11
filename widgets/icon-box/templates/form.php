<?php
/**
 * Icon Box Widget Form
 */

function msb_icon_box_form($instance, $widget) {
    $title = isset($instance['title']) ? $instance['title'] : '';
    $icon_id = isset($instance['icon']) ? intval($instance['icon']) : 0;
    $icon_size = isset($instance['icon_size']) ? intval($instance['icon_size']) : 40;
    $url = isset($instance['url']) ? $instance['url'] : '';
    $align = isset($instance['align']) ? $instance['align'] : 'center';
    $text_color = isset($instance['text_color']) ? $instance['text_color'] : '#111827';
    $icon_pos = isset($instance['icon_pos']) ? $instance['icon_pos'] : 'top';
    $hover_text_color = isset($instance['hover_text_color']) ? $instance['hover_text_color'] : '#000000';
    $hover_bg_color = isset($instance['hover_bg_color']) ? $instance['hover_bg_color'] : '#0b2545';
    $enable_hover_bg = !empty($instance['enable_hover_bg']);
    $hover_scale = isset($instance['hover_scale']) ? floatval($instance['hover_scale']) : 1;

    $field_id = fn($k) => $widget->get_field_id($k);
    $field_n = fn($k) => $widget->get_field_name($k);

    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');
    ?>

    <p>
        <label for="<?php echo esc_attr($field_id('title')); ?>"><?php _e('Tiêu đề:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('title')); ?>"
            name="<?php echo esc_attr($field_n('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('url')); ?>"><?php _e('Liên kết:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('url')); ?>"
            name="<?php echo esc_attr($field_n('url')); ?>" type="url" value="<?php echo esc_attr($url); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('align')); ?>"><?php _e('Căn lề:', 'msb-app-theme'); ?></label>
        <select class="widefat" id="<?php echo esc_attr($field_id('align')); ?>"
            name="<?php echo esc_attr($field_n('align')); ?>">
            <option value="left" <?php selected($align, 'left'); ?>><?php _e('Trái', 'msb-app-theme'); ?></option>
            <option value="center" <?php selected($align, 'center'); ?>><?php _e('Giữa', 'msb-app-theme'); ?></option>
            <option value="right" <?php selected($align, 'right'); ?>><?php _e('Phải', 'msb-app-theme'); ?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('icon_pos')); ?>"><?php _e('Vị trí icon:', 'msb-app-theme'); ?></label>
        <select class="widefat" id="<?php echo esc_attr($field_id('icon_pos')); ?>"
            name="<?php echo esc_attr($field_n('icon_pos')); ?>">
            <option value="top" <?php selected($icon_pos, 'top'); ?>><?php _e('Trên', 'msb-app-theme'); ?></option>
            <option value="bottom" <?php selected($icon_pos, 'bottom'); ?>><?php _e('Dưới', 'msb-app-theme'); ?></option>
            <option value="left" <?php selected($icon_pos, 'left'); ?>><?php _e('Trái', 'msb-app-theme'); ?></option>
            <option value="right" <?php selected($icon_pos, 'right'); ?>><?php _e('Phải', 'msb-app-theme'); ?></option>
        </select>
    </p>

    <div class="msb-media-control">
        <label><?php _e('Icon:', 'msb-app-theme'); ?></label>
        <div class="msb-media-preview" style="margin:6px 0;">
            <?php if ($icon_id && ($img = wp_get_attachment_image($icon_id, array(80, 80)))) {
                echo $img;
            } ?>
        </div>
        <input type="hidden" class="msb-media-id" id="<?php echo esc_attr($field_id('icon')); ?>"
            name="<?php echo esc_attr($field_n('icon')); ?>" value="<?php echo esc_attr($icon_id); ?>" />
        <button type="button" class="button msb-media-select"><?php _e('Chọn ảnh', 'msb-app-theme'); ?></button>
        <button type="button" class="button msb-media-remove"
            style="margin-left:6px;<?php echo $icon_id ? '' : 'display:none;'; ?>"><?php _e('Xóa', 'msb-app-theme'); ?></button>
    </div>

    <p>
        <label
            for="<?php echo esc_attr($field_id('icon_size')); ?>"><?php _e('Kích thước icon (px):', 'msb-app-theme'); ?></label>
        <input class="small-text" id="<?php echo esc_attr($field_id('icon_size')); ?>"
            name="<?php echo esc_attr($field_n('icon_size')); ?>" type="number" value="<?php echo esc_attr($icon_size); ?>">
    </p>

    <p>
        <label for="<?php echo esc_attr($field_id('text_color')); ?>"><?php _e('Màu chữ:', 'msb-app-theme'); ?></label>
        <input class="msb-color-picker" id="<?php echo esc_attr($field_id('text_color')); ?>"
            name="<?php echo esc_attr($field_n('text_color')); ?>" type="text" value="<?php echo esc_attr($text_color); ?>"
            data-default-color="#111827">
    </p>

    <fieldset style="border:1px solid #ddd;padding:10px;margin-top:10px;">
        <legend><strong><?php _e('Tùy chọn Hover', 'msb-app-theme'); ?></strong></legend>

        <p>
            <label
                for="<?php echo esc_attr($field_id('hover_text_color')); ?>"><?php _e('Màu chữ khi hover:', 'msb-app-theme'); ?></label>
            <input class="msb-color-picker" id="<?php echo esc_attr($field_id('hover_text_color')); ?>"
                name="<?php echo esc_attr($field_n('hover_text_color')); ?>" type="text"
                value="<?php echo esc_attr($hover_text_color); ?>" data-default-color="#000000">
        </p>

        <p>
            <label
                for="<?php echo esc_attr($field_id('hover_bg_color')); ?>"><?php _e('Màu nền khi hover:', 'msb-app-theme'); ?></label>
            <input class="msb-color-picker" id="<?php echo esc_attr($field_id('hover_bg_color')); ?>"
                name="<?php echo esc_attr($field_n('hover_bg_color')); ?>" type="text"
                value="<?php echo esc_attr($hover_bg_color); ?>" data-default-color="#0b2545">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($enable_hover_bg, true); ?>
                id="<?php echo esc_attr($field_id('enable_hover_bg')); ?>"
                name="<?php echo esc_attr($field_n('enable_hover_bg')); ?>" />
            <label
                for="<?php echo esc_attr($field_id('enable_hover_bg')); ?>"><?php _e('Bật màu nền khi hover', 'msb-app-theme'); ?></label>
        </p>

        <p>
            <label
                for="<?php echo esc_attr($field_id('hover_scale')); ?>"><?php _e('Kích thước khi hover (scale):', 'msb-app-theme'); ?></label>
            <input class="small-text" id="<?php echo esc_attr($field_id('hover_scale')); ?>"
                name="<?php echo esc_attr($field_n('hover_scale')); ?>" type="number" step="0.01"
                value="<?php echo esc_attr($hover_scale); ?>">
        </p>
    </fieldset>

    <script type="text/javascript">
        (function ($) {
            $(function () {
                $('.msb-color-picker').wpColorPicker();

                var $wrap = $('#<?php echo esc_js($widget->get_field_id('icon')); ?>').closest('.msb-media-control');

                $wrap.find('.msb-media-select').off('click').on('click', function (e) {
                    e.preventDefault();

                    var frame = wp.media({
                        title: '<?php echo esc_js(__('Chọn icon', 'msb-app-theme')); ?>',
                        multiple: false,
                        library: { type: 'image' }
                    });

                    frame.on('select', function () {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $wrap.find('.msb-media-id').val(attachment.id);
                        var imgUrl = (attachment.sizes?.thumbnail?.url) ? attachment.sizes.thumbnail.url : attachment.url;
                        var imgHtml = '<img src="' + imgUrl + '" style="max-width:80px;max-height:80px;" />';
                        $wrap.find('.msb-media-preview').html(imgHtml);
                        $wrap.find('.msb-media-remove').show();
                    });

                    frame.open();
                });

                $wrap.find('.msb-media-remove').off('click').on('click', function (e) {
                    e.preventDefault();
                    $wrap.find('.msb-media-id').val('');
                    $wrap.find('.msb-media-preview').empty();
                    $(this).hide();
                });
            });
        })(jQuery);
    </script>
    <?php
}
