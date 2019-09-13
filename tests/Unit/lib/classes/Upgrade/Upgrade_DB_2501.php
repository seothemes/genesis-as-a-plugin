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
use \StudioPress\Genesis\Upgrade\Upgrade_DB_2501 as Upgrader;

/**
 * Test Upgrade_DB_2501.
 */
class Upgrade_DB_2501_Test extends TestCase {
	/**
	 * Test upgrade method when semantic headings is true.
	 */
	public function test_upgrade_semantic_headings_true() {
		Functions\when( 'genesis_get_seo_option' )->justReturn( 1 );
		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'semantic_headings' => 1,
				],
				GENESIS_SEO_SETTINGS_FIELD
			);

		( new Upgrader() )->upgrade();
	}

	/**
	 * Test upgrade method when semantic headings is false.
	 */
	public function test_upgrade_semantic_headings_false() {
		Functions\when( 'genesis_get_seo_option' )->justReturn( 0 );
		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'semantic_headings' => 0,
				],
				GENESIS_SEO_SETTINGS_FIELD
			);

		( new Upgrader() )->upgrade();
	}
}