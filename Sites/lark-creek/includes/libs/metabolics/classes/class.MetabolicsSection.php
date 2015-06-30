<?php
/**
 * Defines the Metabolics section class
 *
 * Creates a new setting section, handles field adding and validation
 *
 * @author    Bryan Turley <bryan@lionassociates.com>
 * @copyright Copyright (c) 2013, Lion Associates
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL2
 *
 * @package metabolics
 * @since   2.0
 */

// Disallow direct access to this file
if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
  exit( "Do not access this file directly." );

// Only run if Metabolics is undefined and on admin pages
if ( class_exists( 'MetabolicsSection' ) || ! is_admin() ) return;


/**
 * Define the Metabolics section object
 * @author bturley
 * @since  2.0
 */
class MetabolicsSection {

  /**
   * The unique identifier of the section
   * @var   string
   * @since 2.0
   */
  public $id;

  /**
   * The title of the section
   * @var   string
   * @since 2.0
   */
  public $title;

  /**
   * The Metabolics core object to which this section belongs
   * @var   Metabolics
   * @since 2.0
   */
  private $core;

  /**
   * The array of fields belonging to this section
   * @var   array
   * @since 2.0
   */
  private $fields = array();


  /**
   * Creates a new section object
   * @author bturley
   * @since  2.0
   * @param  Metabolics $core The Metabolics core to which this section belongs
   * @param  string     $id         The unique identifier for this section
   * @param  string     $title      The title of this section
   */
  public function __construct( $core, $id, $title = '' ) {
    $this->core  = $core;
    $this->id    = $id;
    $this->title = $title;
  }


  /**
   * Adds a new field to the section
   * @author bturley
   * @since  2.0
   * @param  array            $args An array of field data
   * @return MetabolicsField        A MetabolicsField object
   */
  public function add_field( $args ) {
    // Maintain compatibility with versions < v1.2
    if ( 'checkbox-group' == $args['type'] )
      $args['type'] = 'checkgroup';

    $field_type = 'Metabolics' . ucfirst( $args['type'] );
    $field = new $field_type( $this->core, $args );
    $this->fields[$args['id']] = $field;
    return $field;
  }


  /**
   * Outputs a section and its fields
   * @author bturley
   * @since  2.0
   * @param  array  $saved_meta An array of all relevant saved post meta
   */
  public function output( $saved_meta ) {
    // Output the section title, if there is one
    if ( $this->title )
      echo "<h3 id='{$this->id}' class='metabolics-header'>{$this->title}</h3>";

    // Output the section table
    echo "<table id='{$this->id}-table' class='metabolics-table form-table'>";

    foreach( $this->fields as $field_id => $field ) {
      // Output title area
      if ( $field->type !== 'hidden' ) {
        if ( $field->title )
          echo "<tr><th scope='row'>{$field->title}</th><td>";
        else
          echo "<tr><td colspan='2'>";
      }

      // Output field
      $saved = isset( $saved_meta[$field_id] ) ? $saved_meta[$field_id] : NULL;
      $field->output( $saved );

      // Close
      if ( $field->type !== 'hidden' )
        echo "</td></tr>";
    }

    // Close the table element
    echo "</table>";
  }


  /**
   * Validates the $_POST data of each field and returns an array
   * of validated data
   * @author bturley
   * @param  integer  $post The current post ID
   * @return array          An array of validated post data
   */
  public function save( $post_id ) {
    $post_meta = array();
    foreach( $this->fields as $field_id => $field ) {
      $post_data = isset( $_POST[$this->core->id][$field_id] ) ? $_POST[$this->core->id][$field_id] : NULL;
      $post_meta[$field_id] = $field->sanitize( $post_id, $post_data );
    }
    return $post_meta;
  }
}

?>