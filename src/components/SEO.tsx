import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { select, dispatch } from '@wordpress/data';
import { Button, TextareaControl, Icon } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
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
 * @returns {JSX.Element}
 */
const SEO = (): JSX.Element => {
  const[ keywords, setKeywords ] = useState( '' );
  const [ isLoading, setIsLoading ] = useState( false );
  const { editPost } = dispatch( 'core/editor' ) as any;
  const {
    getCurrentPostId,
    getEditedPostAttribute,
    getEditedPostContent,
  } = select( 'core/editor' );

  const content = getEditedPostContent();

  useEffect( () => {
    setKeywords( getEditedPostAttribute( 'meta' )['apbe_seo_keywords'] );
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

    const response = await apiFetch(
      {
        path: '/ai-plus-block-editor/v1/sidebar',
        method: 'POST',
        data: {
          id: getCurrentPostId(),
          text: content.text || content,
          feature: 'keywords'
        },
      }
    );

    const { data: aiKeywords } = response as any;

    let limit = 1;

    const showAnimatedAiText = setInterval( () => {
      if ( aiKeywords.length === limit ) {
        clearInterval( showAnimatedAiText );
      }
      setKeywords( aiKeywords.substring( 0, limit ) );
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
    editPost( { meta: { apbe_seo_keywords: keywords } } );
  }

  return (
    <>
      <p><strong>{ __( 'SEO Keywords', 'ai-plus-block-editor' ) }</strong></p>
      <TextareaControl
        rows={ 7 }
        value={ keywords }
        onChange={ text => setKeywords( text ) }
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

export default SEO;
