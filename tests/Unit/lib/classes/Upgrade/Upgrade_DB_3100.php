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
use \StudioPress\Genesis\Upgrade\Upgrade_DB_3100 as Upgrader;

/**
 * Test Upgrade_DB_3100.
 */
class Upgrade_DB_3100_Test extends TestCase {
	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 */
	public function setUp() {
		// I have no idea why this is necessary, but the tests error out if I remove it.
		require_once GENESIS_THEME_DIR . '/lib/classes/Upgrade/Upgrade_DB_3100.php';

		Functions\when( 'get_option' )->justReturn( '' );

		parent::setUp();
	}

	/**
	 * Test to ensure specified SEO settings get unset during upgrade.
	 */
	public function test_upgrade_seo_settings_change() {
		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'noodp'  => 'unset',
					'noydir' => 'unset',
				],
				GENESIS_SEO_SETTINGS_FIELD
			);

		( new Upgrader() )->unset_noodp_noydir();
	}

	/**
	 * Test adding footer setting (without any fallback text).
	 */
	public function test_upgrade_footer_with_no_fallback() {
		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'footer_text' => sprintf( '[footer_copyright before="%s "] · [footer_childtheme_link before="" after=" %s"] [footer_genesis_link url="https://www.studiopress.com/" before=""] · [footer_wordpress_link] · [footer_loginout]', __( 'Copyright', 'genesis' ), __( 'on', 'genesis' ) ),
				]
			);

		( new Upgrader() )->create_footer_setting();
	}

	/**
	 * Test adding footer setting (with Simple Edits).
	 */
	public function test_genesis_upgrade_3100_with_simple_edits() {
		define( 'GSE_SETTINGS_FIELD', 'gse-settings' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound -- Mocking this for testing
		Functions\when( 'genesis_get_option' )->justReturn( 'Simple Edits Footer' );
		Functions\expect( 'genesis_update_settings' )
				->once()
				->with(
					[
						'footer_text' => 'Simple Edits Footer',
					]
				);

		( new Upgrader() )->create_footer_setting();
	}
	/**
	 * Test adding footer setting (with filtered footer creds text).
	 */
	public function test_genesis_upgrade_3100_with_footer_creds_filter() {
		Functions\when( 'apply_filters' )->justReturn( 'Filtered Footer' );
		Functions\expect( 'genesis_update_settings' )
				->once()
				->with(
					[
						'footer_text' => 'Filtered Footer',
					]
				);

		( new Upgrader() )->create_footer_setting();
	}
}
