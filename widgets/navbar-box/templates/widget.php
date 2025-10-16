<?php
/**
 * Navbar Box Widget Display Logic
 */

function msb_navbar_box_widget($args, $instance) {
    echo $args['before_widget'];

    // Lấy danh sách item (chỉ text và url)
    $menu_items = isset($instance['menu_items']) && is_array($instance['menu_items'])
        ? $instance['menu_items']
        : [];
    
    // Hover text color
    $hover_text_color = !empty($instance['hover_text_color'])
        ? esc_attr($instance['hover_text_color'])
        : '#0066cc';

    // Icon chính của widget (ID attachment)
    $icon_id = !empty($instance['icon']) ? intval($instance['icon']) : 0;
    // URL cho icon
    $url_icon = !empty($instance['url_icon']) ? esc_url($instance['url_icon']) : '';
    // Kích thước icon
    $icon_size = !empty($instance['icon_size']) ? intval($instance['icon_size']) : 24;

    // Tạo unique ID cho widget này
    $unique_id = 'msb-navbar-box-' . uniqid();
    
    echo '<style>
        #' . $unique_id . ' .msb-navbar-box__link:hover .msb-navbar-box__text {
            color: ' . $hover_text_color . ' !important;
        }
        
        #' . $unique_id . ' .msb-navbar-box__link:not(.msb-navbar-box__link--disabled):hover {
            cursor: pointer;
        }
        
        #' . $unique_id . ' .msb-navbar-box__link--disabled {
            cursor: default;
        }
        
        #' . $unique_id . ' .msb-widget-icon {
            width: ' . esc_attr($icon_size) . 'px;
            height: ' . esc_attr($icon_size) . 'px;
        }
        
        #' . $unique_id . ' .msb-widget-icon-link {
            display: inline-block;
        }
        
        #' . $unique_id . ' .msb-widget-icon-link:hover {
            cursor: pointer;
        }
    </style>';

    echo '<div id="' . $unique_id . '" class="msb-navbar-box">';

    // Hiển thị icon chính của widget (nếu có)
    if ($icon_id) {
        $icon_img = wp_get_attachment_image($icon_id, array($icon_size, $icon_size), false, array('class' => 'msb-widget-icon'));
        if ($icon_img) {
            echo '<div class="msb-widget-icon-wrapper">';
            
            // Kiểm tra có URL cho icon không
            $has_icon_url = !empty($url_icon) && $url_icon !== '#';
            
            if ($has_icon_url) {
                // Icon có link - wrap trong thẻ <a>
                echo '<a href="' . $url_icon . '" class="msb-widget-icon-link">' . $icon_img . '</a>';
            } else {
                // Icon không có link - chỉ hiển thị icon
                echo $icon_img;
            }
            
            echo '</div>';
        }
    }

    // Menu items
    if (!empty($menu_items)) {
        foreach ($menu_items as $item) {
            $text = !empty($item['text']) ? esc_html($item['text']) : '';
            $url  = !empty($item['url']) ? esc_url($item['url']) : '';

            if (empty($text)) {
                continue;
            }

            // Kiểm tra URL có hợp lệ không (không phải rỗng và không phải '#')
            $has_valid_url = !empty($url) && $url !== '#';
            $link_class = $has_valid_url ? 'msb-navbar-box__link' : 'msb-navbar-box__link msb-navbar-box__link--disabled';
            $href = $has_valid_url ? $url : '#';

            echo '<div class="msb-navbar-box__item">';
            echo '<a href="' . $href . '" class="' . $link_class . '">';
            echo '<div class="msb-navbar-box__text">' . $text . '</div>';
            echo '</a>';
            echo '</div>';
        }
    }

    echo '</div>';

    echo $args['after_widget'];
}