<?php
/**
 * Oil-Based Theme functions and definitions
 * 
 * @package oil-based
 */

$theme_name = 'oil-based';
$theme_version = '0.0.1';

/**
 * OilBasedTheme constants
 */
if( ! defined( strtoupper( $theme_name ) . '_VERSION' ) ) {
	define( strtoupper( $theme_name ) . '_VERSION', $theme_version );
}
if( ! defined( strtoupper( $theme_name ) . '_PATH' ) ) {
	define( strtoupper( $theme_name ) . '_PATH', plugin_dir_path(__FILE__) );
}

/**
 * Sets up supports and registers menus
 */
function oil_based_theme_setup() {
	/*
	* Make theme available for translation.
	* Translations can be filed in the /languages/ directory.
	*/
	load_theme_textdomain( $theme_name, get_template_directory() . '/languages' );

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

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo' );

	/**
	 * Add support for Gutenburg Styling
	 */
	add_theme_support( 'align-wide' );

	/**
	 * Register Menu
	 */
	register_nav_menus(
		[
			'primary'	=> __( 'Main Menu' ),
			'social'	=> __( 'Social Links' ),
		]
	);

	// Adding support for core block visual styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );
	
	// Add support for custom color scheme.
	add_theme_support( 'editor-color-palette', [
		[
			'name'  => __( 'Strong Blue', 'gutenbergtheme' ),
			'slug'  => 'strong-blue',
			'color' => '#0073aa',
		],
		[
			'name'  => __( 'Lighter Blue', 'gutenbergtheme' ),
			'slug'  => 'lighter-blue',
			'color' => '#229fd8',
		],
		[
			'name'  => __( 'Very Light Gray', 'gutenbergtheme' ),
			'slug'  => 'very-light-gray',
			'color' => '#eee',
		],
		[
			'name'  => __( 'Very Dark Gray', 'gutenbergtheme' ),
			'slug'  => 'very-dark-gray',
			'color' => '#444',
		],
	 ] );

	$GLOBALS['content_width'] = apply_filters( 'gutenbergtheme_content_width', 640 );

}
add_action( 'after_setup_theme', 'oil_based_theme_setup' );

/**
 * Register widget area.
 */
function oil_based_theme_widgets_init() {
	register_sidebar(
		[
			'name'          => __( 'Widgets Area', $theme_name ),
			'id'            => 'main-widgets',
			'description'   => __( 'Add widgets here to appear in your sidebar.', $theme_name ),
		]
	);
}
add_action( 'widgets_init', 'oil_based_theme_widgets_init' );

/**
 * Register Google Fonts
 */
function oil_based_theme_gutenbergtheme_fonts_url() {
	$fonts_url = '';

	/*
	*Translators: If there are characters in your language that are not
	* supported by Noto Serif, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$notoserif = esc_html_x( 'on', 'Noto Serif font: on or off', 'gutenbergtheme' );
	if ( 'off' !== $notoserif ) {

		$font_families = [];
		$font_families[] = 'Noto Serif:400,400italic,700,700italic';

		$query_args = [
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		];

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Confirms url successful
 * 
 * @link https://stackoverflow.com/questions/7684771/how-to-check-if-a-file-exists-from-a-url#answer-29714882
 */
function url_exists( $url ){
	$headers = get_headers( $url );
	return stripos( $headers[ 0 ],"200 OK" ) ? true : false;
}

/**
 * Queues up theme JS and CSS files to be loaded.
 */
function oil_based_theme_enqueue_scripts() {
	if( url_exists( oil_based_theme_gutenbergtheme_fonts_url() ) ) {
		wp_enqueue_style( 'gutenbergtheme-fonts', oil_based_theme_gutenbergtheme_fonts_url() );
		wp_enqueue_style( 'oil-based-theme', get_stylesheet_uri(), [ 'oil-based-shared', 'gutenbergtheme-fonts' ] );
	} else {
		wp_enqueue_style( 'oil-based-theme', get_stylesheet_uri(), [ 'oil-based-shared' ] );
	}
	
	wp_enqueue_script(
		'oil-based-theme-js',
		get_template_directory_uri() . '/main.js',
		[ 'wp-element', 'oil-based-shared-js' ],
		$theme_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'oil_based_theme_enqueue_scripts' );

function oil_based_theme_the_endpoint() {
	$endpoint = home_url() . '/' . apply_filters( 'graphql_endpoint', 'graphql' );
	echo $endpoint;
}
