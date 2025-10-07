<?php

if (!defined('ABSPATH')) { exit; }

class MSB_WP_Icon_Box_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'msb_wp_icon_box',
            __('IconBox', 'msb-app-theme'),
            [
                'description'   => __('Icon Box (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('IconBox', 'msb-app-theme'),
            ]
        );
    }

    public function widget($args, $instance) {
        $icon_id   = isset($instance['icon']) ? intval($instance['icon']) : 0;
        $icon_size = isset($instance['icon_size']) ? intval($instance['icon_size']) : 40;
        $title     = isset($instance['title']) ? $instance['title'] : '';
        $url       = isset($instance['url']) ? $instance['url'] : '';
        $align     = isset($instance['align']) ? $instance['align'] : 'center';
        $text_color= isset($instance['text_color']) ? $instance['text_color'] : '#111827';

        echo $args['before_widget'];
        $tag = $url ? 'a' : 'div';
        $attrs = $url ? ' href="' . esc_url($url) . '"' : '';
        $classes = 'msb-icon-box align-' . esc_attr($align);
        $style   = 'color:' . esc_attr($text_color) .';'
                  . 'font-size: 14px;'
        ;
                  
        echo '<' . $tag . ' class="' . $classes . '" style="' . esc_attr($style) . '"' . $attrs . '>';

        echo '<div class="msb-icon-box__icon" style="width:' . intval($icon_size) . 'px;height:' . intval($icon_size) . 'px;">';
        if ($icon_id) {
            $src = wp_get_attachment_image_url($icon_id, 'full');
            if ($src) {
                echo '<img src="' . esc_url($src) . '" alt="" width="' . intval($icon_size) . '" height="' . intval($icon_size) . '" />';
            }
        }
        echo '</div>';

        if ($title) {
            echo '<div class="msb-icon-box__title">' . esc_html($title) . '</div>';
        }

        echo '</' . $tag . '>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title      = isset($instance['title']) ? $instance['title'] : '';
        $icon_id    = isset($instance['icon']) ? intval($instance['icon']) : 0; // ensure we keep saved icon
        $icon_size  = isset($instance['icon_size']) ? intval($instance['icon_size']) : 40;
        $url        = isset($instance['url']) ? $instance['url'] : '';
        $align      = isset($instance['align']) ? $instance['align'] : 'center';
        $text_color = isset($instance['text_color']) ? $instance['text_color'] : '#111827';

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
            <label for="<?php echo esc_attr($field_id('url')); ?>"><?php _e('Liên kết:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($field_id('url')); ?>" name="<?php echo esc_attr($field_n('url')); ?>" type="url" value="<?php echo esc_attr($url); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($field_id('align')); ?>"><?php _e('Căn lề:', 'msb-app-theme'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($field_id('align')); ?>" name="<?php echo esc_attr($field_n('align')); ?>">
                <option value="left" <?php selected($align,'left'); ?>><?php _e('Trái','msb-app-theme'); ?></option>
                <option value="center" <?php selected($align,'center'); ?>><?php _e('Giữa','msb-app-theme'); ?></option>
                <option value="right" <?php selected($align,'right'); ?>><?php _e('Phải','msb-app-theme'); ?></option>
            </select>
        </p>
        <div class="msb-media-control">
            <label><?php _e('Icon:', 'msb-app-theme'); ?></label>
            <div class="msb-media-preview" style="margin:6px 0;">
                <?php if ($icon_id && ($img = wp_get_attachment_image($icon_id, array(80,80)))) { echo $img; } ?>
            </div>
            <input type="hidden" class="msb-media-id" id="<?php echo esc_attr($field_id('icon')); ?>" name="<?php echo esc_attr($field_n('icon')); ?>" value="<?php echo esc_attr($icon_id); ?>" />
            <button type="button" class="button msb-media-select"><?php _e('Chọn ảnh', 'msb-app-theme'); ?></button>
            <button type="button" class="button msb-media-remove" style="margin-left:6px;<?php echo $icon_id ? '' : 'display:none;'; ?>"><?php _e('Xóa', 'msb-app-theme'); ?></button>
        </div>
        <script type="text/javascript">
        (function($){
            var $wrap = $('#<?php echo esc_js($field_id('icon')); ?>').closest('.msb-media-control');
            $wrap.find('.msb-media-select').off('click').on('click', function(e){
                e.preventDefault();
                var frame = wp.media({ title: '<?php echo esc_js(__('Chọn icon', 'msb-app-theme')); ?>', multiple: false, library: { type: 'image' } });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    $wrap.find('.msb-media-id').val(attachment.id);
                    var imgHtml = '<img src="'+attachment.sizes?.thumbnail?.url || attachment.url+'" style="max-width:80px;max-height:80px;" />';
                    $wrap.find('.msb-media-preview').html(imgHtml);
                    $wrap.find('.msb-media-remove').show();
                });
                frame.open();
            });
            $wrap.find('.msb-media-remove').off('click').on('click', function(e){
                e.preventDefault();
                $wrap.find('.msb-media-id').val('');
                $wrap.find('.msb-media-preview').empty();
                $(this).hide();
            });
        })(jQuery);
        </script>
        <p>
            <label for="<?php echo esc_attr($field_id('icon_size')); ?>"><?php _e('Kích thước icon (px):', 'msb-app-theme'); ?></label>
            <input class="small-text" id="<?php echo esc_attr($field_id('icon_size')); ?>" name="<?php echo esc_attr($field_n('icon_size')); ?>" type="number" value="<?php echo esc_attr($icon_size); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($field_id('text_color')); ?>"><?php _e('Màu chữ:', 'msb-app-theme'); ?></label>
            <input class="small-text" id="<?php echo esc_attr($field_id('text_color')); ?>" name="<?php echo esc_attr($field_n('text_color')); ?>" type="text" value="<?php echo esc_attr($text_color); ?>" placeholder="#111827">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $inst = [];
        $inst['title']      = sanitize_text_field($new_instance['title'] ?? '');
        $inst['url']        = esc_url_raw($new_instance['url'] ?? '');
        $inst['icon']       = absint($new_instance['icon'] ?? 0);
        $inst['icon_size']  = absint($new_instance['icon_size'] ?? 40);
        $inst['align']      = in_array(($new_instance['align'] ?? 'center'), array('left','center','right'), true) ? $new_instance['align'] : 'center';
        $inst['text_color'] = sanitize_text_field($new_instance['text_color'] ?? '#111827');
        return $inst;
    }
}

add_action('widgets_init', function(){
    register_widget('MSB_WP_Icon_Box_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});


