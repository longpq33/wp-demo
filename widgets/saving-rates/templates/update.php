<?php
function msb_saving_rates_update($new, $old){
    $inst = [];
    $inst['title'] = sanitize_text_field($new['title'] ?? '');
    $inst['default_package'] = sanitize_text_field($new['default_package'] ?? 'periodic');
    $inst['default_bucket'] = sanitize_text_field($new['default_bucket'] ?? '0_6');
    return $inst;
}
?>


