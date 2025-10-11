<?php
/**
 * Menu Select Box Widget
 */

  class MSB_Menu_Select_Box_Widget extends WP_Widget {

    public function __construct() {
      parent::__construct(
        'msb_menu_select_box',
        __('Menu Select Box', 'msb-app-theme'),
            array(
                'classname' => 'msb-menu-select-box-widget',
                'description' => __('Menu Select Box (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb')
            )
      );
    }

    public function widget($args, $instance) {
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : __('Chọn trang...', 'msb-app-theme');
        $modal_title = !empty($instance['modal_title']) ? $instance['modal_title'] : __('Đi đến trang', 'msb-app-theme');
        $menu_items = !empty($instance['menu_items']) ? $instance['menu_items'] : array();

        echo $args['before_widget'];
        ?>
        <div class="msb-menu-select-box">
            <div class="msb-dropdown-trigger">
                <div class="msb-dropdown-input"><?php echo esc_attr($placeholder); ?></div>
                <span class="msb-chevron-down">
                  <svg height="46.002px" id="Capa_1" style="enable-background:new 0 0 86 46.002;" version="1.1" viewBox="0 0 86 46.002" width="86px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M5.907,1.004c-1.352-1.338-3.542-1.338-4.894,0c-1.35,1.336-1.352,3.506,0,4.844l39.54,39.15  c1.352,1.338,3.542,1.338,4.894,0l39.54-39.15c1.351-1.338,1.352-3.506,0-4.844s-3.542-1.338-4.894-0.002L43,36.707L5.907,1.004z"/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/></svg></span>
            </div>
            
            <div class="msb-modal-overlay">
                <div class="msb-modal">
                    <div class="msb-modal-header">
                        <h3 class="msb-modal-title"><?php echo esc_html($modal_title); ?></h3>
                    </div>
                    <div class="msb-modal-content">
                        <div class="msb-menu-grid">
                            <?php 
                            if (!empty($menu_items) && is_array($menu_items)) : 
                                foreach ($menu_items as $index => $item) : 
                                    if (!empty($item['text']) && !empty($item['url'])) : ?>
                                        <a href="<?php echo esc_url($item['url']); ?>" class="msb-menu-item">
                                            <div class="msb-menu-icon">
                                                <?php if (!empty($item['icon'])) : ?>
                                                    <?php if (is_numeric($item['icon'])) : ?>
                                                        <?php $src = wp_get_attachment_image_url((int) $item['icon'], 'thumbnail'); ?>
                                                        <?php if ($src) : ?>
                                                            <img src="<?php echo esc_url($src); ?>" alt="" width="40" height="40" />
                                                        <?php endif; ?>
                                                    <?php elseif (strpos($item['icon'], 'dashicons-') === 0) : ?>
                                                        <span class="dashicons <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></span>
                                                    <?php else : ?>
                                                        <span class="msb-icon"><?php echo esc_html($item['icon']); ?></span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="msb-menu-text"><?php echo esc_html($item['text']); ?></div>
                                        </a>
                                    <?php endif; 
                                endforeach; 
                            else : ?>
                                <p class="msb-no-items">
                                    <?php _e('Chưa có menu items nào.', 'msb-app-theme'); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $placeholder = isset($instance['placeholder']) ? $instance['placeholder'] : '';
        $modal_title = isset($instance['modal_title']) ? $instance['modal_title'] : '';
        $menu_items = isset($instance['menu_items']) ? $instance['menu_items'] : array();
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('placeholder'); ?>"><?php _e('Placeholder text:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('placeholder'); ?>" name="<?php echo $this->get_field_name('placeholder'); ?>" type="text" value="<?php echo esc_attr($placeholder); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('modal_title'); ?>"><?php _e('Modal title:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('modal_title'); ?>" name="<?php echo $this->get_field_name('modal_title'); ?>" type="text" value="<?php echo esc_attr($modal_title); ?>">
        </p>
        
        <div class="msb-menu-items-admin" id="msb-menu-items-<?php echo esc_attr($this->id); ?>">
            <h4><?php _e('Menu Items:', 'msb-app-theme'); ?></h4>
            <div class="msb-items-container" data-base-name="<?php echo esc_attr($this->get_field_name('menu_items')); ?>">
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
                                <input type="hidden" class="msb-media-id" name="<?php echo $this->get_field_name('menu_items'); ?>[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($item['icon']); ?>" />
                                <button type="button" class="button msb-media-select"><?php _e('Chọn icon', 'msb-app-theme'); ?></button>
                                <button type="button" class="button msb-media-remove" style="margin-left:6px;<?php echo (!empty($item['icon']) && is_numeric($item['icon'])) ? '' : 'display:none;'; ?>"><?php _e('Xóa', 'msb-app-theme'); ?></button>
                                <p style="margin-top:6px; color:#666; font-size:12px;"><?php _e('Bạn cũng có thể nhập class Dashicons (ví dụ: dashicons-admin-home) thay cho icon ảnh.', 'msb-app-theme'); ?></p>
                            </div>
                            <p>
                                <label><?php _e('Text:', 'msb-app-theme'); ?></label>
                                <input type="text" name="<?php echo $this->get_field_name('menu_items'); ?>[<?php echo $index; ?>][text]" value="<?php echo esc_attr($item['text']); ?>" placeholder="<?php _e('Trang chủ', 'msb-app-theme'); ?>">
                            </p>
                            <p>
                                <label><?php _e('URL:', 'msb-app-theme'); ?></label>
                                <input type="url" name="<?php echo $this->get_field_name('menu_items'); ?>[<?php echo $index; ?>][url]" value="<?php echo esc_attr($item['url']); ?>" placeholder="https://example.com">
                            </p>
                            <button type="button" class="msb-remove-item"><?php _e('Xóa', 'msb-app-theme'); ?></button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="msb-add-item"><?php _e('Thêm Item', 'msb-app-theme'); ?></button>
        </div>

        <?php // JS đã tách sang widgets/menu-select-box/js/menu-select-box-admin.js ?>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['placeholder'] = sanitize_text_field($new_instance['placeholder']);
        $instance['modal_title'] = sanitize_text_field($new_instance['modal_title']);
        
        // Sanitize menu items
        $menu_items = array();
        if (!empty($new_instance['menu_items']) && is_array($new_instance['menu_items'])) {
            foreach ($new_instance['menu_items'] as $item) {
                if (!empty($item['text']) && !empty($item['url'])) {
                    $menu_items[] = array(
                        'icon' => sanitize_text_field($item['icon']),
                        'text' => sanitize_text_field($item['text']),
                        'url' => esc_url_raw($item['url'])
                    );
                }
            }
        }
        $instance['menu_items'] = $menu_items;
        
        return $instance;
    }
}

// Register widget
function msb_register_menu_select_box_widget() {
    register_widget('MSB_Menu_Select_Box_Widget');
}
add_action('widgets_init', 'msb_register_menu_select_box_widget');

// Enqueue admin JS for widget form
add_action('admin_enqueue_scripts', function($hook){
    if ($hook !== 'widgets.php' && $hook !== 'customize.php') return;
    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script(
        'msb-menu-select-box-admin',
        get_template_directory_uri() . '/widgets/menu-select-box/js/menu-select-box-admin.js',
        array('jquery'),
        filemtime(get_template_directory() . '/widgets/menu-select-box/js/menu-select-box-admin.js'),
        true
    );
});