<?php
/**
 * Template Name: Page with VC formating
 * The template for displaying all pages without sidebar
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
<!--
<div class="wpb_column vc_column_container vc_col-sm-2">
    <div class="vc_column-inner">
        <div class="wpb_wrapper"></div>
    </div>
</div>
-->
<div class="wpb_column vc_column_container vc_col-sm-12">
    <div id="primary" class="content-area vc_column-inner">
        <div id="main" class="site-main wpb_wrapper">

                    <?php
                    while ( have_posts() ) : the_post();

                        get_template_part( 'template-parts/content', 'page' );

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;

                    endwhile; // End of the loop.
                    ?>

        </div>
    </div>
<!--
<div class="wpb_column vc_column_container vc_col-sm-2">
    <div class="vc_column-inner">
        <div class="wpb_wrapper"></div>
    </div>
</div>
-->
    <?php
    //get_sidebar();
    get_footer();
    ?>
</div>
<?php