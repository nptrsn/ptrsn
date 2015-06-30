<?php
/**
 * Template Name: Locu Menu
 */

get_header(); 

$meta = get_post_meta( $post->ID, 'page_metabox', true );
$show_subnav = ( isset( $meta['show_subnav'] ) && $meta['show_subnav'] ) ? true : false;
?>

<section id="page" class="container page-container content">
  
  <?php get_sidebar(); ?>


  <!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right">
      <h3><?php the_title(); ?></h3>
      <?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

            <?php the_content(); ?>

        <?php endwhile; else: ?>

          <p>Sorry, page not found.</p>

      <?php endif; ?>
    </div><!-- / .content-right -->
  </div><!-- / .container-right -->
  

</section><!-- / #page .content -->

<?php get_footer(); ?>