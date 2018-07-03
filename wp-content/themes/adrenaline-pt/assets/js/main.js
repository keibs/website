/* global AdrenalineVars */

// config
require.config( {
	paths: {
		jquery:          'assets/js/fix.jquery',
		underscore:      'assets/js/fix.underscore',
		util:            'bower_components/bootstrap/js/dist/util',
		alert:           'bower_components/bootstrap/js/dist/alert',
		button:          'bower_components/bootstrap/js/dist/button',
		carousel:        'bower_components/bootstrap/js/dist/carousel',
		collapse:        'bower_components/bootstrap/js/dist/collapse',
		dropdown:        'bower_components/bootstrap/js/dist/dropdown',
		modal:           'bower_components/bootstrap/js/dist/modal',
		scrollspy:       'bower_components/bootstrap/js/dist/scrollspy',
		tab:             'bower_components/bootstrap/js/dist/tab',
		tooltip:         'bower_components/bootstrap/js/dist/tooltip',
		popover:         'bower_components/bootstrap/js/dist/popover',
		stampit:         'assets/js/vendor/stampit',
		SlickCarousel:   'bower_components/slick-carousel/slick/slick',
		isElementInView: 'assets/js/utils/isElementInView',
	}
} );

require.config( {
	baseUrl: AdrenalineVars.pathToTheme
} );

require( [
		'jquery',
		'underscore',
		'isElementInView',
		'assets/js/utils/objectFitFallback',
		'assets/js/portfolio-grid-filter/gridFilter',
		'assets/js/portfolio-grid-filter/sliderFilter',
		'assets/js/utils/easeInOutQuad',
		'vendor/proteusthemes/proteuswidgets/assets/js/NumberCounter',
		'assets/js/theme-slider/slick-carousel',
		'assets/js/theme-slider/vimeo-events',
		'assets/js/theme-slider/youtube-events',
		'assets/js/instagram-widget',
		'assets/js/weather-widget',
		'vendor/proteusthemes/sticky-menu/assets/js/sticky-menu',
		'assets/js/TouchDropdown',
		'SlickCarousel',
		'util',
		'carousel',
		'collapse',
		'tab',
		'modal',
], function ( $, _, isElementInView, objectFitFallback, gridFilter, sliderFilter, easeInOutQuad, NumberCounter, ThemeSlider, VimeoEvents, YoutubeEvents, InstagramWidget, WeatherWidget ) {
	'use strict';

	/**
	 * Footer widgets fix
	 */
	$( '.col-lg-__col-num__' ).removeClass( 'col-lg-__col-num__' ).addClass( 'col-lg-3' );

	/**
	 * Number Counter Widget JS code
	 */
	// Get all number counter widgets
	var $counterWidgets = $( '.number-counters' );

	if ( $counterWidgets.length ) {

		// jQuery easing function: easeInOutQuad, for use in NumberCounter
		easeInOutQuad();

		$counterWidgets.each( function () {
			new NumberCounter( $( this ) );
		} );
	}

	/**
	 * Portfolio grid filtering
	 */
	$( '.portfolio-grid' ).each(function () {
		var hash = window.location.hash,
			portfolioGrid;

		if ( 'slider' === $( this ).data( 'type' ) ) {
			portfolioGrid = sliderFilter({
				$container: $( this ),
			});
		}
		else {
			portfolioGrid = gridFilter({
				$container: $( this ),
			});
		}

		// Getting on visit or if "All" nav button is disabled.
		if ( new RegExp( '^#' + portfolioGrid.hashPrefix ).test( hash ) ) {
			$( this ).find( 'a[href="' + hash.replace( portfolioGrid.hashPrefix, '') + '"]' ).trigger( 'click' );
		}
		else if ( $( this ).find( '.portfolio-grid__nav-item' ).first().not( 'is-active' ) ) {
			// Trigger click for the first nav grid item, if the "All" nav button is missing.
			$(this).find( '.portfolio-grid__nav-item' ).first().children( '.portfolio-grid__nav-link' ).trigger( 'click' );
		}

		// Recalculate the mobile nav height. Fix for both cases above.
		if ( ! portfolioGrid.isDesktopLayout() ) {
			portfolioGrid.initNavHolderHeight();
		}
	});

	/**
	 * Slick carousel for the Person profile widget (from the PW composer package).
	 */
	$( '.js-person-profile-initialize-carousel' ).slick();

	/**
	 * Slick Carousel - Theme Slider
	 */
	(function () {
		var themeSliderInstance = new ThemeSlider( $( '.js-pt-slick-carousel-initialize-slides' ) );
		new VimeoEvents( themeSliderInstance );
		new YoutubeEvents( themeSliderInstance );
	})();

	/**
	 * Slick carousel for the Single portfolio pages (it's hidden by default).
	 */
	(function () {
		var $portfolioSlider = $( '.js-sc-portfolio-slider' ).slick().parent().css( 'visibility', 'visible' );

		// Hook into the 'afterChange' slick carousel event to change the current slider number.
		$portfolioSlider.on( 'afterChange', function( ev, slick, currentSlide ) {
			$(this).parent().find( '.js-sc-portfolio-current-number' ).html( ( '00' + (currentSlide + 1) ).slice(-2) );
		} );
	})();

	/**
	 * Instagram widget - initialize.
	 */
	$( '.js-pw-instagram' ).each( function () {
		new InstagramWidget( $( this ) );
	} );

	/**
	 * Weather widget - initialize.
	 */
	$( '.js-weather' ).each( function () {
		new WeatherWidget( $( this ) );
	} );

	/**
	 * Masnory JS - initialization.
	 *
	 * Check if jquery-masonry is enqueued and so the $.masonry function is available.
	 */
	if ( $.isFunction( $.fn.masonry ) ) {
		$( document ).ready( function() {
			$( '.js-pt-masonry' ).masonry( {
				itemSelector: '.grid-item'
			} );
		} );
	}

	/**
	 * Animate the scroll, when back to top is clicked
	 */
	( function () {
		$( '.js-back-to-top' ).click( function ( ev ) {
			ev.preventDefault();

			$( 'body, html' ).animate( {
				scrollTop: 0
			}, 700 );
		});
	} )();

	/**
	 * Object fit - fallback for old browsers
	 * @return {[type]} [description]
	 */
	(function () {
		if ( ! Modernizr.objectfit ) {
			// slider, page header (single, portfoliop)
			$('.js-object-fit-fallback').each(function () {
				objectFitFallback({
					$container: $(this)
				});
			});
		}
	}());

});
