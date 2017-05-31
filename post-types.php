<?php
/*Plugin Name: Create Portfolio Post Type
Description: This plugin registers the 'portfolio' and 'blog' post types.
Version: 1.0
License: GPLv2
*/

function jb_create_post_types(){

  //set up labels
  $labels = array(
    'name' => 'Portfolio Pieces',
    'singular_name' => 'Portfolio Piece',
    'initial_description' => 'Initial Description',
    'carousel' => 'Carousel',
    'tech_lesson' => 'Tech Lesson',
    'github_link' => 'Github Link',
    'design_lesson' => 'Design Lesson',
  );

  //register post types
  register_post_type( 'jb_portfolio',
    array(
      'labels' => $labels,
      'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes'),
      'public' => true,
      'has_archive' => true,
      'taxonomies' => array( 'post_tag', 'category'),
      'exclude_from_search' => false,
      'capability_type' => 'post',
      // 'rewrite' => array( 'slug' => 'products' ),
    )
  );
  register_post_type( 'jb_blog',
    array(
      'labels' => array(
        'name' => __( 'Blog Posts'),
        'singular_name' => __( 'Blog Post')
      ),
      'supports' => array( 'title', 'editor', 'thumbnail'),
      'public' => true,
      'has_archive' => true,
    )
  );

  //Add meta box for Portfolio post carousel images:
  // function jb_add_meta_boxes('jb_portfolio'){
  //   add_meta_box(
  //     'jb_carousel_images',
  //     __( 'Portfolio Carousel Images'),
  //     'render_jb_carousel_images',
  //     'jb_portfolio',
  //     'normal',
  //     'default'
  //   );
  // }
  // add_action('add_meta_boxes_jb_portfolio', 'jb_add_meta_boxes');
  //
  // function render_jb_carousel_images( $post ){
  //   wp_nonce_field( basename(__FILE__), 'jb_meta_box_nonce');
  // }

}
add_action('init', 'jb_create_post_types');

 ?>
