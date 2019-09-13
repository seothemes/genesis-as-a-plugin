<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package StudioPress\Genesis\Tests\System
 * @author  XWP, Google, StudioPress, and contributors
 * @license GPL-2.0-or-later
 * @link    https://github.com/studiopress/genesis-amp
 */

namespace StudioPress\Genesis\Tests\System;

use Brain\Monkey\Functions;
use StudioPress\Genesis\Upgrade\Upgrade_DB_1800 as Upgrader;

/**
 * Test Upgrade_DB_1800.
 */
class Upgrade_DB_1800_Test extends TestCase {
	/**
	 * Test that the upgrade updates the settings.
	 */
	public function test_convert_term_meta() {
		$this->markTestIncomplete( 'Needs examples of terms and term meta to check they are manipulated correctly.' );

		( new Upgrader() )->convert_term_meta();
	}
}