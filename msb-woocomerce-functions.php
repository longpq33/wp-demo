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


