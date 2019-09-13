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

/**
 * Test deprecated functions.
 *
 * @group functions
 * @group deprecated
 */
class Deprecated extends TestCase {

	/**
	 * Test Genesis contributors array.
	 *
	 * @group contributors
	 */
	public function test_genesis_contributors() {
		Functions\expect( '_deprecated_function' )->with( 'genesis_contributors', '2.5.0', 'Genesis_Contributors::find_contributors' )->once();
		$contributors = genesis_contributors();

		self::assertGreaterThan( 1, count( $contributors ), 'If there are not at least two contributors for a Genesis cycle, something is wrong.' );

		// Loop through and perform tests on each contributor.
		array_walk(
			$contributors,
			static function ( $c ) {
				self::assertTrue( is_array( $c ), 'One of the contributors is not an array' );
				self::assertArrayHasKey( 'name', $c, 'One of the contributors lacks a name' );
				self::assertArrayHasKey( 'url', $c, 'One of the contributors lacks a URL' );
				self::assertArrayHasKey( 'gravatar', $c, 'One of the contributors lacks a Gravatar' );
				self::assertRegExp( '#^https?://#', $c['url'], 'A contributor URL looks wrong' );
				self::assertRegExp( '#^https://0\.gravatar\.com/avatar/[a-f0-9]{32}\?s=120#', $c['gravatar'], 'A contributor Gravatar URL is malformed' );
			}
		);
	}

	/**
	 * Test if version string is considered a major version under Genesis rules.
	 *
	 * @group upgraded
	 * @dataProvider version_strings
	 *
	 * @param string $version          Version string to test.
	 * @param bool   $is_major_version True if `$version` should is considered to be major version, false otherwise.
	 */
	public function test_genesis_is_major_version( $version, $is_major_version ) {
		Functions\when( '_deprecated_function' )->justReturn();
		self::assertSame( $is_major_version, \genesis_is_major_version( $version ) );
	}

	/**
	 * Data provider for testing if version strings are considered a major version under Genesis rules.
	 *
	 * @return array
	 */
	public function version_strings() {
		return [
			[ '0.9.0', true ],
			[ '1.0.0', true ],
			[ '1.0.1', false ],
			[ '1.1.0', true ],
			[ '1.1.1', false ],
			[ '1.0.10', false ],
			[ '1.0.0-dev', false ],
			[ '1.0.0-beta', false ],
		];
	}
}