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

namespace StudioPress\Genesis\Tests\Unit;

use Brain\Monkey\Functions;
use Mockery;

/**
 * Test formatting functions.
 *
 * @group functions
 */
class Formatting extends TestCase {

	/**
	 * Test that genesis_strip_attr() correctly strips attributes from an HTML element.
	 *
	 * @covers ::genesis_strip_attr
	 *
	 * @dataProvider data_genesis_strip_attr
	 *
	 * @param string       $expected   Expected string after elements have been stripped.
	 * @param string       $text       A string of HTML formatted code.
	 * @param array|string $elements   Elements that $attributes should be stripped from.
	 * @param array|string $attributes Attributes that should be stripped from $elements.
	 * @param bool         $two_passes Whether the function should allow two passes.
	 */
	public function test_genesis_strip_attr( $expected, $text, $elements, $attributes, $two_passes = true ) {
		$stripped_string = genesis_strip_attr( $text, $elements, $attributes, $two_passes );
		self::assertEquals( $expected, $stripped_string );
	}

	/**
	 * Data Provider for testing genesis_strip_attr().
	 *
	 * @return array
	 */
	public function data_genesis_strip_attr() {
		return [
			'strip single attribute from single element that has single attribute' => [
				'<div><span class="my-class" id="the-span"></span></div>',
				'<div id="the-div"><span class="my-class" id="the-span"></span></div>',
				'div',
				'id',
			],
			'strip single attribute from single element that has multiple attributes' => [
				'<div class="my-class"><span class="my-class" id="the-span"></span></div>',
				'<div class="my-class" id="the-div"><span class="my-class" id="the-span"></span></div>',
				'div',
				'id',
			],
			'strip single attribute from multiple elements' => [
				'<div class="my-class"><span class="my-class"></span></div>',
				'<div class="my-class" id="the-div"><span class="my-class" id="the-span"></span></div>',
				[ 'div', 'span' ],
				'id',
			],
			'strip multiple attributes from multiple elements' => [
				'<div><span></span></div>',
				'<div class="my-class" id="the-div"><span class="my-class" id="the-span"></span></div>',
				[ 'div', 'span' ],
				[ 'class', 'id' ],
			],
			'strip single attribute from multiple instances of single elements' => [
				'<div class="my-class"><div class="my-inner-class"></div></div>',
				'<div class="my-class" id="the-div"><div class="my-inner-class" id="the-other-div"></div></div>',
				'div',
				'id',
			],
			'strip multiple attributes from multiple instances of multiple elements' => [
				'<div><span></span></div><div><span></span></div>',
				'<div class="my-class" id="the-div"><span class="my-class" id="the-span"></span></div><div class="my-class" id="the-div"><span class="my-class" id="the-span"></span></div>',
				[ 'div', 'span' ],
				[ 'class', 'id' ],
			],
			'strip multiple attributes from multiple instances of multiple incorrectly nested elements' => [
				'<div><span></div></span><span><div></span></div>',
				'<div class="my-class" id="the-div"><span class="my-class" id="the-span"></div></span><span class="my-class" id="the-span"><div class="my-class" id="the-div"></span></div>',
				[ 'div', 'span' ],
				[ 'class', 'id' ],
			],
			'strip invalid attribute from valid element'   => [
				'<a href="https://example.com" rel="noopener">Link Text</a>',
				'<a href="https://example.com" rel="noopener" foo="bar">Link Text</a>',
				'a',
				'foo',
			],
			'strip valid attribute from invalid element'   => [
				'<foo href="https://example.com" rel="noopener"></foo>',
				'<foo href="https://example.com" rel="noopener" class="bar"></foo>',
				'foo',
				'class',
			],
			'strip invalid attribute from invalid element' => [
				'<foo href="https://example.com" rel="noopener"></foo>',
				'<foo href="https://example.com" rel="noopener" foo="bar"></foo>',
				'foo',
				'foo',
			],
			'strip last attribute from empty element with trailing space' => [
				'<meta name="foo" value="bar" />',
				'<meta name="foo" value="bar" id="the-id" />',
				'meta',
				'id',
			],
			'strip last attribute from empty element with no trailing space' => [
				'<meta name="foo" value="bar"/>',
				'<meta name="foo" value="bar" id="the-id"/>',
				'meta',
				'id',
			],
			'strip non-last attribute from empty element with trailing space' => [
				'<meta name="foo" value="bar" />',
				'<meta name="foo" id="the-id" value="bar" />',
				'meta',
				'id',
			],
			'strip non-last attribute from empty element with no trailing space' => [
				'<meta name="foo" value="bar"/>',
				'<meta name="foo" id="the-id" value="bar"/>',
				'meta',
				'id',
			],
			'strip multiple attributes with single quotes from element' => [
				'<a>Link Text</a>',
				'<a href=\'https://example.com\' rel=\'noopener\'>Link Text</a>',
				'a',
				[ 'rel', 'href' ],
			],
			'strip multiple attributes with no quotes from element' => [
				'<a id=my-id>Link Text</a>',
				'<a href=https://example.com id=my-id rel=noopener>Link Text</a>',
				'a',
				[ 'rel', 'href' ],
			],
			'strip multiple attributes with single quotes from empty element with trailing space' => [
				'<meta />',
				'<meta name=\'foo\' value=\'bar\' />',
				'meta',
				[ 'name', 'value' ],
			],
			'strip multiple attributes with no quotes from empty element with trailing space' => [
				'<meta id=my-id />',
				'<meta name=foo id=my-id value=bar />',
				'meta',
				[ 'name', 'value' ],
			],
			'strip multiple attributes with single quotes from empty element with no trailing space' => [
				'<meta/>',
				'<meta name=\'foo\' value=\'bar\'/>',
				'meta',
				[ 'name', 'value' ],
			],
			'strip multiple attributes with no quotes from empty element with no trailing space' => [
				'<meta id=my-id/>',
				'<meta name=foo id=my-id value=bar/>',
				'meta',
				[ 'name', 'value' ],
			],
			'strip nothing when attributes are missing from elements' => [
				'<a href="https://example.com">Link Text</a>',
				'<a href="https://example.com">Link Text</a>',
				'a',
				[ 'name', 'value' ],
			],
			'strip nothing when attributes are missing from empty elements with trailing space' => [
				'<meta id="my-id" />',
				'<meta id="my-id" />',
				'meta',
				[ 'name', 'value' ],
			],
			'strip nothing when attributes are missing from empty elements with no trailing space' => [
				'<meta id="my-id"/>',
				'<meta id="my-id"/>',
				'meta',
				[ 'name', 'value' ],
			],
			'strip attributes from missing elements'       => [
				'<div id="the-div"><span class="my-class" id="the-span"></span></div>',
				'<div id="the-div"><span class="my-class" id="the-span"></span></div>',
				'a',
				'id',
			],
			'strip attribute from element, and not element that has similar name' => [
				'<a href="https://example.com"><abbr id="abbr-id"></abbr></a>',
				'<a href="https://example.com" id="anchor-id"><abbr id="abbr-id"></abbr></a>',
				'a',
				'id',
			],
			'strip single boolean attribute from single element' => [
				'<foo src="">',
				'<foo async src="">',
				'foo',
				'async',
			],
			'strip single boolean attribute from multiple elements' => [
				'<foo src=""><meta><meta/><meta />',
				'<foo async src=""><meta async><meta async/><meta async />',
				[ 'foo', 'meta' ],
				'async',
			],
			'strip mutiple boolean attributes from multiple elements' => [
				'<foo src=""><meta><meta/><meta />',
				'<foo async src="" foo><meta foo async><meta foo async/><meta foo async />',
				[ 'foo', 'meta' ],
				[ 'async', 'foo' ],
			],
			'strip boolean and non-boolean attributes from multiple elements' => [
				'<foo src=""><meta><meta/><meta />',
				'<foo async src="" foo="bar"><meta foo=bar async><meta foo=\'bar\' async/><meta foo="bar" async />',
				[ 'foo', 'meta' ],
				[ 'async', 'foo' ],
			],

			/* phpcs:disable Squiz.PHP.CommentedOutCode.Found
			[ // Strip attribute from an element that has a trailing slash as the final character of a value with no quotes for the final attribute.
				'<a></a>',
				'<a href=https://example.com/></a>',
				'a',
				'href',
			],
			// phpcs:enable Squiz.PHP.CommentedOutCode.Found */
		];
	}

