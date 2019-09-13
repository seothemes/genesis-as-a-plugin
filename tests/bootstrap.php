<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Studiopress\Genesis
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

use Brain\Monkey;
use Brain\Monkey\Functions;

genesis_tests_bootstrap_system();

/**
 * Set up and run WordPress testing environment with Genesis as the active theme.
 *
 * @since 2.6.0
 */
function genesis_tests_bootstrap_system() {
	define( 'GENESIS_THEME_DIR', dirname( __DIR__ ) );

	// Require patchwork early so that functions can be monkey patched in Unit tests.
	require GENESIS_THEME_DIR . '/vendor/antecedent/patchwork/Patchwork.php';

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
	define( 'WP_CONTENT_DIR', dirname( dirname( GENESIS_THEME_DIR ) ) );

	$genesis_wp_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $genesis_wp_tests_dir ) {
		$genesis_wp_tests_dir = '/tmp/wordpress-tests-lib';
	}

	// Give access to tests_add_filter() function.
	require_once $genesis_wp_tests_dir . '/includes/functions.php';

	tests_add_filter(
		'setup_theme',
		static function () {
			register_theme_directory( dirname( GENESIS_THEME_DIR ) );
			switch_theme( basename( GENESIS_THEME_DIR ) );
		}
	);

	// Start up the WP testing environment.
	require $genesis_wp_tests_dir . '/includes/bootstrap.php';
	require GENESIS_THEME_DIR . '/vendor/autoload.php';
}
