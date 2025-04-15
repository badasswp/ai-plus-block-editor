/* eslint-disable react-hooks/rules-of-hooks */
import { __ } from '@wordpress/i18n';
import { verse } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { BlockControls } from '@wordpress/block-editor';
import { select, dispatch } from '@wordpress/data';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { ToolbarGroup, ToolbarDropdownMenu } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';
import { getBlockControlOptions } from '../utils';

import '../styles/app.scss';

/**
 * Filter Blocks with AI.
 *
 * @since 1.0.0
 *
 * @param {Object} settings Original block settings
 * @return {Object}         Filtered block settings
 */
export const filterBlockTypesWithAI = ( settings: any ): object => {
	const { name, edit } = settings;

	if ( 'core/paragraph' !== name ) {
		return settings;
	}

	settings.edit = ( props: any ) => {
		const [ tone, setTone ] = useState( '' );
		const [ isLoading, setIsLoading ] = useState( false );

		/**
		 * Get AI generated tone.
		 *
		 * @since 1.0.0
		 *
		 * @param {string} newTone AI tone sent to LLM endpoint.
		 * @return {void}
		 */
		const getTone = async ( newTone: string ): Promise< void > => {
			const { getCurrentPostId } = select( 'core/editor' );
			const { updateBlockAttributes } = dispatch(
				'core/block-editor'
			) as any;
			const { getSelectedBlock, getSelectedBlockClientId } =
				select( 'core/block-editor' );
			const { content } = getSelectedBlock().attributes;

			// Display Toast.
			setIsLoading( true );

			try {
				const aiTone: string = await apiFetch( {
					path: '/ai-plus-block-editor/v1/tone',
					method: 'POST',
					data: {
						id: getCurrentPostId(),
						text: content.text || content,
						newTone,
					},
				} );

				let limit = 1;
				const displayWithEffect = setInterval( () => {
					if ( aiTone.length === limit ) {
						clearInterval( displayWithEffect );
					}
					updateBlockAttributes( getSelectedBlockClientId(), {
						content: aiTone.substring( 0, limit ),
					} );
					limit++;
				}, 5 );

				// Hide Toast.
				setIsLoading( false );
			} catch ( e ) {
				// eslint-disable-next-line no-console
				console.log( e.message );
			}
		};

		useEffect( () => {
			if ( tone ) {
				getTone( tone );
			}
		}, [ tone ] );

		return (
			<Fragment>
				<Toast
					isInEditor={ true }
					message={ __(
						'AI is generating text, please hold on for a bit…',
						'ai-plus-block-editor'
					) }
					isLoading={ isLoading }
				/>
				<BlockControls>
					<ToolbarGroup>
						<ToolbarDropdownMenu
							icon={ verse }
							label={ __( 'AI + Block Editor' ) }
							controls={ getBlockControlOptions( setTone ) }
						/>
					</ToolbarGroup>
				</BlockControls>
				{ edit( props ) }
			</Fragment>
		);
	};

	return settings;
};

addFilter( 'blocks.registerBlockType', 'apbe/ai', filterBlockTypesWithAI );
