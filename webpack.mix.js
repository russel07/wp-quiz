let mix = require("laravel-mix");

mix.js("resources/view/main.js", 'public/js/app.js').vue({version: 3});