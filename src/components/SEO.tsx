import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';
import { store as noticesStore } from '@wordpress/notices';
import { Button, TextareaControl, Icon } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';

interface selectProps {
	getCurrentPostId: () => number;
	getEditedPostContent: () => any;
	getEditedPostAttribute: ( attribute: string ) => any;
}

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
	const editorStore = 'core/editor';
	const [ keywords, setKeywords ] = useState( '' );
	const [ isLoading, setIsLoading ] = useState( false );
	const { editPost, savePost } = useDispatch( editorStore ) as any;
	const { createErrorNotice, removeNotice } = useDispatch( noticesStore );
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
		setIsLoading( true );

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
		} catch ( e ) {
			createErrorNotice( e.message );
			setIsLoading( false );
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