	/**
	 * Test that genesis_code() returns content in code tags.
	 *
	 * @covers ::genesis_code
	 */
	public function test_genesis_code_returns_content_in_code_tags() {
		Functions\when( 'esc_html' )->returnArg();

		$expected = '<code>Foo</code>';

		self::assertEquals( $expected, genesis_code( 'Foo' ) );
	}

	/**
	 * Test that genesis_paged_post_url() returns the permalink when the page number is 1.
	 */
	public function test_returns_permalink_when_page_number_is_1() {
		Functions\expect( 'get_post' )
			->once()
			->with( 22 )
			->andReturn( 22 );
		Functions\expect( 'get_permalink' )
			->once()
			->with( 22 )
			->andReturn( 'https://genesis.test/22' );

		self::assertSame( 'https://genesis.test/22', genesis_paged_post_url( 1, 22 ) );
	}

	/**
	 * Test that genesis_paged_post_url() returns the permalink when the page number is string "1".
	 */
	public function test_returns_permalink_when_page_number_is_string_1() {
		Functions\expect( 'get_post' )
			->once()
			->with( 22 )
			->andReturn( 22 );
		Functions\expect( 'get_permalink' )
			->once()
			->with( 22 )
			->andReturn( 'https://genesis.test/22' );

		self::assertSame( 'https://genesis.test/22', genesis_paged_post_url( '1', 22 ) );
	}

