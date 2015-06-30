<?php
/**
 * File:       index.php
 * Desc:       Generic index template
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */

get_header(); 
$leftright = 'left';
?>

<section id="index" class="container page-container content">

  <?php get_sidebar(); ?>

  <!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right">
      <h3><?php echo get_the_title( get_option( 'page_for_posts' ) ); ?></h3>

  <?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blogPost' ); ?>>
      <header class="post-header index-header">
        <h1 class="post-title index-title"><?php the_title(); ?></h1><!-- / .post-title -->
        <?php if ( has_post_thumbnail() ) :
          $image_id = get_post_thumbnail_id();
          $retina   = wp_get_attachment_image_src( $image_id, 'medium' );
          the_post_thumbnail( 'medium', array( 
            'class'       => 'post-image '.$leftright.' index-image',
            'data-retina' => $retina[0],
          ) );
        endif; ?>
      </header><!-- / .post-header -->
      
      <div class="post-content index-content">
        <?php the_content(); ?>
      </div><!-- / .post-content -->
    </article><!-- / #post-<?php the_ID(); ?> -->

    <?php if ( $leftright == 'left' ) {  
      $leftright = 'right';
    } else {
      $leftright = 'left';
    }
     ?>

  <?php endwhile; endif; ?>

  <?php next_posts_link( '<span class="meta-nav">&larr;</span> Older posts' ); ?>
  <?php previous_posts_link( 'Newer posts <span class="meta-nav">&rarr;</span>' ); ?>

  </div><!-- / .content-right -->
  </div><!-- / .container-right -->

</section><!-- / #index -->

<?php get_footer(); ?>