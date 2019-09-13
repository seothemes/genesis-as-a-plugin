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
use Genesis_SEO_Document_Title_Parts;

/**
 * Test Genesis Document Title Parts class.
 *
 * @group seotitle
 */
class Genesis_SEO_Document_Title_Parts_Test extends TestCase {

	/**
	 * The site title for the purpose of this test.
	 */
	const SITE = 'My Awesome Website';

	/**
	 * The site tagline for the purpose of this test.
	 */
	const TAGLINE = 'My Site Tagline';

	/**
	 * Page number
	 */
	const PAGE = 9;

	/**
	 * Class under test.
	 *
	 * @var Genesis_SEO_Document_Title_Parts
	 */
	private $testee;

	/**
	 * Unaltered parts, as might be provided from the document_title_parts filter.
	 *
	 * @var array
	 */
	private $unaltered_parts = [
		'title'   => 'My Title Decided By WP',
		'tagline' => self::TAGLINE,
		'page'    => self::PAGE,
		'site'    => self::SITE,
	];

	/**
	 * Default parts.
	 *
	 * @var array
	 */
	private $default_parts = [
		'title' => self::SITE,
	];

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before a test is executed.
	 */
	public function setUp() {
		Functions\when( 'wp_parse_args' )->alias( 'array_merge' );
		Functions\expect( 'get_bloginfo' )->with( 'name', 'display' )->andReturn( self::SITE );
		Functions\when( 'get_template_directory' )->justReturn( GENESIS_THEME_DIR );

		$this->testee = new Genesis_SEO_Document_Title_Parts();

		parent::setUp();
	}

	/**
	 * Test class can be instantiated.
	 */
	public function test_class_can_be_instantiated() {
		self::assertInstanceOf( Genesis_SEO_Document_Title_Parts::class, $this->testee );
	}

	/**
	 * Test get_parts returns amended parts when outside of contexts and appending site title.
	 */
	public function test_get_parts_returns_amended_parts_when_outside_of_contexts_and_appending_site_title() {
		Functions\expect( 'genesis_is_root_page' )->andReturn( false );
		Functions\expect( 'is_home' )->andReturn( false );
		Functions\expect( 'is_singular' )->andReturn( false );
		Functions\expect( 'is_category' )->andReturn( false );
		Functions\expect( 'is_tag' )->andReturn( false );
		Functions\expect( 'is_tax' )->andReturn( false );
		Functions\expect( 'is_author' )->andReturn( false );
		Functions\expect( 'is_post_type_archive' )->andReturn( false );
		Functions\expect( 'is_feed' )->andReturn( false );

		Functions\expect( 'genesis_get_seo_option' )->with( 'append_site_title' )->andReturn( true );

		$expected = [
			'title'   => self::SITE,
			'tagline' => self::TAGLINE,
			'page'    => self::PAGE,
			'site'    => self::SITE,
		];

		self::assertEquals( $expected, $this->testee->get_parts( $this->unaltered_parts ) );
	}

	/**
	 * Test get_parts returns amended parts when outside of contexts and not appending site title.
	 */
	public function test_get_parts_returns_amended_parts_when_outside_of_contexts_and_not_appending_site_title() {
		Functions\expect( 'genesis_is_root_page' )->andReturn( false );
		Functions\expect( 'is_home' )->andReturn( false );
		Functions\expect( 'is_singular' )->andReturn( false );
		Functions\expect( 'is_category' )->andReturn( false );
		Functions\expect( 'is_tag' )->andReturn( false );
		Functions\expect( 'is_tax' )->andReturn( false );
		Functions\expect( 'is_author' )->andReturn( false );
		Functions\expect( 'is_post_type_archive' )->andReturn( false );
		Functions\expect( 'is_feed' )->andReturn( false );

		Functions\expect( 'genesis_get_seo_option' )->with( 'append_site_title' )->andReturn( false );

		$expected = [
			'title'   => self::SITE,
			'tagline' => self::TAGLINE,
			'page'    => self::PAGE,
		];

		self::assertEquals( $expected, $this->testee->get_parts( $this->unaltered_parts ) );
	}

	/**
	 * Test get_parts returns amended parts when in the root page context.
	 */
	public function test_get_parts_returns_amended_parts_when_in_the_root_page_context() {
		Functions\expect( 'genesis_is_root_page' )->andReturn( true );
		Functions\expect( 'is_home' )->andReturn( false );
		Functions\expect( 'is_singular' )->andReturn( false );
		Functions\expect( 'is_category' )->andReturn( false );
		Functions\expect( 'is_tag' )->andReturn( false );
		Functions\expect( 'is_tax' )->andReturn( false );
		Functions\expect( 'is_author' )->andReturn( false );
		Functions\expect( 'is_post_type_archive' )->andReturn( false );
		Functions\expect( 'is_feed' )->andReturn( false );
		Functions\expect( 'genesis_get_seo_option' )->with( 'append_site_title' )->andReturn( false );

		Functions\expect( 'genesis_get_seo_option' )->with( 'home_doctitle' )->andReturn( self::SITE );
		Functions\expect( 'genesis_get_seo_option' )->with( 'append_description_home' )->andReturn( false );

		$expected = [
			'title' => self::SITE,
			'page'  => self::PAGE,
		];

		self::assertEquals( $expected, $this->testee->get_parts( $this->unaltered_parts ) );
	}

