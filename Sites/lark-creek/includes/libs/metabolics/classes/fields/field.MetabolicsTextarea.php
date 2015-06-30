<?php
/**
 * A textarea input field
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
class MetabolicsTextarea extends MetabolicsField {

  /**
   * Ouputs the HTML markup for the field
   * @author bturley
   * @since  2.0
   * @param  string   $saved  The saved value (if any)
   */
  public function output( $saved = NULL ) {
    echo "<textarea class='widefat'
                    id='{$this->core->id}_{$this->id}'
                    name='{$this->core->id}[{$this->id}]'
                    rows='{$this->options['rows']}'
                    cols='40'
                    placeholder='{$this->options['placeholder']}'" .
                    ( $this->options['required'] ? 'required' : '' ) . ">" .
                    ( ( isset( $saved ) && $saved != '' ) ? $saved : $this->options['default'] ) .
         "</textarea>";

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
    if ( $this->options['allow_html'] )
      $value = isset( $post_data ) ? wp_kses_post( $post_data ) : NULL;
    else
      $value = isset( $post_data ) ? esc_textarea( $post_data ) : NULL;
    return $value;
  }
}

?>