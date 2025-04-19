import React from 'react';
import '@testing-library/jest-dom';
import { render, screen } from '@testing-library/react';

import { useSelect, useDispatch } from '@wordpress/data';

import Slug from '../../src/components/Slug';

jest.mock( '@wordpress/i18n', () => ( {
	__: jest.fn( ( arg ) => arg ),
} ) );

jest.mock( '@wordpress/data', () => ( {
	useSelect: jest.fn(),
	useDispatch: jest.fn(),
} ) );

jest.mock( '@wordpress/notices', () => ( {
	store: 'core/notices',
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

describe( 'Slug', () => {
	beforeEach( () => {
		( useSelect as jest.Mock ).mockReturnValue( {
			postId: 1,
			postContent: 'Hello World',
			postSlug: 'ai-generated-slug',
			notices: [],
		} );

		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: jest.fn(),
			savePost: jest.fn(),
			removeNotice: jest.fn(),
			createErrorNotice: jest.fn(),
		} );
	} );

	afterEach( () => {
		jest.clearAllMocks();
	} );

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
