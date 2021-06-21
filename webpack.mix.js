const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/main.js', 'public/js/main.js')
    .js('resources/js/edit.js', 'public/js/edit.js')
    .js('resources/js/dashboard.js', 'public/js/dashboard.js')
    .babel('resources/js/genetable.js', 'public/js/genetable.js')
    .js('resources/js/filters.js', 'public/js/filters.js')
    .js('resources/js/bookmark.js', 'public/js/bookmark.js')
    .sass('resources/sass/app.scss', 'public/css');
