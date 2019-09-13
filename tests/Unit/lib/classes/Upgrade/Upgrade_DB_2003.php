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

namespace StudioPress\Genesis\Tests\Unit;

use Brain\Monkey\Functions;
use \StudioPress\Genesis\Upgrade\Upgrade_DB_2003 as Upgrader;

/**
 * Test Upgrade_DB_2003.
 */
class Upgrade_DB_2003_Test extends TestCase {
	/**
	 * Test upgrade method when nav superfish enabled.
	 */
	public function test_upgrade_nav_superfish_enabled() {
		Functions\when( 'genesis_get_option' )->alias(
			function( $arg ) {
				switch ( $arg ) {
					case 'nav_superfish':
						return 1;
					case 'subnav_superfish':
						return 0;
				}
			}
		);

		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'superfish' => 1,
				]
			);

		( new Upgrader() )->upgrade();
	}

	/**
	 * Test upgrade method when subnav superfish enabled.
	 */
	public function test_upgrade_subnav_superfish_enabled() {
		Functions\when( 'genesis_get_option' )->alias(
			function( $arg ) {
				switch ( $arg ) {
					case 'nav_superfish':
						return 0;
					case 'subnav_superfish':
						return 1;
				}
			}
		);

		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'superfish' => 1,
				]
			);

		( new Upgrader() )->upgrade();
	}

	/**
	 * Test upgrade method when superfish is disabled.
	 */
	public function test_upgrade_superfish_disabled() {
		Functions\when( 'genesis_get_option' )->justReturn( 0 );

		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'superfish' => 0,
				]
			);

		( new Upgrader() )->upgrade();
	}
}