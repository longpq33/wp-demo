<?php
/**
 * Image Box Widget Update Logic
 */

function msb_image_box_update($new_instance, $old_instance) {
    $inst = [];
    $inst['title']        = sanitize_text_field($new_instance['title'] ?? '');
    $inst['button_label'] = sanitize_text_field($new_instance['button_label'] ?? '');
    $inst['button_url']   = esc_url_raw($new_instance['button_url'] ?? '');
    $inst['button_color'] = sanitize_hex_color($new_instance['button_color'] ?? '');
    $inst['image_id']     = absint($new_instance['image_id'] ?? 0);
    return $inst;
}


