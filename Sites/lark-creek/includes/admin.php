<?php


function la__do_settings() {
  $key = 'la__settings';
  $settings = new Setabolics( $key, 'Theme Settings', 'Theme Settings' );
  $settings->add_tab( $key, "{$key}_general", 'General' );

  // Social
  $settings->add_field( array(
      'type'        => 'heading',
      'id'          => 'social_heading',
      'description' => __( 'Social Networking', 'la' ),
      'tab'         => "{$key}_general",
  ) );
  $settings->add_field( array(
      'type'  => 'url',
      'id'    => 'facebook',
      'title' => __( 'Facebook URL', 'la' ),
      'tab'   => "{$key}_general",
  ) );
  $settings->add_field( array(
      'type'  => 'url',
      'id'    => 'twitter',
      'title' => __( 'Twitter URL', 'la' ),
      'tab'   => "{$key}_general",
  ) );
  $settings->add_field( array(
      'type'  => 'url',
      'id'    => 'instagram',
      'title' => __( 'Instagram URL', 'la' ),
      'tab'   => "{$key}_general",
  ) );
  $settings->add_field( array(
      'type'  => 'url',
      'id'    => 'yelp',
      'title' => __( 'Yelp URL', 'la' ),
      'tab'   => "{$key}_general",
  ) );
  $settings->add_field( array(
      'type'  => 'url',
      'id'    => 'tripadvisor',
      'title' => __( 'TripAdvisor URL', 'la' ),
      'tab'   => "{$key}_general",
  ) );
  // $settings->add_field( array(
  //     'type'  => 'url',
  //     'id'    => 'linkedin',
  //     'title' => __( 'LinkedIn URL', 'la' ),
  //     'tab'   => "{$key}_general",
  // ) );
  // $settings->add_field( array(
  //     'type'  => 'url',
  //     'id'    => 'pinterest',
  //     'title' => __( 'Pinterest URL', 'la' ),
  //     'tab'   => "{$key}_general",
  // ) );
  // $settings->add_field( array(
  //     'type'  => 'url',
  //     'id'    => 'flickr',
  //     'title' => __( 'Flickr URL', 'la' ),
  //     'tab'   => "{$key}_general",
  // ) );
  // $settings->add_field( array(
  //     'type'  => 'url',
  //     'id'    => 'youtube',
  //     'title' => __( 'Youtube URL', 'la' ),
  //     'tab'   => "{$key}_general",
  // ) );

  // Analytics
  $settings->add_field( array(
      'type'        => 'heading',
      'id'          => 'analytics_heading',
      'description' => __( 'Analytics Settings', 'la' ),
      'tab'         => "{$key}_general",
  ) );
  $settings->add_field( array(
      'type'  => 'text',
      'id'    => 'google_analytics',
      'title' => __( 'Google Analytics Code', 'la' ),
      'tab'   => "{$key}_general",
  ) );

  // Logo
  $settings->add_field( array(
      'type'        => 'heading',
      'id'          => 'logo_heading',
      'description' => __( 'Global Logo', 'la' ),
      'tab'         => "{$key}_general",
  ) );
  $settings->add_field( array(
      'type'  => 'text',
      'id'    => 'logo_id',
      'title' => __( 'Logo Image ID', 'la' ),
      'tab'   => "{$key}_general",
      'description' => '<br/>Upload the logo image to the <a href="'.get_bloginfo('url').'/wp-admin/media-new.php" target="_blank">Media Library</a>, then enter the ID number of the uploaded image here.'
  ) );

  // Favicon
  $settings->add_field( array(
      'type'        => 'heading',
      'id'          => 'favicon_heading',
      'description' => __( 'Favicon', 'la' ),
      'tab'         => "{$key}_general",
  ) );
  $settings->add_field( array(
      'type'  => 'text',
      'id'    => 'favicon_id',
      'title' => __( 'Favicon Image ID', 'la' ),
      'tab'   => "{$key}_general",
      'description' => '<br/>Upload the favicon image to the <a href="'.get_bloginfo('url').'/wp-admin/media-new.php" target="_blank">Media Library</a>, then enter the ID number of the uploaded image here.'
  ) );

     //Site Designation
  $settings->add_field( array(
      'type'        => 'heading',
      'id'          => 'site_designation',
      'description' => __( 'Site Designation for Pixel Script', 'la' ),
      'tab'         => "{$key}_general",
  ) );

  $settings->add_field( array(
      'type'  => 'select',
      'id'    => 'site',
      'title' => __( 'Select Site', 'la' ),
      'tab'   => "{$key}_general",
      'options' => array(
        array(
          'label' => 'None',
          'value' => 0,
          ),
        array(
          'label' => 'One Market',
          'value' => 1,
          ),
        )
  ) );

}

add_filter( 'init', 'la__do_settings' );



