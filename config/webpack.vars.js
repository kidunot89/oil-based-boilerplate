/**
 * 
 */
'use strict';
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
                test: /app\/index\.s?css$/,
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
                test: /shared\/index\.s?css$/,
                exclude: /(node_modules|bower_components)/,
                use: sharedCSSPlugin.extract( extractConfig( env ) ),
            }
        ];
    },
    publicPattern: { from: 'php/plugin', to: '.' },
};

/**
 * Webpack config vars for WP theme-related entry points 
 */
const theme = {
    build: path.join( root, 'build', 'theme' ),
    entries: {
        theme: path.join( src, 'theme', 'index.js' ),
    },
    extractCSS: [ themeCSSPlugin ],
    cssRules( env ) {
        return [
            {
                test: /theme\/style\.s?css$/,
                exclude: /(node_modules|bower_components)/,
                use: themeCSSPlugin.extract( extractConfig( env ) ),
            }
        ];
    },
    publicPattern: { from: 'php/theme', to: '.' },
};

const shared = {
    
}

module.exports = {
    plugin,
    theme,
}