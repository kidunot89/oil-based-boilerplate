<?php
/**
 * Functions for enqueue scripts and styles used in multiple locations across the plugin.
 *
 * @package oil-based-boilerplate
 * @since   0.0.1
 */

use GraphQLRelay\Relay;
use \WPGraphQL\Data\DataSource;

if ( ! function_exists( 'oil_based_shared_scripts' ) ) :
	/**
	 * Enqueue dependencies shared by blocks, admin, themes
	 */
	function oil_based_shared_scripts() {
		global $shared_css_path, $shared_js_path, $shared_version;
		// Styles.
		wp_enqueue_style( 'oil-based-shared', $shared_css_path, array(), $shared_version );

		// Scripts.
		wp_enqueue_script( 'oil-based-shared-js', $shared_js_path, array( 'wp-element' ), $shared_version, true );
	}
endif;
add_action( 'wp_enqueue_scripts', 'oil_based_shared_scripts' );

if ( ! function_exists( 'oil_based_check_dependencies' ) ) :
	/**
	 * Checks for installation WPGraphQL-related plug-ins
	 */
	function oil_based_check_dependencies() {
		global $target;
		$error = null;
		if ( ! defined( 'WPGRAPHQL_VERSION' ) ) {
			$error = new \WP_Error(
				'missing_dependency',
				/* translators: missing dependency error message */
				sprintf( __( 'WPGraphQL must be installed and activated to use %s', 'oil-based' ), $target )
			);
		}
		if ( is_wp_error( $error ) ) {
			add_action(
				'admin_notices',
				function() use ( $dep ) {
					?>
						<div class="error notice">
							<p>
								<?php echo esc_html( $error->get_error_message() ); ?>
							</p>
						</div>
					<?php
				}
			);
		}
	}
endif;
add_action( 'plugins_loaded', 'oil_based_check_dependencies', 11 );
