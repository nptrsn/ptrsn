/*!
 * Adds JS support for core Metabolics functionality
 *
 * @author    Bryan Turley <bryan@lionassociates.com>
 * @copyright Copyright (c) 2013, Lion Associates
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL2
 *
 * @package  metabolics
 * @since    2.0
 */

jQuery( document ).ready( function( $ ) {

  $( '.metabolics-header' ).on( 'click', function() {
    if ( $( this ).closest( '.metabolics-tabs' ).length > 0 ) return;
    if ( $( this ).hasClass( 'collapsed' ) )
      $( this ).next().show();
    else
      $( this ).next().hide();
    $( this ).toggleClass( 'collapsed' );
  } );



  /* -- Misc Fields -- */
  $( '.metabolics-color' ).addClass( 'color {hash:true}' );

  if ( $( '.metabolics-date' ).length > 0 )
    $( '.metabolics-date' ).datepicker( { dateFormat : 'yy-mm-dd' } );

  if ( $( '.metabolics-time' ).length > 0 )
    $( '.metabolics-time' ).timepicker( { timeFormat : 'HH:mm z' } );


  /* -- Sortable -- */
  // Sortable Setup
  $( '.metabolics-sortable' ).sortable( {
    placeholder : 'metabolics-sortable-highlight',
    update      : function( e, ui ) {
      var $list  = ui.item.parent(),
          $input = $( '#' + $list.attr( 'id' ).replace( '_list', '' ) ),
          order  = '';
      $input.val( getSortableOrder( $list ) );
    }
  } );
  $( '.metabolics-sortable' ).disableSelection();

  // Add button actions
  $( '.metabolics-sortable-add' ).click( function() {
    var target  = $( this ).attr( 'href' ),
        id      = target.replace( '_list', '' ),
        $list   = $( target ),
        $select = $( id + '_options' ),
        $option = $select.find( 'option:selected' ),
        $input  = $( id ),
        $item   = $( '<li class="metabolics-sortable-default" />' );

    // Insert new item
    var index = $list.find( 'li[data-metabolics-value^="' + $option.val() + '"]' ).length;
    $item.attr( 'data-metabolics-value', $option.val() + '_' + index );
    $item.html( $option.text() + ' (' + ( index + 1 ) + ') <a class="metabolics-sortable-remove" href="#' + $option.val() + '">Remove</a>' );
    $list.append( $item.hide().fadeIn() );

    // Set the order
    $input.val( getSortableOrder( $list ) );
    return false;
  } );

  // Remove button actions
  $( '.metabolics-sortable' ).on( 'click', '.metabolics-sortable-remove', function() {
    var $list  = $( this ).closest( 'ul' ),
        $input = $( '#' + $list.attr( 'id' ).replace( '_list', '' ) );
    $( this ).parent().fadeOut( function() {
      $( this ).remove();
      $input.val( getSortableOrder( $list ) );
    } );

    return false;
  } );

  // Helper function to get the item order
  function getSortableOrder( $list ) {
    var order = '';
    $list.children().each( function() {
      order += $( this ).attr( 'data-metabolics-value' ) + ',';
    } );
    return order.replace( /,$/, '' );
  }


  /* -- Tabs -- */
  $( '.metabolics-tabbed' ).each( function() {
    var $titles = $( this ).find( '.metabolics-header' ),
        $tabs   = $( '<ul class="metabolics-tabs" />' );

    $titles.each( function() {
      $( this ).detach().appendTo( $tabs );
    } );
    $( this ).prepend( $tabs );
    $tabs.children().wrap( $( '<li />' ) );
    $tabs.children().first().find( '.metabolics-header' ).addClass( 'active' );
    $( this ).find( '.metabolics-table' ).first().addClass( 'active' );
  } );

  $( '.metabolics-tabbed .metabolics-header' ).click( function() {
    var $container = $( this ).closest( '.metabolics-tabbed' ),
      $titles    = $container.find( '.metabolics-header' );

    $titles.removeClass( 'active' );
    $( this ).addClass( 'active' );

    $container.find( '.metabolics-table' ).removeClass( 'active' );
    $( '#' + $( this ).attr( 'id' ) + '-table' ).addClass( 'active' );
  } );
} );