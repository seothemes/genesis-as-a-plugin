<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Admin
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

add_filter( 'debug_information', 'genesis_site_health_info' );
/**
 * Adds Genesis info to the Tools -> Site Health -> Info tab.
 *
 * @since 3.0.0
 *
 * @param array $info The original debug information.
 * @return array Debug information modified with Genesis information.
 */
function genesis_site_health_info( $info ) {
	$options = get_option( GENESIS_SETTINGS_FIELD );

	if ( ! is_array( $options ) ) {
		return $info;
	}

	$info['genesis'] = [
		'label' => __( 'Genesis', 'genesis' ),
	];

	foreach ( $options as $name => $value ) {
		if ( $value ) {
			$info['genesis']['fields'][ $name ] = [
				'label'   => $name,
				'value'   => $value,
				'private' => 'update_email_address' === $name,
			];
		}
	}

	return $info;
}

add_filter( 'debug_information', 'genesis_child_theme_recommendations' );
/**
 * Adds child theme recommendations to the Tools -> Site Health -> Info tab
 * under the “Active Theme” panel.
 *
 * @since 3.0.0
 *
 * @param array $info The original debug information.
 * @return array Debug information modified with Genesis recommendations.
 */
function genesis_child_theme_recommendations( $info ) {
	$deprecated_theme_supports = genesis_get_child_theme_recommendations();

	if ( $deprecated_theme_supports ) {
		$info['wp-active-theme']['fields']['genesis_recommendations'] = [
			'label'   => __( 'Genesis recommendations', 'genesis' ),
			'value'   => $deprecated_theme_supports,
			'private' => false,
		];
	}

	return $info;
}

/**
 * Gets recommendations for the active child theme.
 *
 * Helps surface deprecated Genesis features still being used in themes.
 *
 * @since 3.0.0
 *
 * @return string|void Suggestions for improvement, or void if no suggestions.
 */
function genesis_get_child_theme_recommendations() {

	$recommendations = [];

	if ( get_theme_support( 'genesis-responsive-viewport' ) ) {
		$recommendations[] = '"genesis-responsive-viewport" theme support can be removed';
	}

	$accessibility_support = get_theme_support( 'genesis-accessibility' );

	if ( $accessibility_support && in_array( '404-page', $accessibility_support[0], true ) ) {
		$recommendations[] = __( '"404-page" no longer required in "genesis-accessibility" theme support array', 'genesis' );
	}

	if ( $recommendations ) {
		$message = __( 'This theme uses theme supports that are no longer required in Genesis 3.0+: ', 'genesis' );
		return $message . implode( ', ', $recommendations );
	}
}
