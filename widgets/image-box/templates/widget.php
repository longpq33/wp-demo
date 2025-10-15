<?php
/**
 * Image Box Widget Display
 */

function msb_image_box_widget($args, $instance) {
    $title        = isset($instance['title']) ? trim($instance['title']) : '';
    $button_label = isset($instance['button_label']) ? trim($instance['button_label']) : '';
    $button_url   = isset($instance['button_url']) ? trim($instance['button_url']) : '';
    $button_color = isset($instance['button_color']) ? trim($instance['button_color']) : '';
    $image_id     = isset($instance['image_id']) ? intval($instance['image_id']) : 0;

    $image_url = '';
    if ($image_id) {
        $src = wp_get_attachment_image_src($image_id, 'full');
        if (!empty($src[0])) $image_url = $src[0];
    }

    echo $args['before_widget'];

    echo '<div class="msb-image-box">';
    if ($image_url) {
        echo '<img class="msb-image-box__bg" src="' . esc_url($image_url) . '" alt="" loading="lazy">';
    }

    echo '<div class="msb-image-box__content">';
    if ($title !== '') {
        echo '<h3 class="msb-image-box__title">' . esc_html($title) . '</h3>';
    }
    if ($button_label !== '') {
        $style = '';
        if ($button_color !== '') $style = ' style="background-color:' . esc_attr($button_color) . '"';
        $href = $button_url !== '' ? esc_url($button_url) : '#';
        echo '<a class="msb-image-box__btn" href="' . $href . '"' . $style . '>' . esc_html($button_label) . '</a>';
    }
    echo '</div>'; // content

    echo '</div>'; // wrapper

    echo $args['after_widget'];
}


