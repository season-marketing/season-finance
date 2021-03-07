let mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.setPublicPath('./dist')
  .js('app/Resources/js/app.js', 'js')
  .postCss('app/Resources/css/app.css', 'css')
  .options({
    processCssUrls: false,
    postCss: [tailwindcss("./tailwind.config.js")]
  })

