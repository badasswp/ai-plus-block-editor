import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import { Button, Icon, TextareaControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import { selectProps } from '../utils/types';
import { editorStore } from '../utils/store';

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

			/**
			 * This function returns a promise that resolves
			 * to the AI generated headline when the Animation
			 * responsible for showing same is completed.
			 *
			 * @since 1.2.0
			 *
			 * @return { Promise<string> } Animated text.
			 */
			const showAnimatedAiText = (): Promise< string > => {
				let limit = 1;

				return new Promise( ( resolve ) => {
					const animatedTextInterval = setInterval( () => {
						if ( aiHeadline.length === limit ) {
							clearInterval( animatedTextInterval );
							resolve( aiHeadline );
						}
						setHeadline( aiHeadline.substring( 0, limit ) );
						limit++;
					}, 5 );
				} );
			};

			showAnimatedAiText().then( ( newHeadline ) => {
				editPost( { meta: { apbe_headline: newHeadline } } );
			} );
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
	const handleSelection = (): void => {
		let limit = 1;

		/**
		 * This function returns a promise that
		 * resolves to the headline when the Animation responsible
		 * for showing the headline is completed.
		 *
		 * @since 1.2.0
		 *
		 * @return { Promise<string> } Animated text.
		 */
		const showAnimatedAiText = (): Promise< string > => {
			return new Promise( ( resolve ) => {
				const animatedTextInterval = setInterval( () => {
					if ( limit === headline.length ) {
						clearInterval( animatedTextInterval );
						resolve( headline );
					}
					editPost( { title: headline.substring( 0, limit ) } );
					limit++;
				}, 5 );
			} );
		};

		showAnimatedAiText().then( ( newHeadline ) => {
			editPost( { meta: { apbe_headline: newHeadline } } );
			savePost();
		} );
	};

	return (
		<>
			<p>
				<strong>{ __( 'Headline', 'ai-plus-block-editor' ) }</strong>
			</p>
			<TextareaControl
				rows={ 4 }
				value={ headline }
				onChange={ ( text ) => setHeadline( text ) }
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
		</>
	);
};

export default Headline;
