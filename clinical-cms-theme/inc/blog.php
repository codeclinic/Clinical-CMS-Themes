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
    "icon" => "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAE9ElEQVRYhcWXb2hVZRjAf+977rn37i4359rFocM5myixHKktWxLUIJWkJKgQJJXKIirrgxJhmBAoFFZfpD8QiEUhFKJ9iFAqK6eIf6aSto1ay5mbOrfp7s6957xPH87dubu72+51CD1wOIdznj+/933e93mfo27sqk4AIf4fcUPp4PkB7GJ0vB7Ew3Q1++/Cd6DL70bZsUBN3CHM5RPgOQURFDRyXVFHdMXnqOg0nJ/exHQ1Y9dvwF74albwYTH9nQwdWIP0d+T3XQhAuHErqqgck+jBvbAXq7oJu2HzmMEBdEkV4YZNhbguZAYUuuwuAKSvA8Qgg1dJ/vxWrmYsjr3wFZS20NPm3i4AQCn/blz/1n0S031ybIdzn0CVVmds8khBKbglEbkl9UkD6MoGoiu/JLryC1RZYdNdIIDKvpROPw9/trBqHye67BOsGUuwZjxA9LHdWLMeBm2P7ye4RkW7sas6BYR0RR3hxq3ostpRegrsYpRSeBePkGzeTtGT+wAQEdSIXIvrgGWjlEbEQOpmdjQRTM8ZnMNbkL4/IV2IwC7293lRef45SwcU45E69h4qFidUtxalFCoUGaGmITwlx9ya2Uh0+ackvn4UxPN3gY7XB8HFTWC6W0BMYKQrF6O0zyqJqySPf4Tbth+53gaA2/otVs0KdFntuLXBjx5GT61BT52DKp2NXG/zAZQVDnRSxz8kderjEUYRYutb/ODJAWTgH1LHd2b5NT1nMD1n/JGXzkZFSkBM8C5LtI294LkgzTl1QJID2dCzlwWA5ur58UeXFrt+A/b8pxERhvavzpwbgDVzKWJcUid3ZXgmcqYr7yOydJsPZjzctv15AdzWfcHijDyyE10+P+Nv+iKiK/dg1a7KBhg96mEJL9qIipQgIqSOvR/kfCIxXUdwz+72nRdPx17wfNZ3pTTh+zent3cawFw5hzh9Y7iTtJFCxSryBg+CjNAVciujjsVRU6oyALgJkr+96+/dEeL88g7m5mUAQnVr0RV1eYNbs5qwapYDYHrbSR17fwxCFeyWYA24F/Yy9M0qvH9PZOh7//BPvXROhx1PJKE5K1BKISI4B19DbnRNrA9gVT2EjteTavksp3p5HYcY3LPEB0oN5gVQZbW+7s1LmCvnsr+NkcYQgHhJwos3Yt/7EnKzOzh2c0WQ5A3M1d9xW/dhuo4A/rSH5qzA+XUbyi72gxXdSdEzhzKm2gryLiKIO5QBMJdPYPo70SVVqJKqvKO04vcQmvcU7tndqFgFVs1ylFIkj+7IjNYKo6bOHtPe9JxB+v7KAOA5DB1YQ7hhk9/JZDUTClVa7R8wqQSS7EfF4iilsOuezcyNSFYvIF4SGegcFdng9bSQat7B8A4LKqH0d+D88PIYvIrY+tMQnoLpPsXQ/tXoygYiTR+gi6f7fnvbcQ5uRAa7MwADnSS+ahp3FodlUv8D5tJRnO/WYde/gJAuUnlW+20FADDXzuMcemOy5oHcWkumb/8PVGEe04tLx+sJN76NOLlnh/f3j36nPHy0G5OjM0kAwfS0YM18EGXZ2HXrcjW8JG77AYhMRcXiPlBPS0EABaXAObwFc709N7AIpq8D5/sXkd5WwotfBx3C6z5Nqnl7QQBBU5pf0/K7HSvT+fod0kWCU3PaPPCcdJEp6P/ADQHj1d1sEQ+53jahW7mWv2MaDfAfS6vje9Yi8HUAAAAASUVORK5CYII=",
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
    class WPBakeryShortCode_Single_Carousel_Content extends WPBakeryShortCode {
    }
}

?>