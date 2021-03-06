<?php
/**
 * A field that accepts and validates URLs
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
class MetabolicsUrl extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    echo "<input type='url'
                 class='{$this->options['size']}-text'
                 id='{$this->core->id}_{$this->id}'
                 name='{$this->core->id}[{$this->id}]'
                 placeholder='{$this->options['placeholder']}'
                 value='" . ( ( isset( $saved ) && $saved != '' ) ? $saved : $this->options['default'] ) . "' " .
                 ( $this->options['required'] ? 'required' : '' ) . " />";

    // Output the description
    if ( $this->options['size'] == 'small' )
        echo $this->options['desc'] != '' ? '<span class="description">' . $this->options['desc'] . '</span>' : ''; 
    else
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
    return isset( $post_data ) ? esc_url( $post_data ) : NULL;
  }
}

?>