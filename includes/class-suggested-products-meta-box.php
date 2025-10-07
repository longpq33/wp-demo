<?php
/**
 * Suggested Products Meta Box Class
 * Meta box để quản lý sản phẩm gợi ý cho bài post
 */

class MSB_Suggested_Products_Meta_Box {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Thêm meta box vào admin
     */
    public function add_meta_box() {
        add_meta_box(
            'msb_suggested_products',
            __('Sản phẩm gợi ý', 'msb-app-theme'),
            array($this, 'meta_box_callback'),
            'post',
            'side',
            'high'
        );
    }

    /**
     * Callback function để hiển thị meta box
     */
    public function meta_box_callback($post) {
        // Add nonce field for security
        wp_nonce_field('msb_suggested_products_nonce', 'msb_suggested_products_nonce');
        
        // Get current values
        $title = get_post_meta($post->ID, '_suggested_product_title', true);
        $description = get_post_meta($post->ID, '_suggested_product_description', true);
        $link = get_post_meta($post->ID, '_suggested_product_link', true);
        ?>
        <div class="msb-suggested-products-meta-box">
            <p>
                <label for="suggested_product_title">
                    <strong><?php _e('Tiêu đề sản phẩm:', 'msb-app-theme'); ?></strong>
                </label>
                <input type="text" 
                       id="suggested_product_title" 
                       name="suggested_product_title" 
                       value="<?php echo esc_attr($title); ?>" 
                       placeholder="<?php _e('Nhập tiêu đề sản phẩm', 'msb-app-theme'); ?>"
                       class="widefat" />
            </p>
            
            <p>
                <label for="suggested_product_description">
                    <strong><?php _e('Mô tả sản phẩm:', 'msb-app-theme'); ?></strong>
                </label>
                <textarea id="suggested_product_description" 
                          name="suggested_product_description" 
                          rows="4" 
                          placeholder="<?php _e('Nhập mô tả sản phẩm', 'msb-app-theme'); ?>"
                          class="widefat"><?php echo esc_textarea($description); ?></textarea>
            </p>
            
            <p>
                <label for="suggested_product_link">
                    <strong><?php _e('Link sản phẩm:', 'msb-app-theme'); ?></strong>
                </label>
                <input type="url" 
                       id="suggested_product_link" 
                       name="suggested_product_link" 
                       value="<?php echo esc_url($link); ?>" 
                       placeholder="<?php _e('https://example.com', 'msb-app-theme'); ?>"
                       class="widefat" />
            </p>
            
            <p class="description">
                <?php _e('Tất cả các trường đều tùy chọn. Có thể để trống nếu không cần thiết.', 'msb-app-theme'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Lưu dữ liệu meta box
     */
    public function save_meta_box($post_id) {
        // Check if nonce is set
        if (!isset($_POST['msb_suggested_products_nonce'])) {
            return;
        }

        // Verify nonce
        if (!wp_verify_nonce($_POST['msb_suggested_products_nonce'], 'msb_suggested_products_nonce')) {
            return;
        }

        // Check if user has permission
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Save title
        if (isset($_POST['suggested_product_title'])) {
            $title = sanitize_text_field($_POST['suggested_product_title']);
            update_post_meta($post_id, '_suggested_product_title', $title);
        }

        // Save description
        if (isset($_POST['suggested_product_description'])) {
            $description = sanitize_textarea_field($_POST['suggested_product_description']);
            update_post_meta($post_id, '_suggested_product_description', $description);
        }

        // Save link
        if (isset($_POST['suggested_product_link'])) {
            $link = esc_url_raw($_POST['suggested_product_link']);
            update_post_meta($post_id, '_suggested_product_link', $link);
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on post edit pages
        if ($hook != 'post.php' && $hook != 'post-new.php') {
            return;
        }

        // Enqueue admin CSS
        wp_enqueue_style(
            'msb-suggested-products-admin',
            get_template_directory_uri() . '/assets/css/admin-suggested-products.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/admin-suggested-products.css')
        );
    }
}

// Initialize the meta box
new MSB_Suggested_Products_Meta_Box();
