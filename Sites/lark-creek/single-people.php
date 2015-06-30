<?php

get_header(); 

$meta = get_post_meta( $post->ID, 'people_metabox', true );
$first_name = isset( $meta['first_name'] ) ? $meta['first_name'] : '';
$last_name = isset( $meta['last_name'] ) ? $meta['last_name'] : '';
$title = isset( $meta['title'] ) ? $meta['title'] : '';
$email = isset( $meta['email'] ) ? $meta['email'] : '';
$phone = isset( $meta['phone'] ) ? $meta['phone'] : '';
$linked = isset( $meta['linked'] ) ? $meta['linked'] : '';
$headshot = isset( $meta['headshot'] ) ? $meta['headshot'] : false;
$headshot_src = $headshot ? wp_get_attachment_image_src( $headshot, 'medium' ) : '';
?>

<section id="page" class="container page-container content people">
  
  <?php get_sidebar(); ?>
    
  <!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right">
      <h3><?php the_title(); ?></h3>
      <?php if ( $title ) echo '<h4>'.$title.'</h4>'; ?>

      <?php if ( $headshot ) {
        echo '<img class="headshot" src="'.$headshot_src[0].'" />';
      } ?>

      <?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

            <?php the_content(); ?>

        <?php endwhile; endif; ?>
    </div><!-- / .content-right -->
  </div><!-- / .container-right -->
  

</section><!-- / #page .content -->

<?php get_footer(); ?>