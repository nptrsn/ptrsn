<?php
/**
 * Template Name: Partners Page
 */
get_header();

$meta = get_post_meta( $post->ID, 'page_metabox', true );
$show_subnav = ( isset( $meta['show_subnav'] ) && $meta['show_subnav'] ) ? true : false;
?>

<section id="page" class="container page-container content partners">

  <?php get_sidebar(); ?>

  <!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right">
      <h3><?php the_title(); ?></h3>
      <?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

            <?php the_content(); ?>

        <?php endwhile; endif; ?>

        <?php $args = array(
          'post_type' => 'partners',
          'posts_per_page' => -1,
          );
        $partners = get_posts( $args );

        foreach ( $partners as $partner ) {
          $meta = get_post_meta( $partner->ID, 'partners_metabox', true );
          $logo_id = isset( $meta['logo'] ) ? $meta['logo'] : false;
          $logo_src = $logo_id ? wp_get_attachment_image_src( $logo_id, 'medium' ) : '';
          $link = isset( $meta['link'] ) ? $meta['link'] : false;
          $partner_link_open = $link ? '<a href="'.$link.'" target="_blank">' : '';
          $partner_link_close = $link ? '</a>' : '';

          echo '<div class="blogPost">';
          echo '<h3>'.$partner_link_open.$partner->post_title.$partner_link_close.'</h3>';
          if ( $logo_id ) {
            echo $partner_link_open;
            echo '<img class="partner-logo" src="'.$logo_src[0].'" />';
            echo $partner_link_close;
          }
          echo apply_filters( 'the_content', $partner->post_content );
          echo '</div>';


        } ?>

    </div><!-- / .content-right -->
  </div><!-- / .container-right -->


</section><!-- / #page .content -->

<?php get_footer(); ?>
