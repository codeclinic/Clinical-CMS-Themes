<?php
/**
 *  Custom function to get template part as variable
 */
function load_template_part($template_name, $part_name=null, $blogLayout=null) {
    ob_start();
    switch($blogLayout){
        case "Layout 1":
            get_template_part($template_name, "layout_1");
            break;
        case "Layout 2":
            get_template_part($template_name, "layout_2");
            break;
        case "Layout 3":
            get_template_part($template_name, "layout_3");
            break;
        case "Layout 4":
            //get_template_part($template_name, "layout_4");
            //break;
        default:
            get_template_part($template_name, $part_name);
            break;
    }
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

/**
 * Create A Customizer Element To Identify Blog Template
 */
add_action( 'after_setup_theme', 'clinical_cms_theme_setup_customiser' );
function clinical_cms_theme_setup_customiser() {
    $titan = TitanFramework::getInstance( 'clinical-cms-theme' );
    $pages = $titan->createThemeCustomizerSection( array(
        'id' => 'static_front_page',
    ) ); 
    // Create options that will appear 
    $pages->createOption( array(
        'name' => 'Single Post Template',
        'id' => 'clinical_blog_layout',
        'type' => 'select-pages',
    ) ); 
}

/**
 *  Re-enable the content editor on blog overview page
 */
function clinical_cms_theme_editor_on_posts_page($post) {
    if($post->ID != get_option('page_for_posts') || post_type_supports('page', 'editor'))
            return;

    remove_action('edit_form_after_title', '_wp_posts_page_notice');
    add_post_type_support('page', 'editor');
}
add_action('edit_form_after_title', 'clinical_cms_theme_editor_on_posts_page', 0);

/**
 *  Function to display blog posts on page_for_posts
 */
function clinical_cms_theme_content_page_for_posts( $atts ){
       extract(shortcode_atts(array(
          'template' => 'Layout 1',
       ), $atts));
    
    $output;
    if ( have_posts() ) :
        if ( is_home() && ! is_front_page() ) : 
            $output = '<header><h1 class="page-title screen-reader-text">' . single_post_title('', false) . '</h1></header>';
        endif;
        /* Start the Loop */
        while ( have_posts() ) : the_post();
            /*
             * Include the Post-Format-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
             */
            $output .= load_template_part( 'template-parts/content', get_post_format(), $template );
        endwhile;
        //$output .= the_posts_navigation();
        //$output .= get_the_posts_navigation();
    else :
        $output .= load_template_part( 'template-parts/content', 'none' );
    endif;   
    return $output;
}
add_shortcode( 'Clinical_CMS_Blog_Content', 'clinical_cms_theme_content_page_for_posts' );

/**
 *  Map Blog Content shortcode to Visual Composer
 */
add_action( 'vc_before_init', 'Clinical_CMS_Blog_Content_VisComp_Map' );
function Clinical_CMS_Blog_Content_VisComp_Map() {
   vc_map( array(
      "name" => __( "Clinical CMS Blog Content", "clinical-cms-theme" ),
      "base" => "Clinical_CMS_Blog_Content",
      "class" => "blog-body",
      "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
      "show_settings_on_create" => true,
      "params" => array(
         array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "blog-content",
            "heading" => __( "Template", "clinical-cms-theme" ),
            "param_name" => "template",
            "admin_label" => true,
            "value" => array(
                'one'   => __( 'Layout 1', "clinical-cms-theme" ),
                'two'   => __( 'Layout 2', "clinical-cms-theme" ),
                'three' => __( 'Layout 3', "clinical-cms-theme" ),
                //'four'  => __( 'Layout 4', "clinical-cms-theme" ),
            ),
            'std'         => 'one', //default value
            "description" => __( "Select the blog stream layout template.", "clinical-cms-theme" )
         )
      )
   ) );
}
/**
 *  Function to display blog navigation / paging on page_for_posts
 */
function clinical_cms_theme_nav_page_for_posts(){
    $output = "";
    if ( have_posts() ){
        $output .= get_the_posts_navigation();
    }
    
    return $output;
}
add_shortcode( 'Clinical_CMS_Blog_Nav', 'clinical_cms_theme_nav_page_for_posts' );

/**
 *  Map Blog Navigation shortcode to Visual Composer
 */
add_action( 'vc_before_init', 'Clinical_CMS_Blog_Nav_VisComp_Map' );
function Clinical_CMS_Blog_Nav_VisComp_Map() {
   vc_map( array(
      "name" => __( "Clinical CMS Blog Navigation", "clinical-cms-theme" ),
      "base" => "Clinical_CMS_Blog_Nav",
      "class" => "blog-nav",
      "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
      "show_settings_on_create" => false,
   ) );
}


























if(!function_exists('clinical_cms_theme_blog_block')){
    function clinical_cms_theme_blog_block( $atts, $content = null ) {
       return '<article id="post-' . get_the_ID() . '" class="' . join( ' ', get_post_class() ) . '" >' . do_shortcode($content) . '</article><!-- #post-' . get_the_ID() . ' -->';
    }
    add_shortcode('Clinical_CMS_Theme_Blog_Block', 'clinical_cms_theme_blog_block');
}

if(!function_exists('clinical_cms_theme_blog_header')) {
	function clinical_cms_theme_blog_header( $atts, $content =  null) {
        return '<header class="entry-header">' . do_shortcode($content) . '</header>';
	}
	add_shortcode('Clinical_CMS_Theme_Blog_Header', 'single_carousel_content');		
}





// Mapping 
vc_map( array(
    "name" => __("Clinical CMS Blog Block", "clinical-cms-theme"),
    "base" => "Clinical_CMS_Theme_Blog_Block",
    "as_parent" => array('only' => 'Clinical_CMS_Theme_Blog_Header'),
    "content_element" => true,
    "show_settings_on_create" => false,
    "is_container" => true,
    "js_view" => 'VcColumnView',
      "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
) );

vc_map( array(
    "name" => __("Clinical CMS Blog Header", "mozel"),
    "base" => "Clinical_CMS_Theme_Blog_Header",
    "as_child" => array('only' => 'Clinical_CMS_Theme_Blog_Block'),
    "content_element" => true,
    "show_settings_on_create" => false,
    "is_container" => true,
    "js_view" => 'VcColumnView',
      "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
) );

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Block extends WPBakeryShortCodesContainer {
    }
    //class WPBakeryShortCode_Clinical_Cms_Theme_Blog_Header extends WPBakeryShortCodesContainer {
    //}
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Header extends WPBakeryShortCode {
    }
}

?>