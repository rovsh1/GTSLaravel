const common = require('./webpack.common');

common.mix.setPublicPath('public');

common.sass();
common.js();

common.mix.copy('node_modules/boxicons/fonts/*', 'public/css/fonts/boxicons');
common.mix.version();
