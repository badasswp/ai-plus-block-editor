import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';
import { store as noticesStore } from '@wordpress/notices';
import { Button, TextControl, Icon } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import { selectProps } from '../utils/types';
import { editorStore } from '../utils/store';
import { isAnimationEnabled, showAnimatedAiText } from '../utils';

/**
 * Slug.
 *
 * This Component returns the Slug
 * label and button.
 *
 * @since 1.1.0
 *
 * @return {JSX.Element} Slug.
 */
const Slug = (): JSX.Element => {
	const [ slug, setSlug ] = useState( '' );
	const { editPost, savePost } = useDispatch( editorStore ) as any;
	const { createNotice, createErrorNotice, removeNotice } =
		useDispatch( noticesStore );
	const { postId, postContent, postSlug, notices } = useSelect(
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
				postSlug: getEditedPostAttribute( 'meta' )?.apbe_slug,
				notices: getNotices(),
			};
		},
		[]
	);

	useEffect( () => {
		setSlug( postSlug );
	}, [ postSlug ] );

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
			const aiSlug: string = await apiFetch( {
				path: '/ai-plus-block-editor/v1/sidebar',
				method: 'POST',
				data: {
					id: postId,
					text: postContent?.text || postContent,
					feature: 'slug',
				},
			} );

			if ( isAnimationEnabled() ) {
				await showAnimatedAiText( aiSlug, setSlug );
			} else {
				setSlug( aiSlug );
			}
			editPost( { slug: aiSlug } );
			editPost( { meta: { apbe_slug: aiSlug } } );
			removeNotice( 'apbe-info' );
		} catch ( e ) {
			removeNotice( 'apbe-info' );
			createErrorNotice(
				__(
					'Error! Failed to fetch Slug. Please check your error logs or console for more info.',
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
		editPost( { slug } );
		editPost( { meta: { apbe_slug: slug } } );
		savePost();
	};

	return (
		<>
			<p>
				<strong>{ __( 'Slug', 'ai-plus-block-editor' ) }</strong>
			</p>
			<TextControl
				data-testid="slug"
				placeholder="your-article-slug"
				value={ slug }
				onChange={ ( text ) => setSlug( text ) }
				__nextHasNoMarginBottom
				__next40pxDefaultSize
			/>
			<div className="apbe-button-group">
				<Button
					variant="primary"
					onClick={ handleClick }
					data-testid="slug-btn"
				>
					{ __( 'Generate', 'ai-plus-block-editor' ) }
				</Button>
				<Button
					variant="secondary"
					onClick={ handleSelection }
					data-testid="slug-check"
				>
					<Icon icon={ check } />
				</Button>
			</div>
		</>
	);
};

export default Slug;
