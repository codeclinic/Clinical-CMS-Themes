<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Clinical_CMS_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php  
        $page_for_posts_obj = get_post( 68 );
        echo apply_filters( 'the_content', $page_for_posts_obj->post_content );
?>

</article><!-- #post-<?php the_ID(); ?> -->
