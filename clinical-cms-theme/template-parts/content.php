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
        $titan = TitanFramework::getInstance( 'clinical-cms-theme' );
        $page_for_posts_ID = $titan->getOption( 'aa_sec_body_bg_clr' );
        $page_for_posts_obj = get_post( $page_for_posts_ID );
        $page_for_posts_obj = get_post( 68 );
        echo apply_filters( 'the_content', $page_for_posts_obj->post_content );
?>
</article><!-- #post-<?php the_ID(); ?> -->
