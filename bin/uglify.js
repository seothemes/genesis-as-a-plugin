#!/usr/bin/env node

const UglifyJS = require( 'uglify-js' );
const fs       = require( 'fs' );

const path  = 'lib/js/';

const files = [
	'admin.js',
	'drop-down-menu.js',
	'skip-links.js',
	'menu/superfish.args.js',
	'menu/responsive-menus.js'
];

files.forEach( function( file ) {
	let source  = fs.readFileSync( path + file, "utf8" );
	let result  = UglifyJS.minify( { file: source }, { mangle: false } ).code;
	let fileMin = file.replace( '.js', '.min.js' );

	console.log( 'Compressing ' + path + file + ' to ' + path + fileMin );

	fs.writeFileSync(
		path + fileMin,
		result,
		'utf-8'
	);
} );
