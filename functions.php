<?php
// Theme setup
function msb_app_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor.css');

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'msb-app-theme'),
        'footer'  => __('Footer Menu', 'msb-app-theme'),
    ));
}
add_action('after_setup_theme', 'msb_app_theme_setup');

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

    // Widget: IconBox (WP Widget) styles
    $icon_box_css = get_template_directory() . '/widgets/icon-box/css/icon-box.css';
    if (file_exists($icon_box_css)) {
        wp_enqueue_style(
            'msb-icon-box-wp',
            get_template_directory_uri() . '/widgets/icon-box/css/icon-box.css',
            array('msb-app-style'),
            filemtime($icon_box_css)
        );
    }

    $search_box_css = get_template_directory() . '/widgets/search/css/search.css';
    if (file_exists($search_box_css)) {
        wp_enqueue_style(
            'msb-search-box-wp',
            get_template_directory_uri() .  '/widgets/search/css/search.css',
            array('msb-app-style'),
            filemtime($search_box_css)
        );
    }

    // Widget: FeaturedProductsSlider (WP Widget) styles
    $featured_products_slider_css = get_template_directory() . '/widgets/featured-products-slider/css/featured-products-slider.css';
    if (file_exists($featured_products_slider_css)) {
        wp_enqueue_style(
            'msb-featured-products-slider-wp',
            get_template_directory_uri() . '/widgets/featured-products-slider/css/featured-products-slider.css',
            array('msb-app-style'),
            filemtime($featured_products_slider_css)
        );
    }

    $featured_products_slider_js = get_template_directory() . '/widgets/featured-products-slider/js/init.js';
    if (file_exists($featured_products_slider_js)) {
        wp_enqueue_script(
            'msb-featured-products-slider-wp',
            get_template_directory_uri() . '/widgets/featured-products-slider/js/init.js',
            array('msb-app-main'),
            filemtime($featured_products_slider_js)
        );
    }

    // Widget: Heading (WP Widget) styles
    $heading_css = get_template_directory() . '/widgets/heading/css/heading.css';
    if (file_exists($heading_css)) {
        wp_enqueue_style(
            'msb-heading-wp',
            get_template_directory_uri() . '/widgets/heading/css/heading.css',
            array('msb-app-style'),
            filemtime($heading_css)
        );
    }

    // Widget: Latest Posts (WP Widget) styles
    $latest_posts_css = get_template_directory() . '/widgets/latest-posts/css/latest-posts.css';
    if (file_exists($latest_posts_css)) {
        wp_enqueue_style(
            'msb-latest-posts-wp',
            get_template_directory_uri() . '/widgets/latest-posts/css/latest-posts.css',
            array('msb-app-style'),
            filemtime($latest_posts_css)
        );
    }
}
add_action('wp_enqueue_scripts', 'msb_app_theme_assets');

// Register widget areas
function msb_app_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'msb-app-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'msb-app-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'msb_app_theme_widgets_init');

// Disable WP version exposure for security
remove_action('wp_head', 'wp_generator');

// Change REST API base from /wp-json to /api
add_filter('rest_url_prefix', function () {
    return 'api';
});

// Flush rewrite rules when theme is switched to apply REST prefix changes
add_action('after_switch_theme', function () {
    flush_rewrite_rules();
});

// One-time flush to ensure /api prefix works without manual Permalinks save
add_action('init', function () {
    if (get_option('msb_rest_prefix_flushed') !== '1') {
        flush_rewrite_rules(false);
        update_option('msb_rest_prefix_flushed', '1');
    }
});

// Explicit rewrite for /api/* to support environments not honoring default REST rewrites
add_action('init', function () {
    add_rewrite_rule('^api/v1/?$', 'index.php?rest_route=/api/v1', 'top');
    add_rewrite_rule('^api/v1/(.*)?', 'index.php?rest_route=/api/v1/$matches[1]', 'top');
});

// Helper: get asset path (cache-busting via filemtime)
function msb_app_asset(string $relative): string {
    $path = get_template_directory() . '/' . ltrim($relative, '/');
    $url  = get_template_directory_uri() . '/' . ltrim($relative, '/');
    if (file_exists($path)) {
        $url = add_query_arg('v', filemtime($path), $url);
    }
    return esc_url($url);
}

// Load API endpoints
require_once get_template_directory() . '/api/v1/posts.php';

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
        'title'  => __('Msb widget', 'msb-app-theme'),
        'filter' => array(
            'groups' => array('msb'),
        ),
        'icon'   => 'dashicons-admin-customizer',
    );
    return $tabs;
}, 20);

// Include WooCommerce customizations
require_once get_template_directory() . '/msb-woocomerce-functions.php';

// Include Suggested Products Meta Box
require_once get_template_directory() . '/includes/class-suggested-products-meta-box.php';
