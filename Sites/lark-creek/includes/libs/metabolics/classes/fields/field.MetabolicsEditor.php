<?php
/**
 * Adds another instance of the Wordpress content editor
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
class MetabolicsEditor extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    echo '<div class="metabolics-editor">';
      $content = isset( $saved ) ? $saved : '';
      wp_editor( $content, "{$this->core->id}_{$this->id}", array(
        'textarea_rows' => 15,
        'textarea_name' => "{$this->core->id}[{$this->id}]",
        'media_buttons' => $this->options['media_buttons'],
      ) );
    echo '</div>';
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
    return isset( $post_data ) ? wp_kses_post( $post_data ) : NULL;
  }
}

?>