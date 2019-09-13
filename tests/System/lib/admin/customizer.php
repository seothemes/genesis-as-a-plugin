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

use WP_Customize_Manager;
use Genesis_Customizer;

require_once GENESIS_THEME_DIR . '/lib/admin/customizer.php';
require_once dirname( dirname( dirname( getcwd() ) ) ) . '/wp-includes/class-wp-customize-manager.php';
require_once GENESIS_THEME_DIR . '/lib/classes/class-genesis-customizer.php';

/**
 * Test Customizer functions.
 *
 * @group admin
 * @group customizer
 */
class Customizer_Test extends TestCase {
	/**
	 * WP Customizer Manager object.
	 *
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp() {
		parent::setUp();

		$this->wp_customize = new WP_Customize_Manager();
	}

	/**
	 * Clean up after tests have run.
	 */
	public static function tearDownAfterClass() {
		update_option( 'show_on_front', 'posts' );
		remove_theme_support( 'genesis-custom-header' );
		remove_theme_support( 'genesis-style-selector' );
	}

	/**
	 * Test Genesis Customize Register initializes genesis_customizer and sets up panels.
	 */
	public function test_genesis_customize_register_initializes_genesis_customizer() {
		genesis_customize_register( $this->wp_customize );

		$this->assertTrue( has_action( 'genesis_customizer' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-seo' ) );
		$this->assertEmpty( $this->wp_customize->get_panel( 'genesis-is-the-best' ) );
	}

	/**
	 * Test $genesis_customizer->register() adds a panel.
	 */
	public function test_genesis_customizer_register_adds_panel() {
		$genesis_customizer = new Genesis_Customizer( $this->wp_customize );

		genesis_customize_register( $this->wp_customize );

		$config = require GENESIS_THEME_DIR . '/tests/fixtures/config-array-for-customizer-tests.php';
		$genesis_customizer->register( $config );

		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-seo' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-is-the-best' ) );
	}

	/**
	 * Test genesis_customizer_theme_settings_config filter adds a new panel and removes the Genesis theme settings panel when returning single $config.
	 */
	public function test_genesis_customizer_theme_settings_config_filter_adds_panel_removes_genesis_theme_settings_panel() {
		add_filter(
			'genesis_customizer_theme_settings_config',
			static function () {
				return require GENESIS_THEME_DIR . '/tests/fixtures/config-array-for-customizer-tests.php';
			}
		);

		genesis_customize_register( $this->wp_customize );

		$this->assertEmpty( $this->wp_customize->get_panel( 'genesis' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-seo' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-is-the-best' ) );
	}

