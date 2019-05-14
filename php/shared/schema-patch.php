<?php
/**
 * Adds WPGraphQL fields and types needed for WPGraphQL-Composer to work properly
 *
 * @package oil-based-boilerplate
 */

if ( ! function_exists( 'oil_based_patch_schema' ) ) :
	/**
	 * The "graphql_register_types" callback.
	 */
	function oil_based_patch_schema() {
		// Holds the post type object permalink field.
		$permalink = array(
			'type'        => 'String',
			'args'        => array(
				'leavename' => array(
					'type'        => 'Boolean',
					'description' => __( 'Whether to keep post name or page name' ),
				),
			),
			'description' => __( 'The permalink to the post object' ),
			'resolve'     => function( $source, $args ) {
				if ( ! empty( $args['leavename'] ) && $args['leavename'] ) {
					$leavename = true;
				} else {
					$leavename = false;
				}

				/**
				 * Strip site url for routing use
				*/
				$permalink = str_replace( home_url() . '/', '', get_permalink( $source->ID, $leavename ) );
				return ( $permalink ) ? $permalink : null;
			},
		);
		register_graphql_field( 'post', 'permalink', $permalink );
		register_graphql_field( 'page', 'permalink', $permalink );
		register_graphql_field( 'attachment', 'permalink', $permalink );

		$is_guten_post = array(
			'type'        => 'Boolean',
			'description' => __( 'Is post made with the Gutenberg' ),
			'resolve'     => function( $source, $args ) {
				return preg_match( '/<!-- wp:(.*) -->/', $source->content ) ? true : false;
			},
		);
		register_graphql_field( 'post', 'isGutenPost', $is_guten_post );
		register_graphql_field( 'page', 'isGutenPost', $is_guten_post );
	}
endif;

add_action( 'graphql_register_types', 'oil_based_patch_schema' );
