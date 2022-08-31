const mix = require('laravel-mix')
const ESLintPlugin = require('eslint-webpack-plugin')
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

mix.js('resources/js/app.js', 'public/js').vue().sass('resources/css/app.scss', 'public/css').webpackConfig({ plugins: [new ESLintPlugin({ fix: true, extensions: ['js', 'vue'] })] })
mix.version()
