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

namespace StudioPress\Genesis\Tests\System;

/**
 * Test widget-import.php.
 *
 * @group admin
 * @group functions
 * @group onboarding
 */
class WidgetImport extends TestCase {

	/**
	 * Sidebar state to restore after each test.
	 *
	 * @var array
	 */
	protected $sidebars_widgets;

	/**
	 * Text widget state to restore after each test.
	 *
	 * @var array
	 */
	protected $text_widgets;

	/**
	 * Text widget example to insert.
	 *
	 * @var array
	 */
	protected $sample_text_widget = [
		'title'  => 'Test Widget Title',
		'text'   => '<p>With an emphasis on typography, white space, and mobile-optimized design, your website will look absolutely breathtaking.</p><p><a href="#">Learn more about design</a>.</p>',
		'filter' => 1,
		'visual' => 1,
	];

	/**
	 * Sets up the environment before each test.
	 */
	public function setUp() {
		parent::setUp();
		require_once PARENT_DIR . '/lib/admin/widget-import.php';
		$this->text_widgets     = get_option( 'widget_text' );
		$this->sidebars_widgets = get_option( 'sidebars_widgets' );
	}

	/**
	 * Restores sidebar and text widget state between each test.
	 */
	public function tearDown() {
		update_option( 'widget_text', $this->text_widgets );
		update_option( 'sidebars_widgets', $this->sidebars_widgets );
		parent::tearDown();
	}

	/**
	 * Tests that StudioPress\Genesis\Admin\WidgetImport\insert_widget()
	 * inserts a text widget into the Sidebar widget area.
	 */
	public function test_insert_widget() {
		\StudioPress\Genesis\Admin\WidgetImport\insert_widget(
			'sidebar',
			'text',
			$this->sample_text_widget
		);

		$expected_text_widgets = [
			1              => $this->sample_text_widget,
			'_multiwidget' => 1,
		];

		// Text widgets contain a new text widget with expected properties.
		self::assertEquals( $expected_text_widgets, get_option( 'widget_text' ) );

		// New widget has been assigned to the correct sidebar.
		$sidebars_widgets = get_option( 'sidebars_widgets' );
		self::assertContains( 'text-1', $sidebars_widgets['sidebar'] );
	}

	/**
	 * Tests that StudioPress\Genesis\Admin\WidgetImport\get_next_widget_id()
	 * retrieves the next ID for a given widget type.
	 */
	public function test_get_next_widget_id() {
		// There are no text widget instances in a default WordPress
		// installation, so the next text widget ID should be 1.
		$text_widgets        = get_option( 'widget_text' );
		$next_text_widget_id = \StudioPress\Genesis\Admin\WidgetImport\get_next_widget_id( $text_widgets );
		self::assertEquals( 1, $next_text_widget_id );

		// There is one meta widget instance in a default WP installation,
		// so the next ID should be greater than 1. It can also be higher than
		// 2 due to how WordPress assigns the initial ID, so we test for > 1.
		$meta_widgets        = get_option( 'widget_meta' );
		$next_meta_widget_id = \StudioPress\Genesis\Admin\WidgetImport\get_next_widget_id( $meta_widgets );
		self::assertGreaterThan(
			1,
			$next_meta_widget_id,
			'If this test fails, first check that WordPress still includes a Meta widget instance in a fresh install.'
		);
	}

	/**
	 * Tests that StudioPress\Genesis\Admin\WidgetImport\clear_widget_areas()
	 * moves default widgets from the Sidebar to the Inactive Widgets area.
	 *
	 * In a fresh WordPress installation there are:
	 * - 6 default widgets in the Sidebar area when Genesis is active.
	 * - 0 widgets in the Inactive Widgets area.
	 *
	 * The reverse should be true after clearing widgets from the Sidebar.
	 */
	public function test_clear_sidebar_widget_area() {
		// Attempt to clear the sidebar and wp_inactive_widgets areas.
		// The inactive widgets area should not be cleared ('wp_inactive_widgets' is ignored).
		\StudioPress\Genesis\Admin\WidgetImport\clear_widget_areas( [ 'sidebar', 'wp_inactive_widgets' ] );

		$sidebars_widgets = get_option( 'sidebars_widgets' );

		// Default WordPress widgets were moved to the Inactive Widgets area.
		// 'GreaterThan 0' instead of 'Equals 6' in case WordPress changes
		// default widget count in future versions.
		self::assertGreaterThan( 0, count( $sidebars_widgets['wp_inactive_widgets'] ) );

		// Sidebar widget area is now empty.
		self::assertArrayNotHasKey( 'sidebar', $sidebars_widgets );
	}

}