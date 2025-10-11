<?php
/**
 * Featured Products Slider Widget Display Template
 */

if (!defined('ABSPATH')) {
    exit;
}

function msb_featured_products_slider_widget($args, $instance) {
    $number = !empty($instance['number']) ? absint($instance['number']) : 6;
    $show_description = !empty($instance['show_description']) ? $instance['show_description'] : '';
    $category = !empty($instance['category']) ? absint($instance['category']) : 0; 

    echo $args['before_widget'];

    $q_args = array(
        'post_type' => 'product',
        'posts_per_page' => $number,
        'orderby' => 'date',
        'meta_query' => array(
            array(
                'key' => '_msb_featured',
                'value' => 'yes',
                'compare' => '='
            )
        ),
        'meta_key' => '_stock_status',
        'meta_value' => 'instock'
    );
    if ($category) {
        $q_args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => array($category),
            )
        );
    }
    $products = new WP_Query($q_args);

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
        echo '<p class="msb-no-products">' . __('Không có sản phẩm nổi bật nào.', 'msb-app-theme') . '</p>';
    endif;

    wp_reset_postdata();
    echo $args['after_widget'];
}
