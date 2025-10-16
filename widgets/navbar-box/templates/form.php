<?php
/**
 * Navbar Box Widget Form
 */

function msb_navbar_box_form($instance, $widget) {
    $menu_items = isset($instance['menu_items']) ? (array)$instance['menu_items'] : [];
    $hover_text_color = isset($instance['hover_text_color']) ? $instance['hover_text_color'] : '#000000';
    $icon_id = isset($instance['icon']) ? intval($instance['icon']) : 0;
    $url_icon  = isset($instance['url_icon']) ? $instance['url_icon'] : '';
    $icon_size = isset($instance['icon_size']) ? intval($instance['icon_size']) : 40;
    $field_id = fn($k) => $widget->get_field_id($k);
    $field_n  = fn($k) => $widget->get_field_name($k);

    // Thêm lại: Enqueue media để wp.media hoạt động
    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');

    ?>
    <div class="menu-box-form">
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
          <label><?php _e('Url icon:', 'msb-app-theme'); ?></label>
          <input class="widefat" type="text" name="<?php echo esc_attr($field_n('url_icon')); ?>" value="<?php echo esc_attr($url_icon); ?>">
        </p>

        <p>
          <label for="<?php echo esc_attr($field_id('icon_size')); ?>"><?php _e('Kích thước icon (px):', 'msb-app-theme'); ?></label>
          <input class="small-text" id="<?php echo esc_attr($field_id('icon_size')); ?>"
              name="<?php echo esc_attr($field_n('icon_size')); ?>" type="number" value="<?php echo esc_attr($icon_size); ?>">
        </p>
        <p><strong><?php _e('Menu Items:', 'msb-app-theme'); ?></strong></p>
        <div class="menu-items-wrapper">
            <?php foreach ($menu_items as $index => $item): 
                $text = isset($item['text']) ? $item['text'] : '';
                $url  = isset($item['url']) ? $item['url'] : '';
            ?>
                <div class="menu-item" style="border:1px solid #ddd; padding:10px; margin-bottom:10px; border-radius:6px;">
                    <p><strong><?php echo sprintf(__('Item #%d', 'msb-app-theme'), $index + 1); ?></strong></p>
                    <p>
                      <label><?php _e('Text:', 'msb-app-theme'); ?></label>
                      <input class="widefat" type="text" name="<?php echo esc_attr($field_n('menu_items')); ?>[<?php echo $index; ?>][text]" value="<?php echo esc_attr($text); ?>">
                    </p>
                    <p>
                      <label><?php _e('URL:', 'msb-app-theme'); ?></label>
                      <input class="widefat" type="text" name="<?php echo esc_attr($field_n('menu_items')); ?>[<?php echo $index; ?>][url]" value="<?php echo esc_attr($url); ?>">
                    </p>
                    <button type="button" class="button remove-item"><?php _e('Xóa Item', 'msb-app-theme'); ?></button>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" class="button add-item"><?php _e('Thêm Item', 'msb-app-theme'); ?></button>

        <p>
          <label for="<?php echo esc_attr($field_id('hover_text_color')); ?>"><?php _e('Màu chữ khi hover:', 'msb-app-theme'); ?></label>
          <input class="msb-color-picker" id="<?php echo esc_attr($field_id('hover_text_color')); ?>"
            name="<?php echo esc_attr($field_n('hover_text_color')); ?>" type="text"
            value="<?php echo esc_attr($hover_text_color); ?>" data-default-color="#000000">
        </p>

        <script type="text/html" id="tmpl-menu-item-template">
            <div class="menu-item" style="border:1px solid #ddd; padding:10px; margin-bottom:10px; border-radius:6px;">
              <p><strong><?php _e('Item mới', 'msb-app-theme'); ?></strong></p>
              <p>
                <label><?php _e('Text:', 'msb-app-theme'); ?></label>
                <input class="widefat" type="text" name="<?php echo esc_attr($field_n('menu_items')); ?>[{{index}}][text]" value="">
              </p>
              <p>
                <label><?php _e('URL:', 'msb-app-theme'); ?></label>
                <input class="widefat" type="text" name="<?php echo esc_attr($field_n('menu_items')); ?>[{{index}}][url]" value="#">
              </p>
              <button type="button" class="button remove-item"><?php _e('Xóa Item', 'msb-app-theme'); ?></button>
            </div>
        </script>

        <script>
        (function($){
            $(document).ready(function(){
                // Initialize color picker
                $('.msb-color-picker').wpColorPicker();

                let $wrapper = $('.menu-items-wrapper');
                let itemIndex = <?php echo count($menu_items); ?>;

                // Add item
                $('.add-item').on('click', function(e){
                    e.preventDefault();
                    const template = $('#tmpl-menu-item-template').html().replace(/{{index}}/g, itemIndex);
                    $wrapper.append(template);
                    itemIndex++;
                });

                // Remove item
                $wrapper.on('click', '.remove-item', function(){
                    $(this).closest('.menu-item').remove();
                });

                // THÊM: JS cho media control (icon widget chính)
                var $mediaControl = $('.msb-media-control');

                // Open media frame
                $mediaControl.on('click', '.msb-media-select', function(e) {
                    e.preventDefault();
                    var $btn = $(this);
                    var frame = wp.media({
                        title: '<?php _e("Chọn icon", "msb-app-theme"); ?>',
                        button: { text: '<?php _e("Chọn", "msb-app-theme"); ?>' },
                        multiple: false,
                        library: { type: 'image' }  // Chỉ chọn ảnh
                    });

                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $btn.siblings('.msb-media-id').val(attachment.id);
                        $btn.siblings('.msb-media-preview').html('<img src="' + attachment.sizes.thumbnail.url + '" style="max-width:80px; height:auto;">');  // Dùng thumbnail preview
                        $btn.siblings('.msb-media-remove').show();
                    });

                    frame.open();
                });

                // Remove media
                $mediaControl.on('click', '.msb-media-remove', function(e) {
                    e.preventDefault();
                    $(this).siblings('.msb-media-id').val(0);
                    $(this).siblings('.msb-media-preview').html('');
                    $(this).hide();
                });
            });
        })(jQuery);
        </script>
    </div>
    <?php
}