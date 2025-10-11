<?php
/**
 * Featured Offers Slider Widget Display Function
 */

if (!defined('ABSPATH')) {
    exit;
}

function msb_featured_offers_slider_widget($args, $instance) {
    $number = !empty($instance['number']) ? absint($instance['number']) : 6;
    $show_description = !empty($instance['show_description']) ? $instance['show_description'] : '';
    $show_categories = !empty($instance['show_categories']) ? 1 : 0;
    $category = !empty($instance['category']) ? absint($instance['category']) : 0; // product_cat term id

    echo $args['before_widget'];
    
    $q_args = array(
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
        $all_term_ids = array();
        $all_terms = array();
        while ($products->have_posts()) { $products->the_post();
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $t) {
                    $all_term_ids[$t->term_id] = true;
                    $all_terms[$t->term_id] = $t;
                }
            }
        }
        $products->rewind_posts();
        ?>
        <div class="msb-featured-products-slider" 
             data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
             data-autoplay-speed="<?php echo $autoplay_speed; ?>">
            <?php if ($show_categories && !empty($all_terms)) : ?>
            <div class="msb-cat-filter">
                <?php foreach ($all_terms as $term) : 
                    $term_name_lc = function_exists('mb_strtolower') ? mb_strtolower($term->name) : strtolower($term->name);
                    $term_slug = $term->slug;
                    if (in_array($term_name_lc, array('cá nhân','ca nhan','doanh nghiệp','doanh nghiep'), true) || in_array($term_slug, array('ca-nhan','doanh-nghiep'), true)) {
                        continue;
                    }
                ?>
                    <button type="button" class="msb-cat-chip" data-term="<?php echo esc_attr($term->term_id); ?>"><?php echo esc_html($term->name); ?></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="msb-slider-container">
                <div class="msb-slider-wrapper">
                    <?php while ($products->have_posts()) : $products->the_post(); 
                        global $product;
                        ?>
                        <?php 
                            $terms = get_the_terms(get_the_ID(), 'product_cat');
                            $term_ids = array();
                            if (!empty($terms) && !is_wp_error($terms)) {
                                foreach ($terms as $t) { $term_ids[] = $t->term_id; }
                            }
                            $data_terms = !empty($term_ids) ? implode(',', array_map('intval', $term_ids)) : '';
                        ?>
                        <div class="msb-slide" data-term-ids="<?php echo esc_attr($data_terms); ?>">
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
