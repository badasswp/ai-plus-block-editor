import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';
import { store as noticesStore } from '@wordpress/notices';
import { Button, TextareaControl, Icon } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import { selectProps } from '../utils/types';
import { editorStore } from '../utils/store';
import { isAnimationEnabled, showAnimatedAiText } from '../utils';

/**
 * SEO.
 *
 * This Component returns the SEO keywords,
 * label and button.
 *
 * @since 1.1.0
 *
 * @return {JSX.Element} SEO Keywords.
 */
const SEO = (): JSX.Element => {
	const [ keywords, setKeywords ] = useState( '' );
	const { editPost, savePost } = useDispatch( editorStore ) as any;
	const { createNotice, createErrorNotice, removeNotice } =
		useDispatch( noticesStore );
	const { postId, postContent, postKeywords, notices } = useSelect(
		( select ) => {
			const { getNotices } = select( noticesStore );
			const {
				getCurrentPostId,
				getEditedPostContent,
				getEditedPostAttribute,
			} = select( editorStore ) as selectProps;

			return {
				postId: getCurrentPostId(),
				postContent: getEditedPostContent(),
				postKeywords:
					getEditedPostAttribute( 'meta' )?.apbe_seo_keywords,
				notices: getNotices(),
			};
		},
		[]
	);

	useEffect( () => {
		setKeywords( postKeywords );
	}, [ postKeywords ] );

	/**
	 * This function fires when the user clicks
	 * the `Generate` button.
	 *
	 * @since 1.1.0
	 *
	 * @return { void }
	 */
	const handleClick = async (): Promise< void > => {
		notices.forEach( ( notice ) => removeNotice( notice.id ) );
		createNotice(
			'info',
			__(
				'AI is generating text, please hold on for a bit.',
				'ai-plus-block-editor'
			),
			{
				isDismissible: true,
				id: 'apbe-info',
				type: 'snackbar',
			}
		);

		try {
			const aiKeywords: string = await apiFetch( {
				path: '/ai-plus-block-editor/v1/sidebar',
				method: 'POST',
				data: {
					id: postId,
					text: postContent?.text || postContent,
					feature: 'keywords',
				},
			} );

			if ( isAnimationEnabled() ) {
				await showAnimatedAiText( aiKeywords, setKeywords );
			} else {
				setKeywords( aiKeywords );
			}
			editPost( { meta: { apbe_seo_keywords: aiKeywords } } );
			removeNotice( 'apbe-info' );
		} catch ( e ) {
			removeNotice( 'apbe-info' );
			createErrorNotice(
				__(
					'Error! Failed to fetch SEO Keywords. Please check your error logs or console for more info.',
					'ai-plus-block-editor'
				)
			);
			// eslint-disable-next-line
			console.error( e.message );
		}
	};

	/**
	 * This function fires when the user selects
	 * the AI generated result.
	 *
	 * @since 1.1.0
	 *
	 * @return { void }
	 */
	const handleSelection = (): void => {
		editPost( { meta: { apbe_seo_keywords: keywords } } );
		savePost();
	};

	return (
		<>
			<p>
				<strong>
					{ __( 'SEO Keywords', 'ai-plus-block-editor' ) }
				</strong>
			</p>
			<TextareaControl
				data-testid="seo"
				rows={ 7 }
				value={ keywords }
				onChange={ ( text ) => setKeywords( text ) }
				__nextHasNoMarginBottom
			/>
			<div className="apbe-button-group">
				<Button
					variant="primary"
					onClick={ handleClick }
					data-testid="seo-btn"
				>
					{ __( 'Generate', 'ai-plus-block-editor' ) }
				</Button>
				<Button
					variant="secondary"
					onClick={ handleSelection }
					data-testid="seo-check"
				>
					<Icon icon={ check } />
				</Button>
			</div>
		</>
	);
};

export default SEO;
