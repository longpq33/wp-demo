<?php
function msb_saving_rates_tabs_form($instance, $widget){
    $title = isset($instance['title']) ? $instance['title'] : '';
    
    $field_id = fn($k)=>$widget->get_field_id($k);
    $field_name = fn($k)=>$widget->get_field_name($k);
    ?>
    <p>
        <label for="<?php echo esc_attr($field_id('title')); ?>"><?php _e('Tiêu đề:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('title')); ?>" name="<?php echo esc_attr($field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
    </p>
    
    <p>
        <em><?php _e('Widget này hiển thị 3 tab cố định: Tiền gửi có kỳ hạn tại quầy, Tiền gửi có kỳ hạn trực tuyến, và Tiết kiệm ngoại tệ. Dữ liệu được lưu trong file JSON.', 'msb-app-theme'); ?></em>
    </p>
    <?php
}
?>