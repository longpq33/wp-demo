<?php
if (!defined('ABSPATH')) {
    exit;
}

class MSB_WP_Text_Content_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_wp_text_content_widget',
            __('MSB Text Content Box', 'msb-app-theme'),
            [
                'description'   => __('Hiển thị dữ liệu từ DB dạng danh sách', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title'  => __('Search Box', 'msb-app-theme'),
            ]
            //array('description' => __('Hiển thị dữ liệu từ DB dạng danh sách', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        $title_color = !empty($instance['title_color']) ? esc_attr($instance['title_color']) : '#000000';
        $title_size = !empty($instance['title_size']) ? esc_attr($instance['title_size']) : 15;
        $content_color = !empty($instance['content_color']) ? esc_attr($instance['content_color']) : '#333333';
        $content_size = !empty($instance['content_size']) ? esc_attr($instance['content_size']) : 11;
        $text_color = isset($instance['text_color']) ? $instance['text_color'] : '#111827';
        ?>
            <style>
                .msb-text-content-wrapper {
                    width: 100%;
                    display: flex;
                    gap: 20px; /* khoảng cách giữa các box */
                    flex-wrap: nowrap ; /* nếu quá dài thì xuống hàng */
                }

                .msb-text-content-box {
                    flex: 1 1 30%; /* mỗi box chiếm ~30% chiều ngang, co giãn */
                    border: none;
                    padding: 0px;
                    background: 'transparent';
                    box-sizing: border-box;
                }
                 .msb-box-title {
                    font-size: <?php echo esc_attr($title_size); ?>px;
                    color: <?php echo esc_attr($title_color); ?>;
                }
                .msb-box-content {
                    font-size: <?php echo esc_attr($content_size); ?>px;
                    color: <?php echo esc_attr($content_color); ?>;
                }
            </style>
        <?php
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

       $results = [
            [
                'title'   => 'Thương hiệu Ngân hàng tốt nhất 2024',
                'content' => 'bởi Global Brand Magazine...',
                'created_at' => '2024-01-10',
            ],
            [
                'title'   => 'Thương hiệu Ngân hàng tốt nhất 2025',
                'content' => 'bởi Global Brand Magazine...',
                'created_at' => '2025-01-11',
            ],
            [
                'title'   => 'Thương hiệu Ngân hàng tốt nhất 2026',
                'content' => 'bởi Global Brand Magazine...',
                'created_at' => '2026-01-10',
            ],
        ];
        usort($results, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $count = !empty($instance['countItem']) ? intval($instance['countItem']) : 3;
        $results_to_show = array_slice($results, 0, $count);
        if (!empty($results_to_show)) {
        echo '<div class="msb-text-content-wrapper">';
        foreach ($results_to_show as $item) {
            ?>
            <div class="msb-text-content-box">
                <h3 class="msb-box-title"><?php echo esc_html($item['title']); ?></h3>
                <p class="msb-box-content"><?php echo esc_html($item['content']); ?></p>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<p>Không có dữ liệu.</p>';
    }

    echo $args['after_widget'];

    }

    public function form($instance) {
        $countItem = !empty($instance['countItem']) ? intval($instance['countItem']) : 3; //default 3 box
        $title_color = !empty($instance['title_color']) ? $instance['title_color'] : '#000000';
        $title_size = !empty($instance['title_size']) ? $instance['title_size'] : 15;
        $content_color = !empty($instance['content_color']) ? $instance['content_color'] : '#333333';
        $content_size = !empty($instance['content_size']) ? $instance['content_size'] : 11;
$text_color = isset($instance['text_color']) ? $instance['text_color'] : '#111827';
       
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
        ?>
            <p>
            <label for="<?php echo esc_attr($this->get_field_id('countItem')); ?>"><?php _e('Số lượng bài viết mới nhất cần hiển thị:', 'msb-app-theme'); ?></label>
            <input class="tiny-text" 
                id="<?php echo esc_attr($this->get_field_id('countItem')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('countItem')); ?>" 
                type="number" 
                step="1" min="1" max="10" 
                value="<?php echo esc_attr($countItem); ?>" 
                size="3">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title_color')); ?>"><?php _e('Màu chữ Title:', 'msb-app-theme'); ?></label>
                <input class="msb-color-picker" 
                    id="<?php echo esc_attr($this->get_field_id('title_color')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('title_color')); ?>" 
                    type="text" 
                    value="<?php echo esc_attr($title_color); ?>" 
                    placeholder="#000000"
                    data-default-color="#000000" >
                <small><?php _e('Nhập mã màu hex, ví dụ: #000000', 'msb-app-theme'); ?></small>
            </p>
            

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title_size')); ?>"><?php _e('Kích thước chữ Title (px):', 'msb-app-theme'); ?></label>
                <input class="tiny-text" 
                    id="<?php echo esc_attr($this->get_field_id('title_size')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('title_size')); ?>" 
                    type="number" 
                    step="1" min="8" max="72" 
                    value="<?php echo esc_attr($title_size); ?>" 
                    size="3">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('content_color')); ?>"><?php _e('Màu chữ Content:', 'msb-app-theme'); ?></label>
                <input class="msb-color-picker" 
                    id="<?php echo esc_attr($this->get_field_id('content_color')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('content_color')); ?>" 
                    type="text" 
                    value="<?php echo esc_attr($content_color); ?>" 
                    placeholder="#333333">
                <small><?php _e('Nhập mã màu hex, ví dụ: #333333', 'msb-app-theme'); ?></small>
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('content_size')); ?>"><?php _e('Kích thước chữ Content (px):', 'msb-app-theme'); ?></label>
                <input class="tiny-text" 
                    id="<?php echo esc_attr($this->get_field_id('content_size')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('content_size')); ?>" 
                    type="number" 
                    step="1" min="8" max="72" 
                    value="<?php echo esc_attr($content_size); ?>" 
                    size="3">
            </p>    

            <script>
                (function($){
                   $('.msb-color-picker').wpColorPicker();
                })(jQuery);
            </script>
                                    
        <?php
    }

    // Lưu dữ liệu form
    public function update($new_instance, $old_instance) {
        // $instance = array();

        $instance['countItem'] = (!empty($new_instance['countItem']) && is_numeric($new_instance['countItem'])) 
        ? intval($new_instance['countItem']) 
        : 3; // default 3

         $instance['title_color'] = (!empty($new_instance['title_color'])) 
        ? sanitize_hex_color($new_instance['title_color']) 
        : '#000000';

        $instance['title_size'] = (!empty($new_instance['title_size']) && is_numeric($new_instance['title_size'])) 
            ? intval($new_instance['title_size']) 
            : 15;

        $instance['content_color'] = (!empty($new_instance['content_color'])) 
            ? sanitize_hex_color($new_instance['content_color']) 
            : '#333333';

        $instance['content_size'] = (!empty($new_instance['content_size']) && is_numeric($new_instance['content_size'])) 
            ? intval($new_instance['content_size']) 
            : 11;

            $inst['text_color'] = sanitize_text_field($new_instance['text_color'] ?? '#111827');
        return $instance;
    }
}

add_action('widgets_init', function () {
    register_widget('MSB_WP_Text_Content_Widget');
    if (function_exists('delete_transient')) {
        delete_transient('siteorigin_panels_widgets');
        delete_transient('siteorigin_panels_widget_dialog_tabs');
    }
});


