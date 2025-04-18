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
import { selectProps, selectBlockProps } from '../utils/types';
import { editorStore, noticesStore, blockEditorStore } from '../utils/store';

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
		 * Get Tone Params.
		 *
		 * This function retrieves the current post ID,
		 * selected block ID, and block content.
		 *
		 * @since 1.4.0
		 * @return {Object} Object containing postId, blockId and blockContent.
		 */
		const getToneParams = (): any => {
			const { getCurrentPostId } = select( editorStore ) as selectProps;
			const { getSelectedBlock, getSelectedBlockClientId } = select(
				blockEditorStore
			) as selectBlockProps;

			return {
				postId: getCurrentPostId(),
				blockId: getSelectedBlockClientId(),
				blockContent: getSelectedBlock()?.attributes?.content || '',
			};
		};

		/**
		 * Get AI generated tone.
		 *
		 * @since 1.0.0
		 *
		 * @param {string} newTone AI tone sent to LLM endpoint.
		 * @return {void}
		 */
		const getTone = async ( newTone: string ): Promise< void > => {
			const { postId, blockId, blockContent } = getToneParams();
			const { createErrorNotice } = dispatch( noticesStore ) as any;
			const { updateBlockAttributes } = dispatch(
				blockEditorStore
			) as any;

			setIsLoading( true );

			try {
				const aiTone: string = await apiFetch( {
					path: '/ai-plus-block-editor/v1/tone',
					method: 'POST',
					data: {
						id: postId,
						text: blockContent?.text || blockContent,
						newTone,
					},
				} );

				let limit = 1;
				const displayWithEffect = setInterval( () => {
					if ( aiTone.length === limit ) {
						clearInterval( displayWithEffect );
					}
					updateBlockAttributes( blockId, {
						content: aiTone.substring( 0, limit ),
					} );
					limit++;
				}, 5 );

				setIsLoading( false );
			} catch ( e ) {
				createErrorNotice( e.message, {
					type: 'snackbar',
					isDismissible: true,
				} );
			}
		};

		useEffect( () => {
			if ( tone ) {
				getTone( tone );
			}
			// eslint-disable-next-line react-hooks/exhaustive-deps
		}, [ tone ] );

		return (
			<Fragment>
				<Toast
					isInEditor={ true }
					message={ __(
						'AI is generating text, please hold on for a bit.',
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
