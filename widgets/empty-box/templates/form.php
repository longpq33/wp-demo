<?php
/**
 * Empty Box Widget Form
 */

if (!defined('ABSPATH')) {
    exit;
}

function msb_empty_box_form($instance, $widget) {
    $height = !empty($instance['height']) ? floatval($instance['height']) : 20;
    $unit = !empty($instance['unit']) ? sanitize_text_field($instance['unit']) : 'px';
    $class = !empty($instance['class']) ? sanitize_text_field($instance['class']) : '';
    ?>
    <p>
        <label for="<?php echo $widget->get_field_id('height'); ?>"><?php _e('Chiều cao:', 'msb-app-theme'); ?></label>
        <input class="tiny-text" id="<?php echo $widget->get_field_id('height'); ?>" 
               name="<?php echo $widget->get_field_name('height'); ?>" 
               type="number" step="1" min="0" 
               value="<?php echo esc_attr($height); ?>" size="5">
    </p>

    <p>
        <label for="<?php echo $widget->get_field_id('unit'); ?>"><?php _e('Đơn vị:', 'msb-app-theme'); ?></label>
        <select class="widefat" id="<?php echo $widget->get_field_id('unit'); ?>" name="<?php echo $widget->get_field_name('unit'); ?>">
            <option value="px" <?php selected($unit, 'px'); ?>><?php _e('px', 'msb-app-theme'); ?></option>
            <option value="vh" <?php selected($unit, 'vh'); ?>><?php _e('vh', 'msb-app-theme'); ?></option>
            <option value="vw" <?php selected($unit, 'vw'); ?>><?php _e('vw', 'msb-app-theme'); ?></option>
        </select>
    </p>
    <?php
}
