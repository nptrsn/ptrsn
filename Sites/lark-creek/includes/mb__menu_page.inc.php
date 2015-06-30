<?php
/**
* Includes Menu Page Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\mb__menu_page
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );

/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'menu_page_metabox' );

function menu_page_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	$template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
	
	if ( 'page-MENU-PAGE.php' === $template_file ) {

		$json = la_get_menus();
		$menus = $json->menus;
		$titles = array(
			array(
				'label' => 'Select One',
				'value' => 0
				),
			);

		foreach ( $menus as $menu ) {
			$titles[] = array(
    			'label' => $menu->title,
    			'value' => $menu->title
    			);
		}

		$args = array(
			'tabbed' => false
		);  	
		$menu_page_metabox = new Metabolics( 'menu_page_metabox', 'Menu Configuration', $args );
			 
			$general = $menu_page_metabox->add_section( 'general', '' );

				$general->add_field( array(
			            'type'    => 'select',
			            'id'      => 'menu_name',
			            'title'   => 'SinglePlatform Menu Connect',
			            'options' => $titles,
			            'description' => 'These choices are the current selection of menus avaiable on SinglePlatform. If you do not see the menu you are looking for please login to SinglePlatform and verify your menu is published there.'
			        ) );

				$general->add_field( array(
			            'type'    => 'attachment',
			            'id'      => 'pdf',
			            'title'   => 'Downloadable PDF',
			            'description' => 'Upload the downloadable version of the menu for this page.'
			        ) );

				$general->add_field( array(
			            'type'    => 'text',
			            'id'      => 'pdf_link_text',
			            'title'   => 'Link Text',
			            'size'	  => 'large',
			            'description' => 'Optional custom PDF link text.'
			        ) );
				
        
	}
	
}

?>
