/*!
 * File:        setabolics.js
 * Description: Javascripts to run the Setabolics actions
 * Author:      Bryan Turley
 * Author URI:  http://www.bryanturley.com
 * License:     GPL2
 */

jQuery( document ).ready( function( $ ) {

    /* -- jQuery UI Tabs -- */
    // Find each section and wrap it
    var wrapped = $( '.wrap h3' ).wrap( '<div class=\"setabolics-tabs-panel\">' );
    wrapped.each( function( index ) {
        // Set the tab ID
        var content = $( this ).parent().nextUntil( 'div.setabolics-tabs-panel' ),
            tabID   = $( '.setabolics-tabs-nav li' ).eq( index ).find( 'a' ).attr( 'href' ).replace( '#', '' );
        $( this ).parent().attr( 'id', tabID ).append( content );
        $( this ).remove();
    } );
    
    // Hide all but first tab
    $( '.setabolics-tabs-panel' ).addClass( 'setabolics-tabs-hide' );
    $( '.setabolics-tabs-panel' ).first().removeClass( 'setabolics-tabs-hide' );
    
    // Init the tabs
    $( '.setabolics-tabs' ).tabs();

    /* -- Headings -- */
    $( '.setabolics-heading' ).each( function() {
        $( this ).closest( 'tr' ).prev().remove();
    } );

} );