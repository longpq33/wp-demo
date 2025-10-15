<?php
/**
 * Menu Box Widget Update Logic
 */

function msb_menu_box_update($new_instance, $old_instance) {
    $inst = [];

    // Màu nền hover
    $inst['hover_bg_color'] = sanitize_hex_color($new_instance['hover_bg_color'] ?? '#0b2545');
    $inst['hover_text_color'] = sanitize_hex_color($new_instance['hover_text_color'] ?? '#000000');

    // Danh sách menu items
    $inst['menu_items'] = [];

    if (!empty($new_instance['menu_items']) && is_array($new_instance['menu_items'])) {
        foreach ($new_instance['menu_items'] as $item) {
            // Lọc dữ liệu từng item
            $text = isset($item['text']) ? sanitize_text_field($item['text']) : '';
            $url  = isset($item['url']) ? esc_url_raw($item['url']) : '';
            $icon = isset($item['icon']) ? esc_url_raw($item['icon']) : '';

            // Bỏ qua item trống (nếu user không nhập gì)
            if (empty($text) && empty($url) && empty($icon)) {
                continue;
            }

            $inst['menu_items'][] = [
                'text' => $text,
                'url'  => $url,
                'icon' => $icon,
            ];
        }
    }

    return $inst;
}