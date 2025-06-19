import '@testing-library/jest-dom';
import { act, render, fireEvent, waitFor } from '@testing-library/react';

import apiFetch from '@wordpress/api-fetch';
import { useSelect, useDispatch } from '@wordpress/data';

import SEO from '../../src/components/SEO';

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

	TextareaControl: jest.fn( ( { rows, value, onChange } ) => {
		return (
			<>
				<textarea
					rows={ rows }
					onChange={ ( e ) => onChange( e.target.value ) }
					value={ value }
				/>
			</>
		);
	} ),
} ) );

jest.mock( '@wordpress/api-fetch', () => jest.fn() );

describe( 'SEO', () => {
	beforeEach( () => {
		( useSelect as jest.Mock ).mockReturnValue( {
			postId: 1,
			postContent: 'Hello World',
			postKeywords: 'AI generated SEO...',
			notices: [],
		} );

		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: jest.fn(),
			savePost: jest.fn(),
			removeNotice: jest.fn(),
			createErrorNotice: jest.fn(),
		} );

		jest.spyOn( console, 'error' ).mockImplementation( () => {} );
	} );

	afterEach( () => {
		jest.clearAllMocks();
	} );

	it( 'renders SEO Keywords component', () => {
		const { container, getByText, getByRole } = render( <SEO /> );

		expect( container ).toMatchSnapshot();

		expect( getByText( 'AI generated SEO...' ) ).toBeInTheDocument();
		expect( getByText( 'SEO Keywords' ) ).toBeVisible();
		expect( getByRole( 'button', { name: 'Icon' } ) ).toBeVisible();
		expect( getByRole( 'button', { name: 'Icon' } ) ).toHaveClass(
			'secondary'
		);
		expect( getByRole( 'button', { name: 'Generate' } ) ).toBeVisible();
		expect( getByRole( 'button', { name: 'Generate' } ) ).toHaveClass(
			'primary'
		);
	} );

	it( 'renders fetched API data from AI LLM', async () => {
		const mockEditPost = jest.fn();
		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () => Promise.resolve( 'hello, world, beautiful' ) )
		);

		const { getByText, getByRole } = render( <SEO /> );

		expect( getByText( 'AI generated SEO...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 1 );
			expect(
				getByText( 'hello, world, beautiful' )
			).toBeInTheDocument();
		} );
	} );

	it( 'renders error notice on API fail', async () => {
		const mockCreateErrorNotice = jest.fn();
		( useDispatch as jest.Mock ).mockReturnValue( {
			createErrorNotice: mockCreateErrorNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockRejectedValueOnce(
			new Error( 'AI LLM down...' )
		);

		const { getByText, getByRole } = render( <SEO /> );

		expect( getByText( 'AI generated SEO...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockCreateErrorNotice ).toHaveBeenCalledTimes( 1 );
			expect( getByText( 'AI generated SEO...' ) ).toBeInTheDocument();
		} );
	} );

	it( 'saves the selected AI SEO Keywords', async () => {
		const mockEditPost = jest.fn();
		const mockSavePost = jest.fn();
		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
			savePost: mockSavePost,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () => Promise.resolve( 'hello, world, beautiful' ) )
		);

		const { getByText, getByRole } = render( <SEO /> );

		expect( getByText( 'AI generated SEO...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 1 );
			expect(
				getByText( 'hello, world, beautiful' )
			).toBeInTheDocument();
		} );

		const icon = getByRole( 'button', { name: 'Icon' } );
		await act( async () => {
			fireEvent.click( icon );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 2 );
			expect( mockSavePost ).toHaveBeenCalledTimes( 1 );
		} );
	} );
} );
