<?php 
$show_subnav = false;
$page_for_posts = get_option( 'page_for_posts' );
$home_promo = true;

if ( 'people' === get_post_type() ) {
    $title = 'About';
    $show_subnav = true;
}
if ( 'page' === get_post_type() ) {
    $title = get_the_title($post->ID);
    $template_file = get_post_meta( $post->ID, '_wp_page_template', TRUE );
    if ( 'page-HOME.php' === $template_file ) {
        $promo_args = array(
          'post_type' => 'promotions',
          'posts_per_page' => -1,
          );
        $promos = get_posts( $promo_args ); 
        if ( !$promos ) $home_promo = false;
        $show_subnav = true;
    } elseif ( 'page-MENUS.php' === $template_file ) {
       $show_subnav = true;
    } else {
        if ( $post->post_parent ) {
            $title = get_the_title($post->post_parent);
        }
        $meta = get_post_meta( $post->ID, 'page_metabox', true );
        $show_subnav = ( isset( $meta['show_subnav'] ) && $meta['show_subnav'] ) ? true : false;
    }
}
if ( is_home() ) {
    $show_subnav = true;
} 
?>

<!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER LEFT /\/\/\/\/\/\/\/\/\/\ -->
<?php if ( $show_subnav && $home_promo ) { ?>

<div class="container-left">
    <div class="content-left" >

        <div class="subNav">
        
            <?php if ( 'page-HOME.php' === $template_file ) {
               
                foreach ( $promos as $promo ) {
                    $meta = get_post_meta( $promo->ID, 'promotions_metabox', true );
                    $show_on_home = ( isset( $meta['show_on_home'] ) && $meta['show_on_home'] ) ? true : false;
                    $link_text = isset( $meta['link_text'] ) ? $meta['link_text'] : false;
                    $link_url = isset( $meta['link_url'] ) ? $meta['link_url'] : false;

                    if ( $show_on_home ) {
                        echo '<h1>'.$promo->post_title.'</h1>';
                        echo apply_filters( 'the_content', $promo->post_content );
                        if ( $link_url && $link_text ) {
                            echo '<a class="promo-link" href="'.$link_url.'">'.$link_text.'</a>';
                        }
                    }
                }

            } elseif ( 'page-MENUS.php' === $template_file ) {

                echo '<h4>Download Menus</h4>';
                the_content();

            } elseif ( 'page-ABOUT.php' === $template_file ) {

                echo '<h4>'.$title.'</h4>';

                $children = wp_list_pages( "title_li=&child_of=".$post->ID."&echo=0" );
                if ( $children ) echo '<ul>'.$children.'</ul>';

                $people_args = array(
                    'post_type' => 'people',
                    'numberposts' => -1,
                    'orberby' => 'menu_order',
                    'order' => 'ASC',
                    );
                $people = get_posts( $people_args );

                if ( $people ) {

                    echo '<ul>';

                        foreach( $people as $person ) {

                            $meta = get_post_meta( $person->ID, 'people_metabox', true );
                            $person_title = isset( $meta['title'] ) ? $meta['title'] : false;
                            echo '<li><a href="'.get_permalink( $person->ID ).'">';
                            echo $person_title ? '<span>'.$person_title.'</span><br/>' : '';
                            echo $person->post_title;
                            echo '</a>';
                            echo '</li>';

                        }

                    echo '</ul>';

                }

            } elseif ( is_home() ) {

                $meta = get_post_meta( $page_for_posts, 'posts_page_metabox', true );
                $sidebar_content = isset( $meta['sidebar_content'] ) ? $meta['sidebar_content'] : '';
                echo apply_filters( 'the_content', $sidebar_content );

            } else {

                echo '<h4>'.$title.'</h4>';

                if ( 'page' == get_post_type($post->ID) && 'page-HOME.php' !== $template_file ) {

                    if ( $post->post_parent ) {

                        $children = wp_list_pages( "title_li=&child_of=".$post->post_parent."&echo=0" );

                    } else {

                        $children = wp_list_pages( "title_li=&child_of=".$post->ID."&echo=0" );

                    }

                    if ( $children ) { 

                        echo '<ul>'.$children.'</ul>';

                    }

                }

                if ( 'people' === get_post_type() ) {
                    
                    $pages = get_posts( array(
                        'post_type' => 'page',
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'page-ABOUT.php',
                        'numberposts' => 1,
                        ) );

                    foreach ( $pages as $page ) {
                        $about_id = $page->ID ;                       
                    }

                    $children = wp_list_pages( "title_li=&child_of=".$about_id."&echo=0" );
                    if ( $children ) echo '<ul>'.$children.'</ul>';

                    $people_args = array(
                        'post_type' => 'people',
                        'numberposts' => -1,
                        'orberby' => 'menu_order',
                        'order' => 'ASC',
                        );
                    $people = get_posts( $people_args );
                    if ( $people ) {
                        echo '<ul>';
                            foreach( $people as $person ) {
                                $meta = get_post_meta( $person->ID, 'people_metabox', true );
                                $person_title = isset( $meta['title'] ) ? $meta['title'] : false;
                                echo '<li><a href="'.get_permalink( $person->ID ).'">';
                                echo $person_title ? '<span>'.$person_title.'</span><br/>' : '';
                                echo $person->post_title;
                                echo '</a>';
                                echo '</li>';
                            }
                        echo '</ul>';

                    }

                }

            } ?>

        </div><!-- / .subNav -->

    </div><!-- / .content-left -->
</div><!-- / .container-left -->

<?php } ?>