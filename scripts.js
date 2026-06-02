/* eslint-disable camelcase, no-console, no-undef, no-unused-vars */
( function ( $ ) {
	jQuery( document ).ready( function () {
		/**
		 * Handles the installation of WordPress plugins via AJAX.
		 *
		 * This jQuery function is triggered on the click event of elements
		 * with the class 'more-plugins-install'. It sends an AJAX request to the server
		 * to install the specified plugin and updates the button's text and class
		 * on success.
		 *
		 * @since 1.0.0
		 */
		jQuery( document ).on(
			'click',
			'.more-plugins-install',
			function ( e ) {
				e.preventDefault();
				const button = jQuery( this );

				button.text( 'Installing...' );
				button.removeAttr( 'href' );
				button.attr( 'aria-disabled', 'true' );
				button.css( 'pointer-events', 'none' );
				button.css( 'opacity', '0.5' );

				jQuery.ajax( {
					type: 'POST',
					url: ajax_badasswp.ajax_url,
					data: {
						slug: button.attr( 'data-slug' ),
						file: button.attr( 'data-file' ),
						action: 'badasswp_install_plugin',
						nonce: ajax_badasswp.nonce,
					},
					success( response ) {
						button
							.removeClass( 'button more-plugins-install' )
							.addClass( 'button-primary more-plugins-activate' );
						button.text( 'Activate' );
						button.attr( 'aria-disabled', 'false' );
						button.css( 'pointer-events', 'auto' );
						button.css( 'opacity', '1' );
					},
					error( xhr, status, error ) {
						console.error( xhr.responseText, status, error );
					},
				} );
			}
		);

		/**
		 * Handles the activation of WordPress plugins via AJAX.
		 *
		 * This jQuery function is triggered on the click event of elements
		 * with the class 'more-plugins-activate'. It sends an AJAX request to the server
		 * to activate the specified plugin and updates the button's text and class
		 * on success.
		 *
		 * @since 1.0.0
		 */
		jQuery( document ).on(
			'click',
			'.more-plugins-activate',
			function ( e ) {
				e.preventDefault();
				const button = jQuery( this );

				button.text( 'Activating...' );
				button.removeAttr( 'href' );
				button.attr( 'aria-disabled', 'true' );
				button.css( 'pointer-events', 'none' );
				button.css( 'opacity', '0.5' );

				jQuery.ajax( {
					type: 'POST',
					url: ajax_badasswp.ajax_url,
					data: {
						slug: button.attr( 'data-slug' ),
						file: button.attr( 'data-file' ),
						action: 'badasswp_activate_plugin',
						nonce: ajax_badasswp.nonce,
					},
					success( response ) {
						// Refresh the page to reflect the activated plugin in the UI.
						window.location.href = window.location.href;
					},
					error( xhr, status, error ) {
						console.error( xhr.responseText, status, error );
					},
				} );
			}
		);
	} );
} )( jQuery );
