<?php
/**
 * Plugin Name: Oil-Based
 * Plugin URI: https://axistaylor/oil-based/
 * Author: kidunot89
 * Author URI: https://axistaylor.com/
 * Version: 0.0.1
 * Text-Domain: oil-based
 *
 * @package oil-based-boilerplate
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'GRAPHQL_DEBUG', true );

$plugin_name    = 'oil-based';
$plugin_version = '0.0.1';

// Define plugin constants.
if ( ! defined( strtoupper( preg_replace( '/-/', '_', $plugin_name ) ) . '_VERSION' ) ) {
	define( strtoupper( preg_replace( '/-/', '_', $plugin_name ) ) . '_VERSION', $plugin_version );
}
if ( ! defined( strtoupper( preg_replace( '/-/', '_', $plugin_name ) ) . '_PATH' ) ) {
	define( strtoupper( preg_replace( '/-/', '_', $plugin_name ) ) . '_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Plugin includes.
 */
function oil_based_init() {
	require_once plugin_dir_path( __FILE__ ) . 'blocks.php';
	require_once plugin_dir_path( __FILE__ ) . 'app.php';
}
add_action( 'init', 'oil_based_init' );

// Include WPGraphQL schema patches.
require_once plugin_dir_path( __FILE__ ) . 'includes/settings-patch.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/schema-patch.php';

// Set shared dependencies.
$shared_css_path = plugins_url( 'shared.css', __FILE__ );
$shared_js_path  = plugins_url( 'shared.js', __FILE__ );
$shared_version  = '0.0.1';
$target          = 'the Oil-Based plugin';
require_once plugin_dir_path( __FILE__ ) . 'includes/enqueue-scripts.php';

