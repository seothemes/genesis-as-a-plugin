/**
 * The PostClassTextControl component for use in the Custom Classes panel.
 *
 * @since   3.1.0
 * @package Genesis\JS
 * @author  StudioPress
 * @license GPL-2.0-or-later
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose } from '@wordpress/compose';
import { select, withSelect, withDispatch } from '@wordpress/data';
import { TextControl } from '@wordpress/components';

function PostControl( { postClass, onUpdate } ) {
	return (
		<TextControl
			label={ __( 'Post Class', 'genesis' ) }
			value={ postClass }
			onChange={ ( newClass ) => onUpdate( newClass ) }
		/>
	);
}

export const PostClassTextControl = compose( [
	withSelect( () => {
		return {
			postClass: select( 'core/editor' ).getEditedPostAttribute( 'meta' )._genesis_custom_post_class,
		};
	} ),
	withDispatch( ( dispatch ) => ( {
		onUpdate( newClass ) {
			const currentMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
			const genesisMeta = Object.keys( currentMeta )
				.filter( ( key ) => key.startsWith( '_genesis' ) )
				.reduce( ( obj, key ) => {
					obj[ key ] = currentMeta[ key ];
					return obj;
				}, {} );
			const newMeta = {
				...genesisMeta,
				_genesis_custom_post_class: newClass,
			};
			dispatch( 'core/editor' ).editPost( { meta: newMeta } );
		},
	} ) ),
] )( PostControl );
