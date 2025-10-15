<?php
// WooCommerce customizations: Featured product checkbox, REST meta, and admin list column

add_action('add_meta_boxes', function () {
    if (!post_type_exists('product')) { return; }
    add_meta_box(
        'msb_product_featured',
        __('Sản phẩm nổi bật', 'msb-app-theme'),
        function ($post) {
            wp_nonce_field('msb_save_featured', 'msb_featured_nonce');
            $is_featured = get_post_meta($post->ID, '_msb_featured', true) === 'yes';
            echo '<label><input type="checkbox" name="msb_featured" value="yes" ' . checked($is_featured, true, false) . '> ' . esc_html__('Đánh dấu là sản phẩm nổi bật', 'msb-app-theme') . '</label>';
        },
        'product',
        'side',
        'high'
    );
});

// Save handler
add_action('save_post_product', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (!isset($_POST['msb_featured_nonce']) || !wp_verify_nonce($_POST['msb_featured_nonce'], 'msb_save_featured')) { return; }
    if (!current_user_can('edit_post', $post_id)) { return; }

    $val = isset($_POST['msb_featured']) && $_POST['msb_featured'] === 'yes' ? 'yes' : '';
    if ($val === 'yes') {
        update_post_meta($post_id, '_msb_featured', 'yes');
    } else {
        delete_post_meta($post_id, '_msb_featured');
    }
}, 10, 1);

// Register meta for REST API
add_action('init', function () {
    if (function_exists('register_post_meta')) {
        register_post_meta('product', '_msb_featured', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function () { return current_user_can('edit_products'); }
        ));
    }
});

// Admin list column to display featured flag
add_filter('manage_edit-product_columns', function ($columns) {
    $columns['msb_featured'] = __('Nổi bật', 'msb-app-theme');
    return $columns;
});

add_action('manage_product_posts_custom_column', function ($column, $post_id) {
    if ($column === 'msb_featured') {
        echo get_post_meta($post_id, '_msb_featured', true) === 'yes' ? '✔' : '—';
    }
}, 10, 2);


add_action('add_meta_boxes', function () {
    if (!post_type_exists('product')) { return; }
    add_meta_box(
        'msb_product_featured_offer',
        __('Ưu đãi nổi bật', 'msb-app-theme'),
        function ($post) {
            wp_nonce_field('msb_save_featured_offer', 'msb_featured_offer_nonce');
            $is_featured_offer = get_post_meta($post->ID, '_msb_featured_offer', true) === 'yes';
            echo '<label><input type="checkbox" name="msb_featured_offer" value="yes" ' . checked($is_featured_offer, true, false) . '> ' . esc_html__('Đánh dấu là ưu đãi nổi bật', 'msb-app-theme') . '</label>';
        },
        'product',
        'side',
        'high'
    );
});

// Save handler for featured offer
add_action('save_post_product', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (!isset($_POST['msb_featured_offer_nonce']) || !wp_verify_nonce($_POST['msb_featured_offer_nonce'], 'msb_save_featured_offer')) { return; }
    if (!current_user_can('edit_post', $post_id)) { return; }

    $val = isset($_POST['msb_featured_offer']) && $_POST['msb_featured_offer'] === 'yes' ? 'yes' : '';
    if ($val === 'yes') {
        update_post_meta($post_id, '_msb_featured_offer', 'yes');
    } else {
        delete_post_meta($post_id, '_msb_featured_offer');
    }
}, 10, 1);

// Register meta for REST API (optional parity)
add_action('init', function () {
    if (function_exists('register_post_meta')) {
        register_post_meta('product', '_msb_featured_offer', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function () { return current_user_can('edit_products'); }
        ));
    }
});

add_filter('manage_edit-product_columns', function ($columns) {
    $columns['msb_featured_offer'] = __('Ưu đãi nổi bật', 'msb-app-theme');
    return $columns;
});

