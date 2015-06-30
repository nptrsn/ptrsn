<?php
/**
 * File:       functions.php
 * Desc:       Front-end, theme-related functions
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */

/*
 * 1. Initialize plugin core
 * ========================================================================= */

// Include the core files
require get_template_directory() . '/includes/libs/metabolics/metabolics.php';
require get_template_directory() . '/includes/libs/setabolics/setabolics.php';
require get_template_directory() . '/includes/libs/SP_ApiLibrary.php'; //SinglePlatform API
require get_template_directory() . '/includes/init.php';


// Set the Metabolics and Setabolics root directories
add_filter( 'metabolics_root_path', 'la__metabolics_root' );
add_filter( 'setabolics_root_path', 'la__setabolics_root' );

/**
 * Sets the root Metabolics directory
 * @author bturley
 * @return string The new root Metabolics directory
 */
function la__metabolics_root() {
  return get_template_directory_uri() . '/includes/libs/metabolics/';
}


/**
 * Sets the root Setabolics directory
 * @author bturley
 * @return string The new root Setabolics directory
 */
function la__setabolics_root() {
  return get_template_directory_uri() . '/includes/libs/setabolics/';
}


/**
 * The global plugin core object
 * @var LACore
 */
$la__core = new LACore( array(
  'libs',
  '_mb__menu_page.inc-OLD.php',
) );




/*
 * 2. Theme Setup
 * ========================================================================= */

// Sets the theme content width (used for automatic image/content sizing)
// Adjust to the widest possible width of the main content container
if ( ! isset( $content_width ) )
  $content_width = 604;

/**
 * Setups up the theme
 * @author bturley
 */
function la__theme_setup() {
  // Setup image cropping
  add_theme_support( 'post-thumbnails' );
  add_image_size( 'fullscreen', 1280, 960, false );
  // set_post_thumbnail_size( THUMB_WIDTH, THUMB_HEIGHT, CROP_TRUE/FALSE );

  // Make Wordpress output valid HTML5 for generated content
  add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

  // Register core navigation menus
  register_nav_menu( 'main-menu', 'Main Menu' );
  register_nav_menu( 'footer-menu', 'Footer Menu' );
}
add_action( 'init', 'la__theme_setup' );


/**
 * Flushes Wordpress rewrite rules on theme activation
 */
function la__flush_rewrite_rules() {
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}
register_activation_hook( __FILE__, 'la__flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'la__flush_rewrite_rules' );


/**
 * Enqueues the theme stylesheets
 * @author bturley
 */
function la__enqueue_styles() {
  $theme = wp_get_theme();
  wp_enqueue_style( 'style', get_stylesheet_uri(), array(), $theme->Version );
}
add_action( 'wp_enqueue_scripts', 'la__enqueue_styles' );


/**
 * Enqueues theme Javascript
 * @author bturley
 */
function la__enqueue_scripts() {
  $theme = wp_get_theme();

  // Change jQuery to Google CDN
  wp_deregister_script( 'jquery' );
  wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, $theme->Version, true );

  // Core Theme JS
  wp_enqueue_script( 'core', get_stylesheet_directory_uri() . '/javascripts/site.js', array( 'jquery' ), $theme->Version, true );

  // jQuery Migrate
  wp_enqueue_script( 'migrate', get_stylesheet_directory_uri() . '/javascripts/jquery.migrate.min.js', array( 'jquery' ), $theme->Version, true );

  // Soekan Theme (Old Theme) JS
  wp_enqueue_script( 'cycle', get_stylesheet_directory_uri() . '/javascripts/jquery.cycle2.js', array( 'jquery' ), $theme->Version, true );
  wp_enqueue_script( 'swipe', get_stylesheet_directory_uri() . '/javascripts/jquery.cycle2.swipe.min.js', array( 'jquery' ), $theme->Version, true );
  //wp_enqueue_script( 'maximage', get_stylesheet_directory_uri() . '/javascripts/jquery.maximage.js', array( 'jquery' ), $theme->Version, true );

} // la__enqueue_scripts();
add_action( 'wp_enqueue_scripts', 'la__enqueue_scripts' );



/**
 * Adds a "has-submenu" class to parent menus
 * @param  array  $classes An array of the element's current class_exists
 * @param  object $item    The post object of the current menu item
 * @return array           The modified list of classes
 */
