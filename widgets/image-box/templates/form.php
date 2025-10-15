<?php
/**
 * Image Box Widget Form
 */

function msb_image_box_form($instance, $widget) {
    $title        = isset($instance['title']) ? $instance['title'] : '';
    $button_label = isset($instance['button_label']) ? $instance['button_label'] : '';
    $button_url   = isset($instance['button_url']) ? $instance['button_url'] : '';
    $button_color = isset($instance['button_color']) ? $instance['button_color'] : '';
    $image_id     = isset($instance['image_id']) ? intval($instance['image_id']) : 0;

    $field_id = function($k) use ($widget) { return $widget->get_field_id($k); };
    $field_n  = function($k) use ($widget) { return $widget->get_field_name($k); };

    if ( function_exists('wp_enqueue_media') ) { wp_enqueue_media(); }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    // Enqueue admin js for media picker & color picker init
    $admin_js_path = get_template_directory() . '/widgets/image-box/js/admin.js';
    $admin_js_url  = get_template_directory_uri() . '/widgets/image-box/js/admin.js';
    if ( file_exists($admin_js_path) ) {
        wp_enqueue_script(
            'msb-image-box-admin',
            $admin_js_url,
            array('jquery','wp-color-picker','media-editor','media-models','media-views'),
            filemtime($admin_js_path),
            true
        );
        wp_localize_script('msb-image-box-admin', 'msbImageBoxAdmin', array(
            'chooseTitle'  => __('Chọn ảnh', 'msb-app-theme'),
            'chooseButton' => __('Chọn', 'msb-app-theme'),
        ));
    }
    ?>
    <p>
        <label for="<?php echo esc_attr($field_id('title')); ?>"><?php _e('Tiêu đề:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('title')); ?>" name="<?php echo esc_attr($field_n('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('button_label')); ?>"><?php _e('Tên nút (button):', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('button_label')); ?>" name="<?php echo esc_attr($field_n('button_label')); ?>" type="text" value="<?php echo esc_attr($button_label); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('button_url')); ?>"><?php _e('Link nút:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('button_url')); ?>" name="<?php echo esc_attr($field_n('button_url')); ?>" type="url" value="<?php echo esc_attr($button_url); ?>" placeholder="https://">
    </p>
    <p>
        <label><?php _e('Màu nút (Button color):', 'msb-app-theme'); ?></label>
        <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('button_color')); ?>" type="text" value="<?php echo esc_attr($button_color); ?>">
    </p>
    <div class="msb-media-control">
        <label><?php _e('Ảnh nền:', 'msb-app-theme'); ?></label>
        <div class="msb-media-preview" style="margin:6px 0;">
            <?php if ($image_id && ($img = wp_get_attachment_image($image_id, array(300, 180)))) { echo $img; } ?>
        </div>
        <input type="hidden" class="msb-media-id" id="<?php echo esc_attr($field_id('image_id')); ?>" name="<?php echo esc_attr($field_n('image_id')); ?>" value="<?php echo esc_attr($image_id); ?>" />
        <button type="button" class="button msb-media-select"><?php _e('Chọn ảnh', 'msb-app-theme'); ?></button>
        <button type="button" class="button msb-media-remove" style="margin-left:6px;<?php echo $image_id ? '' : 'display:none;'; ?>"><?php _e('Xóa', 'msb-app-theme'); ?></button>
    </div>


    <script type="text/javascript">
        (function ($) {
            $(function () {
                $('.msb-color-field').wpColorPicker();
                var $wrap = $('#<?php echo esc_js($widget->get_field_id('image_id')); ?>').closest('.msb-media-control');

              $wrap.find('.msb-media-select').off('click').on('click', function (e) {
                  e.preventDefault();

                  var frame = wp.media({
                      title: '<?php echo esc_js(__('Chọn image', 'msb-app-theme')); ?>',
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
            })
        })(jQuery);
    </script>
    <?php
}




