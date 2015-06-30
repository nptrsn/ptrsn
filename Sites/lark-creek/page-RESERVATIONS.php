<?php
/**
 * Template Name: Reservation Page
 */

get_header(); 

$meta = get_post_meta( $post->ID, 'reservations_metabox', true );
$open_table_code = isset( $meta['open_table_code'] )  ? $meta['open_table_code'] : false;
?>

<section id="page" class="container page-container content">

	<!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right">

    	<h3><?php the_title(); ?></h3>
  
  <?php if ( $open_table_code ) {
    echo $open_table_code;

  } ?>

  <?php the_content(); ?>
  
  </div><!-- / .content-right -->
  </div><!-- / .container-right -->
  

</section><!-- / #page .content -->

<?php get_footer(); ?>