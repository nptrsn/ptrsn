<?php
/**
 * Attachment uploader for PDFs and other documents
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
class MetabolicsAttachment extends MetabolicsField {


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
    if ( function_exists( 'wp_enqueue_media' ) ) {
      wp_enqueue_media();
      wp_enqueue_script( 'metabolics-attachment-new', $this->core->js_path . 'script.MetabolicsAttachment.js', array( 'jquery' ), METABOLICS_VER, true );
    }
  }

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) { 
    // Check for WP 3.5+ media uploader
    if ( function_exists( 'wp_enqueue_media' ) ) : ?>
      <div class='metabolics-uploader'>
        
        <?php $url = isset( $saved ) && $saved ? wp_get_attachment_url( $saved ) : ''; ?>
        <input type='hidden'
               class='metabolics-attachment-url'
               id='<?php echo "{$this->core->id}_{$this->id}"; ?>'
               name='<?php echo "{$this->core->id}[{$this->id}]"; ?>'
               value='<?php echo $url; ?>' />

        <?php if ( isset( $saved ) && $saved ) : ?>
          <a class="metabolics-attachment-link" href="<?php echo wp_get_attachment_url( $saved ); ?>">
            <?php echo get_the_title( $saved ); ?>
          </a>
        <?php endif; ?>
      
        <div class='metabolics-uploader-buttons'>
          <a href='#' class='button button-primary metabolics-attachment'><?php _e( 'Add Attachment', '__metabolics' ); ?></a>

          <?php if ( isset( $saved ) && $saved ) : // Only display delete button if there's something to delete?>
            <a href='#' class='button button-secondary metabolics-uploader-delete'><?php _e( 'Delete Attachment', '__metabolics' ); ?></a>
          <?php endif; ?>
        </div>
      </div>

      <?php // Output description
      echo $this->options['desc'] != '' ? '<p class="description">' . $this->options['desc'] . '</p>' : ''; 
    
    // No uploader, display warning message
    else : ?>
      <p class='metabolics-message'>
        <?php _e( 'Sorry, your version of Wordpress does meet the minimum requirements of the Attachment uploader', '__metabolics' ); ?>
      </p>
    <?php endif;
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
    global $wpdb; // Get the attachment ID from the URL
    $url       = isset( $post_data ) ? esc_url( $post_data ) : NULL;
    $query     = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";
    $attach_id = $wpdb->get_var( $query );
    return intval( $attach_id );
  }
}

?>