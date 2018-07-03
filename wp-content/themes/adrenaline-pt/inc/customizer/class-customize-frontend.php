<?php
/**
 * Class which handles the output of the WP customizer on the frontend.
 * Meaning that this stuff loads always, no matter if the global $wp_cutomize
 * variable is present or not.
 *
 * @package adrenaline-pt
 */

/**
 * Customizer frontend related code
 */
class Adrenaline_Customize_Frontent {

	/**
	 * Add actions to load the right staff at the right places (header, footer).
	 */
	function __construct() {
		add_action( 'wp_enqueue_scripts' , array( $this, 'output_customizer_css' ), 20 );
		add_action( 'wp_head' , array( $this, 'head_output' ) );
		add_action( 'wp_footer' , array( $this, 'footer_output' ) );
	}

	/**
	 * This will output the custom WordPress settings to the live theme's WP head.
	 *
	 * Used by hook: 'wp_head'
	 *
	 * @see add_action( 'wp_head' , array( $this, 'head_output' ) );
	 */
	public static function output_customizer_css() {
		$css_string = self::get_customizer_css();

		if ( $css_string ) {
			wp_add_inline_style( self::get_inline_styles_handler(), $css_string );
		}
	}

	/**
	 * This will get custom WordPress settings to the live theme's WP head.
	 *
	 * Used by hook: 'wp_head'
	 *
	 * @see add_action( 'wp_head' , array( $this, 'head_output' ) );
	 */
	public static function get_customizer_css() {
		$css = array();

		$css[] = self::get_customizer_colors_css();
		$css[] = self::get_variable_logo_width_css();
		$css[] = self::get_page_header_bg_image();
		$css[] = self::get_body_bg_image();
		$css[] = self::get_custom_css();

		return join( PHP_EOL, $css );
	}


	/**
	 * Woocommerce css handler if woo is active, main css handler otherwise
	 *
	 * @return string
	 */
	public static function get_inline_styles_handler() {
		if ( AdrenalineHelpers::is_woocommerce_active() ) {
			return 'adrenaline-woocommerce';
		}

		return 'adrenaline-main';
	}


	/**
	 * Branding CSS, generated dynamically and cached stringifyed in db
	 *
	 * @return string CSS
	 */
	public static function get_customizer_colors_css() {
		$out = '';

		$cached_css = get_theme_mod( 'cached_css', '' );

		$out .= '/* WP Customizer start */' . PHP_EOL;
		$out .= apply_filters( 'adrenaline_cached_css', $cached_css );
		$out .= PHP_EOL . '/* WP Customizer end */';

		return $out;
	}


	/**
	 * Custom CSS, written in customizer
	 *
	 * @return string CSS
	 */
	public static function get_custom_css() {
		$out      = '';
		$user_css = get_theme_mod( 'custom_css', '' );

		if ( strlen( $user_css ) ) {
			$out .= PHP_EOL . '/* User custom CSS start */' . PHP_EOL;
			$out .= $user_css . PHP_EOL; // No need to filter this, because it is 100% custom code.
			$out .= PHP_EOL . '/* User custom CSS end */' . PHP_EOL;
		}

		return $out;
	}


	/**
	 * Custom logo width CSS, defined with setting "header_logo_width"
	 *
	 * @return string CSS
	 */
	public static function get_variable_logo_width_css() {
		$header_logo_width = get_theme_mod( 'header_logo_width', 180 );

		return sprintf( '
			@media (min-width: 992px) {
				.header__logo--default {
					width: %1$dpx;
				}
				.header__widgets {
					width: calc(100%% - %1$dpx);
					margin-left: %1$dpx;
				}
				.header__navigation {
					width: calc(100%% - %1$dpx);
				}
			}',
			absint( $header_logo_width )
		);
	}


	/**
	 * Page header background image
	 *
	 * @return string CSS
	 */
	public static function get_page_header_bg_image() {
		// Don't output header bg CSS on Front Page with Slider page template.
		if ( is_page_template( array( 'template-front-page-slider.php', 'template-front-page-slider-alt.php' ) ) ) {
			return '';
		}

		$out                           = '';
		$page_header_bg_img            = get_theme_mod( 'page_header_bg_img', '' );
		$page_header_bg_img_repeat     = get_theme_mod( 'page_header_bg_img_repeat', 'repeat' );
		$page_header_bg_img_position_x = get_theme_mod( 'page_header_bg_img_position_x', 'left' );
		$page_header_bg_img_attachment = get_theme_mod( 'page_header_bg_img_attachment', 'scroll' );

		if ( '' !== $page_header_bg_img ) {
			$out = '.page-header__container {';
			$out .= " background-image: url('$page_header_bg_img');";
			$out .= " background-repeat: $page_header_bg_img_repeat;";
			$out .= " background-position: top $page_header_bg_img_position_x;";
			$out .= " background-attachment: $page_header_bg_img_attachment;";
			$out .= '}';
		}

		return $out;
	}


	/**
	 * Body background image
	 *
	 * @return string CSS
	 */
	public static function get_body_bg_image() {
		$out                    = '';
		$body_bg_img            = get_theme_mod( 'body_bg_img', '' );
		$body_bg_img_repeat     = get_theme_mod( 'body_bg_img_repeat', 'repeat' );
		$body_bg_img_position_x = get_theme_mod( 'body_bg_img_position_x', 'left' );
		$body_bg_img_attachment = get_theme_mod( 'body_bg_img_attachment', 'scroll' );

		if ( ! empty( $body_bg_img ) ) {
			$out = 'body .boxed-container {';
			$out .= " background-image: url('$body_bg_img');";
			$out .= " background-repeat: $body_bg_img_repeat;";
			$out .= " background-position: top $body_bg_img_position_x;";
			$out .= " background-attachment: $body_bg_img_attachment;";
			$out .= '}';
		}

		return $out;
	}


	/**
	 * Outputs the code in head of the every page
	 *
	 * Used by hook: add_action( 'wp_head' , array( $this, 'head_output' ) );
	 */
	public static function head_output() {
		// Custom JS from the customizer.
		$script = get_theme_mod( 'custom_js_head', '' );

		if ( ! empty( $script ) ) {
			echo PHP_EOL . $script . PHP_EOL;
		}
	}


	/**
	 * Outputs the code in footer of the every page, right before closing </body>
	 *
	 * Used by hook: add_action( 'wp_footer' , array( $this, 'footer_output' ) );
	 */
	public static function footer_output() {
		$script = get_theme_mod( 'custom_js_footer', '' );

		if ( ! empty( $script ) ) {
			echo PHP_EOL . $script . PHP_EOL;
		}
	}
}
