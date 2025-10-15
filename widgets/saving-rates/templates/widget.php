<?php
function msb_saving_rates_widget($args, $instance){
    $title = isset($instance['title']) ? $instance['title'] : '';
    $default_package = isset($instance['default_package']) ? $instance['default_package'] : 'periodic';
    $default_bucket = isset($instance['default_bucket']) ? $instance['default_bucket'] : '0_6';

    $json_path = get_template_directory() . '/widgets/interest-calculator/data/rates.json';
    $data = array();
    if (file_exists($json_path)) {
        $data = json_decode(file_get_contents($json_path), true);
    }

    $js_path = get_template_directory() . '/widgets/saving-rates/js/frontend.js';
    if (file_exists($js_path)) {
        wp_enqueue_script('msb-saving-rates', get_template_directory_uri() . '/widgets/saving-rates/js/frontend.js', array('jquery'), filemtime($js_path), true);
        wp_localize_script('msb-saving-rates', 'MSBSavingRatesData', array(
            'data' => $data,
            'labels' => array(
                'online' => __('Gửi trực tuyến','msb-app-theme'),
                'counter' => __('Gửi tại quầy','msb-app-theme'),
                'packages' => array(
                    'highest'=>__('Lãi suất cao nhất','msb-app-theme'),
                    'partial'=>__('Rút gốc từng phần','msb-app-theme'),
                    'periodic'=>__('Định kỳ sinh lời','msb-app-theme'),
                    'pay_now'=>__('Trả lãi ngay','msb-app-theme'),
                    'bee'=>__('Ong Vàng','msb-app-theme'),
                    'sprout'=>__('Măng non','msb-app-theme'),
                    'deposit_contract'=>__('Hợp đồng tiền gửi','msb-app-theme'),
                )
            ),
            'defaults' => array('package'=>$default_package, 'bucket'=>$default_bucket)
        ));
    }

    echo $args['before_widget'];
   
    echo '<div class="msb-saving-rates" data-default-package="'.esc_attr($default_package).'" data-default-bucket="'.esc_attr($default_bucket).'">';
    echo '  <div class="msb-sr-controls">';
    if ($title) echo '<div><h3 class="msb-sr-title">' . esc_html($title) . '</h3></div>';
    echo '    <div class="msb-sr-buckets">';
    echo '      <label><input type="radio" name="msb-sr-bucket" value="0_6"> <span>'.__('0–6 tháng','msb-app-theme').'</span></label>';
    echo '      <label><input type="radio" name="msb-sr-bucket" value="6_12"><span> '.__('6–12 tháng','msb-app-theme').'</span></label>';
    echo '      <label><input type="radio" name="msb-sr-bucket" value=">12"><span> '.__('trên 12 tháng','msb-app-theme').'</span></label>';
    echo '    </div>';
    echo '    <div class="msb-sr-package">';
    echo '      <select class="msb-sr-package-select"></select>';
    echo '    </div>';
    echo '  </div>';
    echo '  <div class="msb-sr-table-wrap">';
    echo '    <table class="msb-sr-table">';
    echo '      <thead><tr><th>'.__('Kỳ hạn','msb-app-theme').'</th><th>'.__('Gửi tại quầy','msb-app-theme').'</th><th>'.__('Gửi trực tuyến','msb-app-theme').'</th></tr></thead>';
    echo '      <tbody></tbody>';
    echo '    </table>';
    echo '  </div>';
    echo '</div>';
    echo $args['after_widget'];
}
?>


