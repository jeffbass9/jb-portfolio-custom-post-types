<!-- From http://seorave.com/upload-media-in-meta-boxes/ -->
<?php function media_uploader_box(): global $post; ?>

<style> .media-upload h2{ font-weight: bold; } </style>

<script>
  (function( $ ){
    $(document).ready(
      function(){
        $('#upload_image_button').click(
          function(){
            tb_show('', 'media-upload.php?post_id=<?php echo $post->ID; ?>&type=image&amp;TB_iframe=true');
            return false;
          }
        );
      }
    );
  })(jQuery);
</script>

<div class="media-upload">
  <h2>Upload Media</h2>
  <table>
    <tr valign="top">
      <td><input id="upload_image_button" type="button" value="Upload Media"></td>
    </tr>
  </table>
</div>

  <?php endif;
// End media_uploader_box function
  ?>
  <?php

    function admin_scripts(){
      wp_enqueue_script('media-upload');
      wp_enqueue_script('thickbox');
    }

    function admin_styles(){
      wp_enqueue_style('thickbox');
    }

    add_action('admin_print_scripts', 'admin_scripts');
    add_action('admin_print_styles', 'admin_styles');


?>

<!-- Another attempt 061417 -->

?>
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

function media_uploader_box(){
  global $post;
    $meta = get_post_meta( $post->ID, 'jb_carousel_images', true); ?>

  <input type="hidden" name="jb_carousel_image_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

  <p>
  	<label for="jb_carousel_images[image]">Image Upload</label><br>
  	<input type="text" name="jb_carousel_images[image]" id="jb_carousel_images[image]" class="meta-image regular-text" value="<?php echo $meta['image']; ?>">
  	<input type="button" class="button image-upload" value="Browse">
  </p>
<div class="image-preview"><img src="<?php echo $meta['image']; ?>" style="max-width: 250px;"></div>

<?php }

function save_jb_carousel_images( $post_id ) {
	// verify nonce
	if ( !wp_verify_nonce( $_POST['jb_carousel_image_nonce'], basename(__FILE__) ) ) {
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
		update_post_meta( $post_id, 'jb_carousel_images', $new );
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'jb_carousel_images', $old );
	}
}
add_action( 'save_post', 'save_jb_carousel_images' );

?>
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


<!-- Formatted solution, upload box won't appear but plugin doesn't break site:  -->

function media_uploader_box(){
  global $post;
    $meta = get_post_meta( $post->ID, 'jb_carousel_images', true); ?>

  <input type="hidden" name="jb_carousel_image_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

  <p>
    <label for="jb_carousel_images[image]">Image Upload</label><br>
    <input type="text" name="jb_carousel_images[image]" id="jb_carousel_images[image]" class="meta-image regular-text" value="<?php echo $meta['image']; ?>">
    <input type="button" class="button image-upload" value="Browse">
  </p>
<div class="image-preview"><img src="<?php echo $meta['image']; ?>" style="max-width: 250px;"></div>

<?php }
//End callback function

//Begin image upload save function
function save_jb_carousel_images( $post_id ) {
  // verify nonce
  if ( !wp_verify_nonce( $_POST['jb_carousel_image_nonce'], basename(__FILE__) ) ) {
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
    update_post_meta( $post_id, 'jb_carousel_images', $new );
  } elseif ( '' === $new && $old ) {
    delete_post_meta( $post_id, 'jb_carousel_images', $old );
  }
}
//End image upload save function
add_action( 'save_post', 'save_jb_carousel_images' );

