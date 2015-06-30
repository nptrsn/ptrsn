<?php
/**
 * File:       single.php
 * Desc:       Single blog post template
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */

get_header(); ?>

<section id="single" class="container single-container content-width">
  <?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
      <header class="post-header single-header">
        <?php if ( has_post_thumbnail() ) :
          $image_id = get_post_thumbnail_id();
          $retina   = wp_get_attachment_image_src( $image_id, 'featured-image-retina' );
          the_post_thumbnail( 'featured-image', array( 
            'class'       => 'post-image single-image',
            'data-retina' => $retina[0],
          ) );
        endif; ?>
        
        <h1 class="post-title single-title">
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h1><!-- / .post-title -->
      </header><!-- / .post-header -->
      
      <div class="post-content single-content">
        <?php the_content(); ?>
      </div><!-- / .post-content -->
    </article><!-- / #post-<?php the_ID(); ?> -->

  <?php endwhile; endif; ?>
</section><!-- / #index -->


<section id="comments" class="container comments-container">
  <?php comments_template(); ?>
</section><!-- / .comments-container -->

<?php get_footer(); ?>