function la__add_nav_menu_parent_class( $classes, $item ) {
  global $wpdb;
  $has_children = $wpdb->get_var( "SELECT COUNT( meta_id ) 
                                     FROM $wpdb->postmeta 
                                    WHERE meta_key   = '_menu_item_menu_item_parent' 
                                      AND meta_value = '" . $item->ID . "'" );
  if ( $has_children > 0 )
    array_push( $classes, "has-submenu" );
  return $classes;
}
add_filter( "nav_menu_css_class", "la__add_nav_menu_parent_class", 10, 2 );



/*
 * 3. Helper Functions
 * ========================================================================= */

/**
 * Returns the value of the specified option. Loads the options array
 * if it's unset, then returns the value from options cache if available
 * @author bturley
 * @param  string   $option   The option name
 * @return mixed              The option value or NULL if not found
 */
function la__get_option( $option ) {
  global $la__options;
  if ( ! isset( $la__options ) )
    $la__options = get_option( 'la__settings' );
  return isset( $la__options[$option] ) ? $la__options[$option] : NULL;
}


/**
 * Updates a core theme option
 * @author bturley
 * @param  string   $option   The option name
 * @param  mixed    $value    The new option value
 * @return boolean            True if the option was updated, false if not
 */
function la__update_option( $option, $value ) {
  global $la__options;
  if ( ! isset( $la__options ) )
    $la__options = get_option( 'la__settings' );
  $la__options = $la__options ? $la__options : array();
  $la__options[$option] = $value;
  return update_option( $option, $la__options );
}


/**
 * Generates and returns a post excerpt for the given ID. Does NOT
 * append "more" text to the end (e.g. "[...]")
 * @param  integer $post_id The ID of the post to fetch an excerpt
 * @param  integer $length  The maximum number of words in the excerpt
 * @return string           The shortened excerpt
 */
function la__get_excerpt_by_id( $post_id, $length = NULL ) {
  $excerpt_length = isset( $length ) ? $length : apply_filters( 'excerpt_length', 35 ); 
  $the_post       = get_post( $post_id );
  $excerpt        = $the_post->post_content;
  $excerpt        = strip_tags( strip_shortcodes( $excerpt ) );
  $words          = explode( ' ', $excerpt, $excerpt_length + 1 );
  $words          = array_slice( $words, 0, $excerpt_length );
  $result         = trim( implode( ' ', $words ) );
  $result         = preg_replace( '/\W*$/', '', $result );
  return $result;
}


/**
 * Checks if more than one page exists
 * @return boolean True if more than one page, false otherwise
 */
function la__is_paged() {
  global $wp_query;
  return $wp_query->max_num_pages > 1;
}


/**
 * Returns the attachment ID for the given attachement URL
 * @author bturley
 * @param  string   $url  The image url
 * @return integer        The attachment ID
 */
function la__get_image_id_by_url( $url ) {
  global $wpdb;
  $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";
  return $wpdb->get_var( $query );
}


/**
 * Returns the number of posts tagged with the given term
 * @author bturley
 * @param  integer  $term_id    The term ID to count
 * @param  integer  $taxonomy   The slug of the term's taxonomy
 * @param  string   $post_type  The post type to count
 * @return integer              The number of posts assigned the term
 */
function la__get_term_posts_count( $term_id, $taxonomy, $post_type = 'post' ) {
  global $wpdb;
  $term  = get_term_by( 'id', $term_id, $taxonomy ); 
  $sql   = "SELECT     COUNT( * ) 
            FROM       $wpdb->posts
            INNER JOIN $wpdb->term_relationships
            ON         ( $wpdb->posts.ID = $wpdb->term_relationships.object_id )
            INNER JOIN $wpdb->term_taxonomy
            ON         ( $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id )
            WHERE      $wpdb->posts.post_type = '$post_type'
            AND        $wpdb->term_taxonomy.taxonomy = '$taxonomy'
            AND        $wpdb->term_taxonomy.term_id = '{$term->term_id}';";

  return $wpdb->get_var( $sql );
}


/**
 * Returns an array of all page IDs that are assigned the given template
 * @author bturley
 * @param  string   $template   The file name of the page template
 * @return array                An array of all IDs using that page template
 */
function la__get_page_ids_by_template( $template ) {
  global $wpdb;
  $sql = "SELECT post_id 
            FROM $wpdb->postmeta 
           WHERE meta_key   = '_wp_page_template'
             AND meta_value = %s";
  $query = $wpdb->prepare( $sql, $template );
  $ids   = array();
  
  if ( $results = $wpdb->get_results( $query ) ) {
    foreach( $results as $result )
      $ids[] = $result->post_id; 
  }
  return $ids;
}



/**
 * Single Platform Menu API Integration
 */
function la_get_menus( ) {
  $sp_option = get_option( 'la__sp_settings' );

  $signingKey = $sp_option['signing_key'];
  $clientId = $sp_option['client_id'];
  $locId = $sp_option['location_id'];
  $host = "api.singleplatform.co";
  $protocol = "http";

  $sp_api = new SP_ApiLibrary( $signingKey, $clientId, $host, $protocol, $debug );
  $value = $sp_api->getMenus( $locId );
  $json = json_encode( $value );
  $decoded = json_decode( $json );

  return $decoded;
}

function la_display_menus( $menu_name ) {
  global $post;
  $meta = get_post_meta( $post->ID, 'menu_page_metabox', true );
  $this_menu = isset( $meta['menu_name'] ) ? $meta['menu_name'] : '';
  
  $json = la_get_menus();
  $all_menus = $json->menus;

  if ( $this_menu ) {

    foreach ( $all_menus as $menu ) {

      if ( $this_menu === $menu->title ) {

        $entries = $menu->entries;

        echo '<ul class="sp-menu">';

        foreach ( $entries as $entry ) {

          $title = ( $entry->type === 'section' ) ? true : false;
          $h_open = $title ? '<h2>' : '';
          $h_closed = $title ? '</h2>' : '';
          $item = ( $entry->type === 'item' ) ? true : false;
          $i_open = $item ? '<span class="item">' : '';
          $i_closed = $item ? '</span>' : '';

          echo '<li>' . $h_open . $i_open . $entry->title . $i_closed . $h_closed ;

          if ( $item ) {

            foreach ( $entry->prices as $price ) {

              echo '<div class="price-details"><span class="title">' . $price->title . '</span>';
              echo '<span class="price">' . $price->price . '</span></div>';

            }
            echo '<p>' . $entry->desc . '</p>';
          }

          echo '</li>';

        }

        echo '</ul>';

      }

    }

  }

}




?>