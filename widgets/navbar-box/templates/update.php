<?php
/**
 * Menu Box Widget Update Logic
 */

function msb_navbar_box_update($new_instance, $old_instance) {
    $inst = [];

    // Màu chữ khi hover
    $inst['hover_text_color'] = sanitize_hex_color($new_instance['hover_text_color'] ?? '#0066cc');

    // Icon chính của widget (ID attachment)
    $inst['icon'] = !empty($new_instance['icon']) ? intval($new_instance['icon']) : 0;

    // URL cho icon (nếu icon có thể click)
    $inst['url_icon'] = !empty($new_instance['url_icon']) ? esc_url_raw($new_instance['url_icon']) : '';

    // Kích thước icon
    $inst['icon_size'] = !empty($new_instance['icon_size']) ? intval($new_instance['icon_size']) : 24;
    // Giới hạn kích thước hợp lý
    if ($inst['icon_size'] < 10) $inst['icon_size'] = 10;
    if ($inst['icon_size'] > 200) $inst['icon_size'] = 200;

    // Danh sách menu items (chỉ text và url)
    $inst['menu_items'] = [];

    if (!empty($new_instance['menu_items']) && is_array($new_instance['menu_items'])) {
        foreach ($new_instance['menu_items'] as $item) {
            // Lọc dữ liệu từng item
            $text = isset($item['text']) ? sanitize_text_field($item['text']) : '';
            $url  = isset($item['url']) ? esc_url_raw($item['url']) : '';

            // Bỏ qua item nếu không có text (URL có thể rỗng)
            if (empty($text)) {
                continue;
            }

            $inst['menu_items'][] = [
                'text' => $text,
                'url'  => $url,
            ];
        }
    }

    return $inst;
}