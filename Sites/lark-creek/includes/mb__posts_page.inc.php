<?php
/**
* Includes Posts Page Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\mb__posts_page
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );

/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'posts_page_metabox' );

function posts_page_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	if ( $post_id == get_option( 'page_for_posts' ) ) {   
	    
	$args = array(
		'tabbed' => false
	);  	
	
	$posts_page_metabox = new Metabolics( 'posts_page_metabox', 'Posts Page Options', $args );
		 
		$general = $posts_page_metabox->add_section( 'general', '' );

			$general->add_field( array(
		            'type'    => 'editor',
		            'id'      => 'sidebar_content',
		            'title'   => 'Left Sidebar Content',
		            
		        ) );
        
	}
	
}

?>
