<?php
/**
 * Icon Box Widget Update Logic
 */

function msb_icon_box_update($new_instance, $old_instance) {
    $inst = [];
    $inst['title'] = sanitize_text_field($new_instance['title'] ?? '');
    $inst['url'] = esc_url_raw($new_instance['url'] ?? '');
    $inst['icon'] = absint($new_instance['icon'] ?? 0);
    $inst['icon_size'] = absint($new_instance['icon_size'] ?? 40);
    $inst['align'] = in_array(($new_instance['align'] ?? 'center'), array('left', 'center', 'right'), true) ? $new_instance['align'] : 'center';
    $inst['text_color'] = sanitize_text_field($new_instance['text_color'] ?? '#111827');
    $inst['icon_pos'] = in_array(($new_instance['icon_pos'] ?? 'top'), ['top', 'bottom', 'left', 'right'], true) ? $new_instance['icon_pos'] : 'top';
    $inst['hover_text_color'] = sanitize_hex_color($new_instance['hover_text_color'] ?? '#000000');
    $inst['hover_bg_color'] = sanitize_hex_color($new_instance['hover_bg_color'] ?? '#0b2545');
    $inst['enable_hover_bg'] = !empty($new_instance['enable_hover_bg']) ? 1 : 0;
    $inst['hover_scale'] = isset($new_instance['hover_scale']) ? floatval($new_instance['hover_scale']) : 1.05;
    return $inst;
}
