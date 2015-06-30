<?php
/**
 * File:       archive.php
 * Desc:       Generic archive template
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */

get_header(); ?>

<section id="archive" class="container archive-container content-width">
  <?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-post' ); ?>>
      <header class="post-header archive-header">
        <?php if ( has_post_thumbnail() ) :
          $image_id = get_post_thumbnail_id();
          $retina   = wp_get_attachment_image_src( $image_id, 'featured-image-retina' );
          the_post_thumbnail( 'featured-image', array( 
            'class'       => 'post-image archive-image',
            'data-retina' => $retina[0],
          ) );
        endif; ?>
        
        <h1 class="post-title archive-title">
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h1><!-- / .post-title -->
      </header><!-- / .post-header -->
      
      <div class="post-content archive-title">
        <?php the_excerpt(); ?>
      </div><!-- / .post-content -->
    </article><!-- / #post-<?php the_ID(); ?> -->

  <?php endwhile; endif; ?>
</section><!-- / #archive -->

<?php get_footer(); ?>