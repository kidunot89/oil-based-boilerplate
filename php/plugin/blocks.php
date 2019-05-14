<?php
/**
 * Functions for setting up guten-blocks.
 *
 * @package oil-based-boilerplate
 * @since   0.0.1
 */

$block_version = '0.0.1';

/**
 * Enqueues guten-block dependencies
 */
function oil_based_block_assets() {
	// Styles.
	wp_enqueue_style(
		'oil-based-block',
		plugins_url( 'blocks.css', __FILE__ ),
		array( 'oil-based-shared', 'wp-blocks' ),
		$block_version
	);
}
add_action( 'enqueue_block_assets', 'oil_based_block_assets' );

/**
 * Enqueues guten-block dependencies for the editor.
 */
function oil_based_block_editor_assets() {
	// Scripts.
	wp_enqueue_script(
		'oil-based-block-js',
		plugins_url( 'blocks.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'oil-based-shared-js' ),
		$block_version,
		true
	);

	// Styles.
	wp_enqueue_style(
		'oil-based-block-editor',
		plugins_url( 'blocks.editor.css', __FILE__ ),
		array( 'oil-based-shared', 'wp-edit-blocks' ),
		$block_version
	);
}
add_action( 'enqueue_block_editor_assets', 'oil_based_block_editor_assets' );
