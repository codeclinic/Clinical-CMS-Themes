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
<!--
	<header class="entry-header">
		< ? php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			< ? php clinical_cms_theme_posted_on(); ?>
		</div><!-- .entry-meta -- >
		< ? php
		endif; 
        if ( has_post_thumbnail( $post->ID ) ) {
            echo '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( $post->post_title ) . '">';
            echo get_the_post_thumbnail( $post->ID, 'large' );
            echo '</a>';
        }
        ? >
	</header><!-- .entry-header -- >
-->
<?php  
        echo '[vc_row full_width="stretch_row_content_no_spaces" parallax="content-moving-fade" css=".vc_custom_1499539750144{padding-top: 200px !important;padding-bottom: 200px !important;}"][vc_column][/vc_column][/vc_row][vc_row][vc_column width="1/6"][/vc_column][vc_column width="2/3"][vc_row_inner][vc_column_inner width="5/6"][Clinical_CMS_Theme_Blog_Block][Clinical_CMS_Theme_Blog_Header_Open][Clinical_CMS_Theme_Blog_Header_Close][/Clinical_CMS_Theme_Blog_Block][/vc_column_inner][vc_column_inner width="1/6"][vc_column_text]This would bethe sidebar zone[/vc_column_text][Clinical_CMS_Blog_Nav][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/6"][/vc_column][/vc_row][vc_row][vc_column][/vc_column][/vc_row]';
    
        //$page_for_posts_obj = get_post( 68 );
        //echo /* apply_filters( 'the_content',*/ $page_for_posts_obj->post_content /*)*/;
 ?>
	<div class="entry-content">
		<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'clinical-cms-theme' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'clinical-cms-theme' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php clinical_cms_theme_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
