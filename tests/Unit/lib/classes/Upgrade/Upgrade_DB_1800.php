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
use \StudioPress\Genesis\Upgrade\Upgrade_DB_1800 as Upgrader;

/**
 * Test Upgrade_DB_1800.
 */
class Upgrade_DB_1800_Test extends TestCase {

	/**
	 * Test that method accurately migrates term name and description as headline and intro text.
	 */
	public function test_convert_term_meta() {
		$sample_term_meta = [
			1 => [
				'display_title'       => 1,
				'display_description' => 1,
			],
			2 => [
				'display_title'       => 1,
				'display_description' => 1,
			],
		];

		$sample_term_1              = new \stdClass();
		$sample_term_1->term_id     = 1;
		$sample_term_1->name        = 'Sample Term 1';
		$sample_term_1->description = 'Sample Term 1 description';

		$sample_term_2              = new \stdClass();
		$sample_term_2->term_id     = 2;
		$sample_term_2->name        = 'Sample Term 2';
		$sample_term_2->description = 'Sample Term 2 description';

		Functions\when( 'get_option' )->justReturn( $sample_term_meta );
		Functions\when( 'get_taxonomies' )->justReturn();
		Functions\when( 'get_terms' )->justReturn(
			[
				$sample_term_1,
				$sample_term_2,
			]
		);

		Functions\expect( 'update_option' )
			->once()
			->with(
				'genesis-term-meta',
				[
					1 => [
						'display_title'       => 1,
						'display_description' => 1,
						'headline'            => $sample_term_1->name,
						'intro_text'          => $sample_term_1->description,
					],
					2 => [
						'display_title'       => 1,
						'display_description' => 1,
						'headline'            => $sample_term_2->name,
						'intro_text'          => $sample_term_2->description,
					],
				]
			);

		( new Upgrader() )->convert_term_meta();
	}

}