<?php
/**
 * Astro App functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astro_App
 */

if ( ! function_exists( 'astro_app_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function astro_app_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Astro App, use a find and replace
		 * to change 'astro-app' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'astro-app', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'astro-app' ),
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
		add_theme_support( 'custom-background', apply_filters( 'astro_app_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 500,
			'width'       => 500,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'astro_app_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function astro_app_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'astro_app_content_width', 640 );
}
add_action( 'after_setup_theme', 'astro_app_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function astro_app_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'astro-app' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'astro-app' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'astro_app_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function astro_app_scripts() {
	// THEME STYLES			
	wp_enqueue_style( 'astro-app-style', get_stylesheet_uri() );

	// GOOGLE FONTS
	wp_register_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,700');
    wp_enqueue_style( 'google-fonts');

    // GENERICONS
	if ( ! wp_style_is( 'genericons', 'registered' ) ) {
		wp_enqueue_style( 'genericons', get_template_directory_uri() . '/fonts/genericons.css', array(), null );
	}

    // THEME SCRIPTS
	wp_enqueue_script( 'astro-app-scripts', get_template_directory_uri() . '/js/astro-app-min.js', array(), false, true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'astro_app_scripts' );

/**
 * Replace 'post' with 'stakeholder' post type in main query.
 *
 * @param WP_Query $query
 */
function astro_app_stakeholders_in_loop($query) {
	if ($query->is_main_query() && $query->is_front_page()) {
		$query->set('post_type', array('stakeholder'));
	}
}
add_action('pre_get_posts', 'astro_app_stakeholders_in_loop');


// Change content output.
function astro_app_regular_content($content) {
  return $content;
}
add_filter ( 'the_content', 'astro_app_regular_content', 20 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'astro_crush_register_required_plugins' );
function astro_crush_register_required_plugins() {
	$plugins = array(
		array(
			'name'               => 'Astro Shortcodes',
			'slug'               => 'astro-shortcodes',
			'source'             => get_template_directory() . '/inc/plugins/astro-shortcodes.zip', 
			'required'           => true, 
			'version'            => '2.6', 
			'force_activation'   => true, 
			'force_deactivation' => false, 
		)
	);

	$config = array(
		'id'           => 'tgmpa',                 
		'default_path' => '',                      
		'menu'         => 'tgmpa-install-plugins', 
		'parent_slug'  => 'themes.php',            
		'capability'   => 'edit_theme_options',   
		'has_notices'  => true,                    
		'dismissable'  => false,                   
		'is_automatic' => true,                 
	);

	tgmpa( $plugins, $config );
}

