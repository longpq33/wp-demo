<?php
/**
 * Menu Box Widget Form
 */

function msb_menu_icon_box_form($instance, $widget) {
    $menu_items = isset($instance['menu_items']) ? (array)$instance['menu_items'] : [];
    $hover_bg_color = isset($instance['hover_bg_color']) ? $instance['hover_bg_color'] : '#0b2545';
    $hover_text_color = isset($instance['hover_text_color']) ? $instance['hover_text_color'] : '#000000';
    $field_id = fn($k) => $widget->get_field_id($k);
    $field_n  = fn($k) => $widget->get_field_name($k);

    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');

    ?>
    <div class="menu-box-form">
        <p>
            <label for="<?php echo esc_attr($field_id('hover_bg_color')); ?>"><?php _e('Màu nền khi hover:', 'msb-app-theme'); ?></label>
            <input class="msb-color-picker" id="<?php echo esc_attr($field_id('hover_bg_color')); ?>"
                name="<?php echo esc_attr($field_n('hover_bg_color')); ?>" type="text"
                value="<?php echo esc_attr($hover_bg_color); ?>" data-default-color="#0b2545">
        </p>

        <p>
            <label
                for="<?php echo esc_attr($field_id('hover_text_color')); ?>"><?php _e('Màu chữ khi hover:', 'msb-app-theme'); ?></label>
            <input class="msb-color-picker" id="<?php echo esc_attr($field_id('hover_text_color')); ?>"
                name="<?php echo esc_attr($field_n('hover_text_color')); ?>" type="text"
                value="<?php echo esc_attr($hover_text_color); ?>" data-default-color="#000000">
        </p>

        <p><strong><?php _e('Menu Items:', 'msb-app-theme'); ?></strong></p>
        <div class="menu-items-wrapper">
            <?php foreach ($menu_items as $index => $item): 
                $icon = isset($item['icon']) ? $item['icon'] : '';
                $text = isset($item['text']) ? $item['text'] : '';
                $url  = isset($item['url']) ? $item['url'] : '';
            ?>
                <div class="menu-item" style="border:1px solid #ddd; padding:10px; margin-bottom:10px; border-radius:6px;">
                    <p><strong><?php echo sprintf(__('Item #%d', 'msb-app-theme'), $index + 1); ?></strong></p>
                    <p>
                        <label><?php _e('Icon:', 'msb-app-theme'); ?></label><br>
                        <img src="<?php echo esc_url($icon); ?>" class="icon-preview" style="max-width:50px; height:auto; display:block; margin-bottom:5px;">
                        <input type="hidden" name="<?php echo esc_attr($field_n('menu_items')); ?>[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($icon); ?>" class="icon-input">
                        <button type="button" class="button select-icon"><?php _e('Chọn icon', 'msb-app-theme'); ?></button>
                        <button type="button" class="button remove-icon"><?php _e('Xóa', 'msb-app-theme'); ?></button>
                    </p>
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

        <script type="text/html" id="tmpl-menu-item-template">
            <div class="menu-item" style="border:1px solid #ddd; padding:10px; margin-bottom:10px; border-radius:6px;">
                <p><strong><?php _e('Item mới', 'msb-app-theme'); ?></strong></p>
                <p>
                    <label><?php _e('Icon:', 'msb-app-theme'); ?></label><br>
                    <img src="" class="icon-preview" style="max-width:50px; height:auto; display:block; margin-bottom:5px;">
                    <input type="hidden" name="<?php echo esc_attr($field_n('menu_items')); ?>[{{index}}][icon]" value="" class="icon-input">
                    <button type="button" class="button select-icon"><?php _e('Chọn icon', 'msb-app-theme'); ?></button>
                    <button type="button" class="button remove-icon"><?php _e('Xóa', 'msb-app-theme'); ?></button>
                </p>
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

                // Select icon
                $wrapper.on('click', '.select-icon', function(e){
                    e.preventDefault();
                    const $btn = $(this);
                    const frame = wp.media({
                        title: '<?php _e("Chọn icon", "msb-app-theme"); ?>',
                        button: { text: '<?php _e("Chọn", "msb-app-theme"); ?>' },
                        multiple: false
                    });
                    frame.on('select', function(){
                        const attachment = frame.state().get('selection').first().toJSON();
                        $btn.siblings('.icon-preview').attr('src', attachment.url);
                        $btn.siblings('.icon-input').val(attachment.url);
                    });
                    frame.open();
                });

                // Remove icon
                $wrapper.on('click', '.remove-icon', function(){
                    $(this).siblings('.icon-preview').attr('src', '');
                    $(this).siblings('.icon-input').val('');
                });
            });
        })(jQuery);
        </script>
    </div>
    <?php
}