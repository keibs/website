<?php
/**
 * Register sidebars for Adrenaline
 *
 * @package adrenaline-pt
 */

function adrenaline_sidebars() {
	// Blog Sidebar.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog Sidebar', 'adrenaline-pt' ),
			'id'            => 'blog-sidebar',
			'description'   => esc_html__( 'Sidebar on the blog layout.', 'adrenaline-pt' ),
			'class'         => 'blog  sidebar',
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="sidebar__headings">',
			'after_title'   => '</h4>',
		)
	);

	// Regular Page Sidebar.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Regular Page Sidebar', 'adrenaline-pt' ),
			'id'            => 'regular-page-sidebar',
			'description'   => esc_html__( 'Sidebar on the regular page.', 'adrenaline-pt' ),
			'class'         => 'sidebar',
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="sidebar__headings">',
			'after_title'   => '</h4>',
		)
	);

	// Header Left Widgets.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header Left', 'adrenaline-pt' ),
			'id'            => 'header-left-widgets',
			'description'   => esc_html__( 'Header left widget area for Text, Icon Box, Social Icons or Search.', 'adrenaline-pt' ),
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
		)
	);

	// Header Right Widgets.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header Right', 'adrenaline-pt' ),
			'id'            => 'header-right-widgets',
			'description'   => esc_html__( 'Header right widget area for Text, Icon Box, Social Icons or Search.', 'adrenaline-pt' ),
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
		)
	);

	// WooCommerce shop sidebar.
	if ( AdrenalineHelpers::is_woocommerce_active() ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Shop Sidebar', 'adrenaline-pt' ),
				'id'            => 'shop-sidebar',
				'description'   => esc_html__( 'Sidebar for the shop page', 'adrenaline-pt' ),
				'class'         => 'sidebar',
				'before_widget' => '<div class="widget  %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="sidebar__headings">',
				'after_title'   => '</h4>',
			)
		);
	}

	// Footer.
	$footer_widgets_num = count( AdrenalineHelpers::footer_widgets_layout_array() );

	// Only register if not 0.
	if ( $footer_widgets_num > 0 ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer', 'adrenaline-pt' ),
				'id'            => 'footer-widgets',
				'description'   => sprintf( esc_html__( 'Footer area works best with %d widgets. This number can be changed in the Appearance &rarr; Customize &rarr; Theme Options &rarr; Footer.', 'adrenaline-pt' ), $footer_widgets_num ),
				'before_widget' => '<div class="col-xs-12  col-lg-__col-num__"><div class="widget  %2$s">', // __col-num__ is replaced dynamically in filter 'dynamic_sidebar_params'
				'after_widget'  => '</div></div>',
				'before_title'  => '<h4 class="footer-top__heading">',
				'after_title'   => '</h4>',
			)
		);
	}
}
add_action( 'widgets_init', 'adrenaline_sidebars' );
