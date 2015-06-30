<?php
/**
* Includes Reservation Page Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\mb__reservations
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );

/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'reservations_metabox' );

function reservations_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	$template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
	
	if ( 'page-RESERVATIONS.php' === $template_file ) {

		$args = array(
			'tabbed' => false
		);  	
		$reservations_metabox = new Metabolics( 'reservations_metabox', 'Open Table Widget', $args );
			 
			$general = $reservations_metabox->add_section( 'general', '' );

				$general->add_field( array(
			            'type'    => 'code',
		            	'id'      => 'open_table_code',
		            	'title'   => 'Open Table Widget Code',
		            	'desc' 	  => 'Paste in the code for the Open Table widget',
			        ) );
        
	}
	
}

?>
