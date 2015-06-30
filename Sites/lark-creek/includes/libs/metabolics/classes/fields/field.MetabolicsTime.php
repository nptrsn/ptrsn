<?php
/**
 * A time selector using jQueryUI Timepicker (Addon)
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
class MetabolicsTime extends MetabolicsField {


  /**
   * Creates a new time field
   * @author bturley
   * @since  2.0
   * @param  Metabolics $core The Metabolics core object to which this field belongs
   * @param  array      $args An array of field options
   */
  public function __construct( $core, $args ) {
    parent::__construct( $core, $args );
    add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue' ) );
  }


  /**
   * Enqueues the CSS & JS
   * @author bturley
   * @since  2.0
   */
  public function _enqueue() {
    wp_enqueue_script( 'jquery-ui-time', $this->core->js_path . 'jquery-ui-timepicker-addon.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-slider' ), METABOLICS_VER, true );
  }


  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    echo "<input type='text'
                  class='metabolics-time'
                  id='{$this->core->id}_{$this->id}'
                  name='{$this->core->id}[{$this->id}]'
                  value='" . ( ( isset( $saved ) && $saved != '' ) ? $saved :  $this->options['default'] ) . "' " .
                  ( $this->options['required'] ? 'required' : '' ) . " />";
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
    return isset( $post_data ) ? esc_html( $post_data ) : NULL;
  }
}

?>