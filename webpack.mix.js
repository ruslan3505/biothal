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
    .sass('resources/sass/app.scss', 'public/css');

mix.scripts([
    'resources/admin/js/libs/kit.fontawesome.js',
    'resources/admin/js/libs/jquery.js',
    'resources/admin/js/libs/popper.js',
    'resources/admin/js/libs/bootstrap.js',
    'resources/admin/js/scripts.js',
], 'public/js/admin.js')

mix.styles([
    'resources/admin/css/libs/bootstrap.css',
    'resources/admin/css/app.css',
],'public/css/admin.css')
