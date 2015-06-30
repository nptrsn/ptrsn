<?php
/**
* Includes Galleries Custom Post Type & Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\cpt__galleries
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );


/** Galleries POST TYPE **/
add_action('init', 'galleries_post_type');

function galleries_post_type() {

   $labels = array(
       'name' => _x('Galleries', 'galleries'),
       'singular_name' => _x('Gallery', 'gallery'),
       'add_new' => _x('Add New', 'Gallery'),
       'add_new_item' => __('Add New Gallery'),
       'edit_item' => __('Edit Gallery'),
       'new_item' => __('New Gallery'),
       'all_items' => __('All Galleries'),
       'view_item' => __('View Gallery'),
       'search_items' => __('Search Galleries'),
       'not_found' => __('No galleries found'),
       'not_found_in_trash' => __('No galleries found in Trash'),
       'parent_item_colon' => '',
       'menu_name' => 'Galleries'
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
           'thumbnail'
       )
   );

register_post_type('galleries', $args);   

}


/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'gallery_metabox' );

function gallery_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	
	if ( 'galleries' == get_post_type($post_id) ) {
	    
	    
		$args = array(
		'tabbed' => false,
		);    
		$gallery_metabox = new Metabolics( 'gallery_metabox', 'Gallery Options', $args );
		$section = $gallery_metabox->add_section( 'general', '' );

      $section->add_field( array(
          'type'    => 'integer',
          'id'      => 'img_count',
          'title'   => 'Image Count',
          'size'    => 'short',
          'description'    => 'How many images would you like in this Gallery? After you make you selection and click Update, an uploader will be provided for each image needed.'
      ) );

      $meta = get_post_meta( $post_id, 'gallery_metabox', true );
      $count = isset( $meta['img_count'] ) ? $meta['img_count'] : false;

      if ( $count ) {
        for ( $i = 1; $i <= $count; $i++ ) {

          $section->add_field( array(
              'type'    => 'image',
              'id'      => "img{$i}",
              'title'   => "Image {$i}",
          ) );

        }
      }

      

      
        
	}
	
}
