<?php
/**
 * Empty Box Widget Update
 */

if (!defined('ABSPATH')) {
    exit;
}

function msb_empty_box_update($new_instance, $old_instance) {
    $instance = array();
    
    // Height
    $instance['height'] = (!empty($new_instance['height'])) ? floatval($new_instance['height']) : 20;
    
    // Unit
    $instance['unit'] = (!empty($new_instance['unit'])) ? sanitize_text_field($new_instance['unit']) : 'px';
    
    // Class
    $instance['class'] = (!empty($new_instance['class'])) ? sanitize_text_field($new_instance['class']) : '';
    
    // Validate unit
    $allowed_units = array('px', 'vh', '%', 'em', 'rem', 'vw');
    if (!in_array($instance['unit'], $allowed_units)) {
        $instance['unit'] = 'px';
    }
    
    // Validate height
    if ($instance['height'] < 0) {
        $instance['height'] = 0;
    }
    
    return $instance;
}
