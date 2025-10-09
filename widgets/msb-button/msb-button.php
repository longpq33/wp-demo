<?php
/**
 * MSB Button Widget
 * Customizable button with colors, alignment, and size options
 */

class MSB_Button_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_button',
            __('MSB Button', 'msb-app-theme'),
            array(
                'classname' => 'msb-button-widget',
                'description' => __('Customizable button with colors, alignment, and size options', 'msb-app-theme'),
                'panels_groups' => array('msb')
            )
        );
    }

    public function widget($args, $instance) {
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Button', 'msb-app-theme');
        $button_link = !empty($instance['button_link']) ? $instance['button_link'] : '#';
        $text_color = !empty($instance['text_color']) ? $instance['text_color'] : '#ffffff';
        $background_color = !empty($instance['background_color']) ? $instance['background_color'] : '#007cba';
        $alignment = !empty($instance['alignment']) ? $instance['alignment'] : 'center';
        $size = !empty($instance['size']) ? $instance['size'] : 'medium';
        $open_new_tab = !empty($instance['open_new_tab']) ? 1 : 0;

        // Ensure CSS is loaded when widget is used
        $css_file = get_template_directory() . '/widgets/msb-button/css/msb-button.css';
        if (file_exists($css_file)) {
            wp_enqueue_style(
                'msb-button-wp',
                get_template_directory_uri() . '/widgets/msb-button/css/msb-button.css',
                array(),
                filemtime($css_file)
            );
        }

        echo $args['before_widget'];
        ?>
        <div class="msb-button-wrapper align-<?php echo esc_attr($alignment); ?>">
            <a href="<?php echo esc_url($button_link); ?>" 
               class="msb-button size-<?php echo esc_attr($size); ?>"
               style="color: <?php echo esc_attr($text_color); ?>; background-color: <?php echo esc_attr($background_color); ?>;"
               <?php echo $open_new_tab ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
               aria-label="<?php echo esc_attr($button_text); ?>">
                <?php echo esc_html($button_text); ?>
            </a>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : '';
        $button_link = isset($instance['button_link']) ? $instance['button_link'] : '';
        $text_color = isset($instance['text_color']) ? $instance['text_color'] : '#ffffff';
        $background_color = isset($instance['background_color']) ? $instance['background_color'] : '#007cba';
        $alignment = isset($instance['alignment']) ? $instance['alignment'] : 'center';
        $size = isset($instance['size']) ? $instance['size'] : 'medium';
        $open_new_tab = isset($instance['open_new_tab']) ? 1 : 0;

        // Enqueue color picker
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" placeholder="<?php _e('Enter button text', 'msb-app-theme'); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_link'); ?>"><?php _e('Button Link:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_link'); ?>" name="<?php echo $this->get_field_name('button_link'); ?>" type="url" value="<?php echo esc_attr($button_link); ?>" placeholder="<?php _e('https://example.com', 'msb-app-theme'); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('text_color'); ?>"><?php _e('Text Color:', 'msb-app-theme'); ?></label>
            <input class="msb-color-picker" id="<?php echo $this->get_field_id('text_color'); ?>" name="<?php echo $this->get_field_name('text_color'); ?>" type="text" value="<?php echo esc_attr($text_color); ?>" data-default-color="#ffffff">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('background_color'); ?>"><?php _e('Background Color:', 'msb-app-theme'); ?></label>
            <input class="msb-color-picker" id="<?php echo $this->get_field_id('background_color'); ?>" name="<?php echo $this->get_field_name('background_color'); ?>" type="text" value="<?php echo esc_attr($background_color); ?>" data-default-color="#007cba">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('alignment'); ?>"><?php _e('Alignment:', 'msb-app-theme'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('alignment'); ?>" name="<?php echo $this->get_field_name('alignment'); ?>">
                <option value="left" <?php selected($alignment, 'left'); ?>><?php _e('Left', 'msb-app-theme'); ?></option>
                <option value="center" <?php selected($alignment, 'center'); ?>><?php _e('Center', 'msb-app-theme'); ?></option>
                <option value="right" <?php selected($alignment, 'right'); ?>><?php _e('Right', 'msb-app-theme'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Button Size:', 'msb-app-theme'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
                <option value="small" <?php selected($size, 'small'); ?>><?php _e('Small', 'msb-app-theme'); ?></option>
                <option value="medium" <?php selected($size, 'medium'); ?>><?php _e('Medium', 'msb-app-theme'); ?></option>
                <option value="large" <?php selected($size, 'large'); ?>><?php _e('Large', 'msb-app-theme'); ?></option>
            </select>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($open_new_tab, 1); ?> id="<?php echo $this->get_field_id('open_new_tab'); ?>" name="<?php echo $this->get_field_name('open_new_tab'); ?>" />
            <label for="<?php echo $this->get_field_id('open_new_tab'); ?>"><?php _e('Open in new tab', 'msb-app-theme'); ?></label>
        </p>

        <script>
        jQuery(document).ready(function($) {
            $('.msb-color-picker').wpColorPicker();
        });
        </script>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['button_text'] = sanitize_text_field($new_instance['button_text']);
        $instance['button_link'] = esc_url_raw($new_instance['button_link']);
        $instance['text_color'] = sanitize_hex_color($new_instance['text_color']);
        $instance['background_color'] = sanitize_hex_color($new_instance['background_color']);
        $instance['alignment'] = in_array($new_instance['alignment'], array('left', 'center', 'right'), true) ? $new_instance['alignment'] : 'center';
        $instance['size'] = in_array($new_instance['size'], array('small', 'medium', 'large'), true) ? $new_instance['size'] : 'medium';
        $instance['open_new_tab'] = !empty($new_instance['open_new_tab']) ? 1 : 0;
        
        return $instance;
    }
}

// Register widget
function msb_register_button_widget() {
    register_widget('MSB_Button_Widget');
}
add_action('widgets_init', 'msb_register_button_widget');
