<?php
/**
 * The page title part of the header
 *
 * @package adrenaline-pt
 */

$adrenaline_style_attr = '';

// Regular page id.
$adrenaline_bg_id        = get_the_ID();
$adrenaline_blog_id      = absint( get_option( 'page_for_posts' ) );
$adrenaline_shop_id      = absint( get_option( 'woocommerce_shop_page_id', 0 ) );
$adrenaline_portfolio_id = absint( get_theme_mod( 'portfolio_parent_page', 0 ) );

// If blog or single post use the ID of the blog.
if ( is_home() || is_singular( 'post' ) ) {
	$adrenaline_bg_id = $adrenaline_blog_id;
}

// If woocommerce page, use the shop page id.
if ( AdrenalineHelpers::is_woocommerce_active() && is_woocommerce() ) {
	$adrenaline_bg_id = $adrenaline_shop_id;
}

// If Portfolio widget is activate use the parent page for single portfolio pages.
if ( AdrenalineHelpers::is_portfolio_plugin_active() && is_singular( 'portfolio' ) ) {
	$adrenaline_bg_id = $adrenaline_portfolio_id;
}

$show_title_area = get_field( 'show_title_area', $adrenaline_bg_id );
if ( ! $show_title_area ) {
	$show_title_area = 'yes';
}

$show_breadcrumbs = get_field( 'show_breadcrumbs', $adrenaline_bg_id );
if ( ! $show_breadcrumbs ) {
	$show_breadcrumbs = 'yes';
}

// Show/hide page title area (ACF control - single page && customizer control - all pages).
if ( 'yes' === $show_title_area && 'yes' === get_theme_mod( 'show_page_title_area', 'yes' ) ) :

	$adrenaline_style_attr = AdrenalineHelpers::page_header_background_style( $adrenaline_bg_id );

	?>

	<div class="page-header__container  js-sticky-desktop-option<?php echo ( ! is_active_sidebar( 'header-left-widgets' ) && ! is_active_sidebar( 'header-right-widgets' ) ) ? '  page-header--no-widgets' : ''; ?>"<?php echo empty( $adrenaline_style_attr ) ? '' : ' style="' . esc_attr( $adrenaline_style_attr ) . ';"'; ?>>
		<div class="container">
			<div class="page-header">
				<div class="page-header__content">
					<?php
					$adrenaline_main_tag = 'h1';
					$adrenaline_subtitle = false;

					if ( is_home() || ( is_single() && 'post' === get_post_type() ) ) {
						$adrenaline_title    = 0 === $adrenaline_blog_id ? esc_html__( 'Blog', 'adrenaline-pt' ) : get_the_title( $adrenaline_blog_id );
						$adrenaline_subtitle = get_field( 'subtitle', $adrenaline_blog_id );

						if ( is_single() ) {
							$adrenaline_main_tag = 'h2';
						}
					}
					elseif ( AdrenalineHelpers::is_woocommerce_active() && is_woocommerce() ) {
						ob_start();
						woocommerce_page_title();
						$adrenaline_title    = ob_get_clean();
						$adrenaline_subtitle = get_field( 'subtitle', $adrenaline_shop_id );

						if ( is_product() ) {
							$adrenaline_main_tag = 'h2';
						}
					}
					elseif ( AdrenalineHelpers::is_portfolio_plugin_active() && is_singular( 'portfolio' ) ) {
						$adrenaline_title    = get_the_title( $adrenaline_portfolio_id );
						$adrenaline_subtitle = get_field( 'subtitle', $adrenaline_portfolio_id );
					}
					elseif ( is_category() || is_tag() || is_author() || is_post_type_archive() || is_tax() || is_day() || is_month() || is_year() ) {
						$adrenaline_title = get_the_archive_title();
					}
					elseif ( is_search() ) {
						$adrenaline_title = esc_html__( 'Search Results For' , 'adrenaline-pt' ) . ' &quot;' . get_search_query() . '&quot;';
					}
					elseif ( is_404() ) {
						$adrenaline_title = esc_html__( 'Error 404' , 'adrenaline-pt' );
					}
					else {
						$adrenaline_title    = get_the_title();
						$adrenaline_subtitle = get_field( 'subtitle' );
					}

					?>

					<?php printf( '<%1$s class="page-header__title">%2$s</%1$s>', tag_escape( $adrenaline_main_tag ), wp_kses_post( $adrenaline_title ) ); ?>

					<?php if ( $adrenaline_subtitle ) : ?>
						<p class="page-header__subtitle"><?php echo esc_html( $adrenaline_subtitle ); ?></p>
					<?php endif; ?>
				</div>

				<?php
				if ( 'yes' === $show_breadcrumbs ) {
					get_template_part( 'template-parts/breadcrumbs' );
				}
				?>

			</div>
		</div>
	</div>
<?php endif; ?>
