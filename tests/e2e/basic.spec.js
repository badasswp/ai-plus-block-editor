import { test, expect } from '@wordpress/e2e-test-utils-playwright';

export async function createNewPost( page ) {
	await page.goto( '/wp-admin/post-new.php' );
	await page.waitForSelector( '.edit-post-layout' );
}

test.describe( 'AI + Block Editor', () => {
	test.beforeEach( async ( { page } ) => {
		createNewPost( page );
	} );

	test( 'displays the plugin icon', async ( { page } ) => {
		const closeIcon = page.getByRole( 'button', { name: 'Close' } );

		await expect( closeIcon ).toBeVisible();
		await closeIcon.click();

		const pluginIcon = page.getByRole( 'button', {
			name: 'AI + Block Editor',
		} );

		await expect( pluginIcon ).toBeVisible();
	} );

	test( 'displays AI plugin sidebar with controls', async ( { page } ) => {
		const closeIcon = page.getByRole( 'button', { name: 'Close' } );

		await expect( closeIcon ).toBeVisible();
		await closeIcon.click();

		// Click Plugin icon.
		await expect(
			page.getByRole( 'button', {
				name: 'AI + Block Editor',
			} )
		).toBeVisible();
		await page
			.getByRole( 'button', {
				name: 'AI + Block Editor',
			} )
			.click();

		// Expect to see Plugin Sidebar label & controls.
		await expect(
			page.getByRole( 'heading', { name: 'AI + Block Editor' } )
		).toBeVisible();

		await expect( page.getByText( 'Switch AI Provider' ) ).toBeVisible();
		await expect( page.getByText( 'Headline' ) ).toBeVisible();
		await expect( page.getByText( 'Slug' ) ).toBeVisible();
		await expect( page.getByText( 'SEO Keywords' ) ).toBeVisible();
		await expect( page.getByText( 'Summary' ) ).toBeVisible();
		await expect( page.getByText( 'Social Media Hashtags' ) ).toBeVisible();

		await expect( page.getByTestId( 'headline' ) ).toBeVisible();
		await expect( page.getByTestId( 'slug' ) ).toBeVisible();
		await expect( page.getByTestId( 'seo' ) ).toBeVisible();
		await expect( page.getByTestId( 'summary' ) ).toBeVisible();
		await expect( page.getByTestId( 'social' ) ).toBeVisible();
		await expect( page.getByTestId( 'switcher' ) ).toBeVisible();
	} );

	test( 'it toggles switcher correctly', async ( { page } ) => {
		const closeIcon = page.getByRole( 'button', { name: 'Close' } );

		await expect( closeIcon ).toBeVisible();
		await closeIcon.click();

		await page.getByRole( 'link', { name: 'View Posts' } ).click();
		await expect(
			page.getByRole( 'link', { name: 'Posts', exact: true } )
		).toBeVisible();

		// Go to plugin options page.
		await page.getByRole( 'link', { name: 'AI + Block Editor' } ).click();
		await expect(
			page.getByRole( 'heading', { name: 'AI + Block Editor' } )
		).toBeVisible();

		// Enable DeepSeek selection
		await page
			.getByRole( 'combobox' )
			.selectOption( { label: 'DeepSeek' } );

		// Save plugin options.
		await page.getByRole( 'button', { name: 'Save Changes' } ).click();

		// Now create a new post.
		await page.getByRole( 'link', { name: 'Posts', exact: true } ).click();
		await page
			.getByLabel( 'Main menu', { exact: true } )
			.getByRole( 'link', { name: 'Add Post' } )
			.click();

		await expect( closeIcon ).toBeVisible();
		await closeIcon.click();

		// Click Plugin icon.
		await expect(
			page.getByRole( 'button', {
				name: 'AI + Block Editor',
			} )
		).toBeVisible();
		await page
			.getByRole( 'button', {
				name: 'AI + Block Editor',
			} )
			.click();

		// Check that switcher has now changed.
		await expect( page.getByTestId( 'switcher' ) ).toBeVisible();
		await expect( page.getByTestId( 'switcher' ) ).toHaveValue(
			/DeepSeek/i
		);
	} );

	test( 'it displays error if wrong API keys is set', async ( { page } ) => {
		const closeIcon = page.getByRole( 'button', { name: 'Close' } );

		await expect( closeIcon ).toBeVisible();
		await closeIcon.click();

		await page.getByRole( 'link', { name: 'View Posts' } ).click();
		await expect(
			page.getByRole( 'link', { name: 'Posts', exact: true } )
		).toBeVisible();

		// Go to plugin options page.
		await page.getByRole( 'link', { name: 'AI + Block Editor' } ).click();
		await expect(
			page.getByRole( 'heading', { name: 'AI + Block Editor' } )
		).toBeVisible();

		// Enable DeepSeek selection
		await page
			.getByRole( 'combobox' )
			.selectOption( { label: 'DeepSeek' } );

		// Enable DeepSeek toggle
		await expect(
			page.locator( 'input[name="deepseek_enable"]' )
		).toBeVisible();
		await page.locator( 'input[name="deepseek_enable"]' ).click();

		// Fill in API Keys
		await expect(
			page.locator( 'input[name="deepseek_token"]' )
		).toBeVisible();
		await page
			.locator( 'input[name="deepseek_token"]' )
			.fill( 'incorrect-api-key' );

		// Save plugin options.
		await page.getByRole( 'button', { name: 'Save Changes' } ).click();

		// Now create a new post.
		await page.getByRole( 'link', { name: 'Posts', exact: true } ).click();
		await page
			.getByLabel( 'Main menu', { exact: true } )
			.getByRole( 'link', { name: 'Add Post' } )
			.click();

		await expect( closeIcon ).toBeVisible();
		await closeIcon.click();

		// Click Plugin icon.
		await expect(
			page.getByRole( 'button', {
				name: 'AI + Block Editor',
			} )
		).toBeVisible();
		await page
			.getByRole( 'button', {
				name: 'AI + Block Editor',
			} )
			.click();

		// Click Generate Headline button.
		await expect( page.getByTestId( 'headline-btn' ) ).toBeVisible();
		await page.getByTestId( 'headline-btn' ).click();

		// Confirm error notice is visible.
		await expect(
			page
				.getByLabel( 'Editor content' )
				.getByText( 'Error! Failed to fetch' )
		).toBeVisible();
	} );
} );
