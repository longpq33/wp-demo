<?php
/**
 * Interest Calculator Form
 */

function msb_interest_calculator_form($instance, $widget) {
    $amount_default = isset($instance['amount_default']) ? $instance['amount_default'] : '';
    $labels = isset($instance['labels']) ? $instance['labels'] : array(
        'amount' => __('Số tiền gửi', 'msb-app-theme'),
        'type' => __('Hình thức gửi', 'msb-app-theme'),
        'package' => __('Gói sản phẩm', 'msb-app-theme'),
        'term' => __('Kỳ hạn', 'msb-app-theme'),
        'result_title' => __('Gốc + lãi dự kiến', 'msb-app-theme'),
        'interest' => __('Tiền lãi', 'msb-app-theme'),
        'rate' => __('Lãi suất', 'msb-app-theme'),
        'updated' => __('Lãi suất cập nhật', 'msb-app-theme'),
    );
    $button = isset($instance['button']) ? $instance['button'] : array(
        'label' => __('Tiến hành mở', 'msb-app-theme'),
        'url' => '',
        'color' => '#f97316',
    );
    // datasets no longer configurable via admin; hardcoded in widget display

    $field_id = function($k) use ($widget) { return $widget->get_field_id($k); };
    $field_n  = function($k) use ($widget) { return $widget->get_field_name($k); };

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    ?>
    <p>
        <label for="<?php echo esc_attr($field_id('amount_default')); ?>"><?php _e('Giá trị mặc định - Số tiền gửi:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('amount_default')); ?>" name="<?php echo esc_attr($field_n('amount_default')); ?>" type="text" value="<?php echo esc_attr($amount_default); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('button_label')); ?>"><?php _e('Nhãn nút:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('button_label')); ?>" name="<?php echo esc_attr($field_n('button[label]')); ?>" type="text" value="<?php echo esc_attr($button['label']); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr($field_id('button_url')); ?>"><?php _e('URL nút:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('button_url')); ?>" name="<?php echo esc_attr($field_n('button[url]')); ?>" type="url" value="<?php echo esc_attr($button['url']); ?>">
    </p>
    <p>
        <label><?php _e('Màu nút:', 'msb-app-theme'); ?></label>
        <input class="msb-color-field" name="<?php echo esc_attr($field_n('button[color]')); ?>" type="text" value="<?php echo esc_attr($button['color']); ?>">
    </p>

    <script type="text/javascript">
        (function ($) { $(function () { $('.msb-color-field').wpColorPicker(); }); })(jQuery);
    </script>
    <?php
}


