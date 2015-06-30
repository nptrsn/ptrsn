<?php
/**
* Includes Gallery Page Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\mb__gallery_page
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );

/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'gallery_page_metabox' );

function gallery_page_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	$template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
	
	if ( 'page-GALLERY.php' === $template_file || 'page-PRIVATE-DINING.php' === $template_file || 'page-HOME.php' === $template_file ) {
	    
	    $galleries_array = array(
	    		array(
	    			'label' => 'Select One',
	    			'value' => 0
	    			)
	    	);
	    $gallery_posts = get_posts( array(
	    	'post_type' => 'galleries',
	    	'posts_per_page' => -1
	    	) );
	    foreach ( $gallery_posts as $gallery ) {
	    	$galleries_array[] = array(
	    			'label' => $gallery->post_title,
	    			'value' => $gallery->ID
	    		);
	    }

		$args = array(
			'tabbed' => false
		);  	
		$gallery_page_metabox = new Metabolics( 'gallery_page_metabox', 'Full-Page Slideshow Options', $args );
			 
			$general = $gallery_page_metabox->add_section( 'general', '' );

				$general->add_field( array(
			            'type'    => 'select',
			            'id'      => 'gallery_id',
			            'title'   => 'Select Gallery to Display',
			            'desc' 	  => 'Select which Gallery to display on this page. To edit an existing Gallery, or to add a new Gallery <a href="'.get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=galleries">click here.</a>',
			            'options' => $galleries_array,
			        ) );
        
	}
	
}

?>
