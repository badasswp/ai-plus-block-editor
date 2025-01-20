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
  const { editPost } = dispatch( 'core/editor' );
  const {
    getCurrentPostId,
    getEditedPostAttribute,
    getEditedPostContent,
  } = select( 'core/editor' );

  const content = getEditedPostContent();

  useEffect( () => {
    setHeadline( getEditedPostAttribute( 'title' ) );
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
    // Display Toast.
    setIsLoading( true );

    const response = await apiFetch(
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

    const { data } = response as any;

    let limit = 1;

    const showAnimatedText = setInterval( () => {
      // Clear Interval.
      if ( limit === caption.length ) {
        clearInterval( showAnimatedText );
      }

      // Update the Headline.
      setHeadline( caption.substring( 0, limit ) );
      limit++;
    }, 5 )

    // Hide Toast.
    setIsLoading( false );
  }

  return (
    <>
      <p><strong>{ __( 'Headline', 'ai-plus-block-editor' ) }</strong></p>
      <TextareaControl
        rows={ 4 }
        value={ headline }
        onChange={ e => console.log(e) }
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
