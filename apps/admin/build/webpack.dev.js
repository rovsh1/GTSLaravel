const common = require('./webpack.common');

common.mix.setPublicPath('public');

common.sass('admin');
common.js('admin');

/*
 |--------------------------------------------------------------------------
 | Configure sass
 |--------------------------------------------------------------------------
 */
const sassOptions = {
    precision: 5
};

// Core stylesheets
common.mixAssets('admin', 'vendor/scss/**/!(_)*.scss', (src, dest) =>
    common.mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), { sassOptions })
);

// Core javascripts
common.mixAssets('admin', 'vendor/js/**/*.js', (src, dest) => common.mix.js(src, dest));

// Libs
common.mixAssets('admin', 'vendor/libs/**/*.js', (src, dest) => common.mix.js(src, dest));
common.mixAssets('admin', 'vendor/libs/**/!(_)*.scss', (src, dest) =>
    common.mix.sass(src, dest.replace(/\.scss$/, '.css'), { sassOptions })
);
common.mixAssets('admin', 'vendor/libs/**/*.{png,jpg,jpeg,gif}', (src, dest) => common.mix.copy(src, dest));
// Copy task for form validation plugin as premium plugin don't have npm package
common.mixAssets('admin', 'vendor/libs/formvalidation/dist', (src, dest) => common.mix.copyDirectory(src, dest));

// Fonts
common.mixAssets('admin', 'vendor/fonts/*/*', (src, dest) => common.mix.copy(src, dest));
common.mixAssets('admin', 'vendor/fonts/!(_)*.scss', (src, dest) =>
    common.mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), { sassOptions })
);

common.mixAssets('admin', 'js/**/*.js', (src, dest) => common.mix.scripts(src, dest));
common.mixAssets('admin', 'css/**/*.css', (src, dest) => common.mix.copy(src, dest));

common.mix.copy('node_modules/boxicons/fonts/*', 'public/admin/assets/vendor/fonts/boxicons');

common.mix.version();

//mix.sass('site');
//mix.js('site');
