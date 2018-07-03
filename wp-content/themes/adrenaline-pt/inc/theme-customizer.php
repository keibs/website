<?php
/**
 * Load the Customizer with some custom extended addons
 *
 * @package adrenaline-pt
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

/**
 * This funtion is only called when the user is actually on the customizer page
 *
 * @param  WP_Customize_Manager $wp_customize
 */
if ( ! function_exists( 'adrenaline_customizer' ) ) {
	function adrenaline_customizer( $wp_customize ) {
		// Add required files.
		AdrenalineHelpers::load_file( '/inc/customizer/class-customize-base.php' );

		new Adrenaline_Customizer_Base( $wp_customize );
	}
	add_action( 'customize_register', 'adrenaline_customizer' );
}


/**
 * Takes care for the frontend output from the customizer and nothing else
 */
if ( ! function_exists( 'adrenaline_customizer_frontend' ) && ! class_exists( 'Adrenaline_Customize_Frontent' ) ) {
	function adrenaline_customizer_frontend() {
		AdrenalineHelpers::load_file( '/inc/customizer/class-customize-frontend.php' );
		$adrenaline_customize_frontent = new Adrenaline_Customize_Frontent();
	}
	add_action( 'init', 'adrenaline_customizer_frontend' );
}