add_action('manage_product_posts_custom_column', function ($column, $post_id) {
    if ($column === 'msb_featured_offer') {
        echo get_post_meta($post_id, '_msb_featured_offer', true) === 'yes' ? '✔' : '—';
    }
}, 10, 2);

add_action( 'admin_menu', 'bbloomer_remove_payments_from_wp_sidebar_menu', 9999 );
 
function bbloomer_remove_payments_from_wp_sidebar_menu() {   
    if (!class_exists('WooCommerce')) {
        return;
    }
    
   remove_menu_page( 'admin.php?page=wc-settings&tab=checkout' );
   remove_menu_page( 'admin.php?page=wc-admin&path=/wc-pay-welcome-page' ); 
   remove_menu_page( 'admin.php?page=wc-admin&task=payments' ); 
   remove_menu_page( 'admin.php?page=wc-admin&task=woocommerce-payments' );
   remove_menu_page( 'admin.php?page=wc-settings&tab=checkout&from=PAYMENTS_MENU_ITEM' );
   
   remove_menu_page('woocommerce-marketing');
}

// Guarantee submit handler fake data
add_action('wp_ajax_msb_guarantee_lookup', 'msb_handle_guarantee_lookup');
add_action('wp_ajax_nopriv_msb_guarantee_lookup', 'msb_handle_guarantee_lookup');
function msb_handle_guarantee_lookup() {
    $ref_number = isset($_POST['ref_number']) ? sanitize_text_field($_POST['ref_number']) : '';
    $amount = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : '';

    if (!preg_match('/^[A-Za-z0-9]{1,10}$/', $ref_number)) {
        wp_send_json_error('Số chứng thư không hợp lệ.', 400);
    }

    if (!ctype_digit($amount)) {
        wp_send_json_error('Số tiền không hợp lệ.', 400);
    }

    try {
    $data = msb_fake_guarantee_data(); 
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

// Dummy data for guarantee
function msb_fake_guarantee_data() {
    return [
        [
            'ref' => 'BL00000001',
            'guarantee_type' => 'Bảo lãnh thực hiện hợp đồng',
            'guaranteed_party' => 'Công ty TNHH Alpha',
            'beneficiary_info' => 'Ngân hàng ACB, CN Hà Nội',
            'purpose' => 'Thực hiện hợp đồng thi công số 01/HĐ-2023',
            'amount' => '10000000',
            'currency' => 'VND',
            'issue_date' => '2023-01-01',
            'effective_date' => '2023-01-05',
            'expiry_date' => '2024-01-05',
            'branch' => 'CN Hà Nội'
        ],
        [
            'ref' => 'BL00000002',
            'guarantee_type' => 'Bảo lãnh tạm ứng',
            'guaranteed_party' => 'Công ty CP Beta',
            'beneficiary_info' => 'Ngân hàng Vietcombank, CN TP.HCM',
            'purpose' => 'Tạm ứng hợp đồng số 02/HĐ-2023',
            'amount' => '5000000',
            'currency' => 'VND',
            'issue_date' => '2023-02-01',
            'effective_date' => '2023-02-05',
            'expiry_date' => '2024-02-05',
            'branch' => 'CN TP.HCM'
        ],
        [
            'ref' => 'BL00000003',
            'guarantee_type' => 'Bảo lãnh thanh toán',
            'guaranteed_party' => 'Công ty TNHH Gamma',
            'beneficiary_info' => 'Ngân hàng BIDV, CN Đà Nẵng',
            'purpose' => 'Đảm bảo thanh toán đơn hàng 03/2023',
            'amount' => '7500000',
            'currency' => 'VND',
            'issue_date' => '2023-03-01',
            'effective_date' => '2023-03-05',
            'expiry_date' => '2024-03-05',
            'branch' => 'CN Đà Nẵng'
        ],
        [
            'ref' => 'BL00000004',
            'guarantee_type' => 'Bảo lãnh bảo hành',
            'guaranteed_party' => 'Công ty CP Delta',
            'beneficiary_info' => 'Ngân hàng MB Bank, CN Cần Thơ',
            'purpose' => 'Bảo hành công trình điện mặt trời',
            'amount' => '12000000',
            'currency' => 'VND',
            'issue_date' => '2023-04-01',
            'effective_date' => '2023-04-05',
            'expiry_date' => '2025-04-05',
            'branch' => 'CN Cần Thơ'
        ],
        [
            'ref' => 'BL00000005',
            'guarantee_type' => 'Bảo lãnh đấu thầu',
            'guaranteed_party' => 'Công ty TNHH Epsilon',
            'beneficiary_info' => 'Ngân hàng TPBank, CN Hà Nội',
            'purpose' => 'Tham gia gói thầu xây dựng QL1A',
            'amount' => '3000000',
            'currency' => 'VND',
            'issue_date' => '2023-05-01',
            'effective_date' => '2023-05-05',
            'expiry_date' => '2023-08-05',
            'branch' => 'CN Hà Nội'
        ],
        [
            'ref' => 'BL00000006',
            'guarantee_type' => 'Bảo lãnh bảo hành',
            'guaranteed_party' => 'Công ty CP Zeta',
            'beneficiary_info' => 'Ngân hàng SHB, CN Hải Phòng',
            'purpose' => 'Bảo hành thiết bị y tế',
            'amount' => '6500000',
            'currency' => 'VND',
            'issue_date' => '2023-06-01',
            'effective_date' => '2023-06-05',
            'expiry_date' => '2024-06-05',
            'branch' => 'CN Hải Phòng'
        ],
        [
            'ref' => 'BL00000007',
            'guarantee_type' => 'Bảo lãnh thanh toán',
            'guaranteed_party' => 'Công ty TNHH Eta',
            'beneficiary_info' => 'Ngân hàng OCB, CN Đà Lạt',
            'purpose' => 'Đảm bảo thanh toán đơn hàng quý II',
            'amount' => '8500000',
            'currency' => 'VND',
            'issue_date' => '2023-07-01',
            'effective_date' => '2023-07-05',
            'expiry_date' => '2024-07-05',
            'branch' => 'CN Đà Lạt'
        ],
        [
            'ref' => 'BL00000008',
            'guarantee_type' => 'Bảo lãnh tạm ứng',
            'guaranteed_party' => 'Công ty CP Theta',
            'beneficiary_info' => 'Ngân hàng VIB, CN Bình Dương',
            'purpose' => 'Tạm ứng thi công nhà máy',
            'amount' => '9500000',
            'currency' => 'VND',
            'issue_date' => '2023-08-01',
            'effective_date' => '2023-08-05',
            'expiry_date' => '2024-08-05',
            'branch' => 'CN Bình Dương'
        ],
        [
            'ref' => 'BL00000009',
            'guarantee_type' => 'Bảo lãnh thực hiện hợp đồng',
            'guaranteed_party' => 'Công ty TNHH Iota',
            'beneficiary_info' => 'Ngân hàng SCB, CN Nha Trang',
            'purpose' => 'Thực hiện hợp đồng cung cấp thiết bị',
            'amount' => '4000000',
            'currency' => 'VND',
            'issue_date' => '2023-09-01',
            'effective_date' => '2023-09-05',
            'expiry_date' => '2024-09-05',
            'branch' => 'CN Nha Trang'
        ],
        [
            'ref' => 'BL00000010',
            'guarantee_type' => 'Bảo lãnh bảo hành',
            'guaranteed_party' => 'Công ty CP Kappa',
            'beneficiary_info' => 'Ngân hàng Techcombank, CN Huế',
            'purpose' => 'Bảo hành lắp đặt hệ thống PCCC',
            'amount' => '10000000',
            'currency' => 'VND',
            'issue_date' => '2023-10-01',
            'effective_date' => '2023-10-05',
            'expiry_date' => '2024-10-05',
            'branch' => 'CN Huế'
        ]
    ];
}



