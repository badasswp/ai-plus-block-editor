import '@testing-library/jest-dom';
import { act, render, fireEvent, waitFor } from '@testing-library/react';

import apiFetch from '@wordpress/api-fetch';
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

	TextControl: jest.fn( ( { placeholder, value, onChange } ) => {
		return (
			<>
				<input
					placeholder={ placeholder }
					onChange={ ( e ) => onChange( e.target.value ) }
					value={ value }
				/>
			</>
		);
	} ),
} ) );

jest.mock( '@wordpress/api-fetch', () => jest.fn() );

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

		jest.spyOn( console, 'error' ).mockImplementation( () => {} );
	} );

	afterEach( () => {
		jest.clearAllMocks();
	} );

	it( 'renders Slug component', () => {
		const { container, getByRole, getByText } = render( <Slug /> );

		expect( container ).toMatchSnapshot();

		expect( getByRole( 'textbox', { name: '' } ) ).toBeInTheDocument();
		expect( getByRole( 'textbox', { name: '' } ) ).toHaveValue(
			'ai-generated-slug'
		);
		expect( getByText( 'Slug' ) ).toBeVisible();
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
		const mockCreateNotice = jest.fn();
		const mockRemoveNotice = jest.fn();

		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
			createNotice: mockCreateNotice,
			removeNotice: mockRemoveNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () => Promise.resolve( 'new-ai-generated-slug' ) )
		);

		const { getByRole } = render( <Slug /> );

		expect( getByRole( 'textbox', { name: '' } ) ).toBeInTheDocument();
		expect( getByRole( 'textbox', { name: '' } ) ).toHaveValue(
			'ai-generated-slug'
		);

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 2 );
			expect( getByRole( 'textbox', { name: '' } ) ).toBeInTheDocument();
			expect( getByRole( 'textbox', { name: '' } ) ).toHaveValue(
				'new-ai-generated-slug'
			);
		} );
	} );

	it( 'renders error notice on API fail', async () => {
		const mockCreateErrorNotice = jest.fn();
		const mockCreateNotice = jest.fn();
		const mockRemoveNotice = jest.fn();

		( useDispatch as jest.Mock ).mockReturnValue( {
			createErrorNotice: mockCreateErrorNotice,
			createNotice: mockCreateNotice,
			removeNotice: mockRemoveNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockRejectedValueOnce(
			new Error( 'AI LLM down...' )
		);

		const { getByRole } = render( <Slug /> );

		expect( getByRole( 'textbox', { name: '' } ) ).toBeInTheDocument();
		expect( getByRole( 'textbox', { name: '' } ) ).toHaveValue(
			'ai-generated-slug'
		);

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockCreateErrorNotice ).toHaveBeenCalledTimes( 1 );
			expect( getByRole( 'textbox', { name: '' } ) ).toBeInTheDocument();
			expect( getByRole( 'textbox', { name: '' } ) ).toHaveValue(
				'ai-generated-slug'
			);
		} );
	} );

	it( 'saves the selected AI Slug', async () => {
		const mockEditPost = jest.fn();
		const mockSavePost = jest.fn();
		const mockCreateNotice = jest.fn();
		const mockRemoveNotice = jest.fn();
		const mockCreateErrorNotice = jest.fn();

		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
			savePost: mockSavePost,
			createNotice: mockCreateNotice,
			removeNotice: mockRemoveNotice,
			createErrorNotice: mockCreateErrorNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () => Promise.resolve( 'new-ai-generated-slug' ) )
		);

		const { getByRole } = render( <Slug /> );

		expect( getByRole( 'textbox', { name: '' } ) ).toBeInTheDocument();
		expect( getByRole( 'textbox', { name: '' } ) ).toHaveValue(
			'ai-generated-slug'
		);

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 2 );
			expect( getByRole( 'textbox', { name: '' } ) ).toBeInTheDocument();
			expect( getByRole( 'textbox', { name: '' } ) ).toHaveValue(
				'new-ai-generated-slug'
			);
		} );

		const icon = getByRole( 'button', { name: 'Icon' } );
		await act( async () => {
			fireEvent.click( icon );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 4 );
			expect( mockSavePost ).toHaveBeenCalledTimes( 1 );
		} );
	} );
} );
