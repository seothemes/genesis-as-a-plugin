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

/**
 * Test widget-import.php.
 *
 * @group admin
 * @group functions
 * @group onboarding
 */
class WidgetImport extends TestCase {

	/**
	 * Imported posts sample.
	 *
	 * @var array
	 */
	protected $imported_posts = [
		'homepage' => 123,
		'about'    => 124,
		'contact'  => 125,
	];

	/**
	 * Sets up the environment before each test.
	 */
	public function setUp() {
		parent::setUp();
		require_once PARENT_DIR . '/lib/admin/widget-import.php';
	}

	/**
	 * Tests that StudioPress\Genesis\Admin\WidgetImport\test_swap_placeholders()
	 * replaces placeholder strings with the equivalent IDs if slugs exist
	 * in the imported_posts array.
	 */
	public function test_swap_placeholders() {

		$widget_arguments = [
			'title'           => 'This text should not change',
			'page_id'         => '$imported_posts_about',
			'page_id_bad'     => '$imported_posts_bad_value', // `$this->imported_posts` has no `bad_value` key.
			'show_image'      => 1,
			'image_size'      => 'featured-image',
			'image_alignment' => 'aligncenter',
			'show_title'      => 1,
			'content_limit'   => '',
			'more_text'       => 'This text should be unaffected',
			'dummy_array'     => [ 'test', 'array', 'unaffected' ],
		];

		$expected_widget_arguments = [
			'title'           => 'This text should not change',
			'page_id'         => $this->imported_posts['about'],
			'page_id_bad'     => '', // Placeholder cleared if no imported post with given slug.
			'show_image'      => 1,
			'image_size'      => 'featured-image',
			'image_alignment' => 'aligncenter',
			'show_title'      => 1,
			'content_limit'   => '',
			'more_text'       => 'This text should be unaffected',
			'dummy_array'     => [ 'test', 'array', 'unaffected' ],
		];

		$new_widget_arguments = \StudioPress\Genesis\Admin\WidgetImport\swap_placeholders(
			$widget_arguments,
			$this->imported_posts
		);

		self::assertEquals( $expected_widget_arguments, $new_widget_arguments );
	}

}