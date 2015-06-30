<?php
/**
 * Field that provides a list of sortable items
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
class MetabolicsSortable extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    $options = $this->options['options'];
    $order   = isset( $saved ) && $saved ? implode( ',', $saved ) : $this->options['default'];
    $meta    = $order != '' ? explode( ',', $order ) : array();

    echo "<ul id='{$this->core->id}_{$this->id}_list' class='metabolics-sortable'>";
    foreach ( $meta as $value ) {
      if ( ! preg_match( '/(.*)_(\d+)$/', $value, $matches ) ) continue;
      $ordinal = intval( $matches[2] ) + 1;

      // Find the current option
      $current = '';
      foreach( $options as $key => $option ) {
        if ( $option['value'] == $matches[1] ) {
          $current = $key;
          break;
        }
      }
      
      if ( $current === '' ) continue;

      // Output element
      echo "<li class='metabolics-sortable-default' 
                data-metabolics-value='{$options[$current]['value']}_{$matches[2]}'>
              {$options[$current]['label']} ($ordinal) <a class='metabolics-sortable-remove' href='#{$options[$current]['value']}'>"
              . __( 'Remove', '__metabolics' ) . "</a>
            </li>";
    }
    echo "</ul>";
    
    // Controls
    echo "<fieldset class='metabolics-fieldset'>";
      echo "<legend>" . __( 'Add New', '__metabolics' ) . '</legend>';
      echo "<select id='{$this->core->id}_{$this->id}_options' class='metabolics-sortable-options'>";
        foreach( $options as $option )
          echo "<option value='{$option['value']}'>{$option['label']}</option>";
      echo "</select>";
      echo "<a href='#{$this->core->id}_{$this->id}_list' class='button button-secondary metabolics-sortable-add'>" . __( 'Add', '__metabolics' ) . "</a>";
      echo "<input type='hidden'
                   id='{$this->core->id}_{$this->id}'
                   name='{$this->core->id}[{$this->id}]'
                   value='$order' />";
    echo "</fieldset>";

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
    if ( isset( $post_data ) )
      $value = explode( ',', esc_html( $post_data ) );
    else
      $value = NULL;
    return $value;
  }
}

?>