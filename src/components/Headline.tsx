import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { select, dispatch } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';
import { Button, Icon, TextareaControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';

/**
 * Headline.
 *
 * This Component returns the Headline
 * label and button.
 *
 * @since 1.1.0
 *
 * @returns {JSX.Element}
 */
const Headline = (): JSX.Element => {
  const [ headline, setHeadline ] = useState( '' );
  const [ isLoading, setIsLoading ] = useState( false );
  const { editPost } = dispatch( 'core/editor' ) as any;
  const {
    getCurrentPostId,
    getEditedPostAttribute,
    getEditedPostContent,
    savePost,
  } = select( 'core/editor' );

  const content = getEditedPostContent();

  useEffect( () => {
    setHeadline( getEditedPostAttribute( 'meta' )['apbe_headline'] );
  }, [] )

  /**
   * This function fires when the user clicks
   * the `Generate` button.
   *
   * @since 1.1.0
   *
   * @returns { void }
   */
  const handleClick = async (): Promise<void> => {
    setIsLoading( true );

    const response: string = await apiFetch(
      {
        path: '/ai-plus-block-editor/v1/sidebar',
        method: 'POST',
        data: {
          id: getCurrentPostId(),
          text: content.text || content,
          feature: 'headline'
        },
      }
    );

    const aiHeadline = response.trim().replace( /^"|"$/g, '' );

    let limit = 1;
    const showAnimatedAiText = setInterval( () => {
      if ( aiHeadline.length === limit ) {
        clearInterval( showAnimatedAiText );
      }
      setHeadline( aiHeadline.substring( 0, limit ) );
      limit++;
    }, 5 )

    setIsLoading( false );
  }

  /**
   * This function fires when the user selects
   * the AI generated result.
   *
   * @since 1.1.0
   *
   * @returns { void }
   */
  const handleSelection = (): void => {
    let limit = 1;

    const showAnimatedAiText = setInterval( () => {
      if ( limit === headline.length ) {
        clearInterval( showAnimatedAiText );
      }
      editPost( { title: headline.substring(0, limit) } );
      limit++;
    }, 5 )


    editPost( { meta: { apbe_headline: headline } } );
    savePost();
  }

  return (
    <>
      <p><strong>{ __( 'Headline', 'ai-plus-block-editor' ) }</strong></p>
      <TextareaControl
        rows={ 4 }
        value={ headline }
        onChange={ text => setHeadline( text ) }
        __nextHasNoMarginBottom
      />
      <div className="apbe-button-group">
        <Button
          variant="primary"
          onClick={ handleClick }
        >
          { __( 'Generate', 'ai-plus-block-editor' ) }
        </Button>
        <Button
          variant="secondary"
          onClick={ handleSelection }
        >
          <Icon icon={ check } />
        </Button>
      </div>
      <Toast
        message={ __( 'AI is generating text, please hold on for a bit...' ) }
        isLoading={ isLoading }
      />
    </>
  )
}

export default Headline;
