/*!
 * Attachment uploader JS for the new (WP 3.5+) built-in media uploader
 *
 * @author    Bryan Turley <bryan@lionassociates.com>
 * @copyright Copyright (c) 2013, Lion Associates
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL2
 *
 * @package  metabolics
 * @since    2.0
 */

jQuery( document ).ready( function( $ ) {
    var container, _custom_media = true;

    // WP 3.5+
    $( '.metabolics-attachment' ).click( function() {
        container = $( this ).closest( '.metabolics-uploader' );
        var _orig_send_attachment = wp.media.editor.send.attachment;
        wp.media.editor.send.attachment = function( properties, attachment ) {
            if ( _custom_media ) {
                var url   = attachment.url,
                    title = attachment.title;
                if ( container.find( '.metabolics-attachment-link' ).length > 0 )
                    container.find( '.metabolics-attachment-link' ).attr( 'href', url ).text( title );
                else
                    $( '<a />' ).attr( 'href', url ).addClass( 'metabolics-attachment-link' ).text( title ).prependTo( container );

                container.find( '.metabolics-attachment-url' ).val( url );
                container.find( 'a.metabolics-uploader-delete' ).css( 'display', 'inline-block' );
            } else {
                return _orig_send_attachment.apply( this, [properties, attachment] );
            }
        };
        wp.media.editor.open( $( this ) );
        return false;
    } );

    $( '.add_media' ).on( 'click', function() {
        _custom_media = false;
    } );

    $( '.metabolics-uploader' ).each( function() {
        if ( $( this ).find( '.metabolics-attachment-link' ).length > 0 )
            $( this ).find( 'a.metabolics-uploader-delete' ).css( 'display', 'inline-block' );
    } );

    $( 'a.metabolics-uploader-delete' ).click( function() {
        container = $( this ).closest( '.metabolics-uploader' );
        container.find( '.metabolics-attachment-link' ).remove();
        container.find( '.metabolics-attachment-url' ).val( '' );
        $( this ).hide();
        return false;
    } );

} );