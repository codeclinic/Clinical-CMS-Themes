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
        'name' => 'Blog Overview Template',
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






















  
if(!function_exists('clinical_cms_theme_blog_header')){
    function clinical_cms_theme_blog_header( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'position'			=> 'left',
            'color'				=> '#333',
            'size'				=> '16px',
            'margin'		=> '0',
            'padding'		=> '0',
            'class'				=> ''
            ), $atts));
        
        $styles ='';
        if(margin) $styles .= 'margin:' . $margin .';';
        if(padding) $styles .= 'padding:' . $padding .';';
        if($position) $styles .= 'text-align:'. $position .';';
        if($size) $styles .= 'font-size:' . (int) $size . 'px;line-height:normal;';
        if($color) $styles .= 'color:' . $color  . ';';
        
        return '<header class="entry-header ' . esc_attr( $class ) . '" style="' . $styles . '">' . do_shortcode($content) . '</header><!-- .entry-header -->';
    }
    add_shortcode('Clinical_CMS_Theme_Blog_Header', 'clinical_cms_theme_blog_header');
}    

if(!function_exists('clinical_cms_theme_blog_title')) {
	function clinical_cms_theme_blog_title( $atts, $content =  null) {
        
        extract(shortcode_atts(array(
            'position'			=> 'left',
            'color'				=> '#333',
            'size'				=> '16px',
            'margin'		=> '10px 0 10px 0',
            'padding'		=> '10px 0 10px 0',
            'class'				=> ''
            ), $atts));
        
        $styles ='';
        if(margin) $styles .= 'margin:' . $margin .';';
        if(padding) $styles .= 'padding:' . $padding .';';
        if($position) $styles .= 'text-align:'. $position .';';
        if($size) $styles .= 'font-size:' . (int) $size . 'px;line-height:normal;';
        if($color) $styles .= 'color:' . $color  . ';';
        
        if ( is_singular() ) :
			return '<h1 class="entry-title ' . $class . '" style="' . $styles . '">' . get_the_title() . '</h1>';
		else :
			return '<h2 class="entry-title ' . $class . '" style="' . $styles . '"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . get_the_title() . '</a></h2>';
		endif;
	}
	add_shortcode('Clinical_CMS_Theme_Blog_Title', 'clinical_cms_theme_blog_title');		
}
if( !function_exists('clinical_cms_theme_blog_meta') ) {
	function clinical_cms_theme_blog_meta( $atts, $content =  null) {
        
        extract(shortcode_atts(array(
            'position'			=> 'left',
            'color'				=> '#333',
            'size'				=> '16px',
            'margin'		=> '',
            'padding'		=> '',
            'class'				=> ''
            ), $atts));
        
        $styles ='';
        if(margin) $styles .= 'margin:' . $margin .';';
        if(padding) $styles .= 'padding:' . $padding .';';
        if($position) $styles .= 'text-align:'. $position .';';
        if($size) $styles .= 'font-size:' . (int) $size . 'px;line-height:normal;';
        if($color) $styles .= 'color:' . $color  . ';';
        
        if ( 'post' === get_post_type() ){
            ob_start();
            clinical_cms_theme_posted_on();
            $postedOn = ob_get_contents();
            ob_end_clean();
            return '<div class="entry-meta" style="' . $styles . '">' . $postedOn . '</div><!-- .entry-meta -->';
        }
        return;
	}
	add_shortcode('Clinical_CMS_Theme_Blog_Meta', 'clinical_cms_theme_blog_meta');		
}
if( !function_exists('clinical_cms_theme_blog_thumb') ) {
	function clinical_cms_theme_blog_thumb( $atts, $content =  null) {
        
        global $post;
        
        extract(shortcode_atts(array(
            'position'			=> 'left',
            'color'				=> '#333',
            'size'				=> '16px',
            'margin'		=> '0',
            'padding'		=> '0',
            'class'				=> '',
            'size' => 'large',
            'link'          => false,
            ), $atts));
        
        $styles ='';
        if(margin) $styles .= 'margin:' . $margin .';';
        if(padding) $styles .= 'padding:' . $padding .';';
        if($position) $styles .= 'text-align:'. $position .';';
        if($size) $styles .= 'font-size:' . (int) $size . 'px;line-height:normal;';
        if($color) $styles .= 'color:' . $color  . ';';
        
        if ( has_post_thumbnail( $post->ID ) ) {
            if($link) return '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( $post->post_title ) . '" style="display:block;' . $styles . '">' . get_the_post_thumbnail( $post->ID, $size ) . '</a>';
            return '<div style="' . $styles . '">' . get_the_post_thumbnail( $post->ID, $size ) . '</div>';
        }
        return;
	}
	add_shortcode('Clinical_CMS_Theme_Blog_Thumb', 'clinical_cms_theme_blog_thumb');		
}

