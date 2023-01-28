const mix = require('laravel-mix');
const fs = require('fs');
//const path = require('path');

const rootPath = process.env.PWD;
const resourcesPath = rootPath + '/resources';
/*mix.autoload({
	jquery: ['$', 'window.jQuery']
});*/

mix.options({
    manifest: false,
    terser: {
        extractComments: false,
    }
});

mix.alias({
    '@site': resourcesPath + '/site/js',
    '@admin': resourcesPath + '/admin/js',

    '~site': resourcesPath + '/site/sass',
    '~admin': resourcesPath + '/admin/sass'
});

const readdir = function (dir, path = '') {
    const readPath = dir + path;
    let files = [];
    fs.readdirSync(readPath)
        .forEach(entry => {
            const stat = fs.statSync(readPath + '/' + entry);
            if (stat.isFile()) {
                if (entry.charAt(0) === '_')
                    return;
                files.push(path + '/' + entry);
                return files;
            } else if (stat.isDirectory())
                readdir(dir, path + '/' + entry).forEach(f => files.push(f));
        });
    return files;
};

const rmdir = function (dirPath, exclude) {
    const files = fs.readdirSync(dirPath);
    if (files.length === 0)
        return;

    files.forEach(fn => {
        if (exclude.find(n => n === fn))
            return;
        const f = dirPath + '/' + fn;
        if (fs.statSync(f).isFile())
            fs.unlinkSync(f);
        else
            fs.rmdirSync(f, {recursive: true});
    });
};

module.exports = {
    //mix: mix,
    fs: fs,
    rootPath: rootPath,
    resourcesPath: resourcesPath,
    readdir: readdir,
    js: function (source) {
        const resourcesPath = 'resources/' + source + '/js';
        const publicPath = 'public/' + source + '/js';

        rmdir(publicPath, ['tinymce']);

        readdir(resourcesPath + '/page').forEach(function (entry) {
            const filename = publicPath + entry;
            mix.js(resourcesPath + '/page' + entry, filename);
        });

        return mix;
    },
    sass: function (source) {
        const resourcesPath = 'resources/' + source + '/sass';
        const publicPath = 'public/' + source + '/css';

        rmdir(publicPath, []);

        readdir(resourcesPath + '/page').forEach(function (entry) {
            let a = entry.split('/');
            const name = a.pop();
            const entryPath = a.length ? a.join('/') : '';
            mix.sass(resourcesPath + '/page' + entry, publicPath + entryPath);
        });

        return mix;
    }
};
