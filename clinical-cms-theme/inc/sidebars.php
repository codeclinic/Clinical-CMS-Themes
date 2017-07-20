<?php

/**
 * Create CPT Dynamic Sidebars
 */
function clinical_cms_theme_create_sidebar_post_type() {
    $labels = array( 
        'name' => __( 'Sidebars' ),
        'singular_name' => __( 'Sidebar' ),
        'add_new' => __( 'New Sidebar' ),
        'add_new_item' => __( 'Add New Sidebar' ),
        'edit_item' => __( 'Edit Sidebar' ),
        'new_item' => __( 'New Sidebar' ),
        'view_item' => __( 'View Sidebar' ),
        'search_items' => __( 'Search Sidebars' ),
        'not_found' =>  __( 'No Sidebars Found' ),
        'not_found_in_trash' => __( 'No Sidebars found in Trash' ),
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => false,
        'public' => true,
        'hierarchical' => false,
        'supports' => array(
            'title', 
            'editor', 
            'excerpt', 
            'custom-fields', 
            'thumbnail',
            'page-attributes'
        ),
    );
    register_post_type( 'sidebar_post', $args );
} 
add_action( 'init', 'clinical_cms_theme_create_sidebar_post_type' );

?>