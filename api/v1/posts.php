<?php

// Register: GET /wp-json/msb/v1/posts
add_action('rest_api_init', function () {
    register_rest_route('api/v1', '/posts', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'msb_app_api_get_posts',
        'permission_callback' => '__return_true',
        'args' => array(
            'page' => array(
                'type' => 'integer',
                'default' => 1,
                'sanitize_callback' => 'absint',
            ),
            'per_page' => array(
                'type' => 'integer',
                'default' => 10,
                'sanitize_callback' => 'absint',
            ),
            'search' => array(
                'type' => 'string',
                'required' => false,
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'category' => array(
                'type' => 'integer',
                'required' => false,
                'sanitize_callback' => 'absint',
            ),
            'post_type' => array(
                'type' => 'string',
                'default' => 'post',
                'sanitize_callback' => 'sanitize_key',
            ),
        ),
    ));
});

function msb_app_api_get_posts(WP_REST_Request $request): WP_REST_Response {
    $page      = max(1, (int) $request->get_param('page'));
    $per_page  = min(50, max(1, (int) $request->get_param('per_page')));
    $search    = $request->get_param('search');
    $category  = $request->get_param('category');
    $post_type = $request->get_param('post_type') ?: 'post';

    $args = array(
        'post_type'           => $post_type,
        'post_status'         => 'publish',
        'paged'               => $page,
        'posts_per_page'      => $per_page,
        'ignore_sticky_posts' => true,
        's'                   => $search ? $search : '',
        'no_found_rows'       => false,
    );

    if (!empty($category) && $post_type === 'post') {
        $args['cat'] = (int) $category;
    }

    $query = new WP_Query($args);

    $items = array();
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $items[] = array(
            'id' => $post_id,
            'title' => get_the_title(),
            'excerpt' => wp_strip_all_tags(get_the_excerpt()),
            'content' => apply_filters('the_content', get_the_content(null, false, $post_id)),
            'link' => get_permalink($post_id),
            'slug' => get_post_field('post_name', $post_id),
            'date' => get_the_date(DATE_ATOM, $post_id),
            'modified' => get_the_modified_date(DATE_ATOM, $post_id),
            'author' => array(
                'id' => (int) get_post_field('post_author', $post_id),
                'name' => get_the_author(),
            ),
            'featured_image' => get_the_post_thumbnail_url($post_id, 'large') ?: '',
            'categories' => wp_get_post_categories($post_id),
            'tags' => wp_get_post_tags($post_id, array('fields' => 'ids')),
        );
    }
    wp_reset_postdata();

    $total      = (int) $query->found_posts;
    $total_page = (int) ceil($total / $per_page);

    $response = new WP_REST_Response($items, 200);
    $response->header('X-WP-Total', (string) $total);
    $response->header('X-WP-TotalPages', (string) $total_page);
    return $response;
}


