<?php
/**
 * Latest Posts Widget
 */

class MSB_Latest_Posts_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			' msb_latest_posts',
			__('Latest Posts(MSB)', 'msb-app-theme'),
			array(
				'classname' => 'msb-latest-posts-widget',
				'description' => __('Hiển thị 3 bài post mới nhất', 'msb-app-theme'),
				'panels_groups' => array('msb')
			)
		);
	}

	public function widget($args, $instance) {
		$title = !empty($instance['title']) ? $instance['title'] : __('', 'msb-app-theme');
    $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : __('', 'msb-app-theme');
    $description = !empty($instance['description']) ? $instance['description'] : __('', 'msb-app-theme');
		$count = !empty($instance['count']) ? min(6, max(1, absint($instance['count']))) : 3;
		$show_date = isset($instance['show_date']) ? (bool)$instance['show_date'] : true;
		$excerpt_words = !empty($instance['excerpt_words']) ? absint($instance['excerpt_words']) : 28;
		$cta_label = !empty($instance['cta_label']) ? $instance['cta_label'] : __('Tìm hiểu', 'msb-app-theme');
		$category = !empty($instance['category']) ? absint($instance['category']) : 0;

		echo $args['before_widget'];
		if (!empty($title)) {
			echo '<div class="lp-sg-title">' . esc_html($title) . '</div>';
		}
    if (!empty($subtitle)) {
      echo '<div class="lp-sg-subtitle">' . esc_html($subtitle) . '</div>';
    }
    if (!empty($description)) {
      echo '<div class="lp-sg-description">' . esc_html($description) . '</div>';
    }

		$q_args = array(
			'post_type' => 'post',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
		);
		if ($category) {
			$q_args['cat'] = $category;
		}
		$loop = new WP_Query($q_args);

		if ($loop->have_posts()) {
			echo '<div class="lp-grid">';
			while ($loop->have_posts()) { $loop->the_post();
				$post_id = get_the_ID();
				$s_title = get_post_meta($post_id, '_suggested_product_title', true);
				$s_desc = get_post_meta($post_id, '_suggested_product_description', true);
				$s_link = get_post_meta($post_id, '_suggested_product_link', true);
				echo '<article class="lp-card">';
					// image
					echo '<a class="lp-thumb" href="' . esc_url(get_permalink()) . '">';
						if (has_post_thumbnail()) {
							echo get_the_post_thumbnail($post_id, 'large', array('class' => 'lp-thumb-img'));
						} else {
							echo '<div class="lp-thumb-fallback"></div>';
						}
					echo '</a>';

					echo '<div class="lp-body">';
						if ($show_date) {
							echo '<div class="lp-date">' . esc_html(get_the_date('d/m/Y')) . '</div>';
						}
						echo '<h3 class="lp-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
						$excerpt = wp_trim_words(get_the_content(), $excerpt_words, '…');
						echo '<div class="lp-excerpt">' . esc_html($excerpt) . '</div>';

						// Suggested product block (render only if any field exists)
						if (!empty($s_title) || !empty($s_desc) || !empty($s_link)) {
							echo '<div class="lp-suggested">';
								echo '<div class="lp-suggested-label">' . esc_html__('Sản phẩm gợi ý', 'msb-app-theme') . '</div>';
								echo '<div class="lp-suggested-card">';
									if (!empty($s_title)) {
										echo '<div class="lp-sg-title">' . esc_html($s_title) . '</div>';
									}
                  echo '<div class="lp-sg-content">';
                    if (!empty($s_desc)) {
                      echo '<div class="lp-sg-desc">' . esc_html($s_desc) . '</div>';
                    }
                    if (!empty($s_link)) {
                      echo '<a class="lp-sg-cta" href="' . esc_url($s_link) . '">' . esc_html($cta_label) . '</a>';
                    }
                  echo '</div>';								
								echo '</div>';
							echo '</div>';
						}
					echo '</div>'; // lp-body

				echo '</article>';
			}
			echo '</div>';
			wp_reset_postdata();
		}

		echo $args['after_widget'];
	}

	public function form($instance) {
		$title = isset($instance['title']) ? $instance['title'] : __('', 'msb-app-theme');
    $subtitle = isset($instance['subtitle']) ? $instance['subtitle'] : __('', 'msb-app-theme');
    $description = isset($instance['description']) ? $instance['description'] : __('', 'msb-app-theme');
		$count = isset($instance['count']) ? absint($instance['count']) : 3;
		$show_date = isset($instance['show_date']) ? (bool)$instance['show_date'] : true;
		$excerpt_words = isset($instance['excerpt_words']) ? absint($instance['excerpt_words']) : 28;
		$cta_label = isset($instance['cta_label']) ? $instance['cta_label'] : __('Tìm hiểu', 'msb-app-theme');
		$category = isset($instance['category']) ? absint($instance['category']) : 0;
?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Tiêu đề:', 'msb-app-theme'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
	</p>
  <p>
		<label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Tiêu đề phụ:', 'msb-app-theme'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>">
	</p>
  <p>
		<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Mô tả:', 'msb-app-theme'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo esc_attr($description); ?>">
	</p>
	<!-- <p>
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Số bài hiển thị:', 'msb-app-theme'); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" step="1" min="1" max="6" value="<?php echo esc_attr($count); ?>" size="3">
	</p> -->
	<p>
		<input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" />
		<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Hiển thị ngày đăng', 'msb-app-theme'); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('excerpt_words'); ?>"><?php _e('Số từ của tóm tắt:', 'msb-app-theme'); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id('excerpt_words'); ?>" name="<?php echo $this->get_field_name('excerpt_words'); ?>" type="number" step="1" min="10" max="80" value="<?php echo esc_attr($excerpt_words); ?>" size="3">
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cta_label'); ?>"><?php _e('CTA label:', 'msb-app-theme'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('cta_label'); ?>" name="<?php echo $this->get_field_name('cta_label'); ?>" type="text" value="<?php echo esc_attr($cta_label); ?>">
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Chuyên mục:', 'msb-app-theme'); ?></label>
		<?php
			wp_dropdown_categories(array(
				'show_option_all' => __('Tất cả', 'msb-app-theme'),
				'name' => $this->get_field_name('category'),
				'id' => $this->get_field_id('category'),
				'class' => 'widefat',
				'selected' => $category,
				'hide_empty' => false,
			));
		?>
	</p>
<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
    $instance['subtitle'] = sanitize_text_field($new_instance['subtitle'] ?? '');
    $instance['description'] = sanitize_text_field($new_instance['description'] ?? '');
		$instance['count'] = absint($new_instance['count'] ?? 3);
		$instance['show_date'] = !empty($new_instance['show_date']) ? 1 : 0;
		$instance['excerpt_words'] = absint($new_instance['excerpt_words'] ?? 28);
		$instance['cta_label'] = sanitize_text_field($new_instance['cta_label'] ?? '');
		$instance['category'] = absint($new_instance['category'] ?? 0);
		return $instance;
	}
}

function msb_register_latest_posts_widget() {
	register_widget('MSB_Latest_Posts_Widget');
}
add_action('widgets_init', 'msb_register_latest_posts_widget');
