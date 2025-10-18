<?php
/**
 * Menu Select Box - Admin form
 */

 
if (!function_exists('msb_menu_select_box_form')) {
    function msb_menu_select_box_form($instance, $widget) {
        $placeholder = isset($instance['placeholder']) ? $instance['placeholder'] : '';
        $modal_title = isset($instance['modal_title']) ? $instance['modal_title'] : '';
        $menu_items = isset($instance['menu_items']) ? $instance['menu_items'] : array();
        ?>
        <div class="msb-menu-select-box-container">
          <div class="msb-menu-select-box-form">
          <h3><?php _e('Menu Select Box', 'msb-app-theme'); ?></h3>
          <p>
              <label for="<?php echo $widget->get_field_id('placeholder'); ?>"><?php _e('Placeholder text:', 'msb-app-theme'); ?></label>
              <input class="widefat" id="<?php echo $widget->get_field_id('placeholder'); ?>" name="<?php echo $widget->get_field_name('placeholder'); ?>" type="text" value="<?php echo esc_attr($placeholder); ?>">
          </p>
          
          <p>
              <label for="<?php echo $widget->get_field_id('modal_title'); ?>"><?php _e('Modal title:', 'msb-app-theme'); ?></label>
              <input class="widefat" id="<?php echo $widget->get_field_id('modal_title'); ?>" name="<?php echo $widget->get_field_name('modal_title'); ?>" type="text" value="<?php echo esc_attr($modal_title); ?>">
          </p>
          
        <div class="msb-menu-items-admin" id="msb-menu-items-<?php echo esc_attr($widget->id); ?>">
              <h4><?php _e('Menu Items:', 'msb-app-theme'); ?></h4>
              <div class="msb-items-container" data-base-name="<?php echo esc_attr($widget->get_field_name('menu_items')); ?>">
                  <?php if (!empty($menu_items)) : ?>
                      <?php foreach ($menu_items as $index => $item) : ?>
                          <div class="msb-item-row">
                              <div class="msb-media-control">
                                  <label><?php _e('Icon:', 'msb-app-theme'); ?></label>
                                  <div class="msb-media-preview" style="margin:6px 0;">
                                      <?php if (!empty($item['icon']) && is_numeric($item['icon'])) {
                                          $img = wp_get_attachment_image(intval($item['icon']), array(48, 48));
                                          if ($img) echo $img;
                                      } ?>
                                  </div>
                                  <input type="hidden" class="msb-media-id" name="<?php echo $widget->get_field_name('menu_items'); ?>[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($item['icon']); ?>" />
                                  <button type="button" class="button msb-media-select"><?php _e('Chọn icon', 'msb-app-theme'); ?></button>
                                  <button type="button" class="button msb-media-remove" style="margin-left:6px;<?php echo (!empty($item['icon']) && is_numeric($item['icon'])) ? '' : 'display:none;'; ?>"><?php _e('Xóa', 'msb-app-theme'); ?></button>
                                  <p style="margin-top:6px; color:#666; font-size:12px;"><?php _e('Bạn cũng có thể nhập class Dashicons (ví dụ: dashicons-admin-home) thay cho icon ảnh.', 'msb-app-theme'); ?></p>
                              </div>
                              <p>
                                  <label><?php _e('Text:', 'msb-app-theme'); ?></label>
                                  <input type="text" name="<?php echo $widget->get_field_name('menu_items'); ?>[<?php echo $index; ?>][text]" value="<?php echo esc_attr($item['text']); ?>" placeholder="<?php _e('Trang chủ', 'msb-app-theme'); ?>">
                              </p>
                              <p>
                                  <label><?php _e('URL:', 'msb-app-theme'); ?></label>
                                  <input type="url" name="<?php echo $widget->get_field_name('menu_items'); ?>[<?php echo $index; ?>][url]" value="<?php echo esc_attr($item['url']); ?>" placeholder="https://example.com">
                              </p>
                              <button type="button" class="msb-remove-item"><?php _e('Xóa', 'msb-app-theme'); ?></button>
                          </div>
                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
              <button type="button" class="msb-add-item"><?php _e('Thêm Item', 'msb-app-theme'); ?></button>
            </div>
          </div>
          
        </div>

        <hr>
        <div class="msb-tabs-admin" id="msb-tabs-<?php echo esc_attr($widget->id); ?>">
            <h4><?php _e('Tabs:', 'msb-app-theme'); ?></h4>
            <?php $tabs = isset($instance['tabs']) && is_array($instance['tabs']) ? $instance['tabs'] : array(); ?>
            <div class="msb-tabs-container" data-base-name="<?php echo esc_attr($widget->get_field_name('tabs')); ?>">
                <?php if (!empty($tabs)) : ?>
                    <?php foreach ($tabs as $tIndex => $tab) : ?>
                        <div class="msb-tab-row">
                            <p>
                                <label><?php _e('Tên tab:', 'msb-app-theme'); ?></label><br />
                                <input type="text" name="<?php echo $widget->get_field_name('tabs'); ?>[<?php echo $tIndex; ?>][title]" value="<?php echo esc_attr($tab['title'] ?? ''); ?>" placeholder="<?php _e('Cá nhân', 'msb-app-theme'); ?>">
                            </p>
                            <p>
                                <label><?php _e('Link:', 'msb-app-theme'); ?></label><br />
                                <input type="url" name="<?php echo $widget->get_field_name('tabs'); ?>[<?php echo $tIndex; ?>][url]" value="<?php echo esc_attr($tab['url'] ?? ''); ?>" placeholder="https://example.com">
                            </p>
                            <button type="button" class="button msb-remove-tab"><?php _e('Xóa', 'msb-app-theme'); ?></button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="button msb-add-tab"><?php _e('Thêm Tab', 'msb-app-theme'); ?></button>
        </div>
       
        <script>
        jQuery(document).ready(function($) {
            var $wrap = $('#msb-menu-items-<?php echo esc_js($widget->id); ?>');
            if (!$wrap.length) return;

            var $container = $wrap.find('.msb-items-container');
            var baseName = $container.data('base-name');
            var itemIndex = $container.find('.msb-item-row').length;

            if (typeof wp !== 'undefined' && wp.media && typeof wp_enqueue_media === 'function') {
                wp_enqueue_media();
            }

            $wrap.on('click', '.msb-add-item', function(e) {
                e.preventDefault();
                var html = ''
                    + '<div class="msb-item-row">'
                    +   '<div class="msb-media-control">'
                    +     '<label><?php echo esc_js(__('Icon:', 'msb-app-theme')); ?></label>'
                    +     '<div class="msb-media-preview" style="margin:6px 0;"></div>'
                    +     '<input type="hidden" class="msb-media-id" name="' + baseName + '[' + itemIndex + '][icon]" value="">'
                    +     '<button type="button" class="button msb-media-select"><?php echo esc_js(__('Chọn icon', 'msb-app-theme')); ?></button>'
                    +     '<button type="button" class="button msb-media-remove" style="margin-left:6px; display:none;"><?php echo esc_js(__('Xóa', 'msb-app-theme')); ?></button>'
                    +     '<p style="margin-top:6px; color:#666; font-size:12px;"><?php echo esc_js(__('Hoặc nhập class Dashicons vào ô Hidden qua Inspect nếu cần.', 'msb-app-theme')); ?></p>'
                    +   '</div>'
                    +   '<p><label><?php echo esc_js(__('Text:', 'msb-app-theme')); ?></label>'
                    +   '<input type="text" name="' + baseName + '[' + itemIndex + '][text]" placeholder="<?php echo esc_js(__('Trang chủ', 'msb-app-theme')); ?>"></p>'
                    +   '<p><label><?php echo esc_js(__('URL:', 'msb-app-theme')); ?></label>'
                    +   '<input type="url" name="' + baseName + '[' + itemIndex + '][url]" placeholder="https://example.com"></p>'
                    +   '<button type="button" class="msb-remove-item"><?php echo esc_js(__('Xóa', 'msb-app-theme')); ?></button>'
                    + '</div>';
                $container.append(html);
                itemIndex++;
            });

            // Media select handlers (delegated)
            $wrap.on('click', '.msb-media-select', function(e) {
                e.preventDefault();
                var $row = $(this).closest('.msb-item-row');
                var frame = wp.media({ title: '<?php echo esc_js(__('Chọn icon', 'msb-app-theme')); ?>', multiple: false, library: { type: 'image' } });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $row.find('.msb-media-id').val(attachment.id);
                    var imgUrl = (attachment.sizes && attachment.sizes.thumbnail) ? attachment.sizes.thumbnail.url : attachment.url;
                    $row.find('.msb-media-preview').html('<img src="' + imgUrl + '" style="max-width:48px;max-height:48px;" />');
                    $row.find('.msb-media-remove').show();
                });
                frame.open();
            });

            $wrap.on('click', '.msb-media-remove', function(e) {
                e.preventDefault();
                var $row = $(this).closest('.msb-item-row');
                $row.find('.msb-media-id').val('');
                $row.find('.msb-media-preview').empty();
                $(this).hide();
            });

            $wrap.on('click', '.msb-remove-item', function(e) {
                e.preventDefault();
                $(this).closest('.msb-item-row').remove();
            });
        });
        </script>
        <script>
        jQuery(document).ready(function($){
            var $tabsWrap = $('#msb-tabs-<?php echo esc_js($widget->id); ?>');
            if (!$tabsWrap.length) return;
            var $container = $tabsWrap.find('.msb-tabs-container');
            var baseName = $container.data('base-name');
            var tIndex = $container.find('.msb-tab-row').length;

            $tabsWrap.on('click', '.msb-add-tab', function(e){
                e.preventDefault();
                var html = ''
                  + '<div class="msb-tab-row">'
                  +   '<p><label><?php echo esc_js(__('Tên tab:', 'msb-app-theme')); ?></label>'
                  +   '<input type="text" name="' + baseName + '[' + tIndex + '][title]" placeholder="<?php echo esc_js(__('Cá nhân', 'msb-app-theme')); ?>"></p>'
                  +   '<p><label><?php echo esc_js(__('Link:', 'msb-app-theme')); ?></label>'
                  +   '<input type="url" name="' + baseName + '[' + tIndex + '][url]" placeholder="https://example.com"></p>'
                  +   '<button type="button" class="button msb-remove-tab"><?php echo esc_js(__('Xóa', 'msb-app-theme')); ?></button>'
                  + '</div>';
                $container.append(html);
                tIndex++;
            });

            $tabsWrap.on('click', '.msb-remove-tab', function(e){
                e.preventDefault();
                $(this).closest('.msb-tab-row').remove();
            });
        });
        </script>
        <?php
    }
}


