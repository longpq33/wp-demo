<?php
/**
 * Menu Icon Box Widget Display Logic
 */

function msb_menu_box_widget($args, $instance) {
    echo $args['before_widget'];

    // Lấy danh sách item
    $menu_items = isset($instance['menu_items']) && is_array($instance['menu_items'])
        ? $instance['menu_items']
        : [];

    // Lấy màu hover
    $hover_bg_color = !empty($instance['hover_bg_color'])
        ? esc_attr($instance['hover_bg_color'])
        : '#0b2545';
    
    $hover_text_color = !empty($instance['hover_text_color'])
        ? esc_attr($instance['hover_text_color'])
        : '#ffffff';

    $fixed_bottom = !empty($instance['fixed_bottom']) ? 1 : 0;

    if (!empty($menu_items)) {
        // Tạo unique ID cho widget này
        $unique_id = 'msb-menu-box-' . uniqid();
        
        // Chuyển hex color sang rgba để dùng cho box-shadow
        $hex = str_replace('#', '', $hover_bg_color);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Gắn inline style với selector cụ thể hơn
        echo '<style>
            #' . $unique_id . ' .msb-menu-box__link:hover {
                background: ' . $hover_bg_color . ' !important;
                box-shadow: 0 4px 12px rgba(' . $r . ', ' . $g . ', ' . $b . ', 0.15),
                            0 2px 6px rgba(' . $r . ', ' . $g . ', ' . $b . ', 0.1) !important;
            }
            #' . $unique_id . ' .msb-menu-box__link:hover .msb-menu-box__text {
                color: ' . $hover_text_color . ' !important;
            }
           
        </style>';

        $class = $fixed_bottom ? 'fixed-bottom-navbar' : '';

        echo '<div id="' . $unique_id . '" class="msb-menu-box ' . $class . '">';

        foreach ($menu_items as $item) {
            $text = !empty($item['text']) ? esc_html($item['text']) : '';
            $url  = !empty($item['url']) ? esc_url($item['url']) : '#';
            $icon = !empty($item['icon']) ? esc_url($item['icon']) : '';

            echo '<div class="msb-menu-box__item">';
            echo '<a href="' . $url . '" class="msb-menu-box__link">';

            if ($icon) {
                echo '<div class="msb-menu-box__icon"><img src="' . $icon . '" alt="' . $text . '"></div>';
            }

            if ($text) {
                echo '<div class="msb-menu-box__text">' . $text . '</div>';
            }

            echo '</a>';
            echo '</div>';
        }

        echo '</div>';
    }

    echo $args['after_widget'];
}