<?php
/**
 * Empty Box Widget Display Function
 */

if (!defined('ABSPATH')) {
    exit;
}

function msb_empty_box_widget($args, $instance) {
    $height = !empty($instance['height']) ? floatval($instance['height']) : 20;
    $unit = !empty($instance['unit']) ? sanitize_text_field($instance['unit']) : 'px';
    $class = !empty($instance['class']) ? sanitize_text_field($instance['class']) : '';
    
    // Validate unit
    $allowed_units = array('px', 'vh', '%', 'em', 'rem', 'vw');
    if (!in_array($unit, $allowed_units)) {
        $unit = 'px';
    }
    
    // Validate height
    if ($height < 0) {
        $height = 0;
    }
    
    $style = 'height: ' . $height . $unit . ';';
    $class_attr = !empty($class) ? ' class="' . esc_attr($class) . '"' : '';
    
    echo $args['before_widget'];
    echo '<div' . $class_attr . ' style="' . esc_attr($style) . '"></div>';
    echo $args['after_widget'];
}
