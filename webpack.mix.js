const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .styles([
        'resources/assets/bootstrap/css/bootstrap.min.css',
    ],  'public/css/bootstrap.css')
    .styles([
        'resources/assets/dist/css/AdminLTE.min.css',
        'resources/assets/dist/css/skins/skin-blue.min.css',
        'resources/assets/plugins/datatables/jquery.dataTables.css',
        'resources/assets/plugins/datatables/dataTables.bootstrap.css',
        'resources/assets/plugins/datatables/dataTables.buttons.min.css',
        'resources/assets/plugins/daterangepicker/daterangepicker.css',
        'resources/assets/dist/css/font-awesome-4.7.0/css/font-awesome.min.css'
    ],  'public/css/nanose.css')
    .scripts([
        'resources/assets/plugins/jQuery/jquery-2.2.3.min.js',
        'resources/assets/bootstrap/js/bootstrap.min.js',
        'resources/assets/plugins/datatables/jquery.dataTables.js',
        'resources/assets/plugins/datatables/dataTables.bootstrap.js',
        'resources/assets/plugins/datatables/dataTables.buttons.min.js',
        'resources/assets/plugins/datatables/buttons.server-side.js',
        'resources/assets/plugins/slimScroll/jquery.slimscroll.min.js',
        'resources/assets/plugins/daterangepicker/moment.js',
        'resources/assets/plugins/daterangepicker/daterangepicker.js',
        'resources/assets/dist/js/app.min.js'
    ],  'public/js/nanose.js')
    .copy('resources/assets/dist/css/font-awesome-4.7.0/fonts', 'public/fonts');