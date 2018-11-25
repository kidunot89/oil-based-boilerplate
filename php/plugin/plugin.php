<?php
/**
 * Plugin Name: Oil-Based
 * Plugin URI: https://axistaylor/oil-based/
 * Author: kidunot89
 * Author URI: https://axistaylor.com/
 * Version: 0.0.1
 *
 * @package oil-based-plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin includes.
 */
function oil_based_init() {
	require_once plugin_dir_path( __FILE__ ) . 'shared.php';
	require_once plugin_dir_path( __FILE__ ) . 'blocks.php';
	require_once plugin_dir_path( __FILE__ ) . 'app.php';
}
add_action( 'init', 'oil_based_init' );

