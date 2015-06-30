<?php
/**
 * A single checkbox that stores a true/false value
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
class MetabolicsCheckbox extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    $checked = false;
  
    // Ignore default value if the value has been saved
    if ( isset( $saved ) ) $checked = $saved;
    else if ( $this->options['default'] ) $checked = true;

    echo "<label for='{$this->core->id}_{$this->id}'>";
    echo "<input type='checkbox'
                 id='{$this->core->id}_{$this->id}'
                 name='{$this->core->id}[$this->id]'
                 value='true'" .
                 ( $checked ? "checked='checked'" : '' ) . " />";
    echo " {$this->options['label']}</label>";

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
    return isset( $post_data ) ? true : false;
  }
}

?>