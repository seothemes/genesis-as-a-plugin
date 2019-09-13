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

namespace StudioPress\Genesis\Tests\Integration;

use Brain\Monkey\Functions;
use Genesis_Contributor;
use Genesis_Contributors;

/**
 * Test Genesis Contributors class.
 *
 * @group contributors
 */
class Genesis_Contributors_Test extends TestCase {

	/**
	 * Mock config list of people.
	 *
	 * @var array
	 */
	private $people;

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before a test is executed.
	 */
	public function setUp() {
		$this->people = [
			'nathanrice'   => [
				'name'     => 'Nathan Rice',
				'twitter'  => 'nathanrice',
				'gravatar' => 'fdbd4b13e3bcccb8b48cc18f846efb7f',
				'role'     => 'lead-developer',
			],
			'briangardner' => [
				'name'     => 'Brian Gardner',
				'twitter'  => 'bgardner',
				'gravatar' => 'c845c86ebe395cea0d21c03bc4a93957',
				'role'     => 'lead-developer',
			],
			'garyjones'    => [
				'name'     => 'Gary Jones',
				'twitter'  => 'garyj',
				'gravatar' => 'e70d4086e89c2e1e081870865be68485',
				'role'     => 'contributor',
			],
		];

		parent::setUp();

		Functions\when( 'get_template_directory' )->justReturn( GENESIS_THEME_DIR );
	}

	/**
	 * Test can find all people.
	 *
	 * @group genesiscontributors
	 */
	public function test_genesis_contributors_can_find_all_with_multiple_people() {
		$contributors = new Genesis_Contributors( $this->people );

		$expects = [
			'nathanrice'   => new Genesis_Contributor(
				'Nathan Rice',
				'https://twitter.com/nathanrice',
				'https://0.gravatar.com/avatar/fdbd4b13e3bcccb8b48cc18f846efb7f?s=120',
				'lead-developer'
			),
			'briangardner' => new Genesis_Contributor(
				'Brian Gardner',
				'https://twitter.com/bgardner',
				'https://0.gravatar.com/avatar/c845c86ebe395cea0d21c03bc4a93957?s=120',
				'lead-developer'
			),
			'garyjones'    => new Genesis_Contributor(
				'Gary Jones',
				'https://twitter.com/garyj',
				'https://0.gravatar.com/avatar/e70d4086e89c2e1e081870865be68485?s=120',
				'contributor'
			),
		];

		self::assertEquals( $expects, $contributors->find_all() );
	}

	/**
	 * Test all contributors with a specific role can be found.
	 *
	 * @group genesiscontributors
	 */
	public function test_genesis_contributors_can_find_by_role() {
		$lead_developers = [
			'nathanrice'   => new Genesis_Contributor(
				'Nathan Rice',
				'https://twitter.com/nathanrice',
				'https://0.gravatar.com/avatar/fdbd4b13e3bcccb8b48cc18f846efb7f?s=120',
				'lead-developer'
			),
			'briangardner' => new Genesis_Contributor(
				'Brian Gardner',
				'https://twitter.com/bgardner',
				'https://0.gravatar.com/avatar/c845c86ebe395cea0d21c03bc4a93957?s=120',
				'lead-developer'
			),
		];

		$contributor = [
			'garyjones' => new Genesis_Contributor(
				'Gary Jones',
				'https://twitter.com/garyj',
				'https://0.gravatar.com/avatar/e70d4086e89c2e1e081870865be68485?s=120',
				'contributor'
			),
		];

		$contributors = new Genesis_Contributors( $this->people );

		self::assertEquals( $lead_developers, $contributors->find_by_role( 'lead-developer' ) );
		self::assertEquals( $contributor, $contributors->find_by_role( 'contributor' ) );
	}

	/**
	 * Test all people with contributor role can be found.
	 *
	 * @group genesiscontributors
	 */
	public function test_genesis_contributors_can_find_contributors() {
		$expects = [
			new Genesis_Contributor(
				'Gary Jones',
				'https://twitter.com/garyj',
				'https://0.gravatar.com/avatar/e70d4086e89c2e1e081870865be68485?s=120',
				'contributor'
			),
		];

		$contributors = new Genesis_Contributors( $this->people );

		self::assertEquals( $expects, $contributors->find_contributors() );
	}

	/**
	 * Test specific contributor can be found by ID.
	 *
	 * @group genesiscontributors
	 */
	public function test_genesis_contributors_can_find_by_id() {
		$expects = new Genesis_Contributor(
			'Nathan Rice',
			'https://twitter.com/nathanrice',
			'https://0.gravatar.com/avatar/fdbd4b13e3bcccb8b48cc18f846efb7f?s=120',
			'lead-developer'
		);

		$contributors = new Genesis_Contributors( $this->people );

		self::assertEquals( $expects, $contributors->find_by_id( 'nathanrice' ) );
	}

	/**
	 * Test unset role is populated with "none".
	 *
	 * @group genesiscontributors
	 */
	public function test_genesis_contributors_can_handle_unset_role() {
		$people = [
			'garyjones' => [
				'name'     => 'Gary Jones',
				'twitter'  => 'garyj',
				'gravatar' => 'e70d4086e89c2e1e081870865be68485',
				// No role set.
			],
		];

		$expects = [
			'garyjones' => new Genesis_Contributor(
				'Gary Jones',
				'https://twitter.com/garyj',
				'https://0.gravatar.com/avatar/e70d4086e89c2e1e081870865be68485?s=120',
				'none'
			),
		];

		$contributors = new Genesis_Contributors( $people );

		self::assertEquals( $expects, $contributors->find_all() );
	}

	/**
	 * Test profile URL is not amended when using full profile URL instead of Twitter handle.
	 *
	 * @group genesiscontributors
	 */
	public function test_genesis_contributors_can_handle_absolute_url() {
		$people = [
			'briangardner' => [
				'name'     => 'Brian Gardner',
				'url'      => 'https://briangardner.com',
				'gravatar' => 'c845c86ebe395cea0d21c03bc4a93957',
				'role'     => 'lead-developer',
			],
		];

		$expects = [
			'briangardner' => new Genesis_Contributor(
				'Brian Gardner',
				'https://briangardner.com',
				'https://0.gravatar.com/avatar/c845c86ebe395cea0d21c03bc4a93957?s=120',
				'lead-developer'
			),
		];

		$contributors = new Genesis_Contributors( $people );

		self::assertEquals( $expects, $contributors->find_all() );
	}

	/**
	 * Test avatar URL is not amended when using full avatar URL instead of Gravatar.
	 *
	 * @group genesiscontributors
	 */
	public function test_genesis_contributors_can_handle_avatar_url() {
		$people = [
			'briangardner' => [
				'name'    => 'Brian Gardner',
				'twitter' => 'bgardner',
				'avatar'  => 'https://briangardner.com/images/about.jpg',
				'role'    => 'lead-developer',
			],
		];

		$expects = [
			'briangardner' => new Genesis_Contributor(
				'Brian Gardner',
				'https://twitter.com/bgardner',
				'https://briangardner.com/images/about.jpg',
				'lead-developer'
			),
		];

		$contributors = new Genesis_Contributors( $people );

		self::assertEquals( $expects, $contributors->find_all() );
	}
}