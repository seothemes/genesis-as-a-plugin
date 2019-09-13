<?php
/**
 * Plugin Name: Genesis Framework
 * Plugin URI:  https://github.com/seothemes/genesis-config-exporter
 * Description: WP CLI command to export config file for child theme.
 * Version:     1.0.0
 * Author:      SEO Themes
 * Author URI:  https://seothemes.com/
 * License:     GPL-2.0-or-later
 * Text Domain: genesis-config-exporter
 */


/**
 * Calls the init.php file, but only if the child theme has not called it first.
 *
 * This method allows the child theme to load
 * the framework so it can use the framework
 * components immediately.
 */

define( 'GENESIS_FILE', __FILE__ );
define( 'GENESIS_DIR', __DIR__ );

add_action( 'setup_theme', function () {

	require_once __DIR__ . '/lib/init.php';

}, 15 );
