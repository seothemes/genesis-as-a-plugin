// Extends the default `webpack.config.js` from the @wordpress/scripts package.
// - Generate one output file per input file, instead of combining as index.js.
// - Each new input file must be named in the entry object.
// - Change build folder to `lib/js/build`.
//
// Build for production with `npm run build:js`.
// Build for development with `npm start`. (Live reload, source maps, Ctrl-C to exit.)
// Delete all files in `lib/js/build` with `npm run clean:js`.
//
// The WordPress Scripts package generates a dependencies file with the
// extension `.deps.json` that you can use to determine required dependencies
// when enqueueing scripts:
//
// wp_enqueue_script(
//	'example-file',
//	GENESIS_JS_URL . '/build/example-file.js',
//	[ 'wp-polyfill', 'wp-other-dependency' ], // This line from build/example-file.deps.json.
//	PARENT_THEME_VERSION,
//	true
//);
//
// The *.deps.json files are gitignored and do not need to be committed.

const defaultConfig      = require("./node_modules/@wordpress/scripts/config/webpack.config");
const genesisJsRootPath  = __dirname + '/lib/js/';
const genesisJsBuildPath = __dirname + '/lib/js/build/';

module.exports = {
	...defaultConfig,
	entry: {
		'breadcrumbs-toggle': genesisJsRootPath + 'editor/breadcrumbs-toggle.js',
		'title-toggle': genesisJsRootPath + 'editor/title-toggle.js',
		'image-toggle': genesisJsRootPath + 'editor/image-toggle.js',
		'genesis-sidebar': genesisJsRootPath + 'editor/genesis-sidebar.js',
		'layout-toggle': genesisJsRootPath + 'editor/layout-toggle.js',
		'custom-classes': genesisJsRootPath + 'editor/custom-classes.js',
	},
	output: {
		filename: '[name].js',
		path: genesisJsBuildPath,
	},
};
