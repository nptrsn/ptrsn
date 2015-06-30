<?php
/**
* Includes Events Custom Post Type & Metabox
*
* Copyright: © 2013
* {@link http://www.lionassociates.com/ Lion Associates}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package larkcreek\includes\cpt__events
* @since 1.0
*/

if ( realpath( __FILE__ ) === realpath( $_SERVER[ "SCRIPT_FILENAME" ] ) )
	exit( "Do not access this file directly." );


/** Events POST TYPE **/
add_action('init', 'events_post_type');

function events_post_type() {

   $labels = array(
       'name' => _x('Events', 'events'),
       'singular_name' => _x('Event', 'event'),
       'add_new' => _x('Add New', 'Event'),
       'add_new_item' => __('Add New Event'),
       'edit_item' => __('Edit Event'),
       'new_item' => __('New Event'),
       'all_items' => __('All Events'),
       'view_item' => __('View Event'),
       'search_items' => __('Search Events'),
       'not_found' => __('No events found'),
       'not_found_in_trash' => __('No events found in Trash'),
       'parent_item_colon' => '',
       'menu_name' => 'Events'
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
           'editor',
           'thumbnail'
       )
   );

register_post_type('lc_events', $args);   

}


/* /\/\/\/\/\/\/\/\/\/\ METABOLICS METABOXES /\/\/\/\/\/\/\/\/\/\ */
add_action( 'admin_init', 'events_metabox' );

function events_metabox() {
	
	if ( ! is_admin() ) return;
	
	global $post_id;

	if ( ! $post_id ) {
	    if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
	    elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
	    else return;
	}
	
	
	if ( 'lc_events' == get_post_type($post_id) ) {
	    
	    
		$args = array(
		'tabbed' => false,
		);    
		$wine_metabox = new Metabolics( 'events_metabox', 'Event Configuration', $args );
		$section = $wine_metabox->add_section( 'general', '' );

  		$section->add_field( array(
          'type'    => 'radio',
          'id'      => 'img_align',
          'title'   => 'Featured Image Alignment',
          'description'    => 'Designate which side of the text the featured image will be displayed in the list of events.',
          'options' => array(
              array(
                'label' => 'Left',
                'value' => 'left'
                ),
              array(
                'label' => 'Right',
                'value' => 'right'
                ),
            ),
      ) );

      $section->add_field( array(
          'type'    => 'date',
          'id'      => 'event_date',
          'title'   => 'Event Date',
          'description'    => 'Click on the field and select the date from the calendar. You can enter the date manually, but it must be in this format: YYYY-MM-DD'
      ) );

      $section->add_field( array(
  	      'type'    => 'checkbox',
  	      'id'      => 'show_on_home',
  	      'title'   => 'Show on Home Page?',
  	      'description'    => 'Check this box to display this event on the Home Page.'
  	  ) );

      $section->add_field( array(
          'type'    => 'textarea',
          'id'      => 'event_excerpt',
          'title'   => 'Event Excerpt',
          'description'    => 'If filled, this will be used as the abbreviated description (Home Page display only).'
      ) );

      
        
	}
	
}