	/**
	 * Test genesis_customizer_theme_settings_config filter adds a new panel to the existing Genesis panel with array_merge.
	 */
	public function test_genesis_customizer_theme_settings_config_filter_adds_panel_array_merge() {
		add_filter(
			'genesis_customizer_theme_settings_config',
			static function ( $config ) {
				$config2 = require GENESIS_THEME_DIR . '/tests/fixtures/config-array-for-customizer-tests.php';

				return array_merge( $config, $config2 );
			}
		);

		genesis_customize_register( $this->wp_customize );

		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-is-the-best' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-seo' ) );
	}

	/**
	 * Test genesis_customizer_seo_settings_config filter adds a new panel and removes the Genesis SEO settings panel when returning single $config.
	 */
	public function test_genesis_customizer_seo_settings_config_filter_adds_panel_removes_genesis_seo_settings_panel() {
		add_filter(
			'genesis_customizer_seo_settings_config',
			static function () {
				return require GENESIS_THEME_DIR . '/tests/fixtures/config-array-for-customizer-tests.php';
			}
		);

		genesis_customize_register( $this->wp_customize );

		$this->assertEmpty( $this->wp_customize->get_panel( 'genesis-seo' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-is-the-best' ) );
	}

	/**
	 * Test genesis_customizer_seo_settings_config filter adds a new panel to the existing Genesis panel with array_merge.
	 */
	public function test_genesis_customizer_seo_settings_config_filter_adds_panel_array_merge() {
		add_filter(
			'genesis_customizer_seo_settings_config',
			static function ( $config ) {
				$config2 = require GENESIS_THEME_DIR . '/tests/fixtures/config-array-for-customizer-tests.php';

				return array_merge( $config, $config2 );
			}
		);

		genesis_customize_register( $this->wp_customize );

		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-is-the-best' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis' ) );
		$this->assertNotEmpty( $this->wp_customize->get_panel( 'genesis-seo' ) );
	}

	/**
	 * Test genesis_get_color_schemes_for_customizer returns an empty array when no theme support for genesis-style-selector is added.
	 */
	public function test_genesis_get_color_schemes_for_customizer_returns_empty_array_when_no_theme_support() {
		remove_theme_support( 'genesis-style-selector' );
		$this->assertEmpty( genesis_get_color_schemes_for_customizer() );
	}

	/**
	 * Test genesis_get_color_schemes_for_customizer returns an empty array when theme support for genesis-style-selector is added without styles.
	 */
	public function test_genesis_get_color_schemes_for_customizer_returns_empty_array_when_no_styles_added() {
		// Add theme support, but without actual schemes defined.
		add_theme_support( 'genesis-style-selector' );
		$this->assertEmpty( genesis_get_color_schemes_for_customizer() );

		// An empty array is not sufficient for this.
		add_theme_support( 'genesis-style-selector', [] );
		$this->assertEmpty( genesis_get_color_schemes_for_customizer() );
	}

	/**
	 * Test genesis_get_color_schemes_for_customizer returns an array of color schemes.
	 */
	public function test_genesis_get_color_schemes_for_customizer_returns_array_of_color_schemes() {
		// Add theme support with an array of color schemes.
		add_theme_support(
			'genesis-style-selector',
			[
				'genesis_blue'   => __( 'Genesis Blue', 'genesis' ),
				'genesis_custom' => __( 'Genesis Custom', 'genesis' ),
			]
		);

		$color_schemes = genesis_get_color_schemes_for_customizer();

		$this->assertArrayHasKey( '', $color_schemes );
		$this->assertArrayHasKey( 'genesis_blue', $color_schemes );
		$this->assertArrayHasKey( 'genesis_custom', $color_schemes );
	}

	/**
	 * Test genesis_has_color_schemes returns false when no theme support for genesis-style-selector is added.
	 */
	public function test_genesis_has_color_schemes_returns_false_when_no_theme_support_for_genesis_style_selector() {
		remove_theme_support( 'genesis-style-selector' );
		$this->assertFalse( genesis_has_color_schemes() );
	}

	/**
	 * Test genesis_has_color_schemes returns false when no styles are added.
	 */
	public function test_genesis_has_color_schemes_returns_false_when_no_styles_are_added() {
		// Add theme support, but without actual schemes defined.
		add_theme_support( 'genesis-style-selector' );
		$this->assertFalse( genesis_has_color_schemes() );

		// Empty array is not sufficient for this to pass.
		add_theme_support( 'genesis-style-selector', [] );
		$this->assertFalse( genesis_has_color_schemes() );
	}

	/**
	 * Test genesis_has_color_schemes returns true when styles are defined.
	 */
	public function test_genesis_has_color_schemes_returns_true_when_styles_defined() {
		// Add theme support with an array of color schemes.
		add_theme_support(
			'genesis-style-selector',
			[
				'genesis_blue'   => __( 'Genesis Blue', 'genesis' ),
				'genesis_custom' => __( 'Genesis Custom', 'genesis' ),
			]
		);
		$this->assertTrue( genesis_has_color_schemes() );
	}

	/**
	 * Test genesis_show_header_customizer_callback returns true when neither custom header theme supports are added.
	 */
	public function test_genesis_show_header_customizer_callback_returns_true_when_neither_custom_header_theme_supports_are_added() {
		remove_theme_support( 'genesis-custom-header' );
		remove_theme_support( 'custom-header' );
		$this->assertTrue( genesis_show_header_customizer_callback() );
	}

	/**
	 * Test genesis_show_header_customizer_callback returns false when one or both custom header theme supports are added.
	 */
	public function test_genesis_show_header_customizer_callback_returns_false_when_one_or_both_custom_header_theme_supports_are_added() {
		add_theme_support( 'custom-header' );
		$this->assertFalse( genesis_show_header_customizer_callback() );

		remove_theme_support( 'custom-header' );

		add_theme_support( 'genesis-custom-header' );
		$this->assertFalse( genesis_show_header_customizer_callback() );

		add_theme_support( 'custom-header' );
		$this->assertFalse( genesis_show_header_customizer_callback() );
	}

	/**
	 * Test genesis_posts_show_on_front returns true when show_on_front is posts.
	 */
	public function test_genesis_posts_show_on_front_returns_true_when_show_on_front_is_posts() {
		update_option( 'show_on_front', 'posts' );
		$this->assertTrue( genesis_posts_show_on_front() );
	}

	/**
	 * Test genesis_posts_show_on_front returns false when show_on_front is foo.
	 */
	public function test_genesis_posts_show_on_front_returns_false_when_show_on_front_is_foo() {
		update_option( 'show_on_front', 'foo' );
		$this->assertFalse( genesis_posts_show_on_front() );
	}

	/**
	 * Test genesis_page_show_on_front returns true when show_on_front is page.
	 */
	public function test_genesis_page_show_on_front_returns_true_when_show_on_front_is_page() {
		update_option( 'show_on_front', 'page' );
		$this->assertTrue( genesis_page_show_on_front() );
	}

	/**
	 * Test genesis_page_show_on_front returns false when show_on_front is foo.
	 */
	public function test_genesis_page_show_on_front_returns_false_when_show_on_front_is_foo() {
		update_option( 'show_on_front', 'foo' );
		$this->assertFalse( genesis_page_show_on_front() );
	}
}