//BODY CONTENT
if( !function_exists('clinical_cms_theme_blog_body') ) {
    function clinical_cms_theme_blog_body( $atts, $content = null ) {
        
        extract(shortcode_atts(array(
            'position'			=> 'left',
            'color'				=> '#333',
            'size'				=> '16px',
            'margin'		=> '0',
            'padding'		=> '0',
            'class'				=> ''
            ), $atts));
        
        $styles ='';
        if(margin) $styles .= 'margin:' . $margin .';';
        if(padding) $styles .= 'padding:' . $padding .';';
        if($position) $styles .= 'text-align:'. $position .';';
        if($size) $styles .= 'font-size:' . (int) $size . 'px;line-height:normal;';
        if($color) $styles .= 'color:' . $color  . ';';
        
        return '<div class="entry-content" style="' . $styles . '">' . do_shortcode($content) . '</div><!-- .entry-content -->';
    }
    add_shortcode('Clinical_CMS_Theme_Blog_Body', 'clinical_cms_theme_blog_body');
}
if(!function_exists('clinical_cms_theme_blog_contents')){
    function clinical_cms_theme_blog_contents( $atts, $content = null ) {
        
        extract(shortcode_atts(array(
            'source'              => 'content',
            'more'              => __('Continue reading', 'clinical-cms-theme' ),
            'length'            => '55',
            'position'			=> 'left',
            'color'				=> '#333',
            'size'				=> '16px',
            'margin'		    => '0',
            'padding'		    => '0',
            'class'				=> ''
            ), $atts));
        
        $styles ='';
        if(margin) $styles .= 'margin:' . $margin .';';
        if(padding) $styles .= 'padding:' . $padding .';';
        if($position) $styles .= 'text-align:'. $position .';';
        if($size) $styles .= 'font-size:' . (int) $size . 'px;line-height:normal;';
        if($color) $styles .= 'color:' . $color  . ';';
        
        /*$safeMore = sprintf(  
                wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers * /
                        esc_html ( $more ) . __( '<span class="screen-reader-text" style="' . $styles . '"> "%s"</span>', 'clinical-cms-theme' ),
                        array(
                            'span' => array(
                            'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
             );
        */
        //echo "TMPLINK: " . $safeMore;
        
        if( $source == 'content' ){
            //ob_start();
            //$postContent = get_the_content();
            //$postContent = ob_get_contents();
            //ob_end_clean();
            //Apply 'the_conter()' filters
            //$postContent = apply_filters( 'the_content', $postContent );
            //$postContent = str_replace( ']]>', ']]&gt;', $postContent );
            
            $postContent = clinical_cms_theme_content( (int)$length, $post );
        }
        else {
            //clinical_cms_theme_excerpt_length
            //$postContent = get_the_excerpt();
            
            $postContent = clinical_cms_theme_excerpt( (int)$length, $post );
        } 
            
        //echo "POSTCONTENT: " . $postContent;
        //$postContent = wp_trim_words( $postContent, $length, "More >>" );
        
        //$postContent = wp_trim_words( $postContent, $length, $tmpMore);
        
        
        ob_start();
        wp_link_pages( array(
            'before' => '<div class="page-links" style="' . $styles . '">' . esc_html__( 'Pages:', 'clinical-cms-theme' ),
            'after'  => '</div>',
        ) );
        $postContent .= ob_get_contents();
        ob_end_clean();
        
        return $postContent;
    }
    add_shortcode('Clinical_CMS_Theme_Blog_Contents', 'clinical_cms_theme_blog_contents');
}
//FOOTER CONTENT
if( !function_exists('clinical_cms_theme_blog_footer') ) {
    function clinical_cms_theme_blog_footer( $atts, $content = null ) {
        
        extract(shortcode_atts(array(
            'position'			=> 'left',
            'color'				=> '#333',
            'size'				=> '16px',
            'margin'		=> '0',
            'padding'		=> '0',
            'class'				=> ''
            ), $atts));
        
        $styles ='';
        if(margin) $styles .= 'margin:' . $margin .';';
        if(padding) $styles .= 'padding:' . $padding .';';
        if($position) $styles .= 'text-align:'. $position .';';
        if($size) $styles .= 'font-size:' . (int) $size . 'px;line-height:normal;';
        if($color) $styles .= 'color:' . $color  . ';';
        
        return '<footer class="entry-footer" style="' . $styles . '">' . do_shortcode($content) . '</footer><!-- .entry-footer -->';
    }
    add_shortcode('Clinical_CMS_Theme_Blog_Footer', 'clinical_cms_theme_blog_footer');
}
if( !function_exists('clinical_cms_theme_blog_tools') ) {
    function clinical_cms_theme_blog_tools( $atts, $content = null ) {
        
        ob_start();
        clinical_cms_theme_entry_footer();
        $postTools = ob_get_contents();
        ob_end_clean();
        return $postTools;
        
    }
    add_shortcode('Clinical_CMS_Theme_Blog_Tools', 'clinical_cms_theme_blog_tools');
}
//COMMENT COUNT
if( !function_exists('clinical_cms_theme_blog_comments') ) {
    function clinical_cms_theme_blog_comments( $atts, $content = null ) { 
        
        global $post;
        
        extract(shortcode_atts(array(
            'before'            => '',
            'after'             => ' comments',
            'position'          => 'left',
            'color'				=> '#333',
            'size'				=> '16px',
            'margin'		    => '0',
            'padding'		    => '0',
            'class'				=> ''
            ), $atts));
        
        $styles ='';
        if(margin) $styles .= 'margin:' . $margin .';';
        if(padding) $styles .= 'padding:' . $padding .';';
        if($position) $styles .= 'text-align:'. $position .';';
        if($size) $styles .= 'font-size:' . (int) $size . 'px;line-height:normal;';
        if($color) $styles .= 'color:' . $color  . ';';
        
        $count = wp_count_comments( $post->ID );
        $output =  "<div style='" . $styles . "'>" . $before . $count->approved . $after . "</div>";
        return $output; 
    }
    add_shortcode('Clinical_Cms_Theme_Blog_Comments','clinical_cms_theme_blog_comments'); 
}


