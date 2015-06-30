<?php
/**
 * File:       search.php
 * Desc:       Search results template
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */

get_header(); ?>

<section id="search" class="container search-container content-width">
  <?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'search-post' ); ?>>
      <header class="post-header search-header">
        <?php if ( has_post_thumbnail() ) :
          $image_id = get_post_thumbnail_id();
          $retina   = wp_get_attachment_image_src( $image_id, 'featured-image-retina' );
          the_post_thumbnail( 'featured-image', array( 
            'class'       => 'post-image search-image',
            'data-retina' => $retina[0],
          ) );
        endif; ?>
        
        <h1 class="post-title search-title">
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h1><!-- / .post-title -->
      </header><!-- / .post-header -->
      
      <div class="post-content search-content">
        <?php the_excerpt(); ?>
      </div><!-- / .post-content -->
    </article><!-- / #post-<?php the_ID(); ?> -->

  <?php endwhile; endif; ?>
</section><!-- / #search -->

<?php get_footer(); ?>