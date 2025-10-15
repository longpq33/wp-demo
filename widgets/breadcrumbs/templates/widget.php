<?php
/**
 * Breadcrumbs Widget Display Logic
 */

function msb_breadcrumbs_widget($args, $instance) {
    $separator = isset($instance['separator']) ? $instance['separator'] : '>';
    $home_text = isset($instance['home_text']) ? $instance['home_text'] : 'Trang chủ';
    $show_home = isset($instance['show_home']) ? $instance['show_home'] : true;
    $custom_urls = isset($instance['custom_urls']) ? $instance['custom_urls'] : array();
    
    // Styles
    $text_color = isset($instance['text_color']) ? trim($instance['text_color']) : '';
    $link_color = isset($instance['link_color']) ? trim($instance['link_color']) : '';
    $separator_color = isset($instance['separator_color']) ? trim($instance['separator_color']) : '';
    $hover_bg_color = isset($instance['hover_bg_color']) ? trim($instance['hover_bg_color']) : '#E84524';
    $hover_text_color = isset($instance['hover_text_color']) ? trim($instance['hover_text_color']) : '#fff';
    $disable_current_hover = isset($instance['disable_current_hover']) ? $instance['disable_current_hover'] : false;
    $font_size = isset($instance['font_size']) ? trim($instance['font_size']) : '';
    $font_weight = isset($instance['font_weight']) ? trim($instance['font_weight']) : '';

    echo $args['before_widget'];
    
    // Build breadcrumbs array
    $breadcrumbs = array();
    
    // Add home
    if ($show_home) {
        $breadcrumbs[] = array(
            'url' => home_url('/'),
            'title' => $home_text,
            'is_current' => is_front_page()
        );
    }
    
    // Get current URL
    global $wp;
    $current_url = home_url(add_query_arg(array(), $wp->request));
    
    // Add breadcrumbs based on WordPress hierarchy
    if (is_singular()) {
        $post = get_queried_object();
        
        // Add parent pages for pages
        if (is_page() && $post->post_parent) {
            $parent_id = $post->post_parent;
            $parents = array();
            while ($parent_id) {
                $page = get_post($parent_id);
                $parents[] = array(
                    'url' => get_permalink($page->ID),
                    'title' => msb_get_custom_breadcrumb_title(get_permalink($page->ID), get_the_title($page->ID), $custom_urls),
                    'is_current' => false
                );
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_merge($breadcrumbs, array_reverse($parents));
        }
        
        // Add categories for posts
        if (is_single() && !is_page()) {
            $categories = get_the_category($post->ID);
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumbs[] = array(
                    'url' => get_category_link($category->term_id),
                    'title' => msb_get_custom_breadcrumb_title(get_category_link($category->term_id), $category->name, $custom_urls),
                    'is_current' => false
                );
            }
        }
        
        // Add current post/page
        $breadcrumbs[] = array(
            'url' => get_permalink($post->ID),
            'title' => msb_get_custom_breadcrumb_title(get_permalink($post->ID), get_the_title($post->ID), $custom_urls),
            'is_current' => true
        );
    } 
    elseif (is_category()) {
        $category = get_queried_object();
        $breadcrumbs[] = array(
            'url' => get_category_link($category->term_id),
            'title' => msb_get_custom_breadcrumb_title(get_category_link($category->term_id), $category->name, $custom_urls),
            'is_current' => true
        );
    }
    elseif (is_archive()) {
        $breadcrumbs[] = array(
            'url' => '',
            'title' => post_type_archive_title('', false),
            'is_current' => true
        );
    }
    elseif (is_search()) {
        $breadcrumbs[] = array(
            'url' => '',
            'title' => 'Kết quả tìm kiếm',
            'is_current' => true
        );
    }
    elseif (is_404()) {
        $breadcrumbs[] = array(
            'url' => '',
            'title' => '404',
            'is_current' => true
        );
    }
    
    // Generate inline styles
    $container_style = '';
    if ($font_size) $container_style .= 'font-size:' . esc_attr($font_size) . ';';
    if ($font_weight) $container_style .= 'font-weight:' . esc_attr($font_weight) . ';';
    
    $text_style = '';
    if ($text_color) $text_style = 'color:' . esc_attr($text_color) . ';';
    
    $link_style = '';
    if ($link_color) $link_style = 'color:' . esc_attr($link_color) . ';';
    
    $separator_style = '';
    if ($separator_color) $separator_style = 'color:' . esc_attr($separator_color) . ';';
    
    // Generate unique widget ID for scoped CSS
    $widget_id = 'msb-breadcrumbs-' . uniqid();
    
    // Convert hex to rgba for blur effect
    $hex_to_rgba = function($hex, $alpha = 1) {
        if (empty($hex)) return '';
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }
        $rgb = array_map('hexdec', str_split($hex, 2));
        return 'rgba(' . implode(',', $rgb) . ',' . $alpha . ')';
    };
    
    // Generate dynamic CSS for hover effects
    if ($hover_bg_color || $hover_text_color) {
        $blur_bg = $hex_to_rgba($hover_bg_color, 0.1);
        echo '<style>';
        echo '#' . $widget_id . ' .msb-breadcrumbs__item:hover {';
        if ($hover_bg_color) {
            echo 'background-color: ' . esc_attr($hover_bg_color) . ' !important;';
            echo 'backdrop-filter: blur(10px) !important;';
            echo 'box-shadow: 0 4px 15px ' . esc_attr($blur_bg) . ' !important;';
        }
        echo '}';
        echo '#' . $widget_id . ' .msb-breadcrumbs__item:hover .msb-breadcrumbs__link,';
        echo '#' . $widget_id . ' .msb-breadcrumbs__item:hover .msb-breadcrumbs__current,';
        echo '#' . $widget_id . ' .msb-breadcrumbs__item:hover .msb-breadcrumbs__separator {';
        if ($hover_text_color) {
            echo 'color: ' . esc_attr($hover_text_color) . ' !important;';
        }
        echo '}';
        
        // Chặn hover cho current page nếu option được bật
        if ($disable_current_hover) {
            echo '#' . $widget_id . ' .msb-breadcrumbs__item--current:hover {';
            echo 'background-color: transparent !important;';
            echo 'transform: none !important;';
            echo '}';
            echo '#' . $widget_id . ' .msb-breadcrumbs__item--current:hover .msb-breadcrumbs__current {';
            echo 'color: ' . esc_attr($text_color ?: '#e84524') . ' !important;';
            echo '}';
        }
        
        echo '</style>';
    }
    
    // Output breadcrumbs
    echo '<div id="' . $widget_id . '" class="msb-breadcrumbs"' . ($container_style ? ' style="' . $container_style . '"' : '') . '>';
    
    $total = count($breadcrumbs);
    foreach ($breadcrumbs as $index => $crumb) {
        $is_last = ($index === $total - 1);
        
        // Wrap each breadcrumb item in a div
        $item_class = 'msb-breadcrumbs__item';
        if ($disable_current_hover && ($crumb['is_current'] || $is_last || empty($crumb['url']))) {
            $item_class .= ' msb-breadcrumbs__item--current';
        }
        echo '<div class="' . $item_class . '">';
        
        if ($crumb['is_current'] || $is_last || empty($crumb['url'])) {
            echo '<span class="msb-breadcrumbs__current"' . ($text_style ? ' style="' . $text_style . '"' : '') . '>' . esc_html($crumb['title']) . '</span>';
        } else {
            echo '<a href="' . esc_url($crumb['url']) . '" class="msb-breadcrumbs__link"' . ($link_style ? ' style="' . $link_style . '"' : '') . '>' . esc_html($crumb['title']) . '</a>';
        }
        
        if (!$is_last) {
            echo '<span class="msb-breadcrumbs__separator"' . ($separator_style ? ' style="' . $separator_style . '"' : '') . '>' . esc_html($separator) . '</span>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
    echo $args['after_widget'];
}

/**
 * Get custom breadcrumb title for a URL or use default
 */
function msb_get_custom_breadcrumb_title($url, $default, $custom_urls) {
    if (empty($custom_urls) || !is_array($custom_urls)) {
        return $default;
    }
    
    // Normalize URL for comparison
    $url = trailingslashit($url);
    
    foreach ($custom_urls as $custom) {
        if (isset($custom['url']) && isset($custom['label'])) {
            $custom_url = trailingslashit($custom['url']);
            if ($custom_url === $url && !empty($custom['label'])) {
                return $custom['label'];
            }
        }
    }
    
    return $default;
}

