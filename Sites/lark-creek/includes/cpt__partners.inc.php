<?php
/**
* Includes Partners Custom Post Type & Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\cpt__partners
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );


/** Partners POST TYPE **/
add_action('init', 'partners_post_type');

function partners_post_type() {

   $labels = array(
       'name' => _x('Partners', 'partners'),
       'singular_name' => _x('Partner', 'partner'),
       'add_new' => _x('Add New', 'Partner'),
       'add_new_item' => __('Add New Partner'),
       'edit_item' => __('Edit Partner'),
       'new_item' => __('New Partner'),
       'all_items' => __('All Partners'),
       'view_item' => __('View Partner'),
       'search_items' => __('Search Partners'),
       'not_found' => __('No partners found'),
       'not_found_in_trash' => __('No partners found in Trash'),
       'parent_item_colon' => '',
       'menu_name' => 'Partners'
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

register_post_type('partners', $args);   

}


/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'partners_metabox' );

function partners_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	
	if ( 'partners' == get_post_type($post_id) ) {
	    
	    
		$args = array(
		'tabbed' => false,
		);    
		$partners_metabox = new Metabolics( 'partners_metabox', 'Partner Options', $args );
		$section = $partners_metabox->add_section( 'general', '' );

      $section->add_field( array(
          'type'    => 'image',
          'id'      => 'logo',
          'title'   => 'Partner Logo Image',
      ) );

      $section->add_field( array(
          'type'    => 'text',
          'id'      => 'link',
          'title'   => 'Partner URL',
          'description'    => 'Enter the full URL including protocol (http://...).'
      ) );
        
	}
	
}