	/**
	 * Test get_parts returns amended parts when in the post type archive context.
	 */
	public function test_get_parts_returns_amended_parts_when_in_the_post_type_archive_context() {
		Functions\expect( 'genesis_is_root_page' )->andReturn( false );
		Functions\expect( 'is_home' )->andReturn( false );
		Functions\expect( 'is_singular' )->andReturn( false );
		Functions\expect( 'is_category' )->andReturn( false );
		Functions\expect( 'is_tag' )->andReturn( false );
		Functions\expect( 'is_tax' )->andReturn( false );
		Functions\expect( 'is_author' )->andReturn( false );
		Functions\expect( 'is_post_type_archive' )->andReturn( true );
		Functions\expect( 'is_feed' )->andReturn( false );
		Functions\expect( 'genesis_get_seo_option' )->with( 'append_site_title' )->andReturn( true );

		Functions\expect( 'genesis_has_post_type_archive_support' )->once()->andReturn( true );
		Functions\expect( 'genesis_get_cpt_option' )->once()->andReturn( 'My Archive Title' );

		$expected = [
			'title'   => 'My Archive Title',
			'tagline' => self::TAGLINE,
			'page'    => self::PAGE,
			'site'    => self::SITE,
		];

		self::assertEquals( $expected, $this->testee->get_parts( $this->unaltered_parts ) );
	}

	/**
	 * Test get_root_page_title_parts returns default parts when home title is empty and not appending description to home.
	 */
	public function test_class_returns_default_parts_when_home_title_is_empty_and_not_appending_description_to_home() {
		Functions\expect( 'genesis_get_seo_option' )->with( 'home_doctitle' )->andReturnNull();
		Functions\expect( 'genesis_get_seo_option' )->with( 'append_description_home' )->andReturn( false );

		self::assertEquals( $this->default_parts, $this->testee->get_root_page_title_parts() );
	}

	/**
	 * Test get_root_page_title_parts returns default parts when home title is populated and not appending description to home.
	 */
	public function test_class_returns_default_parts_when_home_title_is_populated_and_not_appending_description_to_home() {
		Functions\expect( 'genesis_get_seo_option' )->with( 'home_doctitle' )->andReturn( self::SITE );
		Functions\expect( 'genesis_get_seo_option' )->with( 'append_description_home' )->andReturn( false );

		self::assertEquals( $this->default_parts, $this->testee->get_root_page_title_parts() );
	}

	/**
	 * Test get_root_page_title_parts returns default parts when home title is populated and not appending description to home.
	 *
	 * The anomaly here, is that for the unit test of the public method, we never merged in a `tagline` key to `$parts`,
	 * so the unsetting in the testee method has no effect.
	 *
	 * The correct behaviour is covered in the test for `get_parts()`.
	 */
	public function test_class_returns_default_parts_when_home_title_is_populated_and_appending_description_to_home() {
		Functions\expect( 'genesis_get_seo_option' )->with( 'home_doctitle' )->andReturn( self::SITE );
		Functions\expect( 'genesis_get_seo_option' )->with( 'append_description_home' )->andReturn( true );

		self::assertEquals( $this->default_parts, $this->testee->get_root_page_title_parts() );
	}

	/**
	 * Test get_home_page_title_parts returns default parts when not using page for posts.
	 */
	public function test_class_returns_default_parts_when_not_using_page_for_posts() {
		Functions\expect( 'get_option' )->andReturn( 0 );

		self::assertEquals( $this->default_parts, $this->testee->get_home_page_title_parts() );
	}

	/**
	 * Test get_home_page_title_parts returns amended parts when using page for posts.
	 */
	public function test_class_returns_amended_parts_when_using_page_for_posts() {
		Functions\expect( 'get_option' )->andReturn( 999 );
		Functions\expect( 'get_queried_object_id' )->andReturn( 999 );
		Functions\expect( 'genesis_get_custom_field' )->with( '_genesis_title', 999 )->andReturn( 'My Genesis Singular Home Page Title' );

		$expected = [
			'title' => 'My Genesis Singular Home Page Title',
		];

		self::assertEquals( $expected, $this->testee->get_home_page_title_parts() );
	}

	/**
	 * Test get_singular_title_parts returns default parts when singular title is empty.
	 */
	public function test_class_returns_default_parts_when_singular_title_is_empty() {
		Functions\expect( 'genesis_get_custom_field' )->andReturnNull();

		self::assertEquals( $this->default_parts, $this->testee->get_singular_title_parts() );
	}

