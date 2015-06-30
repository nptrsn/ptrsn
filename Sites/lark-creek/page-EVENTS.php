<?php
/**
 * Template Name: Events Page
 */

get_header(); 

?>

<section id="page" class="container page-container content events">

  <!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right">
      <h3><?php the_title(); ?></h3>
      <?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

            <?php the_content(); ?>

      <?php endwhile; endif; ?>

      <?php $args = array(
          'post_type' => 'lc_events',
          'posts_per_page' => -1,
          );
        $events = get_posts( $args ); 

        foreach ( $events as $event ) {
          $meta = get_post_meta( $event->ID, 'events_metabox', true );
          $align = isset( $meta['img_align'] ) ? $meta['img_align'] : 'left';
          if ( $align == 'left' ) {
            $margin = 'margin-right:20px;';
          }
          if ( $align == 'right' ) {
            $margin = 'margin-left:20px;';
          }

          echo '<div class="blogPost">';
          if ( has_post_thumbnail( $event->ID ) ) {
            echo '<div class="event-thumb" style="float:'.$align.';'.$margin.'">';
            echo get_the_post_thumbnail( $event->ID, 'medium' );
            echo '</div>';
          }
          echo '<h3>'.$event->post_title.'</h3>';
          echo apply_filters( 'the_content', $event->post_content );
          echo '</div>';


        } ?>
    </div><!-- / .content-right -->
  </div><!-- / .container-right -->
  

</section><!-- / #page .content -->

<?php get_footer(); ?>