	/**
	 * Test that genesis_paged_post_url() returns paged post permalink when the page number is not 1 and default permalink-structure.
	 */
	public function test_returns_paged_post_permalink_when_page_number_is_not_1_and_default_permalink_structure() {
		Functions\expect( 'get_post' )
			->once()
			->with( 22 )
			->andReturn( 22 );
		Functions\expect( 'get_option' )
			->with( 'permalink_structure' )
			->andReturn( '' );
		Functions\expect( 'get_permalink' )
			->with( 22 )
			->andReturn( 'https://genesis.test/?p=22' );
		Functions\expect( 'add_query_arg' )
			->once()
			->with( 'page', 3, get_permalink( 22 ) )
			->andReturn( 'https://genesis.test/page/3/?p=22' );

		self::assertSame( 'https://genesis.test/page/3/?p=22', genesis_paged_post_url( 3, 22 ) );
	}

	/**
	 * Test that genesis_paged_post_url() returns paged post permalink when page number is not 1, custom permalink structure and page is a draft.
	 */
	public function test_returns_paged_post_permalink_when_page_number_is_not_1_custom_permalink_structure_and_page_draft() {
		Functions\expect( 'get_post' )
			->once()
			->with( 22 )
			->andReturn( $this->get_post( 'draft' ) );
		Functions\expect( 'get_option' )
			->once()
			->ordered()
			->with( 'permalink_structure' )
			->andReturn( '/%postname%/' )
			->andAlsoExpectIt()
			->never()
			->ordered()
			->with( 'show_on_front' )
			->andReturn( 'page' )
			->andAlsoExpectIt()
			->never()
			->ordered()
			->with( 'page_on_front' )
			->andReturn( true );
		Functions\expect( 'get_permalink' )
			->with( 22 )
			->andReturn( 'https://genesis.test/' );
		Functions\expect( 'add_query_arg' )
			->once()
			->with( 'page', 3, get_permalink( 22 ) )
			->andReturn( 'https://genesis.test/page/3/?p=22' );

		self::assertSame( 'https://genesis.test/page/3/?p=22', genesis_paged_post_url( 3, 22 ) );
	}

	/**
	 * Test that genesis_paged_post_url() returns paged post permalink when page number is not 1, custom permalink structure and page is pending.
	 */
	public function test_returns_paged_post_permalink_when_page_number_is_not_1_custom_permalink_structure_and_page_pending() {
		Functions\expect( 'get_post' )
			->once()
			->with( 22 )
			->andReturn( $this->get_post( 'pending' ) );
		Functions\expect( 'get_option' )
			->once()
			->ordered()
			->with( 'permalink_structure' )
			->andReturn( '/%postname%/' )
			->andAlsoExpectIt()
			->never()
			->ordered()
			->with( 'show_on_front' )
			->andReturn( 'page' )
			->andAlsoExpectIt()
			->never()
			->ordered()
			->with( 'page_on_front' )
			->andReturn( true );
		Functions\expect( 'get_permalink' )
			->with( 22 )
			->andReturn( 'https://genesis.test/' );
		Functions\expect( 'add_query_arg' )
			->once()
			->with( 'page', 3, get_permalink( 22 ) )
			->andReturn( 'https://genesis.test/page/3/?p=22' );

		self::assertSame( 'https://genesis.test/page/3/?p=22', genesis_paged_post_url( 3, 22 ) );
	}

