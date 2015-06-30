<?php
/**
 * Defines a prototype class for all Metabolics fields
 * 
 * @author    Bryan Turley <bryan@lionassociates.com>
 * @copyright Copyright (c) 2013, Lion Associates
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL2
 * 
 * @package  metabolics
 * @since    2.0
 * @abstract
 */

// Disallow direct access to this file
if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
  exit( "Do not access this file directly." );

// Only run if Metabolics is undefined and on admin pages
if ( class_exists( 'MetabolicsSection' ) || ! is_admin() ) return;


/**
 * Defines a prototype Metabolics field object
 * @author bturley
 * @since  2.0
 */
abstract class MetabolicsField {

  /**
   * The field type
   * @var   string
   * @since 2.0
   */
  public $type;

  /**
   * The unique field identifier
   * @var   string
   * @since 2.0
   */
  public $id;

  /**
   * The title of the field
   * @var   string
   * @since 2.0
   */
  public $title;

  /**
   * The Metabolics core object to which this field belongs
   * @var   Metabolics
   * @since 2.0
   */
  protected $core;

  /**
   * The array of field options
   * @var   array
   * @since 2.0
   */
  protected $options;


  /**
   * Creates a new field object and defines the options
   * @author bturley
   * @since  2.0
   * @param  array    $args An array of field options
   */
  public function __construct( $core, $args ) {
    $this->core  = $core;
    $this->type  = $args['type'];
    $this->id    = $args['id'];
    $this->title = isset( $args['title'] ) ? $args['title'] : '';

    // Parse arguments against default values
    $defaults = array(
      // Generic
      'title'    => '',
      'required' => false,
      'default'  => '',
      'desc'     => '',

      // Text-based inputs
      'placeholder'   => '',
      'size'          => 'regular',
      'rows'          => 5,
      'allow_html'    => false,

      // Content editor
      'media_buttons' => true, 

      // Numerical inputs
      'min'       => NULL,
      'max'       => NULL,
      'precision' => 1,

      // Image uploader
      'simple_upload' => false,

      // Checkbox
      'label' => '',

      // Option elements (select, checkgroup, radio, etc.)
      'options'  => array(),
    );
    $this->options = wp_parse_args( $args, $defaults );

    // Manually parse old params for backwards compatibility
    if ( isset( $args['description'] ) )
      $this->options['desc'] = $args['description'];

    if ( isset( $args['media_library'] ) )
      $this->options['simple_upload'] = ! $args['media_library'];
  }

  /**
   * Handles the HTML output of the field
   * @author bturley
   * @since  2.0
   * @param  mixed  $saved  The saved post meta for this field
   */
  abstract public function output( $saved = NULL );


  /**
   * Handles the data sanitization for the field
   * @author bturley
   * @since  2.0
   * @param  integer  $post_id    The current post ID
   * @param  string   $post_data  The submitted $_POST data
   * @return mixed                The sanitized field value
   */
  abstract public function sanitize( $post_id, $post_data = NULL );
}

?>