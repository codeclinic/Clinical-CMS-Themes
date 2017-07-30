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
        'desc' => esc_html__( 'Select the Sidebar type; Legacy Sidebars can be used in the widgets sections of WordPress and will ignore all content added here. Clinical Sidebars, <!-- can\'t be used for widgets but WILL show all content added in the content box qbove. These --> allow you to enter any content you want using Visual Composer.', 'clinical-cms-theme' ),
        'priority' => 'high',
        'context' => 'normal',
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
    $titan = TitanFramework::getInstance( 'clinical-cms-theme' );
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
        $postID = get_the_ID();
        $csbt = $titan->getOption( 'clinical_sidebar_type', $postID );
        
        if($csbt == 1){
            //if legacy sidebar/widget - register the sidebar
            register_sidebar( array(
                'name'          => esc_html( get_the_title() ),
                'id'            => esc_html( get_the_title() ), 
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

/**
 *  Function to display sidebars
 */
function clinical_cms_legacy_sidebar( $atts ){
    /*
    extract(shortcode_atts(array(
        'legacy_name' => '',
    ), $atts));
    */
    $atts = shortcode_atts(
    array(
        'legacy_name' => 'nrnrnrn',
        'display_axis' => 'vertical',
    ), $atts );
    
    $sidebar; 
    //if ( is_active_sidebar( $atts['legacy_name'] ) ){
        ob_start();
        dynamic_sidebar( $atts['legacy_name'] );
        $sidebar = ob_get_contents();
        ob_end_clean();
    //}
    $sidebar = '<aside id="secondary" class=' . $atts["display_axis"] . ' widget-area">' . $sidebar . '</aside><!-- #secondary -->';
    return $sidebar;
}
add_shortcode( 'Clinical_CMS_Legacy_Sidebar', 'clinical_cms_legacy_sidebar' );

/**
 *  Map WP Legacy Sidebars Content shortcode to Visual Composer
 */
add_action( 'vc_before_init', 'Clinical_CMS_Legacy_Sidebar_VisComp_Map' );
function Clinical_CMS_Legacy_Sidebar_VisComp_Map() {
    
    $arrSidebars = [];
    foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
        $arrSidebars[$sidebar['id']] = $sidebar['name'];
    }
    vc_map( array(
        "name" => __( "WP Legacy Sidebar", "clinical-cms-theme" ),
        "base" => "Clinical_CMS_Legacy_Sidebar",
        "class" => "Clinical_CMS_Legacy_Sidebar",
        "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
        "show_settings_on_create" => true,
        "params" => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "legacy-sidebar",
                "heading" => __( "Display Sidebar", "clinical-cms-theme" ),
                "param_name" => "legacy_name",
                "admin_label" => true,
                "value" => $arrSidebars,
                //'std'         => 'one', //default value
                "description" => __( "Select the WP Legacy sidebar to show.", "clinical-cms-theme" )
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "legacy-sidebar",
                "heading" => __( "Display Axis", "clinical-cms-theme" ),
                "param_name" => "display_axis",
                "admin_label" => true,
                "value" => array(
                    "Horizontal" => "Horizontal",
                    "Vertical" => "Vertical"
                    ),
                //'std'         => 'one', //default value
                "description" => __( "Select the WP Legacy sidebar to show.", "clinical-cms-theme" )
            )
        )
    ) );
}
/**
 *  Function to display clinical sidebars
 */
function clinical_cms_clinical_sidebar( $atts ){
    /*
    extract(shortcode_atts(array(
        'legacy_name' => '',
    ), $atts));
    */
    $atts = shortcode_atts(
    array(
        'vc_sidebar_name' => 'cddcsdcsd',
    ), $atts );
    
    $sidebar; 
    $post = get_page_by_title( $atts['vc_sidebar_name'], OBJECT, "sidebar_post" );
    
    $sidebar = apply_filters( 'the_content', $post->post_content );
    return $sidebar;
}
add_shortcode( 'Clinical_CMS_Clinical_Sidebar', 'clinical_cms_clinical_sidebar' );

/**
 *  Map Clinical Sidebars Content shortcode to Visual Composer
 */
function Clinical_CMS_Clinical_Sidebar_VisComp_Map() {
    
//Get the Titan Framework instance
    $titan = TitanFramework::getInstance( 'clinical-cms-theme' );
    //Build the query
    $query = new WP_Query(array(
        'post_type' => 'sidebar_post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));
    //get the posts and build Clinical VC sidebars
    $arrSidebarsMod = [];
    while ($query->have_posts()) {
        $query->the_post(); 
        //Get post id and check if this is legacy post/sidebar
        $postID = get_the_ID();
        $csbt = $titan->getOption( 'clinical_sidebar_type', $postID );
        
        if($csbt == 2){
            //if Clinical CMS sidebar
            $arrSidebarsMod[$postID] = get_the_title();
        }
    }
    
    //reset the query
    wp_reset_query();
    
    vc_map( array(
        "name" => __( "Clinical CMS Sidebar", "clinical-cms-theme" ),
        "base" => "Clinical_CMS_Clinical_Sidebar",
        "class" => "Clinical_CMS_Clinical_Sidebar",
        "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
        "show_settings_on_create" => true,
        "params" => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "clinical-sidebar",
                "heading" => __( "Display Sidebar", "clinical-cms-theme" ),
                "param_name" => "vc_sidebar_name",
                "admin_label" => true,
                "value" => $arrSidebarsMod ,
                //'std'         => 'one', //default value
                "description" => __( "Select the Clinical sidebar to show.", "clinical-cms-theme" )
            )
        )
    ) );
}
add_action( 'vc_before_init', 'Clinical_CMS_Clinical_Sidebar_VisComp_Map' );
?>