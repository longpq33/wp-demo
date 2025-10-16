<?php
function msb_saving_rates_tabs_update($new, $old){
    $instance = array();
    $instance['title'] = sanitize_text_field($new['title'] ?? '');
    return $instance;
}
?>