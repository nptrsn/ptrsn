<?php
/**
 * A dropdown select field
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
class MetabolicsSelect extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    echo "<select id='{$this->core->id}_{$this->id}' name='{$this->core->id}[{$this->id}]'>";
    foreach( $this->options['options'] as $option ) {
        $selected = '';
        if ( $saved ) {
            if ( $option['value'] == $saved )
                $selected = "selected='selected'";
        } else {
            if ( isset( $option['selected'] ) && $option['selected'] )
                $selected = "selected='selected'";
        }
        $disabled = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled="disabled"' : '';
        echo "<option value='{$option['value']}' $selected $disabled>{$option['label']}</option>";
    }
    echo "</select>";
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