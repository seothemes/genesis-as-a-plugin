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

/**
 * Test post author shortcodes.
 *
 * @group shortcodes
 * @group post-author-shortcodes
 */
class Post_Author_Test extends TestCase {
	/**
	 * Test label.
	 */
	const TEST_LABEL = 'TEST_LABEL_123';

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before a test is executed.
	 */
	public function setUp() {
		parent::setUp();
		// Create a new user.
		$user_id = $this->factory->user->create(
			[
				'role' => 'author',
			]
		);
		// Give them a URL.
		wp_update_user(
			[
				'ID'       => $user_id,
				'user_url' => 'https://wordpress.org/',
			]
		);
		// Create a post for them.
		$post_id = $this->factory->post->create(
			[
				'post_author' => $user_id,
				'post_status' => 'publish',
			]
		);
		$query   = new \WP_Query();
		$query->query(
			[
				'p' => $post_id,
			]
		);
		// And make it the current one in the loop.
		$query->the_post();
		// This setup needs to be done as post author shortcodes now check to see if the current post type has post
		// support for `author`.
	}

	/**
	 * Adds a 'before' attribute to an attribute array.
	 *
	 * @param array $atts An array of attributes.
	 */
	public function add_test_before( $atts ) {
		$atts['before'] = self::TEST_LABEL;
		return $atts;
	}

	/**
	 * Tests the `post_author` shortcode attribute filtering.
	 *
	 * @covers genesis_post_author_shortcode
	 */
	public function test_post_author_shortcode_attribute_filtering() {
		$this->assertTrue( function_exists( 'genesis_post_author_shortcode' ) );
		add_filter( 'shortcode_atts_post_author', [ $this, 'add_test_before' ] );
		$out = genesis_post_author_shortcode(
			[
				'before' => 'should not pass through',
			]
		);
		$this->assertContains( self::TEST_LABEL, $out );
		remove_filter( 'shortcode_atts_post_author', [ $this, 'add_test_before' ] );
	}

	/**
	 * Tests `post_author` shortcode HTML classes.
	 *
	 * @covers genesis_post_author_shortcode
	 */
	public function test_post_author_shortcode_classes() {
		$this->html(
			function() {
				$html5 = genesis_post_author_shortcode( [] );
				foreach ( [ 'entry-author', 'entry-author-name' ] as $class ) {
					$this->assertContainsClass( $class, $html5 );
				}
			}
		);
	}

	/**
	 * Tests the `post_author_link` shortcode attribute filtering.
	 *
	 * @covers genesis_post_author_link_shortcode
	 */
	public function test_post_author_link_shortcode_attribute_filtering() {
		$this->assertTrue( function_exists( 'genesis_post_author_link_shortcode' ) );
		add_filter( 'shortcode_atts_post_author_link', [ $this, 'add_test_before' ] );
		$out = genesis_post_author_link_shortcode(
			[
				'before' => 'should not pass through',
			]
		);
		$this->assertContains( self::TEST_LABEL, $out );
		remove_filter( 'shortcode_atts_post_author_link', [ $this, 'add_test_before' ] );
	}

	/**
	 * Tests `post_author_link` shortcode HTML classes.
	 *
	 * @covers genesis_post_author_link_shortcode
	 */
	public function test_post_author_link_shortcode_classes() {
		$this->html(
			function() {
				$html5 = genesis_post_author_link_shortcode( [] );
				foreach ( [ 'entry-author-link', 'entry-author-name' ] as $class ) {
					$this->assertContainsClass( $class, $html5 );
				}
			}
		);
	}

	/**
	 * Tests the `post_author_posts_link` shortcode attribute filtering.
	 *
	 * @covers genesis_post_author_posts_link_shortcode
	 */
	public function test_post_author_posts_link_shortcode_attribute_filtering() {
		$this->assertTrue( function_exists( 'genesis_post_author_posts_link_shortcode' ) );
		add_filter( 'shortcode_atts_post_author_posts_link', [ $this, 'add_test_before' ] );
		$out = genesis_post_author_posts_link_shortcode(
			[
				'before' => 'should not pass through',
			]
		);
		$this->assertContains( self::TEST_LABEL, $out );
		remove_filter( 'shortcode_atts_post_author_posts_link', [ $this, 'add_test_before' ] );
	}

	/**
	 * Tests `post_author_posts_link` shortcode HTML classes.
	 *
	 * @covers genesis_post_author_posts_link_shortcode
	 */
	public function test_post_author_posts_link_shortcode_classes() {
		$this->html(
			function() {
				$html5 = genesis_post_author_posts_link_shortcode( [] );
				foreach ( [ 'entry-author-link', 'entry-author-name' ] as $class ) {
					$this->assertContainsClass( $class, $html5 );
				}
			}
		);
	}
}