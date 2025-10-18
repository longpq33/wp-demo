<?php
/**
 * Menu Select Box - Frontend render function
 */

if (!function_exists('msb_menu_select_box_widget')) {
    function msb_menu_select_box_widget($args, $instance) {
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : __('Chọn trang...', 'msb-app-theme');
        $modal_title = !empty($instance['modal_title']) ? $instance['modal_title'] : __('Đi đến trang', 'msb-app-theme');
        $menu_items = !empty($instance['menu_items']) ? $instance['menu_items'] : array();
        $tabs = !empty($instance['tabs']) && is_array($instance['tabs']) ? $instance['tabs'] : array();

        // Debug output
        if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
            echo '<!-- MSB Debug: menu_items = ' . esc_html(print_r($menu_items, true)) . ' -->';
        }

        echo $args['before_widget'];
        ?>
        <div class="msb-menu-select-box">
            <div class="msb-dropdown-trigger-container">
                <div class="msb-dropdown-trigger">
                    <div class="msb-dropdown-input"><?php echo esc_attr($placeholder); ?></div>
                    <span class="msb-chevron-down" aria-hidden="true">
                    <svg height="46.002px" viewBox="0 0 86 46.002" width="86px" xmlns="http://www.w3.org/2000/svg"><path d="M5.907,1.004c-1.352-1.338-3.542-1.338-4.894,0c-1.35,1.336-1.352,3.506,0,4.844l39.54,39.15  c1.352,1.338,3.542,1.338,4.894,0l39.54-39.15c1.351-1.338,1.352-3.506,0-4.844s-3.542-1.338-4.894-0.002L43,36.707L5.907,1.004z"/></svg>
                    </span>
                </div>
                <?php if (!empty($tabs)) : ?>
                <nav class="msb-msb-tabs" aria-label="Menu Select Tabs">
                    <?php foreach ($tabs as $tab) : ?>
                        <a class="msb-msb-tab-link" href="<?php echo esc_url($tab['url']); ?>"><?php echo esc_html($tab['title']); ?></a>
                    <?php endforeach; ?>
                </nav>
                <?php endif; ?>
            </div>
            
            
            <div class="msb-modal-overlay">
                <div class="msb-modal">
                    <div class="msb-modal-header">
                        <h3 class="msb-modal-title"><?php echo esc_html($modal_title); ?></h3>
                    </div>
                    <div class="msb-modal-content">
                        <div class="msb-menu-grid">
                            <?php if (!empty($menu_items) && is_array($menu_items)) : ?>
                                <?php foreach ($menu_items as $index => $item) : ?>
                                    <?php if (!empty($item['text']) && !empty($item['url'])) : ?>
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
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="msb-no-items"><?php _e('Chưa có menu items nào.', 'msb-app-theme'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }
}


