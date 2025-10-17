<?php
/**
 * Posts Slider Widget
 * Hiển thị slider các bài viết
 */

class MSB_Posts_Slider_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_posts_slider',
            __('Posts Slider', 'msb-app-theme'),
            array(
                'panels_groups' => array('msb'),
                'description' => __('Posts Slider (MSB).', 'msb-app-theme'),
                'classname' => 'msb-posts-slider-widget'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('', 'msb-app-theme');
        $number = !empty($instance['number']) ? absint($instance['number']) : 6;
        $show_description = !empty($instance['show_description']) ? $instance['show_description'] : '';

        echo $args['before_widget'];
        
        if ($title) { 
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        $posts = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        ));

        if ($posts->have_posts()) :
            ?>
            <div class="msb-featured-products-slider">
                <div class="msb-slider-container">
                    <div class="msb-slider-wrapper">
                        <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                            <div class="msb-slide">
                                <div class="msb-product-card">
                                    <div class="msb-product-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php 
                                            if (has_post_thumbnail()) {
                                                the_post_thumbnail('medium', array('class' => 'msb-product-thumbnail'));
                                            } else {
                                                echo '<div class="msb-product-thumbnail msb-thumb-fallback"></div>';
                                            }
                                            ?>
                                        </a>
                                    </div>
                                    <div class="mark"></div>
                                    
                                    <div class="msb-product-info">
                                        <h3 class="msb-product-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <?php if ($show_description) : ?>
                                            <?php
                                            $raw_excerpt = get_the_excerpt();
                                            if ($raw_excerpt === '') {
                                                $raw_excerpt = wp_strip_all_tags(get_the_content(null, false, get_the_ID()));
                                            }
                                            $excerpt = wp_trim_words($raw_excerpt, 28, '…');
                                            ?>
                                            <div class="msb-product-description"><?php echo esc_html($excerpt); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <!-- Navigation arrows -->
                    <button class="msb-slider-prev" aria-label="<?php _e('Sản phẩm trước', 'msb-app-theme'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="msb-slider-next" aria-label="<?php _e('Sản phẩm tiếp', 'msb-app-theme'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
            <?php
        else :
            echo '<p class="msb-no-products">' . __('Không có bài viết nào.', 'msb-app-theme') . '</p>';
        endif;

        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 6;
        $show_description = !empty($instance['show_description']) ? 1 : 0;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Tiêu đề:', 'msb-app-theme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_description); ?> id="<?php echo $this->get_field_id('show_description'); ?>" name="<?php echo $this->get_field_name('show_description'); ?>" />
            <label for="<?php echo $this->get_field_id('show_description'); ?>"><?php _e('Hiển thị mô tả', 'msb-app-theme'); ?></label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Số sản phẩm hiển thị:', 'msb-app-theme'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 6;
       $instance['show_description'] = !empty($new_instance['show_description']) ? 1 : 0;
        
        return $instance;
    }
}

function msb_register_posts_slider_widget() {
    register_widget('MSB_Posts_Slider_Widget');
}
add_action('widgets_init', 'msb_register_posts_slider_widget');
