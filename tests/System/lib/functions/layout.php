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

namespace StudioPress\Genesis\Tests\System;

require_once PARENT_DIR . '/lib/functions/layout.php';

/**
 * Test layout functions.
 *
 * @group functions
 */
class Layout_Test extends TestCase {

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp() {
		parent::setUp();
		remove_theme_support( 'genesis-structural-wraps' );
	}
	/**
	 * Test genesis_get_structural_wrap() returns null when no theme support for genesis_structural_wraps is added.
	 */
	public function test_genesis_get_structural_wrap_returns_null_when_no_theme_support() {
		self::assertNull( genesis_get_structural_wrap() );
	}

	/**
	 * Test genesis_get_structural_wrap() returns an empty string when context is not in the added genesis_structural_wraps.
	 */
	public function test_genesis_get_structural_wrap_returns_empty_string_when_no_theme_support_for_context() {
		add_theme_support( 'genesis-structural-wraps' );

		self::assertEmpty( genesis_get_structural_wrap() );
		self::assertEmpty( genesis_get_structural_wrap( 'genesis-rocks' ) );
	}

	/**
	 * Test genesis_get_structural_wrap() maps old contexts to new contexts.
	 */
	public function test_genesis_get_structural_wrap_maps_old_contexts_to_new_contexts() {
		add_theme_support( 'genesis-structural-wraps', [ 'nav', 'subnav', 'inner' ] );

		$map = [
			'nav'    => 'menu-primary',
			'subnav' => 'menu-secondary',
			'inner'  => 'site-inner',
		];

		foreach ( $map as $key => $value ) {
			self::assertEquals( '<div class="wrap">', genesis_get_structural_wrap( $key ) );
			self::assertEquals( '<div class="wrap">', genesis_get_structural_wrap( $value ) );
			self::assertEquals( '</div>', genesis_get_structural_wrap( $key, 'close' ) );
			self::assertEquals( '</div>', genesis_get_structural_wrap( $value, 'close' ) );
		}

		self::assertNotEquals( '<div class="wrap">', genesis_get_structural_wrap( 'genesis-rocks' ) );
	}

	/**
	 * Test genesis_get_structural_wrap() wraps custom contexts.
	 */
	public function test_genesis_get_structural_wrap_wraps_custom_contexts() {
		add_theme_support( 'genesis-structural-wraps', [ 'genesis-header', 'genesis-rocks' ] );

			self::assertEquals( '<div class="wrap">', genesis_get_structural_wrap( 'genesis-header' ) );
			self::assertEquals( '<div class="wrap">', genesis_get_structural_wrap( 'genesis-rocks' ) );
			self::assertEquals( '</div>', genesis_get_structural_wrap( 'genesis-header', 'close' ) );
			self::assertEquals( '</div>', genesis_get_structural_wrap( 'genesis-rocks', 'close' ) );
	}

	/**
	 * Test genesis_structural_wrap() throws deprecated message when third parameter is set to truthy.
	 */
	public function test_genesis_structural_wrap_shows_deprecated_message_when_third_parameter_is_true() {
		self::setExpectedDeprecated( 'genesis_structural_wrap' );

		genesis_structural_wrap( 'menu-primary', 'open', true );
		genesis_structural_wrap( 'menu-primary', 'open', 1 );
		genesis_structural_wrap( 'menu-primary', 'open', 'true' );
	}

	/**
	 * Test genesis_structural_wrap() shows deprecated message when third parameter is falsy.
	 */
	public function test_genesis_structural_wrap_shows_deprecated_message_when_third_parameter_is_falsy() {
		self::setExpectedDeprecated( 'genesis_structural_wrap' );

		self::assertNull( genesis_structural_wrap( 'menu-primary', 'open', false ) );
		self::assertNull( genesis_structural_wrap( 'menu-primary', 'open', 0 ) );
		self::assertNull( genesis_structural_wrap( 'menu-primary', 'open', 'false' ) );
	}

	/**
	 * Test genesis_structural_wrap() shows deprecated message, returns the wraps when third parameter is set to false and theme support is added.
	 */
	public function test_genesis_structural_wrap_shows_deprecated_message_outputs_wraps_when_third_parameter_is_false_and_theme_support_added() {
		self::setExpectedDeprecated( 'genesis_structural_wrap' );
		add_theme_support( 'genesis-structural-wraps', [ 'genesis-header', 'genesis-rocks' ] );

		self::assertEquals( '<div class="wrap">', genesis_structural_wrap( 'genesis-header', 'open', false ) );
		self::assertEquals( '</div>', genesis_structural_wrap( 'genesis-header', 'close', false ) );
		self::assertEquals( '<div class="wrap">', genesis_structural_wrap( 'genesis-rocks', 'open', false ) );
		self::assertEquals( '</div>', genesis_structural_wrap( 'genesis-rocks', 'close', false ) );
	}

	/**
	 * Test genesis_structural_wrap() echoes the opening HTML.
	 */
	public function test_genesis_structural_wrap_echoes_opening_html() {
		add_theme_support( 'genesis-structural-wraps', [ 'genesis-header' ] );

		self::expectOutputString( '<div class="wrap">', genesis_structural_wrap( 'genesis-header' ) );
	}

	/**
	 * Test genesis_structural_wrap() echoes the closing HTML.
	 */
	public function test_genesis_structural_wrap_echoes_closing_html() {
		add_theme_support( 'genesis-structural-wraps', [ 'genesis-header' ] );

		self::expectOutputString( '</div>', genesis_structural_wrap( 'genesis-header', 'close' ) );
	}
}
