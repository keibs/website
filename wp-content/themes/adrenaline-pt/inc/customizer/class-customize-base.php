<?php
/**
 * Customizer
 *
 * @package adrenaline-pt
 */

use ProteusThemes\CustomizerUtils\Setting;
use ProteusThemes\CustomizerUtils\Control;
use ProteusThemes\CustomizerUtils\CacheManager;
use ProteusThemes\CustomizerUtils\Helpers as WpUtilsHelpers;

/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 */
class Adrenaline_Customizer_Base {
	/**
	 * The singleton manager instance
	 *
	 * @see wp-includes/class-wp-customize-manager.php
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * Instance of the DynamicCSS cache manager
	 *
	 * @var ProteusThemes\CustomizerUtils\CacheManager
	 */
	private $dynamic_css_cache_manager;

	/**
	 * Holds the array for the DynamiCSS.
	 *
	 * @var array
	 */
	private $dynamic_css = array();

	/**
	 * Constructor method for this class.
	 *
	 * @param WP_Customize_Manager $wp_customize The WP customizer manager instance.
	 */
	public function __construct( WP_Customize_Manager $wp_customize ) {
		// Set the private propery to instance of wp_customize.
		$this->wp_customize = $wp_customize;

		// Set the private propery to instance of DynamicCSS CacheManager.
		$this->dynamic_css_cache_manager = new CacheManager( $this->wp_customize );

		// Init the dynamic_css property.
		$this->dynamic_css = $this->dynamic_css_init();

		// Register the settings/panels/sections/controls.
		$this->register_settings();
		$this->register_panels();
		$this->register_sections();
		$this->register_partials();
		$this->register_controls();

		/**
		 * Action and filters
		 */

		// Render the CSS and cache it to the theme_mod when the setting is saved.
		add_action( 'wp_head', array( 'ProteusThemes\CustomizerUtils\Helpers', 'add_dynamic_css_style_tag' ), 50, 0 );
		add_action( 'customize_save_after', function() {
			$adrenaline_woocommerce_selectors_filter_callable = false;

			if ( ! AdrenalineHelpers::is_woocommerce_active() ) {
				$adrenaline_woocommerce_selectors_filter_callable = function ( $css_selector ) {
					return false === strpos( $css_selector, '.woocommerce' ) && false === strpos( $css_selector, '.wc-appointments' );
				};
			}

			$this->dynamic_css_cache_manager->cache_rendered_css( $adrenaline_woocommerce_selectors_filter_callable );
		}, 10, 0 );

		// Save logo width/height dimensions.
		add_action( 'customize_save_logo_img', array( 'ProteusThemes\CustomizerUtils\Helpers', 'save_logo_dimensions' ), 10, 1 );
		add_action( 'customize_save_footer_logo_img', array( 'AdrenalineHelpers', 'save_footer_logo_dimensions' ), 10, 1 );
	}


