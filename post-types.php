<?php
/*Plugin Name: Create Custom Post Types
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
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'custom-fields',
        'thumbnail',
        'page-attributes'),
      'public' => true,
      'has_archive' => true,
      'taxonomies' => array(
        'post_tag',
        'category'),
      'exclude_from_search' => false,
      'capability_type' => 'post',
      // 'rewrite' => array( 'slug' => 'products' ),
    )
  );
  register_taxonomy_for_object_type( 'category', 'jb_portfolio');
  register_taxonomy_for_object_type( 'post_tag', 'jb_portfolio');

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
  function jb_add_meta_boxes($post){
    add_meta_box(
      'jb_carousel_images',
      __( 'Portfolio Carousel Images'),
      'render_jb_carousel_images',
      'jb_portfolio',
      'normal',
      'default'
    );
  }
  //End add_meta_boxes_jb_portfolio function

  
  function render_jb_carousel_images( $post ){ ?>
    <?php wp_nonce_field( basename( __FILE__ ), 'jb_portfolio_post_class_nonce'); ?>

    <p>
      <label for="portfolio-carousel-image"><?php _e("Add an image to this post's image carousel.", 'example'); ?></label>
      <br>
      <input type="text" name="portfolio-carousel-image" id="portfolio-carousel-image" class="meta-image regular-text" value="<?php echo $meta['image']; ?>">
      <input type="button" class="button image-upload" value="Browse">
    </p>
    <div class="image-preview"><img src="<?php echo $meta['image']; ?>" style="max-width: 250px;"></div>

    <script>
      jQuery(document).ready(function ($) {

      	// Instantiates the variable that holds the media library frame.
      	var meta_image_frame;
      	// Runs when the image button is clicked.
      	$('.image-upload').click(function (e) {
      		e.preventDefault();
      		var meta_image = $(this).parent().children('.meta-image');

      		// If the frame already exists, re-open it.
      		if (meta_image_frame) {
      			meta_image_frame.open();
      			return;
      		}
      		// Sets up the media library frame
      		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
      			title: meta_image.title,
      			button: {
      				text: meta_image.button
      			}
      		});
      		// Runs when an image is selected.
      		meta_image_frame.on('select', function () {
      			// Grabs the attachment selection and creates a JSON representation of the model.
      			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
      			// Sends the attachment URL to our custom image input field.
      			meta_image.val(media_attachment.url);
      		});
      		// Opens the media library frame.
      		meta_image_frame.open();
      	});
      });
      </script>
  <?php
  }
  //End function render_jb_carousel_images



  function jb_portfolio_meta_boxes_setup(){

    //Add meta boxes on the 'add_meta_boxes' hook
    add_action('add_meta_boxes_jb_portfolio', 'jb_add_meta_boxes');

    //Save post meta on the 'save_post' hook
    add_action( 'save_post', 'jb_portfolio_post_class_meta', 10, 2);
  }

  add_action( 'load-post.php', 'jb_portfolio_meta_boxes_setup');
  add_action( 'load-post-new.php', 'jb_portfolio_meta_boxes_setup');

  //Save the meta box's post metadata:
  //From https://www.smashingmagazine.com/2011/10/create-custom-post-meta-boxes-wordpress/
  function jb_portfolio_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['jb_portfolio_post_class_nonce'] ) || !wp_verify_nonce( $_POST['jb_portfolio_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['portfolio-carousel-image'] ) ? sanitize_html_class( $_POST['portfolio-carousel-image'] ) : '' );

  /* Get the meta key. */
  $meta_key = 'portfolio-carousel-image';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}
//end function jb_portfolio_post_class_meta


}
//End jb_create_post_types function

add_action('init', 'jb_create_post_types');

?>
