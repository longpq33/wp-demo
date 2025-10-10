<?php
/**
 * Empty Box Widget
 */

class MSB_Empty_Box_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_empty_box',
            __('Empty Box', 'msb-app-theme'),
            array(
                'panels_groups' => array('msb'),
                'description' => __('Tạo khoảng trống với chiều cao tùy chỉnh (MSB).', 'msb-app-theme'),
                'classname' => 'msb-empty-box-widget'
            )
        );
    }

    public function widget($args, $instance) {
        $height = !empty($instance['height']) ? floatval($instance['height']) : 20;
        $unit = !empty($instance['unit']) ? sanitize_text_field($instance['unit']) : 'px';
        $class = !empty($instance['class']) ? sanitize_text_field($instance['instance']['class']) : '';
        
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

    public function form($instance) {
        $height = !empty($instance['height']) ? floatval($instance['height']) : 20;
        $unit = !empty($instance['unit']) ? sanitize_text_field($instance['unit']) : 'px';
        $class = !empty($instance['class']) ? sanitize_text_field($instance['class']) : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Chiều cao:', 'msb-app-theme'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('height'); ?>" 
                   name="<?php echo $this->get_field_name('height'); ?>" 
                   type="number" step="0.1" min="0" 
                   value="<?php echo esc_attr($height); ?>" size="5">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('unit'); ?>"><?php _e('Đơn vị:', 'msb-app-theme'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('unit'); ?>" name="<?php echo $this->get_field_name('unit'); ?>">
                <option value="px" <?php selected($unit, 'px'); ?>><?php _e('Pixels (px)', 'msb-app-theme'); ?></option>
                <option value="vh" <?php selected($unit, 'vh'); ?>><?php _e('Viewport Height (vh)', 'msb-app-theme'); ?></option>
                <option value="%" <?php selected($unit, '%'); ?>><?php _e('Phần trăm (%)', 'msb-app-theme'); ?></option>
                <option value="em" <?php selected($unit, 'em'); ?>><?php _e('Em (em)', 'msb-app-theme'); ?></option>
                <option value="rem" <?php selected($unit, 'rem'); ?>><?php _e('Root Em (rem)', 'msb-app-theme'); ?></option>
                <option value="vw" <?php selected($unit, 'vw'); ?>><?php _e('Viewport Width (vw)', 'msb-app-theme'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('CSS Class (tùy chọn):', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" 
                   name="<?php echo $this->get_field_name('class'); ?>" 
                   type="text" value="<?php echo esc_attr($class); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['height'] = (!empty($new_instance['height'])) ? floatval($new_instance['height']) : 20;
        $instance['unit'] = (!empty($new_instance['unit'])) ? sanitize_text_field($new_instance['unit']) : 'px';
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
}

// Register widget
function msb_register_empty_box_widget() {
    register_widget('MSB_Empty_Box_Widget');
}
add_action('widgets_init', 'msb_register_empty_box_widget');