	/**
	 * Initialization of the dynamic CSS settings with config arrays
	 *
	 * @return array
	 */
	private function dynamic_css_init() {
		$darken5   = new Setting\DynamicCSS\ModDarken( 5 );
		$darken10  = new Setting\DynamicCSS\ModDarken( 10 );
		$lighten5  = new Setting\DynamicCSS\ModLighten( 5 );

		return array(
			'main_navigation_mobile_background' => array(
				'default' => '#2e3b4e',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.main-navigation',
							),
						),
					),
				),
			),

			'main_navigation_mobile_color' => array(
				'default' => '#a3adbc',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.main-navigation a',
							),
						),
					),
				),
			),

			'main_navigation_mobile_color_hover' => array(
				'default' => '#ffffff',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.main-navigation .menu-item:focus > a',
								'.main-navigation .menu-item:hover > a',
							),
						),
					),
				),
			),

			'main_navigation_mobile_sub_color' => array(
				'default' => '#f0f0f0',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.main-navigation .sub-menu .menu-item > a',
							),
						),
					),
				),
			),

			'main_navigation_mobile_sub_color_hover' => array(
				'default' => '#ffffff',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.main-navigation .sub-menu .menu-item:hover > a',
								'.main-navigation .sub-menu .menu-item:focus > a',
							),
						),
					),
				),
			),

			'main_navigation_background' => array(
				'default' => '#2e3b4e',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.header__navigation',
								'.header__navigation-container',
							),
						),
					),
				),
			),

			'main_navigation_color' => array(
				'default' => '#a3adbc',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation a',
							),
						),
					),
				),
			),

			'main_navigation_color_hover' => array(
				'default' => '#ffffff',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation > .menu-item:focus > a',
								'.main-navigation > .menu-item:hover > a',
								'.main-navigation > .current-menu-item > a',
								'.main-navigation > .current-menu-ancestor > a',
							),
						),
					),
				),
			),

			'main_navigation_sub_bg' => array(
				'default' => '#2e3b4e',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu a',
							),
						),
					),
				),
			),

			'main_navigation_sub_color' => array(
				'default' => '#a3adbc',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu .menu-item a',
							),
						),
					),
				),
			),

			'main_navigation_sub_color_hover' => array(
				'default' => '#ffffff',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu .menu-item > a:hover',
							),
						),
					),
				),
			),

			'page_header_bg_color' => array(
				'default' => '#f0f0f0',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.page-header__container',
							),
						),
					),
				),
			),

			'page_header_color' => array(
				'default' => '#2e3b4e',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.page-header__title',
							),
						),
					),
				),
			),

			'page_header_subtitle_color' => array(
				'default' => '#666666',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.page-header__subtitle',
							),
						),
					),
				),
			),

			'breadcrumbs_color' => array(
				'default'    => '#666666',
				'css_props' => array(
					array(
						'name'      => 'color',
						'selectors' => array(
							'noop' => array(
								'.breadcrumbs a',
								'.breadcrumbs a::after',
							),
						),
					),
				),
			),

			'breadcrumbs_color_hover' => array(
				'default'    => '#ff7240',
				'css_props' => array(
					array(
						'name'      => 'color',
						'selectors' => array(
							'noop' => array(
								'.breadcrumbs a:focus',
								'.breadcrumbs a:hover',
							),
						),
					),
				),
			),

			'breadcrumbs_color_active' => array(
				'default'    => '#666666',
				'css_props' => array(
					array(
						'name'      => 'color',
						'selectors' => array(
							'noop' => array(
								'.breadcrumbs .current-item',
							),
						),
					),
				),
			),

			'text_color_content_area' => array(
				'default' => '#666666',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.content-area',
								'.content-area .icon-box',
								'.adrenaline-table',
								'.number-counter__title',
							),
						),
					),
				),
			),

			'headings_color' => array(
				'default' => '#2e3b4e',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'h1',
								'h2',
								'h3',
								'h4',
								'h5',
								'h6',
								'hentry__title',
								'.hentry__title a',
								'.time-table .widget-title',
								'.latest-news--block .latest-news__title a',
								'.latest-news--more-news',
								'.portfolio-grid__item-title',
								'.portfolio-grid__price',
								'.special-offer__title',
								'.special-offer__price',
								'.pricing-list__title',
								'.pricing-list__price',
								'.weather-current__temperature',
								'.accordion__panel .panel-title a.collapsed',
								'.accordion .more-link',
								'.masonry .article__title-link',
								'.widget_archive a',
								'.widget_pages a',
								'.widget_categories a',
								'.widget_meta a',
								'.widget_recent_comments a',
								'.widget_recent_entries a',
								'.widget_rss a',
								'.testimonial__author',
								'.number-counter__number',
								'.page-box__title a',
								'.sidebar__headings',
								'body.woocommerce-page ul.products li.product h3',
								'.woocommerce ul.products li.product h3',
								'body.woocommerce-page .entry-summary .entry-title',
								'.header-info__link',
							),
						),
					),
				),
			),

			'primary_color' => array(
				'default' => '#ff7240',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'a',
								'.accordion__panel .panel-title a:hover',
								'.main-navigation > .menu-item-has-children > a::after',
								'.person-profile__skill-rating',
								'.time-table .week-day.today',
								'.portfolio-grid__nav-item.is-active > .portfolio-grid__nav-link',
								'.weather-current__title',
								'.accordion .more-link:focus',
								'.accordion .more-link:hover',
								'.footer-bottom__center a .fa',
								'.pagination .current',
								'body.woocommerce-page ul.products li.product a:hover img',
								'.woocommerce ul.products li.product a:hover img',
								'.portfolio-grid__nav-link:focus',
								'.portfolio-grid__nav-link:hover',
								'.portfolio-grid__nav-item.is-active > .portfolio-grid__nav-link:focus',
								'.portfolio-grid__nav-item.is-active > .portfolio-grid__nav-link:hover',
								'.btn-outline-primary',
								'a.icon-box:focus .fa',
								'a.icon-box:hover .fa',
								'.pagination .page-numbers:focus',
								'.pagination .page-numbers:hover',
								'.content-area .widget_nav_menu .menu .current-menu-item > a',
								'.widget_archive .current-cat a',
								'.widget_archive .current_page_item a',
								'.widget_pages .current-cat a',
								'.widget_pages .current_page_item a',
								'.widget_categories .current-cat a',
								'.widget_categories .current_page_item a',
								'.widget_meta .current-cat a',
								'.widget_meta .current_page_item a',
								'.widget_recent_comments .current-cat a',
								'.widget_recent_comments .current_page_item a',
								'.widget_recent_entries .current-cat a',
								'.widget_recent_entries .current_page_item a',
								'.widget_rss .current-cat a',
								'.widget_rss .current_page_item a',
								'.widget_archive a::before',
								'.widget_pages a::before',
								'.widget_categories a::before',
								'.widget_meta a::before',
								'.widget_archive a:hover',
								'.widget_pages a:hover',
								'.widget_categories a:hover',
								'.widget_meta a:hover',
								'.widget_recent_comments a:hover',
								'.widget_recent_entries a:hover',
								'.widget_rss a:hover',
								'.content-area .widget_nav_menu .menu a:focus',
								'.content-area .widget_nav_menu .menu a:hover',
								'.content-area .widget_nav_menu .menu a::before',
								'.header .social-icons__link:focus',
								'.header .social-icons__link:hover',
								'.header-info__link .fa',
								'.header-info__link:focus',
								'.header-info__link:hover',
								'.accordion__panel .panel-title a',
								'body.woocommerce-page ul.products li.product a',
								'.woocommerce ul.products li.product a',
								'body.woocommerce-page nav.woocommerce-pagination ul li span.current',
								'body.woocommerce-page nav.woocommerce-pagination ul li a:focus',
								'body.woocommerce-page nav.woocommerce-pagination ul li a:hover',
								'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a',
								'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li:hover a',
								'body.woocommerce-page .widget_product_categories .product-categories li.current-cat > a',
								'body.woocommerce-page .widget_product_categories .product-categories a::before',
								'body.woocommerce-page .widget_product_categories .product-categories a:focus',
								'body.woocommerce-page .widget_product_categories .product-categories a:hover',
								'body.woocommerce-page .quantity .qty:focus',
								'.wpml-ls .wpml-ls-item-toggle::after',
							),
						),
					),
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'a:focus',
								'a:hover',
								'.btn-outline-primary:focus',
								'.btn-outline-primary:hover',
								'.weather-current__title:focus',
								'.weather-current__title:hover',
							),
						),
						'modifier'  => $darken5,
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.header__logo',
								'.main-navigation > .menu-item::before',
								'.person-profile__label',
								'.portfolio-grid__nav-link:focus::before',
								'.portfolio-grid__nav-link:hover::before',
								'.portfolio-grid__label',
								'.portfolio-grid__nav-item.is-active > .portfolio-grid__nav-link::before',
								'.special-offer__label',
								'.pricing-list__badge',
								'.adrenaline-table thead th',
								'.brochure-box',
								'.btn-outline-primary:hover',
								'.btn-outline-primary:focus',
								'.btn-outline-primary.focus',
								'.btn-outline-primary:active',
								'.btn-outline-primary.active',
								'.btn-primary',
								'.widget_calendar caption',
								'.testimonial::before',
								'.testimonial::after',
								'.number-counter__bar--progress',
								'.footer-top__logo',
								'.page-header-portfolio__label',
								'body.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle',
								'body.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range',
								'body.woocommerce-page a.button:hover',
								'body.woocommerce-page input.button:hover',
								'body.woocommerce-page input.button.alt:hover',
								'body.woocommerce-page button.button:hover',
								'body.woocommerce-page #review_form #respond input#submit',
								'body.woocommerce-page div.product form.cart .button.single_add_to_cart_button',
								'body.woocommerce-page div.product form.cart .button.single_add_to_cart_button:focus',
								'body.woocommerce-page div.product form.cart .button.single_add_to_cart_button:hover',
								'body.woocommerce-page .woocommerce-error a.button',
								'body.woocommerce-page .woocommerce-info a.button',
								'body.woocommerce-page .woocommerce-message a.button',
								'.woocommerce button.button.alt:disabled',
								'.woocommerce button.button.alt:disabled:hover',
								'.woocommerce button.button.alt:disabled[disabled]',
								'.woocommerce button.button.alt:disabled[disabled]:hover',
								'.woocommerce-cart .wc-proceed-to-checkout a.checkout-button',
								'body.woocommerce-page #payment #place_order',
								'body.woocommerce-page a.add_to_cart_button:hover',
								'.woocommerce a.add_to_cart_button:hover',
								'.timetable a.timetable__item:focus .timetable__content',
								'.timetable a.timetable__item:focus .timetable__date',
								'.timetable a.timetable__item:hover .timetable__date',
								'.timetable a.timetable__item:hover .timetable__content',
								'body.woocommerce-page .widget_product_search .search-field + input:hover',
								'body.woocommerce-page .widget_product_search .search-field + input:focus',
								'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a::before',
								'.wc-appointments-appointment-form .slot-picker li.selected a',
								'.wc-appointments-appointment-form .slot-picker li.selected:hover a',
								'.wc-appointments-date-picker .ui-datepicker td.ui-datepicker-current-day a',
								'.wc-appointments-date-picker .ui-datepicker td.ui-datepicker-current-day a:hover',
								'.wc-appointments-date-picker .ui-datepicker td.appointable-range .ui-state-default',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:focus',
								'.btn-primary:hover',
								'.brochure-box:focus',
								'.brochure-box:hover',
								'body.woocommerce-page #review_form #respond input#submit:hover',
								'.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover',
								'body.woocommerce-page #payment #place_order:hover',
								'body.woocommerce-page .woocommerce-error a.button:hover',
								'body.woocommerce-page .woocommerce-info a.button:hover',
								'body.woocommerce-page .woocommerce-message a.button:hover',
								'body.woocommerce-page #review_form #respond input#submit:hover',
								'.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover',
							),
						),
						'modifier'  => $darken5,
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:active:hover',
								'.btn-primary:active:focus',
								'.btn-primary:active.focus',
								'.btn-primary.active.focus',
								'.btn-primary.active:focus',
								'.btn-primary.active:hover',
							),
						),
						'modifier'  => $darken10,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.portfolio-grid__nav-item.is-active:first-of-type .portfolio-grid__nav-link',
								'.portfolio-grid__nav-link:focus, .portfolio-grid__nav-link:hover',
								'.btn-outline-primary',
								'.btn-outline-primary:hover',
								'.btn-outline-primary:focus',
								'.btn-outline-primary.focus',
								'.btn-outline-primary:active',
								'.btn-outline-primary.active',
								'.btn-primary',
								'.pagination .current:first-child',
								'.pagination .current',
								'.portfolio-grid__nav-item.is-active > .portfolio-grid__nav-link',
								'.portfolio-grid__nav-item:first-of-type .portfolio-grid__nav-link:focus',
								'.portfolio-grid__nav-item:first-of-type .portfolio-grid__nav-link:hover',
								'.widget_search .search-field:focus',
								'.content-area .widget_nav_menu .menu .current-menu-item > a',
								'.content-area .widget_nav_menu .menu li.current-menu-item:first-of-type > a',
								'body.woocommerce-page .widget_product_search .search-field + input:hover',
								'body.woocommerce-page .widget_product_search .search-field + input:focus',
								'body.woocommerce-page nav.woocommerce-pagination ul li span.current',
								'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a',
								'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li:hover a',
								'body.woocommerce-page .widget_product_categories .product-categories li.current-cat > a',
								'body.woocommerce-page .quantity .qty:focus',
							),
						),
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:focus',
								'.btn-primary:hover',
							),
						),
						'modifier'  => $darken5,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:active:hover',
								'.btn-primary:active:focus',
								'.btn-primary:active.focus',
								'.btn-primary.active.focus',
								'.btn-primary.active:focus',
								'.btn-primary.active:hover',
							),
						),
						'modifier'  => $darken10,
					),
				),
			),

			'dark_button_color' => array(
				'default' => '#2e3b4e',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary:focus',
								'.btn-secondary:hover',
							),
						),
						'modifier'  => $lighten5,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary',
							),
						),
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary:focus',
								'.btn-secondary:hover',
							),
						),
						'modifier'  => $lighten5,
					),
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.btn-outline-secondary',
							),
						),
					),
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.btn-outline-secondary:focus',
								'.btn-outline-secondary:hover',
							),
						),
						'modifier'  => $lighten5,
					),
				),
			),

			'light_button_color' => array(
				'default' => '#ffffff',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-light',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-light:focus',
								'.btn-light:hover',
							),
						),
						'modifier'  => $darken5,
					),
				),
			),

			'body_bg' => array(
				'default'   => '#ffffff',
				'css_props' => array(
					array(
						'name'      => 'background-color',
						'selectors' => array(
							'noop' => array(
								'body .boxed-container',
							),
						),
					),
				),
			),

			'footer_bg_color' => array(
				'default' => '#2e3b4e',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.footer-top',
							),
						),
					),
				),
			),

			'footer_title_color' => array(
				'default' => '#ffffff',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-top__heading',
							),
						),
					),
				),
			),

			'footer_text_color' => array(
				'default' => '#a3adbc',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-top',
							),
						),
					),
				),
			),

			'footer_link_color' => array(
				'default' => '#a3adbc',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-top a',
								'.footer-top .widget_nav_menu .menu a',
							),
						),
					),
				),
			),

			'footer_bottom_bg_color' => array(
				'default' => '#ffffff',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.footer-bottom',
							),
						),
					),
				),
			),

			'footer_bottom_text_color' => array(
				'default' => '#666666',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-bottom',
							),
						),
					),
				),
			),

			'footer_bottom_link_color' => array(
				'default' => '#666666',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-bottom a',
							),
						),
					),
				),
			),
		);
	}

	/**
	 * Register customizer settings
	 *
	 * @return void
	 */
	public function register_settings() {
		// Branding.
		$this->wp_customize->add_setting( 'logo_img' );
		$this->wp_customize->add_setting( 'logo2x_img' );
		$this->wp_customize->add_setting( 'header_logo_width', array( 'default' => '180' ) );

		// Page header area.
		$this->wp_customize->add_setting( 'page_header_bg_img' );
		$this->wp_customize->add_setting( 'page_header_bg_img_repeat', array( 'default' => 'repeat' ) );
		$this->wp_customize->add_setting( 'page_header_bg_img_position_x', array( 'default' => 'left' ) );
		$this->wp_customize->add_setting( 'page_header_bg_img_attachment', array( 'default' => 'scroll' ) );
		$this->wp_customize->add_setting( 'show_page_title_area', array( 'default' => 'yes' ) );

		// Typography.
		$this->wp_customize->add_setting( 'charset_setting', array( 'default' => 'latin' ) );

		// Theme layout & color.
		$this->wp_customize->add_setting( 'layout_mode', array( 'default' => 'wide' ) );
		$this->wp_customize->add_setting( 'blog_columns', array( 'default' => 6 ) );
		$this->wp_customize->add_setting( 'body_bg_img' );
		$this->wp_customize->add_setting( 'body_bg_img_repeat', array( 'default' => 'repeat' ) );
		$this->wp_customize->add_setting( 'body_bg_img_position_x', array( 'default' => 'left' ) );
		$this->wp_customize->add_setting( 'body_bg_img_attachment', array( 'default' => 'scroll' ) );

		// Shop.
		if ( AdrenalineHelpers::is_woocommerce_active() ) {
			$this->wp_customize->add_setting( 'products_per_page', array( 'default' => 9 ) );
			$this->wp_customize->add_setting( 'single_product_sidebar', array( 'default' => 'left' ) );
		}

		// Portfolio.
		if ( AdrenalineHelpers::is_portfolio_plugin_active() ) {
			$this->wp_customize->add_setting( 'portfolio_parent_page', array( 'default' => 0 ) );
			$this->wp_customize->add_setting( 'portfolio_slug', array(
				'default'           => 'portfolio',
				'sanitize_callback' => function( $title ) {
					return sanitize_title( $title, 'portfolio' );
				},
			) );
		}

		// Footer.
		$this->wp_customize->add_setting( 'footer_logo_enabled', array( 'default' => true ) );
		$this->wp_customize->add_setting( 'footer_logo_img' );
		$this->wp_customize->add_setting( 'footer_logo2x_img' );

		$this->wp_customize->add_setting( 'footer_custom_text', array( 'default' => '<div class="footer-top__text"><p class="h6">164 Edgefield St.<br>Richmond, VA 23223</p>+386 123 456<br>rush@info.com<br>adrenalin@info.com</div><div class="footer-top__social-icons">[fa icon="fa-facebook" href="#"][fa icon="fa-twitter" href="#"][fa icon="fa-linkedin" href="#"][fa icon="fa-facebook" href="#"]</div>' ) );

		$this->wp_customize->add_setting( 'footer_widgets_layout', array( 'default' => '[4,8]' ) );

		$this->wp_customize->add_setting( 'footer_bottom_left_txt', array( 'default' => '<i class="fa  fa-2x  fa-cc-paypal"></i> <i class="fa  fa-2x  fa-cc-mastercard"></i> <i class="fa  fa-2x  fa-cc-visa"></i> <i class="fa  fa-2x  fa-cc-amex"></i>' ) );
		$this->wp_customize->add_setting( 'footer_bottom_middle_left_txt', array( 'default' => '<a href="#"><i class="fa fa-map-marker"></i> FIND US ON MAP</a>' ) );
		$this->wp_customize->add_setting( 'footer_bottom_middle_right_txt', array( 'default' => '<a href="https://www.proteusthemes.com/wordpress-themes/adrenaline/">Adrenaline Theme</a> - Made by ProteusThemes' ) );
		$this->wp_customize->add_setting( 'footer_bottom_right_txt', array( 'default' => sprintf( '&copy; %s All Rights Reserved', date( 'Y' ) ) ) );

		// Custom code (css/js).
		$this->wp_customize->add_setting( 'custom_js_head' );
		$this->wp_customize->add_setting( 'custom_js_footer' );
		$this->wp_customize->add_setting( 'custom_css', array( 'default' => '' ) );

		// Migrate any existing theme CSS to the core option added in WordPress 4.7.
		if ( function_exists( 'wp_update_custom_css_post' ) ) {
			$css = get_theme_mod( 'custom_css', '' );

			if ( ! empty( $css ) ) {
				$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
				$return   = wp_update_custom_css_post( '/* Migrated CSS from old Theme Custom CSS setting: */' . PHP_EOL . $css . PHP_EOL . PHP_EOL . '/* New custom CSS: */' . PHP_EOL . $core_css );
				if ( ! is_wp_error( $return ) ) {
					// Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
					remove_theme_mod( 'custom_css' );
				}
			}

			// Add new "CSS setting" that will only notify the users that the new "Additional CSS" field is available.
			// It can't be the same name ('custom_css'), because the core control is also named 'custom_css' and it would not display the WP core "Additional CSS" control.
			$this->wp_customize->add_setting( 'pt_custom_css', array( 'default' => '' ) );
		}

		// ACF.
		$this->wp_customize->add_setting( 'show_acf', array( 'default' => 'no' ) );
		$this->wp_customize->add_setting( 'use_minified_css', array( 'default' => 'no' ) );

		// All the DynamicCSS settings.
		foreach ( $this->dynamic_css as $setting_id => $args ) {
			$this->wp_customize->add_setting(
				new Setting\DynamicCSS( $this->wp_customize, $setting_id, $args )
			);
		}
	}


	/**
	 * Panels
	 *
	 * @return void
	 */
	public function register_panels() {
		// One ProteusThemes panel to rule them all.
		$this->wp_customize->add_panel( 'panel_adrenaline', array(
			'title'       => esc_html__( '[PT] Theme Options', 'adrenaline-pt' ),
			'description' => esc_html__( 'All Adrenaline theme specific settings.', 'adrenaline-pt' ),
			'priority'    => 10,
		) );
	}


	/**
	 * Sections
	 *
	 * @return void
	 */
	public function register_sections() {
		$this->wp_customize->add_section( 'adrenaline_section_logos', array(
			'title'       => esc_html__( 'Logo', 'adrenaline-pt' ),
			'description' => sprintf( esc_html__( 'Logo for the Adrenaline theme. %3$sYou can set front page specific logo%4$s by editing your front page and setting the logo in the %1$sHeader settings%2$s tab in the %1$sFront page slider and header%2$s meta box.', 'adrenaline-pt' ) , '<i>', '</i>', '<a href="https://www.proteusthemes.com/docs/adrenaline/#logo-and-favicon" target="_blank">', '</a>' ),
			'priority'    => 10,
			'panel'       => 'panel_adrenaline',
		) );

		$this->wp_customize->add_section( 'adrenaline_section_navigation', array(
			'title'       => esc_html__( 'Navigation', 'adrenaline-pt' ),
			'description' => esc_html__( 'Navigation for the Adrenaline theme.', 'adrenaline-pt' ),
			'priority'    => 30,
			'panel'       => 'panel_adrenaline',
		) );

		$this->wp_customize->add_section( 'adrenaline_section_page_header', array(
			'title'       => esc_html__( 'Page Header Area', 'adrenaline-pt' ),
			'description' => esc_html__( 'All layout and appearance settings for the page header area (regular pages).', 'adrenaline-pt' ),
			'priority'    => 33,
			'panel'       => 'panel_adrenaline',
		) );

		$this->wp_customize->add_section( 'adrenaline_section_breadcrumbs', array(
			'title'       => esc_html__( 'Breadcrumbs', 'adrenaline-pt' ),
			'description' => esc_html__( 'All layout and appearance settings for breadcrumbs.', 'adrenaline-pt' ),
			'priority'    => 35,
			'panel'       => 'panel_adrenaline',
		) );

		$this->wp_customize->add_section( 'adrenaline_section_theme_colors', array(
			'title'       => esc_html__( 'Theme Layout &amp; Colors', 'adrenaline-pt' ),
			'priority'    => 40,
			'panel'       => 'panel_adrenaline',
		) );

		if ( AdrenalineHelpers::is_woocommerce_active() ) {
			$this->wp_customize->add_section( 'adrenaline_section_shop', array(
				'title'       => esc_html__( 'Shop', 'adrenaline-pt' ),
				'priority'    => 80,
				'panel'       => 'panel_adrenaline',
			) );
		}

		if ( AdrenalineHelpers::is_portfolio_plugin_active() ) {
			$this->wp_customize->add_section( 'adrenaline_section_portfolio', array(
				'title'       => esc_html__( 'Portfolio', 'adrenaline-pt' ),
				'priority'    => 85,
				'panel'       => 'panel_adrenaline',
			) );
		}

		$this->wp_customize->add_section( 'section_footer', array(
			'title'       => esc_html__( 'Footer', 'adrenaline-pt' ),
			'description' => esc_html__( 'All layout and appearance settings for the footer.', 'adrenaline-pt' ),
			'priority'    => 90,
			'panel'       => 'panel_adrenaline',
		) );

		$this->wp_customize->add_section( 'section_custom_code', array(
			'title'       => esc_html__( 'Custom Code' , 'adrenaline-pt' ),
			'priority'    => 100,
			'panel'       => 'panel_adrenaline',
		) );

		$this->wp_customize->add_section( 'section_other', array(
			'title'       => esc_html__( 'Other' , 'adrenaline-pt' ),
			'priority'    => 150,
			'panel'       => 'panel_adrenaline',
		) );
	}


	/**
	 * Partials for selective refresh
	 *
	 * @return void
	 */
	public function register_partials() {
		$this->wp_customize->selective_refresh->add_partial( 'dynamic_css', array(
			'selector' => 'head > #wp-utils-dynamic-css-style-tag',
			'settings' => array_keys( $this->dynamic_css ),
			'render_callback' => function() {
				return $this->dynamic_css_cache_manager->render_css();
			},
		) );
	}


	/**
	 * Controls
	 *
	 * @return void
	 */
	public function register_controls() {
		// Section: adrenaline_section_logos.
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'logo_img',
			array(
				'label'       => esc_html__( 'Logo Image', 'adrenaline-pt' ),
				'description' => esc_html__( 'Max recommended height for the logo image is 140px. You can set your logo width below.', 'adrenaline-pt' ),
				'section'     => 'adrenaline_section_logos',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'logo2x_img',
			array(
				'label'       => esc_html__( 'Retina Logo Image', 'adrenaline-pt' ),
				'description' => esc_html__( '2x logo size, for screens with high DPI.', 'adrenaline-pt' ),
				'section'     => 'adrenaline_section_logos',
			)
		) );
		$this->wp_customize->add_control(
			'header_logo_width',
			array(
				'type'        => 'number',
				'label'       => esc_html__( 'Logo width', 'adrenaline-pt' ),
				'description' => esc_html__( 'Change the logo area width (min: 180px, max: 400px).', 'adrenaline-pt' ),
				'section'     => 'adrenaline_section_logos',
				'input_attrs' => array(
					'min'  => 180,
					'max'  => 400,
					'step' => 5,
				),
			)
		);

		// Section: adrenaline_section_navigation.
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_background',
			array(
				'priority' => 125,
				'label'    => esc_html__( 'Main navigation background color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_color',
			array(
				'priority' => 130,
				'label'    => esc_html__( 'Main navigation link color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_color_hover',
			array(
				'priority' => 132,
				'label'    => esc_html__( 'Main navigation link hover color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_sub_bg',
			array(
				'priority' => 160,
				'label'    => esc_html__( 'Main navigation submenu background', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_sub_color',
			array(
				'priority' => 170,
				'label'    => esc_html__( 'Main navigation submenu link color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_sub_color_hover',
			array(
				'priority' => 180,
				'label'    => esc_html__( 'Main navigation submenu link hover color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_color',
			array(
				'priority' => 190,
				'label'    => esc_html__( 'Main navigation link color (mobile)', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_color_hover',
			array(
				'priority' => 192,
				'label'    => esc_html__( 'Main navigation link hover color (mobile)', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_sub_color',
			array(
				'priority' => 194,
				'label'    => esc_html__( 'Main navigation submenu link color (mobile)', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_sub_color_hover',
			array(
				'priority' => 195,
				'label'    => esc_html__( 'Main navigation submenu link hover color (mobile)', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_background',
			array(
				'priority' => 188,
				'label'    => esc_html__( 'Main navigation background color (mobile)', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_navigation',
			)
		) );

		// Section: adrenaline_section_page_header.
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'page_header_bg_color',
			array(
				'priority' => 10,
				'label'    => esc_html__( 'Page Header background color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_page_header',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'page_header_bg_img',
			array(
				'priority' => 20,
				'label'    => esc_html__( 'Page Header Background Image', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_page_header',
			)
		) );
		$this->wp_customize->add_control( 'page_header_bg_img_repeat', array(
			'priority'        => 21,
			'label'           => esc_html__( 'Page Header Background Repeat', 'adrenaline-pt' ),
			'section'         => 'adrenaline_section_page_header',
			'type'            => 'radio',
			'active_callback' => function() {
				return WpUtilsHelpers::is_theme_mod_not_empty( 'page_header_bg_img' );
			},
			'choices'         => array(
				'no-repeat'  => esc_html__( 'No Repeat', 'adrenaline-pt' ),
				'repeat'     => esc_html__( 'Tile', 'adrenaline-pt' ),
				'repeat-x'   => esc_html__( 'Tile Horizontally', 'adrenaline-pt' ),
				'repeat-y'   => esc_html__( 'Tile Vertically', 'adrenaline-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'page_header_bg_img_position_x', array(
			'priority'        => 22,
			'label'           => esc_html__( 'Page Header Background Position', 'adrenaline-pt' ),
			'section'         => 'adrenaline_section_page_header',
			'type'            => 'radio',
			'active_callback' => function() {
				return WpUtilsHelpers::is_theme_mod_not_empty( 'page_header_bg_img' );
			},
			'choices'         => array(
				'left'       => esc_html__( 'Left', 'adrenaline-pt' ),
				'center'     => esc_html__( 'Center', 'adrenaline-pt' ),
				'right'      => esc_html__( 'Right', 'adrenaline-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'page_header_bg_img_attachment', array(
			'priority'        => 23,
			'label'           => esc_html__( 'Page Header Background Attachment', 'adrenaline-pt' ),
			'section'         => 'adrenaline_section_page_header',
			'type'            => 'radio',
			'active_callback' => function() {
				return WpUtilsHelpers::is_theme_mod_not_empty( 'page_header_bg_img' );
			},
			'choices'         => array(
				'scroll'     => esc_html__( 'Scroll', 'adrenaline-pt' ),
				'fixed'      => esc_html__( 'Fixed', 'adrenaline-pt' ),
			),
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'page_header_color',
			array(
				'priority' => 30,
				'label'    => esc_html__( 'Page Header title color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_page_header',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'page_header_subtitle_color',
			array(
				'priority' => 31,
				'label'    => esc_html__( 'Page Header subtitle color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_page_header',
			)
		) );
		$this->wp_customize->add_control( 'show_page_title_area', array(
			'type'        => 'select',
			'priority'    => 35,
			'label'       => esc_html__( 'Show page title area', 'adrenaline-pt' ),
			'description' => esc_html__( 'This will hide the page title area on all pages. You can also hide individual page headers in page settings. To remove breadcrumbs from all pages, please deactivate the Breadcrumb NavXT plugin.', 'adrenaline-pt' ),
			'section'     => 'adrenaline_section_page_header',
			'choices'     => array(
				'yes' => esc_html__( 'Show', 'adrenaline-pt' ),
				'no'  => esc_html__( 'Hide', 'adrenaline-pt' ),
			),
		) );

		// Section: adrenaline_section_breadcrumbs.
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_color',
			array(
				'priority' => 45,
				'label'    => esc_html__( 'Breadcrumbs text color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_breadcrumbs',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_color_hover',
			array(
				'priority' => 50,
				'label'    => esc_html__( 'Breadcrumbs hover text color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_breadcrumbs',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_color_active',
			array(
				'priority' => 50,
				'label'    => esc_html__( 'Breadcrumbs active text color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_breadcrumbs',
			)
		) );

		// Section: adrenaline_section_theme_colors.
		$this->wp_customize->add_control( 'layout_mode', array(
			'type'     => 'select',
			'priority' => 10,
			'label'    => esc_html__( 'Layout', 'adrenaline-pt' ),
			'section'  => 'adrenaline_section_theme_colors',
			'choices'  => array(
				'wide'  => esc_html__( 'Wide', 'adrenaline-pt' ),
				'boxed' => esc_html__( 'Boxed', 'adrenaline-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'blog_columns', array(
			'type'        => 'select',
			'priority'    => 20,
			'label'       => esc_html__( 'Blog columns', 'adrenaline-pt' ),
			'description' => esc_html__( 'Number of columns on blog pages (blog, archives and search) on desktop.', 'adrenaline-pt' ),
			'section'     => 'adrenaline_section_theme_colors',
			'choices'     => array(
				12 => 1,
				6 => 2,
				4 => 3,
				3 => 4,
			),
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'text_color_content_area',
			array(
				'priority' => 30,
				'label'    => esc_html__( 'Text color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'headings_color',
			array(
				'priority' => 33,
				'label'    => esc_html__( 'Headings color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'primary_color',
			array(
				'priority' => 34,
				'label'    => esc_html__( 'Primary color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'dark_button_color',
			array(
				'priority' => 36,
				'label'    => esc_html__( 'Dark button background color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'light_button_color',
			array(
				'priority' => 37,
				'label'    => esc_html__( 'Light button background color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'body_bg_img',
			array(
				'priority' => 40,
				'label'    => esc_html__( 'Body background image', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( 'body_bg_img_repeat', array(
			'priority'        => 41,
			'label'           => esc_html__( 'Body background repeat', 'adrenaline-pt' ),
			'section'         => 'adrenaline_section_theme_colors',
			'type'            => 'radio',
			'active_callback' => function() {
				return WpUtilsHelpers::is_theme_mod_not_empty( 'body_bg_img' );
			},
			'choices'         => array(
				'no-repeat' => esc_html__( 'No Repeat', 'adrenaline-pt' ),
				'repeat'    => esc_html__( 'Tile', 'adrenaline-pt' ),
				'repeat-x'  => esc_html__( 'Tile Horizontally', 'adrenaline-pt' ),
				'repeat-y'  => esc_html__( 'Tile Vertically', 'adrenaline-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'body_bg_img_position_x', array(
			'priority'        => 42,
			'label'           => esc_html__( 'Body background position', 'adrenaline-pt' ),
			'section'         => 'adrenaline_section_theme_colors',
			'type'            => 'radio',
			'active_callback' => function() {
				return WpUtilsHelpers::is_theme_mod_not_empty( 'body_bg_img' );
			},
			'choices'         => array(
				'left'   => esc_html__( 'Left', 'adrenaline-pt' ),
				'center' => esc_html__( 'Center', 'adrenaline-pt' ),
				'right'  => esc_html__( 'Right', 'adrenaline-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'body_bg_img_attachment', array(
			'priority'        => 43,
			'label'           => esc_html__( 'Body background attachment', 'adrenaline-pt' ),
			'section'         => 'adrenaline_section_theme_colors',
			'type'            => 'radio',
			'active_callback' => function() {
				return WpUtilsHelpers::is_theme_mod_not_empty( 'body_bg_img' );
			},
			'choices'         => array(
				'scroll' => esc_html__( 'Scroll', 'adrenaline-pt' ),
				'fixed'  => esc_html__( 'Fixed', 'adrenaline-pt' ),
			),
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'body_bg',
			array(
				'priority' => 45,
				'label'    => esc_html__( 'Body background color', 'adrenaline-pt' ),
				'section'  => 'adrenaline_section_theme_colors',
			)
		) );

		// Section: adrenaline_section_shop.
		if ( AdrenalineHelpers::is_woocommerce_active() ) {
			$this->wp_customize->add_control( 'products_per_page', array(
					'label'   => esc_html__( 'Number of products per page', 'adrenaline-pt' ),
					'section' => 'adrenaline_section_shop',
				)
			);
			$this->wp_customize->add_control( 'single_product_sidebar', array(
					'label'   => esc_html__( 'Sidebar on single product page', 'adrenaline-pt' ),
					'section' => 'adrenaline_section_shop',
					'type'    => 'select',
					'choices' => array(
						'none'  => esc_html__( 'No sidebar', 'adrenaline-pt' ),
						'left'  => esc_html__( 'Left', 'adrenaline-pt' ),
						'right' => esc_html__( 'Right', 'adrenaline-pt' ),
					),
				)
			);
		}

		// Section: adrenaline_section_portfolio.
		if ( AdrenalineHelpers::is_portfolio_plugin_active() ) {
			$this->wp_customize->add_control( 'portfolio_parent_page', array(
					'label'       => esc_html__( 'Portfolio parent page', 'adrenaline-pt' ),
					'description' => esc_html__( 'Page with all the portfolio items.', 'adrenaline-pt' ),
					'section'     => 'adrenaline_section_portfolio',
					'type'        => 'dropdown-pages',
				)
			);
			$this->wp_customize->add_control( 'portfolio_slug', array(
					'label'       => esc_html__( 'Portfolio slug', 'adrenaline-pt' ),
					'description' => esc_html__( 'This is used in the URL part. After changing this, you must save the permalink settings again in Settings &rarr; Permalinks.', 'adrenaline-pt' ),
					'section'     => 'adrenaline_section_portfolio',
				)
			);
		}

		// Section: section_footer.
		$this->wp_customize->add_control( 'footer_logo_enabled', array(
			'priority' => 1,
			'type'     => 'checkbox',
			'label'    => esc_html__( 'Enable footer logo', 'adrenaline-pt' ),
			'section'  => 'section_footer',
		) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'footer_logo_img',
			array(
				'priority'        => 2,
				'label'           => esc_html__( 'Logo image', 'adrenaline-pt' ),
				'section'         => 'section_footer',
				'active_callback' => array( $this, 'is_footer_logo_enabled' ),
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'footer_logo2x_img',
			array(
				'priority'        => 3,
				'label'           => esc_html__( 'Retina logo image', 'adrenaline-pt' ),
				'description'     => esc_html__( '2x logo size, for screens with high DPI.', 'adrenaline-pt' ),
				'section'         => 'section_footer',
				'active_callback' => array( $this, 'is_footer_logo_enabled' ),
			)
		) );
		$this->wp_customize->add_control( 'footer_custom_text', array(
			'priority'    => 4,
			'type'        => 'textarea',
			'label'       => esc_html__( 'Custom text', 'adrenaline-pt' ),
			'description' => esc_html__( 'Custom text area next to the footer logo, before the widgets area.', 'adrenaline-pt' ),
			'section'     => 'section_footer',
		) );
		$this->wp_customize->add_control( new Control\LayoutBuilder(
			$this->wp_customize,
			'footer_widgets_layout',
			array(
				'priority'    => 5,
				'label'       => esc_html__( 'Footer widgets layout', 'adrenaline-pt' ),
				'description' => esc_html__( 'Select number of widget you want in the footer and then with the slider rearrange the layout', 'adrenaline-pt' ),
				'section'     => 'section_footer',
				'input_attrs' => array(
					'min'     => 0,
					'max'     => 12,
					'step'    => 1,
					'maxCols' => 6,
				),
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bg_color',
			array(
				'priority' => 10,
				'label'    => esc_html__( 'Footer background color', 'adrenaline-pt' ),
				'section'  => 'section_footer',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_title_color',
			array(
				'priority' => 30,
				'label'    => esc_html__( 'Footer widget title color', 'adrenaline-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_text_color',
			array(
				'priority' => 31,
				'label'    => esc_html__( 'Footer text color', 'adrenaline-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_link_color',
			array(
				'priority' => 32,
				'label'    => esc_html__( 'Footer link color', 'adrenaline-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_bg_color',
			array(
				'priority' => 35,
				'label'    => esc_html__( 'Footer bottom background color', 'adrenaline-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_text_color',
			array(
				'priority' => 36,
				'label'    => esc_html__( 'Footer bottom text color', 'adrenaline-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_link_color',
			array(
				'priority' => 37,
				'label'    => esc_html__( 'Footer bottom link color', 'adrenaline-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( 'footer_bottom_left_txt', array(
			'type'        => 'text',
			'priority'    => 110,
			'label'       => esc_html__( 'Footer bottom text on the left', 'adrenaline-pt' ),
			'description' => esc_html__( 'You can use HTML: a, span, i, em, strong, img.', 'adrenaline-pt' ),
			'section'     => 'section_footer',
		) );

		$this->wp_customize->add_control( 'footer_bottom_middle_left_txt', array(
			'type'        => 'text',
			'priority'    => 115,
			'label'       => esc_html__( 'Footer bottom text in the center (left)', 'adrenaline-pt' ),
			'description' => esc_html__( 'You can use HTML: a, span, i, em, strong, img.', 'adrenaline-pt' ),
			'section'     => 'section_footer',
		) );

		$this->wp_customize->add_control( 'footer_bottom_middle_right_txt', array(
			'type'        => 'text',
			'priority'    => 120,
			'label'       => esc_html__( 'Footer bottom text in the center (right)', 'adrenaline-pt' ),
			'description' => esc_html__( 'You can use HTML: a, span, i, em, strong, img.', 'adrenaline-pt' ),
			'section'     => 'section_footer',
		) );

		$this->wp_customize->add_control( 'footer_bottom_right_txt', array(
			'type'        => 'text',
			'priority'    => 125,
			'label'       => esc_html__( 'Footer bottom text on the right', 'adrenaline-pt' ),
			'description' => esc_html__( 'You can use HTML: a, span, i, em, strong, img.', 'adrenaline-pt' ),
			'section'     => 'section_footer',
		) );

		// Section: section_custom_code.
		if ( function_exists( 'wp_update_custom_css_post' ) ) {
			// Show the notice of custom CSS setting migration.
			$this->wp_customize->add_control( 'pt_custom_css', array(
				'type'        => 'hidden',
				'label'       => esc_html__( 'Custom CSS', 'adrenaline-pt' ),
				'description' => esc_html__( 'This field is obsolete. The existing code was migrated to the "Additional CSS" field, that can be found in the root of the customizer. This new "Additional CSS" field is a WordPress core field and was introduced in WP version 4.7.', 'adrenaline-pt' ),
				'section'     => 'section_custom_code',
			) );
		}
		else {
			$this->wp_customize->add_control( 'custom_css', array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Custom CSS', 'adrenaline-pt' ),
				'description' => sprintf( esc_html__( '%1$s How to find CSS classes %2$s in the theme.', 'adrenaline-pt' ), '<a href="https://www.youtube.com/watch?v=V2aAEzlvyDc" target="_blank">', '</a>' ),
				'section'     => 'section_custom_code',
			) );
		}

		$this->wp_customize->add_control( 'custom_js_head', array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Custom JavaScript (head)', 'adrenaline-pt' ),
			'description' => esc_html__( 'You have to include the &lt;script&gt;&lt;/script&gt; tags as well. Paste your Google Analytics tracking code here.', 'adrenaline-pt' ),
			'section'     => 'section_custom_code',
		) );

		$this->wp_customize->add_control( 'custom_js_footer', array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Custom JavaScript (footer)', 'adrenaline-pt' ),
			'description' => esc_html__( 'You have to include the &lt;script&gt;&lt;/script&gt; tags as well.', 'adrenaline-pt' ),
			'section'     => 'section_custom_code',
		) );

		// Section: section_other.
		$this->wp_customize->add_control( 'show_acf', array(
			'type'        => 'select',
			'label'       => esc_html__( 'Show ACF admin panel?', 'adrenaline-pt' ),
			'description' => esc_html__( 'If you want to use ACF and need the ACF admin panel set this to <strong>Yes</strong>. Do not change if you do not know what you are doing.', 'adrenaline-pt' ),
			'section'     => 'section_other',
			'choices'     => array(
				'no'  => esc_html__( 'No', 'adrenaline-pt' ),
				'yes' => esc_html__( 'Yes', 'adrenaline-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'use_minified_css', array(
			'type'    => 'select',
			'label'   => esc_html__( 'Use minified theme CSS', 'adrenaline-pt' ),
			'section' => 'section_other',
			'choices' => array(
				'no'  => esc_html__( 'No', 'adrenaline-pt' ),
				'yes' => esc_html__( 'Yes', 'adrenaline-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'charset_setting', array(
			'type'     => 'select',
			'label'    => esc_html__( 'Character set for Google Fonts', 'adrenaline-pt' ),
			'section'  => 'section_other',
			'choices'  => array(
				'latin'        => 'Latin',
				'latin-ext'    => 'Latin Extended',
				'cyrillic'     => 'Cyrillic',
				'cyrillic-ext' => 'Cyrillic Extended',
				'vietnamese'   => 'Vietnamese',
				'greek'        => 'Greek',
				'greek-ext'    => 'Greek Extended',
			),
		) );
	}

	/**
	 * Returns if the footer logo is enabled.
	 *
	 * Used by the footer_logo_img and footer_logo2x_img controls.
	 *
	 * @return boolean
	 */
	public function is_footer_logo_enabled() {
		return get_theme_mod( 'footer_logo_enabled' );
	}
}
