import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons'
import { select, dispatch } from '@wordpress/data';
import { Button, TextareaControl, Icon } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
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
 * @returns {JSX.Element}
 */
const Summary = (): JSX.Element => {
  const[ summary, setSummary ] = useState( '' );
  const[ isLoading, setIsLoading ] = useState( false );
  const { editPost } = dispatch( 'core/editor' ) as any;
  const {
    getCurrentPostId,
    getEditedPostContent,
    getEditedPostAttribute
  } = select( 'core/editor' );

  const content = getEditedPostContent();

  useEffect( () => {
    setSummary( getEditedPostAttribute( 'meta' )['apbe_summary'] );
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
          feature: 'summary'
        },
      }
    );

    const { data: aiSummary } = response as any;

    let limit = 1;

    const showAnimatedAiText = setInterval( () => {
      if ( aiSummary.length === limit ) {
        clearInterval( showAnimatedAiText );
      }
      setSummary( aiSummary.substring( 0, limit ) );
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
    editPost( { excerpt: summary } );
    editPost( { meta: { apbe_summary: summary } } );
  }

  return (
    <>
      <p><strong>{ __( 'Summary', 'ai-plus-block-editor' ) }</strong></p>
      <TextareaControl
        rows={ 4 }
        value={ summary }
        onChange={ text => setSummary( text ) }
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

export default Summary;
