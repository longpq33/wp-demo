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

// Load widgets custom functions
require_once get_template_directory() . '/widgets/widgets-function.php';

// Load API endpoints
require_once get_template_directory() . '/api/v1/posts.php';

// Include WooCommerce customizations
require_once get_template_directory() . '/woocomerces/msb-woocomerce-functions.php';

// Include Suggested Products Meta Box
require_once get_template_directory() . '/includes/class-suggested-products-meta-box.php';

// Include Widgets LESS Compiler Pro (using lessphp library)
require_once get_template_directory() . '/includes/class-widgets-less-compiler.php';

// Include Testimonials Custom Post Type
require_once get_template_directory() . '/custom-post-types/testimonials.php';