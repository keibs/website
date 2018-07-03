<?php
/**
 * Filters for Adrenaline WP theme
 *
 * @package adrenaline-pt
 */

/**
 * AdrenalineFilters class with filter hooks
 */
class AdrenalineFilters {

	/**
	 * Runs on class initialization. Adds filters and actions.
	 */
	function __construct() {

		// ProteusWidgets.
		add_filter( 'pw/widget_views_path', array( $this, 'set_widgets_view_path' ) );
		add_filter( 'pw/testimonial_widget', array( $this, 'set_testimonial_settings' ) );
		add_filter( 'pw/featured_page_widget_page_box_image_size', array( $this, 'set_page_box_image_size' ) );
		add_filter( 'pw/featured_page_widget_inline_image_size', array( $this, 'set_inline_image_size' ) );
		add_filter( 'pw/latest_news_widget_image_size', array( $this, 'set_latest_news_image_size' ) );
		add_filter( 'pw/featured_page_excerpt_lengths', array( $this, 'set_featured_page_excerpt_lengths' ) );
		add_filter( 'pw/social_icons_fa_icons_list', array( $this, 'social_icons_fa_icons_list' ) );
		add_filter( 'pw/default_social_icon', array( $this, 'default_social_icon' ) );
		add_filter( 'pw/featured_page_fields', array( $this, 'set_featured_page_fields' ) );
		add_filter( 'pw/steps_widget_settings', array( $this, 'set_steps_widget_settings' ) );
		add_filter( 'pw/latest_news_fields', array( $this, 'set_latest_news_fields' ) );
		add_filter( 'pw/latest_news_texts', array( $this, 'set_latest_news_texts' ) );
		add_filter( 'pw/person_profile_widget_settings', array( $this, 'set_person_profile_widget_settings' ) );
		add_filter( 'pw/number_counter_widget', array( $this, 'set_number_counter_widget_settings' ) );

		// Custom tag font size.
		add_filter( 'widget_tag_cloud_args', array( $this, 'set_tag_cloud_sizes' ) );

		// Custom text after excerpt.
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );

		// Footer widgets with dynamic layouts.
		add_filter( 'dynamic_sidebar_params', array( $this, 'footer_widgets_params' ), 9, 1 );

		// Google fonts.
		add_filter( 'adrenaline_pre_google_web_fonts', array( $this, 'additional_fonts' ) );
		add_filter( 'adrenaline_subsets_google_web_fonts', array( $this, 'subsets_google_web_fonts' ) );

		// Page builder.
		add_filter( 'siteorigin_panels_widget_style_fields', array( $this, 'add_fields_to_pagebuilder_widget_panel' ) );
		add_filter( 'siteorigin_panels_widget_style_attributes', array( $this, 'add_attributes_to_pagebuilder_widget_panel' ), 10, 2 );
		add_filter( 'siteorigin_panels_settings_defaults', array( $this, 'siteorigin_panels_settings_defaults' ) );
		add_filter( 'siteorigin_panels_widgets', array( $this, 'add_icons_to_page_builder_for_pw_widgets' ), 15 );
		add_filter( 'siteorigin_panels_widget_dialog_tabs', array( $this, 'siteorigin_panels_add_widgets_dialog_tabs' ), 15 );

