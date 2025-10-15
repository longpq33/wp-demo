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
   return $inst;
}