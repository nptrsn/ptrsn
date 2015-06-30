<?php
/**
 * Template Name: Menu Page for Walnut Creek
 */

get_header(); 

$meta = get_post_meta( $post->ID, 'menu_page_metabox', true );
$this_menu = isset( $meta['menu_name'] ) ? $meta['menu_name'] : '';
$pdf = isset( $meta['pdf'] ) ? $meta['pdf'] : false;
$pdf_src = $pdf ? wp_get_attachment_url( $pdf ) : false;
$pdf_link_text = isset( $meta['pdf_link_text'] ) ? $meta['pdf_link_text'] : false;
$link_text = $pdf_link_text ? $pdf_link_text : 'Click to download our '. $this_menu . ' menu.' ;
?>

<section id="page" class="container page-container content menu-page">

  <?php get_sidebar(); ?>

  <!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right">

      <?php if ( $pdf_src ) {
        echo '<a id="menu-pdf-link" href="'. $pdf_src .'">'. $link_text .'</a>';
      } ?>
      
      <!-- /\/\/\/\/\ SINGLE PLATFORM INTEGRATION /\/\/\/\/\ -->
<div id="menusContainer"></div>
<script type="text/javascript" src="https://menus.singleplatform.co/businesses/storefront/?apiKey=ke09z8icq4xu8uiiccighy1bw">
</script>
<script>
    var options = {};
    options['PrimaryBackgroundColor'] = 'transparent';
    options['MenuDescBackgroundColor'] = 'transparent';
    options['SectionTitleBackgroundColor'] = 'transparent';
    options['SectionDescBackgroundColor'] = 'transparent';
    options['ItemBackgroundColor'] = '#ffffff';
    options['PrimaryFontFamily'] = 'Roboto';
    options['BaseFontSize'] = '15px';
    options['FontCasing'] = 'Default';
    options['PrimaryFontColor'] = '#fffffff';
    options['MenuDescFontColor'] = '#fffffff';
    options['SectionTitleFontColor'] = '#ffffff';
    options['SectionDescFontColor'] = '#ffffff';
    options['ItemTitleFontColor'] = '#ffffff';
    options['FeedbackFontColor'] = '#ffffff';
    options['ItemDescFontColor'] = '#ffffff';
    options['ItemPriceFontColor'] = '#ffffff';
    options['HideDisplayOptionPhotos'] = 'true';
    options['HideDisplayOptionDollarSign'] = 'true';
    options['HideDisplayOptionDisclaimer'] = 'true';
    options['MenuTemplate'] = '2';
    //options['MenuDropDownBackgroundColor'] = '#f1f1f1';
    options['MenuIframe'] = 'false';
    new BusinessView("lark-creek-walnut-creek", "menusContainer", options);
</script>

    
    </div><!-- / .content-right -->
  </div><!-- / .container-right -->
  

</section><!-- / #page .content -->

<?php get_footer(); ?>