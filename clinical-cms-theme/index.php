<?php
/**
 * Template Name: Blog Page with VC formating
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Clinical_CMS_Theme
 */

get_header(); ?>
<div class="wpb_column vc_column_container vc_col-sm-12">
	<div id="primary" class="content-area vc_column-inner">
		<main id="main" class="site-main wpb_wrapper">

<?php
        //get the post page layout & content data
        $page_for_posts_id = get_option( 'page_for_posts' );
        $page_for_posts_obj = get_post( $page_for_posts_id );
        echo apply_filters( 'the_content', $page_for_posts_obj->post_content );
 ?>   
          
            
		</main><!-- #main -->
	</div><!-- #primary -->

    <?php
    //get_sidebar();
    get_footer();
    ?>
</div>
<?php
