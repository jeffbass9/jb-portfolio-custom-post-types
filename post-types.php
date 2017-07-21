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

}
    //End function jb_create_post_types

  add_action('init', 'jb_create_post_types');

  //Add meta box for Portfolio post carousel images:
  function jb_add_meta_boxes($post){
    add_meta_box(
      'jb_carousel_images',
      __( 'Portfolio Carousel Images'),
      'media_uploader_box',
      'jb_portfolio',
      'normal',
      'default'
    );
  }
  add_action( 'add_meta_boxes','jb_add_meta_boxes');

  function media_uploader_box() {
  	global $post;
  		$meta = get_post_meta( $post->ID, 'jb_carousel_images', true );
      ?>

  	<input type="hidden" name="jb_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

      <!-- All fields will go here -->
      <p>
      	<label for="jb_carousel_images">Image Upload</label><br>
      	<input type="text" name="jb_carousel_images" id="jb_carousel_images" class="meta-image regular-text" value="<?php echo $meta; ?>">
      	<input type="button" class="button image-upload" value="Browse">
      </p>

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

  <div class="image-preview"><img src="<?php echo $meta; ?>" style="max-width: 250px;"></div>

  	<?php }

  function save_your_fields_meta( $post_id ) {
  	// verify nonce
    //Line below fixed with solution at https://wordpress.stackexchange.com/questions/91402/undefined-index-at-nonce-in-custom-post-metabox
  	if ( isset($_POST['at_nonce']) && !wp_verify_nonce( $_POST['jb_meta_box_nonce'], basename(__FILE__) ) ) {
  		return $post_id;
  	}
  	// check autosave
  	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
  		return $post_id;
  	}
  	// check permissions
  	if ( 'page' === $_POST['post_type'] ) {
  		if ( !current_user_can( 'edit_page', $post_id ) ) {
  			return $post_id;
  		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
  			return $post_id;
  		}
  	}

    $old = get_post_meta( $post_id, 'jb_carousel_images', true );
    $new = $_POST['jb_carousel_images'];


    if ( $new && $new !== $old ) {
      add_post_meta( $post_id, 'jb_carousel_images', $new, false);
    }
  }
  add_action( 'save_post', 'save_your_fields_meta' );


 ?>
