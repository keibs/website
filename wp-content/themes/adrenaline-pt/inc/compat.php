<?php
/**
 * Compatibility hooks
 *
 * For 3rd party plugins/features.
 *
 * @package adrenaline-pt
 */

/**
 * AdrenalineCompat class with compatibility hooks
 */
class AdrenalineCompat {

	/**
	 * Runs on class initialization. Adds actions and filters.
	 */
	function __construct() {
		add_action( 'activate_breadcrumb-navxt/breadcrumb-navxt.php', array( $this, 'custom_hseparator' ) );
		add_action( 'activate_custom-sidebars-by-proteusthemes/custom-sidebars-by-proteusthemes.php', array( $this, 'detect_custom_sidebar_plugin_activation' ) );
		add_filter( 'portfolioposttype_args', array( $this, 'portfolioposttype_args' ) );
	}

	/**
	 * Set custom separator for NavXT breadcrumbs plugin.
	 */
	function custom_hseparator() {
		add_option( 'bcn_options', array( 'hseparator' => '' ) );
	}

	/**
	 * Set custom sidebars if Custom Sidebars by ProteusThemes plugin is active.
	 */
	function detect_custom_sidebar_plugin_activation() {
		// Get existing sidebars (if any exist).
		$custom_sidebars_options = get_option( 'pt_cs_sidebars', array() );

		// Only add the custom sidebar (Our Services) if the Custom Sidebars by ProteusThemes plugin option pt_cs_sidebars is empty.
		if ( empty( $custom_sidebars_options ) ) {
			update_option( 'pt_cs_sidebars', array(
				array(
					'id'            => 'pt-cs-1',
					'name'          => 'Services',
					'description'   => '',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => '',
				),
			) );
		}
	}

	/**
	 * Change post type labels and arguments for Portfolio Post Type plugin.
	 *
	 * @param array $args Existing arguments.
	 *
	 * @return array
	 */
	function portfolioposttype_args( array $args ) {
		$args['rewrite'] = array( 'slug' => get_theme_mod( 'portfolio_slug', 'portfolio' ) );

		return $args;
	}
}

// Single instance.
$adrenaline_compat = new AdrenalineCompat();
