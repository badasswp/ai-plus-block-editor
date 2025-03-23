import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';
import { store as noticesStore } from '@wordpress/notices';
import { Button, TextareaControl, Icon } from '@wordpress/components';
import { select, dispatch, useSelect, useDispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';

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
	const [ isLoading, setIsLoading ] = useState( false );
	const { removeNotice } = useDispatch( noticesStore );
	const { editPost, savePost } = dispatch( 'core/editor' ) as any;
	const { getCurrentPostId, getEditedPostAttribute, getEditedPostContent } =
		select( 'core/editor' );

	const content = getEditedPostContent();
	const notices = useSelect(
		( use ) => use( noticesStore ).getNotices(),
		[]
	);

	useEffect( () => {
		setKeywords( getEditedPostAttribute( 'meta' ).apbe_seo_keywords );
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
		notices.forEach( ( notice ) => removeNotice( notice.id ) );
		setIsLoading( true );

		const aiKeywords: string = await apiFetch( {
			path: '/ai-plus-block-editor/v1/sidebar',
			method: 'POST',
			data: {
				id: getCurrentPostId(),
				text: content.text || content,
				feature: 'keywords',
			},
		} );

		/**
		 * This function returns a promise that resolves
		 * to the AI generated SEO keywords when the Animation
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
					if ( aiKeywords.length === limit ) {
						clearInterval( animatedTextInterval );
						resolve( aiKeywords );
					}
					setKeywords( aiKeywords.substring( 0, limit ) );
					limit++;
				}, 5 );
			} );
		};

		showAnimatedAiText().then( ( newKeywords ) => {
			editPost( { meta: { apbe_seo_keywords: newKeywords } } );
		} );

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
				rows={ 7 }
				value={ keywords }
				onChange={ ( text ) => setKeywords( text ) }
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

export default SEO;
