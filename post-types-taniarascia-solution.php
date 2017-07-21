<?php

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

function show_your_fields_meta_box() {
	global $post;
		$meta = get_post_meta( $post->ID, 'portfolio_carousel_images', true ); ?>

	<input type="hidden" name="your_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <!-- All fields will go here -->
    <p>
    	<label for="portfolio_carousel_images[image]">Image Upload</label><br>
    	<input type="text" name="portfolio_carousel_images[image]" id="portfolio_carousel_images[image]" class="meta-image regular-text" value="<?php echo $meta['image']; ?>">
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

<div class="image-preview"><img src="<?php echo $meta['image']; ?>" style="max-width: 250px;"></div>

	<?php }

function save_your_fields_meta( $post_id ) {
	// verify nonce
	if ( !wp_verify_nonce( $_POST['your_meta_box_nonce'], basename(__FILE__) ) ) {
		return $post_id;
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	if ( 'page' === $_POST['jb_portfolio'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	$old = get_post_meta( $post_id, 'portfolio_carousel_images', true );
	$new = $_POST['portfolio_carousel_images'];

	if ( $new && $new !== $old ) {
		update_post_meta( $post_id, 'portfolio_carousel_images', $new );
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'portfolio_carousel_images', $old );
	}
}
add_action( 'save_post', 'save_your_fields_meta' );

?>
