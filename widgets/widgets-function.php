<?php
// Autoload custom SiteOrigin widgets from theme/widgets/*
function msb_autoload_siteorigin_widgets(): void {
  if (!class_exists('SiteOrigin_Widget')) {
      return; // Widgets Bundle not ready
  }
  $widgets_root = get_template_directory() . '/widgets';
  if (!is_dir($widgets_root)) {
      return;
  }
  foreach (glob($widgets_root . '/*/*.php') as $widget_file) {
      require_once $widget_file;
  }
}

// Ensure autoloader runs when the Widgets Bundle is ready (and fallback late)
add_action('siteorigin_widgets_loaded', 'msb_autoload_siteorigin_widgets', 10);
add_action('init', 'msb_autoload_siteorigin_widgets', 99);

// Autoload native WordPress widgets (not SiteOrigin)
function msb_autoload_wp_widgets(): void {
  $widgets_root = get_template_directory() . '/widgets';
  if (!is_dir($widgets_root)) {
      return;
  }
  foreach (glob($widgets_root . '/*/*.php') as $widget_file) {
      require_once $widget_file;
  }
}
add_action('widgets_init', 'msb_autoload_wp_widgets', 1);

// Add a custom tab "Msb widget" to the SiteOrigin widget picker
add_filter('siteorigin_panels_widget_dialog_tabs', function ($tabs) {
  $tabs[] = array(
      'title'  => __('MSB Widgets', 'msb-app-theme'),
      'filter' => array(
          'groups' => array('msb'),
      ),
      'icon'   => 'dashicons-admin-customizer',
  );
  return $tabs;
}, 20);


// Enqueue scripts and styles
function msb_app_theme_assets() {
    $theme_version = wp_get_theme()->get('Version');
    // Frontend styles: load theme root style.css first so it can define base rules
    $root_style_path = get_stylesheet_directory() . '/style.css';
    $root_style_ver  = file_exists($root_style_path) ? filemtime($root_style_path) : $theme_version;
    wp_enqueue_style('msb-app-style', get_stylesheet_uri(), array(), $root_style_ver);

    // Then load main compiled stylesheet which can override base styles
    wp_enqueue_style('msb-app-main', get_template_directory_uri() . '/assets/css/main.css', array('msb-app-style'), $theme_version);
    wp_enqueue_script('msb-app-main', get_template_directory_uri() . '/assets/js/main.js', array(), $theme_version, true);

    // Search widget CSS migrated to LESS (auto-compiled and auto-enqueued)

    $featured_products_slider_js = get_template_directory() . '/widgets/featured-products-slider/js/init.js';
    if (file_exists($featured_products_slider_js)) {
        wp_enqueue_script(
            'msb-featured-products-slider-wp',
            get_template_directory_uri() . '/widgets/featured-products-slider/js/init.js',
            array('msb-app-main'),
            filemtime($featured_products_slider_js)
        );
    }
    
    $menu_select_box_js = get_template_directory() . '/widgets/menu-select-box/js/menu-select-box.js';
    if (file_exists($menu_select_box_js)) {
        wp_enqueue_script(
            'msb-menu-select-box-wp',
            get_template_directory_uri() . '/widgets/menu-select-box/js/menu-select-box.js',
            array('jquery'),
            filemtime($menu_select_box_js),
        );
    }

    // Auto-load compiled CSS for widgets with LESS files (using lessphp)
    $compiler = msb_init_widgets_less_compiler();
    $widgets_with_less = $compiler->get_widgets_with_less();
    
    foreach ($widgets_with_less as $widget) {
        $css_path = $compiler->get_widget_css_path($widget);
        $css_url = $compiler->get_widget_css_url($widget);
        
        if (file_exists($css_path)) {
            wp_enqueue_style(
                'msb-' . $widget . '-wp',
                $css_url,
                array('msb-app-style'),
                filemtime($css_path)
            );
        }
    } 
}
add_action('wp_enqueue_scripts', 'msb_app_theme_assets');

?>
