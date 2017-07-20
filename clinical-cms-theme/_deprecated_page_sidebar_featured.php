<?php
/**
 * Template Name: Page: sidebar & ft image
 * The template for displaying all pages with sidebar
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Clinical_CMS_Theme
 */

get_header(); ?>
<div data-vc-full-width="true" data-vc-full-width-init="true" data-vc-stretch-content="true" data-vc-parallax="1.5" data-vc-parallax-o-fade="on" data-vc-parallax-image="<?php the_post_thumbnail_url( 'full' ); ?>" class="vc_row wpb_row vc_row-fluid vc_row-has-fill vc_row-no-padding vc_general vc_parallax vc_parallax-content-moving-fade js-vc_parallax-o-fade" style="position: relative; left: -263.833px; box-sizing: border-box; width: 1583px; padding: 200px 0; height:35px;"><div class="wpb_column vc_column_container vc_col-sm-12 skrollable skrollable-before" data-5p-top-bottom="opacity:0;" data-30p-top-bottom="opacity:1;" style="opacity: 1;"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div><div class="vc_parallax-inner skrollable skrollable-between" style="height: 150%; background-image: url(&quot;<?php the_post_thumbnail_url( 'full' ); ?>&quot;); top: -43.7225%;" data-bottom-top="top: -50%;" data-top-bottom="top: 0%;"></div></div>
<div class="vc_row-full-width vc_clearfix margin-top-50"></div>
<div class="wpb_column vc_column_container vc_col-sm-2">
    <div class="vc_column-inner ">
        <div class="wpb_wrapper"></div>
    </div>
</div>
<div class="wpb_column vc_column_container vc_col-sm-8">
    <div class="vc_column-inner ">
        <div class="wpb_wrapper">
            <div class="vc_row wpb_row vc_row-fluid">
                <div class="wpb_column vc_column_container vc_col-sm-9">
                    <div class="vc_column-inner ">
                        <div class="wpb_wrapper">

                            <div id="primary" class="content-area">
                                <main id="main" class="site-main">

                                    <?php
                                    while ( have_posts() ) : the_post();

                                        get_template_part( 'template-parts/content', 'page' );

                                        // If comments are open or we have at least one comment, load up the comment template.
                                        if ( comments_open() || get_comments_number() ) :
                                            comments_template();
                                        endif;

                                    endwhile; // End of the loop.
                                    ?>

                                </main><!-- #main -->
                            </div><!-- #primary -->

                        </div>
                    </div>
                </div> 
                <div class="wpb_column vc_column_container vc_col-sm-3">
                    <div class="vc_column-inner ">
                        <div class="wpb_wrapper"> 
                            <?php
                            get_sidebar();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wpb_column vc_column_container vc_col-sm-2">
    <div class="vc_column-inner ">
        <div class="wpb_wrapper"></div>
    </div>
</div>
<?php
get_footer();
