<?php
/**
 *  Function to display comments
 */
function clinical_cms_comments( /*$atts*/ ){

    /*
    $atts = shortcode_atts(
    array(
        'legacy_name' => 'nrnrnrn',
        'display_axis' => 'vertical',
    ), $atts );
    */
    
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
        $tmp_comments = comments_template();
        return $tmp_comments;
    endif;
    
    return ""; //return nothing
}
add_shortcode( 'Clinical_CMS_Comments', 'clinical_cms_comments' );
/**
 *  Map WP Comments Content shortcode to Visual Composer
 */
add_action( 'vc_before_init', 'Clinical_CMS_Comments_VisComp_Map' );
function Clinical_CMS_Comments_VisComp_Map() {

    vc_map( array(
        "name" => __( "Clinical CMS Comments", "clinical-cms-theme" ),
        "base" => "Clinical_CMS_Comments",
        "class" => "Clinical_CMS_Comments",
        "category" => __( "Clinical CMS Theme", "clinical-cms-theme"),
        "show_settings_on_create" => false,
    ) );
}




?>