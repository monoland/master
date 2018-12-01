// @ts-ignore
const mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@scripts': __dirname + '/resources/scripts'
        }
    }
});

mix.js('resources/scripts/frontend.js', 'public/vendor/monoland/frontend.js');
mix.stylus('resources/stylus/frontend/main.styl', 'public/vendor/monoland/frontend.css');

mix.js('resources/scripts/account.js', 'public/vendor/monoland/account.js');
mix.stylus('resources/stylus/account/main.styl', 'public/vendor/monoland/account.css');

mix.js('resources/scripts/backend.js', 'public/vendor/monoland/backend.js');
mix.stylus('resources/stylus/backend/main.styl', 'public/vendor/monoland/backend.css');

mix.extract(['debounce', 'vue', 'vuetify']);