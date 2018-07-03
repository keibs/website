/* global AdrenalineVars */
define( ['jquery', 'underscore'], function ( $, _ ) {
	'use strict';

	var config = {
		ctaClass:   '.js-pw-instagram-cta',
		photoClass: '.pw-instagram__photo',
		eventNS: 'instagramWidget',
	};

	var template = {
		item:  _.template( '<a class="pw-instagram__item" href="<%= link %>" target="_blank"><img class="pw-instagram__photo" src="<%= image %>" alt="<%= title %>"></a>' ),
		error: _.template( '<p class="pw-instagram__error"><%= errorCode %> - <%= errorMessage %></p>' ),
	};

	var InstagramWidget = function ( $widget ) {
		this.$widget = $widget;

		this.accessToken = this.$widget.data( 'access-token' );
		this.hasCTA      = this.$widget.data( 'has-cta' );
		this.numImages   = this.$widget.data( 'has-cta' ) ? this.$widget.data( 'num-images' ) - 1 : this.$widget.data( 'num-images' );
		this.ctaPosition = 0;

		this.getDataFromInstagramAPI();

		return this;
	};

	_.extend( InstagramWidget.prototype, {
		/**
		 * Make a GET request to the Instagram API for the latest images.
		 */
		getDataFromInstagramAPI: function () {
			$.ajax({
				method:     "GET",
				url:        AdrenalineVars.ajax_url,
				data:       {
					'action':       'pt_adrenaline_get_instagram_data',
					'security':     AdrenalineVars.ajax_nonce,
					'access_token': this.accessToken,
				},
				context:    this,
				beforeSend: function() {
					this.$widget.append( '<p class="pw-instagram__loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="screen-reader-text">Loading...</span></p>' );
				},
				complete: function() {
					this.$widget.find( '.pw-instagram__loader' ).remove();
				}
			})
			.done( function( response ) {
				this.outputData( JSON.parse( response ) );
			});

			return this;
		},

		/**
		 * Display images or an error.
		 */
		outputData: function ( response ) {

			// Check if the response from instagram API is ok, otherwise display an error.
			if ( 200 === response.meta.code ) {

				// Use the minimal number for images to be display.
				this.numImages = Math.min( this.numImages ,response.data.length );

				if ( this.hasCTA ) {
					this.ctaPosition = this.getCtaPosition( this.numImages );
				}

				// Loop through all images.
				$.each( response.data, _.bind( function( index, obj ) {

					// Exit the loop, if the the number of images to display is reached.
					if ( this.numImages === index ) {
						return false;
					}

					// Reposition CTA element.
					if ( this.ctaPosition === index ) {
						this.repositionCta();
					}

					// Get the caption text or use a default.
					var caption = this.getCaption( obj );

					// Display the instagram image.
					this.$widget.append(
						template.item( {
							link:  obj.link,
							image: obj.images.low_resolution.url,
							title: caption
						} )
					);
				}, this ) );

				// Copy the first image to the CTA box (hidden with CSS) so the box is resized properly
				if ( this.hasCTA ) {
					_.defer( _.bind( function() {
						var $ctaElement = this.$widget.find( config.ctaClass ),
							$instagramImage = $ctaElement.siblings().first().find( config.photoClass );

						$ctaElement.append( $instagramImage.clone() );
					}, this ) );
				}
			}
			else {

				// Display the error.
				this.$widget.append(
					template.error( {
						errorCode: response.meta.code,
						errorMessage: response.meta.error_message
					} )
				);
			}
		},

		/**
		 * Get caption.
		 */
		getCaption: function ( obj ) {
			return obj.caption ? obj.caption.text : 'Instagram image';
		},

		/**
		 * Get CTA position.
		 */
		getCtaPosition: function ( num ) {
			var posModifier = ( 0 === num % 2 ) ? 1 : 0;
			return Math.ceil( ( num / 2 ) + posModifier );
		},

		/**
		 * Reposition CTA element. Detach it and append it to the correct position.
		 */
		repositionCta: function () {
			this.$widget.append( this.$widget.find( config.ctaClass ).detach().css( 'display', 'block' ) );
		},
	} );

	return InstagramWidget;
} );