<?php
/**
 * Heading Widget Update Logic
 */

function msb_heading_update($new_instance, $old_instance) {
    $inst = [];
    $inst['title']      = sanitize_text_field($new_instance['title'] ?? '');
    $inst['subtitle']   = sanitize_text_field($new_instance['subtitle'] ?? '');
    $inst['description'] = sanitize_text_field($new_instance['description'] ?? '');
    // styles
    $inst['title_fs'] = sanitize_text_field($new_instance['title_fs'] ?? '');
    $inst['title_cl'] = sanitize_text_field($new_instance['title_cl'] ?? '');
    $inst['title_fw'] = sanitize_text_field($new_instance['title_fw'] ?? '');
    $inst['subtitle_fs'] = sanitize_text_field($new_instance['subtitle_fs'] ?? '');
    $inst['subtitle_cl'] = sanitize_text_field($new_instance['subtitle_cl'] ?? '');
    $inst['subtitle_fw'] = sanitize_text_field($new_instance['subtitle_fw'] ?? '');
    $inst['desc_fs'] = sanitize_text_field($new_instance['desc_fs'] ?? '');
    $inst['desc_cl'] = sanitize_text_field($new_instance['desc_cl'] ?? '');
    $inst['desc_fw'] = sanitize_text_field($new_instance['desc_fw'] ?? '');
    // More button
    $inst['show_more_btn'] = !empty($new_instance['show_more_btn']) ? 1 : 0;
    $inst['more_btn_text'] = sanitize_text_field($new_instance['more_btn_text'] ?? 'Xem thêm');
    $inst['more_btn_url'] = esc_url_raw($new_instance['more_btn_url'] ?? '');
   return $inst;
}