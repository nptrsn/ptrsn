<?php
/**
 * Defines the core Metabolics class.
 *
 * Registers the new metabox, adds the appropriate action hooks,
 * and provides a simple API for adding sections and content.
 *
 * @author Bryan Turley <bryan@lionassociates.com>
 * @copyright Copyright (c) 2013, Lion Associates
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPL2
 * 
 * @package metabolics
 * @since 2.0
 */

// Disallow direct access to this file
if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
  exit( "Do not access this file directly." );

// Only run if Metabolics is undefined and on admin pages
if ( class_exists( 'Metabolics' ) || ! is_admin() ) return;


/**
 * Define the core Metabolics object
 * @author bturley
 * @since  2.0
 */
class Metabolics {

  /**
   * Tracks the number of Metabolics instances
   * Used primarily to avoid adding multiple form enctype parameters
   * @var integer
   * @since 2.0
   */
  static protected $instances = 0;

  /**
   * The unique identifier for the current Metabolics instace
   * @var string
   * @since 2.0
   */
  public $id;

  /**
   * The title of the created metabox
   * @var string
   * @since 2.0
   */
  public $title;

  /**
   * The Metabolics options
   * @var array
   * @since 2.0
   */
  public $options;

  /**
   * The path Metabolics will use for CSS enqueues
   * @var   string
   * @since 2.0
   */
  public $css_path;

  /**
   * The path Metabolics will use for JS enqueues
   * @var   string
   * @since 2.0
   */
  public $js_path;

  /**
   * The main array of all section data
   * @var array
   * @since 2.0
   */
  private $sections = array();


  /**
   * Creates a new instance of the core Metabolics class
   *
   * Defines plugin settings and sets up actions hooks
   * @author bturley
   * @since  2.0
   * @param  string   $id     The unique identifier for the Metabolics object
   * @param  string   $title  The text title for the new metabox
   * @param  array    $args   An array of additional arguments
   */
  public function __construct( $id, $title, $args = array() ) {
    // Add basic info
    $this->id    = $id;
    $this->title = $title;

    // Parse options against defaults
    $defaults = array(
      'hide_editor' => false,
      'tabbed'      => false,
      'post_type'   => NULL,
      'context'     => 'advanced',
      'priority'    => 'default',
    );
    $this->options = wp_parse_args( $args, $defaults );

    // Setup JS & CSS paths
    $root           = plugin_dir_url( METABOLICS_DIR . '/metabolics.php' );
    $root           = apply_filters( 'metabolics_root_path', $root );
    $this->css_path = apply_filters( 'metabolics_css_path', $root . 'stylesheets/' );
    $this->js_path  = apply_filters( 'metabolics_js_path', $root . 'javascripts/' );

    // Add custom Javascript & CSS
    add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue' ), 99 );

    // Add the meta box
    add_action( 'add_meta_boxes', array( &$this, '_add' ) );

    // Add hook for validation
    add_action( 'save_post', array( &$this, '_save' ), 10, 2 );

    // Enable file uploads (if needed)
    self::$instances++;
    if ( self::$instances <= 1 )
      add_action( 'post_edit_form_tag', array( &$this, '_set_enctype' ) );

    // Remove the editor, if required
    if ( $this->options['hide_editor'] )
      $this->hide_editor();
  }


  /**
   * Adds a new section to the metabox
   * @author bturley
   * @since  2.0
   * @param  string             $section_id     The unique identifier for this section
   * @param  string             $section_title  The title of the section
   * @return MetabolicsSection                  The section object
   */
  public function add_section( $section_id, $section_title ) {
    $section = new MetabolicsSection( $this, $section_id, $section_title );
    $this->sections[$section_id] = $section;
    return $section;
  }


  /**
   * Wrapper for the MetabolicsSection add_field function
   * Provided mainly for backwards compatibility
   * @author bturley
   * @since  2.0
   * @param  array            $args An array of field data
   * @return MetabolicsField        The field object
   */
  public function add_field( $args ) {
    $section = $this->sections[$args['section']];
    return $section->add_field( $args );
  }


