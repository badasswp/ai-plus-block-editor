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
 * Social.
 *
 * This Component returns the Social media
 * trending hash-tags & keywords.
 *
 * @since 1.5.0
 *
 * @return {JSX.Element} Social Hashtags.
 */
const Social = (): JSX.Element => {
	const [ social, setSocial ] = useState( '' );
	const { editPost, savePost } = useDispatch( editorStore ) as any;
	const { createNotice, createErrorNotice, removeNotice } =
		useDispatch( noticesStore );
	const { postId, postContent, postSocial, notices } = useSelect(
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
				postSocial: getEditedPostAttribute( 'meta' )?.apbe_social,
				notices: getNotices(),
			};
		},
		[]
	);

	useEffect( () => {
		setSocial( postSocial );
	}, [ postSocial ] );

	/**
	 * This function fires when the user clicks
	 * the `Generate` button.
	 *
	 * @since 1.5.0
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
			const aiSocial: string = await apiFetch( {
				path: '/ai-plus-block-editor/v1/sidebar',
				method: 'POST',
				data: {
					id: postId,
					text: postContent?.text || postContent,
					feature: 'social',
				},
			} );

			if ( isAnimationEnabled() ) {
				await showAnimatedAiText( aiSocial, setSocial );
			} else {
				setSocial( aiSocial );
			}
			editPost( { meta: { apbe_social: aiSocial } } );
			removeNotice( 'apbe-info' );
		} catch ( e ) {
			removeNotice( 'apbe-info' );
			createErrorNotice(
				__(
					'Error! Failed to fetch Hashtags. Please check your error logs or console for more info.',
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
	 * @since 1.5.0
	 *
	 * @return { void }
	 */
	const handleSelection = (): void => {
		editPost( { meta: { apbe_social: social } } );
		savePost();
	};

	return (
		<>
			<p>
				<strong>
					{ __( 'Social Media Hashtags', 'ai-plus-block-editor' ) }
				</strong>
			</p>
			<TextareaControl
				data-testid="social"
				rows={ 4 }
				value={ social }
				onChange={ ( text ) => setSocial( text ) }
				__nextHasNoMarginBottom
			/>
			<div className="apbe-button-group">
				<Button
					variant="primary"
					onClick={ handleClick }
					data-testid="social-btn"
				>
					{ __( 'Generate', 'ai-plus-block-editor' ) }
				</Button>
				<Button
					variant="secondary"
					onClick={ handleSelection }
					data-testid="social-check"
				>
					<Icon icon={ check } />
				</Button>
			</div>
		</>
	);
};

export default Social;
