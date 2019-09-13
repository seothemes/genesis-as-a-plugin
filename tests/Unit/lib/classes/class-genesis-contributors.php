<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Tests
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

namespace StudioPress\Genesis\Tests\Unit;

use Brain\Monkey\Functions;
use Genesis_Contributors;

/**
 * Test Genesis Contributors class.
 *
 * @group contributors
 */
class Genesis_Contributors_Test extends TestCase {

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before a test is executed.
	 */
	public function setUp() {
		Functions\when( 'get_template_directory' )->justReturn( GENESIS_THEME_DIR );

		parent::setUp();
	}

	/**
	 * Test can find all with no people.
	 *
	 * @group genesiscontributors
	 */
	public function test_genesis_contributors_can_find_all_with_no_people() {
		$contributors = new Genesis_Contributors( [] );

		self::assertEquals( [], $contributors->find_all() );
	}
}