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
use \StudioPress\Genesis\Upgrade\Upgrade_DB_2603 as Upgrader;

/**
 * Test Upgrade_DB_2603.
 */
class Upgrade_DB_2603_Test extends TestCase {
	/**
	 * Test unslashing post meta scripts.
	 */
	public function test_unslash_post_meta_scripts() {
		global $wpdb;
		$real_wpdb = $wpdb;

		// Simple post object.
		$sample_post          = new \stdClass();
		$sample_post->post_id = 1;

		//phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Mocking $wpdb.
		$wpdb           = \Mockery::mock( 'wpdb' );
		$wpdb->postmeta = 'wp_postmeta';

		$wpdb->shouldReceive( 'get_results' )
			->once()
			->with(
				"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_genesis_scripts'"
			)
			->andReturn(
				[
					$sample_post,
				]
			);

		$wpdb->shouldReceive( 'get_results' )
			->once()
			->with(
				"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_genesis_scripts_body'"
			)
			->andReturn(
				[
					$sample_post,
				]
			);

		Functions\when( 'get_post_meta' )->justReturn( 'some post meta from the db' );

		Functions\expect( 'update_post_meta' )
			->once()
			->with(
				1,
				'_genesis_scripts',
				'some post meta from the db'
			);

		Functions\expect( 'update_post_meta' )
			->once()
			->with(
				1,
				'_genesis_scripts_body',
				'some post meta from the db'
			);

		( new Upgrader() )->unslash_post_meta_scripts();

		//phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Restore $wpdb.
		$wpdb = $real_wpdb;
	}

	/**
	 * Test unslashing header/footer scipts.
	 */
	public function test_unslash_header_footer_scripts() {
		// We grab some values from the database.
		Functions\expect( 'genesis_get_option' )->once()->with( 'header_scripts' )->andReturn( 'header script value' );
		Functions\expect( 'genesis_get_option' )->once()->with( 'footer_scripts' )->andReturn( 'footer script value' );

		// Those values are passed through stripslashes().
		Functions\expect( 'stripslashes' )->once()->with( 'header script value' )->andReturn( 'stripped header script value' );
		Functions\expect( 'stripslashes' )->once()->with( 'footer script value' )->andReturn( 'stripped footer script value' );

		// And sent back to the database.
		Functions\expect( 'genesis_update_settings' )
			->once()
			->with(
				[
					'header_scripts' => 'stripped header script value',
					'footer_scripts' => 'stripped footer script value',
				]
			);

		( new Upgrader() )->unslash_header_footer_scripts();
	}
}
