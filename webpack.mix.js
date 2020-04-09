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

mix.js([
    'resources/js/app.js',
    'resources/customjs/jquery.inputmask.min.js',
    //'resources/customjs/Chart.bundle.min.js',
    //'resources/customjs/Chart.min.js',
    'resources/customjs/sidebar.js',
], 'public/js/app.js')
.js('resources/customjs/Chart.bundle.min.js'
        , 'public/js/Chart.min.js')
 .js('resources/customjs/jquery-dateformat.min.js'
        , 'public/js/jquery-dateformat.min.js')
   .sass('resources/sass/app.scss', 'public/css')
   .styles([
        'resources/css/sidebar.css',
        'resources/css/bootstrap.min.css',
        'resources/css/Chart.min.css',
        'resources/css/custom.css'
    ], 'public/css/all.css')
    .copyDirectory('resources/img', 'public/img')
    .copyDirectory('resources/customjs/base', 'public/js');
