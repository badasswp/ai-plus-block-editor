import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';

import Slug from '../../src/components/Slug';

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
	TextControl: jest.fn(
		( {
			placeholder,
			value,
			onChange,
			__nextHasNoMarginBottom = true,
		} ) => {
			return (
				__nextHasNoMarginBottom && (
					<>
						<input
							placeholder={ placeholder }
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
									apbe_slug: 'ai-generated-slug',
							  }
							: {}
					),
			  }
			: {}
	),
} ) );

describe( 'Slug', () => {
	it( 'renders the Slug text input and 2 buttons', () => {
		const { container } = render( <Slug /> );

		// Expect Component to look like so:
		expect( container.innerHTML ).toBe(
			`<p><strong>Slug</strong></p><input placeholder="your-article-slug" value="ai-generated-slug"><div class="apbe-button-group"><button class="primary">Generate</button><button class="secondary">Icon</button></div>`
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
