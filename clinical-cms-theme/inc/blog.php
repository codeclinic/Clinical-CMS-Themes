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
 * Create A MetaBox To Identify Blog Template
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


























if(!function_exists('carousel_content')){
    function carousel_content( $atts, $content = null ) {
       return '<div class="owl-carousel content-carousel content-slider">'.do_shortcode($content).'</div>';
    }
    add_shortcode('carousel_content', 'carousel_content');
}

if(!function_exists('single_carousel_content')) {
	function single_carousel_content( $atts, $content =  null) {
		extract(shortcode_atts(array(
			'title' => 'Flexible & Customizable',
			'description' => '',
			'url' => '',
			'img' => ''
		), $atts));
        
        $url = ($url=='||') ? '' : $url;
		$url = ps_build_link( $url );
		$a_link = $url['url'];
		$a_title = ($url['title'] == '') ? '' : 'title="'.$url['title'].'"';
		$a_target = ($url['target'] == '') ? '' : 'target="'.$url['target'].'"';
        $button = $a_link ? '<a class="btn btn-md btn-black" href="'.$a_link. '" '.$a_title.' '.$a_target.'>'.$url['title'].'</a>' : '';

        $image = wp_get_attachment_image_src( $img, 'full');
		$image_src = $image['0'];
        
        
        $output = '<div class="item">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 mb-sm-30">
                                <img src="'.$image_src.'" alt="" />
                            </div>
                            <div class="col-md-5 col-md-offset-1">
                                <h3>'.$title.'</h3>
                                <div class="spacer-15"></div>
                                '.$description.'
                                <div class="spacer-15"></div>
                                '.$button.'
                            </div>
                        </div>
                    </div>
                </div>'; 
        
        return $output;
	}
	add_shortcode('single_carousel_content', 'single_carousel_content');		
}




// Mapping 
vc_map( array(
    "name" => __("Carousel Content", "mozel"),
    "base" => "carousel_content",
    "as_parent" => array('only' => 'single_carousel_content'),
    "content_element" => true,
    "show_settings_on_create" => false,
    "is_container" => true,
    "js_view" => 'VcColumnView',
    "category" =>array('Mozel', 'Content')
) );

vc_map( array(
    "name" => __("Single Carousel Content", "mozel"),
    "base" => "single_carousel_content",
    "content_element" => true,
    "as_child" => array('only' => 'carousel_content'),
    "show_settings_on_create" => true,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", "mozel"),
            "param_name" => "title"
        ), 
        array(
            "type" => "textarea",
            "heading" => __("Description", "mozel"),
            "param_name" => "description"
        ),
        array(
			'type' => 'vc_link',
			'heading' => __( 'Button', 'mozel' ),
			'param_name' => 'url',
		),
        array(
			'type' => 'attach_image',
			'heading' => __( 'Add Image', 'mozel' ),
			'param_name' => 'img',
		)
    ),
    ) );

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_Carousel_Content extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_Single_Carousel_Content extends WPBakeryShortCode {
    }
}
?>