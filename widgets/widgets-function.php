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

// Manually include featured-offers-slider-v2 widget
require_once get_template_directory() . '/widgets/featured-offers-slider-v2/featured-offers-slider-v2.php';

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


// Guarantee submit handler fake data
add_action('wp_ajax_msb_guarantee_lookup', 'msb_handle_guarantee_lookup');
add_action('wp_ajax_nopriv_msb_guarantee_lookup', 'msb_handle_guarantee_lookup');
function msb_handle_guarantee_lookup() {
    $ref_number = isset($_POST['ref_number']) ? sanitize_text_field($_POST['ref_number']) : '';
    $amount = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : '';

    if (!preg_match('/^[A-Za-z0-9]{1,10}$/', $ref_number)) {
        wp_send_json_error('Số chứng thư không hợp lệ.', 400);
        wp_die();
    }

    if (!ctype_digit($amount)) {
        wp_send_json_error('Số tiền không hợp lệ.', 400);
        wp_die();
    }

    try {
        $json_path = get_template_directory() . '/widgets/guarantee-lookup/data.json';
        $json_content = file_get_contents($json_path);
        $data = json_decode($json_content, true);
        
    error_log(print_r($data, true)); 
    } catch (Exception $e) {
         error_log('msb_handle_guarantee_lookup exception: ' . $e->getMessage());
            wp_send_json_error('Server exception', 500);
        wp_die();
    }

    $matched = null;
    foreach ($data as $item) {
        if ($item['ref'] === $ref_number && $item['amount'] === $amount) {
            $matched = $item;
            break;
        }
    }

    if (! $matched) 
        {
            wp_send_json_error('Không tìm thấy thông tin bảo lãnh', 404);
            wp_die();
        }

    if ($matched) {
        ob_start();
        echo '<div class="msb-result-block">';
        echo '<h4>Kết quả bảo lãnh</h4>';
        echo '<table class="msb-result-table">';
        foreach ($matched as $field => $value) {
            $label = msb_field_label($field);
            echo '<tr>';
            echo '<td><strong>' . esc_html($label) . '</strong></td>';
            echo '<td>' . esc_html($value) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
        $html = ob_get_clean();

        wp_send_json_success($html);
        wp_die();
    } 
}

function msb_field_label($field) {
    $labels = [
        'ref' => 'Mã bảo lãnh',
        'guarantee_type' => 'Loại bảo lãnh',
        'guaranteed_party' => 'Bên được bảo lãnh',
        'beneficiary_info' => 'Thông tin thụ hưởng',
        'purpose' => 'Mục đích bảo lãnh',
        'amount' => 'Số tiền bảo lãnh',
        'currency' => 'Loại tiền tệ',
        'issue_date' => 'Ngày phát hành',
        'effective_date' => 'Ngày bắt đầu hiệu lực',
        'expiry_date' => 'Ngày hết hạn',
        'branch' => 'Chi nhánh'
    ];

    return $labels[$field] ?? ucfirst($field);
}

function msb_enqueue_guarantee_scripts() {
    wp_enqueue_script(
        'msb-guarantee-lookup',
        get_template_directory_uri() . '/widgets/guarantee-lookup/guarantee-lookup.js',
        array(),
        filemtime(get_template_directory() . '/widgets/guarantee-lookup/guarantee-lookup.js'),
        true
    );

    wp_localize_script('msb-guarantee-lookup', 'msb_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'msb_enqueue_guarantee_scripts');


add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['widgets.php', 'post.php', 'post-new.php'], true)) return;
  
    $css_path = get_template_directory() . '/assets/css/widgets/menu-select-box.css';
    if (!file_exists($css_path)) return;
  
    wp_enqueue_style(
      'msb-menu-select-box-admin',
      get_template_directory_uri() . '/assets/css/widgets/menu-select-box.css',
      [],
      filemtime($css_path)
    );
  
  });
?>
