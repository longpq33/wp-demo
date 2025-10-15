<?php
function msb_saving_rates_form($instance, $widget) {
    $title = isset($instance['title']) ? $instance['title'] : '';
    $default_package = isset($instance['default_package']) ? $instance['default_package'] : 'periodic';
    $default_bucket = isset($instance['default_bucket']) ? $instance['default_bucket'] : '0_6';
    $field_id = fn($k)=>$widget->get_field_id($k);
    $field_n  = fn($k)=>$widget->get_field_name($k);
    ?>
    <p>
        <label for="<?php echo esc_attr($field_id('title')); ?>"><?php _e('Tiêu đề:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('title')); ?>" name="<?php echo esc_attr($field_n('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('default_package')); ?>"><?php _e('Gói mặc định:', 'msb-app-theme'); ?></label>
        <select class="widefat" id="<?php echo esc_attr($field_id('default_package')); ?>" name="<?php echo esc_attr($field_n('default_package')); ?>">
            <option value="highest" <?php selected($default_package,'highest'); ?>><?php _e('Lãi suất cao nhất','msb-app-theme'); ?></option>
            <option value="partial" <?php selected($default_package,'partial'); ?>><?php _e('Rút gốc từng phần','msb-app-theme'); ?></option>
            <option value="periodic" <?php selected($default_package,'periodic'); ?>><?php _e('Định kỳ sinh lời','msb-app-theme'); ?></option>
            <option value="pay_now" <?php selected($default_package,'pay_now'); ?>><?php _e('Trả lãi ngay','msb-app-theme'); ?></option>
            <option value="bee" <?php selected($default_package,'bee'); ?>><?php _e('Ong Vàng','msb-app-theme'); ?></option>
            <option value="sprout" <?php selected($default_package,'sprout'); ?>><?php _e('Măng non','msb-app-theme'); ?></option>
            <option value="deposit_contract" <?php selected($default_package,'deposit_contract'); ?>><?php _e('Hợp đồng tiền gửi','msb-app-theme'); ?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('default_bucket')); ?>"><?php _e('Nhóm kỳ hạn mặc định:', 'msb-app-theme'); ?></label>
        <select class="widefat" id="<?php echo esc_attr($field_id('default_bucket')); ?>" name="<?php echo esc_attr($field_n('default_bucket')); ?>">
            <option value="0_6" <?php selected($default_bucket,'0_6'); ?>><?php _e('0–6 tháng','msb-app-theme'); ?></option>
            <option value="6_12" <?php selected($default_bucket,'6_12'); ?>><?php _e('6–12 tháng','msb-app-theme'); ?></option>
            <option value=">12" <?php selected($default_bucket,'>12'); ?>><?php _e('trên 12 tháng','msb-app-theme'); ?></option>
        </select>
    </p>
    <?php
}
?>


