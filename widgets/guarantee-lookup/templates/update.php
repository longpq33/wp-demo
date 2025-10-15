<?php
/**
 * Guarantee Lookup Widget Update Logic
 */

function msb_guarantee_lookup_update($new_instance, $old_instance) {
    $inst = [];
    $inst['form_title'] = sanitize_text_field($new_instance['form_title'] ?? '');
    $inst['ref_label'] = sanitize_text_field($new_instance['ref_label'] ?? '');
    $inst['ref_placeholder'] = sanitize_text_field($new_instance['ref_placeholder'] ?? '');
    $inst['amount_label'] = sanitize_text_field($new_instance['amount_label'] ?? '');
    $inst['amount_placeholder'] = sanitize_text_field($new_instance['amount_placeholder'] ?? '');
    $inst['notes_title'] = sanitize_text_field($new_instance['notes_title'] ?? '');
    $inst['notes_content'] = sanitize_textarea_field($new_instance['notes_content'] ?? '');
    $inst['button_label'] = sanitize_text_field($new_instance['button_label'] ?? '');
    $inst['button_url'] = esc_url_raw($new_instance['button_url'] ?? '');
    $inst['button_color'] = sanitize_hex_color($new_instance['button_color'] ?? '');
    return $inst;
}
