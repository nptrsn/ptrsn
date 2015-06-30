<?php
/**
 * A group of checkboxes
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
class MetabolicsCheckgroup extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    $saved = empty( $saved ) ? array() : $saved;
    echo "<div class='metabolics-group'>";

    // Iterate over each checkbox
    foreach( $this->options['options'] as $key => $option ) {

      // Check for saved value vs. defaults
      $checked = false;
      if ( isset( $saved[$key] ) ) {
        if ( $option['value'] == $saved[$key] )
          $checked = true;
      } else {
        if ( isset( $option['selected'] ) && $option['selected'] )
          $checked = true;
      }

      $disabled = isset( $option['disabled'] ) && $option['disabled'];

      // Output element
      echo "<label for='{$this->core->id}_{$this->id}_{$key}'>";
      echo "<input type='checkbox'
                   id='{$this->core->id}_{$this->id}_{$key}'
                   name='{$this->core->id}[{$this->id}][]'
                   value='{$option['value']}'" .
                   ( $checked  ? "checked='checked'"   : "" ) .
                   ( $disabled ? "disabled='disabled'" : "" ) . " />";
      echo " {$option['label']}</label>";
    }
    echo "</div>";

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
    // Prevent resetting to default, if no options are selected
    $options = array_fill( 0, sizeof( $this->options['options'] ), '' );

    if ( isset( $post_data ) && is_array( $post_data ) ) {
      // Iterate over each option, and grab the value
      foreach( $this->options['options'] as $key => $option )
        $options[$key] = in_array( $option['value'], $post_data ) ? $option['value'] : '';
    }
    return $options;
  }
}

?>