// Mapping 
vc_map( array(
    "name" => __("Clinical Post Header", "clinical-cms-theme"), 
    "base" => "Clinical_CMS_Theme_Blog_Header",
    "as_parent" => array('only' => 'Clinical_CMS_Theme_Blog_Title, Clinical_CMS_Theme_Blog_Meta, Clinical_CMS_Theme_Blog_Thumb, Clinical_CMS_Theme_Blog_Tools, Clinical_Cms_Theme_Blog_Comments'),
    "content_element" => true,
    "show_settings_on_create" => true,
    "is_container" => true,
    "js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(

		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),   
) );
vc_map( array(
    "name" => __("Clinical Post Title", "clinical-cms-theme"),
    "base" => "Clinical_CMS_Theme_Blog_Title",
    "as_child" => array('only' => 'Clinical_CMS_Theme_Blog_Header'),
    //"content_element" => false, // set this parameter when element will has a content
    "show_settings_on_create" => true,
    //"is_container" => false, // set this param when you need to add a content element in this element
    //"js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(

		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),   
) );
vc_map( array(
    "name" => __("Clinical Post Meta", "clinical-cms-theme"),
    "base" => "Clinical_CMS_Theme_Blog_Meta",
    "as_child" => array('only' => 'Clinical_CMS_Theme_Blog_Header'),
    //"content_element" => false, // set this parameter when element will have content
    "show_settings_on_create" => true,
    //"is_container" => false, // set this param when you need to add a content element in this element
    //"js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(

		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),   
) );
vc_map( array(
    "name" => __("Clinical Post Image", "clinical-cms-theme"),
    "base" => "Clinical_CMS_Theme_Blog_Thumb",
    "as_child" => array('only' => 'Clinical_CMS_Theme_Blog_Header'),
    //"content_element" => false, // set this parameter when element will have content
    "show_settings_on_create" => true,
    //"is_container" => false, // set this param when you need to add a content element in this element
    //"js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(
    
		array(
			"type" => "checkbox",
			"heading" => __("Link To Post? ", "clinical-cms-theme"),
			"param_name" => "link",
			"value" => 0,
			),
    
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "thumb-sizes",
            "heading" => __( "Image Size", "clinical-cms-theme" ),
            "param_name" => "size",
            "admin_label" => true,
            "value" => get_intermediate_image_sizes(),
            //'std'         => 'one', //default value
            "description" => __( "Select the required image size.", "clinical-cms-theme" )
            ),		

		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),      
) );
vc_map( array(
    "name" => __("Clinical Post Body", "clinical-cms-theme"), 
    "base" => "Clinical_CMS_Theme_Blog_Body",
    "as_parent" => array('only' => 'Clinical_CMS_Theme_Blog_Contents, Clinical_Cms_Theme_Blog_Comments'),
    "content_element" => true,
    "show_settings_on_create" => true,
    "is_container" => true,
    "js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(
    
		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),   
) );
vc_map( array(
    "name" => __("Clinical Post Contents", "clinical-cms-theme"),
    "base" => "Clinical_CMS_Theme_Blog_Contents",
    "as_child" => array('only' => 'Clinical_CMS_Theme_Blog_Body'),
    //"content_element" => false, // set this parameter when element will have content
    "show_settings_on_create" => true,
    //"is_container" => false, // set this param when you need to add a content element in this element
    //"js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(

        array(
			"type" => "dropdown",
			"heading" => __("Content Source", "clinical-cms-theme"),
			"param_name" => "source",
			"value" => array('content'=>'content','excerpt'=>'excerpt'),
			),	

        array(
			"type" => "textfield",
			"heading" => __("Max Length", "clinical-cms-theme"),
			"param_name" => "length",
			"value" => "",
			),	

        array(
			"type" => "textfield",
			"heading" => __("Read More Label", "clinical-cms-theme"),
            "description" => __("The text used to link to full content.", "clinical-cms-theme"),
			"param_name" => "more",
			"value" => "",
			),	
    
		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),   
) );
vc_map( array(
    "name" => __("Clinical Post Footer", "clinical-cms-theme"),
    "base" => "Clinical_CMS_Theme_Blog_Footer",
    "as_parent" => array('only' => 'Clinical_CMS_Theme_Blog_Tools, Clinical_Cms_Theme_Blog_Comments'),
    "content_element" => true, // set this parameter when element will have content
    "show_settings_on_create" => true,
    "is_container" => true, // set this param when you need to add a content element in this element
    "js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(

		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),   
) );
vc_map( array(
    "name" => __("Clinical Post Tools", "clinical-cms-theme"),
    "base" => "Clinical_CMS_Theme_Blog_Tools",
    "as_child" => array('only' => 'Clinical_CMS_Theme_Blog_Header, Clinical_CMS_Theme_Blog_Footer'),
    //"content_element" => false, // set this parameter when element will have content
    "show_settings_on_create" => true,
    //"is_container" => false, // set this param when you need to add a content element in this element
    //"js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(

		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),   
) );
/*
//COMMENT COUNTS
vc_map( array(
    "name" => __("Clinical Post Comment Count", "clinical-cms-theme"),
    "base" => "Clinical_Cms_Theme_Blog_Comments",
    "as_child" => array('only' => 'Clinical_CMS_Theme_Blog_Header, Clinical_CMS_Theme_Blog_Body, Clinical_CMS_Theme_Blog_Footer'),
    //"content_element" => false, // set this parameter when element will have content
    "show_settings_on_create" => true,
    //"is_container" => false, // set this param when you need to add a content element in this element
    //"js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(
    
		array(
			"type" => "textfield",
			"heading" => __("Text Before Count", "clinical-cms-theme"),
            "description" => __("eg: 'This post has '"),
			"param_name" => "text-before",
			"value" => "",
			),	
    
		array(
			"type" => "textfield",
			"heading" => __("Text After Count", "clinical-cms-theme"),
            "description" => __("eg: ' user comments'"),
			"param_name" => "text-after",
			"value" => "",
			),	

		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
        ),   
) );
*/
vc_map( array(
    "name" => __("Clinical Comments Count", "clinical-cms-theme"),
    "base" => "Clinical_Cms_Theme_Blog_Comments",
    "as_child" => array('only' => 'Clinical_CMS_Theme_Blog_Header, Clinical_CMS_Theme_Blog_Body, Clinical_CMS_Theme_Blog_Footer'),
    //"content_element" => false, // set this parameter when element will have content
    "show_settings_on_create" => true,
    //"is_container" => false, // set this param when you need to add a content element in this element
    //"js_view" => 'VcColumnView',
    "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
    "params" => array(

		array(
			"type" => "textfield",
			"heading" => __("Text Before Count", "clinical-cms-theme"),
            "description" => __("eg: 'This post has '"),
			"param_name" => "before",
			"value" => "",
			),	
    
		array(
			"type" => "textfield",
			"heading" => __("Text After Count", "clinical-cms-theme"),
            "description" => __("eg: ' user comments'"),
			"param_name" => "after",
			"value" => "",
			),	
    
		array(
			"type" => "dropdown",
			"heading" => __("Text Alignment", "clinical-cms-theme"),
			"param_name" => "position",
			"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	
    
        array(
			"type" => "textfield",
			"heading" => __("Font Size", "clinical-cms-theme"),
			"param_name" => "size",
			"value" => "",
			),					

		array(
			"type" => "colorpicker",
			"heading" => __("Font Color", "clinical-cms-theme"),
			"param_name" => "color",
			"value" => "",
			),			

		array(
			"type" => "textfield",
			"heading" => __("Margin", "clinical-cms-theme"),
			"param_name" => "margin",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Padding", "clinical-cms-theme"),
			"param_name" => "padding",
			"value" => "",
			),

		array(
			"type" => "textfield",
			"heading" => __("Custom Class ", "clinical-cms-theme"),
			"param_name" => "class",
			"value" => "",
			),
    
                ),   
) );

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Header extends WPBakeryShortCodesContainer {
    } 
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Body extends WPBakeryShortCodesContainer {
    } 
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Footer extends WPBakeryShortCodesContainer {
    } 
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Title extends WPBakeryShortCode {
    }
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Meta extends WPBakeryShortCode {
    }
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Thumb extends WPBakeryShortCode {
    }
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Contents extends WPBakeryShortCode {
    }
    class WPBakeryShortCode_Clinical_CMS_Theme_Blog_Tools extends WPBakeryShortCode {
    }
    class WPBakeryShortCode_Clinical_Cms_Theme_Blog_Comments extends WPBakeryShortCode {
    }
    
}

?>