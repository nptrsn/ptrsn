<?php
/**
 * File:       header.php
 * Desc:       Main header for the theme
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */
$options = get_option( 'la__settings' );
$logo_id = isset( $options['logo_id'] ) ? $options['logo_id'] : false;
$favicon_id = isset( $options['favicon_id'] ) ? $options['favicon_id'] : false;
if ( $logo_id ) {
  $logo_src = wp_get_attachment_image_src( $logo_id, 'logo-size' );
}
if ( $favicon_id ) {
  $favicon_src = wp_get_attachment_image_src( $favicon_id, 'full' );
}
$page_for_posts = get_option( 'page_for_posts' );

$site = isset( $options['site'] ) ? $options['site'] : '';
if ( $site == 1 )
?>

<!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8 no-js" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

  <head>
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, minimal-ui" />
    <meta name="author" content="Lion Associates, http://lionassociates.com" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php if ( $favicon_id ) { ?>
      <link rel="icon" type="image/png" href="<?php echo $favicon_src[0]; ?>" />
    <?php } ?>

    <!-- WEB FONT -->
    <link href='http://fonts.googleapis.com/css?family=Quattrocento+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>

    <?php if ( $site == 1 ) { ?>
      <script type='text/javascript'>
      var axel = Math.random()+"";
      var a = axel * 10000000000000;
      document.write('<img class="pixel-display" src="http://pubads.g.doubleclick.net/activity;xsp=81082;ord=1;num='+ a +'?" width=1 height=1 border=0/>');
      </script>
      <noscript>
      <img class="pixel-display" src="http://pubads.g.doubleclick.net/activity;xsp=81082;ord=1;num=1?" width=1 height=1 border=0 />
      </noscript>
    <?php } ?>

    <?php if ( $site == 1 ) { ?>
      <script type='text/javascript'>
      var axel = Math.random()+"";
      var a = axel * 10000000000000;
      document.write('<img class="pixel-display" src="http://pubads.g.doubleclick.net/activity;xsp=80962;ord='+ a +'?" width=1 height=1 border=0/>');
      </script>
      <noscript>
      <img class="pixel-display" src="http://pubads.g.doubleclick.net/activity;xsp=80962;ord=1?" width=1 height=1 border=0/>
      </noscript>
    <?php } ?>

    <script type="text/javascript">
      document.getElementsByTagName( 'html' )[0].className = document.getElementsByTagName( 'html' )[0].className.replace( 'no-js', 'js' );
    </script>

    <!--[if lt IE 9]>
      <script src="<?php echo get_template_directory_uri(); ?>/javascripts/html5shiv.min.js"></script>
      <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/ie.css" />
    <![endif]-->

    <?php if ( $ga_code = la__get_option( 'google_analytics' ) ) : ?>
      <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?php echo $ga_code; ?>']);
        _gaq.push(['_trackPageview']);
        (function() {
          var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
    <?php endif; ?>

    <?php wp_head(); ?>
  </head>

<!-- <body <?php body_class(); ?>> -->
<!-- Line below allows us to target the inviduals domains of the multisite install -->
<?php $blog_id = get_current_blog_id(); ?>
<body <?php body_class( "site-$blog_id" ); ?>>
 
    <div id="mobile-nav">
      <ul>
      <?php $nav_args = array(
                  'theme_location' => 'main-menu',
                  'container'      => false,
                  'items_wrap'     => '%3$s',
                  'depth'           => 1,
                );
                wp_nav_menu( $nav_args );

            $nav_args2 = array(
                  'theme_location' => 'footer-menu',
                  'container'      => false,
                  'items_wrap'     => '%3$s',
                  'fallback_cb'     => false,
                  'depth'           => 1,
                );
                wp_nav_menu( $nav_args2 ); ?>
      </ul>
    </div><!-- / #mobile-nav -->

    <div id="master-wrap">

      <div id="fullscreen" class="cycle-slideshow" data-cycle-speed="1200" data-cycle-timeout="6000" data-cycle-slides="> .slide" data-cycle-prev="#arrow_left" data-cycle-next="#arrow_right" data-cycle-swipe=true> <!-- formerly #maximage -->
        <?php 
        
        $template_file = get_post_meta( $post->ID, '_wp_page_template', TRUE );
        $images = '';

        if ( 'page-GALLERY.php' === $template_file || 'page-PRIVATE-DINING.php' === $template_file || 'page-HOME.php' === $template_file ) {

          $gallery_page_meta = get_post_meta( $post->ID, 'gallery_page_metabox', true );
          $gallery_id = isset( $gallery_page_meta['gallery_id'] ) ? $gallery_page_meta['gallery_id'] : false;

          if ( $gallery_id ) {

            $gallery_meta = get_post_meta( $gallery_id, 'gallery_metabox', true );
            $count = isset( $gallery_meta['img_count'] ) ? $gallery_meta['img_count'] : false;
          
            if ( $count ) {

              for ( $i = 1; $i <= $count; $i++ ) {

                $img_id = isset( $gallery_meta["img{$i}"] ) ? $gallery_meta["img{$i}"] : false;
                $img_src = $img_id ? wp_get_attachment_image_src( $img_id, 'fullscreen' ) : '';
                
                if ( $img_id ) {

                  $images .= '<div class="slide" style="background-image:url('.$img_src[0].');background-repeat:no-repeat;background-position: center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div>';

                }

              }

            }

          }
          
        }

        if ( 'people' === get_post_type() ) {
          $pages = get_posts( array(
              'post_type' => 'page',
              'meta_key' => '_wp_page_template',
              'meta_value' => 'page-ABOUT.php',
              'numberposts' => 1,
          ) );

          foreach ( $pages as $page ) {
            $thumbnail = get_post_thumbnail_id( $page->ID );
            $img_src = wp_get_attachment_image_src( $thumbnail, 'full' );
            $images .= '<div class="slide" style="background-image:url('.$img_src[0].');background-repeat:no-repeat;background-position: center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div>';
          }

        }

        if ( has_post_thumbnail() ) {
          $thumbnail = get_post_thumbnail_id( $post->ID );
          $img_src = wp_get_attachment_image_src( $thumbnail, 'full' );
          $images .= '<div class="slide" style="background-image:url('.$img_src[0].');background-repeat:no-repeat;background-position: center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div>';

        }

        if ( is_home() ) {
          $thumbnail = get_post_thumbnail_id( $page_for_posts );
          $img_src = wp_get_attachment_image_src( $thumbnail, 'full' );
          $images = '<div class="slide" style="background-image:url('.$img_src[0].');background-repeat:no-repeat;background-position: center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"></div>';
        }

        //TODO: add a fallback in case the selectiion is empty or deleted
          echo $images;
        ?>

      </div><!-- / #fullscreen -->
    
      <header id="site-header" class="header">
        
        <div id="mobile-button">
          <i class="icon-align-justify"></i>
        </div><!-- #mobile-button -->

        <div class="logo">
            <a href="<?php echo home_url(); ?>" alt="<?php echo get_bloginfo('name'); ?>"><img src="<?php echo $logo_src[0]; ?>" /></a>
        </div>
        
        <nav id="main-menu" class="nav">
          <h1 class="is-hidden"><?php _e( 'Main Navigation', 'la' ); ?></h1>
          <ul class="menu">
            <?php $nav_args = array(
              'theme_location' => 'main-menu',
              'container'      => false,
              'items_wrap'     => '%3$s',
              'depth'           => 1,
            );
            wp_nav_menu( $nav_args ); ?>
          </ul><!-- / .menu -->
        </nav><!-- / .main-menu -->
      </header><!-- / #site-header -->