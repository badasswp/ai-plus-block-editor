import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import { Button, Icon, TextareaControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import { selectProps } from '../utils/types';
import { editorStore } from '../utils/store';
import { isAnimationEnabled, showAnimatedAiText } from '../utils';

/**
 * Headline.
 *
 * This Component returns the Headline
 * label and button.
 *
 * @since 1.1.0
 *
 * @return {JSX.Element} Headline.
 */
const Headline = (): JSX.Element => {
	const [ headline, setHeadline ] = useState( '' );
	const { editPost, savePost } = useDispatch( editorStore ) as any;
	const { createNotice, createErrorNotice, removeNotice } =
		useDispatch( noticesStore );
	const { postId, postContent, postHeadline, notices } = useSelect(
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
				postHeadline: getEditedPostAttribute( 'meta' )?.apbe_headline,
				notices: getNotices(),
			};
		},
		[]
	);

	useEffect( () => {
		setHeadline( postHeadline );
	}, [ postHeadline ] );

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
			const response: string = await apiFetch( {
				path: '/ai-plus-block-editor/v1/sidebar',
				method: 'POST',
				data: {
					id: postId,
					text: postContent?.text || postContent,
					feature: 'headline',
				},
			} );

			const aiHeadline = response.trim().replace( /^"|"$/g, '' );

			if ( isAnimationEnabled() ) {
				await showAnimatedAiText( aiHeadline, setHeadline );
			} else {
				setHeadline( aiHeadline );
			}
			editPost( { meta: { apbe_headline: aiHeadline } } );
			removeNotice( 'apbe-info' );
		} catch ( e ) {
			removeNotice( 'apbe-info' );
			createErrorNotice(
				__(
					'Error! Failed to fetch Headline. Please check your error logs or console for more info.',
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
	const handleSelection = async (): Promise< void > => {
		if ( isAnimationEnabled() ) {
			await showAnimatedAiText( headline, ( title ) =>
				editPost( { title } )
			);
		} else {
			editPost( { title: headline } );
		}

		editPost( { meta: { apbe_headline: headline } } );
		savePost();
	};

	return (
		<>
			<p>
				<strong>{ __( 'Headline', 'ai-plus-block-editor' ) }</strong>
			</p>
			<TextareaControl
				data-testid="headline"
				rows={ 4 }
				value={ headline }
				onChange={ ( text ) => setHeadline( text ) }
				__nextHasNoMarginBottom
			/>
			<div className="apbe-button-group">
				<Button
					variant="primary"
					onClick={ handleClick }
					data-testid="headline-btn"
				>
					{ __( 'Generate', 'ai-plus-block-editor' ) }
				</Button>
				<Button
					variant="secondary"
					onClick={ handleSelection }
					data-testid="headline-check"
				>
					<Icon icon={ check } />
				</Button>
			</div>
		</>
	);
};

export default Headline;
