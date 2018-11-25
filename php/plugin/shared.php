<?php

$shared_version = '0.0.1';

/**
 * Enqueue dependencies shared by blocks, admin, themes
 */
function oil_based_shared_scripts() {
    // Scripts.
	wp_enqueue_script(
		'oil-based-shared-js',
		plugins_url( 'shared.js', __FILE__ ),
		array( 'wp-element' ),
		true
    );
    
	// Styles.
	wp_enqueue_style(
		'oil-based-shared',
		plugins_url( 'shared.css', __FILE__ ),
		array(),
		$shared_version,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'oil_based_shared_scripts' );
add_action( 'wp_enqueue_scripts', 'oil_based_shared_scripts' );