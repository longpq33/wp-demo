<?php
/**
 * Menu Select Box - Update logic
 */

if (!function_exists('msb_menu_select_box_update')) {
    function msb_menu_select_box_update($new_instance, $old_instance) {
        $instance = array();
        $instance['placeholder'] = sanitize_text_field($new_instance['placeholder'] ?? '');
        $instance['modal_title'] = sanitize_text_field($new_instance['modal_title'] ?? '');

        // Sanitize menu items
        $menu_items = array();
        if (!empty($new_instance['menu_items']) && is_array($new_instance['menu_items'])) {
            foreach ($new_instance['menu_items'] as $item) {
                if (!empty($item['text']) && !empty($item['url'])) {
                    $menu_items[] = array(
                        'icon' => sanitize_text_field($item['icon'] ?? ''),
                        'text' => sanitize_text_field($item['text'] ?? ''),
                        'url' => esc_url_raw($item['url'] ?? '')
                    );
                }
            }
        }
        $instance['menu_items'] = $menu_items;

        // Sanitize tabs (title, url)
        $tabs = array();
        if (!empty($new_instance['tabs']) && is_array($new_instance['tabs'])) {
            foreach ($new_instance['tabs'] as $tab) {
                $title = sanitize_text_field($tab['title'] ?? '');
                $url   = esc_url_raw($tab['url'] ?? '');
                if ($title !== '' && $url !== '') {
                    $tabs[] = array('title' => $title, 'url' => $url);
                }
            }
        }
        $instance['tabs'] = $tabs;

        return $instance;
    }
}


