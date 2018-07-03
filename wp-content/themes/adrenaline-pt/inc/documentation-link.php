<?php
/**
 * Add the link to documentation under Appearance in the wp-admin
 *
 * @package adrenaline-pt
 */

if ( ! function_exists( 'adrenaline_add_docs_page' ) ) {

	/**
	 * Creates the Documentation page under the Appearance menu in wp-admin.
	 */
	function adrenaline_add_docs_page() {
		add_theme_page(
			esc_html__( 'Documentation', 'adrenaline-pt' ),
			esc_html__( 'Documentation', 'adrenaline-pt' ),
			'',
			'proteusthemes-theme-docs',
			'adrenaline_docs_page_output'
		);
	}
	add_action( 'admin_menu', 'adrenaline_add_docs_page' );

	/**
	 * This is the callback function for the Documentation page above.
	 * This is the output of the Documentation page.
	 */
	function adrenaline_docs_page_output() {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Documentation', 'adrenaline-pt' ); ?></h2>

			<p>
				<strong><a href="https://www.proteusthemes.com/docs/adrenaline/" class="button button-primary " target="_blank"><?php esc_html_e( 'Click here to see online documentation of the theme!', 'adrenaline-pt' ); ?></a></strong>
			</p>
		</div>
		<?php
	}
}
