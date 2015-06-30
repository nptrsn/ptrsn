<?php
/**
 * Template Name: Menu
 */

get_header(); 

$meta = get_post_meta( $post->ID, 'menu_metabox', true );
$container = isset( $meta['container'] ) ? $meta['container'] : false;
?>

<section id="page" class="container page-container content menu">

  <?php get_sidebar(); ?>

  <!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right">
      <h3><?php the_title(); ?></h3>
      
      <!-- /\/\/\/\/\ SINGLE PLATFORM /\/\/\/\/\ -->
      <?php if ( $container ) {
        echo wp_kses_post($container);
      } ?>
      

    
    </div><!-- / .content-right -->
  </div><!-- / .container-right -->
  

</section><!-- / #page .content -->

<?php get_footer(); ?>