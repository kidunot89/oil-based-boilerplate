<?php

$app_version = '0.0.1';

/**
 * Enqueues admin app dependencies
 */
function oil_based_app_scripts( $hook ) {
	if ( $hook !== 'toplevel_page_ob_customizer') {
		return;
	}
    // Scripts.
	wp_enqueue_script(
		'oil-based-app-js',
		plugins_url( 'app.js', __FILE__ ),
        array( 'wp-element', 'oil-based-shared-js' ),
        $app_version,
		true
    );
    
	// Styles.
	wp_enqueue_style(
		'oil-based-app',
		plugins_url( 'app.css', __FILE__ ),
		array( 'oil-based-shared' )
	);
}
add_action( 'admin_enqueue_scripts', 'oil_based_app_scripts' );

function oil_based_register_options_page() {
	add_menu_page( 'Oil-Based Customizer', 'OB Customizer', 'edit_theme_options', 'ob_customizer', 'oil_based_customizer');
}
add_action( 'admin_menu', 'oil_based_register_options_page' );

function oil_based_customizer() { ?>
	<div id="customizer-root"></div>
<?php }
