<?php
/**
 * Clinical CMS Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Clinical_CMS_Theme
 */

if ( ! function_exists( 'clinical_cms_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function clinical_cms_theme_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Clinical CMS Theme, use a find and replace
	 * to change 'clinical-cms-theme' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'clinical-cms-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'clinical-cms-theme' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'clinical_cms_theme_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'clinical_cms_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function clinical_cms_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'clinical_cms_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'clinical_cms_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function clinical_cms_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar 1', 'clinical-cms-theme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'clinical-cms-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    register_sidebar( array(
		'name'          => esc_html__( 'Sidebar 2', 'clinical-cms-theme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'clinical-cms-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    register_sidebar( array(
		'name'          => esc_html__( 'Sidebar 3', 'clinical-cms-theme' ),
		'id'            => 'sidebar-3',
		'description'   => esc_html__( 'Add widgets here.', 'clinical-cms-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    register_sidebar( array(
		'name'          => esc_html__( 'Sidebar 4', 'clinical-cms-theme' ),
		'id'            => 'sidebar-4',
		'description'   => esc_html__( 'Add widgets here.', 'clinical-cms-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'clinical_cms_theme_widgets_init' );


/**
 * Load Titan Framework plugin checker
 */
require get_template_directory() . '/titan-framework-checker.php';
/**
 * Load Titan Framework options
 */
require get_template_directory() . '/titan-options.php';
/**
 * Create A MetaBox To Identify Blog Template
 */
add_action( 'tf_create_options', 'clinical_cms_theme_setup' );
function clinical_cms_theme_setup() {
    $titan = TitanFramework::getInstance( 'clinical-cms-theme' );
    $section = $titan->createThemeCustomizerSection( array(
        'id' => 'static_front_page',
    ) ); 
    // Create options that will appear 
    $section->createOption( array(
        'id' => 'static_front_page',
        'name' => 'Single Post Template',
        'id' => 'clinical_blog_template',
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
function clinical_cms_theme_content_page_for_posts(){
    $output;
    if ( have_posts() ) :
			if ( is_home() && ! is_front_page() ) : 
				$output = '<header>
					<h1 class="page-title screen-reader-text">' . single_post_title() . '</h1>
				</header>';
			endif;
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				$output .= get_template_part( 'template-parts/content', get_post_format() );
			endwhile;
			$output .= the_posts_navigation();
		else :
			$output .= get_template_part( 'template-parts/content', 'none' );
		endif;   
    return $output;
}
add_shortcode( 'Clinical_CMS_Blog_Content', 'clinical_cms_theme_content_page_for_posts' );




/**
 * Enqueue scripts and styles.
 */
function clinical_cms_theme_scripts() {
	wp_enqueue_style( 'clinical-cms-theme-style', get_stylesheet_uri() );

	wp_enqueue_script( 'clinical-cms-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'clinical-cms-theme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'clinical_cms_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Additional features to allow styling of the templates.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Nag to install / activate required plugins
 */
require_once get_template_directory() . '/inc/tgmpa/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'clinical_cms_theme_register_required_plugins' );

/**
 * Function to initialise TGMP with rerquired plugins
 */
function clinical_cms_theme_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		//Include Slider Revolution.
		array(
			'name'               => 'Slider Revolution', // The plugin name.
			'slug'               => 'revslider', // The plugin slug (typically the folder name).
			'source'             => get_template_directory() . '/inc/tgmpa/plugins/slider-revolution.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
        
        //Include Visual Composer
		array(
			'name'               => 'Visual Composer', // The plugin name.
			'slug'               => 'js_composer', // The plugin slug (typically the folder name).
			'source'             => get_template_directory() . '/inc/tgmpa/plugins/js_composer.zip', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
        
        // Clever Mega Menu from the WordPress Plugin Repository.
		array(
			'name'      => 'Clever Mega Menu (Visual Composer Extension)',
			'slug'      => 'clever-mega-menu',
			'required'  => false,
		),
/*
		// This is an example of how to include a plugin from an arbitrary external source in your theme.
		array(
			'name'         => 'TGM New Media Plugin', // The plugin name.
			'slug'         => 'tgm-new-media-plugin', // The plugin slug (typically the folder name).
			'source'       => 'https://s3.amazonaws.com/tgm/tgm-new-media-plugin.zip', // The plugin source.
			'required'     => true, // If false, the plugin is only 'recommended' instead of required.
			'external_url' => 'https://github.com/thomasgriffin/New-Media-Image-Uploader', // If set, overrides default API URL and points to an external URL.
		),
*/
/*
		// This is an example of how to include a plugin from a GitHub repository in your theme.
		// This presumes that the plugin code is based in the root of the GitHub repository
		// and not in a subdirectory ('/src') of the repository.
		array(
			'name'      => 'Adminbar Link Comments to Pending',
			'slug'      => 'adminbar-link-comments-to-pending',
			'source'    => 'https://github.com/jrfnl/WP-adminbar-comments-to-pending/archive/master.zip',
		),
*/
/*
		// This is an example of how to include a plugin from the WordPress Plugin Repository.
		array(
			'name'      => 'BuddyPress',
			'slug'      => 'buddypress',
			'required'  => false,
		),
*/
/*
		// This is an example of the use of 'is_callable' functionality. A user could - for instance -
		// have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
		// 'wordpress-seo-premium'.
		// By setting 'is_callable' to either a function from that plugin or a class method
		// `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
		// recognize the plugin as being installed.
		array(
			'name'        => 'WordPress SEO by Yoast',
			'slug'        => 'wordpress-seo',
			'is_callable' => 'wpseo_init',
		),
*/
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'clinical-cms-theme',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);
	tgmpa( $plugins, $config );
}