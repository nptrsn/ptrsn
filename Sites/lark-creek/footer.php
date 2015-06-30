<?php
/**
 * File:       footer.php
 * Desc:       Main footer for the theme
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */
$options = get_option( 'la__settings' );
$facebook = isset( $options['facebook'] ) ? $options['facebook'] : '';
$twitter = isset( $options['twitter'] ) ? $options['twitter'] : '';
$instagram = isset( $options['instagram'] ) ? $options['instagram'] : '';
$yelp = isset( $options['yelp'] ) ? $options['yelp'] : '';
$tripadvisor = isset( $options['tripadvisor'] ) ? $options['tripadvisor'] : '';
?>
      <!-- The line below adds specific classes for each of the multisite domains, so we can target them individually when needed -->
      <footer id="site-footer" class="footer">
        <nav id="footer-menu" class="nav">
          <h1 class="is-hidden"><?php _e( 'Site Map', 'la' ); ?></h1>
          <ul class="menu">
            <?php $nav_args = array(
              'theme_location'  => 'footer-menu',
              'container'       => false,
              'items_wrap'      => '%3$s',
              'fallback_cb'     => false,
              'depth'           => 1,
            );
            wp_nav_menu( $nav_args ); ?>
          </ul><!-- / #footer-menu -->

          <div class="social">
            <?php if ($facebook) { ?><a href="<?php echo $facebook; ?>" target="_blank"><i class="icon-facebook"></i></a><?php } ?>
            <?php if ($twitter) { ?><a href="<?php echo $twitter; ?>" target="_blank"><i class="icon-twitter"></i></a><?php } ?>
            <?php if ($instagram) { ?><a href="<?php echo $instagram; ?>" target="_blank"><i class="icon-instagram"></i></a><?php } ?>
            <?php if ($yelp) { ?><a href="<?php echo $yelp; ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/yelp-logo-white.png" class="yelp"></a><?php } ?>
            <?php if ($tripadvisor) { ?><a href="<?php echo $tripadvisor; ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/tripadvisor-logo-white.png" class="tripadvisor"></a><?php } ?>
          </div>
        </nav><!-- / .footer-menu -->

        <!-- <p id="site-info">&copy; </p> --> 

      </footer><!-- / #site-footer -->

    </div><!-- / #master-wrap -->

    
    <?php wp_footer(); ?>
    
    <?php $template_file = get_post_meta( $post->ID, '_wp_page_template', TRUE );

    if ( 'page-MENUS.php' === $template_file ) { 
      $meta = get_post_meta( $post->ID, 'menu_metabox', true );
      $script = isset( $meta['script'] ) ? $meta['script'] : false;
      if ( $script ) echo $script;
    ?>
    
    <?php } ?>

  </body>
</html>
