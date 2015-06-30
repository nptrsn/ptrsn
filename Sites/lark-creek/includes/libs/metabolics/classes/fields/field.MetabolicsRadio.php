<?php
/**
 * A group of radio options
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
class MetabolicsRadio extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    $saved = empty( $saved ) ? array() : $saved;
    echo "<div class='metabolics-group'>";  

    // Iterate over the different options
    foreach( $this->options['options'] as $key => $option ) {

      // Check for saved values vs. defaults
      $checked = false;
      if ( $saved ) {
          if ( $option['value'] == $saved )
              $checked = true;
      } else {
          if ( isset( $option['selected'] ) && $option['selected'] )
              $checked = true;
      }

      $disabled = isset( $option['disabled'] ) && $option['disabled'];

      // Output the radio
      echo "<label for='{$this->core->id}_{$this->id}_{$key}'>";
      echo "<input type='radio'
                   id='{$this->core->id}_{$this->id}_{$key}'
                   name='{$this->core->id}[{$this->id}]'
                   value='{$option['value']}'" .
                   ( $this->options['required'] ? 'required' : '' ) .
                   ( $checked  ? "checked='checked'"   : "" ) . 
                   ( $disabled ? "disabled='disabled'" : "" ) . " />";
      echo " {$option['label']}</label>";
    }
    echo "</div>";
    
    // Description
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