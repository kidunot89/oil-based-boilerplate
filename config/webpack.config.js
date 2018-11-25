/**
 * 
 */
'use strict';
const UglifyJsPlugin = require( 'uglifyjs-webpack-plugin' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );

const { plugin, theme, shared } = require( './webpack.vars' );

const env = 'production';
process.env.BABEL_ENV = env;
process.env.NODE_ENV = env;

const shouldUseSourceMap = process.env.GENERATE_SOURCEMAP === 'true';

const prodExtra = {
    devtool: shouldUseSourceMap ? 'source-map' : false,
    stats: 'minimal',
    externals: {
        react: 'React',
        'react-dom': 'ReactDOM',
        ga: 'ga', // Old Google Analytics.
        gtag: 'gtag', // New Google Analytics.
        jquery: 'jQuery', // import $ from 'jquery' // Use the WordPress version.
    },
}

const uglifyJsPlugin = new UglifyJsPlugin( {
    uglifyOptions: {
        compress: {
            warnings: false,
            // Disabled because of an issue with Uglify breaking seemingly valid code:
            // https://github.com/facebookincubator/create-react-app/issues/2376
            // Pending further investigation:
            // https://github.com/mishoo/UglifyJS2/issues/2011
            comparisons: false,
        },
        mangle: true,
        output: {
            comments: false,
            // Turned on because emoji and regex is not minified properly using default
            // https://github.com/facebookincubator/create-react-app/issues/2488
            ascii_only: true,
        },
    },
    sourceMap: shouldUseSourceMap,
} );

module.exports = [
    Object.assign( 
        {
            entry: plugin.entries,
            output: {
                pathinfo: true,
                filename: '[name].js',
                path: plugin.build
            },
            plugins: [
                new CopyWebpackPlugin(
                    [ plugin.publicPattern ],
                    { debug: 'debug' },
                ),
                ...plugin.extractCSS,
                uglifyJsPlugin,
            ],
            module: {
                rules: [
                    {
                        test: /\.(js|jsx|mjs)$/,
                        exclude: /(node_modules|bower_components)/,
                        use: {
                            loader: 'babel-loader',
                            options: {
                                babelrc: false,
                                presets: [
                                    /*
                                      To get tree shaking working, we need the `modules: false` below.
                                      https://goo.gl/4vZBSr - 2ality blog mentions that the issue is caused
                                      by under-the-hood usage of `transform-es2015-modules-commonjs`.
                                      https://goo.gl/sBmiwZ - A comment on the above post shows that we
                                      can use `modules: false`.
                                      https://goo.gl/aAxYAq - `babel-preset-env` documentation.
                                    */
                                    [
                                        '@babel/preset-env',
                                        {
                                            targets: {
                                                browsers: [
                                                    'last 2 Chrome versions',
                                                    'last 2 Firefox versions',
                                                    'last 2 Safari versions',
                                                    'last 2 iOS versions',
                                                    'last 1 Android version',
                                                    'last 1 ChromeAndroid version',
                                                    'ie 11'
                                                ]
                                            },
                                            modules: false // Needed for tree shaking to work.
                                        }
                                    ],
                                    // '@babel/preset-env', // https://goo.gl/aAxYAq
                                    '@babel/preset-react' // https://goo.gl/4aEFV3
                                ],
                                cacheDirectory: true,
                            },
                        },
                    },
                    ...plugin.cssRules( env ),
                ],
            },
        },
        prodExtra
    ),
    Object.assign(
        {
            entry: theme.entries,
            output: {
                pathinfo: true,
                filename: 'main.js',
                path: theme.build
            },
            plugins: [
                new CopyWebpackPlugin(
                    [ theme.publicPattern ],
                    { debug: 'debug' },
                ),
                ...theme.extractCSS,
                uglifyJsPlugin,
            ],
            module: {
                rules: [
                    {
                        test: /\.(js|jsx|mjs)$/,
                        exclude: /(node_modules|bower_components)/,
                        use: {
                            loader: 'babel-loader',
                            options: {
                                babelrc: false,
                                presets: [
                                    /*
                                      To get tree shaking working, we need the `modules: false` below.
                                      https://goo.gl/4vZBSr - 2ality blog mentions that the issue is caused
                                      by under-the-hood usage of `transform-es2015-modules-commonjs`.
                                      https://goo.gl/sBmiwZ - A comment on the above post shows that we
                                      can use `modules: false`.
                                      https://goo.gl/aAxYAq - `babel-preset-env` documentation.
                                    */
                                    [
                                        '@babel/preset-env',
                                        {
                                            targets: {
                                                browsers: [
                                                    'last 2 Chrome versions',
                                                    'last 2 Firefox versions',
                                                    'last 2 Safari versions',
                                                    'last 2 iOS versions',
                                                    'last 1 Android version',
                                                    'last 1 ChromeAndroid version',
                                                    'ie 11'
                                                ]
                                            },
                                            modules: false // Needed for tree shaking to work.
                                        }
                                    ],
                                    // '@babel/preset-env', // https://goo.gl/aAxYAq
                                    '@babel/preset-react' // https://goo.gl/4aEFV3
                                ],
                                plugins: [
                                    '@babel/plugin-proposal-object-rest-spread', // https://goo.gl/LCHWnP
                                    '@babel/plugin-proposal-class-properties' // https://goo.gl/TE6TyG
                                ],
                                cacheDirectory: true,
                            },
                        },
                    },
                    ...theme.cssRules( env ),
                ],
            },
        },
        prodExtra,
    ),
];