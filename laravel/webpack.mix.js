require('dotenv').config()
let webpack = require('webpack')

const mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

let dotenvplugin = new webpack.DefinePlugin({
    'process.env': {
        WEBSOCKET_URL: JSON.stringify(process.env.WEBSOCKET_URL)
    }
})

mix.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', []);

mix.webpackConfig({
    plugins: [
        dotenvplugin,
    ]
})