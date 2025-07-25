import { __ } from '@wordpress/i18n';
import { verse } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { BlockControls } from '@wordpress/block-editor';
import { select, dispatch } from '@wordpress/data';
import { ToolbarGroup, ToolbarDropdownMenu } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import { selectProps, selectBlockProps, noticeProps } from '../utils/types';
import { getAllowedBlocks, getBlockMenuOptions } from '../utils';
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

	if ( getAllowedBlocks().indexOf( name ) === -1 ) {
		return settings;
	}

	settings.edit = ( props: any ) => {
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
			const { createNotice, createErrorNotice, removeNotice } = dispatch(
				noticesStore
			) as any;
			const { updateBlockAttributes } = dispatch(
				blockEditorStore
			) as any;
			const { getNotices } = select( noticesStore ) as noticeProps;

			createNotice(
				'success',
				__(
					'AI is generating text, please hold on for a bit.',
					'ai-plus-block-editor'
				),
				{
					isDismissible: true,
					id: 'apbe-tone-success',
					type: 'snackbar',
				}
			);

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

				getNotices().forEach( ( notice: any ) =>
					removeNotice( notice.id )
				);
			} catch ( e ) {
				createErrorNotice( e.message, {
					type: 'snackbar',
					isDismissible: true,
				} );
			}
		};

		return (
			<>
				<BlockControls>
					<ToolbarGroup>
						<ToolbarDropdownMenu
							icon={ verse }
							label={ __( 'AI + Block Editor' ) }
							controls={ getBlockMenuOptions( getTone ) }
						/>
					</ToolbarGroup>
				</BlockControls>
				{ edit( props ) }
			</>
		);
	};

	return settings;
};

addFilter( 'blocks.registerBlockType', 'apbe/ai', filterBlockTypesWithAI );
