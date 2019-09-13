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
use \StudioPress\Genesis\Upgrade\Upgrade_DB_160 as Upgrader;

/**
 * Test Upgrade_DB_160.
 */
class Upgrade_DB_160_Test extends TestCase {
	/**
	 * Test upgrade method when nav type is not `nav_menu`.
	 */
	public function test_upgrade_nav_type_not_nav_menu() {
		Functions\when( 'genesis_get_option' )->justReturn( 'anything_else' );

		Functions\expect( '_genesis_vestige' )
			->once()
			->with(
				[
					'nav_type',
					'nav_superfish',
					'nav_home',
					'nav_pages_sort',
					'nav_categories_sort',
					'nav_depth',
					'nav_exclude',
					'nav_include',
				]
			);

		Functions\expect( '_genesis_vestige' )
			->once()
			->with(
				[
					'subnav_type',
					'subnav_superfish',
					'subnav_home',
					'subnav_pages_sort',
					'subnav_categories_sort',
					'subnav_depth',
					'subnav_exclude',
					'subnav_include',
				]
			);

		( new Upgrader() )->upgrade();
	}

	/**
	 * Test upgrade method when nav type is `nav_menu`.
	 */
	public function test_upgrade_nav_type_is_nav_menu() {
		Functions\when( 'genesis_get_option' )->justReturn( 'nav-menu' );

		Functions\expect( '_genesis_vestige' )->never();

		( new Upgrader() )->upgrade();
	}
}
