<?php
/**
* Includes Default Page Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\mb__page
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );

/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'page_metabox' );

function page_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	$template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
	
	if ( 'page' == get_post_type($post_id) && 'page-HOME.php' !== $template_file && 'page-MENUS.php' !== $template_file ) {
	    
	    
	$args = array(
		'tabbed' => false
	);  	
	
	$page_metabox = new Metabolics( 'page_metabox', 'Page Options', $args );
		 
		$general = $page_metabox->add_section( 'general', '' );

			$general->add_field( array(
		            'type'    => 'checkbox',
		            'id'      => 'show_subnav',
		            'title'   => 'Show Sub-Navigation?',
		            'desc' 	  => 'Check this box to display sub-navigation on this page.',
		        ) );
        
	}
	
}

?>
