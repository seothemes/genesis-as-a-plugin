<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package StudioPress\Genesis
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

/**
 * Manage Genesis Framework database via cli.
 */
class Genesis_Cli_Db_Command {
	/**
	 * Upgrade the database settings for Genesis, usually after an update.
	 *
	 * ## DB
	 *
	 * ## EXAMPLES
	 *
	 *  $ wp genesis db upgrade
	 *  Success: Genesis database upgraded.
	 *
	 * @subcommand upgrade
	 *
	 * @since 2.10.0
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Stores all the arguments defined like --key=value or --flag or --no-flag.
	 */
	public function upgrade( $args, $assoc_args ) {

		// Disable post-upgrade redirect.
		remove_action( 'genesis_upgrade', 'genesis_upgrade_redirect' );

		// Call the upgrade function.
		genesis_upgrade();

		WP_CLI::success( __( 'Genesis database upgraded.', 'genesis' ) );

	}

	/**
	 * Show current Genesis database version
	 *
	 * ## DB
	 *
	 * ## EXAMPLES
	 *
	 *  $ wp genesis db version
	 *  Success: Version 2.10.0
	 *
	 * @subcommand version
	 *
	 * @since 2.10.0
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Stores all the arguments defined like --key=value or --flag or --no-flag.
	 */
	public function version( $args, $assoc_args ) {

		WP_CLI::log( PARENT_DB_VERSION );

	}
}