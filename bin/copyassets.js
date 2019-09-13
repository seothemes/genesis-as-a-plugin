#!/usr/bin/env node

// This script copies external JavaScript libraries to the correct location.
// It can be run with `npm run copyassets`.

const fs = require( 'fs' );

const files = [
	{
		src: 'node_modules/superfish/dist/js/superfish.js',
		dest: 'lib/js/menu/superfish.js'
	},
	{
		src: 'node_modules/superfish/dist/js/superfish.js',
		dest: 'lib/js/menu/superfish.js'
	}
];

files.forEach( function( file ) {
	console.log( 'Copying ' + file.src + ' to ' + file.dest );
	fs.copyFile( file.src, file.dest, function( err ) {
		if ( err ) {
			throw err;
		}
	} );
} );
