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
<?php do_shortcode('[vc_row full_width="stretch_row_content_no_spaces" parallax="content-moving-fade" parallax_image="224" css=".vc_custom_1499286787646{padding-top: 250px !important;padding-bottom: 250px !important;}"][vc_column][/vc_column][/vc_row]'); ?>
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
