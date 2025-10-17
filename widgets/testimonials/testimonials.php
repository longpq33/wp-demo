<?php
/*
 * Widget: Testimonials (title + user name)
 */

if (!class_exists('MSB_Testimonials_Widget')) {
    class MSB_Testimonials_Widget extends WP_Widget {
        public function __construct() {
            parent::__construct(
              'msb_testimonials_widget',
              __('Testimonials', 'msb-app-theme'),
              [
                  'description'   => __('Testimonials (MSB)', 'msb-app-theme'),
                  'panels_groups' => array('msb'),
                  'panels_title'  => __('Search Box', 'msb-app-theme'),
              ]
          );
        }

        public function form($instance) {
            $title   = isset($instance['title']) ? $instance['title'] : '';
            $count   = isset($instance['count']) ? (int)$instance['count'] : 3;
            $order   = isset($instance['order']) ? $instance['order'] : 'DESC';
            $cat     = isset($instance['cat']) ? (int)$instance['cat'] : 0;
            // Taxonomy dropdown
            $terms = get_terms(array('taxonomy' => 'testimonial_category', 'hide_empty' => false));
?>
            <p>
                <label><?php echo esc_html__('Tiêu đề', 'msb-app-theme'); ?></label>
                <input class="widefat" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label><?php echo esc_html__('Số lượng', 'msb-app-theme'); ?></label>
                <input name="<?php echo esc_attr($this->get_field_name('count')); ?>" type="number" min="1" value="<?php echo esc_attr($count); ?>" />
            </p>
            <p>
                <label><?php echo esc_html__('Sắp xếp', 'msb-app-theme'); ?></label>
                <select name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                    <option value="DESC" <?php selected($order, 'DESC'); ?>>DESC</option>
                    <option value="ASC" <?php selected($order, 'ASC'); ?>>ASC</option>
                </select>
            </p>
            <p>
                <label><?php echo esc_html__('Danh mục (tuỳ chọn)', 'msb-app-theme'); ?></label>
                <select name="<?php echo esc_attr($this->get_field_name('cat')); ?>" class="widefat">
                    <option value="0">— <?php echo esc_html__('Tất cả', 'msb-app-theme'); ?> —</option>
                    <?php foreach ($terms as $t): ?>
                        <option value="<?php echo esc_attr($t->term_id); ?>" <?php selected($cat, $t->term_id); ?>><?php echo esc_html($t->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
<?php
        }

        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
            $instance['count'] = max(1, (int)($new_instance['count'] ?? 3));
            $instance['order'] = in_array(($new_instance['order'] ?? 'DESC'), array('ASC','DESC'), true) ? $new_instance['order'] : 'DESC';
            $instance['cat']   = (int)($new_instance['cat'] ?? 0);
            return $instance;
        }

        public function widget($args, $instance) {
            $title = !empty($instance['title']) ? $instance['title'] : '';
            $count = !empty($instance['count']) ? (int)$instance['count'] : 3;
            $order = !empty($instance['order']) ? $instance['order'] : 'DESC';
            $cat   = !empty($instance['cat']) ? (int)$instance['cat'] : 0;

            echo $args['before_widget'];
            if (!empty($title)) {
                echo $args['before_title'] . esc_html($title) . $args['after_title'];
            }

            $query_args = array(
                'post_type'      => 'testimonials',
                'posts_per_page' => $count,
                'order'          => $order,
                'orderby'        => 'date',
            );
            if ($cat) {
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'testimonial_category',
                        'field'    => 'term_id',
                        'terms'    => $cat,
                    ),
                );
            }

            $q = new WP_Query($query_args);
            if ($q->have_posts()):
?>
                <div class="msb-testimonials">
                    <div class="msb-testimonials__grid">
                        <?php while ($q->have_posts()): $q->the_post();
                            $user_name = get_post_meta(get_the_ID(), '_msb_testimonial_user_name', true);
                            if (!$user_name) {
                                $author = get_userdata(get_post_field('post_author', get_the_ID()));
                                $user_name = $author ? $author->display_name : '';
                            }
                        ?>
                            <article class="msb-testimonial">
                                <h3 class="msb-testimonial__title"><?php the_title(); ?></h3>
                                <?php if ($user_name): ?>
                                <div class="msb-testimonial__meta">
                                    <span>
                                        bởi
                                    </span>
                                    <?php echo esc_html($user_name); ?>
                                </div>
                                <?php endif; ?>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
<?php
            else:
                echo '<p>' . esc_html__('Chưa có testimonials.', 'msb-app-theme') . '</p>';
            endif;

            echo $args['after_widget'];
        }
    }
}

// Register widget (works with theme autoloader calling all widgets/*.php)
add_action('widgets_init', function(){
    register_widget('MSB_Testimonials_Widget');
});


