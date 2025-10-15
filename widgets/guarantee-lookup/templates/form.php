<?php
/**
 * Guarantee Lookup Widget Form
 */

function msb_guarantee_lookup_form($instance, $widget) {
    $form_title = isset($instance['form_title']) ? $instance['form_title'] : '';
    $ref_label = isset($instance['ref_label']) ? $instance['ref_label'] : '';
    $ref_placeholder = isset($instance['ref_placeholder']) ? $instance['ref_placeholder'] : '';
    $amount_label = isset($instance['amount_label']) ? $instance['amount_label'] : '';
    $amount_placeholder = isset($instance['amount_placeholder']) ? $instance['amount_placeholder'] : '';
    $notes_title = isset($instance['notes_title']) ? $instance['notes_title'] : '';
    $notes_content = isset($instance['notes_content']) ? $instance['notes_content'] : '';
    $button_label = isset($instance['button_label']) ? $instance['button_label'] : '';
    $button_url = isset($instance['button_url']) ? $instance['button_url'] : '';
    $button_color = isset($instance['button_color']) ? $instance['button_color'] : '';

    $field_id = function($k) use ($widget) { return $widget->get_field_id($k); };
    $field_n  = function($k) use ($widget) { return $widget->get_field_name($k); };

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    ?>
    <p>
        <label for="<?php echo esc_attr($field_id('form_title')); ?>"><?php _e('Tiêu đề form:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('form_title')); ?>" name="<?php echo esc_attr($field_n('form_title')); ?>" type="text" value="<?php echo esc_attr($form_title); ?>">
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('ref_label')); ?>"><?php _e('Nhãn trường Ref:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('ref_label')); ?>" name="<?php echo esc_attr($field_n('ref_label')); ?>" type="text" value="<?php echo esc_attr($ref_label); ?>">
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('ref_placeholder')); ?>"><?php _e('Placeholder trường Ref:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('ref_placeholder')); ?>" name="<?php echo esc_attr($field_n('ref_placeholder')); ?>" type="text" value="<?php echo esc_attr($ref_placeholder); ?>">
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('amount_label')); ?>"><?php _e('Nhãn trường số tiền:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('amount_label')); ?>" name="<?php echo esc_attr($field_n('amount_label')); ?>" type="text" value="<?php echo esc_attr($amount_label); ?>">
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('amount_placeholder')); ?>"><?php _e('Placeholder trường số tiền:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('amount_placeholder')); ?>" name="<?php echo esc_attr($field_n('amount_placeholder')); ?>" type="text" value="<?php echo esc_attr($amount_placeholder); ?>">
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('notes_title')); ?>"><?php _e('Tiêu đề phần lưu ý:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('notes_title')); ?>" name="<?php echo esc_attr($field_n('notes_title')); ?>" type="text" value="<?php echo esc_attr($notes_title); ?>">
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('notes_content')); ?>"><?php _e('Nội dung lưu ý (mỗi dòng 1 điểm):', 'msb-app-theme'); ?></label>
        <textarea class="widefat" id="<?php echo esc_attr($field_id('notes_content')); ?>" name="<?php echo esc_attr($field_n('notes_content')); ?>" rows="8"><?php echo esc_textarea($notes_content); ?></textarea>
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('button_label')); ?>"><?php _e('Nhãn nút:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('button_label')); ?>" name="<?php echo esc_attr($field_n('button_label')); ?>" type="text" value="<?php echo esc_attr($button_label); ?>">
    </p>
    
    <p>
        <label for="<?php echo esc_attr($field_id('button_url')); ?>"><?php _e('URL xử lý tra cứu:', 'msb-app-theme'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($field_id('button_url')); ?>" name="<?php echo esc_attr($field_n('button_url')); ?>" type="url" value="<?php echo esc_attr($button_url); ?>">
    </p>
    
    <p>
        <label><?php _e('Màu nút:', 'msb-app-theme'); ?></label>
        <input class="widefat msb-color-field" name="<?php echo esc_attr($field_n('button_color')); ?>" type="text" value="<?php echo esc_attr($button_color); ?>">
    </p>
    
    <div id="msb-result" style="margin-top: 20px;"></div>
    
    <script type="text/javascript">
        (function ($) {
            $(function () {
                $('.msb-color-field').wpColorPicker();
            })
        })(jQuery);
    </script>
    <?php
}
