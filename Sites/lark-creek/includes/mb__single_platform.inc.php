<?php
/**
* Includes Single Platform Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\mb__single_platform
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );

/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'menu_metabox' );

function menu_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	$template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
	
	if ( 'page-MENUS.php' === $template_file ) {
	    
	    
	$args = array(
		'tabbed' => false
	);  	
	
	$menu_metabox = new Metabolics( 'menu_metabox', 'Menu Options - SinglePlatform Integration', $args );
		 
		$general = $menu_metabox->add_section( 'general', '' );

			$general->add_field( array(
		            'type'    => 'text',
		            'id'      => 'container',
		            'title'   => 'Container Code',
		            'allow_html' => true,
		            'desc' 	  => 'This will be the first line of code in the SinglePlatform snippet.',
		        ) );

			$general->add_field( array(
		            'type'    => 'code',
		            'id'      => 'script',
		            'title'   => 'Scripts Code',
		            'desc' 	  => 'Everything after the first line of code in the SinglePlatform snippet.',
		        ) );
        
	}
	
}

?>
