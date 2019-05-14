/**
 * WebPack configuration variables used in development and production
 */

'use strict';

/**
 * External dependencies
 */
const autoprefixer = require( 'autoprefixer' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const path = require( 'path' );

const root = path.resolve( __dirname, '..' );
const src = path.join( root, 'src' );

const appCSSPlugin = new ExtractTextPlugin( 'app.css' );
const blocksCSSPlugin = new ExtractTextPlugin( 'blocks.css' );
const editBlocksCSSPlugin = new ExtractTextPlugin( 'blocks.editor.css' );
const sharedCSSPlugin = new ExtractTextPlugin( 'shared.css' );
const themeCSSPlugin = new ExtractTextPlugin( 'style.css' );

/**
 * Returns SCSS module config
 * 
 * @param {string} env
 * @param {string|null} hasCommon
 * 
 * @returns {object}
 */
function extractConfig( env, hasCommon ) {
    return {
        use: [
            // "postcss" loader applies autoprefixer to our CSS.
            { loader: 'raw-loader' },
            {
                loader: 'postcss-loader',
                options: {
                    ident: 'postcss',
                    plugins: [
                        autoprefixer( {
                            browsers: [
                                '>1%',
                                'last 4 versions',
                                'Firefox ESR',
                                'not ie < 9', // React doesn't support IE8 anyway
                            ],
                            flexbox: 'no-2009',
                        } ),
                    ],
                },
            },
            // "sass" loader converts SCSS to CSS.
            {
                loader: 'sass-loader',
                options: {
                    // Add common CSS file for variables and mixins.
                    data: hasCommon && hasCommon,
                    outputStyle: env === 'development' ? 'nested' : 'compressed'
                },
            },
        ],
    };
};

/**
 * Webpack config vars for WP plugin-related entry points 
 */
const plugin = {
    build: path.join( root, 'build', 'plugin' ),
    entries: {
        blocks: path.join( src, 'blocks', 'index.js' ),
        app: path.join( src, 'app', 'index.js' ),
        shared: path.join( src, 'shared', 'index.js' ),
    },
    extractCSS: [ appCSSPlugin, blocksCSSPlugin, editBlocksCSSPlugin, sharedCSSPlugin ],
    cssRules( env ) {
        return [
            {
                test: /app(?:.*)\.s?css$/,
                exclude: /(node_modules|bower_components)/,
                use: appCSSPlugin.extract( extractConfig( env ) ),
            },
            {
                test: /blocks(?:.*)?editor\.s?css$/,
                exclude: /(node_modules|bower_components)/,
                use: blocksCSSPlugin.extract( extractConfig( env, '@import "./src/blocks/common.scss";\n' ) ),
            },
            {
                test: /blocks(?:.*)?style\.s?css$/,
                exclude: /(node_modules|bower_components)/,
                use: editBlocksCSSPlugin.extract( extractConfig( env, '@import "./src/blocks/common.scss";\n' ) ),
            },
            {
                test: /shared(?:.*)\.s?css$/,
                exclude: /(node_modules|bower_components)/,
                use: sharedCSSPlugin.extract( extractConfig( env ) ),
            }
        ];
    },
    publicPattern: [
        { from: 'php/plugin', to: '.' },
        { from: 'php/shared', to: './includes' },
    ],
};

/**
 * Webpack config vars for WP theme-related entry points 
 */
const theme = {
    build: path.join( root, 'build', 'theme' ),
    entries: {
        main: path.join( src, 'theme', 'index.js' ),
        shared: path.join( src, 'shared', 'index.js' ),
    },
    extractCSS: [ themeCSSPlugin, sharedCSSPlugin ],
    cssRules( env ) {
        return [
            {
                test: /theme\/style\.s?css$/,
                exclude: /(node_modules|bower_components)/,
                use: themeCSSPlugin.extract( extractConfig( env ) ),
            },
            {
                test: /shared(?:.*)\.s?css$/,
                exclude: /(node_modules|bower_components)/,
                use: sharedCSSPlugin.extract( extractConfig( env ) ),
            }
        ];
    },
    publicPattern: [
        { from: 'php/theme', to: '.' },
        { from: 'php/shared', to: './includes' },
    ],
};

const resolve = {
    modules: [
        path.resolve( __dirname, '..', 'src' ),
        'node_modules'
    ],
    extensions: [ '.wasm', '.mjs', '.js', '.json', '.jsx' ],
};

/**
 * Given a string, returns a new string with dash separators converted to
 * camel-case equivalent. This is not as aggressive as `_.camelCase`, which
 * which would also upper-case letters following numbers.
 *
 * @param {string} string Input dash-delimited string.
 *
 * @return {string} Camel-cased string.
 */
const camelCaseDash = string => string.replace( /-([a-z])/g, ( match, letter ) => letter.toUpperCase() );

const externals = [
	'components',
	'edit-post',
	'element',
	'plugins',
	'editor',
	'blocks',
	'utils',
	'date',
	'data',
	'i18n',
].reduce(
	( externals, name ) => ( {
		...externals,
		[ `@wordpress/${ name }` ]: `wp.${ camelCaseDash( name ) }`,
	} ),
	{
		wp: 'wp',
		ga: 'ga', // Old Google Analytics.
		gtag: 'gtag', // New Google Analytics.
		react: 'React', // React itself is there in Gutenberg.
		jquery: 'jQuery', // import $ from 'jquery' // Use the WordPress version after enqueuing it.
		'react-dom': 'ReactDOM',
	}
);

module.exports = { plugin, theme, resolve, externals };