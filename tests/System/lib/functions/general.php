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

require __DIR__ . '/../../../fixtures/dummies.php';

/**
 * Test general functions.
 *
 * @group functions
 */
class General_Test extends TestCase {
	/**
	 * Test get theme support arguments.
	 */
	public function test_genesis_get_theme_support_arg() {
		// Make sure it exists.
		$this->assertTrue( function_exists( 'genesis_get_theme_support_arg' ), 'genesis_get_theme_support_arg() is not defined!' );
		$feature = 'pet-dolphins';
		$key     = 'with';
		$value   = 'enthusiasm';
		$default = 'sadness';

		// Add our dummy feature.
		add_theme_support(
			$feature,
			[
				$key => $value,
			]
		);

		// Test normal operation.
		$this->assertEquals( $value, genesis_get_theme_support_arg( $feature, $key ) );

		// Test that the default fallback works.
		$this->assertEquals( $default, genesis_get_theme_support_arg( $feature, 'THIS_KEY_DOES_NOT_EXIST', $default ) );

		// Clean up after ourselves.
		remove_theme_support( $feature );
	}

	/**
	 * Test get CPT archive types.
	 */
	public function test_genesis_get_cpt_archive_types() {
		// Should start at zero, for WP core.
		$this->assertCount( 0, genesis_get_cpt_archive_types() );

		$favorite_animal = 'dolphin';

		// But what if we add an archived CPT?
		register_post_type(
			$favorite_animal,
			[
				'public'       => true,
				'show_ui'      => true,
				'show_in_menu' => true,
				'has_archive'  => true,
			]
		);

		// Now we should expect to see one.
		$this->assertCount( 1, genesis_get_cpt_archive_types() );

		// And it should be the one we added.
		$this->assertEquals( [ $favorite_animal ], genesis_get_cpt_archive_types_names() );

		// It shouldn't have genesis-cpt-archives-settings support yet.
		$this->assertFalse( genesis_has_post_type_archive_support( $favorite_animal ) );

		// Add genesis-cpt-archives-settings support.
		add_post_type_support( $favorite_animal, 'genesis-cpt-archives-settings' );

		// And it should now say that it has archive support.
		$this->assertTrue( genesis_has_post_type_archive_support( $favorite_animal ) );

		// Clean up after ourselves.
		unset( $GLOBALS['wp_post_types']['dolphin'] );
	}

	/**
	 * Test the plugin install link.
	 */
	public function test_genesis_plugin_install_link() {
		$expected_url = get_site_url() . '/wp-admin/plugin-install.php?tab=plugin-information&#038;TB_iframe=1&#038;width=600&#038;height=550&#038;plugin=plugin-slug';

		$this->assertEquals(
			'<a href="' . $expected_url . '" class="thickbox">text</a>',
			genesis_plugin_install_link( 'plugin-slug', 'text' )
		);
	}
}