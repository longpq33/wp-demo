<?php
/**
 * Heading Widget Display Logic
 */

function msb_heading_widget($args, $instance) {
    $heading_id   = isset($instance['id']) ? intval($instance['id']) : 0;
    $title     = isset($instance['title']) ? $instance['title'] : '';
    $subtitle   = isset($instance['subtitle']) ? $instance['subtitle'] : '';
    $description = isset($instance['description']) ? $instance['description'] : '';
    // Styles
    $t_fs = isset($instance['title_fs']) ? trim($instance['title_fs']) : '';
    $t_cl = isset($instance['title_cl']) ? trim($instance['title_cl']) : '';
    $t_fw = isset($instance['title_fw']) ? trim($instance['title_fw']) : '';
    $s_fs = isset($instance['subtitle_fs']) ? trim($instance['subtitle_fs']) : '';
    $s_cl = isset($instance['subtitle_cl']) ? trim($instance['subtitle_cl']) : '';
    $s_fw = isset($instance['subtitle_fw']) ? trim($instance['subtitle_fw']) : '';
    $d_fs = isset($instance['desc_fs']) ? trim($instance['desc_fs']) : '';
    $d_cl = isset($instance['desc_cl']) ? trim($instance['desc_cl']) : '';
    $d_fw = isset($instance['desc_fw']) ? trim($instance['desc_fw']) : '';
    // More button
    $show_more_btn = isset($instance['show_more_btn']) ? $instance['show_more_btn'] : 0;
    $more_btn_text = isset($instance['more_btn_text']) ? $instance['more_btn_text'] : 'Xem thêm';
    $more_btn_url = isset($instance['more_btn_url']) ? $instance['more_btn_url'] : '';

    echo $args['before_widget'];
    $tag = 'div';
    $classes = 'msb-heading';
    if ($show_more_btn) {
        $classes .= ' msb-heading--with-btn';
    }
   
    echo '<' . $tag . ' class="' . $classes . '" id="' . $heading_id . '">';
    
    // Header content wrapper
    echo '<div class="msb-heading__content">';

    if ($title) {
        $style = '';
        if ($t_fs !== '') $style .= 'font-size:' . esc_attr($t_fs) . ';';
        if ($t_cl !== '') $style .= 'color:' . esc_attr($t_cl) . ';';
        if ($t_fw !== '') $style .= 'font-weight:' . esc_attr($t_fw) . ';';
        echo '<h3 class="msb-icon-box__title"' . ($style ? ' style="' . $style . '"' : '') . '>' . esc_html($title) . '</h3>';
    }
    if ($subtitle) {
        $style = '';
        if ($s_fs !== '') $style .= 'font-size:' . esc_attr($s_fs) . ';';
        if ($s_cl !== '') $style .= 'color:' . esc_attr($s_cl) . ';';
        if ($s_fw !== '') $style .= 'font-weight:' . esc_attr($s_fw) . ';';
        echo '<h4 class="msb-icon-box__subtitle"' . ($style ? ' style="' . $style . '"' : '') . '>' . esc_html($subtitle) . '</h4>';
    }

    if ($description) {
        $style = '';
        if ($d_fs !== '') $style .= 'font-size:' . esc_attr($d_fs) . ';';
        if ($d_cl !== '') $style .= 'color:' . esc_attr($d_cl) . ';';
        if ($d_fw !== '') $style .= 'font-weight:' . esc_attr($d_fw) . ';';
        echo '<p class="msb-icon-box__description"' . ($style ? ' style="' . $style . '"' : '') . '>' . esc_html($description) . '</p>';
    }
    
    echo '</div>'; // Close msb-heading__content
    
    // More button
    if ($show_more_btn && $more_btn_text && $more_btn_url) {
        echo '<div class="msb-heading__action">';
        echo '<a href="' . esc_url($more_btn_url) . '" class="msb-heading__btn">' . esc_html($more_btn_text) . '</a>';
        echo '</div>';
    }

    echo '</' . $tag . '>';
    echo $args['after_widget'];
}