  /**
   * Enqueues any required Javascript and CSS
   * @author bturley
   * @since  2.0
   * @uses   apply_filters()  metabolics_css_path Alters the path used to enqueue Metabolics CSS
   * @uses   apply_filters()  metabolics_js_path  Alters the path used to enqueue Metabolics JS
   */
  public function _enqueue() {
    global $post_id;
    if ( ! $post_id ) {
      if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
      elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
    }

    // Only enqueue on pages with a post ID (i.e. not on settings screens)
    if ( $post_id ) {
      // Enqueue CSS
      wp_enqueue_style( 'metabolics-jquery-ui', $this->css_path . 'jquery-ui/jquery-ui-custom.css', false, METABOLICS_VER, 'screen' );
      wp_enqueue_style( 'metabolics', $this->css_path . 'style.metabolics.css', false, METABOLICS_VER, 'screen' );

      // Enqueue JS
      wp_enqueue_script( 'metabolics', $this->js_path . 'script.metabolics.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' ), METABOLICS_VER, true );
    }
  }


  /**
   * Adds the metabox to the post form
   * @author bturley
   * @since 2.0
   */
  public function _add() {
    add_meta_box( $this->id, $this->title, array( &$this, '_do' ), $this->options['post_type'], 
                  $this->options['context'], $this->options['priority'] );
  }


  /**
   * Handles the output of the HTML markup for the meta fields
   * @author bturley
   * @since  2.0
   * @param  object $post The current post object
   */
  public function _do( $post ) {
    // Get the saved meta
    $saved_meta = get_post_meta( $post->ID, $this->id, true );

    // Add a nonce
    echo "<input type='hidden' name='{$this->id}_nonce' value='" . wp_create_nonce( basename( __FILE__ ) ) . "' />";

    // Output main wrapper
    echo "<div class='metabolics-container " . ( $this->options['tabbed'] ? 'metabolics-tabbed' : '' ) . "'>";

    // Output each section
    foreach( $this->sections as $section_id => $section )
      $section->output( $saved_meta );

    // Close container element
    echo "</div>";
  }


  /**
   * Saves the post meta to the database
   * 
   * Checks for the proper save actions and user permissions,
   * then validates each section. All sections are merged, then
   * store together in a single object.
   * @author bturley
   * @since  2.0
   * @param  integer  $post_id  The current post ID
   * @param  object   $post     The current post object
   */
  public function _save( $post_id, $post ) {
    // Check for valid nonce
    if ( ! isset( $_POST[$this->id . '_nonce'] ) || 
         ! wp_verify_nonce( $_POST[$this->id . '_nonce'], basename( __FILE__ ) ) ) 
        return;

    // Ignore autosaves
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    // Ignore revisions
    if ( 'revision' == $post->post_type )
        return;

    // Make sure user has correct permissions
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] )
        if ( ! current_user_can( 'edit_page', $post_id ) ) return;
    else
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Remove save action (prevents infinite loop in certain cases)
    remove_action( 'save_post', array( &$this, '_save' ), 10, 2 );

    // Sanitize the fields in each section
    $post_meta = array();
    foreach( $this->sections as $section )
      $post_meta = array_merge( $post_meta, $section->save( $post_id ) );

    // Update the saved data array
    update_post_meta( $post_id, $this->id, $post_meta );
  }


  /**
   * Outputs the enctype parameter on the metabox form element
   * @author bturley
   * @since 2.0
   */
  public function _set_enctype() {
    echo ' enctype="multipart/form-data"';
  }


  /**
   * Hides the built-in Wordpress content editor
   * @author bturley
   * @since  2.0
   */
  private function hide_editor() {
    // If custom post type specified, just use it
    if ( $this->options['post_type'] ) {
      remove_post_type_support( $this->options['post_type'], 'editor' );
      return;
    }

    // Otherwise, find the post ID
    global $post_id;
    if ( ! $post_id ) {
      if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
      elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
    }

    // Get the post type & remove the editor
    $post_type = $post_id ? get_post_type( $post_id ) : 'post';
    remove_post_type_support( $post_type, 'editor' );
  }
}

?>