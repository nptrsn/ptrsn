/*!
 * Image uploader JS for the old (< WP 3.5) built-in image uploader
 *
 * @author    Bryan Turley <bryan@lionassociates.com>
 * @copyright Copyright (c) 2013, Lion Associates
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL2
 *
 * @package  metabolics
 * @since    2.0
 */

jQuery( document ).ready( function( $ ) {

  var container, send_to_editor = window.send_to_editor;

  $( '.metabolics-upload-media' ).click( function() {
    container = $( this ).closest( '.metabolics-uploader' );
    var post_id = parseInt( container.find( '.metabolics-uploader-post-id' ).val(), 10 );
    tb_show( 'Upload Image', 'media-upload.php?type=image&TB_iframe=true&post_id=' + post_id, false );
    return false;
  } );

  window.send_to_editor = function( html ) {
    if ( typeof container === 'undefined' ) {
      send_to_editor( html );
      return;
    }
    var image_url = $( 'img', html ).attr( 'src' );
    if ( container.find( 'img' ).length > 0 )
      container.find( 'img' ).attr( 'src', image_url );
    else
      container.prepend( $( '<img />' ).attr( 'src', image_url ) );
    container.find( '.metabolics-uploader-image-url' ).val( image_url );
  };
} );