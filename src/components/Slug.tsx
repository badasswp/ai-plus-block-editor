import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { select, dispatch } from '@wordpress/data';
import { Button, TextControl, Icon } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';

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
	const [ isLoading, setIsLoading ] = useState( false );
	const { editPost, savePost } = dispatch( 'core/editor' ) as any;
	const { getCurrentPostId, getEditedPostContent, getEditedPostAttribute } =
		select( 'core/editor' );

	const content = getEditedPostContent();

	useEffect( () => {
		setSlug( getEditedPostAttribute( 'meta' ).apbe_slug );
	}, [ getEditedPostAttribute ] );

	/**
	 * This function fires when the user clicks
	 * the `Generate` button.
	 *
	 * @since 1.1.0
	 *
	 * @return { void }
	 */
	const handleClick = async (): Promise< void > => {
		setIsLoading( true );

		const aiSlug: string = await apiFetch( {
			path: '/ai-plus-block-editor/v1/sidebar',
			method: 'POST',
			data: {
				id: getCurrentPostId(),
				text: content.text || content,
				feature: 'slug',
			},
		} );

		let limit = 1;
		const showAnimatedAiText = setInterval( () => {
			if ( aiSlug.length === limit ) {
				clearInterval( showAnimatedAiText );
			}
			setSlug( aiSlug.substring( 0, limit ) );
			limit++;
		}, 5 );

		setIsLoading( false );
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
				placeholder="your-article-slug"
				value={ slug }
				onChange={ ( text ) => setSlug( text ) }
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
					'AI is generating text, please hold on for a bit…'
				) }
				isLoading={ isLoading }
			/>
		</>
	);
};

export default Slug;
