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
 * Test onboarding functions.
 *
 * @group functions
 */
class Onboarding_Test extends TestCase {

	/**
	 * Test that Genesis returns proper onboarding dependencies data.
	 *
	 * @covers genesis_onboarding_plugins
	 */
	public function test_genesis_onboarding_plugins() {
		$config = include GENESIS_THEME_DIR . '/tests/fixtures/test-configs/child/config/onboarding.php';

		Functions\when( 'genesis_get_config' )->justReturn( $config );
		Functions\when( 'get_option' )->justReturn( false );

		$this->assertSame(
			genesis_onboarding_plugins(),
			[
				[
					'name'       => 'Atomic Blocks',
					'slug'       => 'atomic-blocks/atomicblocks.php',
					'public_url' => 'https://atomicblocks.com/',
				],
			]
		);
	}

	/**
	 * Test that `genesis_onboarding_plugins_list()` returns an unordered list of plugin dependencies from a config array.
	 *
	 * @covers ::genesis_onboarding_plugins_list
	 *
	 * @dataProvider data_genesis_onboarding_plugins_list
	 *
	 * @param array  $plugins  The plugin array to test against.
	 * @param string $expected The expected result of genesis_onboarding_plugins_list().
	 */
	public function test_genesis_onboarding_plugins_list( $plugins, $expected ) {
		Functions\when( 'esc_html' )->returnArg();
		Functions\when( 'esc_url' )->returnArg();
		Functions\when( '__' )->returnArg();
		Functions\when( 'genesis_onboarding_plugins' )->justReturn( $plugins );
		Functions\when( 'get_option' )->justReturn( false );

		self::assertSame(
			$expected,
			genesis_onboarding_plugins_list()
		);
	}

	/**
	 * Data provider for testing genesis_onboarding_plugins_list().
	 *
	 * @return array
	 */
	public function data_genesis_onboarding_plugins_list() {
		$list_item_pattern      = '<li>%s</li>';
		$list_item_link_pattern = '<li><a href="%s" target="_blank" rel="noopener noreferrer">%s <span class="screen-reader-text">(new window)</span></a></li>';

		return [
			'return valid plugin list from valid plugin array with 2 plugins' => [
				[
					[
						'name'       => 'ABC',
						'slug'       => 'ab/c.php',
						'public_url' => 'https://ab.c/',
					],
					[
						'name'       => 'XYZ',
						'slug'       => 'xy/z.php',
						'public_url' => 'https://xy.z/',
					],
				],
				'<ul>' . sprintf( $list_item_link_pattern, 'https://ab.c/', 'ABC' ) . sprintf( $list_item_link_pattern, 'https://xy.z/', 'XYZ' ) . '</ul>',
			],
			'return valid plugin list where one plugin has no public_url' => [
				[
					[
						'name'       => 'ABC',
						'slug'       => 'ab/c.php',
						'public_url' => 'https://ab.c/',
					],
					[
						'name' => 'XYZ',
						'slug' => 'xy/z.php',
					],
				],
				'<ul>' . sprintf( $list_item_link_pattern, 'https://ab.c/', 'ABC' ) . sprintf( $list_item_pattern, 'XYZ' ) . '</ul>',
			],
			'return valid plugin list where one plugin has no name' => [
				[
					[
						'slug'       => 'ab/c.php',
						'public_url' => 'https://ab.c/',
					],
					[
						'name' => 'XYZ',
						'slug' => 'xy/z.php',
					],
				],
				'<ul>' . sprintf( $list_item_pattern, 'XYZ' ) . '</ul>',
			],
			'return valid plugin list where one plugin has no slug' => [
				[
					[
						'name'       => 'ABC',
						'public_url' => 'https://ab.c/',
					],
					[
						'name' => 'XYZ',
						'slug' => 'xy/z.php',
					],
				],
				'<ul>' . sprintf( $list_item_pattern, 'XYZ' ) . '</ul>',
			],
			'return nothing from an empty config' => [
				[],
				'',
			],
		];
	}

	/**
	 * Test that Genesis returns proper onboarding content data.
	 *
	 * @covers genesis_onboarding_content
	 */
	public function test_genesis_onboarding_content() {
		$config = include GENESIS_THEME_DIR . '/tests/fixtures/test-configs/child/config/onboarding.php';

		Functions\when( 'genesis_get_config' )->justReturn( $config );
		Functions\when( 'get_option' )->justReturn( false );

		$this->assertSame(
			genesis_onboarding_content(),
			[
				'homepage' => [
					'post_title'     => 'Homepage',
					'post_content'   => 'This is the homepage.',
					'post_excerpt'   => 'This is an excerpt.',
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'page_template'  => 'template-blocks.php',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
				],
				'blog'     => [
					'post_title'   => 'Blog',
					'post_content' => '',
					'post_type'    => 'page',
					'post_status'  => 'publish',
				],
				'about'    => [
					'post_title'     => 'About Us',
					'post_content'   => 'This is an about us page.',
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
				],
				'howdy'    => [
					'post_title'     => 'Howdy',
					'post_content'   => 'Howdy, partner!',
					'post_excerpt'   => 'This is the excerpt.',
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'featured_image' => 'https://preview.arraythemes.com/atomic/wp-content/uploads/sites/36/2017/03/service2-1600x600.jpg',
				],
			]
		);
	}

	/**
	 * Test that Genesis returns proper onboarding navigation menu data.
	 *
	 * @covers genesis_onboarding_navigation_menus
	 */
	public function test_genesis_onboarding_navigation_menus() {
		$config = include GENESIS_THEME_DIR . '/tests/fixtures/test-configs/child/config/onboarding.php';

		Functions\when( 'genesis_get_config' )->justReturn( $config );
		Functions\when( 'get_option' )->justReturn( false );

		$this->assertSame(
			genesis_onboarding_navigation_menus(),
			[
				'primary'   => [
					'homepage' => [
						'title' => 'Home',
					],
					'about'    => [
						'title' => 'About Us',
					],
					'howdy'    => [
						'title'  => 'Howdy Partner',
						'parent' => 'about',
					],
				],
				'secondary' => [
					'homepage' => [
						'title' => 'Home',
					],
					'about'    => [
						'title'  => 'About Us',
						'parent' => 'homepage',
					],
				],
			]
		);
	}
}