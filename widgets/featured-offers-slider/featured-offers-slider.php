<?php
/**
 * Featured Products Slider Widget
 * Hiển thị slider các sản phẩm nổi bật
 */

class MSB_Featured_Offer_Products_Slider_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msb_featured_offers_products_slider',
            __('Featured Offer Products Slider', 'msb-app-theme'),
            array(
                'panels_groups' => array('msb'),
                'description' => __('Products Offers Featured Slider (MSB).', 'msb-app-theme'),
                'classname' => 'msb-featured-products-slider-widget'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Sản phẩm nổi bật', 'msb-app-theme');
        $number = !empty($instance['number']) ? absint($instance['number']) : 6;
        $show_price = !empty($instance['show_price']) ? 1 : 0;
        $show_rating = !empty($instance['show_rating']) ? 1 : 0;
        $autoplay = !empty($instance['autoplay']) ? 1 : 0;
        $autoplay_speed = !empty($instance['autoplay_speed']) ? absint($instance['autoplay_speed']) : 3000;
        $show_description = !empty($instance['show_description']) ? $instance['show_description'] : '';

        echo $args['before_widget'];
        
        if ($title) { 
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        // Query sản phẩm nổi bật
        $products = new WP_Query(array(
            'post_type' => 'product',
            'posts_per_page' => $number,
            'meta_query' => array(
                array(
                    'key' => '_msb_featured_offer',
                    'value' => 'yes',
                    'compare' => '='
                )
            ),
            'meta_key' => '_stock_status',
            'meta_value' => 'instock'
        ));

        if ($products->have_posts()) :
            ?>
            <div class="msb-featured-products-slider" 
                 data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
                 data-autoplay-speed="<?php echo $autoplay_speed; ?>">
                <div class="msb-slider-container">
                    <div class="msb-slider-wrapper">
                        <?php while ($products->have_posts()) : $products->the_post(); 
                            global $product;
                            ?>
                            <div class="msb-slide">
                                <div class="msb-product-card">
                                    <div class="msb-product-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php 
                                            if (has_post_thumbnail()) {
                                                the_post_thumbnail('medium', array('class' => 'msb-product-thumbnail'));
                                            } else {
                                                echo '<img src="' . wc_placeholder_img_src() . '" alt="' . get_the_title() . '" class="msb-product-thumbnail">';
                                            }
                                            ?>
                                        </a>
                                        <?php if ($product->is_on_sale()) : ?>
                                            <span class="msb-sale-badge">Sale</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mark"></div>
                                    
                                    <div class="msb-product-info">
                                        <h3 class="msb-product-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <?php if ($show_description) : ?>
                                            <div class="msb-product-description">
                                                <?php echo $product->get_description(); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($show_rating && $product->get_average_rating()) : ?>
                                            <div class="msb-product-rating">
                                                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($show_price) : ?>
                                            <div class="msb-product-price">
                                                <?php echo $product->get_price_html(); ?>
                                            </div>
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
                
                <!-- Dots indicator -->
                <!-- <div class="msb-slider-dots"></div> -->
            </div>
            <?php
        else :
            echo '<p class="msb-no-products">' . __('Không có sản phẩm nổi bật nào.', 'msb-app-theme') . '</p>';
        endif;

        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 6;
        $show_price = !empty($instance['show_price']) ? 1 : 0;
        $show_rating = !empty($instance['show_rating']) ? 1 : 0;
        $autoplay = !empty($instance['autoplay']) ? 1 : 0;
        $autoplay_speed = !empty($instance['autoplay_speed']) ? absint($instance['autoplay_speed']) : 3000;
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
        
        <!-- <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo $this->get_field_id('show_price'); ?>" name="<?php echo $this->get_field_name('show_price'); ?>" />
            <label for="<?php echo $this->get_field_id('show_price'); ?>"><?php _e('Hiển thị giá', 'msb-app-theme'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo $this->get_field_id('show_rating'); ?>" name="<?php echo $this->get_field_name('show_rating'); ?>" />
            <label for="<?php echo $this->get_field_id('show_rating'); ?>"><?php _e('Hiển thị đánh giá', 'msb-app-theme'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($autoplay); ?> id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" />
            <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Tự động chạy', 'msb-app-theme'); ?></label>
        </p> -->
        
        <!-- <p>
            <label for="<?php echo $this->get_field_id('autoplay_speed'); ?>"><?php _e('Tốc độ tự động (ms):', 'msb-app-theme'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('autoplay_speed'); ?>" name="<?php echo $this->get_field_name('autoplay_speed'); ?>" type="number" step="100" min="1000" value="<?php echo esc_attr($autoplay_speed); ?>" size="5">
        </p> -->
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 6;
        $instance['show_price'] = !empty($new_instance['show_price']) ? 1 : 0;
        $instance['show_rating'] = !empty($new_instance['show_rating']) ? 1 : 0;
        $instance['autoplay'] = !empty($new_instance['autoplay']) ? 1 : 0;
        $instance['autoplay_speed'] = (!empty($new_instance['autoplay_speed'])) ? absint($new_instance['autoplay_speed']) : 3000;
        $instance['show_description'] = !empty($new_instance['show_description']) ? 1 : 0;
        
        return $instance;
    }
}

// Register widget
function msb_register_featured_offer_products_slider_widget() {
    register_widget('MSB_Featured_Offer_Products_Slider_Widget');
}
add_action('widgets_init', 'msb_register_featured_offer_products_slider_widget');
