<?php

/**
 * Create CPT Dynamic Sidebars
 */
function clinical_cms_theme_create_sidebar_post_type() {
    $labels = array( 
        'name' => __( 'Clinical Sidebars' ),
        'singular_name' => __( 'Clinical Sidebar' ),
        'add_new' => __( 'New Clinical Sidebar' ),
        'add_new_item' => __( 'Add New Clinical Sidebar' ),
        'edit_item' => __( 'Edit Clinical Sidebar' ),
        'new_item' => __( 'New Clinical Sidebar' ),
        'view_item' => __( 'View Clinical Sidebar' ),
        'search_items' => __( 'Search Sidebars' ),
        'not_found' =>  __( 'No Clinical Sidebars Found' ),
        'not_found_in_trash' => __( 'No Clinical Sidebars found in Trash' ),
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => false,
        'public' => true,
        'hierarchical' => false,
        'supports' => array(
            'title', 
            'editor', 
            /*'excerpt',*/ 
            /*'custom-fields',*/ 
            /*'thumbnail',*/ 
            /*'page-attributes'*/ 
        ),
    );
    register_post_type( 'sidebar_post', $args );
} 
add_action( 'init', 'clinical_cms_theme_create_sidebar_post_type' );

function clinical_cms_theme_sidebars() {
     
    $args = array(
        'post_type' => 'sidebar_post'
    );
     
    $query = new WP_query ( $args );
    if ( $query->have_posts() ) { ?>
 
        <?php while ( $query->have_posts() ) : $query->the_post(); /* start the loop */ ?>
      
        <aside id="post-<?php the_ID(); ?>" <?php post_class( 'sidebar-post' ); ?>>
            <h3 class="sidebar-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'compass' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
      
            <?php if ( has_post_thumbnail() ) { ?>
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail( 'medium', array(
                        'class' => 'aligncenter',
                        'alt'   => trim(strip_tags( $wp_postmeta->_wp_attachment_image_alt ))
                    ) ); ?>
                </a>
            <?php } ?>
             
            <section class="sidebar-content">
                <?php the_content(); ?>
            </section><!-- .entry-content -->
             
        </aside>
      
        <?php endwhile; /* end the loop*/ ?>
      
        <?php rewind_posts();
    }
}

function clinical_cms_theme_sidebar_metaboxes(){
    $titan = TitanFramework::getInstance( 'clinical' );
    $postMetaBox = $titan->createMetaBox( array(
        'name' => 'Sidebar Type Selector',
        'post_type' => 'sidebar_post',
        'desc' => esc_html__( 'Select the Sidebar type; Legacy Sidebars can be used in the widgets sections of WordPress and will ignore all content added here. Clinical Sidebars, can\'t be used for widgets but WILL show all content added in the content box opposite. These allow you to enter any content you want using Visual Composer.', 'clinical-cms-theme' ),
        'priority' => 'high',
        'context' => 'advanced',
    ) );
    $postMetaBox->createOption( array(
        'name' => 'Sidebar Type',
        'id' => 'clinical_sidebar_type',
        'options' => array(
        '1' => 'WP Legacy',
        '2' => 'Clinical [recommended]',
        ),
        'type' => 'radio',
        'desc' => esc_html__( 'Legacy = Widgets | Clinical = Visual Composer', 'clinical-cms-theme' ),
        'priority' => 'high',
        'default' => '2',
    ) );
}
add_action('after_setup_theme', 'clinical_cms_theme_sidebar_metaboxes');
?>