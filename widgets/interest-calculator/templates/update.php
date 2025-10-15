<?php
/**
 * Interest Calculator Update
 */

function msb_interest_calculator_update($new_instance, $old_instance) {
    $inst = [];
    $inst['amount_default'] = preg_replace('/[^0-9]/', '', $new_instance['amount_default'] ?? '');

    $inst['button'] = array(
        'label' => sanitize_text_field($new_instance['button']['label'] ?? ''),
        'url'   => esc_url_raw($new_instance['button']['url'] ?? ''),
        'color' => sanitize_hex_color($new_instance['button']['color'] ?? ''),
    );
    return $inst;
}


