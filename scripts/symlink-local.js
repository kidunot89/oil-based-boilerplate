#!/usr/bin/env node
/**
 * Symlinks all necessary project directories to a local WordPress installation
 */

'use strict';

/**
 * External dependencies
 */
const fs = require( 'fs' );
const chalk = require( 'chalk' );
const path = require( 'path' );
const makeSymlinks = require( 'make-symlinks' );

const printSymlinks = ( symlink => {
    console.log( `${ chalk.green( symlink.target ) } → ${ chalk.cyan( symlink.path ) }`)
} );

try {
    // Throw if path to WP installation provided 
    if ( !process.argv[2] ) {
        throw new Error( chalk.red( 'no path to local Wordpress installation provided' ) );
    }

    const wpPath = path.resolve( process.argv[2] );
    // Throw if wp-config not found
    if ( !fs.existsSync( path.join( wpPath, 'wp-config.php' ) ) ) {
        throw new Error( `Wordpress installation no found at ${ chalk.red( wpPath ) }` );
    }

    const pluginsDirPath = path.resolve( wpPath, process.argv[3] || 'wp-content/plugins/' );
    const themesDirPath = path.resolve( wpPath, process.argv[4] || 'wp-content/themes/' );

    // Make symlinks for all sub-directories in _dev
    const pattern = path.resolve ( __dirname, '..', '_dev', '*' );
    makeSymlinks.sync( pattern, pluginsDirPath, { force: true } ).forEach(printSymlinks);

    // Symlink build/plugins and build/themes
    const pluginPath = path.resolve( __dirname, '..', 'build', 'plugin' );
    makeSymlinks.sync( pluginPath, pluginsDirPath, { force: true } ).forEach(printSymlinks);
    const themePath = path.resolve( __dirname, '..', 'build', 'theme' );
    makeSymlinks.sync( themePath, themesDirPath, { force: true } ).forEach(printSymlinks);

} catch ( error ) {
    console.error( error );
}