<?php
/**
 * Icon Box Widget Display Logic
 */

function msb_icon_box_widget($args, $instance, $widget) {
    $icon_id = isset($instance['icon']) ? intval($instance['icon']) : 0;
    $icon_size = isset($instance['icon_size']) ? intval($instance['icon_size']) : 40;
    $title = isset($instance['title']) ? $instance['title'] : '';
    $url = isset($instance['url']) ? $instance['url'] : '';
    $align = isset($instance['align']) ? $instance['align'] : 'center';
    $text_color = isset($instance['text_color']) ? $instance['text_color'] : '#111827';
    $icon_pos = isset($instance['icon_pos']) ? $instance['icon_pos'] : 'top';

    echo $args['before_widget'];

    $tag = $url ? 'a' : 'div';
    $attrs = $url ? ' href="' . esc_url($url) . '"' : '';
    $classes = 'msb-icon-box align-' . esc_attr($align) . ' icon-pos-' . esc_attr($icon_pos);
    $style = 'color:' . esc_attr($text_color) . '; font-size:14px;';

    // ✅ Lấy dữ liệu hover
    $widget_id = esc_attr($widget->id);
    $hover_text_color = esc_attr($instance['hover_text_color'] ?? '#000000');
    $hover_bg_color = esc_attr($instance['hover_bg_color'] ?? '#0b2545');
    $enable_hover_bg = !empty($instance['enable_hover_bg']);
    $hover_scale = $instance['hover_scale'] ?? 1.05;

    $hover_bg_class = $enable_hover_bg ? 'has-hover-bg' : 'no-hover-bg';

    if (!$enable_hover_bg) {
        $hover_bg_color = 'transparent';
    }
    $style = sprintf(
        'color:%1$s; font-size:14px; --hover-text-color:%2$s; --hover-bg-color:%3$s; --hover-scale:%4$s;',
        esc_attr($text_color),
        esc_attr($hover_text_color),
        esc_attr($hover_bg_color),
        esc_attr($hover_scale)
    );

    echo '<' . $tag . ' id="' . $widget_id . '" class="' . $classes . ' ' . $hover_bg_class . '" style="' . esc_attr($style) . '"' . $attrs . '>';

    // ✅ Render HTML theo vị trí icon
    if (in_array($icon_pos, ['left', 'right'])) {
        echo '<div class="msb-icon-box__inner">';
        if ($icon_pos === 'left') {
            msb_icon_box_render_icon($icon_id, $icon_size);
            msb_icon_box_render_title($title);
        } else {
            msb_icon_box_render_title($title);
            msb_icon_box_render_icon($icon_id, $icon_size);
        }
        echo '</div>';
    } else {
        if ($icon_pos === 'top') {
            msb_icon_box_render_icon($icon_id, $icon_size);
            msb_icon_box_render_title($title);
        } else {
            msb_icon_box_render_title($title);
            msb_icon_box_render_icon($icon_id, $icon_size);
        }
    }
    

    echo '</' . $tag . '>';
    echo $args['after_widget'];
}

function msb_icon_box_render_icon($icon_id, $icon_size) {
    if(!empty($icon_id)) {
        echo '<div class="msb-icon-box__icon" style="width:' . intval($icon_size) . 'px;height:' . intval($icon_size) . 'px;">';
        if ($icon_id) {
            $src = wp_get_attachment_image_url($icon_id, 'full');
            if ($src) {
                echo '<img src="' . esc_url($src) . '" alt="" width="' . intval($icon_size) . '" height="' . intval($icon_size) . '" />';
            }
        }
        echo '</div>';
    }
}

function msb_icon_box_render_title($title) {
    if ($title) {
        echo '<div class="msb-icon-box__title">' . esc_html($title) . '</div>';
    }
}
