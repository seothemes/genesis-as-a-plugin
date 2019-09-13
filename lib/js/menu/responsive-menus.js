/* eslint-disable vars-on-top */
/**
 * Add the accessible responsive menu.
 *
 * @version 1.2.0
 *
 * @author  StudioPress
 * @link    https://github.com/copyblogger/responsive-menus/
 * @license GPL-2.0-or-later
 * @package StudioPress\Genesis\JS
 */

( function( document, $, undefined ) {

	'use strict';

	// eslint-disable-next-line camelcase
	var genesisMenuParams     = 'undefined' === typeof genesis_responsive_menu ? '' : genesis_responsive_menu,
		genesisMenusUnchecked = genesisMenuParams.menuClasses,
		genesisMenus          = {},
		menusToCombine        = [];

	/**
	 * Validate the menus passed by the theme with what's being loaded on the
	 * page, and pass the new and accurate information to our new data.
	 *
	 * @param  {genesisMenusUnchecked} Raw data from the localized script in the theme.
	 *
	 * @return {array} genesisMenus array gets populated with updated data.
	 * @return {array} menusToCombine array gets populated with relevant data.
	 */
	$.each( genesisMenusUnchecked, function( group ) {

		// Mirror our group object to populate.
		genesisMenus[ group ] = [];

		// Loop through each instance of the specified menu on the page.
		$.each( this, function( key, value ) {

			var menuString = value,
				$menu      = $( value );

			// If there is more than one instance, append the index and update array.
			if ( 1 < $menu.length ) {

				$.each( $menu, function( key, value ) {
					var newString = menuString + '-' + key;

					$( this ).addClass( newString.replace( '.', '' ) );

					genesisMenus[ group ].push( newString );

					if ( 'combine' === group ) {
						menusToCombine.push( newString );
					}
				});

			} else if ( 1 === $menu.length ) {

				genesisMenus[ group ].push( menuString );

				if ( 'combine' === group ) {
					menusToCombine.push( menuString );
				}
			}
		});
	});

	// Make sure there is something to use for the 'others' array.
	if ( 'undefined' === typeof genesisMenus.others ) {
		genesisMenus.others = [];
	}

	// If there's only one menu on the page for combining, push it to the 'others' array and nullify our 'combine' variable.
	if ( 1 === menusToCombine.length ) {
		genesisMenus.others.push( menusToCombine[ 0 ]);
		genesisMenus.combine = null;
		menusToCombine = null;
	}

	var genesisMenu         = {},
		mainMenuButtonClass = 'menu-toggle',
		subMenuButtonClass  = 'sub-menu-toggle',
		responsiveMenuClass = 'genesis-responsive-menu';

	// Initialize.
	genesisMenu.init = function() {

		// Exit early if there are no menus to do anything.
		if ( 0 === $( _getAllMenusArray() ).length ) {
			return;
		}

		var menuIconClass    = 'undefined' !== typeof genesisMenuParams.menuIconClass ? genesisMenuParams.menuIconClass : 'dashicons-before dashicons-menu',
			subMenuIconClass = 'undefined' !== typeof genesisMenuParams.subMenuIconClass ? genesisMenuParams.subMenuIconClass : 'dashicons-before dashicons-arrow-down-alt2',
			toggleButtons    = {
				menu: $( '<button />', {
						'class': mainMenuButtonClass,
						'aria-expanded': false,
						'aria-pressed': false
					}
				).append( genesisMenuParams.mainMenu ),
				submenu: $(
					'<button />', {
						'class': subMenuButtonClass,
						'aria-expanded': false,
						'aria-pressed': false
					}
				).append( $( '<span />', {
					'class': 'screen-reader-text',
					'text': genesisMenuParams.subMenu
				}) )
			};

		// Add the responsive menu class to the active menus.
		_addResponsiveMenuClass();

		// Add the main nav button to the primary menu, or exit the plugin.
		_addMenuButtons( toggleButtons );

		var mainMenuButton = $( '.' + mainMenuButtonClass ),
			subMenuButton  = $( '.' + subMenuButtonClass );

		// Setup additional classes.
		mainMenuButton.addClass( menuIconClass );
		subMenuButton.addClass( subMenuIconClass );
		mainMenuButton.on( 'click.genesisMenu-mainbutton', _mainmenuToggle ).each( _addClassID );
		subMenuButton.on( 'click.genesisMenu-subbutton', _submenuToggle );
		$( window ).on( 'resize.genesisMenu', _doResize ).triggerHandler( 'resize.genesisMenu' );
	};

	/**
	 * Add menu toggle button to appropriate menus.
	 *
	 * @param toggleButtons Object of menu buttons to use for toggles.
	 *
	 * @return {void}
	 */
	function _addMenuButtons( toggleButtons ) {

		// Apply sub menu toggle to each sub-menu found in the menuList.
		$( _getMenuSelectorString( genesisMenus ) ).find( '.sub-menu' ).before( toggleButtons.submenu );

		if ( null !== menusToCombine ) {

			var menusToToggle = genesisMenus.others.concat( menusToCombine[ 0 ]);

			// Only add menu button the primary menu and navs NOT in the combine variable.
			$( _getMenuSelectorString( menusToToggle ) ).before( toggleButtons.menu );

		} else {

			// Apply the main menu toggle to all menus in the list.
			$( _getMenuSelectorString( genesisMenus.others ) ).before( toggleButtons.menu );
		}
	}

	/**
	 * Add the responsive menu class.
	 *
	 * @return {void}
	 */
	function _addResponsiveMenuClass() {
		$( _getMenuSelectorString( genesisMenus ) ).addClass( responsiveMenuClass );
	}

	/**
	 * Execute our responsive menu functions on window resizing.
	 *
	 * @return {void}
	 */
	function _doResize() {
		var buttons = $( 'button[id^="genesis-mobile-"]' ).attr( 'id' );

		if ( 'undefined' === typeof buttons ) {
			return;
		}

		_maybeClose( buttons );
		_superfishToggle( buttons );
		_changeSkipLink( buttons );
		_combineMenus( buttons );
	}

	/**
	 * Add the nav- class of the related navigation menu as an ID to associated
	 * button (helps target specific buttons outside of context).
	 *
	 * @return {void}
	 */
	function _addClassID() {
		var $this = $( this ),
			nav   = $this.next( 'nav' ),
			id    = 'class';

		$this.attr( 'id', 'genesis-mobile-' + $( nav ).attr( id ).match( /nav-\w*\b/ ) );
	}

	/**
	 * Combine our menus if the mobile menu is visible.
	 *
	 * @params buttons
	 *
	 * @return {void}
	 */
	function _combineMenus( buttons ) {

		// Exit early if there are no menus to combine.
		if ( null === menusToCombine ) {
			return;
		}

		// Split up the menus to combine based on order of appearance in the array.
		var primaryMenu   = menusToCombine[ 0 ],
			combinedMenus = $( menusToCombine ).filter(
				function( index ) {
					if ( 0 < index ) {
						return index;
					}
				}
			);

		// If the responsive menu is active, append items in 'combinedMenus' object to the 'primaryMenu' object.
		if ( 'none' !== _getDisplayValue( buttons ) ) {

			$.each( combinedMenus, function( key, value ) {
				$( value ).find( '.menu > li' ).addClass( 'moved-item-' + value.replace( '.', '' ) ).appendTo( primaryMenu + ' ul.genesis-nav-menu' );
			});

			$( _getMenuSelectorString( combinedMenus ) ).hide();

		} else {

			$( _getMenuSelectorString( combinedMenus ) ).show();

			$.each( combinedMenus, function( key, value ) {
				$( '.moved-item-' + value.replace( '.', '' ) ).appendTo( value + ' ul.genesis-nav-menu' ).removeClass( 'moved-item-' + value.replace( '.', '' ) );
			});
		}
	}

	/**
	 * Action to happen when the main menu button is clicked.
	 *
	 * @return {void}
	 */
	function _mainmenuToggle() {
		var $this     = $( this ),
			animation = 'undefined' !== typeof genesisMenuParams.menuAnimation ? genesisMenuParams.menuAnimation : {};

		_toggleAria( $this, 'aria-pressed' );
		_toggleAria( $this, 'aria-expanded' );
		$this.toggleClass( 'activated' );
		$this.next( 'nav' ).toggleClass( 'nav-visible' );

		if ( animation ) {
			var effect = 'undefined' !== typeof animation.effect ? animation.effect : 'slideToggle';

			$this.next( 'nav' )[ effect ]( animation );
		}
	}

	/**
	 * Action for submenu toggles.
	 *
	 * @return {void}
	 */
	function _submenuToggle() {
		var $this     = $( this ),
			others    = $this.closest( '.menu-item' ).siblings(),
			animation = 'undefined' !== typeof genesisMenuParams.subMenuAnimation ? genesisMenuParams.subMenuAnimation : {};

		_toggleAria( $this, 'aria-pressed' );
		_toggleAria( $this, 'aria-expanded' );
		$this.toggleClass( 'activated' );
		$this.next( '.sub-menu' ).toggleClass( 'sub-menu-visible' );

		if ( animation ) {
			var effect = 'undefined' !== typeof animation.effect ? animation.effect : 'slideToggle';

			$this.next( '.sub-menu' )[ effect ]( animation );
		}

		others.find( '.' + subMenuButtonClass ).removeClass( 'activated' ).attr( 'aria-pressed', 'false' );
		others.find( '.sub-menu' ).removeClass( 'sub-menu-visible' );

		if ( 'undefined' !== typeof effect ) {
			var hideFunction = 'slideToggle' === effect ? 'slideUp' : 'fadeToggle' === effect ? 'fadeOut' : false;

			others.find( '.sub-menu' )[ hideFunction ]( animation );
		}
	}

	/**
	 * Activate/deactivate superfish.
	 *
	 * @params buttons
	 *
	 * @return {void}
	 */
	function _superfishToggle( buttons ) {
		var _superfish = $( '.' + responsiveMenuClass + ' .js-superfish' ),
			$args      = 'destroy';

		if ( 'function' !== typeof _superfish.superfish ) {
			return;
		}

		if ( 'none' === _getDisplayValue( buttons ) ) {
			$args = {
				'delay': 100,
				'animation': {
					'opacity': 'show',
					'height': 'show'
				},
				'dropShadows': false,
				'speed': 'fast'
			};
		}

		_superfish.superfish( $args );
	}

	/**
	 * Modify skip link to match mobile buttons.
	 *
	 * @param buttons
	 *
	 * @return {void}
	 */
	function _changeSkipLink( buttons ) {

		// Start with an empty array.
		var menuToggleList = _getAllMenusArray();

		// Exit out if there are no menu items to update.
		if ( 0 < ! $( menuToggleList ).length ) {
			return;
		}

		$.each( menuToggleList, function( key, value ) {

			var newValue  = value.replace( '.', '' ),
				startLink = 'genesis-' + newValue,
				endLink   = 'genesis-mobile-' + newValue;

			if ( 'none' === _getDisplayValue( buttons ) ) {
				startLink = 'genesis-mobile-' + newValue;
				endLink = 'genesis-' + newValue;
			}

			var $item = $( '.genesis-skip-link a[href="#' + startLink + '"]' );

			if ( null !== menusToCombine && value !== menusToCombine[ 0 ]) {
				$item.toggleClass( 'skip-link-hidden' );
			}

			if ( 0 < $item.length ) {
				var link = $item.attr( 'href' );
				link = link.replace( startLink, endLink );

				$item.attr( 'href', link );
			}
		});
	}

	/**
	 * Close all the menu toggles if buttons are hidden.
	 *
	 * @param buttons
	 *
	 * @return {void}
	 */
	function _maybeClose( buttons ) {
		if ( 'none' !== _getDisplayValue( buttons ) ) {
			return true;
		}

		$( '.' + mainMenuButtonClass + ', .' + responsiveMenuClass + ' .sub-menu-toggle' )
		.removeClass( 'activated' )
		.attr( 'aria-expanded', false )
		.attr( 'aria-pressed', false );

		$( '.' + responsiveMenuClass + ', .' + responsiveMenuClass + ' .sub-menu' )
		.attr( 'style', '' );
	}

	/**
	 * Generic function to get the display value of an element.
	 *
	 * @param  {id} $id ID to check
	 *
	 * @return {string} CSS value of display property
	 */
	function _getDisplayValue( $id ) {
		var element = document.getElementById( $id ),
			style   = window.getComputedStyle( element );

		return style.getPropertyValue( 'display' );
	}

	/**
	 * Toggle aria attributes.
	 *
	 * @param  {button}     $this passed through
	 * @param  {aria-label} attribute aria attribute to toggle
	 *
	 * @return {bool}       from _ariaReturn
	 */
	function _toggleAria( $this, attribute ) {
		$this.attr( attribute, function( index, value ) {
			return 'false' === value;
		});
	}

	/**
	 * Helper function to return a comma separated string of menu selectors.
	 *
	 * @param itemArray Array of menu items to loop through.
	 *
	 * @return {string} Comma-separated string.
	 */
	function _getMenuSelectorString( itemArray ) {

		var itemString = $.map(
			itemArray, function( value, key ) {
				return value;
			}
		);

		return itemString.join( ',' );
	}

	/**
	 * Helper function to return a group array of all the menus in
	 * both the 'others' and 'combine' arrays.
	 *
	 * @return {array} Array of all menu items as class selectors.
	 */
	function _getAllMenusArray() {

		// Start with an empty array.
		var menuList = [];

		// If there are menus in the 'menusToCombine' array, add them to 'menuList'.
		if ( null !== menusToCombine ) {

			$.each( menusToCombine, function( key, value ) {
				menuList.push( value.valueOf() );
			});
		}

		// Add menus in the 'others' array to 'menuList'.
		$.each( genesisMenus.others, function( key, value ) {
			menuList.push( value.valueOf() );
		});

		if ( 0 < menuList.length ) {
			return menuList;
		} else {
			return null;
		}
	}

	$( document ).ready( function() {

		if ( null !== _getAllMenusArray() ) {
			genesisMenu.init();
		}
	});

}( document, jQuery ) );
