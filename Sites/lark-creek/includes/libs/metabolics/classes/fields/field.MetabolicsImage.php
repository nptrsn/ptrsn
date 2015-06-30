<?php
/**
 * Image uploaders (simple custom uploader and built-in media uploader)
 * 
 * @author    Bryan Turley <bryan@lionassociates.com>
 * @copyright Copyright (c) 2013, Lion Associates
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL2
 * 
 * @package  metabolics
 * @since    2.0
 */


/**
 * Define and sanitize the field
 * @author bturley
 * @since  2.0
 */
class MetabolicsImage extends MetabolicsField {


  /**
   * Handles enqueuing and setup for the image uploaders
   * @author bturley
   * @since  2.0
   * @param  array    $args The array of field options
   */
  public function __construct( $core, $args ) {
    parent::__construct( $core, $args );

    // Enqueue JS & CSS
    add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue' ) );
  }


  /**
   * Enqueues required Javascript and CSS
   * @author bturley
   * @since  2.0
   */
  public function _enqueue() {
    // Enqueue JS & CSS for built-in uploader
    if ( ! $this->options['simple_upload'] ) {
      // WP 3.5+
      if ( function_exists( 'wp_enqueue_media' ) ) {
          wp_enqueue_media();
          wp_enqueue_script( 'metabolics-image-new', $this->core->js_path . 'script.MetabolicsImage-new.js', array( 'jquery' ), METABOLICS_VER, true );

      // Earlier versions
      } else {
          wp_enqueue_style( 'thickbox' );
          wp_enqueue_script( 'thickbox' );
          wp_enqueue_script( 'media-upload' );
          wp_enqueue_script( 'metabolics-image-old', $this->core->js_path . 'script.MetabolicsImage-old.js', array( 'jquery' ), METABOLICS_VER, true );
      }
    }
  }


  /**
   * Inserts a new attachment into the Wordpress media library
   * @author bturley
   * @param  array    $new_file The $_FILE data of the new attachment
   * @param  integer  $post_id  The current post ID
   * @return integer            The attachment ID
   */
  private function insert( $new_file, $post_id ) {
    $wp_upload_dir = wp_upload_dir();
    $attachment    = array(
        'guid'           => $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $new_file['file'] ), 
        'post_mime_type' => $new_file['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $new_file['file'] ) ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $new_file['file'] ), $post_id );
    
    // Generate image meta
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $new_file['file'] );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    return $attach_id;
  }


  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    if ( $this->options['simple_upload'] ) : // Custom Uploader ?>
      <div class='metabolics-uploader'>
        <?php if ( ! $saved ) : // If nothing saved, display upload button?>
          <label for='<?php echo "{$this->core->id}_{$this->id}"; ?>_upload'><?php _e( 'Upload:', '__metabolics' ); ?>
            <input type='file'
                   id='<?php echo "{$this->core->id}_{$this->id}"; ?>_upload'
                   name='<?php echo "{$this->core->id}_{$this->id}_upload"; ?>'
                   size='40' />
          </label>

        <?php else : // Otherwise, display image and delete controls?>
          <?php echo wp_get_attachment_image( $saved, 'medium', false, array( 'class' => 'metabolics-uploader-image' ) ); ?>
          <input type='hidden'
                 id='<?php echo "{$this->core->id}_{$this->id}"; ?>_id'
                 name='<?php echo "{$this->core->id}_{$this->id}_id"; ?>'
                 value='<?php echo $saved; ?>' />
          <label for='<?php echo "{$this->core->id}_{$this->id}"; ?>_delete' class='metabolics-uploader-delete'>
            <input type='checkbox'
                   id='<?php echo "{$this->core->id}_{$this->id}"; ?>_delete'
                   name='<?php echo "{$this->core->id}_{$this->id}_delete"; ?>'
                   value='true' />
              <?php _e( 'Delete Image?', '__metabolics' ); ?>
            </label>
        <?php endif; ?>
      </div>

    <?php else : // Built-in WP Uploader ?>
      <div class='metabolics-uploader'>
        <?php $url = isset( $saved ) && $saved ? wp_get_attachment_image_src( $saved, 'full' ) : ''; ?>
        <input type='hidden'
               class='metabolics-uploader-image-url'
               id='<?php echo "{$this->core->id}_{$this->id}"; ?>'
               name='<?php echo "{$this->core->id}[{$this->id}]"; ?>'
               value='<?php echo $url[0]; ?>' />

        <?php if ( isset( $saved ) && $saved ) : ?>
          <?php echo wp_get_attachment_image( $saved, 'medium', false, array( 'class' => 'metabolics-uploader-image' ) ); ?>
        <?php endif; ?>
      
        <div class='metabolics-uploader-buttons'>
          <?php if ( function_exists( 'wp_enqueue_media' ) ) : // If WP 3.5+ ?>
            <a href='#' class='button button-primary metabolics-add-media'><?php _e( 'Select Image', '__metabolics' ); ?></a>
          
          <?php else : // If old uploader?>
            <input type='hidden' class='metabolics-uploader-post-id' value='<?php global $post_id; echo $post_id; ?>' />
            <a href='#' class='button button-primary metabolics-upload-media'><?php _e( 'Select Image', '__metabolics' ); ?></a>
          <?php endif; ?>
          
          <?php if ( isset( $saved ) && $saved ) : // Only display delete button if there's something to delete?>
            <a href='#' class='button button-secondary metabolics-uploader-delete'><?php _e( 'Delete Image', '__metabolics' ); ?></a>
          <?php endif; ?>
        </div>
      </div>
    <?php endif;

    // Output description
    echo $this->options['desc'] != '' ? '<p class="description">' . $this->options['desc'] . '</p>' : ''; 
  }


  /**
   * Sanitizes the submitted input
   * @author bturley
   * @since  2.0
   * @param  integer  $post_id    The current post ID
   * @param  string   $post_data  The submitted $_POST data
   * @return string               The cleansed value
   */
  public function sanitize( $post_id, $post_data = NULL ) {
    // If using custom uploader
    if ( $this->options['simple_upload'] ) {
        // Get current image ID
        $attach_id = isset( $_POST["{$this->core->id}_{$this->id}_id"] ) ? intval( $_POST["{$this->core->id}_{$this->id}_id"] ) : NULL;

        // Upload new image (if needed)
        if ( isset( $_FILES["{$this->core->id}_{$this->id}_upload"] ) && ! empty( $_FILES["{$this->core->id}_{$this->id}_upload"]['name'] ) ) {
            if ( ! current_user_can( 'upload_files' ) )
                wp_die( __( 'You do not have permission to do upload files.', '__metabolics' ) );
            $new_file = wp_handle_upload( $_FILES["{$this->core->id}_{$this->id}_upload"], array( 'test_form' => false ) );
            $attach_id = $this->insert( $new_file, $post_id );
        }

        // Delete existing image (if needed)
        if ( isset( $_POST["{$this->core->id}_{$this->id}_delete"] ) ) {
            if ( ! current_user_can( 'upload_files' ) )
                wp_die( __( 'You do not have permission to do delete files.', '__metabolics' ) );
            wp_delete_attachment( $attach_id, true );
            $attach_id = NULL;
        }
        $value = $attach_id;

    // If using the Wordpress uploader
    } else {
      global $wpdb; // Get the attachment ID from the URL
      $url   = isset( $post_data ) ? esc_url( $post_data ) : NULL;
      $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";
      $value = $wpdb->get_var( $query );
    }
    return $value;
  }
}

?>