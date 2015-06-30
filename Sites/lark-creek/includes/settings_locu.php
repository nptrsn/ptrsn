<?php
function la__locu_settings() {
  $key = 'la__locu_settings';
  $settings = new Setabolics( $key, 'Locu', 'Locu Settings' );
  $settings->add_tab( $key, "{$key}_general", 'Site Configuration' );

  $settings->add_field( array(
      'type'  => 'text',
      'id'    => 'location_id',
      'title' => __( 'Location ID', 'la' ),
      'tab'   => "{$key}_general",
  ) );
  
  $settings->add_field( array(
      'type'  => 'text',
      'id'    => 'client_id',
      'title' => __( 'Client ID', 'la' ),
      'tab'   => "{$key}_general",
  ) );

  $settings->add_field( array(
      'type'        => 'text',
      'id'          => 'signing_key',
      'title' => __( 'Signing Key', 'la' ),
      'tab'         => "{$key}_general",
  ) );

 
}

add_filter( 'init', 'la__locu_settings' );
