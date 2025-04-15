import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';
import { store as noticesStore } from '@wordpress/notices';
import { Button, TextareaControl, Icon } from '@wordpress/components';
import { select, dispatch, useSelect, useDispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';

/**
 * Summary.
 *
 * This Component returns the Summary
 * label and button.
 *
 * @since 1.1.0
 *
 * @return {JSX.Element} Summary.
 */
const Summary = (): JSX.Element => {
	const [ summary, setSummary ] = useState( '' );
	const [ isLoading, setIsLoading ] = useState( false );
	const { editPost, savePost } = dispatch( 'core/editor' ) as any;
	const { createErrorNotice, removeNotice } = useDispatch( noticesStore );
	const { getCurrentPostId, getEditedPostContent, getEditedPostAttribute } =
		select( 'core/editor' );

	const content = getEditedPostContent();
	const notices = useSelect(
		( use ) => use( noticesStore ).getNotices(),
		[]
	);

	useEffect( () => {
		setSummary( getEditedPostAttribute( 'meta' ).apbe_summary );
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

		try {
			const aiSummary: string = await apiFetch( {
				path: '/ai-plus-block-editor/v1/sidebar',
				method: 'POST',
				data: {
					id: getCurrentPostId(),
					text: content.text || content,
					feature: 'summary',
				},
			} );

			/**
			 * This function returns a promise that resolves
			 * to the AI generated summary when the Animation
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
						if ( aiSummary.length === limit ) {
							clearInterval( animatedTextInterval );
							resolve( aiSummary );
						}
						setSummary( aiSummary.substring( 0, limit ) );
						limit++;
					}, 5 );
				} );
			};

			showAnimatedAiText().then( ( newSummary ) => {
				editPost( { excerpt: newSummary } );
				editPost( { meta: { apbe_summary: newSummary } } );
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
		editPost( { excerpt: summary } );
		editPost( { meta: { apbe_summary: summary } } );
		savePost();
	};

	return (
		<>
			<p>
				<strong>{ __( 'Summary', 'ai-plus-block-editor' ) }</strong>
			</p>
			<TextareaControl
				rows={ 4 }
				value={ summary }
				onChange={ ( text ) => setSummary( text ) }
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
					'AI is generating text, please hold on for a bit…',
					'ai-plus-block-editor'
				) }
				isLoading={ isLoading }
			/>
		</>
	);
};

export default Summary;
