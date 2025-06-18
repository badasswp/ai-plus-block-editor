import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';
import { store as noticesStore } from '@wordpress/notices';
import { Button, TextareaControl, Icon } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';

import { selectProps } from '../utils/types';
import { editorStore } from '../utils/store';

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
	const [ isLoading, setIsLoading ] = useState( false );
	const { editPost, savePost } = useDispatch( editorStore ) as any;
	const { createErrorNotice, removeNotice } = useDispatch( noticesStore );
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
		setIsLoading( true );

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

			/**
			 * This function returns a promise that resolves
			 * to the AI generated social media hashtags and keywords
			 * when the Animation responsible for showing same is completed.
			 *
			 * @since 1.5.0
			 *
			 * @return { Promise<string> } Animated text.
			 */
			const showAnimatedAiText = (): Promise< string > => {
				let limit = 1;

				return new Promise( ( resolve ) => {
					const animatedTextInterval = setInterval( () => {
						if ( aiSocial.length === limit ) {
							clearInterval( animatedTextInterval );
							resolve( aiSocial );
						}
						setSocial( aiSocial.substring( 0, limit ) );
						limit++;
					}, 5 );
				} );
			};

			showAnimatedAiText().then( ( newSocial ) => {
				editPost( { meta: { apbe_social: newSocial } } );
			} );

			setIsLoading( false );
		} catch ( e ) {
			createErrorNotice( e.message );
			setIsLoading( false );
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
				rows={ 4 }
				value={ social }
				onChange={ ( text ) => setSocial( text ) }
				__nextHasNoMarginBottom
			/>
			<div className="apbe-button-group">
				<Button variant="primary" onClick={ handleClick }>
					{ __( 'Generate', 'ai-plus-block-editor' ) }
				</Button>
				<Button variant="secondary" onClick={ handleSelection }>
					<Icon icon={ check } />
				</Button>
			</div>
			<Toast
				message={ __(
					'AI is generating text, please hold on for a bit.',
					'ai-plus-block-editor'
				) }
				isLoading={ isLoading }
			/>
		</>
	);
};

export default Social;
