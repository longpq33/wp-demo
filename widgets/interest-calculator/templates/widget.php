<?php
/**
 * Interest Calculator Display
 */

function msb_interest_calculator_widget($args, $instance) {
    $amount_default = isset($instance['amount_default']) ? intval($instance['amount_default']) : 0;
    $button = isset($instance['button']) ? $instance['button'] : array('label' => __('Tiến hành mở','msb-app-theme'),'url'=>'','color'=>'#f97316');
    // Load datasets from JSON for maintainability
    $json_path = get_template_directory() . '/widgets/interest-calculator/data/rates.json';
    $packages_by_type = array();
    $rates = array();
    if (file_exists($json_path)) {
        $json = json_decode(file_get_contents($json_path), true);
        if (is_array($json)) {
            $packages_by_type = isset($json['packagesByType']) ? $json['packagesByType'] : array();
            $rates = isset($json['rates']) ? $json['rates'] : array();
        }
    }

    // Enqueue frontend assets
    $js_path = get_template_directory() . '/widgets/interest-calculator/js/frontend.js';
    if (file_exists($js_path)) {
        wp_enqueue_script('msb-interest-calculator', get_template_directory_uri() . '/widgets/interest-calculator/js/frontend.js', array('jquery'), filemtime($js_path), true);
        wp_localize_script('msb-interest-calculator', 'MSBInterestData', array(
            'packagesByType' => $packages_by_type,
            'rates' => $rates,
            'buttonUrl' => esc_url($button['url'] ?? ''),
            'termsByType' => isset($json['termsByType']) ? $json['termsByType'] : array(),
        ));
    }

    $button_style = !empty($button['color']) ? ' style="background-color:' . esc_attr($button['color']) . '"' : '';

    echo $args['before_widget'];
    echo '<div class="msb-interest-calculator">';
    echo '  <div class="msb-ic-left">';
    echo '    <label>' . esc_html__('Số tiền gửi','msb-app-theme') . '</label>';
    echo '    <input type="text" class="msb-ic-amount" value="">';
    echo '    <small class="msb-ic-amount-hint"></small>';
    echo '    <label>' . esc_html__('Hình thức gửi','msb-app-theme') . '</label>';
    echo '    <select class="msb-ic-type">';
    echo '      <option value="online">' . esc_html__('Tiền gửi có kỳ hạn trực tuyến','msb-app-theme') . '</option>';
    echo '      <option value="counter">' . esc_html__('Tiền gửi có kỳ hạn tại quầy','msb-app-theme') . '</option>';
    echo '    </select>';
    echo '    <label>' . esc_html__('Gói sản phẩm','msb-app-theme') . '</label>';
    echo '    <select class="msb-ic-package"></select>';
    echo '    <label>' . esc_html__('Kỳ hạn','msb-app-theme') . '</label>';
    echo '    <select class="msb-ic-term">';
    $terms = array('1d'=>'1 ngày','1m'=>'1 tháng','2m'=>'2 tháng','3m'=>'3 tháng','4m'=>'4 tháng','5m'=>'5 tháng','6m'=>'6 tháng','7m'=>'7 tháng','8m'=>'8 tháng','9m'=>'9 tháng','10m'=>'10 tháng','11m'=>'11 tháng','12m'=>'12 tháng','13m'=>'13 tháng','15m'=>'15 tháng','18m'=>'18 tháng','24m'=>'24 tháng','36m'=>'36 tháng');
    foreach ($terms as $k => $label) echo '<option value="' . esc_attr($k) . '">' . esc_html($label) . '</option>';
    echo '    </select>';
    echo '  </div>';

    echo '  <div class="msb-ic-right">';
    echo '    <div class="msb-ic-total"><span class="msb-ic-total-label">' . esc_html__('Gốc + lãi dự kiến','msb-app-theme') . '</span></div>';
    echo '    <div><span class="msb-ic-total-value">—</span><span class="msb-ic-total-currency"> VND</span> </div>';
    echo '    <div class="msb-ic-row"><span>' . esc_html__('Tiền lãi','msb-app-theme') . '</span><span class="msb-ic-interest">— VND</span></div>';
    echo '    <div class="msb-ic-row"><span>' . esc_html__('Lãi suất','msb-app-theme') . '</span><span class="msb-ic-rate">— %</span></div>';
    echo '    <div class="row">';
    echo '    <div class="msb-ic-updated">' . esc_html__('Lãi suất cập nhật','msb-app-theme') . '</div>';
    echo '    <a class="msb-ic-cta" href="' . esc_url($button['url']) . '"' . $button_style . '>' . esc_html($button['label']) . '</a>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
    echo $args['after_widget'];
}


