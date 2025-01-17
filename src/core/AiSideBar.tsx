import { __ } from '@wordpress/i18n';
import { verse } from '@wordpress/icons';
import { Fragment } from '@wordpress/element';
import { PanelBody } from '@wordpress/components';
import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';

import SEO from '../components/SEO';
import Slug from '../components/Slug';
import Summary from '../components/Summary';
import Headline from '../components/Headline';

import '../styles/app.scss';

/**
 * AI SideBar.
 *
 * This function returns a JSX component that comprises
 * the Plugin's Sidebar and controls.
 *
 * @since 1.1.0
 *
 * @returns {JSX.Element}
 */
const AiSideBar = (): JSX.Element => {
  return (
    <Fragment>
      <PluginSidebarMoreMenuItem
        icon={ verse }
        target="apbe-sidebar"
      >
        { __( 'AI + Block Editor', 'ai-plus-block-editor' ) }
      </PluginSidebarMoreMenuItem>
      <PluginSidebar
        name="apbe-sidebar"
        title={ __( 'AI + Block Editor', 'ai-plus-block-editor' ) }
        icon={ verse }
      >
        <PanelBody>
          <div className="apbe-sidebar">
            <ul>
              <li>
                <Headline />
              </li>
              <li>
                <Slug />
              </li>
              <li>
                <SEO />
              </li>
              <li>
                <Summary />
              </li>
            </ul>
          </div>
        </PanelBody>
      </PluginSidebar>
    </Fragment>
  );
};

registerPlugin( 'ai-plus-block-editor', { render: AiSideBar } );
