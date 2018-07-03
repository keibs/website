<?php
/**
 * Load here all the individual widgets
 *
 * @package adrenaline-pt
 */

// ProteusWidgets init.
new ProteusWidgets;

// Require the individual widgets.
add_action( 'widgets_init', function () {
	// Custom widgets in the theme.
	$adrenaline_custom_widgets = array(
		'widget-call-to-action',
		'widget-portfolio-grid',
		'widget-instagram',
		'widget-special-offer',
		'widget-image-banner',
		'widget-weather',
		'widget-special-title',
		'widget-latest-posts',
		'widget-timetable',
	);

	foreach ( $adrenaline_custom_widgets as $file ) {
		AdrenalineHelpers::load_file( sprintf( '/inc/widgets/%s.php', $file ) );
	}

	// Relying on composer's autoloader, just provide classes from ProteusWidgets.
	register_widget( 'PW_Brochure_Box' );
	register_widget( 'PW_Facebook' );
	register_widget( 'PW_Featured_Page' );
	register_widget( 'PW_Icon_Box' );
	register_widget( 'PW_Opening_Time' );
	register_widget( 'PW_Skype' );
	register_widget( 'PW_Social_Icons' );
	register_widget( 'PW_Testimonials' );
	register_widget( 'PW_Person_Profile' );
	register_widget( 'PW_Accordion' );
	register_widget( 'PW_Number_Counter' );
	register_widget( 'PW_Pricing_List' );
} );
