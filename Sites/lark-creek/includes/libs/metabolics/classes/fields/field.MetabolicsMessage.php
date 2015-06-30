<?php
/**
 * A simple message field for communicating information to the user
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
class MetabolicsMessage extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    $saved = $saved ? $saved : $this->options['default'];
    echo "<p id='{$this->core->id}_{$this->id}' class='metabolics-message'>{$this->options['desc']}</p>";
    echo "<input type='hidden' 
                 name='{$this->core->id}[{$this->id}]' 
                 value='" . ( is_array( $saved ) ? implode( ',', $saved ) : $saved ) . "' />";
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
    return $post_data;
  }
}

?>