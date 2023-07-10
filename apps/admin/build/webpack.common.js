const {EnvironmentPlugin} = require('webpack');
const mix = require('laravel-mix');
const fs = require('fs');
const glob = require('glob');
const path = require('path');

const rootPath = process.env.PWD;
const resourcesPath = rootPath + '/resources';
/*mix.autoload({
	jquery: ['$', 'window.jQuery']
});*/

/*
 |--------------------------------------------------------------------------
 | Configure mix
 |--------------------------------------------------------------------------
 */
mix.options({
	resourceRoot: process.env.ASSET_URL || undefined,
	processCssUrls: false,
	postCss: [require('autoprefixer')],
	//manifest: false,
	terser: {
		extractComments: false,
	}
});

mix.alias({
	//'@core': resourcesPath + '/core/js',

	'~fonts': resourcesPath + '/sass/common/fonts',
	'~variables': resourcesPath + '/sass/variables',
	'~mixins': resourcesPath + '/sass/abstracts/mixins',
});

/*
 |--------------------------------------------------------------------------
 | Configure Webpack
 |--------------------------------------------------------------------------
 */

mix.webpackConfig({
	output: {
		publicPath: process.env.ASSET_URL || undefined,
		libraryTarget: 'window'
	},
	plugins: [
		new EnvironmentPlugin({
			// Application's public url
			BASE_URL: process.env.ASSET_URL ? `${process.env.ASSET_URL}/` : '/'
		})
	],
	module: {
		rules: [
			{
				test: /\.es6$|\.js$/,
				include: [
					path.join(__dirname, 'node_modules/bootstrap/'),
					path.join(__dirname, 'node_modules/popper.js/'),
					path.join(__dirname, 'node_modules/shepherd.js/')
				],
				loader: 'babel-loader',
				options: {
					presets: [['@babel/preset-env', {targets: 'last 2 versions, ie >= 10'}]],
					plugins: [
						'@babel/plugin-transform-destructuring',
						'@babel/plugin-proposal-object-rest-spread',
						'@babel/plugin-transform-template-literals'
					],
					babelrc: false
				}
			}
		]
	},
	externals: {
		jquery: 'jQuery',
		moment: 'moment',
		'datatables.net': '$.fn.dataTable',
		jsdom: 'jsdom',
		velocity: 'Velocity',
		hammer: 'Hammer',
		pace: '"pace-progress"',
		chartist: 'Chartist',
		'popper.js': 'Popper',

		// blueimp-gallery plugin
		'./blueimp-helper': 'jQuery',
		'./blueimp-gallery': 'blueimpGallery',
		'./blueimp-gallery-video': 'blueimpGallery'
	}
});

mix.alias({});

/*
 |--------------------------------------------------------------------------
 | Vendor assets
 |--------------------------------------------------------------------------
 */
const readdir = function (dir, path = '') {
	const readPath = dir + path;
	let files = [];
	fs.readdirSync(readPath)
		.forEach(entry => {
			const stat = fs.statSync(readPath + '/' + entry);
			if (stat.isFile()) {
				if (entry.charAt(0) === '_') {
					return;
				}
				files.push(path + '/' + entry);
				return files;
			} else if (stat.isDirectory()) {
				readdir(dir, path + '/' + entry).forEach(f => files.push(f));
			}
		});
	return files;
};

const rmdir = function (dirPath, exclude) {
	const files = fs.readdirSync(dirPath);
	if (files.length === 0) {
		return;
	}

	files.forEach(fn => {
		if (exclude.find(n => n === fn)) {
			return;
		}
		const f = dirPath + '/' + fn;
		if (fs.statSync(f).isFile()) {
			fs.unlinkSync(f);
		} else {
			fs.rmdirSync(f, {recursive: true});
		}
	});
};

module.exports = {
	mix: mix,
	fs: fs,
	rootPath: rootPath,
	resourcesPath: resourcesPath,
	readdir: readdir,
	js: function () {
		const resourcesPath = 'resources/js';
		const publicPath = 'public/js';

		if (!fs.existsSync(publicPath)) {
			fs.mkdirSync(publicPath);
		}

		rmdir(publicPath, ['tinymce']);

		readdir(resourcesPath + '/pages').forEach(function (entry) {
			const filename = publicPath + entry;
			mix.js(resourcesPath + '/pages' + entry, filename);
		});

		return mix;
	},
	sass: function () {
		const resourcesPath = 'resources/sass';
		const publicPath = 'public/css';

		if (!fs.existsSync(publicPath)) {
			fs.mkdirSync(publicPath);
		}

		rmdir(publicPath, []);

		readdir(resourcesPath + '/pages').forEach(function (entry) {
			let a = entry.split('/');
			const name = a.pop();
			const entryPath = a.length ? a.join('/') : '';
			mix.sass(resourcesPath + '/pages' + entry, publicPath + entryPath);
		});

		return mix;
	},
	mixAssets: function (source, query, cb) {
		return mixAssetsDir(source, query, cb);
	}
};