	/**
	 * Test that genesis_paged_post_url() returns paged post permalink when page number is not 1, custom permalink structure, page published and page is front page.
	 */
	public function test_returns_paged_post_permalink_when_page_number_is_not_1_custom_permalink_structure_page_published_and_page_front_page() {
		global $wp_rewrite;
		$wp_rewrite                  = Mockery::mock( 'WP_Rewrite' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$wp_rewrite->pagination_base = 'page';

		Functions\expect( 'get_post' )
			->once()
			->with( 22 )
			->andReturn( $this->get_post() );
		Functions\expect( 'get_option' )
			->once()
			->ordered()
			->with( 'permalink_structure' )
			->andReturn( '/%postname%/' )
			->andAlsoExpectIt()
			->once()
			->ordered()
			->with( 'show_on_front' )
			->andReturn( 'page' )
			->andAlsoExpectIt()
			->once()
			->ordered()
			->with( 'page_on_front' )
			->andReturn( true );
		Functions\expect( 'get_permalink' )
			->once()
			->with( 22 )
			->andReturn( 'https://genesis.test/' );
		Functions\expect( 'user_trailingslashit' )
			->once()
			->andReturn( 'page/3/' );

		self::assertSame( 'https://genesis.test/page/3/', genesis_paged_post_url( 3, 22 ) );
	}

	/**
	 * Test that genesis_paged_post_url() returns paged post permalink when page number is not 1, custom permalink structure and show_on_front is post.
	 */
	public function test_returns_paged_post_permalink_when_page_number_is_not_1_custom_permalink_structure_page_published_and_show_on_front_is_post() {
		Functions\expect( 'get_post' )
			->once()
			->with( 22 )
			->andReturn( $this->get_post() );
		Functions\expect( 'get_option' )
			->once()
			->ordered()
			->with( 'permalink_structure' )
			->andReturn( '/%postname%/' )
			->andAlsoExpectIt()
			->once()
			->ordered()
			->with( 'show_on_front' )
			->andReturn( 'post' )
			->andAlsoExpectIt()
			->never()
			->ordered()
			->with( 'page_on_front' );
		Functions\expect( 'get_permalink' )
			->with( 22 )
			->andReturn( 'https://genesis.test/genesis-is-cool' );
		Functions\expect( 'user_trailingslashit' )
			->once()
			->with( 3, 'single_paged' )
			->andReturn( '3/' );

		self::assertSame( 'https://genesis.test/genesis-is-cool/3/', genesis_paged_post_url( 3, 22 ) );
	}

	/**
	 * Test that genesis_paged_post_url() returns paged post permalink when page number is not 1, custom permalink structure and not page_on_front.
	 */
	public function test_returns_paged_post_permalink_when_page_number_is_not_1_custom_permalink_structure_page_published_and_not_page_on_front() {
		Functions\expect( 'get_post' )
			->once()
			->with( 22 )
			->andReturn( $this->get_post() );
		Functions\expect( 'get_option' )
			->once()
			->ordered()
			->with( 'permalink_structure' )
			->andReturn( '/%postname%/' )
			->andAlsoExpectIt()
			->once()
			->ordered()
			->with( 'show_on_front' )
			->andReturn( 'page' )
			->andAlsoExpectIt()
			->once()
			->ordered()
			->with( 'page_on_front' )
			->andReturn( false );
		Functions\expect( 'get_permalink' )
			->with( 22 )
			->andReturn( 'https://genesis.test/genesis-is-cool' );
		Functions\expect( 'user_trailingslashit' )
			->once()
			->with( 3, 'single_paged' )
			->andReturn( '3/' );

		self::assertSame( 'https://genesis.test/genesis-is-cool/3/', genesis_paged_post_url( 3, 22 ) );
	}

	/**
	 * Mock post object.
	 *
	 * @param string $status Mocked post status.
	 * @return object Mocked post object.
	 */
	private function get_post( $status = 'publish' ) {
		return (object) [
			'ID'          => 22,
			'post_type'   => 'page',
			'post_name'   => 'genesis-is-cool',
			'post_status' => $status,
		];
	}

	/**
	 * Test that genesis_human_time_diff() correctly returns string
	 *
	 * @covers ::genesis_human_time_diff
	 *
	 * @dataProvider data_genesis_human_time_diff
	 *
	 * @param string   $expected       Expected return value should be a string.
	 * @param int      $older_date     Unix timestamp of date you want to calculate the time since for`.
	 * @param int|bool $newer_date     Optional. Unix timestamp of date to compare older date to. Default false (current time).
	 * @param int      $relative_depth Optional, how many units to include in relative date. Default 2.
	 */
	public function test_genesis_human_time_diff( $expected, $older_date, $newer_date = false, $relative_depth = 2 ) {
		Functions\stubs(
			[
				'_nx',
				'_x',
			]
		);
		Functions\when( 'absint' )->alias(
			static function ( $int ) {
				return abs( $int );
			}
		);
		Functions\when( '_nx_noop' )->alias(
			static function ( $singular, $plural, $context, $domain = null ) {
				return [
					0          => $singular,
					1          => $plural,
					2          => $context,
					'singular' => $singular,
					'plural'   => $plural,
					'context'  => $context,
					'domain'   => $domain,
				];
			}
		);
		Functions\when( 'translate_nooped_plural' )->alias(
			static function( $nooped_plural, $count, $domain = 'default' ) {
				return 1 === $count ? $nooped_plural['singular'] : $nooped_plural['plural'];
			}
		);
		Functions\when( 'time' )->alias(
			static function () {
				return (int) '1548805766';
			}
		);

		$actual = genesis_human_time_diff( $older_date, $newer_date, $relative_depth );
		self::assertEquals( $expected, $actual );
	}

	/**
	 * Data provider for test_genesis_human_time_diff().
	 *
	 * @return array
	 */
	public function data_genesis_human_time_diff() {
		return [
			'dates the same'                           => [
				'0 seconds',
				1548794199,
				1548794199,
				0,
			],
			'dates with depth 0 (defaults to depth 2)' => [
				'1 hour and 6 minutes',
				1548794199,
				1548798199,
				0,
			],
			'dates with depth 1'                       => [
				'1 hour',
				1548794199,
				1548798199,
				1,
			],
			'dates with depth 2'                       => [
				'1 hour and 6 minutes',
				1548794199,
				1548798199,
				2,
			],
			'dates with depth 3'                       => [
				'1 hour, 6 minutes and 40 seconds',
				1548794199,
				1548798199,
				3,
			],
			'dates with depth 4'                       => [
				'3 months, 3 weeks, 4 days and 18 hours',
				1538794199,
				1548798199,
				4,
			],
			'newer date and depth args missing'        => [
				'3 hours and 12 minutes',
				1548794199,
				null,
				null,
			],
			'dates with default relative depth'        => [
				'3 hours and 12 minutes',
				1548794199,
				1548805766,
				null,
			],
			'older date is not int'                    => [
				'',
				'foo',
				null,
				null,
			],
		];
	}

	/**
	 * Test that a phrase shortened in length to a maximum number of characters is returned.
	 *
	 * @covers ::genesis_truncate_phrase
	 *
	 * @dataProvider data_genesis_truncate_phrase
	 *
	 * @param string $text           A string to be shortened.
	 * @param int    $max_characters The maximum number of characters to return.
	 * @param string $expected       The expected string to be returned.
	 */
	public function test_genesis_truncate_phrase( $text, $max_characters, $expected ) {

		$actual = genesis_truncate_phrase( $text, $max_characters );
		self::assertEquals( $expected, $actual );

	}

	/**
	 * Data provider for test_genesis_truncate_phrase().
	 *
	 * @return array
	 */
	public function data_genesis_truncate_phrase() {
		return [
			'maxcharacters of 5'                     => [
				'Lorem ipsum',
				5,
				'Lorem',
			],
			'max characters of 50'                   => [
				'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec tellus dolor.
				Vivamus nec tellus dolor. Aenean fringilla, urna vel gravida iaculis,
				est ex volutpat odio, in commodo dolor nunc id nunc. Morbi ultrices tortor mi,',
				50,
				'Lorem ipsum dolor sit amet, consectetur adipiscing',
			],
			'max characters 0 returns blank string'  => [
				'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
				0,
				'',
			],
			'check max characters being falsy'       => [
				'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
				false,
				'',
			],
			'max characters less than string length' => [
				'Lorem ipsum',
				50,
				'Lorem ipsum',
			],
		];
	}
}
