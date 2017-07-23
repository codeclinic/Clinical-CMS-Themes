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

/**
 * Create Sidebar Type Selector
 */
function clinical_cms_theme_sidebar_metaboxes(){
    $titan = TitanFramework::getInstance( 'clinical-cms-theme' );
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

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function clinical_cms_theme_widgets_init() {
    //Get the Titan Framework instance
    $titan = TitanFramework::getInstance( 'clinical' );
    //Build the query
    $query = new WP_Query(array(
        'post_type' => 'sidebar_post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));
    //get the posts and build legacy sidebars
    while ($query->have_posts()) {
        $query->the_post();
        //Get post id and check if this is legacy post/sidebar
        $titan = TitanFramework::getInstance( 'clinical-cms-theme' );
        $csbt = $titan->getOption( 'clinical_sidebar_type', get_the_ID() );
        
        if($csbt == 1){
            //if legacy sidebar/widget - register the sidebar
            register_sidebar( array(
                'name'          => esc_html( get_the_title() ),
                'id'            => get_the_title(),
                'description'   => __( 'Add widgets here.', 'clinical-cms-theme' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );
        }
    }
    //reset the query
    wp_reset_query();
}
add_action( 'widgets_init', 'clinical_cms_theme_widgets_init' );
?>