import { __ } from '@wordpress/i18n';
import { select } from '@wordpress/data';
import { Button, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * Slug.
 *
 * This Component returns the Slug
 * label and button.
 *
 * @since 1.0.0
 *
 * @returns {JSX.Element}
 */
const Slug = () => {
  const[ slug, setSlug ] = useState( '' );
  const postID = select('core/editor').getCurrentPostId();

  return (
    <>
      <p><strong>{ __( 'Slug', 'ai-plus-block-editor' ) }</strong></p>
      <TextControl
        __nextHasNoMarginBottom
        placeholder="/your-article-slug"
        value={ slug }
        onChange={ e => console.log(e) }
      />
      <Button
        variant="primary"
        onClick={ () => { } }
      >
        { __( 'Generate', 'ai-plus-block-editor' ) }
      </Button>
  </>
  )
}

export default Slug;
