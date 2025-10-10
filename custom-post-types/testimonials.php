<?php
// Register Testimonials post type (WooCommerce-like setup)
function msb_register_testimonials_cpt() {
    $labels = array(
        'name'                  => __('Testimonials', 'msb-app-theme'),
        'singular_name'         => __('Testimonial', 'msb-app-theme'),
        'menu_name'             => __('Testimonials', 'msb-app-theme'),
        'name_admin_bar'        => __('Testimonial', 'msb-app-theme'),
        'add_new'               => __('Add New', 'msb-app-theme'),
        'add_new_item'          => __('Add New Testimonial', 'msb-app-theme'),
        'new_item'              => __('New Testimonial', 'msb-app-theme'),
        'edit_item'             => __('Edit Testimonial', 'msb-app-theme'),
        'view_item'             => __('View Testimonial', 'msb-app-theme'),
        'all_items'             => __('All Testimonials', 'msb-app-theme'),
        'search_items'          => __('Search Testimonials', 'msb-app-theme'),
        'parent_item_colon'     => __('Parent Testimonials:', 'msb-app-theme'),
        'not_found'             => __('No testimonials found.', 'msb-app-theme'),
        'not_found_in_trash'    => __('No testimonials found in Trash.', 'msb-app-theme'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 25,
        'menu_icon'             => 'dashicons-testimonial',
        'supports'              => array('title','editor','thumbnail','excerpt','revisions'),
        'has_archive'           => true,
        'rewrite'               => array('slug' => 'testimonials', 'with_front' => true),
        'show_in_rest'          => true,
        'publicly_queryable'    => true,
        'query_var'             => true,
        'capability_type'       => 'post',
        'map_meta_cap'          => true,
    );

    register_post_type('testimonials', $args);
}
add_action('init', 'msb_register_testimonials_cpt');

// Register hierarchical taxonomy (like product_cat)
function msb_register_testimonial_category() {
    $labels = array(
        'name'              => __('Testimonial Categories', 'msb-app-theme'),
        'singular_name'     => __('Testimonial Category', 'msb-app-theme'),
        'search_items'      => __('Search Categories', 'msb-app-theme'),
        'all_items'         => __('All Categories', 'msb-app-theme'),
        'parent_item'       => __('Parent Category', 'msb-app-theme'),
        'parent_item_colon' => __('Parent Category:', 'msb-app-theme'),
        'edit_item'         => __('Edit Category', 'msb-app-theme'),
        'update_item'       => __('Update Category', 'msb-app-theme'),
        'add_new_item'      => __('Add New Category', 'msb-app-theme'),
        'new_item_name'     => __('New Category Name', 'msb-app-theme'),
        'menu_name'         => __('Categories', 'msb-app-theme'),
    );

    register_taxonomy('testimonial_category', array('testimonials'), array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'testimonial-category'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'msb_register_testimonial_category');

// Register non-hierarchical taxonomy (like product_tag)
function msb_register_testimonial_tag() {
    $labels = array(
        'name'                       => __('Testimonial Tags', 'msb-app-theme'),
        'singular_name'              => __('Testimonial Tag', 'msb-app-theme'),
        'search_items'               => __('Search Tags', 'msb-app-theme'),
        'popular_items'              => __('Popular Tags', 'msb-app-theme'),
        'all_items'                  => __('All Tags', 'msb-app-theme'),
        'edit_item'                  => __('Edit Tag', 'msb-app-theme'),
        'update_item'                => __('Update Tag', 'msb-app-theme'),
        'add_new_item'               => __('Add New Tag', 'msb-app-theme'),
        'new_item_name'              => __('New Tag Name', 'msb-app-theme'),
        'separate_items_with_commas' => __('Separate tags with commas', 'msb-app-theme'),
        'add_or_remove_items'        => __('Add or remove tags', 'msb-app-theme'),
        'choose_from_most_used'      => __('Choose from the most used tags', 'msb-app-theme'),
        'menu_name'                  => __('Tags', 'msb-app-theme'),
    );

    register_taxonomy('testimonial_tag', array('testimonials'), array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'         => true,
        'rewrite'           => array('slug' => 'testimonial-tag'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'msb_register_testimonial_tag');

// Use Classic Editor UI (like WooCommerce screens)
add_filter('use_block_editor_for_post_type', function($use_block, $post_type){
    if ($post_type === 'testimonials') return false;
    return $use_block;
}, 10, 2);

// Main meta box with tabs (General, Media)
add_action('add_meta_boxes', function(){
    add_meta_box(
        'msb_testimonial_details',
        __('Testimonial Details', 'msb-app-theme'),
        'msb_render_testimonial_details_metabox',
        'testimonials',
        'normal',
        'high'
    );
});

function msb_render_testimonial_details_metabox($post){
    wp_nonce_field('msb_testimonial_save', 'msb_testimonial_nonce');
    $user_name = get_post_meta($post->ID, '_msb_testimonial_user_name', true);
    $image_id  = (int) get_post_meta($post->ID, '_msb_testimonial_image_id', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
?>
    <div class="msb-tabs" style="margin-top:6px;">
        <ul class="msb-tabs__nav" style="margin:0 0 10px;display:flex;gap:6px;">
            <li><a href="#msb-tab-general" class="button button-secondary msb-tab-link is-active"><?php echo esc_html__('General', 'msb-app-theme'); ?></a></li>
            <li><a href="#msb-tab-media" class="button button-secondary msb-tab-link"><?php echo esc_html__('Media', 'msb-app-theme'); ?></a></li>
        </ul>
        <div id="msb-tab-general" class="msb-tab-panel" style="display:block;">
            <p>
                <label for="msb_testimonial_user_name" style="display:block;font-weight:600;margin-bottom:4px;"><?php echo esc_html__('User name', 'msb-app-theme'); ?></label>
                <input type="text" id="msb_testimonial_user_name" name="msb_testimonial_user_name" value="<?php echo esc_attr($user_name); ?>" style="width:100%;max-width:480px;" />
            </p>
            <p style="color:#666;margin-top:8px;"><?php echo esc_html__('Use main editor for testimonial content.', 'msb-app-theme'); ?></p>
        </div>
        <div id="msb-tab-media" class="msb-tab-panel" style="display:none;">
            <p>
                <label style="display:block;font-weight:600;margin-bottom:4px;"><?php echo esc_html__('Testimonial image', 'msb-app-theme'); ?></label>
                <input type="hidden" id="msb_testimonial_image_id" name="msb_testimonial_image_id" value="<?php echo esc_attr($image_id); ?>" />
                <button type="button" class="button" id="msb_testimonial_image_btn"><?php echo esc_html__('Select image', 'msb-app-theme'); ?></button>
                <button type="button" class="button" id="msb_testimonial_image_remove" style="margin-left:6px;"><?php echo esc_html__('Remove', 'msb-app-theme'); ?></button>
                <div id="msb_testimonial_image_preview" style="margin-top:10px;">
                    <?php if ($image_url) : ?><img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width:120px;height:auto;border:1px solid #ddd;padding:2px;border-radius:4px;" /><?php endif; ?>
                </div>
            </p>
            <p style="color:#666;margin-top:6px;"><?php echo esc_html__('Tip: You can also set Featured Image (right sidebar).', 'msb-app-theme'); ?></p>
        </div>
    </div>
    <script>
    jQuery(function($){
        $('.msb-tab-link').on('click', function(e){
            e.preventDefault();
            var target = $(this).attr('href');
            $('.msb-tab-link').removeClass('is-active');
            $(this).addClass('is-active');
            $('.msb-tab-panel').hide();
            $(target).show();
        });
        var frame;
        $('#msb_testimonial_image_btn').on('click', function(e){
            e.preventDefault();
            if(frame){ frame.open(); return; }
            frame = wp.media({ title: 'Select image', button: { text: 'Use this image' }, multiple: false });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                var thumb = (attachment.sizes && attachment.sizes.thumbnail) ? attachment.sizes.thumbnail.url : attachment.url;
                $('#msb_testimonial_image_id').val(attachment.id);
                $('#msb_testimonial_image_preview').html('<img src="'+thumb+'" style="max-width:120px;height:auto;border:1px solid #ddd;padding:2px;border-radius:4px;" />');
            });
            frame.open();
        });
        $('#msb_testimonial_image_remove').on('click', function(){
            $('#msb_testimonial_image_id').val('');
            $('#msb_testimonial_image_preview').empty();
        });
    });
    </script>
<?php }

// Save meta
add_action('save_post_testimonials', function($post_id){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if(!current_user_can('edit_post', $post_id)) return;
    if(isset($_POST['msb_testimonial_nonce']) && wp_verify_nonce($_POST['msb_testimonial_nonce'], 'msb_testimonial_save')){
        $user_name = isset($_POST['msb_testimonial_user_name']) ? sanitize_text_field($_POST['msb_testimonial_user_name']) : '';
        $image_id  = isset($_POST['msb_testimonial_image_id']) ? (int) $_POST['msb_testimonial_image_id'] : 0;
        update_post_meta($post_id, '_msb_testimonial_user_name', $user_name);
        if($image_id){ update_post_meta($post_id, '_msb_testimonial_image_id', $image_id); } else { delete_post_meta($post_id, '_msb_testimonial_image_id'); }
    }
    $featured  = isset($_POST['msb_testimonial_featured']) ? 'yes' : 'no';
    $highlight = isset($_POST['msb_testimonial_highlight']) ? 'yes' : 'no';
    update_post_meta($post_id, '_msb_testimonial_featured', $featured);
    update_post_meta($post_id, '_msb_testimonial_highlight', $highlight);
});


