
let mix = require('laravel-mix');

mix.styles([
    'resources/assets/front/plugins/fontawesome-free/css/all.min.css',
    'resources/assets/front/css/adminlte.min.css',
], 'public/assets/front/css/front.css');

mix.scripts([
    'resources/assets/front/plugins/jquery/jquery.min.js',
    'resources/assets/front/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'resources/assets/front/js/adminlte.min.js',
    'resources/assets/front/js/adminlte.min.js',
], 'public/assets/front/js/front.js');

mix.copyDirectory('resources/assets/front/plugins/fontawesome-free/webfonts', 'public/assets/front/webfonts');
mix.copyDirectory('resources/assets/front/img', 'public/assets/front/img');
mix.copy('resources/assets/front/css/adminlte.min.css.map', 'public/assets/front/css/adminlte.min.css.map');
mix.copy('resources/assets/front/js/adminlte.min.js.map', 'public/assets/front/js/adminlte.min.js.map');