	/**
	 * Test get_singular_title_parts returns amended parts when singular title is populated with Genesis SEO value.
	 */
	public function test_class_returns_amended_parts_when_singular_title_is_populated_with_genesis_seo() {
		Functions\expect( 'genesis_get_custom_field' )->with( '_genesis_title', null )->andReturn( 'My Genesis Singular Title' );

		$expected = [
			'title' => 'My Genesis Singular Title',
		];

		self::assertEquals( $expected, $this->testee->get_singular_title_parts() );
	}

	/**
	 * Test get_singular_title_parts returns amended parts when singular title is populated with All in One SEO value.
	 */
	public function test_class_returns_amended_parts_when_singular_title_is_populated_with_aioseo_seo() {
		Functions\expect( 'genesis_get_custom_field' )->with( '_aioseo_title', null )->andReturn( 'My AIOSEO Singular Title' );
		Functions\expect( 'genesis_get_custom_field' )->andReturnNull();

		$expected = [
			'title' => 'My AIOSEO Singular Title',
		];

		self::assertEquals( $expected, $this->testee->get_singular_title_parts() );
	}

	/**
	 * Test get_tax_archive_title_parts returns default parts when term title is empty.
	 */
	public function test_class_returns_default_parts_when_term_title_is_empty() {
		Functions\expect( 'get_queried_object' )->once()->andReturn( $this->get_mock_term() );
		Functions\expect( 'get_term_meta' )->once()->andReturn( null );

		self::assertEquals( $this->default_parts, $this->testee->get_tax_archive_title_parts() );
	}

	/**
	 * Test get_tax_archive_title_parts returns amended parts when term title is populated.
	 */
	public function test_class_returns_amended_parts_when_term_title_is_populated() {
		Functions\expect( 'get_queried_object' )->once()->andReturn( $this->get_mock_term() );
		Functions\expect( 'get_term_meta' )->once()->andReturn( 'My Term Title' );

		$expected = [
			'title' => 'My Term Title',
		];

		self::assertEquals( $expected, $this->testee->get_tax_archive_title_parts() );
	}

	/**
	 * Test get_author_archive_title_parts returns default parts when author title is empty.
	 */
	public function test_class_returns_default_parts_when_author_title_is_empty() {
		Functions\expect( 'get_the_author_meta' )->once()->andReturn( null );
		Functions\when( 'get_query_var' )->justReturn();

		self::assertEquals( $this->default_parts, $this->testee->get_author_archive_title_parts() );
	}

	/**
	 * Test get_author_archive_title_parts returns amended parts when author title is populated.
	 */
	public function test_class_returns_amended_parts_when_author_title_is_populated() {
		Functions\expect( 'get_the_author_meta' )->once()->andReturn( 'My User Title' );
		Functions\when( 'get_query_var' )->justReturn();

		$expected = [
			'title' => 'My User Title',
		];

		self::assertEquals( $expected, $this->testee->get_author_archive_title_parts() );
	}

	/**
	 * Test get_post_type_archive_title_parts returns default parts when there is no post type archive support.
	 */
	public function test_class_returns_default_parts_when_no_post_type_support() {
		Functions\expect( 'genesis_has_post_type_archive_support' )->once()->andReturn( false );

		self::assertEquals( $this->default_parts, $this->testee->get_post_type_archive_title_parts() );
	}

	/**
	 * Test get_post_type_archive_title_parts returns default parts when there is no post type archive support.
	 */
	public function test_class_returns_default_parts_when_has_post_type_support_but_no_cpt_title() {
		Functions\expect( 'genesis_has_post_type_archive_support' )->once()->andReturn( true );
		Functions\expect( 'genesis_get_cpt_option' )->once()->andReturnNull();

		self::assertEquals( $this->default_parts, $this->testee->get_post_type_archive_title_parts() );
	}

	/**
	 * Test get_post_type_archive_title_parts returns amended parts when there is post type support,
	 * and a CPT archive doctitle field is set.
	 */
	public function test_class_returns_amended_parts_when_has_post_type_support_and_cpt_title() {
		Functions\expect( 'genesis_has_post_type_archive_support' )->once()->andReturn( true );
		Functions\expect( 'genesis_get_cpt_option' )->once()->andReturn( 'My Archive Title' );

		$expected = [
			'title' => 'My Archive Title',
		];

		self::assertEquals( $expected, $this->testee->get_post_type_archive_title_parts() );
	}

	/**
	 * Test that the default parts can be retrieved.
	 */
	public function test_can_get_default_parts() {
		self::assertEquals( $this->default_parts, $this->testee->get_default_parts() );
	}

	/**
	 * Get mock term.
	 */
	private function get_mock_term() {
		$term          = new \stdClass();
		$term->term_id = 9999;

		return $term;
	}
}
