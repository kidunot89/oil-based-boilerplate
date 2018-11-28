<?php

use GraphQLRelay\Relay;
use \WPGraphQL\Data\DataSource;

$plugin_name = 'oil-based-plugin';
$shared_version = '0.0.1';

/**
 * Enqueue dependencies shared by blocks, admin, themes
 */
function oil_based_shared_scripts() {
	// Styles.
	wp_enqueue_style(
		'oil-based-shared',
		plugins_url( 'shared.css', __FILE__ ),
		array(),
		$shared_version
	);

    // Scripts.
	wp_enqueue_script(
		'oil-based-shared-js',
		plugins_url( 'shared.js', __FILE__ ),
		array( 'wp-element' ),
		true
    );
}
add_action( 'wp_enqueue_scripts', 'oil_based_shared_scripts' );

/**
 * Checks for installation WPGraphQL-related plug-ins
 */
function oil_based_shared_check_dependencies() {
	$error = null;
	if (! defined( 'WPGRAPHQL_VERSION' ) ) {
		$error = new \WP_Error(
			'missing_dependency',
			sprintf(
				__( "WPGraphQL must be installed and activated to use the %s theme", $plugin_name ),
				$plugin_name
			)
		);
	}
	if ( is_wp_error( $error ) ) {
		echo $error->get_error_message();
	}
}
add_action( 'plugins_loaded', 'oil_based_shared_check_dependencies', 11 );

/**
 * Loads WPGraphQL schema patches needed for WPGraphQL-Composer to work properly
 */
function oil_based_shared_patch_schema()  {
  	register_graphql_fields('Settings', [
		/** 
		 * Defines the page_on_front setting
		 */
		'pageOnFront' => [
			'type' 			=> 'ID',
			'description' 	=> __( 'The page that should be displayed on the front page' ),
			'resolve' 		=> function() {
				$id = get_option( 'page_on_front', null );
				return ! empty( $id ) ? Relay::toGlobalId( 'page', $id ) : null;
			},
		],

		/** 
		 * Defines the page_for_posts setting
		 */
		'pageForPosts' => [
			'type' 			=> 'String',
			'description' 	=> __( 'The page that displays posts' ),
			'resolve' 		=> function() {
				$id = get_option( 'page_for_posts' );
				return ! empty( $id ) ? DataSource::resolve_post_object( $id, 'page' )->post_name : null;
			},
		],

		/** 
		 * Defines the show_avatar setting
		 */
		'showAvatars' => [
			'type' 			=> 'Boolean',
			'description' 	=> __( 'Avatar Display' ),
			'resolve' 		=> function() {
				return get_option( 'show_avatars', false );
			},
		],

		/** 
		 * Defines the users_can_register setting
		 */
		'usersCanRegister' => [
			'type' 			=> 'Boolean',
			'description' 	=> __( 'Anyone can register' ),
			'resolve' 		=> function() {
				return get_option( 'users_can_register', false );
			},
		],

		/** 
		 * Defines the permalink_structure setting
		 */
		'permalinkStructure' => [
			'type' 			=> 'String',
			'description' 	=> __( 'The structure of the blog\'s permalinks.' ),
			'resolve' 		=> function() {
				return get_option( 'permalink_structure' );
			},
		],

		/** 
		 * Defines the home_url setting
		 */
		'homeUrl' => [
			'type' 			=> 'String',
			'description' 	=> __( 'The url to current site. Use this if site is a multisite' ),
			'resolve' 		=> function() {
				return home_url();
			},
		],
  	] );

	/** 
	 * Holds the post type object permalink field
	 */
	$permalink = [
		'type' 				=> 'String',
		'args' 				=> [
			'leavename' => [
				'type' 			=> 'Boolean',
				'description' 	=> __( 'Whether to keep post name or page name' ),
			],
		],
		'description' 	=> __( 'The permalink to the post object' ),
		'resolve' => function( WP_Post $post, $args ) {
			if ( ! empty( $args['leavename'] ) && $args['leavename'] ) {
				$leavename = true;
			} else {
				$leavename = false;
			}

			/**
			 * Strip site url for routing use
			*/
			$permalink = str_replace( home_url() . '/', '', get_permalink( $post, $leavename ) );
			return ( $permalink ) ? $permalink : null;
		},
  	];
	register_graphql_field( 'post', 'permalink', $permalink );
	register_graphql_field( 'page', 'permalink', $permalink );
	register_graphql_field( 'attachment', 'permalink', $permalink );

	$isGutenPost = [
		'type' 			=> 'Boolean',
		'description' 	=> __( 'Is post made with the Gutenberg' ),
		'resolve' 		=> function( WP_Post $post, $args ) {
			$is_guten_post = preg_match("/<!-- wp:(.*) -->/", $post->post_content ) ? true : false;
			return $is_guten_post;
		},
	];
	register_graphql_field( 'post', 'isGutenPost', $isGutenPost );
	register_graphql_field( 'page', 'isGutenPost', $isGutenPost );
}
add_action( 'graphql_register_types', 'oil_based_shared_patch_schema' );