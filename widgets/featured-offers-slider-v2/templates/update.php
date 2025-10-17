<?php
/**
 * Featured Offers Slider V2 Widget Update Template
 */

if (!defined('ABSPATH')) {
    exit;
}

function msb_featured_offers_slider_v2_update($new_instance, $old_instance) {
    $instance = array();
    
    // Number of products
    $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 6;
    
    // Show description
    $instance['show_description'] = !empty($new_instance['show_description']) ? 1 : 0;
    
    // Show categories
    $instance['show_categories'] = !empty($new_instance['show_categories']) ? 1 : 0;
    
    // Category
    $instance['category'] = absint($new_instance['category'] ?? 0);
    
    return $instance;
}
