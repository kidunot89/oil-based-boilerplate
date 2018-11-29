#!/usr/bin/env node
/**
 * Clones all github repositories used as volumes in the Docker development environment
 */

'use strict';

/**
 * External dependencies
 */
const fs = require( 'fs-extra' );
const path = require( 'path' );

// path to _dev directory
const _devDir = path.resolve( __dirname, '..', '_dev' );

try {

    // Create Directory if it doesn't exist and make sure its empty.
    fs.ensureDirSync ( _devDir );
    fs.emptyDirSync( _devDir );

    // Create git spawner in _dev directory
    const git = require( 'simple-git' )( _devDir );

    // Clone WPGraphQL-related repositories from github
    git
        .clone( 'https://github.com/wp-graphql/wp-graphql' )
        .clone( 'https://github.com/wp-graphql/wp-graphiql' )
        .clone( 'https://github.com/wp-graphql/wp-graphql-jwt-authentication' );
} catch ( error ) {
    console.error( 'Something went wrong', error );
}
