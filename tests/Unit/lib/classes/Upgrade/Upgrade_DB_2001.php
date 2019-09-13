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
use \StudioPress\Genesis\Upgrade\Upgrade_DB_2001 as Upgrader;

/**
 * Test Upgrade_DB_2001.
 */
class Upgrade_DB_2001_Test extends TestCase {
	/**
	 * Test upgrade method when nav extras enabled.
	 */
	public function test_upgrade_nav_extras_enabled() {
		Functions\when( 'genesis_get_option' )->alias(
			function( $arg ) {
				switch ( $arg ) {
					case 'nav_extras_enable':
						return 1;
					case 'nav_extras':
						return 'something';
				}
			}
		);

		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'nav_extras' => 'something',
				]
			);

		$upgrader = new Upgrader();
		$upgrader->upgrade();
	}

	/**
	 * Test upgrade method when nav extras disabled.
	 */
	public function test_upgrade_nav_extras_disabled() {
		Functions\when( 'genesis_get_option' )->alias(
			function( $arg ) {
				switch ( $arg ) {
					case 'nav_extras_enable':
						return 0;
					case 'nav_extras':
						return 'something';
				}
			}
		);

		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'nav_extras' => '',
				]
			);

		( new Upgrader() )->upgrade();
	}
}
