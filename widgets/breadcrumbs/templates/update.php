<?php
/**
 * Breadcrumbs Widget Update Logic
 */

function msb_breadcrumbs_update($new_instance, $old_instance) {
    $inst = [];
    
    // Basic settings
    $inst['separator'] = sanitize_text_field($new_instance['separator'] ?? '>');
    $inst['home_text'] = sanitize_text_field($new_instance['home_text'] ?? 'Trang chủ');
    $inst['show_home'] = isset($new_instance['show_home']) ? true : false;
    
    // Custom URLs
    $custom_urls = array();
    if (isset($new_instance['custom_urls']) && is_array($new_instance['custom_urls'])) {
        foreach ($new_instance['custom_urls'] as $url_mapping) {
            if (!empty($url_mapping['url']) && !empty($url_mapping['label'])) {
                $custom_urls[] = array(
                    'url' => esc_url_raw($url_mapping['url']),
                    'label' => sanitize_text_field($url_mapping['label'])
                );
            }
        }
    }
    $inst['custom_urls'] = $custom_urls;
    
    // Style settings
    $inst['text_color'] = sanitize_text_field($new_instance['text_color'] ?? '');
    $inst['link_color'] = sanitize_text_field($new_instance['link_color'] ?? '');
    $inst['separator_color'] = sanitize_text_field($new_instance['separator_color'] ?? '');
    $inst['hover_bg_color'] = sanitize_text_field($new_instance['hover_bg_color'] ?? '#E84524');
    $inst['hover_text_color'] = sanitize_text_field($new_instance['hover_text_color'] ?? '#fff');
    $inst['disable_current_hover'] = isset($new_instance['disable_current_hover']) ? true : false;
    $inst['font_size'] = sanitize_text_field($new_instance['font_size'] ?? '');
    $inst['font_weight'] = sanitize_text_field($new_instance['font_weight'] ?? '');
    
    return $inst;
}