		// Embeds.
		add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ), 10, 1 );
		add_filter( 'oembed_result', array( $this, 'oembed_result' ), 10, 3 );
		add_filter( 'oembed_fetch_url', array( $this, 'oembed_fetch_url' ), 10, 3 );

		// Protocols.
		add_filter( 'kses_allowed_protocols', array( $this, 'kses_allowed_protocols' ) );

		// <body> and post class
		add_filter( 'body_class', array( $this, 'body_class' ), 10, 1 );
		add_filter( 'post_class', array( $this, 'post_class' ), 10, 1 );

		// One Click Demo Import plugin.
		add_filter( 'pt-ocdi/import_files', array( $this, 'ocdi_import_files' ) );
		add_action( 'pt-ocdi/after_import', array( $this, 'ocdi_after_import_setup' ) );
		add_filter( 'pt-ocdi/message_after_file_fetching_error', array( $this, 'ocdi_message_after_file_fetching_error' ) );

		// Shortcodes plugin.
		add_filter( 'pt/convert_widget_text', '__return_true' );

		// Instagram widget - AJAX actions.
		add_action( 'wp_ajax_pt_adrenaline_get_instagram_data', array( $this, 'pt_adrenaline_get_instagram_data' ) );
		add_action( 'wp_ajax_nopriv_pt_adrenaline_get_instagram_data', array( $this, 'pt_adrenaline_get_instagram_data' ) );

		// Weather widget - AJAX actions.
		add_action( 'wp_ajax_pt_adrenaline_get_weather_data', array( $this, 'pt_adrenaline_get_weather_data' ) );
		add_action( 'wp_ajax_nopriv_pt_adrenaline_get_weather_data', array( $this, 'pt_adrenaline_get_weather_data' ) );

		// Sticky menu.
		add_filter( 'pt-sticky-menu/theme_panel', array( $this, 'pt_sticky_menu_theme_panel' ) );
		add_filter( 'pt-sticky-menu/settings_default', array( $this, 'pt_sticky_menu_default_settings' ) );

		// Remove references to SiteOrigin Premium.
		add_filter( 'siteorigin_premium_upgrade_teaser', '__return_false' );
	}


	/**
	 * Filter the Testimonial widget fields that the Adrenaline theme will need from ProteusWidgets - Tesimonial widget.
	 *
	 * @param array $attr default attributes.
	 * @return array
	 */
	function set_testimonial_settings( $attr ) {
		$attr['number_of_testimonial_per_slide'] = 3;
		$attr['rating']                          = false;
		$attr['author_description']              = true;
		$attr['author_avatar']                   = false;
		$attr['bootstrap_version']               = 4;
		return $attr;
	}

	/**
	 * Custom tag font size.
	 *
	 * @param array $args default arguments.
	 * @return array
	 */
	function set_tag_cloud_sizes( $args ) {
		$args['smallest'] = 12;
		$args['largest']  = 12;
		$args['unit'] = 'px';
		return $args;
	}


	/**
	 * Custom text after excerpt.
	 *
	 * @param array $more default more value.
	 * @return array
	 */
	function excerpt_more( $more ) {
		return ' &hellip;';
	}


	/**
	 * Filter the dynamic sidebars and alter the BS col classes for the footer widgets.
	 *
	 * @param  array $params parameters of the sidebar.
	 * @return array
	 */
	function footer_widgets_params( $params ) {
		static $counter              = 0;
		static $first_row            = true;
		$footer_widgets_layout_array = AdrenalineHelpers::footer_widgets_layout_array();

		if ( 'footer-widgets' === $params[0]['id'] ) {
			// 'before_widget' contains __col-num__, see inc/theme-sidebars.php.
			$params[0]['before_widget'] = str_replace( '__col-num__', $footer_widgets_layout_array[ $counter ], $params[0]['before_widget'] );

			// First widget in the any non-first row.
			if ( false === $first_row && 0 === $counter ) {
				$params[0]['before_widget'] = '</div><div class="row">' . $params[0]['before_widget'];
			}

			$counter++;
		}

		end( $footer_widgets_layout_array );
		if ( $counter > key( $footer_widgets_layout_array ) ) {
			$counter   = 0;
			$first_row = false;
		}

		return $params;
	}


	/**
	 * Filter setting ProteusWidgets mustache widget views path for Adrenaline.
	 */
	function set_widgets_view_path() {
		return get_template_directory() . '/inc/widgets-views';
	}


	/**
	 * Filter the Featured page widget pw-page-box image size for Adrenaline (ProteusWidgets).
	 *
	 * @param array $image default image parameters.
	 * @return array
	 */
	function set_page_box_image_size( $image ) {
		$image['width']  = 352;
		$image['height'] = 198;
		return $image;
	}


	/**
	 * Filter the pw-latest-news image size for Adrenaline, that is currently used for portfolio grid widget.
	 *
	 * @param array $image default image parameters.
	 * @return array
	 */
	function set_latest_news_image_size( $image ) {
		$image['width']  = 428;
		$image['height'] = 242;
		return $image;
	}


	/**
	 * Set the default FA social icon for Adrenaline (ProteusWidgets).
	 *
	 * @param array $icon default FA icon string.
	 * @return string
	 */
	function default_social_icon( $icon ) {
		return 'fa-facebook';
	}


	/**
	 * Filter the Featured page widget pw-inline image size for Adrenaline (ProteusWidgets).
	 *
	 * @param array $image default image parameters.
	 * @return array
	 */
	function set_inline_image_size( $image ) {
		$image['width']  = 100;
		$image['height'] = 70;
		return $image;
	}

	/**
	 * Filter the Featured page widget excerpt lengths for Adrenaline (ProteusWidgets).
	 *
	 * @param array $excerpt_lengths default excerpt lengths.
	 * @return array
	 */
	function set_featured_page_excerpt_lengths( $excerpt_lengths ) {
		$excerpt_lengths['inline_excerpt'] = 55;
		$excerpt_lengths['block_excerpt']  = 140;
		return $excerpt_lengths;
	}

	/**
	 * Set Latest News widget settings for Adrenaline (ProteusWidgets).
	 *
	 * @param array $defaults default Latest news widget settings.
	 * @return array
	 */
	function set_latest_news_fields( $defaults ) {
		$defaults['featured_type'] = true;
		return $defaults;
	}

	/**
	 * Set Latest News widget texts for Adrenaline (ProteusWidgets).
	 *
	 * @param array $defaults default Latest news widget texts.
	 * @return array
	 */
	function set_latest_news_texts( $defaults ) {
		$defaults['read_more'] = 'Read more';
		return $defaults;
	}

	/**
	 * Set Person Profile widget settings for Adrenaline (ProteusWidgets).
	 *
	 * @param array $defaults default Person Profile settings.
	 * @return array
	 */
	function set_person_profile_widget_settings( $defaults ) {
		$defaults['label_instead_of_tag']      = true;
		$defaults['carousel_instead_of_image'] = true;
		$defaults['skills']                    = true;
		$defaults['tags']                      = true;
		return $defaults;
	}

	/**
	 * Set Number Counter widget settings for Adrenaline (ProteusWidgets).
	 *
	 * @param array $defaults default Number Counter settings.
	 * @return array
	 */
	function set_number_counter_widget_settings( $defaults ) {
		$defaults['progress_bar'] = true;
		return $defaults;
	}

	/**
	 * Filter for the list of Font-Awesome icons in social icons widget in Adrenaline (ProteusWidgets).
	 */
	function social_icons_fa_icons_list() {
		return array(
			'fa-facebook',
			'fa-twitter',
			'fa-youtube',
			'fa-google-plus',
			'fa-pinterest',
			'fa-tumblr',
			'fa-xing',
			'fa-vimeo',
			'fa-linkedin',
			'fa-facebook-square',
			'fa-twitter-square',
			'fa-youtube-square',
			'fa-google-plus-square',
			'fa-pinterest-square',
			'fa-tumblr-square',
			'fa-xing-square',
			'fa-vimeo-square',
			'fa-linkedin-square',
		);
	}

	/**
	 * Return Google fonts and sizes.
	 *
	 * @see https://github.com/grappler/wp-standard-handles/blob/master/functions.php
	 * @param array $fonts google fonts used in the theme.
	 * @return array Google fonts and sizes.
	 */
	function additional_fonts( $fonts ) {

		/* translators: If there are characters in your language that are not supported by Open Sans, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== esc_html_x( 'on', 'Open Sans: on or off', 'adrenaline-pt' ) ) {
			$fonts['Open Sans'] = array(
				'400' => '400',
				'700' => '700',
				'800' => '800',
			);
			$fonts['Gloria Hallelujah'] = array(
				'400' => '400',
			);
		}

		return $fonts;
	}


	/**
	 * Add subsets from customizer, if needed.
	 *
	 * @param array $subsets google font subsets used in the theme.
	 * @return array
	 */
	function subsets_google_web_fonts( $subsets ) {
		$additional_subset = get_theme_mod( 'charset_setting', 'latin' );

		array_push( $subsets, $additional_subset );

		return $subsets;
	}


	/**
	 * Add the "featured widget" checkbox to the PageBuilder widget panel under Design section.
	 *
	 * @link https://siteorigin.com/docs/page-builder/hooks/custom-row-settings/
	 * @param array $fields Array of all existing (default) PageBuilder widget settings fields.
	 * @return array
	 */
	function add_fields_to_pagebuilder_widget_panel( $fields ) {
		$fields['featured_widgets'] = array(
			'name'     => esc_html__( 'Widget Style', 'adrenaline-pt' ),
			'type'     => 'checkbox',
			'label'    => esc_html__( 'Set a box around the widget', 'adrenaline-pt' ),
			'group'    => 'design',
			'priority' => 17,
		);

		return $fields;
	}


	/**
	 * Add the functionality of the above "featured widget" checkbox.
	 *
	 * @link https://siteorigin.com/docs/page-builder/hooks/custom-row-settings/
	 * @param array $attributes Array of all attributes that get applied to the widget on front-end.
	 * @param array $args Array of all settings from the widget panel.
	 * @return array
	 */
	function add_attributes_to_pagebuilder_widget_panel( $attributes, $args ) {
		if ( empty( $attributes['class'] ) ) {
			$attributes['class'] = array();
		}

		if ( ! empty( $args['featured_widgets'] ) ) {
			$attributes['class'][] = 'featured-widget';
		}

		return $attributes;
	}


	/**
	 * Embedded videos and video container around them.
	 *
	 * @param string $html html to be enclosed with responsive HTML.
	 * @return string
	 */
	function embed_oembed_html( $html ) {
		if (
			false !== strstr( $html, 'youtube.com' ) ||
			false !== strstr( $html, 'wordpress.tv' ) ||
			false !== strstr( $html, 'wordpress.com' ) ||
			false !== strstr( $html, 'vimeo.com' )
		) {
			$out = '<div class="embed-responsive  embed-responsive-16by9">' . $html . '</div>';
		} else {
			$out = $html;
		}
		return $out;
	}


	/**
	 * Add more allowed protocols.
	 *
	 * @param array $protocols default protocols.
	 * @return array
	 * @link https://developer.wordpress.org/reference/functions/wp_allowed_protocols/
	 */
	static function kses_allowed_protocols( $protocols ) {
		return array_merge( $protocols, array( 'skype' ) );
	}


	/**
	 * Append the right body classes to the <body>.
	 *
	 * @param  array $classes The default array of classes.
	 * @return array
	 */
	public static function body_class( $classes ) {
		$classes[] = 'adrenaline-pt';

		if ( 'boxed' === get_theme_mod( 'layout_mode', 'wide' ) ) {
			$classes[] = 'boxed';
		}

		return $classes;
	}


	/**
	 * Append the right post classes.
	 *
	 * @param  array $classes The default array of classes.
	 * @return array
	 */
	public static function post_class( $classes ) {
		$classes[] = 'clearfix';
		$classes[] = 'article';

		// Remove the hentry class.
		$classes = array_diff( $classes , array( 'hentry' ) );

		return $classes;
	}


	/**
	 * Change the default settings for SO.
	 *
	 * @param  array $settings default Page Builder settings.
	 * @return array
	 */
	function siteorigin_panels_settings_defaults( $settings ) {
		$settings['title-html']           = '<h3 class="widget-title"><span class="widget-title__inline">{{title}}</span></h3>';
		$settings['full-width-container'] = '.boxed-container';
		$settings['mobile-width']         = '991';
		$settings['post-types'][]         = 'portfolio';

		return $settings;
	}


	/**
	 * Set Featured Page widget fields (ProteusWidgets)
	 *
	 * @param array $fields default settings for Featured page widget.
	 * @return array
	 */
	function set_featured_page_fields( $fields ) {
		$fields['show_read_more_link'] = true;
		return $fields;
	}


	/**
	 * Set Steps widget fields (ProteusWidgets).
	 *
	 * @param array $fields default settings for Steps widget.
	 * @return array
	 */
	function set_steps_widget_settings( $fields ) {
		$fields['use_icons'] = false;
		return $fields;
	}


	/**
	 * Define demo import files for One Click Demo Import plugin.
	 */
	function ocdi_import_files() {
		return array(
			array(
				'import_file_name'       => 'Adrenaline Import',
				'import_file_url'        => 'http://artifacts.proteusthemes.com/xml-exports/adrenaline-latest.xml',
				'import_widget_file_url' => 'http://artifacts.proteusthemes.com/json-widgets/adrenaline.json',
			),
		);
	}


	/**
	 * After import theme setup for One Click Demo Import plugin.
	 */
	function ocdi_after_import_setup() {

		// Menus to Import and assign.
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

		set_theme_mod( 'nav_menu_locations', array(
				'main-menu'   => $main_menu->term_id,
			)
		);

		// Set options for front page and blog page.
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );

		// Set options for Breadcrumbs NavXT
		$breadcrumbs_settings = get_option( 'bcn_options', array() );
		$breadcrumbs_settings['hseparator'] = '';
		$shop_page = get_page_by_title( 'Shop' );
		if ( ! is_null( $shop_page ) ) {
			$breadcrumbs_settings['apost_product_root'] = $shop_page->ID;
		}
		$breadcrumbs_settings['bpost_product_archive_display'] = false;
		$projects_page = get_page_by_title( 'Activities' );
		if ( ! is_null( $projects_page ) ) {
			$breadcrumbs_settings['apost_portfolio_root'] = $projects_page->ID;
		}
		$breadcrumbs_settings['bpost_portfolio_archive_display'] = false;
		$breadcrumbs_settings['bpost_portfolio_taxonomy_display'] = false;
		update_option( 'bcn_options', $breadcrumbs_settings );

		// Set logo in customizer.
		set_theme_mod( 'logo_img', get_template_directory_uri() . '/assets/images/logo.png' );
		set_theme_mod( 'logo2x_img', get_template_directory_uri() . '/assets/images/logo2x.png' );

		// Set page header background pattern.
		set_theme_mod( 'page_header_bg_img', get_template_directory_uri() . '/assets/images/light_pattern.png' );

		esc_html_e( 'After import setup ended!', 'adrenaline-pt' );
	}


	/**
	 * Message for manual demo import for One Click Demo Import plugin.
	 */
	function ocdi_message_after_file_fetching_error() {
		return sprintf( esc_html__( 'Please try to manually import the demo data. Here are instructions on how to do that: %1$sDocumentation: Import XML File%2$s', 'adrenaline-pt' ), '<a href="https://www.proteusthemes.com/docs/adrenaline/#import-xml-file" target="_blank">', '</a>' );
	}


	/**
	 * Add arguments to oembed iframes.
	 *
	 * @param string $html the output.
	 * @param string $url the url used for the embed.
	 * @param array  $args arguments.
	 */
	function oembed_result( $html, $url, $args ) {

		// Modify youtube parameters.
		if ( strstr( $html, 'youtube.com/' ) ) {
			if ( isset( $args['player_id'] ) ) {
				$html = str_replace( '<iframe ', '<iframe id="' . $args['player_id'] . '"', $html );
			}
			if ( isset( $args['api'] ) ) {
				$html = str_replace( '?feature=oembed', '?feature=oembed&enablejsapi=1', $html );
			}
		}

		return $html;
	}


	/**
	 * Modify the oembed urls.
	 * Check the full list of args here: https://developer.vimeo.com/apis/oembed.
	 * You can find the list of defaults providers in WP_oEmbed::__construct().
	 *
	 * @param  string $provider URL of the oEmbed provider.
	 * @param  string $url      URL of the content to be embedded.
	 * @param  array  $args     Arguments, usually passed from a shortcode.
	 * @return string           Modified $provider.
	 */
	function oembed_fetch_url( $provider, $url, $args ) {
		if ( false !== strpos( $provider, 'vimeo.com' ) ) {
			if ( isset( $args['api'] ) ) {
				$provider = add_query_arg( 'api', absint( $args['api'] ), $provider );
			}
			if ( isset( $args['player_id'] ) ) {
				$provider = add_query_arg( 'player_id', esc_attr( $args['player_id'] ), $provider );
			}
		}

		return $provider;
	}


	/**
	 * Add PW widgets to Page Builder group and add icon class.
	 *
	 * @param array $widgets All widgets in page builder list of widgets.
	 *
	 * @return array
	 */
	function add_icons_to_page_builder_for_pw_widgets( $widgets ) {
		foreach ( $widgets as $class => $widget ) {
			if ( strstr( $widget['title'], 'ProteusThemes:' ) ) {
				$widgets[ $class ]['icon']   = 'pw-pb-widget-icon';
				$widgets[ $class ]['groups'] = array( 'pw-widgets' );
			}
		}

		return $widgets;
	}


	/**
	 * Add another tab section in the Page Builder "add new widget" dialog.
	 *
	 * @param array $tabs Existing tabs.
	 *
	 * @return array
	 */
	function siteorigin_panels_add_widgets_dialog_tabs( $tabs ) {
		$tabs['pw_widgets'] = array(
			'title' => esc_html__( 'ProteusThemes Widgets', 'adrenaline-pt' ),
			'filter' => array(
				'groups' => array( 'pw-widgets' ),
			),
		);

		return $tabs;
	}


	/**
	 * AJAX callback:
	 * Instagram widget. Get instagram data, from their API or from cache (WP transient).
	 */
	public function pt_adrenaline_get_instagram_data() {
		// Check AJAX referer for a logged in user - cache issue fix for non logged in users.
		if ( is_user_logged_in() ) {
			check_ajax_referer( 'pt-adrenaline-ajax-verification', 'security' );
		}

		$ajax_response = '';
		$access_token  = $_GET['access_token'];

		// Get instagram data from the instagram API, or from DB, if there is any cached data.
		$data = get_transient( 'pt_adrenaline_instagram_data-' . sanitize_key( $access_token ) );
		if ( false === $data || '-1' === $data ) {
			// Get data from instagram API.
			$instagram_url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $access_token;
			$response      = wp_remote_get( $instagram_url );

			// Return with an error if something went wrong.
			if ( is_wp_error( $response ) ) {
				wp_send_json(
					array(
						'meta' => array(
							'code'          => $response->get_error_code(),
							'error_message' => $response->get_error_message(),
						),
					)
				);
			}

			$ajax_response = wp_remote_retrieve_body( $response );

			// Save the data to cache (WP transient).
			set_transient( 'pt_adrenaline_instagram_data-' . sanitize_key( $access_token ), $ajax_response, 0.5 * HOUR_IN_SECONDS );
		}
		else {
			$ajax_response = $data;
		}

		// Send JSON back to the AJAX call in JS (instagram-widget.js).
		wp_send_json( $ajax_response );
	}


	/**
	 * AJAX callback:
	 * Weather widget. Get weather data, from a weather API or from cache (WP transient).
	 */
	public function pt_adrenaline_get_weather_data() {
		// Check AJAX referer for a logged in user - cache issue fix for non logged in users.
		if ( is_user_logged_in() ) {
			check_ajax_referer( 'pt-adrenaline-ajax-verification', 'security' );
		}

		$ajax_response = '';

		// Get data from the AJAX GET request.
		$latitude  = ! empty( $_GET['latitude'] ) ? $_GET['latitude'] : '';
		$longitude = ! empty( $_GET['longitude'] ) ? $_GET['longitude'] : '';
		$api_key   = get_option( AdrenalineHelpers::create_location_key( 'pt_adrenaline_api_key', $latitude, $longitude ) );

		// Get weather data from DB, if there is any cached data, else get it from the weather API.
		if ( empty( $api_key ) ) {
			wp_send_json_error( esc_html__( 'Error: The API key is missing, please check the widget settings!', 'adrenaline-pt' ) );

			return;
		}

		if ( empty( $latitude ) || empty( $longitude ) ) {
			wp_send_json_error( esc_html__( 'Error: The coordinates are missing, please check the widget settings!', 'adrenaline-pt' ) );

			return;
		}

		if ( $data = get_transient( AdrenalineHelpers::create_location_key( 'pt_adrenaline_weather_data', $latitude, $longitude ) ) ) {
			$ajax_response = $data;
		}
		else {
			// Initiate the Weather API class with appropriate data.
			$weather_api   = new \ProteusThemes\WeatherWidget\WeatherApi( $api_key, $latitude, $longitude );

			// Get the data from the Weather API class.
			$ajax_response = $weather_api->get_data();

			// Save the data to cache (WP transient).
			set_transient( AdrenalineHelpers::create_location_key( 'pt_adrenaline_weather_data', $latitude, $longitude ), $ajax_response, 0.5 * HOUR_IN_SECONDS );
		}

		// Send JSON back to the AJAX call in JS (weather-widget.js).
		wp_send_json( $ajax_response );
	}


	/**
	 * Set to which customizer panel the sticky menu should be appended.
	 *
	 * @param array $defaults Default settings for this filter.
	 *
	 * @return array
	 */
	public function pt_sticky_menu_theme_panel( $defaults ) {
		$defaults['panel'] = 'panel_adrenaline';

		return $defaults;
	}


	/**
	 * Change the default settings for the sticky menu.
	 *
	 * @param array $defaults Default settings for this filter.
	 *
	 * @return array
	 */
	public function pt_sticky_menu_default_settings( $defaults ) {
		$defaults['fp_bg_color'] = '#2e3b4e';

		return $defaults;
	}
}

// Single instance.
$adrenaline_filters = new AdrenalineFilters();
