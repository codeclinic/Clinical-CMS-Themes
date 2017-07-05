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
<div class="vc_row wpb_row vc_row-fluid vc_row-has-fill vc_row-no-padding vc_general vc_parallax vc_parallax-content-moving-fade js-vc_parallax-o-fade">
    <div class="wpb_column vc_column_container vc_col-sm-12">
        <div class="vc_column-inner ">
            <div class="wpb_wrapper">
                <?php the_post_thumbnail( 'full' ); ?>
            </div>
        </div>
    </div>
</div>
<div class="vc_row-full-width vc_clearfix"></div>
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
