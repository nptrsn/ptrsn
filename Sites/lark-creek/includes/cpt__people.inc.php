<?php
/**
* Includes People Custom Post Type & Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\cpt__people
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );


/** People POST TYPE **/
add_action('init', 'people_post_type');

function people_post_type() {

   $labels = array(
       'name' => _x('People', 'people'),
       'singular_name' => _x('Person', 'person'),
       'add_new' => _x('Add New', 'Person'),
       'add_new_item' => __('Add New Person'),
       'edit_item' => __('Edit Person'),
       'new_item' => __('New Person'),
       'all_items' => __('All People'),
       'view_item' => __('View Person'),
       'search_items' => __('Search People'),
       'not_found' => __('No people found'),
       'not_found_in_trash' => __('No people found in Trash'),
       'parent_item_colon' => '',
       'menu_name' => 'People'
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
       'hierarchical' => false,
       'menu_position' => 5,
       'supports' => array(
           'title',
           'editor',
           'page-attributes'
       )
   );

register_post_type('people', $args);   

}


/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'people_metabox' );

function people_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	
	if ( 'people' == get_post_type($post_id) ) {
	    
	    
		$args = array(
		'tabbed' => false,
		);    
		$people_metabox = new Metabolics( 'people_metabox', 'People Details', $args );
		$section = $people_metabox->add_section( 'general', '' );

      $section->add_field( array(
          'type'    => 'text',
          'id'      => 'first_name',
          'title'   => 'First Name',
          'size'    => 'large'
      ) );

      $section->add_field( array(
          'type'    => 'text',
          'id'      => 'last_name',
          'title'   => 'Last Name',
          'size'    => 'large'
      ) );

      $section->add_field( array(
          'type'    => 'text',
          'id'      => 'title',
          'title'   => 'Title',
          'size'    => 'large'
      ) );

      $section->add_field( array(
          'type'    => 'email',
          'id'      => 'email',
          'title'   => 'Email',
          'size'    => 'large'
      ) );

      $section->add_field( array(
          'type'    => 'text',
          'id'      => 'phone',
          'title'   => 'Phone',
          'size'    => 'large'
      ) );

      $section->add_field( array(
          'type'    => 'text',
          'id'      => 'linked',
          'title'   => 'LinkedIn URL',
          'size'    => 'large'
      ) );

      $section->add_field( array(
          'type'    => 'image',
          'id'      => 'headshot',
          'title'   => 'Headshot Image',
      ) );
        
	}
	
}
