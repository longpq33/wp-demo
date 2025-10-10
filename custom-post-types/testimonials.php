
<?php
  function create_testimonials_type() {
      $labels = array(
          'name'               => 'Testimonials',
          'singular_name'      => 'Testimonials',
          'menu_name'          => 'Testimonials',
          'name_admin_bar'     => 'Testimonials',
          'add_new'            => 'Add Testimonials',
          'add_new_item'       => 'Add Testimonials',
          'new_item'           => 'New Testimonials',
          'edit_item'          => 'Edit Testimonials',
          'view_item'          => 'View Testimonials',
          'all_items'          => 'All Testimonials',
          'search_items'       => 'Search Testimonials',
          'not_found'          => 'No testimonials found.',
          'not_found_in_trash' => 'No testimonials found in trash.'
      );

      $args = array(
          'labels'             => $labels,
          'public'             => true,               
          'show_in_menu'       => true,              
          'menu_icon'          => 'dashicons-admin-post',
          'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
          'has_archive'        => true,
          'rewrite'            => array('slug' => 'testimonials'),
          'show_in_rest'       => true,             
          'taxonomies'         => array('category', 'testimonials_tag'), 
      );

      register_post_type('testimonials', $args);
  }
  add_action('init', 'create_testimonials_type');
?>