?>
<script>
(function($){

  $(document).ready(function ($) {

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
})(jQuery);
</script>
<?php


    function admin_scripts(){
      wp_enqueue_script('media-upload');
      wp_enqueue_script('thickbox');
    }

    function admin_styles(){
      wp_enqueue_style('thickbox');
    }

    add_action('admin_print_scripts', 'admin_scripts');
    add_action('admin_print_styles', 'admin_styles');


// Solution from https://hugh.blog/2015/12/18/create-a-custom-featured-image-box/
// Notice that he hooks into the add_meta_boxes action before defining the function
add_action( 'add_meta_boxes', 'jb_add_meta_boxes');

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

function listing_image_metabox ( $post ) {
	global $content_width, $_wp_additional_image_sizes;
	$image_id = get_post_meta( $post->ID, '_listing_image_id', true );
	$old_content_width = $content_width;
	$content_width = 254;
	if ( $image_id && get_post( $image_id ) ) {
		if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
			$thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
		} else {
			$thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
		}
		if ( ! empty( $thumbnail_html ) ) {
			$content = $thumbnail_html;
			$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_listing_image_button" >' . esc_html__( 'Remove listing image', 'text-domain' ) . '</a></p>';
			$content .= '<input type="hidden" id="upload_listing_image" name="_listing_cover_image" value="' . esc_attr( $image_id ) . '" />';
		}
		$content_width = $old_content_width;
	} else {
		$content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
		$content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set listing image', 'text-domain' ) . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'text-domain' ) . '" data-uploader_button_text="' . esc_attr__( 'Set listing image', 'text-domain' ) . '">' . esc_html__( 'Set listing image', 'text-domain' ) . '</a></p>';
		$content .= '<input type="hidden" id="upload_listing_image" name="_listing_cover_image" value="" />';
	}
	echo $content;
}
add_action( 'save_post', 'listing_image_save', 10, 1 );
function listing_image_save ( $post_id ) {
	if( isset( $_POST['_listing_cover_image'] ) ) {
		$image_id = (int) $_POST['_listing_cover_image'];
		update_post_meta( $post_id, '_listing_image_id', $image_id );
	}
}

//Another possible solution, without a meta box, found here: https://codex.wordpress.org/Using_Custom_Fields_to_attach_images,_links_or_files_to_a_post_easily
function bd_parse_post_variables(){
  // bd_parse_post_variables function for WordPress themes by Nick Van der Vreken.
  // please refer to bydust.com/using-custom-fields-in-wordpress-to-attach-images-or-files-to-your-posts/
  // for further information or questions.
  global $post, $post_var;

  // fill in all types you'd like to list in an array, and
  // the label they should get if no label is defined.
  // example: each file should get label "Download" if no
  // label is set for that particular file.
  $types = array('image' => 'no info available',
  'file' => 'Download',
  'link' => 'Read more...');

  // this variable will contain all custom fields
  $post_var = array();
  foreach(get_post_custom($post->ID) as $k => $v) $post_var[$k] = array_shift($v);

  // creating the arrays
  foreach($types as $type => $message){
    global ${'post_'.$type.'s'},
            ${'post_'.$type.'s_label'};
    $i = 1;
    ${'post_'.$type.'s'} = array();
    ${'post_'.$type.'s_label'} = array();
    while($post_var[$type.$i]){
      echo $type.$i.' - '.${$type.$i.'_label'};
      array_push(${'post_'.$type.'s'}, $post_var[$type.$i]);
      array_push(${'post_'.$type.'s_label'},  $post_var[$type.$i.'_label']?htmlspecialchars($post_var[$type.$i.'_label']):$message);
      $i++;
    }
  }
}
//End function bd_parse_post_variables

// <!-- Start the Loop. -->
 <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
  // call the function in our functions.php file
  bd_parse_post_variables();
  // these variables are now available:
  // $post_var
  // $post_images and $post_images_label
  // $post_links and $post_links_label
  // $post_files and $post_files_label
  ?>

   <!-- Display the Post's Content in a div box. -->
   <div class="entry">
     <?php the_content(); ?>
   </div>


  <!-- Display our Custom Field -->
  <div class="link">
     link1: "<?php echo $post_var['myField']; ?>"
   </div>

  <!-- Display the list of links -->
  <ul>
    <?php while(count($post_images) > 0): ?>
     <li class="file">
      <a href="<?php echo array_shift($post_images); ?>" title="Click to download this file">
      <?php echo array_shift($post_images_label); ?>
      </a>
    </li>
    <?php endwhile; ?>
  </ul>

   <!-- Stop The Loop (but note the "else:" - see next line). -->
   <?php endwhile; else: ?>

   <!-- The very first "if" tested to see if there were any Posts to -->
   <!-- display.  This "else" part tells what do if there weren't any. -->
   <p>Sorry, no posts matched your criteria.</p>

   <!-- REALLY stop The Loop. -->
   <?php endif; ?>



<!--
//My potential solution for the multiple image upload problem: -->
<?php
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
  if ( 'page' === $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) ) {
      return $post_id;
    } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
      return $post_id;
    }
  }
  //This is what I'm going to have to change in order to be able to upload multiple images:
  $old = get_post_meta( $post_id, 'jb_carousel_images', true );
  $new = $_POST['jb_carousel_images'];


  if ( $new && $new !== $old ) {
    add_post_meta( $post_id, 'jb_carousel_images', $new, false);
  } 
}
add_action( 'save_post', 'save_your_fields_meta' );
?>



?>
