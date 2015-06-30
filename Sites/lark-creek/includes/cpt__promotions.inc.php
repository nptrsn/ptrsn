<?php
/**
* Includes Promotions Custom Post Type & Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\cpt__promotions
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );


/** Promotions POST TYPE **/
add_action('init', 'promotions_post_type');

function promotions_post_type() {

   $labels = array(
       'name' => _x('Promotions', 'promotions'),
       'singular_name' => _x('Promotion', 'promotion'),
       'add_new' => _x('Add New', 'Promotion'),
       'add_new_item' => __('Add New Promotion'),
       'edit_item' => __('Edit Promotion'),
       'new_item' => __('New Promotion'),
       'all_items' => __('All Promotions'),
       'view_item' => __('View Promotion'),
       'search_items' => __('Search Promotions'),
       'not_found' => __('No promotions found'),
       'not_found_in_trash' => __('No promotions found in Trash'),
       'parent_item_colon' => '',
       'menu_name' => 'Promotions'
   );

   $args = array(
       'labels' => $labels,
       'public' => true,
       'publicly_queryable' => true,
       'show_ui' => true,
       'show_in_menu' => true,
       'query_var' => true,
       'rewrite' => true,
       'capability_type' => 'post',
       'has_archive' => false,
       'hierarchical' => true,
       'menu_position' => 5,
       'supports' => array(
           'title',
           'editor'
       )
   );

register_post_type('promotions', $args);   

}


/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'promotions_metabox' );

function promotions_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	
	if ( 'promotions' == get_post_type($post_id) ) {
	    
	    
		$args = array(
		'tabbed' => false,
		);    
		$promotions_metabox = new Metabolics( 'promotions_metabox', 'Promotion Options', $args );
		$section = $promotions_metabox->add_section( 'general', '' );

      $section->add_field( array(
  	      'type'    => 'checkbox',
  	      'id'      => 'show_on_home',
  	      'title'   => 'Show on Home Page?',
  	      'description'    => 'Check this box to display this Promotion on the Home Page.'
  	  ) );

      $section->add_field( array(
          'type'    => 'text',
          'id'      => 'link_text',
          'title'   => 'Link Text',
          'description'    => 'Enter the text label for the link.'
      ) );

      $section->add_field( array(
          'type'    => 'text',
          'id'      => 'link_url',
          'title'   => 'Link URL',
          'description'    => 'Enter the full URL including protocol (http://...).'
      ) );

      
        
	}
	
}
