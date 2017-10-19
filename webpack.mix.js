let path = require('path');
let mix = require('laravel-mix');

let root = process.env.NODE_ENV === 'production' ? path.normalize('assets/prod') : path.normalize('assets/dev');

mix.webpackConfig({
    output: {
        libraryTarget: 'umd',
        umdNamedDefine: false
    }
}).setResourceRoot(root)
    .setPublicPath(root)
    .js('src/vue/vendors/Dropzone.js', 'vue/vendors/Dropzone.js')
    // .sass('sass/dropzone/dropzone.scss', 'dropzone/dropzone.css', {
    //     includePaths: [
    //         path.normalize("node_modules/compass-mixins/lib")
    //     ]
    // });