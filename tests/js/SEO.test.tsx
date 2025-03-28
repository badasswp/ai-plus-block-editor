import React from 'react';
import '@testing-library/jest-dom';
import { render, screen } from '@testing-library/react';

import SEO from '../../src/components/SEO';

jest.mock( '@wordpress/i18n', () => ( {
	__: jest.fn( ( arg ) => arg ),
} ) );

jest.mock( '@wordpress/components', () => ( {
	Button: jest.fn( ( { children, variant, onClick } ) => {
		return (
			<>
				<button className={ variant } onClick={ onClick }>
					{ children }
				</button>
			</>
		);
	} ),
	Icon: jest.fn( () => {
		return <>Icon</>;
	} ),
	TextareaControl: jest.fn(
		( { rows, value, onChange, __nextHasNoMarginBottom = true } ) => {
			return (
				__nextHasNoMarginBottom && (
					<>
						<textarea
							rows={ rows }
							onChange={ onChange }
							value={ value }
						/>
					</>
				)
			);
		}
	),
} ) );

jest.mock( '@wordpress/data', () => ( {
	useSelect: jest.fn(),
	useDispatch: jest.fn( () => ( {
		removeNotice: jest.fn(),
	} ) ),
	dispatch: jest.fn( ( store ) =>
		store === 'core/editor'
			? {
					editPost: jest.fn(),
			  }
			: {}
	),
	select: jest.fn( ( store ) =>
		store === 'core/editor'
			? {
					savePost: jest.fn(),
					getCurrentPostId: jest.fn(),
					getEditedPostContent: jest.fn(),
					getEditedPostAttribute: jest.fn( ( attribute ) =>
						attribute === 'meta'
							? {
									apbe_seo_keywords: 'AI generated SEO...',
							  }
							: {}
					),
			  }
			: {}
	),
	createReduxStore: jest.fn(),
	register: jest.fn(),
} ) );

describe( 'SEO', () => {
	it( 'renders the SEO textarea and 2 buttons', () => {
		const { container } = render( <SEO /> );

		// Expect Component to look like so:
		expect( container.innerHTML ).toBe(
			`<p><strong>SEO Keywords</strong></p><textarea rows="7">AI generated SEO...</textarea><div class="apbe-button-group"><button class="primary">Generate</button><button class="secondary">Icon</button></div>`
		);

		// Assert the Generate button is displayed.
		const generateButton = screen.getByText( 'Generate' );
		expect( generateButton ).toHaveClass( 'primary' );
		expect( generateButton ).toBeInTheDocument();
		expect( generateButton ).toBeInstanceOf( HTMLButtonElement );

		// Assert the Select button is displayed.
		const selectButton = screen.getByText( 'Icon' );
		expect( selectButton ).toHaveClass( 'secondary' );
		expect( selectButton ).toBeInTheDocument();
		expect( selectButton ).toBeInstanceOf( HTMLButtonElement );
	} );
} );
