<?php
/**
 * Featured Products Slider Widget Form Template
 */

if (!defined('ABSPATH')) {
    exit;
}

function msb_featured_products_slider_form($instance, $widget) {
    $number = !empty($instance['number']) ? absint($instance['number']) : 6;
    $show_description = !empty($instance['show_description']) ? 1 : 0;
    $category = !empty($instance['category']) ? absint($instance['category']) : 0;
    ?>

    <p>
        <label for="<?php echo $widget->get_field_id('number'); ?>"><?php _e('Số sản phẩm hiển thị:', 'msb-app-theme'); ?></label>
        <input class="tiny-text" id="<?php echo $widget->get_field_id('number'); ?>" name="<?php echo $widget->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
    </p>

    <p>
        <input class="checkbox" type="checkbox" <?php checked($show_description); ?> id="<?php echo $widget->get_field_id('show_description'); ?>" name="<?php echo $widget->get_field_name('show_description'); ?>" />
        <label for="<?php echo $widget->get_field_id('show_description'); ?>"><?php _e('Hiển thị mô tả', 'msb-app-theme'); ?></label>
    </p>

    <p>
        <label for="<?php echo $widget->get_field_id('category'); ?>"><?php _e('Danh mục sản phẩm:', 'msb-app-theme'); ?></label>
        <?php
            wp_dropdown_categories(array(
                'show_option_all' => __('Tất cả', 'msb-app-theme'),
                'name' => $widget->get_field_name('category'),
                'id' => $widget->get_field_id('category'),
                'class' => 'widefat',
                'selected' => $category,
                'hide_empty' => false,
                'taxonomy' => 'product_cat',
            ));
        ?>
    </p>
    <?php
}
